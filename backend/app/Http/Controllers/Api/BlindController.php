<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlindResource;
use App\Models\Blind;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BlindController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Blind::query();

        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
        }

        // Filter by color
        if ($request->has('color')) {
            $query->where('color', 'like', '%' . $request->color . '%');
        }

        // Filter by low stock (must be > 0 and <= threshold)
        if ($request->has('low_stock')) {
            $query->whereColumn('stock_qty', '<=', 'low_stock_threshold')
                  ->where('stock_qty', '>', 0);
        }

        // Filter by out of stock
        if ($request->has('out_of_stock')) {
            $query->where('stock_qty', 0);
        }

        // Filter by has details
        if ($request->has('has_details')) {
            $query->where('has_details', filter_var($request->has_details, FILTER_VALIDATE_BOOLEAN));
        }

        // Load relationships
        $query->with(['primaryImage', 'blindImages']);

        $blinds = $query->latest()->paginate($request->get('per_page', 20));
        return BlindResource::collection($blinds);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'color' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:7',
            'description' => 'nullable|string',
            'image_path' => 'nullable|string',
            'stock_qty' => 'nullable|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'has_details' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $blind = Blind::create($validated);
        $blind->load(['primaryImage', 'blindImages']);

        return new BlindResource($blind, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $blind = Blind::with(['primaryImage', 'blindImages'])->findOrFail($id);
        return new BlindResource($blind);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $blind = Blind::findOrFail($id);
        
        $validated = $request->validate([
            'color' => 'sometimes|required|string|max:255',
            'color_code' => 'nullable|string|max:7',
            'description' => 'nullable|string',
            'image_path' => 'nullable|string',
            'stock_qty' => 'nullable|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'has_details' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $blind->fill($validated)->save();
        $blind->load(['primaryImage', 'blindImages']);

        return new BlindResource($blind);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $blind = Blind::findOrFail($id);
        $blind->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Get stock status summary
     */
    public function stockSummary()
    {
        $summary = [
            'total' => Blind::count(),
            'active' => Blind::where('is_active', true)->count(),
            'in_stock' => Blind::whereColumn('stock_qty', '>', 'low_stock_threshold')->count(),
            'low_stock' => Blind::whereColumn('stock_qty', '<=', 'low_stock_threshold')->where('stock_qty', '>', 0)->count(),
            'out_of_stock' => Blind::where('stock_qty', 0)->count(),
        ];

        return response()->json($summary);
    }

    /**
     * Get unique blind colors for dropdown selection
     */
    public function getColors()
    {
        $colors = Blind::select('color')
            ->distinct()
            ->whereNotNull('color')
            ->where('color', '!=', '')
            ->orderBy('color')
            ->pluck('color')
            ->values()
            ->toArray();

        return response()->json($colors);
    }
}

