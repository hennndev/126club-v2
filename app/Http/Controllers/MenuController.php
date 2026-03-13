<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Services\AccurateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function __construct(protected AccurateService $accurateService) {}

    public function index(): \Illuminate\View\View
    {
        $inventoryItems = InventoryItem::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'code', 'name', 'unit']);

        $categoryTypes = InventoryItem::distinct()->orderBy('category_type')->pluck('category_type');

        return view('menus.index', compact('inventoryItems', 'categoryTypes'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'no' => 'required|string|max:100',
            'name' => 'required|string|max:255',
            'category_type' => 'nullable|string|max:255',
            'unit' => 'required|string|max:50',
            'selling_price' => 'required|numeric|min:0',
            'detail_group' => 'nullable|array',
            'detail_group.*.item_no' => 'required|string|max:100',
            'detail_group.*.detail_name' => 'required|string|max:255',
            'detail_group.*.quantity' => 'required|numeric|min:0.001',
        ]);

        $detailGroup = collect($validated['detail_group'] ?? [])
            ->values()
            ->map(fn ($row, $seq) => [
                'itemNo' => $row['item_no'],
                'detailName' => $row['detail_name'],
                'quantity' => $row['quantity'],
            ])
            ->toArray();

        $payload = [
            'no' => $validated['no'],
            'name' => $validated['name'],
            'itemType' => 'GROUP',
            'unit1Name' => $validated['unit'],
            'unitPrice' => $validated['selling_price'],
            'detailGroup' => $detailGroup,
        ];

        if (! empty($validated['category_type'])) {
            $payload['itemCategoryName'] = $validated['category_type'];
        }

        try {
            $result = $this->accurateService->saveItem($payload);

            $accurateId = $result['d']['id'] ?? null;

            if ($accurateId !== null) {
                InventoryItem::updateOrCreate(
                    ['code' => $validated['no']],
                    [
                        'accurate_id' => $accurateId,
                        'name' => $validated['name'],
                        'category_type' => $validated['category_type'] ?? '',
                        'price' => $validated['selling_price'],
                        'stock_quantity' => 0,
                        'unit' => $validated['unit'],
                        'is_active' => true,
                    ]
                );
            }

            return response()->json(['success' => true, 'data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }
}
