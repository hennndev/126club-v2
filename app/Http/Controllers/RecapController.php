<?php

namespace App\Http\Controllers;

use App\Models\BarOrderItem;
use App\Models\KitchenOrderItem;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RecapController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'date' => ['nullable', 'date'],
            'start_datetime' => ['nullable', 'date'],
            'end_datetime' => ['nullable', 'date', 'after_or_equal:start_datetime'],
        ]);

        [$startAt, $endAt] = $this->resolveRange($validated);
        $recapData = $this->buildRecapData($startAt, $endAt);

        return view('recap.index', $recapData);
    }

    public function export(Request $request): BinaryFileResponse
    {
        $validated = $request->validate([
            'date' => ['nullable', 'date'],
            'start_datetime' => ['nullable', 'date'],
            'end_datetime' => ['nullable', 'date', 'after_or_equal:start_datetime'],
        ]);

        [$startAt, $endAt] = $this->resolveRange($validated);
        $recapData = $this->buildRecapData($startAt, $endAt);
        $rows = [
            ['Rekapan End Day'],
            ['Rentang', $recapData['selectedStartDatetime'].' - '.$recapData['selectedEndDatetime']],
            [],
            ['Ringkasan'],
            ['Transaksi Kasir', $recapData['cashierCount']],
            ['Total Penjualan Kasir', $recapData['cashierRevenue']],
            ['Item Keluar Kitchen', $recapData['kitchenQtyTotal']],
            ['Item Keluar Bar', $recapData['barQtyTotal']],
            [],
            ['Kasir (Harga Ditampilkan)'],
            ['Tanggal & Jam', 'No. Transaksi', 'Customer', 'Metode Pembayaran', 'Qty Item', 'Total'],
        ];

        foreach ($recapData['cashierTransactions'] as $transaction) {
            $rows[] = [
                $transaction['datetime'],
                $transaction['order_number'],
                $transaction['customer_name'],
                $transaction['payment_method'],
                $transaction['items_count'],
                $transaction['total'],
            ];
        }

        $rows[] = [];
        $rows[] = ['Item Keluar Kitchen'];
        $rows[] = ['Tanggal & Jam', 'Order', 'Item', 'Qty'];
        foreach ($recapData['kitchenItems'] as $item) {
            $rows[] = [
                $item['datetime'],
                $item['order_number'],
                $item['item_name'],
                $item['qty'],
            ];
        }

        $rows[] = [];
        $rows[] = ['Item Keluar Bar'];
        $rows[] = ['Tanggal & Jam', 'Order', 'Item', 'Qty'];
        foreach ($recapData['barItems'] as $item) {
            $rows[] = [
                $item['datetime'],
                $item['order_number'],
                $item['item_name'],
                $item['qty'],
            ];
        }

        $export = new class($rows) implements FromArray
        {
            public function __construct(private array $rows) {}

            public function array(): array
            {
                return $this->rows;
            }
        };

        return Excel::download($export, 'rekapan-'.$startAt->format('Ymd_Hi').'-'.$endAt->format('Ymd_Hi').'.xlsx');
    }

    private function buildRecapData(Carbon $startAt, Carbon $endAt): array
    {
        $cashierTransactions = Order::query()
            ->with(['items', 'tableSession.billing', 'tableSession.customer.profile', 'customer.user'])
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startAt, $endAt): void {
                $query->whereBetween('ordered_at', [$startAt, $endAt])
                    ->orWhere(function ($fallbackQuery) use ($startAt, $endAt): void {
                        $fallbackQuery->whereNull('ordered_at')
                            ->whereBetween('created_at', [$startAt, $endAt]);
                    });
            })
            ->orderByRaw('COALESCE(ordered_at, created_at) ASC')
            ->get()
            ->map(function (Order $order): array {
                $eventTime = $order->ordered_at ?? $order->created_at;
                $customerName = $order->tableSession?->customer?->profile?->name
                    ?? $order->tableSession?->customer?->name
                    ?? $order->customer?->user?->name
                    ?? 'Walk-in';
                $paymentMode = $order->payment_mode ?? $order->tableSession?->billing?->payment_mode;
                $paymentMethod = $order->payment_method ?? $order->tableSession?->billing?->payment_method;

                return [
                    'timestamp' => $eventTime,
                    'datetime' => $eventTime?->format('d/m/Y H:i') ?? '-',
                    'order_number' => $order->order_number,
                    'customer_name' => $customerName,
                    'payment_method' => $this->formatPaymentMethod($paymentMethod, $paymentMode),
                    'items_count' => $order->items->count(),
                    'total' => (float) $order->total,
                ];
            })
            ->values();

        $kitchenItems = KitchenOrderItem::query()
            ->with(['inventoryItem', 'kitchenOrder.order'])
            ->whereHas('kitchenOrder', function ($query) use ($startAt, $endAt): void {
                $query->whereBetween('created_at', [$startAt, $endAt]);
            })
            ->get()
            ->map(function (KitchenOrderItem $item): array {
                $eventTime = $item->kitchenOrder?->order?->ordered_at
                    ?? $item->kitchenOrder?->created_at
                    ?? $item->created_at;

                return [
                    'timestamp' => $eventTime,
                    'datetime' => $eventTime?->format('d/m/Y H:i') ?? '-',
                    'order_number' => $item->kitchenOrder?->order_number ?? '-',
                    'item_name' => $item->inventoryItem?->name ?? 'Unknown Item',
                    'qty' => (int) $item->quantity,
                ];
            })
            ->sortBy(fn (array $event) => $event['timestamp'] ?? now())
            ->values();

        $barItems = BarOrderItem::query()
            ->with(['inventoryItem', 'barOrder.order'])
            ->whereHas('barOrder', function ($query) use ($startAt, $endAt): void {
                $query->whereBetween('created_at', [$startAt, $endAt]);
            })
            ->get()
            ->map(function (BarOrderItem $item): array {
                $eventTime = $item->barOrder?->order?->ordered_at
                    ?? $item->barOrder?->created_at
                    ?? $item->created_at;

                return [
                    'timestamp' => $eventTime,
                    'datetime' => $eventTime?->format('d/m/Y H:i') ?? '-',
                    'order_number' => $item->barOrder?->order_number ?? '-',
                    'item_name' => $item->inventoryItem?->name ?? 'Unknown Item',
                    'qty' => (int) $item->quantity,
                ];
            })
            ->sortBy(fn (array $event) => $event['timestamp'] ?? now())
            ->values();

        return [
            'selectedDate' => $startAt->toDateString(),
            'selectedStartDatetime' => $startAt->format('Y-m-d\TH:i'),
            'selectedEndDatetime' => $endAt->format('Y-m-d\TH:i'),
            'cashierTransactions' => $cashierTransactions,
            'cashierCount' => $cashierTransactions->count(),
            'cashierRevenue' => (float) $cashierTransactions->sum('total'),
            'kitchenItems' => $kitchenItems,
            'kitchenQtyTotal' => (int) $kitchenItems->sum('qty'),
            'barItems' => $barItems,
            'barQtyTotal' => (int) $barItems->sum('qty'),
        ];
    }

    private function formatPaymentMethod(?string $paymentMethod, ?string $paymentMode): string
    {
        if ($paymentMode === 'split') {
            return 'Split Bill';
        }

        return match (strtolower((string) $paymentMethod)) {
            'cash' => 'Tunai',
            'debit' => 'Debit',
            'kredit' => 'Kredit',
            default => filled($paymentMethod) ? strtoupper((string) $paymentMethod) : '-',
        };
    }

    /**
     * @param  array{date?: string|null, start_datetime?: string|null, end_datetime?: string|null}  $validated
     * @return array{0: Carbon, 1: Carbon}
     */
    private function resolveRange(array $validated): array
    {
        if (! empty($validated['start_datetime']) && ! empty($validated['end_datetime'])) {
            return [
                Carbon::parse($validated['start_datetime'])->seconds(0),
                Carbon::parse($validated['end_datetime'])->seconds(59),
            ];
        }

        if (! empty($validated['date'])) {
            $date = Carbon::parse($validated['date']);

            return [$date->copy()->startOfDay(), $date->copy()->endOfDay()];
        }

        return [now()->startOfDay(), now()->endOfDay()];
    }
}
