<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WaiterPerformanceController extends Controller
{
    public function index(Request $request): View
    {
        $period = $request->get('period', 'today');
        $mode = $request->get('mode', 'individual');
        $waiterId = $request->get('waiter_id');

        $dateRange = $this->getDateRange($period);

        $waiters = User::whereHas('roles', fn ($q) => $q->where('name', 'Waiter/Server'))
            ->whereHas('internalUser', fn ($q) => $q->where('is_active', true))
            ->with(['internalUser.area'])
            ->get();

        $selectedWaiter = null;
        $stats = null;
        $topProducts = collect();
        $recentSessions = collect();
        $allWaitersStats = collect();
        $rank = null;

        if ($mode === 'individual') {
            $selectedWaiter = $waiters->firstWhere('id', $waiterId) ?? $waiters->first();

            if ($selectedWaiter) {
                // Orders credited to sessions this waiter handled
                $ordersBase = DB::table('orders')
                    ->join('table_sessions', 'orders.table_session_id', '=', 'table_sessions.id')
                    ->where('table_sessions.waiter_id', $selectedWaiter->id)
                    ->where('orders.status', '!=', 'cancelled')
                    ->whereBetween('orders.created_at', [$dateRange['start'], $dateRange['end']]);

                $totalOrderRevenue = (clone $ordersBase)->sum('orders.total');
                $totalTransactions = (clone $ordersBase)->count();
                $avgPerTransaction = $totalTransactions > 0 ? $totalOrderRevenue / $totalTransactions : 0;

                // Sessions handled in period
                $sessionsBase = DB::table('table_sessions')
                    ->where('waiter_id', $selectedWaiter->id)
                    ->whereBetween('checked_in_at', [$dateRange['start'], $dateRange['end']]);

                $customersHandled = (clone $sessionsBase)->count();
                $completedSessions = (clone $sessionsBase)->where('status', 'completed')->count();

                // Total billing revenue (orders + min charge) from their completed sessions
                $sessionRevenue = DB::table('billings')
                    ->join('table_sessions', 'billings.table_session_id', '=', 'table_sessions.id')
                    ->where('table_sessions.waiter_id', $selectedWaiter->id)
                    ->where('billings.billing_status', 'paid')
                    ->whereBetween('table_sessions.checked_in_at', [$dateRange['start'], $dateRange['end']])
                    ->sum('billings.grand_total');

                // Avg session duration from completed sessions
                $completedRows = DB::table('table_sessions')
                    ->where('waiter_id', $selectedWaiter->id)
                    ->whereNotNull('checked_in_at')
                    ->whereNotNull('checked_out_at')
                    ->whereBetween('checked_in_at', [$dateRange['start'], $dateRange['end']])
                    ->select('checked_in_at', 'checked_out_at')
                    ->get();

                $avgDurationMinutes = $completedRows->isNotEmpty()
                    ? (int) round($completedRows->avg(fn ($r) => abs(
                        \Carbon\Carbon::parse($r->checked_out_at)->diffInMinutes(\Carbon\Carbon::parse($r->checked_in_at))
                    )))
                    : 0;

                // Rank by session revenue among all waiters
                $allRevenues = [];
                foreach ($waiters as $w) {
                    $allRevenues[$w->id] = DB::table('billings')
                        ->join('table_sessions', 'billings.table_session_id', '=', 'table_sessions.id')
                        ->where('table_sessions.waiter_id', $w->id)
                        ->where('billings.billing_status', 'paid')
                        ->whereBetween('table_sessions.checked_in_at', [$dateRange['start'], $dateRange['end']])
                        ->sum('billings.grand_total');
                }
                arsort($allRevenues);
                $rank = array_search($selectedWaiter->id, array_keys($allRevenues)) + 1;

                $stats = compact(
                    'totalOrderRevenue', 'totalTransactions', 'avgPerTransaction',
                    'customersHandled', 'completedSessions', 'sessionRevenue', 'avgDurationMinutes'
                );

                // Top 5 products from sessions assigned to this waiter
                $topProducts = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->join('table_sessions', 'orders.table_session_id', '=', 'table_sessions.id')
                    ->where('table_sessions.waiter_id', $selectedWaiter->id)
                    ->where('orders.status', '!=', 'cancelled')
                    ->where('order_items.status', '!=', 'cancelled')
                    ->whereBetween('orders.created_at', [$dateRange['start'], $dateRange['end']])
                    ->select(
                        'order_items.item_name',
                        DB::raw('SUM(order_items.quantity) as total_qty'),
                        DB::raw('SUM(order_items.subtotal) as total_revenue')
                    )
                    ->groupBy('order_items.item_name')
                    ->orderByDesc('total_qty')
                    ->limit(5)
                    ->get();

                // Recent sessions handled (with customer name, table, billing total, duration)
                $recentSessions = DB::table('table_sessions')
                    ->join('tables', 'table_sessions.table_id', '=', 'tables.id')
                    ->leftJoin('billings', 'billings.table_session_id', '=', 'table_sessions.id')
                    ->leftJoin('users', 'table_sessions.customer_id', '=', 'users.id')
                    ->where('table_sessions.waiter_id', $selectedWaiter->id)
                    ->whereBetween('table_sessions.checked_in_at', [$dateRange['start'], $dateRange['end']])
                    ->select(
                        'table_sessions.id',
                        'table_sessions.session_code',
                        'table_sessions.checked_in_at',
                        'table_sessions.checked_out_at',
                        'table_sessions.status',
                        'tables.table_number',
                        'billings.grand_total',
                        'billings.orders_total',
                        'billings.tax_percentage',
                        'billings.discount_amount',
                        'billings.billing_status',
                        'users.name as customer_name'
                    )
                    ->orderByDesc('table_sessions.checked_in_at')
                    ->limit(10)
                    ->get();
            }
        } else {
            // All waiters comparison
            foreach ($waiters as $w) {
                $sessQ = DB::table('table_sessions')
                    ->where('waiter_id', $w->id)
                    ->whereBetween('checked_in_at', [$dateRange['start'], $dateRange['end']]);

                $ordQ = DB::table('orders')
                    ->join('table_sessions', 'orders.table_session_id', '=', 'table_sessions.id')
                    ->where('table_sessions.waiter_id', $w->id)
                    ->where('orders.status', '!=', 'cancelled')
                    ->whereBetween('orders.created_at', [$dateRange['start'], $dateRange['end']]);

                $sessionRevenue = DB::table('billings')
                    ->join('table_sessions', 'billings.table_session_id', '=', 'table_sessions.id')
                    ->where('table_sessions.waiter_id', $w->id)
                    ->where('billings.billing_status', 'paid')
                    ->whereBetween('table_sessions.checked_in_at', [$dateRange['start'], $dateRange['end']])
                    ->sum('billings.grand_total');

                $customersHandled = (clone $sessQ)->count();
                $txCount = (clone $ordQ)->count();

                $allWaitersStats->push((object) [
                    'user' => $w,
                    'sessionRevenue' => $sessionRevenue,
                    'customersHandled' => $customersHandled,
                    'totalTransactions' => $txCount,
                    'avgPerCustomer' => $customersHandled > 0 ? $sessionRevenue / $customersHandled : 0,
                ]);
            }
            $allWaitersStats = $allWaitersStats->sortByDesc('sessionRevenue')->values();
        }

        return view('waiter-performance.index', compact(
            'period', 'mode', 'waiters', 'selectedWaiter', 'stats',
            'topProducts', 'recentSessions', 'allWaitersStats', 'rank', 'dateRange'
        ));
    }

    private function getDateRange(string $period): array
    {
        return match ($period) {
            'week' => [
                'start' => now()->startOfWeek()->toDateTimeString(),
                'end' => now()->endOfWeek()->toDateTimeString(),
            ],
            'month' => [
                'start' => now()->startOfMonth()->toDateTimeString(),
                'end' => now()->endOfMonth()->toDateTimeString(),
            ],
            default => [
                'start' => now()->startOfDay()->toDateTimeString(),
                'end' => now()->endOfDay()->toDateTimeString(),
            ],
        };
    }
}
