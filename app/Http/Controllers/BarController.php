<?php

namespace App\Http\Controllers;

use App\Models\BarOrder;
use App\Models\BarOrderItem;
use Illuminate\Http\Request;

class BarController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        
        $query = BarOrder::with([
            'customer.profile',
            'table.area',
            'items.recipe'
        ])->orderBy('created_at', 'desc');
        
        if ($status === 'dalam-proses') {
            $query->where('status', 'proses');
        } elseif ($status === 'selesai') {
            $query->where('status', 'selesai');
        }
        
        $orders = $query->get();
        
        // Calculate stats
        $stats = [
            'total' => BarOrder::count(),
            'baru' => BarOrder::where('status', 'baru')->count(),
            'proses' => BarOrder::where('status', 'proses')->count(),
            'selesai' => BarOrder::where('status', 'selesai')->count(),
        ];
        
        // Count by filter
        $counts = [
            'semua' => BarOrder::count(),
            'dalam_proses' => BarOrder::where('status', 'proses')->count(),
            'selesai' => BarOrder::where('status', 'selesai')->count(),
        ];
        
        return view('bar.index', compact('orders', 'stats', 'counts', 'status'));
    }

    public function toggleItem($itemId)
    {
        $item = BarOrderItem::findOrFail($itemId);
        $item->is_completed = !$item->is_completed;
        $item->save();
        
        // Update order progress
        $item->barOrder->updateProgress();
        
        return back();
    }

    public function completeAll($orderId)
    {
        $order = BarOrder::with('items')->findOrFail($orderId);
        
        // Mark all items as completed
        $order->items()->update(['is_completed' => true]);
        
        // Update order progress
        $order->updateProgress();
        
        return back();
    }
}
