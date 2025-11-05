<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderBlindResource;
use App\Models\Order;
use App\Models\OrderBlind;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderBlindController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Order $order)
    {
        $order->load('blinds');
        return OrderBlindResource::collection($order->blinds);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Order $order)
    {
        $validated = $request->validate([
            'qty' => 'required|integer|min:1',
            'width_m' => 'required|numeric|min:0.01',
            'height_m' => 'required|numeric|min:0.01',
            'note' => 'nullable|string',
            'stock_alert' => 'nullable|boolean',
            'stock_alert_reason' => 'nullable|string',
            'image_path' => 'nullable|string',
            'calc_multiplier' => 'nullable|integer|in:10,11',
            'extra_charge' => 'nullable|numeric|min:0',
        ]);

        $blind = new OrderBlind($validated);
        $blind->order()->associate($order);
        $blind->save();

        return new OrderBlindResource($blind, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order, OrderBlind $blind)
    {
        abort_unless($blind->order_id === $order->id, Response::HTTP_NOT_FOUND);
        return new OrderBlindResource($blind);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order, OrderBlind $blind)
    {
        abort_unless($blind->order_id === $order->id, Response::HTTP_NOT_FOUND);
        
        $validated = $request->validate([
            'qty' => 'sometimes|required|integer|min:1',
            'width_m' => 'sometimes|required|numeric|min:0.01',
            'height_m' => 'sometimes|required|numeric|min:0.01',
            'note' => 'nullable|string',
            'stock_alert' => 'nullable|boolean',
            'stock_alert_reason' => 'nullable|string',
            'image_path' => 'nullable|string',
            'calc_multiplier' => 'nullable|integer|in:10,11',
            'extra_charge' => 'nullable|numeric|min:0',
        ]);

        $blind->fill($validated)->save();
        return new OrderBlindResource($blind);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order, OrderBlind $blind)
    {
        abort_unless($blind->order_id === $order->id, Response::HTTP_NOT_FOUND);
        $blind->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Upload image for blind
     */
    public function uploadImage(Request $request, Order $order, OrderBlind $blind)
    {
        abort_unless($blind->order_id === $order->id, Response::HTTP_NOT_FOUND);
        
        $validated = $request->validate([
            'image' => 'required|file|mimes:jpg,jpeg,png,webp|max:8192',
        ]);

        if ($blind->image_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($blind->image_path);
        }

        $path = $validated['image']->store('order-blinds', 'public');
        $blind->image_path = $path;
        $blind->save();

        return new OrderBlindResource($blind);
    }
}

