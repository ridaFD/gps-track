<style>
.blind-row-hidden {
    display: none !important;
}

/* Modern, clean styling for blind rows */
.blind-row-group {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 12px;
    border: 1px solid #e9ecef;
    transition: all 0.2s ease;
    display: grid !important;
    grid-template-columns: repeat(7, 1fr) 2fr 60px;
    gap: 8px;
    width: 100%;
    align-items: start !important;
    justify-items: start !important;
    min-width: 0;
    max-width: 100%;
    box-sizing: border-box;
}

.blind-row-group:hover {
    background: #ffffff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

/* Add a wrapper for horizontal scrolling if needed */
@supports (overflow-x: auto) {
    .blind-row-group {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
}

/* Each field container inside the grid */
/* .blind-row-group > div > div {
    margin-top: 0 !important;
    padding-top: 0 !important;
    display: flex !important;
    flex-direction: column !important;
    width: 100% !important;
    min-width: 0 !important;
    align-items: flex-start !important;
    justify-content: flex-start !important;
} */

/* Label styling - always consistent */
.blind-row-group label {
    margin-bottom: 4px !important;
    margin-top: 0 !important;
    font-weight: 500;
    font-size: 0.8rem;
    color: #495057;
    line-height: 1.2;
}

/* Make Note and Stock Alert Reason fields span all columns on a new line */
.blind-row-group > div:has(textarea[name*="note"]),
.blind-row-group > div > div > div:has(textarea[name*="note"]),
.blind-row-group > div:has(textarea[name*="stock_alert_reason"]),
.blind-row-group > div > div > div:has(textarea[name*="stock_alert_reason"]) {
    grid-column: 1 / -1 !important; /* Span all columns */
    margin-top: 8px !important; /* Add some space above it */
}

/* Input field styling - compact */
.blind-row-group input[type="number"],
.blind-row-group input[type="text"],
.blind-row-group input.form-control,
.blind-row-group .form-control,
.blind-row-group textarea {
    width: 100% !important;
    padding: 6px 8px !important;
    border: 1px solid #ced4da !important;
    border-radius: 6px !important;
    font-size: 0.85rem !important;
    transition: all 0.2s ease !important;
    background: #fff !important;
    color: #212529 !important;
}

.blind-row-group input[type="number"]:focus,
.blind-row-group input[type="text"]:focus,
.blind-row-group textarea:focus {
    outline: none;
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}

.blind-row-group input[readonly] {
    background: #f8f9fa;
    cursor: not-allowed;
    color: #6c757d;
}

/* Textarea specific styling */
.blind-row-group textarea {
    resize: vertical;
    min-height: 60px;
}

/* Upload field alignment and styling */
.blind-row-group > div > div:has(.upload-field-container),
.blind-row-group > div > div:has([data-field="upload"]) {
    margin-top: 0 !important;
    padding-top: 0 !important;
    align-items: flex-start !important;
}

.blind-row-group > div > div:has([data-field="upload"]) > label {
    margin-top: 0 !important;
    margin-bottom: 6px !important;
}

/* Upload field button styling */
.blind-row-group .upload-field-container,
.blind-row-group [data-field="upload"],
.blind-row-group .field-wrapper[data-field="upload"] {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    width: 100%;
}

.blind-row-group .upload-field-container button,
.blind-row-group [data-field="upload"] button {
    padding: 10px 16px;
    border-radius: 6px;
    border: 1px solid #ced4da;
    background: #fff;
    transition: all 0.2s ease;
}

.blind-row-group .upload-field-container button:hover,
.blind-row-group [data-field="upload"] button:hover {
    background: #f8f9fa;
    border-color: #80bdff;
}

/* Uploaded image preview */
.blind-row-group .uploaded-file,
.blind-row-group .file-preview,
.blind-row-group .upload-field-container img {
    display: block;
}

/* Remove button styling */
.blind-row-group button[class*="btn-danger"],
.blind-row-group .btn-danger,
.blind-row-group button[title*=""],
.blind-row-group a[class*="btn-danger"] {
    width: 100% !important;
    padding: 8px !important;
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    height: 32px !important;
}

.blind-row-group button[class*="btn-danger"] .bi,
.blind-row-group .btn-danger .bi,
.blind-row-group button[class*="btn-danger"] i {
    font-size: 1rem !important;
    margin: 0;
}


/* Responsive adjustments */
@media (max-width: 1400px) {
    .blind-row-group > div.d-flex.flex-column.grid,
    .blind-row-group > div.d-md-grid,
    .blind-row-group > div[class*="grid"] {
        grid-template-columns: 90px 90px 50px 80px 80px 90px 160px 45px;
        gap: 6px;
    }
}

@media (max-width: 900px) {
    .blind-row-group {
        overflow-x: auto;
    }
    .blind-row-group > div.d-flex.flex-column.grid,
    .blind-row-group > div.d-md-grid,
    .blind-row-group > div[class*="grid"] {
        min-width: 700px;
    }
}

/* Add Row button styling */
#add-blind-row-btn {
    background: #007bff;
    border: none;
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0,123,255,0.2);
    margin-bottom: 20px;
}

