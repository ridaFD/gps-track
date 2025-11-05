<?php

namespace App\Orchid\Screens;

use App\Models\Order;
use App\Models\OrderBlind;
use App\Orchid\Layouts\OrderEditRows;
use App\Orchid\Layouts\OrderBlindsRows;
use App\Orchid\Layouts\OrderLinesTable;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderEditScreen extends Screen
{
    public ?Order $orderModel = null;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Order $order = null): iterable
    {
        $this->orderModel = $order ?? new Order();
        if ($this->orderModel->exists) {
            $this->orderModel->load(['lines', 'blinds']);
        }
        $blindsList = $this->orderModel->exists ? $this->orderModel->blinds : collect();
        
        // Format blinds data for Group layout (both formats for compatibility)
        $blindsData = [];
        foreach ($blindsList as $index => $b) {
            // Calculate size in m² (width * height since inputs are in meters)
            $sizeM2 = ($b->width_m ?? 0) * ($b->height_m ?? 0);
            
            $blindsData[$index] = [
                'Width (M)' => $b->width_m,
                'Height (M)' => $b->height_m,
                'Note' => $b->note,
                'Qty' => $b->qty ?? 1,
                'Size (m²)' => number_format($sizeM2, 2),
                'Multiplier' => $b->calc_multiplier,
                'Extra' => $b->extra_charge,
                'Total' => $b->total_price,
                'width' => $b->width_m,
                'height' => $b->height_m,
                'note' => $b->note,
                'qty' => $b->qty ?? 1,
                'size_m2' => number_format($sizeM2, 2),
                'multiplier' => $b->calc_multiplier,
                'extra' => $b->extra_charge,
                'total' => $b->total_price,
                'stock_alert' => $b->stock_alert ?? false,
                'stock_alert_reason' => $b->stock_alert_reason,
                'image_id' => $b->image_attachment_id ? (int) $b->image_attachment_id : null,
            ];
        }
        
        return [
            'order' => $this->orderModel->toArray(),
            'lines' => $this->orderModel->exists ? $this->orderModel->lines : collect(),
            'blinds' => $blindsData,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->orderModel && $this->orderModel->exists ? 'Edit Order' : 'Create Order';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Save')
                ->icon('bs.save')
                ->method('save'),
            Button::make('Remove')
                ->icon('bs.trash')
                ->method('remove')
                ->canSee($this->orderModel && $this->orderModel->exists),
            Button::make('Prepare Shipping Details')
                ->icon('bs.box-arrow-up-right')
                ->canSee($this->orderModel && $this->orderModel->exists)
                ->method('prepareShippingDetails'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::view('orchid.order-shipping-helper', [
                'orderModel' => $this->orderModel,
            ])->canSee($this->orderModel && $this->orderModel->exists),
            OrderEditRows::class,
            Layout::view('orchid.customer-check-js'),
            OrderBlindsRows::class,
            Layout::view('orchid.blinds-rows'),
            OrderLinesTable::class,
        ];
    }

    /**
     * Normalize phone number for comparison (remove spaces, dashes, parentheses, etc.)
     *
     * @param string|null $phone
     * @return string|null
     */
    protected function normalizePhone(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }
        
        // Remove all non-digit characters except +
        $normalized = preg_replace('/[^\d+]/', '', $phone);
        
        return $normalized ?: null;
    }

    /**
     * Check if a customer already exists based on phone or name
     *
     * @param string|null $phone
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string|null $customerName
     * @return array Returns array with 'exists' => bool, 'orders' => collection, 'message' => string
     */
    protected function checkCustomerExists(?string $phone, ?string $firstName, ?string $lastName, ?string $customerName): array
    {
        $existingOrders = collect();
        $message = '';

        // If phone is provided, check by phone (primary identifier)
        if (!empty($phone)) {
            $normalizedPhone = $this->normalizePhone($phone);
            
            // Get all orders and filter in memory for normalized phone matching
            // This allows matching phones stored in different formats
            $allOrders = Order::when($this->orderModel && $this->orderModel->exists, function ($query) {
                    $query->where('id', '!=', $this->orderModel->id);
                })
                ->whereNotNull('customer_phone')
                ->where('customer_phone', '!=', '')
                ->get();
            
            \Log::info('[DEBUG] checkCustomerExists - Phone check', [
                'original_phone' => $phone,
                'normalized_phone' => $normalizedPhone,
                'total_orders_found' => $allOrders->count()
            ]);
            
            $existingOrders = $allOrders->filter(function ($order) use ($normalizedPhone) {
                $orderPhone = $this->normalizePhone($order->customer_phone);
                return $orderPhone && $orderPhone === $normalizedPhone;
            });
            
            \Log::info('[DEBUG] checkCustomerExists - Matching orders', [
                'matching_count' => $existingOrders->count()
            ]);
            
            if ($existingOrders->isNotEmpty()) {
                $count = $existingOrders->count();
                $message = "A customer with phone number '{$phone}' already exists with {$count} existing order(s).";
                return [
                    'exists' => true,
                    'orders' => $existingOrders,
                    'message' => $message,
                ];
            }
        }

        // If no phone, check by name
        $composedName = trim(($firstName ?? '') . ' ' . ($lastName ?? '')) ?: $customerName;
        if (!empty($composedName)) {
            $existingOrders = Order::where(function ($query) use ($composedName, $firstName, $lastName, $customerName) {
                // Check customer_name (composed or stored)
                $query->where('customer_name', $composedName)
                    ->orWhere(function ($q) use ($firstName, $lastName) {
                        $q->where('customer_first_name', $firstName)
                          ->where('customer_last_name', $lastName);
                    })
                    ->orWhere(function ($q) use ($customerName) {
                        if ($customerName) {
                            $q->where('customer_name', $customerName);
                        }
                    });
            })
            ->where(function ($query) {
                // Only match if phone is empty/null (to avoid false positives)
                $query->whereNull('customer_phone')
                    ->orWhere('customer_phone', '');
            })
            ->when($this->orderModel && $this->orderModel->exists, function ($query) {
                $query->where('id', '!=', $this->orderModel->id);
            })
            ->get();

            if ($existingOrders->isNotEmpty()) {
                $count = $existingOrders->count();
                $message = "A customer with name '{$composedName}' already exists with {$count} existing order(s).";
                return [
                    'exists' => true,
                    'orders' => $existingOrders,
                    'message' => $message,
                ];
            }
        }

        return [
            'exists' => false,
            'orders' => collect(),
            'message' => '',
        ];
    }

    /**
     * AJAX endpoint to check customer existence in real-time
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkCustomer(Request $request): JsonResponse
    {
        $phone = $request->input('phone');
        $firstName = $request->input('firstName');
        $lastName = $request->input('lastName');
        $customerName = $request->input('customerName');
        $orderId = $request->input('orderId');
        
        \Log::info('[DEBUG] checkCustomer called', [
            'phone' => $phone,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'customerName' => $customerName,
            'orderId' => $orderId,
            'normalized_phone' => $this->normalizePhone($phone)
        ]);
        
        // Set the orderModel if orderId is provided (for edit mode)
        if ($orderId) {
            $this->orderModel = Order::find($orderId);
        } else {
            $this->orderModel = new Order();
        }
        
        $customerCheck = $this->checkCustomerExists($phone, $firstName, $lastName, $customerName);
        
        \Log::info('[DEBUG] checkCustomer result', [
            'exists' => $customerCheck['exists'],
            'message' => $customerCheck['message'],
            'orders_count' => $customerCheck['orders']->count()
        ]);
        
        if ($customerCheck['exists']) {
            $ordersList = $customerCheck['orders']->map(function ($o) {
                return $o->reference . ' (' . $o->created_at->format('Y-m-d') . ')';
            })->implode(', ');
            
            return response()->json([
                'exists' => true,
                'message' => $customerCheck['message'] . ' Orders: ' . $ordersList,
                'orders' => $customerCheck['orders']->map(function ($o) {
                    return [
                        'id' => $o->id,
                        'reference' => $o->reference,
                        'date' => $o->created_at->format('Y-m-d'),
                    ];
                })->values(),
            ]);
        }
        
        return response()->json([
            'exists' => false,
            'message' => '',
            'orders' => [],
        ]);
    }

    public function save(Order $order): RedirectResponse
    {
        $data = request()->get('order');
        
        // Check if customer exists before creating a new order
        if (!$order->exists) {
            $phone = $data['customer_phone'] ?? null;
            $firstName = $data['customer_first_name'] ?? null;
            $lastName = $data['customer_last_name'] ?? null;
            $customerName = $data['customer_name'] ?? null;
            
            $customerCheck = $this->checkCustomerExists($phone, $firstName, $lastName, $customerName);
            
            if ($customerCheck['exists']) {
                // Show warning but allow to proceed
                $ordersList = $customerCheck['orders']->map(function ($o) {
                    return $o->reference . ' (' . $o->created_at->format('Y-m-d') . ')';
                })->implode(', ');
                
                Toast::warning(
                    $customerCheck['message'] . ' Orders: ' . $ordersList . '. Proceeding with new order creation.'
                );
            }
            
            // Generate a simple sequential reference
            $lastOrder = Order::orderBy('id', 'desc')->first();
            $nextNumber = $lastOrder ? $lastOrder->id + 1 : 1;
            $order->reference = 'ORD-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
        }

        // Compose customer_name from first/last for backward compatibility
        $composedName = trim(($data['customer_first_name'] ?? '') . ' ' . ($data['customer_last_name'] ?? '')) ?: null;

        $order->fill([
            'customer_name' => $composedName,
            'customer_phone' => $data['customer_phone'] ?? null,
            'customer_first_name' => $data['customer_first_name'] ?? null,
            'customer_last_name' => $data['customer_last_name'] ?? null,
            'customer_address' => $data['customer_address'] ?? null,
            'customer_city' => $data['customer_city'] ?? null,
            'pick_up_in_store' => isset($data['pick_up_in_store']) && $data['pick_up_in_store'],
            'shipping_cost' => $data['shipping_cost'] ?? 0,
            'notes' => $data['notes'] ?? null,
            'status' => $data['status'] ?? 'draft',
        ])->save();

        // Save order blinds (for multiple blinds per order)
        // Fields are structured as: blinds.0.width, blinds.0.height, etc.
        $blindsData = request()->get('blinds', []);
        $blindImages = request()->get('blind_images', []);
        
        // Debug: Log what we're receiving
        \Log::info('Blinds data received:', ['blinds' => $blindsData, 'images' => $blindImages]);
        
        // Ensure order is saved and has an ID before creating blinds
        if (!$order->exists) {
            $order->save();
        }
        
        if (!empty($blindsData) && is_array($blindsData)) {
            // Delete existing blinds first
            $order->blinds()->delete();
            
            foreach ($blindsData as $index => $blindRow) {
                if (is_array($blindRow)) {
                    // Get values from indexed structure: blinds.0.width, blinds.0.height, etc.
                    $blindWidth = isset($blindRow['width']) ? (float) $blindRow['width'] : 0;
                    $blindHeight = isset($blindRow['height']) ? (float) $blindRow['height'] : 0;
                    $blindNote = isset($blindRow['note']) ? $blindRow['note'] : null;
                    $blindQty = isset($blindRow['qty']) ? (int) $blindRow['qty'] : 1;
                    $blindMultiplier = isset($blindRow['multiplier']) ? (int) $blindRow['multiplier'] : 10;
                    $blindExtra = isset($blindRow['extra']) ? (float) $blindRow['extra'] : 0;
                    $stockAlert = isset($blindRow['stock_alert']) && $blindRow['stock_alert'];
                    $stockAlertReason = isset($blindRow['stock_alert_reason']) ? $blindRow['stock_alert_reason'] : null;
                    
                    // Only create if we have valid dimensions
                    if ($blindWidth > 0 && $blindHeight > 0) {
                        // Calculate area since width/height are now in meters
                        $areaSquareMeters = $blindWidth * $blindHeight;
                        
                        // Apply minimum total logic
                        $basePrice = $areaSquareMeters * $blindMultiplier;
                        if ($areaSquareMeters < 2) {
                            $minimum = $blindMultiplier === 11 ? 22 : 20;
                            $basePrice = max($basePrice, $minimum);
                        }
                        
                        $unitPrice = $basePrice + $blindExtra;
                        $blindTotal = round($unitPrice * $blindQty, 2);
                        
                        // Get image for this blind by index
                        $imageAttachmentId = null;
                        $imagePath = null;
                        if (isset($blindImages[$index])) {
                            $imageData = $blindImages[$index];
                            $attachmentId = is_array($imageData) ? ($imageData[0] ?? null) : $imageData;
                            if ($attachmentId) {
                                $attachment = \Orchid\Attachment\Models\Attachment::find($attachmentId);
                                if ($attachment) {
                                    $imageAttachmentId = $attachment->id;
                                    $imagePath = $attachment->url();
                                }
                            }
                        }
                        
                        try {
                            $order->blinds()->create([
                                'width_m' => $blindWidth,
                                'height_m' => $blindHeight,
                                'note' => $blindNote,
                                'qty' => $blindQty,
                                'stock_alert' => $stockAlert,
                                'stock_alert_reason' => $stockAlertReason,
                                'calc_multiplier' => $blindMultiplier,
                                'extra_charge' => $blindExtra,
                                'total_price' => $blindTotal,
                                'image_attachment_id' => $imageAttachmentId,
                                'image_path' => $imagePath,
                            ]);
                        } catch (\Exception $e) {
                            \Log::error('Error creating blind: ' . $e->getMessage());
                            Toast::error('Error saving blind #' . ($index + 1) . ': ' . $e->getMessage());
                        }
                    }
                }
            }
        }
        
        // Calculate and update total_amount from blinds and shipping cost
        $order->refresh();
        $order->load('blinds');
        $blindsTotal = $order->blinds->sum('total_price') ?? 0;
        $shippingCost = $order->shipping_cost ?? 0;
        $order->total_amount = round($blindsTotal + $shippingCost, 2);
        $order->save();

        Toast::info('Order saved successfully');
        return redirect()->route('platform.orders.edit', $order->id);
    }

    public function remove(Order $order): RedirectResponse
    {
        $order->delete();
        Toast::info('Order deleted');
        return redirect()->route('platform.orders');
    }

    public function prepareShippingDetails(Order $order): RedirectResponse
    {
        // Store flag in session to show the helper box
        session()->flash('show_shipping_helper', true);

        Toast::info('Shipping helper ready! Look for the blue box on the page.');
        return redirect()->route('platform.orders.edit', $order->id);
    }
}
