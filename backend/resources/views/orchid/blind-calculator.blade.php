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
    grid-template-columns: repeat(7, 1fr) 60px;
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

/* Override Bootstrap column classes to work with grid */
.blind-row-group > div.col-12,
.blind-row-group > div.col-md,
.blind-row-group > div[class*="col-"],
.blind-row-group > div > div.col-12,
.blind-row-group > div > div.col-md,
.blind-row-group > div > div[class*="col-"],
.blind-row-group div[class*="col-"] {
    width: 100% !important;
    flex: none !important;
    max-width: 100% !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
    min-width: 0 !important;
}

/* Target all form-group elements inside grid */
.blind-row-group .form-group {
    width: 100% !important;
    flex: none !important;
    max-width: 100% !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
}

/* Label styling - always consistent */
.blind-row-group label {
    margin-bottom: 4px !important;
    margin-top: 0 !important;
    font-weight: 500;
    font-size: 0.8rem;
    color: #495057;
    line-height: 1.2;
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

/* Center the last column (remove button) - target specifically the div containing the button */
.blind-row-group > div:last-child,
.blind-row-group > div:last-child > div {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
}

.blind-row-group button[class*="btn-danger"] .bi,
.blind-row-group .btn-danger .bi,
.blind-row-group button[class*="btn-danger"] i {
    font-size: 1rem !important;
    margin: 0;
}

/* Responsive adjustments */
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
h3:has-text("Blind Calculator"),
.calculator-section-title {
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

<!-- Calculator Totals Section -->
<div class="calculator-totals-section" style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef;">
    <h5 style="margin-bottom: 15px; color: #495057; border-bottom: 2px solid #dee2e6; padding-bottom: 10px;">Calculator Totals</h5>
    <div class="totals-grid" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; align-items: start;">
        <div class="total-item" style="display: flex; flex-direction: column; align-items: stretch;">
            <label style="display: block; margin: 0 0 8px 0; font-weight: 600; color: #495057; font-size: 0.9rem; text-align: left;">Subtotal</label>
            <input type="text" id="order-subtotal-display" readonly value="0.00" style="width: 100%; padding: 10px 12px; border: 2px solid #dee2e6; border-radius: 6px; font-size: 1.1rem; font-weight: 600; background: #fff; color: #212529; text-align: right; box-sizing: border-box;">
        </div>
        <div class="total-item" style="display: flex; flex-direction: column; align-items: stretch;">
            <label style="display: block; margin: 0 0 8px 0; font-weight: 600; color: #495057; font-size: 0.9rem; text-align: left;">Extra Charges</label>
            <input type="text" id="order-extra-display" readonly value="0.00" style="width: 100%; padding: 10px 12px; border: 2px solid #dee2e6; border-radius: 6px; font-size: 1.1rem; font-weight: 600; background: #fff; color: #212529; text-align: right; box-sizing: border-box;">
        </div>
        <div class="total-item" style="display: flex; flex-direction: column; align-items: stretch;">
            <label style="display: block; margin: 0 0 8px 0; font-weight: 600; color: #007bff; font-size: 1rem; text-align: left;">Grand Total</label>
            <input type="text" id="order-grand-total-display" readonly value="0.00" style="width: 100%; padding: 10px 12px; border: 2px solid #007bff; border-radius: 6px; font-size: 1.1rem; font-weight: 700; background: #e7f3ff; color: #007bff; text-align: right; box-sizing: border-box;">
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';
    
    function initBlindsManager() {
        const addBtn = document.getElementById('add-blind-row-btn');
        if (!addBtn) return false;
        
        const form = addBtn.closest('form') || document.querySelector('form') || document.body;
        
        // Prevent duplicate initialization
        if (addBtn.classList.contains('init-attached')) {
            return false;
        }
        addBtn.classList.add('init-attached');
        
        // Find the last blind row to use as a template for cloning
        function getLastBlindRow() {
            // Find all blind inputs and get the highest index
            let maxIndex = -1;
            form.querySelectorAll('input[name*="blinds"], select[name*="blinds"]').forEach(function(field) {
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
                        removeContainer.style.alignItems = 'center';
                        removeContainer.style.justifyContent = 'center';
                        
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
                                // Find the parent group that contains this button
                                let parentGroup = removeBtn.closest('.form-group');
                                if (parentGroup && (parentGroup.classList.contains('d-flex') || parentGroup.classList.contains('grid') || parentGroup.classList.contains('d-md-grid'))) {
                                    parentGroup.remove();
                                } else {
                                    group.remove();
                                }
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
                    console.log('[DEBUG] Updated field name:', field.name);
                }
                if (field.id) {
                    field.id = field.id.replace(/blinds_\d+/, 'blinds_' + newIndex);
                }
            });
            
            // Check if cloned row has hidden input or button
            const hasButton = newRow.querySelector('button[class*="btn-danger"]');
            
            // Clear values in the new row
            newRow.querySelectorAll('input[type="number"]').forEach(function(input) {
                if (input.name.includes('multiplier')) {
                    input.value = '10';
                } else if (input.name.includes('total') || input.name.includes('size')) {
                    input.value = '0.00';
                } else if (input.name.includes('qty')) {
                    input.value = '1';
                } else {
                    input.value = '';
                }
            });
            
            // Insert the new row after the last row
            lastRow.parentNode.insertBefore(newRow, lastRow.nextSibling);
            
            // Mark the new row group for styling
            if (newRow.classList && newRow.classList.contains('form-group')) {
                newRow.classList.add('blind-row-group');
                newRow.style.alignItems = 'start';
            }
            
            // Remove the remove-attached class so attachRemoveButtons will process this row
            newRow.classList.remove('remove-attached');
            
            // If cloned row has button instead of hidden input, we need to remove the button and let attachRemoveButtons add it
            if (hasButton) {
                // Find the button and its container
                const clonedButton = newRow.querySelector('button[class*="btn-danger"]');
                if (clonedButton) {
                    const buttonContainer = clonedButton.parentElement;
                    if (buttonContainer) {
                        // Replace button container with hidden input so attachRemoveButtons can process it
                        const newHiddenInput = document.createElement('input');
                        newHiddenInput.type = 'hidden';
                        newHiddenInput.name = 'blind_remove_' + newIndex;
                        buttonContainer.parentNode.replaceChild(newHiddenInput, buttonContainer);
                    }
                }
            }
            
            // Re-attach remove buttons
            attachRemoveButtons();
            markBlindRows();
            updateOrderTotals();
        });
        
        // Calculate size and total for a single row
        function calculateRow(row) {
            const widthInput = row.querySelector('[name*="width"]');
            const heightInput = row.querySelector('[name*="height"]');
            const qtyInput = row.querySelector('[name*="qty"]');
            const sizeInput = row.querySelector('[name*="size_m2"]');
            const multiplierInput = row.querySelector('[name*="multiplier"]');
            const extraInput = row.querySelector('[name*="extra"]');
            const totalInput = row.querySelector('[name*="total"]');
            
            if (!widthInput || !heightInput || !qtyInput || !sizeInput || !multiplierInput || !extraInput || !totalInput) {
                console.log('[DEBUG] calculateRow: Missing inputs', { widthInput: !!widthInput, heightInput: !!heightInput, qtyInput: !!qtyInput, sizeInput: !!sizeInput, multiplierInput: !!multiplierInput, extraInput: !!extraInput, totalInput: !!totalInput });
                console.log('[DEBUG] calculateRow: Row HTML:', row.innerHTML.substring(0, 200));
                return;
            }
            
            const width = parseFloat(widthInput.value) || 0;
            const height = parseFloat(heightInput.value) || 0;
            const qty = parseInt(qtyInput.value) || 1;
            const multiplier = parseInt(multiplierInput.value) || 10;
            const extra = parseFloat(extraInput.value) || 0;
            
            console.log('[DEBUG] calculateRow: input names:', { widthName: widthInput.name, heightName: heightInput.name, qtyName: qtyInput.name, sizeName: sizeInput.name, multiplierName: multiplierInput.name, extraName: extraInput.name, totalName: totalInput.name });
            console.log('[DEBUG] calculateRow: values:', { width, height, qty, multiplier, extra });
            
            // Calculate area
            const area = width * height;
            sizeInput.value = area.toFixed(2);
            
            // Apply minimum total logic
            let basePrice = area * multiplier;
            if (area < 2 && area > 0) {
                const minimum = multiplier === 11 ? 22 : 20;
                basePrice = Math.max(basePrice, minimum);
            }
            
            // Calculate total
            const unitPrice = basePrice + extra;
            const total = (unitPrice * qty).toFixed(2);
            totalInput.value = total;
            
            console.log('[DEBUG] calculateRow: calculated', { area, basePrice, unitPrice, total });
        }
        
        // Update order totals
        function updateOrderTotals() {
            console.log('[DEBUG] updateOrderTotals called');
            let subtotal = 0;
            let extraTotal = 0;
            
            // Sum up all blind totals
            const allRows = form.querySelectorAll('.form-group.d-flex, .form-group.grid, .form-group.d-md-grid');
            console.log('[DEBUG] Found', allRows.length, 'rows');
            allRows.forEach(function(row, index) {
                const hasBlinds = row.querySelector('[name*="blinds"]');
                console.log('[DEBUG] Row', index, 'hasBlinds:', !!hasBlinds, 'classes:', row.className);
                if (hasBlinds) {
                    console.log('[DEBUG] Processing row', index);
                    calculateRow(row);
                    
                    const totalInput = row.querySelector('[name*="total"]');
                    const extraInput = row.querySelector('[name*="extra"]');
                    const qtyInput = row.querySelector('[name*="qty"]');
                    
                    console.log('[DEBUG] Row', index, 'total inputs:', { total: totalInput ? totalInput.value : 'NOT FOUND', extra: extraInput ? extraInput.value : 'NOT FOUND', qty: qtyInput ? qtyInput.value : 'NOT FOUND' });
                    
                    if (totalInput && extraInput && qtyInput) {
                        subtotal += parseFloat(totalInput.value) || 0;
                        const qty = parseInt(qtyInput.value) || 1;
                        const extra = parseFloat(extraInput.value) || 0;
                        extraTotal += extra * qty;
                    }
                }
            });
            
            console.log('[DEBUG] Final subtotal:', subtotal, 'extraTotal:', extraTotal);
            
            // Update display fields
            const subtotalDisplay = document.getElementById('order-subtotal-display');
            const extraDisplay = document.getElementById('order-extra-display');
            const grandTotalDisplay = document.getElementById('order-grand-total-display');
            
            if (subtotalDisplay) {
                subtotalDisplay.value = subtotal.toFixed(2);
            }
            if (extraDisplay) {
                extraDisplay.value = extraTotal.toFixed(2);
            }
            if (grandTotalDisplay) {
                grandTotalDisplay.value = subtotal.toFixed(2);
            }
        }
        
        // Attach event listeners to inputs using event delegation
        function attachCalculationListeners() {
            // Use event delegation on the form itself - works for all existing and future inputs
            form.addEventListener('input', function(e) {
                // Check if this is a blind calculation field
                if (e.target.name && e.target.name.match(/blinds[\.\[](\d+)[\.\]].*(width|height|qty|multiplier|extra)/)) {
                    updateOrderTotals();
                }
            });
        }
        
        // Mark and style blind rows for proper alignment
        function markBlindRows() {
            const allGroups = form.querySelectorAll('.form-group.d-flex, .form-group.grid, .form-group.d-md-grid');
            
            allGroups.forEach(function(group) {
                const hasBlindFields = group.querySelector('[name*="blinds"]');
                
                if (hasBlindFields) {
                    group.classList.add('blind-row-group');
                    
                    // Override autoWidth() inline styles to use our custom grid template
                    group.style.setProperty('grid-template-columns', 'repeat(7, 1fr) 60px', 'important');
                    
                    // Find and center the last column (remove button)
                    const lastChild = group.children[group.children.length - 1];
                    if (lastChild && lastChild.querySelector('button[class*="btn-danger"]')) {
                        lastChild.style.display = 'flex';
                        lastChild.style.flexDirection = 'column';
                        lastChild.style.alignItems = 'center';
                        lastChild.style.justifyContent = 'center';
                    }
                }
            });
        }
        
        // Initialize
        attachRemoveButtons();
        attachCalculationListeners();
        updateOrderTotals();
        markBlindRows();
        
        // Multiple attempts to catch all rows as they load
        [200, 500, 1000].forEach(function(delay) {
            setTimeout(function() {
                markBlindRows();
            }, delay);
        });
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initBlindsManager);
    } else {
        initBlindsManager();
    }
    
    // Also try after a delay in case form loads later
    setTimeout(initBlindsManager, 100);
})();
</script>

