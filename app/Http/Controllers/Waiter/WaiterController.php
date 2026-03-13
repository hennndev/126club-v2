<?php

namespace App\Http\Controllers\Waiter;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\GeneralSetting;
use App\Models\InventoryItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\TableReservation;
use App\Models\TableSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WaiterController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('waiter.scanner');
    }

    public function scanner(): View
    {
        return view('waiter.scanner');
    }

    public function activeTables(): View
    {
        $sessions = TableSession::with(['table.area', 'customer.profile', 'billing'])
            ->withSum(['orders as total_spent' => fn ($q) => $q->whereNotIn('status', ['cancelled'])], 'total')
            ->where('status', 'active')
            ->orderByDesc('checked_in_at')
            ->get();

        $areas = Area::where('is_active', true)->orderBy('sort_order')->get();
        $generalSettings = GeneralSetting::instance();

        return view('waiter.active-tables', compact('sessions', 'areas', 'generalSettings'));
    }

    public function updatePax(Request $request, TableSession $session): JsonResponse
    {
        $validated = $request->validate([
            'pax' => 'required|integer|min:1|max:9999',
        ]);

        $session->update(['pax' => $validated['pax']]);

        return response()->json(['success' => true, 'pax' => $session->pax]);
    }

    public function pos(): View
    {
        $products = InventoryItem::whereIn('category_type', ['food', 'bar', 'beverage'])
            ->where('is_active', true)
            ->get()
            ->map(fn ($item) => [
                'id' => 'item_'.$item->id,
                'name' => $item->name,
                'category' => $item->category_type,
                'price' => (float) $item->price,
                'type' => 'item',
            ])
            ->sortBy('name')
            ->values();

        $activeSessions = TableSession::with(['table.area', 'customer.profile'])
            ->where('status', 'active')
            ->orderByDesc('checked_in_at')
            ->get();

        $cart = session('pos_cart', []);
        $selectedCounter = session('pos_selected_counter');

        return view('waiter.pos', compact('products', 'activeSessions', 'cart', 'selectedCounter'));
    }

    public function notifications(): View
    {
        $waiter = auth()->user();

        // Personal notifications for this waiter (assigned bookings)
        $assignedNotifications = $waiter->unreadNotifications()
            ->where('type', \App\Notifications\WaiterAssignedNotification::class)
            ->latest()
            ->get();

        $pendingCheckIns = TableReservation::with(['table.area', 'customer.profile'])
            ->where('status', 'confirmed')
            ->orderByDesc('created_at')
            ->get();

        $recentCheckIns = TableSession::with(['table.area', 'customer.profile'])
            ->where('status', 'active')
            ->whereDate('checked_in_at', today())
            ->orderByDesc('checked_in_at')
            ->take(10)
            ->get();

        // Mark assigned notifications as read when viewing this page
        $waiter->unreadNotifications()
            ->where('type', \App\Notifications\WaiterAssignedNotification::class)
            ->update(['read_at' => now()]);

        return view('waiter.notifications', compact('pendingCheckIns', 'recentCheckIns', 'assignedNotifications'));
    }

    public function transactions(Request $request): View
    {
        $waiter = auth()->user();
        $tab = $request->get('tab', 'active');

        $query = TableSession::with(['table.area', 'customer.profile', 'billing'])
            ->where('waiter_id', $waiter->id);

        if ($tab === 'active') {
            $query->where('status', 'active');
        } else {
            $query->whereIn('status', ['completed', 'force_closed']);
        }

        $sessions = $query->orderByDesc('checked_in_at')->get();

        $activeCount = TableSession::where('waiter_id', $waiter->id)->where('status', 'active')->count();
        $historyCount = TableSession::where('waiter_id', $waiter->id)->whereIn('status', ['completed', 'force_closed'])->count();

        return view('waiter.transactions', compact('sessions', 'tab', 'activeCount', 'historyCount'));
    }

    public function transactionChecker(Request $request): View
    {
        $waiter = auth()->user();
        $tab = $request->get('tab', 'proses');

        // Show orders for tables currently assigned to this waiter
        $assignedTableIds = TableSession::where('waiter_id', $waiter->id)
            ->where('status', 'active')
            ->pluck('table_id');

        $query = Order::with([
            'items.inventoryItem',
            'tableSession.table',
            'tableSession.customer.profile',
            'customer.user',
        ])->whereNotIn('status', ['cancelled'])
            ->whereHas('tableSession', fn ($q) => $q->whereIn('table_id', $assignedTableIds));

        if ($tab === 'proses') {
            $query->whereIn('status', ['pending', 'preparing', 'ready']);
        } elseif ($tab === 'selesai') {
            $query->where('status', 'completed');
        }

        $orders = $query->latest('ordered_at')->get();

        $prosesCount = Order::whereNotIn('status', ['cancelled', 'completed'])
            ->whereHas('tableSession', fn ($q) => $q->whereIn('table_id', $assignedTableIds))
            ->count();

        $selesaiCount = Order::where('status', 'completed')
            ->whereHas('tableSession', fn ($q) => $q->whereIn('table_id', $assignedTableIds))
            ->count();

        return view('waiter.transaction-checker', compact('orders', 'tab', 'prosesCount', 'selesaiCount'));
    }

    public function transactionCheckerCheckItem(OrderItem $item): JsonResponse
    {
        $item->update([
            'status' => 'served',
            'served_at' => now(),
        ]);

        $item->order->updateStatus();

        $order = Order::with('items')->find($item->order_id);
        $servedCount = $order->items->where('status', 'served')->count();
        $totalCount = $order->items->where('status', '!=', 'cancelled')->count();

        return response()->json([
            'success' => true,
            'order_status' => $order->status,
            'served_count' => $servedCount,
            'total_count' => $totalCount,
        ]);
    }

    public function transactionCheckerCheckAll(Order $order): JsonResponse
    {
        $order->items()
            ->whereNotIn('status', ['cancelled', 'served'])
            ->update(['status' => 'served', 'served_at' => now()]);

        $order->updateStatus();

        return response()->json([
            'success' => true,
            'order_status' => $order->fresh()->status,
        ]);
    }

    public function settings(): View
    {
        return view('waiter.settings');
    }
}
