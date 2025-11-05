<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Sales Reports</h2>
            
            <div class="row">
                <!-- Total Blinds -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-muted mb-3">Total Blinds</h5>
                            <h2 class="mb-0" style="color: #007bff; font-weight: 700;">{{ number_format($total_blinds ?? 0, 0) }}</h2>
                            <p class="text-muted mb-0 mt-2">Total quantity of all blinds</p>
                        </div>
                    </div>
                </div>
                
                <!-- Blinds by Multiplier 10 -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-muted mb-3">Blinds (Multiplier × 10)</h5>
                            <h2 class="mb-0" style="color: #28a745; font-weight: 700;">{{ number_format($blinds_multiplier_10 ?? 0, 0) }}</h2>
                            <p class="text-muted mb-0 mt-2">Blinds using multiplier 10</p>
                        </div>
                    </div>
                </div>
                
                <!-- Blinds by Multiplier 11 -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-muted mb-3">Blinds (Multiplier × 11)</h5>
                            <h2 class="mb-0" style="color: #fd7e14; font-weight: 700;">{{ number_format($blinds_multiplier_11 ?? 0, 0) }}</h2>
                            <p class="text-muted mb-0 mt-2">Blinds using multiplier 11</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <!-- Subtotal -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-primary">
                        <div class="card-body">
                            <h5 class="card-title text-muted mb-3">Subtotal</h5>
                            <h2 class="mb-0" style="color: #007bff; font-weight: 700;">${{ number_format($subtotal ?? 0, 2) }}</h2>
                            <p class="text-muted mb-0 mt-2">Total before shipping</p>
                        </div>
                    </div>
                </div>
                
                <!-- Grand Total -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm" style="border: 2px solid #007bff;">
                        <div class="card-body" style="background: #e7f3ff;">
                            <h5 class="card-title mb-3" style="color: #007bff; font-weight: 600;">Grand Total</h5>
                            <h2 class="mb-0" style="color: #007bff; font-weight: 700; font-size: 2.5rem;">${{ number_format($grand_total ?? 0, 2) }}</h2>
                            <p class="text-muted mb-0 mt-2">Subtotal + Shipping</p>
                        </div>
                    </div>
                </div>
                
                <!-- Profit -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-success">
                        <div class="card-body" style="background: #f0fff4;">
                            <h5 class="card-title mb-3" style="color: #28a745; font-weight: 600;">Profit</h5>
                            <h2 class="mb-0" style="color: #28a745; font-weight: 700; font-size: 2.5rem;">${{ number_format($profit ?? 0, 2) }}</h2>
                            <p class="text-muted mb-0 mt-2">Sum of (Extra × Qty) for all blinds</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <!-- Pick Up In Store -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-info">
                        <div class="card-body" style="background: #e7f5ff;">
                            <h5 class="card-title mb-3" style="color: #0d6efd; font-weight: 600;">Pick Up In Store</h5>
                            <h2 class="mb-0" style="color: #0d6efd; font-weight: 700; font-size: 2.5rem;">{{ number_format($pick_up_in_store_count ?? 0, 0) }}</h2>
                            <p class="text-muted mb-0 mt-2">Orders with pick up in store</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                            <h5 class="card-title mb-0" style="color: #495057; font-weight: 600;">Orders by City</h5>
                        </div>
                        <div class="card-body">
                            @if(!empty($orders_by_city))
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead style="background: #f8f9fa;">
                                            <tr>
                                                <th style="font-weight: 600; color: #495057;">City</th>
                                                <th style="font-weight: 600; color: #495057; text-align: right;">Orders</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($orders_by_city as $city => $count)
                                                <tr>
                                                    <td>{{ $city }}</td>
                                                    <td style="text-align: right; font-weight: 600; color: #007bff;">{{ number_format($count, 0) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted mb-0">No orders by city data available.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
    }
</style>

