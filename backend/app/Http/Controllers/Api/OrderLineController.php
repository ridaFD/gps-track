<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderLineResource;
use App\Models\Order;
use App\Models\OrderLine;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class OrderLineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Order $order)
    {
        $order->load('lines');
        return OrderLineResource::collection($order->lines);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Order $order)
    {
        $validated = $request->validate([
            'width_mm' => ['required', 'numeric', 'min:0.01'],
            'height_mm' => ['required', 'numeric', 'min:0.01'],
            'label' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'integer', 'min:0'],
        ]);

        $line = new OrderLine($validated);
        $line->order()->associate($order);
        if (!isset($validated['position'])) {
            $line->position = ($order->lines()->max('position') ?? -1) + 1;
        }
        $line->save();
        return new OrderLineResource($line, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order, OrderLine $line)
    {
        abort_unless($line->order_id === $order->id, Response::HTTP_NOT_FOUND);
        return new OrderLineResource($line);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order, OrderLine $line)
    {
        abort_unless($line->order_id === $order->id, Response::HTTP_NOT_FOUND);
        $validated = $request->validate([
            'width_mm' => ['nullable', 'numeric', 'min:0.01'],
            'height_mm' => ['nullable', 'numeric', 'min:0.01'],
            'label' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'integer', 'min:0'],
        ]);
        $line->fill($validated)->save();
        return new OrderLineResource($line);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order, OrderLine $line)
    {
        abort_unless($line->order_id === $order->id, Response::HTTP_NOT_FOUND);
        if ($line->image_path) {
            Storage::disk('public')->delete($line->image_path);
        }
        $line->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function uploadImage(Request $request, Order $order, OrderLine $line)
    {
        abort_unless($line->order_id === $order->id, Response::HTTP_NOT_FOUND);
        $validated = $request->validate([
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ]);
        if ($line->image_path) {
            Storage::disk('public')->delete($line->image_path);
        }
        $path = $validated['image']->store('order-lines', 'public');
        $line->image_path = $path;
        $line->save();
        return new OrderLineResource($line);
    }

    public function reorder(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order' => ['required', 'array'],
            'order.*.id' => ['required', 'integer', 'distinct'],
            'order.*.position' => ['required', 'integer', 'min:0'],
        ]);

        $idToPosition = collect($validated['order'])->pluck('position', 'id');
        $lines = $order->lines()->whereIn('id', $idToPosition->keys())->get();
        foreach ($lines as $line) {
            $line->position = $idToPosition[$line->id];
            $line->save();
        }
        return OrderLineResource::collection($order->fresh('lines')->lines);
    }
}
