<button type="button" class="btn btn-sm btn-primary" onclick="openSendModal({{ $order->id }})">
    Send
</button>

<!-- Modal -->
<div class="modal fade" id="sendModal{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="sendModalLabel{{ $order->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendModalLabel{{ $order->id }}">Order Details - {{ $order->reference }}</h5>
                <button type="button" class="close" onclick="closeModal({{ $order->id }})" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @php
                    $order->load('blinds');
                @endphp
                
                @if($order->blinds && $order->blinds->count() > 0)
                    <div class="blinds-list">
                        @foreach($order->blinds as $index => $blind)
                            <div class="blind-item mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <h6 class="mb-3">Blind #{{ $loop->iteration }}</h6>
                                
                                @if($blind->image_path)
                                    <div class="mb-3 position-relative">
                                        <img src="{{ $blind->image_path }}" alt="Blind Image {{ $loop->iteration }}" id="blind-img-{{ $order->id }}-{{ $loop->iteration }}" class="img-fluid blind-image-{{ $order->id }}-{{ $loop->iteration }}" style="max-width: 100%; height: auto; border-radius: 8px; cursor: pointer;" onclick="selectImageToCopy('blind-img-{{ $order->id }}-{{ $loop->iteration }}')">
                                        <div class="text-muted small">Tip: Click or right-click the image to copy it</div>
                                    </div>
                                @endif
                                
                                <div class="blind-details" id="blind-details-{{ $order->id }}-{{ $loop->iteration }}" style="font-family: monospace; background: #f8f9fa; padding: 15px; border-radius: 8px; white-space: pre-wrap; direction: rtl; text-align: right;">
📐 *ستارة #{{ $loop->iteration }}*

📏 العرض: {{ $blind->width_m }} M
📏 الارتفاع: {{ $blind->height_m }} M
🔢 الكمية: {{ $blind->qty }}

@if($blind->note)
📝 ملاحظة: {{ $blind->note }}
@endif

VICCI HOME
                                </div>
                                
                                @php
                                    // Calculate grand total (with extra_charge)
                                    // This is the total_price which already includes extra_charge
                                    $grandTotal = $blind->total_price ?? 0;
                                @endphp
                                
                                <div class="blind-details-original" id="blind-details-original-{{ $order->id }}-{{ $loop->iteration }}" style="font-family: monospace; background: #f8f9fa; padding: 15px; border-radius: 8px; white-space: pre-wrap; direction: rtl; text-align: right; display: none;">
📐 *ستارة #{{ $loop->iteration }}*

📏 العرض: {{ $blind->width_m }} M
📏 الارتفاع: {{ $blind->height_m }} M
🔢 الكمية: {{ $blind->qty }}

@if($blind->note)
📝 ملاحظة: {{ $blind->note }}
@endif

💰 السعر الإجمالي: {{ number_format($grandTotal, 2) }} $

VICCI HOME
                                </div>
                                
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="copyToClipboard('blind-details-{{ $order->id }}-{{ $loop->iteration }}', this)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="display: inline-block; margin-right: 4px;">
                                            <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                                            <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                                        </svg>
                                        Copy Details
                                    </button>
                                    <button type="button" class="btn btn-sm btn-info ml-2" onclick="copyToClipboard('blind-details-original-{{ $order->id }}-{{ $loop->iteration }}', this)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="display: inline-block; margin-right: 4px;">
                                            <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                                            <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                                        </svg>
                                        Copy with Total Price
                                    </button>
                                    <button type="button" class="btn btn-sm btn-success ml-2" onclick="sendViaWhatsApp({{ $order->id }}, {{ $loop->iteration }})">
                                        📱 Send via WhatsApp
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No blinds found for this order.</p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal({{ $order->id }})">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function openSendModal(orderId) {
    const modal = document.getElementById('sendModal' + orderId);
    if (modal) {
        // Check if Bootstrap 5 or Bootstrap 4
        if (typeof bootstrap !== 'undefined') {
            // Bootstrap 5
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        } else if (typeof jQuery !== 'undefined' && jQuery.fn.modal) {
            // Bootstrap 4 with jQuery
            jQuery(modal).modal('show');
        } else {
            // Fallback: just show the modal
            modal.classList.add('show');
            modal.style.display = 'block';
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('modal-open');
            
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.setAttribute('data-order-id', orderId);
            document.body.appendChild(backdrop);
        }
    }
}

function closeModal(orderId) {
    const modal = document.getElementById('sendModal' + orderId);
    if (modal) {
        // Check if Bootstrap 5 or Bootstrap 4
        if (typeof bootstrap !== 'undefined') {
            // Bootstrap 5
            const bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) {
                bsModal.hide();
            }
        } else if (typeof jQuery !== 'undefined' && jQuery.fn.modal) {
            // Bootstrap 4 with jQuery
            jQuery(modal).modal('hide');
        } else {
            // Fallback: just hide the modal
            modal.classList.remove('show');
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('modal-open');
            
            // Remove backdrop
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
        }
    }
}