#add-blind-row-btn:hover {
    background: #0056b3;
    box-shadow: 0 4px 8px rgba(0,123,255,0.3);
    transform: translateY(-1px);
}

#add-blind-row-btn:active {
    transform: translateY(0);
}

#add-blind-row-btn:disabled {
    background: #6c757d;
    cursor: not-allowed;
    box-shadow: none;
}

/* Section title styling */
h3:has-text("Blinds"),
.blinds-section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #212529;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e9ecef;
}
</style>

<div class="mb-3">
    <button type="button" id="add-blind-row-btn">
        <span style="margin-right: 6px;">+</span> Add Row
    </button>
</div>

<!-- Order Totals Section -->
<div class="order-totals-section" style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef;">
    <h5 style="margin-bottom: 15px; color: #495057; border-bottom: 2px solid #dee2e6; padding-bottom: 10px;">Order Totals</h5>
    <div class="totals-grid" style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 20px; align-items: start;">
        <div class="total-item" style="display: flex; flex-direction: column; align-items: stretch;">
            <label style="display: block; margin: 0 0 8px 0; font-weight: 600; color: #495057; font-size: 0.9rem; text-align: left;">Subtotal</label>
            <input type="text" id="order-subtotal-display" readonly value="0.00" style="width: 100%; padding: 10px 12px; border: 2px solid #dee2e6; border-radius: 6px; font-size: 1.1rem; font-weight: 600; background: #fff; color: #212529; text-align: right; box-sizing: border-box;">
        </div>
        <div class="total-item" style="display: flex; flex-direction: column; align-items: stretch;">
            <label style="display: block; margin: 0 0 8px 0; font-weight: 600; color: #495057; font-size: 0.9rem; text-align: left;">Shipping Cost</label>
            <input type="text" id="order-shipping-display" readonly value="0.00" style="width: 100%; padding: 10px 12px; border: 2px solid #dee2e6; border-radius: 6px; font-size: 1.1rem; font-weight: 600; background: #fff; color: #212529; text-align: right; box-sizing: border-box;">
        </div>
        <div class="total-item" style="display: flex; flex-direction: column; align-items: stretch;">
            <label style="display: block; margin: 0 0 8px 0; font-weight: 600; color: #007bff; font-size: 1rem; text-align: left;">Grand Total</label>
            <input type="text" id="order-grand-total-display" readonly value="0.00" style="width: 100%; padding: 10px 12px; border: 2px solid #007bff; border-radius: 6px; font-size: 1.1rem; font-weight: 700; background: #e7f3ff; color: #007bff; text-align: right; box-sizing: border-box;">
        </div>
        <div class="total-item" style="display: flex; flex-direction: column; align-items: stretch;">
            <label style="display: block; margin: 0 0 8px 0; font-weight: 600; color: #28a745; font-size: 0.9rem; text-align: left;">Profit</label>
            <input type="text" id="order-profit-display" readonly value="0.00" style="width: 100%; padding: 10px 12px; border: 2px solid #28a745; border-radius: 6px; font-size: 1.1rem; font-weight: 600; background: #f0fff4; color: #28a745; text-align: right; box-sizing: border-box;">
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';
    
    function initBlindsManager() {
        const addBtn = document.getElementById('add-blind-row-btn');
        if (!addBtn) return false;
        
        const form = addBtn.closest('form') || document.querySelector('form');
        if (!form) return false;
        
        // Prevent duplicate initialization
        if (addBtn.classList.contains('init-attached')) {
            return false;
        }
        addBtn.classList.add('init-attached');
        
        // Find the last blind row to use as a template for cloning
        function getLastBlindRow() {
            // Find all blind inputs and get the highest index
            let maxIndex = -1;
            form.querySelectorAll('input[name*="blinds"], select[name*="blinds"], textarea[name*="blinds"]').forEach(function(field) {
                const match = field.name.match(/blinds[\.\[](\d+)[\.\]]/);
                if (match) {
                    const index = parseInt(match[1]);
                    if (index > maxIndex) {
                        maxIndex = index;
                    }
                }
            });
            
            if (maxIndex === -1) return null;
            
            // Find the parent container of the last row (try both naming formats)
            const lastWidthInput = form.querySelector('[name="blinds.' + maxIndex + '.width"], [name="blinds[' + maxIndex + '][width]"]');
            if (!lastWidthInput) return null;
            
            // Find the parent form-group container
            let parent = lastWidthInput;
            for (let i = 0; i < 15; i++) {
                parent = parent.parentElement;
                if (!parent) break;
                
                if (parent.classList && parent.classList.contains('form-group') && 
                    (parent.classList.contains('d-flex') || parent.classList.contains('grid') || 
                     parent.classList.contains('d-md-grid'))) {
                    return { row: parent, index: maxIndex };
                }
            }
            
            // Fallback
            const formGroup = lastWidthInput.closest('.form-group');
            if (formGroup) {
                return { row: formGroup, index: maxIndex };
            }
            
            return null;
        }
        
        // Handle remove button clicks
        function attachRemoveButtons() {
            // Find all blind rows
            const allGroups = form.querySelectorAll('.form-group.d-flex, .form-group.grid, .form-group.d-md-grid');
            
            allGroups.forEach(function(group, index) {
                // Check if this group contains blind fields (support both naming formats)
                const hasBlindFields = group.querySelector('[name*="blinds"]');
                
                if (hasBlindFields && !group.classList.contains('remove-attached')) {
                    group.classList.add('remove-attached');
                    
                    // Find hidden input placeholder for remove button
                    const hiddenInput = group.querySelector('input[type="hidden"][name^="blind_remove_"]');
                    
                    if (hiddenInput) {
                        // Create remove button container
                        const removeContainer = document.createElement('div');
                        removeContainer.style.display = 'flex';
                        removeContainer.style.flexDirection = 'column';
                        removeContainer.style.alignItems = 'flex-start';
                        
                        // Create remove button
                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'btn btn-danger btn-sm';
                        removeBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/></svg>';
                        removeBtn.title = 'Remove Row';
                        removeBtn.style.width = '100%';
                        removeBtn.style.padding = '6px 8px';
                        removeBtn.style.fontSize = '0.875rem';
                        removeBtn.style.display = 'flex';
                        removeBtn.style.alignItems = 'center';
                        removeBtn.style.justifyContent = 'center';
                        
                        removeContainer.appendChild(removeBtn);
                        
                        // Replace hidden input with button
                        hiddenInput.parentNode.replaceChild(removeContainer, hiddenInput);
                        
                        // Attach click handler
                        removeBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            
                            // Count how many blind rows exist
                            const remainingGroups = form.querySelectorAll('.form-group.d-flex, .form-group.grid, .form-group.d-md-grid');
                            let blindGroupsCount = 0;
                            remainingGroups.forEach(function(g) {
                                if (g.querySelector('[name*="blinds"]')) {
                                    blindGroupsCount++;
                                }
                            });
                            
                            // Only allow removal if there's more than 1 row
                            if (blindGroupsCount > 1) {
                                group.remove();
                                // Recalculate totals after removal
                                updateOrderTotals();
                            } else {
                                alert('You must keep at least one row');
                            }
                        });
                    }
                }
            });
        }
        
        // Clone the last row and create a new one
        addBtn.addEventListener('click', function() {
            const lastRowData = getLastBlindRow();
            if (!lastRowData) {
                console.error('Could not find last blind row to clone');
                return;
            }
            
            const lastRow = lastRowData.row;
            const lastIndex = lastRowData.index;
            const newIndex = lastIndex + 1;
            
            // Check max rows
            if (newIndex >= 20) {
                addBtn.disabled = true;
                addBtn.textContent = 'Maximum rows reached (20)';
                return;
            }
            
            // Clone the row
            const newRow = lastRow.cloneNode(true);
            
            // Update all field names and IDs
            newRow.querySelectorAll('input, select, textarea, [name]').forEach(function(field) {
                if (field.name) {
                    // Replace the index in the name for blinds fields (handle both dot and bracket notation)
                    field.name = field.name.replace(/blinds\.(\d+)/g, 'blinds.' + newIndex);
                    field.name = field.name.replace(/blinds\[(\d+)\]/g, 'blinds[' + newIndex + ']');
                    // Replace the index for blind_remove_ fields
                    field.name = field.name.replace(/blind_remove_(\d+)/, 'blind_remove_' + newIndex);
                }
                if (field.id) {
                    field.id = field.id.replace(/blinds_\d+/, 'blinds_' + newIndex);
                }
            });
            
            // Update blind_images field name
            newRow.querySelectorAll('[name*="blind_images"]').forEach(function(field) {
                // Handle both dot and bracket notation
                const matchDot = field.name.match(/blind_images\.(\d+)/);
                const matchBracket = field.name.match(/blind_images\[(\d+)\]/);
                if (matchDot) {
                    field.name = 'blind_images.' + newIndex;
                } else if (matchBracket) {
                    field.name = 'blind_images[' + newIndex + ']';
                }
            });
            
            // Clear values in the new row
            newRow.querySelectorAll('input[type="number"]').forEach(function(input) {
                if (input.name.includes('multiplier')) {
                    input.value = '10';
                } else if (input.name.includes('total')) {
                    input.value = '0.00';
                } else {
                    input.value = '';
                }
            });
            
            // Clear note textarea
            newRow.querySelectorAll('textarea[name*="note"]').forEach(function(textarea) {
                textarea.value = '';
            });
            
            // Clear stock alert checkbox and textarea
            newRow.querySelectorAll('input[type="checkbox"][name*="stock_alert"]').forEach(function(checkbox) {
                checkbox.checked = false;
            });
            newRow.querySelectorAll('textarea[name*="stock_alert_reason"]').forEach(function(textarea) {
                textarea.value = '';
            });
            
            // Clear upload field
            const uploadFields = newRow.querySelectorAll('[data-field="upload"], .upload-field-container');
            uploadFields.forEach(function(upload) {
                // Remove uploaded file previews
                const previews = upload.querySelectorAll('.uploaded-file, .file-preview, img');
                previews.forEach(function(preview) {
                    preview.remove();
                });
                // Clear hidden input values
                const hiddenInputs = upload.querySelectorAll('input[type="hidden"]');
                hiddenInputs.forEach(function(input) {
                    if (input.name.includes('blind_images')) {
                        input.value = '';
                    }
                });
            });
            
            // Insert the new row after the last row
            lastRow.parentNode.insertBefore(newRow, lastRow.nextSibling);
            
            // Mark the new row group for styling and align fields
            if (newRow.classList && newRow.classList.contains('form-group')) {
                newRow.classList.add('blind-row-group');
                newRow.style.alignItems = 'start';
                
                // Align field containers in the new row, but skip upload containers
                const fieldContainers = newRow.querySelectorAll(':scope > div > div');
                fieldContainers.forEach(function(container) {
                    // Skip upload fields - they have their own complex DOM structure
                    if (container.querySelector('[data-field="upload"]')) {
                        return;
                    }
                    
                    container.style.marginTop = '0';
                    container.style.paddingTop = '0';
                    container.style.display = 'flex';
                    container.style.flexDirection = 'column';
                    container.style.alignItems = 'flex-start';
                    
                    const label = container.querySelector('label, .form-label');
                    const input = container.querySelector('input, select, textarea');
                    
                    if (label) {
                        label.style.marginTop = '0';
                        label.style.marginBottom = '4px';
                        label.style.display = 'block';
                    }
                    
                    if (label && input) {
                        label.style.order = '1';
                        input.style.order = '2';
                    }
                });
            }
            
            // Attach remove button to the new row and apply styling
            attachRemoveButtons();
            markBlindRows();
        });
        
        // Calculate total for a row (including qty)
        function calculateTotal(rowIndex) {
            const widthInput = form.querySelector('[name="blinds.' + rowIndex + '.width"], [name="blinds[' + rowIndex + '][width]"]');
            const heightInput = form.querySelector('[name="blinds.' + rowIndex + '.height"], [name="blinds[' + rowIndex + '][height]"]');
            const qtyInput = form.querySelector('[name="blinds.' + rowIndex + '.qty"], [name="blinds[' + rowIndex + '][qty]"]');
            const multiplierInput = form.querySelector('[name="blinds.' + rowIndex + '.multiplier"], [name="blinds[' + rowIndex + '][multiplier]"]');
            const extraInput = form.querySelector('[name="blinds.' + rowIndex + '.extra"], [name="blinds[' + rowIndex + '][extra]"]');
            const sizeInput = form.querySelector('[name="blinds.' + rowIndex + '.size_m2"], [name="blinds[' + rowIndex + '][size_m2]"]');
            const totalInput = form.querySelector('[name="blinds.' + rowIndex + '.total"], [name="blinds[' + rowIndex + '][total]"]');
            
            if (widthInput && heightInput && qtyInput && multiplierInput && extraInput && totalInput) {
                const width = parseFloat(widthInput.value) || 0;
                const height = parseFloat(heightInput.value) || 0;
                const qty = parseInt(qtyInput.value) || 1;
                const multiplier = parseInt(multiplierInput.value) || 10;
                const extra = parseFloat(extraInput.value) || 0;
                
                // Only calculate if both width and height are greater than 0
                if (width > 0 && height > 0) {
                    // Calculate size in m² (width and height are now in meters)
                    const areaSquareMeters = width * height;
                    if (sizeInput) {
                        sizeInput.value = areaSquareMeters.toFixed(2);
                    }
                    
                    // Calculate total: (m² * multiplier + extra) * qty
                    // If size < 2m², apply minimum: $20 for multiplier 10, $22 for multiplier 11
                    let unitPrice;
                    if (areaSquareMeters < 2) {
                        const minimum = multiplier === 11 ? 22 : 20;
                        unitPrice = minimum + extra;
                    } else {
                        unitPrice = areaSquareMeters * multiplier + extra;
                    }
                    
                    const total = Math.round(unitPrice * qty * 100) / 100;
                    totalInput.value = total.toFixed(2);
                } else {
                    // If width or height is empty/zero, set total and size to 0
                    if (sizeInput) {
                        sizeInput.value = '0.00';
                    }
                    totalInput.value = '0.00';
                }
                
                // Update order totals
                updateOrderTotals();
            }
        }
        
        // Calculate order totals (subtotal, grand total, and profit)
        function updateOrderTotals() {
            const subtotalDisplay = document.getElementById('order-subtotal-display');
            const shippingDisplay = document.getElementById('order-shipping-display');
            const grandTotalDisplay = document.getElementById('order-grand-total-display');
            const profitDisplay = document.getElementById('order-profit-display');
            
            // Get all blind totals
            let subtotal = 0;
            form.querySelectorAll('input[name*="blinds"][name*=".total"], input[name*="blinds"][name*="[total]"]').forEach(function(input) {
                const value = parseFloat(input.value) || 0;
                subtotal += value;
            });
            
            // Get shipping cost from OrderEditRows (look for shipping_cost field)
            let shippingCost = 0;
            const shippingInput = form.querySelector('input[name*="shipping_cost"]');
            if (shippingInput) {
                shippingCost = parseFloat(shippingInput.value) || 0;
            }
            
            // Calculate profit: sum of (extra * qty) for all blinds
            let profit = 0;
            const blindRows = form.querySelectorAll('[name*="blinds"]:not([name*="blind_images"])');
            const blindRowGroups = new Map();
            
            // Group fields by blind row index
            blindRows.forEach(function(input) {
                const match = input.name.match(/blinds[\.\[](\d+)[\.\]].*/);
                if (match) {
                    const index = parseInt(match[1]);
                    if (!blindRowGroups.has(index)) {
                        blindRowGroups.set(index, {});
                    }
                    // Handle both dot notation (blinds.0.extra) and bracket notation (blinds[0][extra])
                    const fieldMatch = input.name.match(/blinds[\.\[]\d+[\.\]][\.\[](.+)/);
                    if (fieldMatch) {
                        let fieldName = fieldMatch[1];
                        // Remove closing bracket if present
                        fieldName = fieldName.replace(/]$/, '');
                        blindRowGroups.get(index)[fieldName] = input;
                    }
                }
            });
            
            // Calculate profit for each blind row
            blindRowGroups.forEach(function(row) {
                const extraInput = row.extra;
                const qtyInput = row.qty;
                
                if (extraInput && qtyInput) {
                    const extra = parseFloat(extraInput.value) || 0;
                    const qty = parseInt(qtyInput.value) || 1;
                    profit += extra * qty;
                }
            });
            
            // Update displays
            if (subtotalDisplay) {
                subtotalDisplay.value = subtotal.toFixed(2);
            }
            if (shippingDisplay) {
                shippingDisplay.value = shippingCost.toFixed(2);
            }
            if (grandTotalDisplay) {
                const grandTotal = subtotal + shippingCost;
                grandTotalDisplay.value = grandTotal.toFixed(2);
            }
            if (profitDisplay) {
                profitDisplay.value = profit.toFixed(2);
            }
        }
        
        // Auto-calculate totals (using event delegation to handle dynamically added rows)
        form.addEventListener('input', function(e) {
            const match = e.target.name && e.target.name.match(/blinds[\.\[](\d+)[\.\]].*(width|height|qty|multiplier|extra)/);
            if (match) {
                calculateTotal(match[1]);
            }
            
            // Also update totals if shipping cost, extra, or qty changes
            if (e.target.name && (e.target.name.includes('shipping_cost') || e.target.name.includes('blinds') && (e.target.name.includes('.total') || e.target.name.includes('[total]') || e.target.name.includes('.extra') || e.target.name.includes('[extra]') || e.target.name.includes('.qty') || e.target.name.includes('[qty]')))) {
                updateOrderTotals();
            }
        });
        
        // Initialize totals on page load
        updateOrderTotals();
        
        // Recalculate size_m2 for all existing rows on page load
        function recalculateAllSizes() {
            // Find all blind row indices
            const allBlindRows = form.querySelectorAll('[name*="blinds"][name*=".width"], [name*="blinds"][name*="[width]"]');
            allBlindRows.forEach(function(widthInput) {
                const match = widthInput.name.match(/blinds[\.\[](\d+)[\.\]].*/);
                if (match) {
                    const rowIndex = match[1];
                    calculateTotal(rowIndex);
                }
            });
        }
        
        // Recalculate all sizes on page load (after a delay to ensure DOM is ready)
        setTimeout(recalculateAllSizes, 300);
        
        // Add class to blind row groups for better CSS targeting
        function markBlindRows() {
            // Find all form-groups that have d-flex/grid classes and contain blind fields
            const allGroups = form.querySelectorAll('.form-group.d-flex, .form-group.grid, .form-group.d-md-grid');
            
            allGroups.forEach(function(group) {
                // Check if this group contains any blind fields (support both naming formats)
                const hasBlindFields = group.querySelector('[name*="blinds"]');
                
                if (hasBlindFields) {
                    group.classList.add('blind-row-group');
                    
                    // Override autoWidth() inline styles to use our custom grid template
                    group.style.setProperty('grid-template-columns', 'repeat(7, 1fr) 2fr 60px', 'important');
                    
                    // Force align-items to start to override Bootstrap baseline
                    group.style.alignItems = 'start';
                    group.style.setProperty('align-items', 'start', 'important');
                    
                    // Also remove align-items-baseline if present
                    group.classList.remove('align-items-baseline');
                    
                    // Ensure all field containers in the row have consistent styling
                    const fieldContainers = group.querySelectorAll(':scope > div > div');
                    fieldContainers.forEach(function(container) {
                        // Skip upload fields - they have their own complex DOM structure
                        if (container.querySelector('[data-field="upload"]')) {
                            return;
                        }
                        
                        // Reset any inline styles that might cause misalignment
                        const label = container.querySelector('label, .form-label');
                        const input = container.querySelector('input, select, textarea');
                        
                        // Ensure label is always above the input
                        if (label && input) {
                            // Move label before input if it's not already
                            if (label.nextSibling !== input && !label.contains(input)) {
                                label.style.order = '1';
                                input.style.order = '2';
                            }
                            // Ensure label has consistent spacing
                            label.style.marginTop = '0';
                            label.style.marginBottom = '4px';
                            label.style.display = 'block';
                        }
                        // Ensure container itself has no extra spacing
                        container.style.marginTop = '0';
                        container.style.paddingTop = '0';
                        container.style.display = 'flex';
                        container.style.flexDirection = 'column';
                        container.style.alignItems = 'flex-start';
                    });
                    
                    // Make Note field span all columns on a new line
                    const noteTextarea = group.querySelector('textarea[placeholder*="Add a note"]');
                    if (noteTextarea) {
                        const noteContainer = noteTextarea.closest('.col-12');
                        if (noteContainer) {
                            noteContainer.style.gridColumn = '1 / -1';
                            noteContainer.style.marginTop = '8px';
                        }
                    }
                    
                    // Make Stock Alert Reason field span all columns on a new line
                    const stockAlertTextarea = group.querySelector('textarea[name*="stock_alert_reason"]');
                    if (stockAlertTextarea) {
                        const stockAlertContainer = stockAlertTextarea.closest('.col-12');
                        if (stockAlertContainer) {
                            stockAlertContainer.style.gridColumn = '1 / -1';
                            stockAlertContainer.style.marginTop = '8px';
                        }
                    }
                }
            });
        }
        
        // Mark rows on initialization and after delays to ensure proper alignment
        markBlindRows();
        attachRemoveButtons();
        
        // Multiple attempts to catch all rows as they load
        [200, 500, 1000, 1500].forEach(function(delay) {
            setTimeout(function() {
                markBlindRows();
                attachRemoveButtons();
            }, delay);
        });
        
        return true;
    }
    
    // Start when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initBlindsManager);
    } else {
        initBlindsManager();
    }
    
    // Also try after a delay in case form loads later
    setTimeout(initBlindsManager, 100);
})();
</script>

