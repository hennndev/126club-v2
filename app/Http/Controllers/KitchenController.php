<?php

namespace App\Http\Controllers;

use App\Models\KitchenOrder;
use App\Models\KitchenOrderItem;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index(Request $request)
    {
        $query = KitchenOrder::with(['customer.profile', 'table.area', 'items.recipe']);

        // Filter by status
        if ($request->has('status') && in_array($request->status, ['baru', 'proses', 'selesai'])) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->get();

        // Calculate stats
        $totalOrders = KitchenOrder::count();
        $baruOrders = KitchenOrder::where('status', 'baru')->count();
        $prosesOrders = KitchenOrder::where('status', 'proses')->count();
        $selesaiOrders = KitchenOrder::where('status', 'selesai')->count();

        return view('kitchen.index', compact('orders', 'totalOrders', 'baruOrders', 'prosesOrders', 'selesaiOrders'));
    }

    public function toggleItem(KitchenOrderItem $item)
    {
        $item->update(['is_completed' => !$item->is_completed]);
        $item->kitchenOrder->updateProgress();

        return back()->with('success', 'Item status updated!');
    }

    public function completeAll(KitchenOrder $order)
    {
        $order->items()->update(['is_completed' => true]);
        $order->update([
            'progress' => 100,
            'status' => 'selesai'
        ]);

        return back()->with('success', 'Semua item telah diselesaikan!');
    }
}