function copyToClipboard(elementId, buttonElement) {
    const element = document.getElementById(elementId);
    if (!element) {
        console.error('Element not found:', elementId);
        alert('Failed to copy: element not found');
        return;
    }
    
    const text = element.textContent || element.innerText;
    
    // Function to update button state
    function updateButtonState(success) {
        if (!buttonElement) {
            // Fallback: try to find button by onclick attribute
            const parent = element.parentElement;
            if (parent) {
                const buttonContainer = parent.querySelector('.mt-2');
                if (buttonContainer) {
                    const buttons = buttonContainer.querySelectorAll('button');
                    buttons.forEach(function(btn) {
                        if (btn.onclick && btn.onclick.toString().includes(elementId)) {
                            buttonElement = btn;
                        }
                    });
                }
            }
        }
        
        if (buttonElement && success) {
            const originalText = buttonElement.innerHTML;
            const isOriginalButton = buttonElement.onclick && buttonElement.onclick.toString().includes('blind-details-original');
            const originalClass = isOriginalButton ? 'btn-info' : 'btn-secondary';
            
            buttonElement.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="display: inline-block; margin-right: 4px;"><path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/></svg> Copied!';
            buttonElement.classList.add('btn-success');
            buttonElement.classList.remove('btn-secondary', 'btn-info');
        
        setTimeout(function() {
                buttonElement.innerHTML = originalText;
                buttonElement.classList.remove('btn-success');
                buttonElement.classList.add(originalClass);
        }, 2000);
        }
    }
    
    // Try modern Clipboard API first
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(function() {
            updateButtonState(true);
    }).catch(function(err) {
            console.error('Clipboard API failed:', err);
            // Fallback to execCommand
            fallbackCopy(text);
        });
    } else {
        // Use fallback method
        fallbackCopy(text);
    }
    
    // Fallback method using execCommand
    function fallbackCopy(text) {
        // Create a temporary textarea element
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.left = '-999999px';
        textarea.style.top = '-999999px';
        document.body.appendChild(textarea);
        textarea.focus();
        textarea.select();
        
        try {
            const successful = document.execCommand('copy');
            document.body.removeChild(textarea);
            
            if (successful) {
                updateButtonState(true);
            } else {
                console.error('execCommand copy failed');
                alert('Failed to copy to clipboard. Please try selecting the text manually.');
            }
        } catch (err) {
            document.body.removeChild(textarea);
            console.error('Copy failed:', err);
            alert('Failed to copy to clipboard. Please try selecting the text manually.');
        }
    }
}

