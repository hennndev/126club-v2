<?php

namespace App\Http\Controllers;

use App\Models\BomRecipe;
use App\Models\InventoryItem;
use App\Models\TableSession;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index(Request $request)
    {
        // Get BOM recipes with category_type = 'food' or 'bar'
        $bomQuery = BomRecipe::with('inventoryItem')
            ->whereHas('inventoryItem', function($q) {
                $q->whereIn('category_type', ['food', 'bar']);
            });

        // Get inventory items with category_type = 'drink'
        $inventoryQuery = InventoryItem::where('category_type', 'drink');

        // Search functionality
        if ($request->filled('search')) {
            $bomQuery->whereHas('inventoryItem', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
            $inventoryQuery->where('name', 'like', '%' . $request->search . '%');
        }

        // Map BOM recipes to product format
        $bomProducts = $bomQuery->get()->map(function ($bom) {
            return [
                'id' => 'bom_' . $bom->id,
                'bom_id' => $bom->id,
                'name' => $bom->inventoryItem->name ?? 'Unknown',
                'category' => 'drink',
                'price' => $bom->selling_price,
                'stock' => $bom->inventoryItem->quantity ?? 0,
                'type' => 'bom'
            ];
        });

        // Map inventory items to product format
        $inventoryProducts = $inventoryQuery->get()->map(function ($item) {
            return [
                'id' => 'item_' . $item->id,
                'item_id' => $item->id,
                'name' => $item->name,
                'category' => $item->category_type,
                'price' => $item->unit_price ?? 0,
                'stock' => $item->quantity ?? 0,
                'type' => 'item'
            ];
        });

        // Combine both collections and reset keys
        $products = $bomProducts->merge($inventoryProducts)->values();

        // Get cart from session
        $cart = session()->get('pos_cart', []);
        $cartItems = collect($cart)->map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ];
        });

        $cartTotal = $cartItems->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
        
        // Get active table sessions for booking customers
        $tableSessions = TableSession::with(['customer', 'table.area'])
            ->where('status', 'active')
            ->whereNotNull('checked_in_at')
            ->whereNull('checked_out_at')
            ->get();
        
        return view('pos.index', compact('products', 'cartItems', 'cartTotal', 'tableSessions'));
    }
    
    public function addToCart(Request $request, $productId)
    {
        // Check if it's a BOM or inventory item
        if (str_starts_with($productId, 'bom_')) {
            // BOM Recipe
            $bomId = str_replace('bom_', '', $productId);
            $bom = BomRecipe::with('inventoryItem')->find($bomId);
            
            if (!$bom || !$bom->inventoryItem || !in_array($bom->inventoryItem->category_type, ['food', 'bar'])) {
                return back()->with('error', 'Product not found');
            }
            
            $product = [
                'id' => $productId,
                'name' => $bom->inventoryItem->name,
                'price' => $bom->selling_price,
                'type' => 'bom'
            ];
        } else {
            // Inventory Item
            $itemId = str_replace('item_', '', $productId);
            $inventoryItem = InventoryItem::where('id', $itemId)
                ->where('category_type', 'drink')
                ->first();

            if (!$inventoryItem) {
                return back()->with('error', 'Product not found');
            }

            $product = [
                'id' => $productId,
                'name' => $inventoryItem->name,
                'price' => $inventoryItem->unit_price ?? 0,
                'type' => 'item'
            ];
        }
        
        $cart = session()->get('pos_cart', []);
        
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            $cart[$productId] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1
            ];
        }
        
        session()->put('pos_cart', $cart);
        
        return back();
    }
    
    public function updateCartQuantity(Request $request, $productId)
    {
        $cart = session()->get('pos_cart', []);
        $action = $request->input('action');
        
        if (isset($cart[$productId])) {
            if ($action === 'increase') {
                $cart[$productId]['quantity']++;
            } elseif ($action === 'decrease') {
                $cart[$productId]['quantity']--;
                if ($cart[$productId]['quantity'] <= 0) {
                    unset($cart[$productId]);
                }
            }
        }
        
        session()->put('pos_cart', $cart);
        
        return back();
    }
    
    public function removeFromCart($productId)
    {
        $cart = session()->get('pos_cart', []);
        
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }
        
        session()->put('pos_cart', $cart);
        
        return back();
    }
    
    public function clearCart()
    {
        session()->forget('pos_cart');
        
        return back();
    }
    
    public function checkout(Request $request)
    {
        $request->validate([
            'customer_user_id' => 'required',
            'table_id' => 'required',
            'payment_method' => 'required|in:cash,credit-card,debit-card,e-wallet'
        ]);
        
        $cart = session()->get('pos_cart', []);
        
        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong!');
        }
        
        // For demo purposes, just clear the cart and show success
        session()->forget('pos_cart');
        
        return back()->with('success', 'Order berhasil diproses! (Demo Mode - Tidak menyimpan ke database)');
    }
}
