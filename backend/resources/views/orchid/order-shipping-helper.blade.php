@php
    // Get order data - check both the model passed from layout and the array from query()
    $orderData = null;
    $hasOrder = false;
    
    // First, check if orderModel was passed (from layout parameters)
    if (isset($orderModel) && $orderModel && $orderModel->exists) {
        $orderData = $orderModel->toArray();
        $hasOrder = true;
    }
    // Otherwise, check if order array exists (from query method)
    elseif (isset($order) && is_array($order) && isset($order['id'])) {
        $orderData = $order;
        $hasOrder = true;
    }
@endphp

@if(session()->has('show_shipping_helper') || ($orderData && $hasOrder))
@php
    $customerDetails = null;
    if ($orderData) {
        $firstName = $orderData['customer_first_name'] ?? '';
        $lastName = $orderData['customer_last_name'] ?? '';
        $name = trim($firstName . ' ' . $lastName) ?: ($orderData['customer_name'] ?? 'N/A');
        $phone = $orderData['customer_phone'] ?? 'N/A';
        $address = $orderData['customer_address'] ?? 'N/A';
        $city = $orderData['customer_city'] ?? 'N/A';
        $reference = $orderData['reference'] ?? 'N/A';
        
        // Calculate grand total
        $grandTotal = $orderData['total_amount'] ?? 0;
        
        // If total_amount is zero or null, calculate from blinds and shipping_cost
        if ($grandTotal <= 0) {
            // Try to get from orderModel if available
            if (isset($orderModel) && $orderModel && $orderModel->exists) {
                if (!$orderModel->relationLoaded('blinds')) {
                    $orderModel->load('blinds');
                }
                $blindsTotal = $orderModel->blinds->sum('total_price') ?? 0;
                $shippingCost = $orderModel->shipping_cost ?? 0;
                $grandTotal = $blindsTotal + $shippingCost;
            } else {
                // Fallback: try to get from array data if blinds are included
                $blindsTotal = 0;
                if (isset($orderData['blinds']) && is_array($orderData['blinds'])) {
                    foreach ($orderData['blinds'] as $blind) {
                        $blindsTotal += $blind['total_price'] ?? 0;
                    }
                }
                $shippingCost = $orderData['shipping_cost'] ?? 0;
                $grandTotal = $blindsTotal + $shippingCost;
            }
        }
        
        $grandTotalFormatted = number_format($grandTotal, 2);
        
        $customerDetails = [
            'text' => "Customer Details:\nName: " . $name . "\nPhone: " . $phone . "\nAddress: " . $address . "\nCity: " . $city . "\nOrder Reference: " . $reference . "\nGrand Total: " . $grandTotalFormatted . " USD",
            'json' => json_encode([
                'name' => $name,
                'phone' => $phone === 'N/A' ? '' : $phone,
                'address' => $address === 'N/A' ? '' : $address,
                'city' => $city === 'N/A' ? '' : $city,
                'reference' => $reference === 'N/A' ? '' : $reference,
                'grand_total' => $grandTotalFormatted,
                'grand_total_currency' => 'USD',
            ], JSON_PRETTY_PRINT),
            'shipping_url' => 'https://systemtunes.com/flypost/clients/orders/add.php',
        ];
    }
@endphp
<style>
.shipping-helper-box {
    background: #e7f3ff;
    border: 2px solid #0d6efd;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.shipping-helper-box.hidden {
    display: none;
}

.shipping-helper-title {
    font-weight: 600;
    color: #0d6efd;
    margin: 0;
    flex: 1;
    min-width: 200px;
}

.shipping-helper-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.shipping-helper-buttons .btn {
    white-space: nowrap;
}
</style>

<div class="shipping-helper-box" id="shippingHelperBox">
    <div class="shipping-helper-title">
        <i class="bi bi-truck"></i> Ready to share customer details for shipping
    </div>
    <div class="shipping-helper-buttons">
        <button type="button" class="btn btn-primary btn-sm" onclick="copyCustomerDetailsToClipboard()">
            <i class="bi bi-clipboard"></i> Copy Customer Details
        </button>
        <button type="button" class="btn btn-success btn-sm" onclick="openShippingWebsite()">
            <i class="bi bi-box-arrow-up-right"></i> Open Shipping Site
        </button>
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="closeShippingHelper()">
            <i class="bi bi-x"></i> Close
        </button>
    </div>
</div>

<script>
const customerDetails = @json($customerDetails ?? []);

function copyCustomerDetailsToClipboard() {
    if (!customerDetails.text) {
        console.error('No customer details available to copy.');
        return;
    }
    
    // Copy formatted text to clipboard
    navigator.clipboard.writeText(customerDetails.text).then(() => {
        // Silently copy without showing alert
    }).catch(err => {
        console.error('Failed to copy:', err);
        // Fallback: Select text in a temporary textarea
        const textarea = document.createElement('textarea');
        textarea.value = customerDetails.text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();
        try {
            document.execCommand('copy');
            // Silently copy without showing alert
        } catch (err) {
            console.error('Failed to copy manually:', err);
        }
        document.body.removeChild(textarea);
    });
}

function openShippingWebsite() {
    const shippingUrl = customerDetails.shipping_url || 'https://systemtunes.com/flypost/clients/orders/add.php';
    window.open(shippingUrl, '_blank');
    
    // Copy details to clipboard automatically
    setTimeout(() => {
        copyCustomerDetailsToClipboard();
    }, 500);
}

function closeShippingHelper() {
    const box = document.getElementById('shippingHelperBox');
    if (box) {
        box.classList.add('hidden');
        // Store preference in localStorage
        localStorage.setItem('hideShippingHelper', 'true');
    }
}

// Check if user previously closed the helper (only if not explicitly shown)
document.addEventListener('DOMContentLoaded', function() {
    const box = document.getElementById('shippingHelperBox');
    if (box) {
        @if(session()->has('show_shipping_helper'))
            // If explicitly shown via button, always show it
            box.classList.remove('hidden');
            localStorage.removeItem('hideShippingHelper'); // Reset preference when explicitly shown
        @else
            // Otherwise, check localStorage preference
            if (localStorage.getItem('hideShippingHelper') === 'true') {
                box.classList.add('hidden');
            } else {
                box.classList.remove('hidden');
            }
        @endif
    }
});
</script>
@endif