async function sendViaWhatsApp(orderId, blindIndex) {
    const element = document.getElementById('blind-details-' + orderId + '-' + blindIndex);
    const text = element.textContent || element.innerText;
    
    // Try to copy image to clipboard if available
    const imgElement = document.getElementById('blind-img-' + orderId + '-' + blindIndex);
    let imageCopied = false;
    let copyMethod = null;
    
    if (imgElement && imgElement.src) {
        console.log('[WhatsApp] Attempting to copy image:', imgElement.src);
        
        // Method: Use canvas to draw image and copy (most reliable)
        try {
            // Wait for image to load if not already loaded
            if (!imgElement.complete || imgElement.naturalWidth === 0) {
                console.log('[WhatsApp] Waiting for image to load...');
                await new Promise((resolve, reject) => {
                    const timeout = setTimeout(() => {
                        reject(new Error('Image load timeout'));
                    }, 5000);
                    
                    if (imgElement.complete && imgElement.naturalWidth > 0) {
                        clearTimeout(timeout);
                        resolve();
                    } else {
                        imgElement.onload = () => {
                            clearTimeout(timeout);
                            resolve();
                        };
                        imgElement.onerror = () => {
                            clearTimeout(timeout);
                            reject(new Error('Image failed to load'));
                        };
                    }
                });
            }
            
            console.log('[WhatsApp] Image loaded, dimensions:', imgElement.naturalWidth, 'x', imgElement.naturalHeight);
            
            // Create canvas and draw image
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            canvas.width = imgElement.naturalWidth || imgElement.width;
            canvas.height = imgElement.naturalHeight || imgElement.height;
            
            console.log('[WhatsApp] Canvas created:', canvas.width, 'x', canvas.height);
            
            // Draw image to canvas
            ctx.drawImage(imgElement, 0, 0);
            
            // Convert canvas to blob and copy
            canvas.toBlob(async function(blob) {
                if (!blob) {
                    console.error('[WhatsApp] Canvas toBlob failed');
                    openWhatsAppWithText(text, false);
                    return;
                }
                
                console.log('[WhatsApp] Blob created, type:', blob.type, 'size:', blob.size);
                
                try {
                    // Copy to clipboard
                    await navigator.clipboard.write([
                        new ClipboardItem({
                            [blob.type]: blob
                        })
                    ]);
                    
                    imageCopied = true;
                    copyMethod = 'canvas';
                    console.log('[WhatsApp] ✅ Image copied to clipboard successfully via canvas method');
                    
                    // Open WhatsApp after image is copied
                    openWhatsAppWithText(text, true);
                } catch (clipboardErr) {
                    console.error('[WhatsApp] Clipboard write failed:', clipboardErr);
                    
                    // Try alternative: fetch and copy method
                    try {
                        console.log('[WhatsApp] Trying fetch method as fallback...');
                        const response = await fetch(imgElement.src);
                        if (!response.ok) {
                            throw new Error('Failed to fetch image: ' + response.status);
                        }
                        const fetchBlob = await response.blob();
                        console.log('[WhatsApp] Fetched blob, type:', fetchBlob.type);
                        
                        await navigator.clipboard.write([
                            new ClipboardItem({
                                [fetchBlob.type]: fetchBlob
                            })
                        ]);
                        
                        imageCopied = true;
                        copyMethod = 'fetch';
                        console.log('[WhatsApp] ✅ Image copied via fetch method');
                        openWhatsAppWithText(text, true);
                    } catch (fetchErr) {
                        console.error('[WhatsApp] Fetch method also failed:', fetchErr);
                        openWhatsAppWithText(text, false);
                    }
                }
            }, 'image/png');
            
            return; // Wait for blob conversion
        } catch (err) {
            console.error('[WhatsApp] Canvas method failed:', err);
            // Try fetch method as last resort
            try {
                console.log('[WhatsApp] Trying fetch method...');
                const response = await fetch(imgElement.src);
                if (!response.ok) {
                    throw new Error('Failed to fetch image');
                }
                const blob = await response.blob();
                await navigator.clipboard.write([
                    new ClipboardItem({
                        [blob.type]: blob
                    })
                ]);
                imageCopied = true;
                copyMethod = 'fetch-fallback';
                console.log('[WhatsApp] ✅ Image copied via fetch fallback');
                openWhatsAppWithText(text, true);
                return;
            } catch (fetchErr) {
                console.error('[WhatsApp] All methods failed:', fetchErr);
                // Continue without image
            }
        }
    }
    
    // Open WhatsApp Web or App (if no image or copy completed synchronously)
    if (!imgElement || (imageCopied === false && copyMethod === null)) {
        console.log('[WhatsApp] Opening WhatsApp without image (no image element or copy failed)');
        openWhatsAppWithText(text, false);
    }
}

function openWhatsAppWithText(text, imageCopied = false) {
    // Encode the text for WhatsApp
    const encodedText = encodeURIComponent(text);
    
    // Open WhatsApp Web or App
    const whatsappUrl = 'https://wa.me/?text=' + encodedText;
    window.open(whatsappUrl, '_blank');
    
    // Show message about image if applicable
    if (imageCopied) {
        setTimeout(() => {
            alert('✅ Image copied to clipboard!\n\nThe text has been opened in WhatsApp.\n\n📋 Please paste the image in the chat:\n• Press Ctrl+V (Windows/Linux) or Cmd+V (Mac)\n• Or right-click and select "Paste"\n\n💡 Tip: Paste the image before or after the text message.');
        }, 800);
    } else {
        // Show message if image exists but couldn't be copied
        const hasImage = document.querySelector('img[src*="storage"]') !== null;
        if (hasImage) {
            setTimeout(() => {
                alert('⚠️ Could not copy image automatically.\n\nThe text has been opened in WhatsApp.\n\n📷 To add the image:\n1. Right-click the image above and select "Copy Image"\n2. Then paste it (Ctrl+V or Cmd+V) in WhatsApp');
            }, 800);
        }
    }
}

function selectImageToCopy(imageId) {
    const img = document.getElementById(imageId);
    if (img) {
        // Create a range and select the image
        const range = document.createRange();
        range.selectNode(img);
        const selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);
        
        try {
            // Try to copy
            const successful = document.execCommand('copy');
            if (successful) {
                alert('Image selected! You can now paste it (Ctrl+V or Cmd+V) in WhatsApp.');
            } else {
                alert('Please right-click the image and select "Copy Image" to copy it.');
            }
        } catch (err) {
            console.error('Failed to copy:', err);
            alert('Please right-click the image and select "Copy Image" to copy it.');
        }
        
        // Clear selection
        selection.removeAllRanges();
    }
}

// Close modal handlers
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.modal .close, .modal [data-dismiss="modal"]').forEach(function(closeBtn) {
        closeBtn.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                modal.classList.remove('show');
                modal.style.display = 'none';
                modal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('modal-open');
                
                // Remove backdrop
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
            }
        });
    });
});
</script>

