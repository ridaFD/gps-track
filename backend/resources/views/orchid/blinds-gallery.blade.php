<style>
.blinds-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 24px;
    padding: 20px 0;
}

.blind-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    display: flex;
    flex-direction: column;
    position: relative;
}

.blind-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}

.blind-card.selected {
    border-color: #0d6efd;
    box-shadow: 0 4px 16px rgba(13, 110, 253, 0.3);
    background: #f0f7ff;
}

.blind-card-checkbox {
    position: absolute;
    top: 12px;
    left: 12px;
    z-index: 10;
    width: 24px;
    height: 24px;
    cursor: pointer;
    accent-color: #0d6efd;
}

.blind-card-checkbox:checked + .blind-image-wrapper::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(13, 110, 253, 0.2);
    z-index: 1;
}

.blind-card.low-stock {
    border-color: #ffc107;
}

.blind-card.out-of-stock {
    border-color: #dc3545;
    opacity: 0.8;
}

.blind-image-wrapper {
    position: relative;
    width: 100%;
    height: 200px;
    background: #f8f9fa;
    overflow: hidden;
}

.blind-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: relative;
    z-index: 0;
}

.blind-image-count {
    position: absolute;
    top: 8px;
    right: 8px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
}

.blind-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    color: #dee2e6;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.blind-info {
    padding: 16px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.blind-color {
    font-size: 1.25rem;
    font-weight: 600;
    color: #212529;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.blind-color-badge {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: inline-block;
    border: 2px solid #fff;
    box-shadow: 0 0 0 1px rgba(0,0,0,0.1);
}

.blind-stock {
    font-size: 0.9rem;
    font-weight: 500;
    padding: 6px 12px;
    border-radius: 6px;
    margin-top: auto;
    text-align: center;
}

.blind-stock.in-stock {
    background: #d4edda;
    color: #155724;
}

.blind-stock.low-stock {
    background: #fff3cd;
    color: #856404;
}

.blind-stock.out-of-stock {
    background: #f8d7da;
    color: #721c24;
}

.blind-description {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 12px;
    line-height: 1.5;
}

.blind-actions {
    display: flex;
    gap: 8px;
    margin-top: 12px;
}

.blind-actions .btn {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.empty-state svg {
    width: 80px;
    height: 80px;
    margin: 0 auto 16px;
    opacity: 0.3;
}

.gallery-actions-bar {
    display: flex;
    gap: 12px;
    padding: 16px 0;
    align-items: center;
    flex-wrap: wrap;
    border-bottom: 1px solid #dee2e6;
    margin-bottom: 20px;
}

.gallery-actions-bar .btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.selection-info {
    margin-left: auto;
    color: #6c757d;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .blinds-gallery {
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 16px;
    }
    
    .gallery-actions-bar {
        flex-direction: column;
        align-items: stretch;
    }
    
    .selection-info {
        margin-left: 0;
        margin-top: 8px;
    }
    }
</style>

<div class="gallery-actions-bar">
    <button type="button" class="btn btn-outline-primary" id="toggleSelectBtn" onclick="toggleSelectAll()">
        <i class="bi bi-check-square" id="toggleSelectIcon"></i> <span id="toggleSelectText">Select All Filtered</span>
    </button>
    <button type="button" class="btn btn-success" id="copyImagesBtn" onclick="shareSelectedImages()" disabled>
        <i class="bi bi-share"></i> Share Selected Images
    </button>
    <button type="button" class="btn btn-primary" id="downloadZipBtn" onclick="downloadSelectedImagesAsZip()" disabled>
        <i class="bi bi-download"></i> Download as ZIP
    </button>
    <span class="selection-info" id="selectionInfo">0 selected</span>
</div>

<div class="blinds-gallery">
    @forelse($blinds as $blind)
        @php
            $firstImage = null;
            $imageCount = 0;
            
            // Prioritize new multiple images
            if ($blind->relationLoaded('blindImages') && $blind->blindImages->isNotEmpty()) {
                $firstImage = $blind->blindImages->first();
                $imageCount = $blind->blindImages->count();
            } elseif ($blind->image_path) {
                // Fallback to old single image field
                $firstImage = (object) ['url' => $blind->image_path];
                $imageCount = 1;
            }
            
            $imageUrl = $firstImage && isset($firstImage->url) ? $firstImage->url : null;
        @endphp
        <div class="blind-card {{ $blind->stock_status }}" data-blind-id="{{ $blind->id }}" data-color="{{ strtolower($blind->color) }}">
            <input type="checkbox" class="blind-card-checkbox" data-blind-id="{{ $blind->id }}" data-image-url="{{ $imageUrl }}" data-blind-color="{{ $blind->color }}" onchange="updateSelectionInfo()">
            <div class="blind-image-wrapper">
                @if($imageUrl)
                    <img src="{{ $imageUrl }}" alt="{{ $blind->color }}" loading="lazy" data-image-url="{{ $imageUrl }}">
                    @if($imageCount > 1)
                        <div class="blind-image-count">+{{ $imageCount - 1 }}</div>
                    @endif
                @else
                    <div class="blind-placeholder">
                        <span style="color: {{ $blind->color_code ?? '#667eea' }}">🎨</span>
                    </div>
                @endif
            </div>
            
            <div class="blind-info">
                <div class="blind-color">
                    @if($blind->color_code)
                        <span class="blind-color-badge" style="background-color: {{ $blind->color_code }}"></span>
                    @endif
                    {{ $blind->color }}
                    @if($blind->has_details)
                        <span class="badge bg-info ms-2" title="Has extra detailing, patterns, or pictures">🎨 Details</span>
                    @endif
                </div>
                
                @if($blind->description)
                    <div class="blind-description">{{ Str::limit($blind->description, 100) }}</div>
                @endif
                
                <div class="blind-stock {{ $blind->stock_status }}">
                    @if($blind->stock_status === 'out_of_stock')
                        ⚠️ Out of Stock
                    @elseif($blind->stock_status === 'low_stock')
                        ⚠️ Low Stock: {{ $blind->stock_qty }}
                    @else
                        ✓ In Stock: {{ $blind->stock_qty }}
                    @endif
                </div>
                
                <div class="blind-actions">
                    <a href="{{ route('platform.blinds.edit', $blind->id) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil"></i> <span>Edit</span>
                    </a>
                    @if($blind->is_active)
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleBlindStatus({{ $blind->id }}, false)">
                            <i class="bi bi-eye-slash"></i> <span>Hide</span>
                        </button>
                    @else
                        <button type="button" class="btn btn-success btn-sm" onclick="toggleBlindStatus({{ $blind->id }}, true)">
                            <i class="bi bi-eye"></i> <span>Show</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3>No blinds yet</h3>
            <p>Get started by adding your first blind color to the catalog.</p>
            <a href="{{ route('platform.blinds.create') }}" class="btn btn-primary mt-3">
                <i class="bi bi-plus-circle"></i> Add First Blind
            </a>
        </div>
    @endforelse
</div>

<script>
function toggleBlindStatus(blindId, status) {
    if (confirm('Are you sure you want to ' + (status ? 'activate' : 'deactivate') + ' this blind?')) {
        // You would implement this via AJAX or a form submission
        console.log('Toggle blind', blindId, 'to', status);
        // For now, just reload the page
        location.reload();
    }
}

// Selection management
function updateSelectionInfo() {
    const checkboxes = document.querySelectorAll('.blind-card-checkbox:checked');
    const count = checkboxes.length;
    const infoElement = document.getElementById('selectionInfo');
    const copyBtn = document.getElementById('copyImagesBtn');
    const zipBtn = document.getElementById('downloadZipBtn');
    
    if (infoElement) {
        infoElement.textContent = count + ' selected';
    }
    
    if (copyBtn) {
        copyBtn.disabled = count === 0;
    }
    
    if (zipBtn) {
        zipBtn.disabled = count === 0;
    }
    
    // Update card selected state
    document.querySelectorAll('.blind-card').forEach(card => {
        const checkbox = card.querySelector('.blind-card-checkbox');
        if (checkbox && checkbox.checked) {
            card.classList.add('selected');
        } else {
            card.classList.remove('selected');
        }
    });
    
    // Update toggle button state
    updateToggleButton();
}

function toggleSelectAll() {
    const checkboxes = document.querySelectorAll('.blind-card-checkbox');
    const checkedCount = document.querySelectorAll('.blind-card-checkbox:checked').length;
    const allChecked = checkedCount === checkboxes.length && checkboxes.length > 0;
    
    // If all are checked, deselect all; otherwise, select all
    const shouldSelectAll = !allChecked;
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = shouldSelectAll;
    });
    
    updateSelectionInfo();
    updateToggleButton();
}

function updateToggleButton() {
    const toggleBtn = document.getElementById('toggleSelectBtn');
    const toggleIcon = document.getElementById('toggleSelectIcon');
    const toggleText = document.getElementById('toggleSelectText');
    
    if (!toggleBtn || !toggleIcon || !toggleText) return;
    
    const checkboxes = document.querySelectorAll('.blind-card-checkbox');
    const checkedCount = document.querySelectorAll('.blind-card-checkbox:checked').length;
    const allChecked = checkedCount === checkboxes.length && checkboxes.length > 0;
    
    if (allChecked) {
        // All selected - show deselect option
        toggleIcon.className = 'bi bi-square';
        toggleText.textContent = 'Deselect All';
        toggleBtn.className = 'btn btn-outline-secondary';
    } else {
        // Some or none selected - show select all option
        toggleIcon.className = 'bi bi-check-square';
        toggleText.textContent = 'Select All Filtered';
        toggleBtn.className = 'btn btn-outline-primary';
    }
}

// Helper function to convert image to blob using canvas (doesn't copy, just returns blob)
async function copyImageViaCanvasToBlob(imageUrl, imgElement = null) {
    return new Promise((resolve, reject) => {
        let img;
        
        function processImage() {
            try {
                // Validate image dimensions
                const width = img.naturalWidth || img.width;
                const height = img.naturalHeight || img.height;
                
                if (!width || !height || width === 0 || height === 0) {
                    reject(new Error('Image has invalid dimensions: ' + width + 'x' + height));
                    return;
                }
                
                // Create canvas with exact image dimensions
                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;
                
                const ctx = canvas.getContext('2d');
                
                // Draw the image directly (don't clear with white as it might cause white images)
                ctx.drawImage(img, 0, 0, width, height);
                
                canvas.toBlob(function(blob) {
                    if (!blob || blob.size === 0) {
                        reject(new Error('Failed to create image blob'));
                        return;
                    }
                    resolve(blob);
                }, 'image/png');
            } catch (err) {
                reject(err);
            }
        }
        
        if (imgElement) {
            // Use the already loaded image element
            if (imgElement.complete && imgElement.naturalWidth > 0 && imgElement.naturalHeight > 0) {
                img = imgElement;
                setTimeout(processImage, 10);
            } else if (imgElement.complete) {
                reject(new Error('Image element has invalid dimensions'));
            } else {
                img = imgElement;
                img.onload = function() {
                    if (img.naturalWidth > 0 && img.naturalHeight > 0) {
                        processImage();
                    } else {
                        reject(new Error('Image failed to load properly'));
                    }
                };
                img.onerror = function() {
                    reject(new Error('Image element failed to load'));
                };
                if (!img.src && imageUrl) {
                    img.src = imageUrl;
                }
            }
        } else {
            // Load the image from URL
            img = new Image();
            
            img.onload = function() {
                if (img.naturalWidth > 0 && img.naturalHeight > 0) {
                    processImage();
                } else {
                    reject(new Error('Image loaded but has invalid dimensions'));
                }
            };
            
            img.onerror = function() {
                const img2 = new Image();
                img2.onload = function() {
                    if (img2.naturalWidth > 0 && img2.naturalHeight > 0) {
                        img = img2;
                        processImage();
                    } else {
                        reject(new Error('Image loaded but has invalid dimensions'));
                    }
                };
                img2.onerror = () => reject(new Error('Failed to load image from URL: ' + imageUrl));
                img2.src = imageUrl;
            };
            
            img.crossOrigin = 'anonymous';
            img.src = imageUrl;
        }
    });
}

// Helper function to copy image using canvas from existing img element or URL (kept for backward compatibility)
async function copyImageViaCanvas(imageUrl, imgElement = null) {
    return new Promise(async (resolve, reject) => {
        try {
            const blob = await copyImageViaCanvasToBlob(imageUrl, imgElement);
            if (navigator.clipboard && navigator.clipboard.write && window.ClipboardItem) {
                const clipboardItem = new ClipboardItem({ 'image/png': blob });
                await navigator.clipboard.write([clipboardItem]);
                resolve(true);
            } else {
                reject(new Error('Clipboard API not available'));
            }
        } catch (err) {
            reject(err);
        }
    });
}

// Share multiple images using Web Share API (works great with WhatsApp)
async function shareSelectedImages() {
    const checkboxes = document.querySelectorAll('.blind-card-checkbox:checked');
    
    if (checkboxes.length === 0) {
        alert('Please select at least one image to share.');
        return;
    }
    
    const copyBtn = document.getElementById('copyImagesBtn');
    const originalText = copyBtn.innerHTML;
    
    // Declare imageData outside try block so it's accessible in catch
    let imageData = [];
    
    try {
        // Disable button and show loading state
        copyBtn.disabled = true;
        copyBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Preparing...';
        
        // Collect image data
        imageData = [];
        checkboxes.forEach(checkbox => {
            const imageUrl = checkbox.getAttribute('data-image-url');
            const blindId = checkbox.getAttribute('data-blind-id');
            const blindColor = checkbox.getAttribute('data-blind-color') || 'blind';
            if (imageUrl) {
                // Try to find the actual img element on the page (already loaded, no CORS issues)
                const blindCard = checkbox.closest('.blind-card');
                const imgElement = blindCard ? blindCard.querySelector('img') : null;
                imageData.push({ url: imageUrl, element: imgElement, blindId: blindId, color: blindColor });
            }
        });
        
        if (imageData.length === 0) {
            alert('No images found in selected blinds.');
            copyBtn.disabled = false;
            copyBtn.innerHTML = originalText;
            return;
        }
        
        console.log(`Attempting to share ${imageData.length} images`);
        
        // Check if Web Share API is available
        if (navigator.share && navigator.canShare) {
            try {
                const files = [];
                
                // Fetch all images and convert to File objects
                for (let i = 0; i < imageData.length; i++) {
                    const imageInfo = imageData[i];
                    let blob = null;
                    
                    // Try fetch first
                    try {
                        const response = await fetch(imageInfo.url);
                        if (!response.ok) {
                            throw new Error('Failed to fetch image: ' + response.status);
                        }
                        blob = await response.blob();
                        
                        if (!blob.type.startsWith('image/')) {
                            throw new Error('Response is not an image type: ' + blob.type);
                        }
                        
                        console.log(`Fetched image ${i + 1}/${imageData.length}:`, blob.type, blob.size);
                    } catch (fetchError) {
                        console.log(`Fetch failed for image ${i + 1}, trying canvas method:`, fetchError);
                        
                        // Fallback to canvas method
                        try {
                            if (imageInfo.element) {
                                blob = await copyImageViaCanvasToBlob(imageInfo.url, imageInfo.element);
                            } else {
                                blob = await copyImageViaCanvasToBlob(imageInfo.url);
                            }
                            console.log(`Canvas method succeeded for image ${i + 1}`);
                        } catch (canvasError) {
                            console.error(`Failed to get image ${i + 1} via canvas:`, canvasError);
                            throw new Error(`Failed to process image ${i + 1}: ${canvasError.message}`);
                        }
                    }
                    
                    // Create a File object from the blob
                    // Generate a filename based on the blind color
                    const colorName = (imageInfo.color || 'blind').replace(/[^a-z0-9]/gi, '-').toLowerCase();
                    const extension = blob.type.split('/')[1] || 'png';
                    const fileName = `${colorName}-${i + 1}.${extension}`;
                    const file = new File([blob], fileName, { type: blob.type });
                    files.push(file);
                }
                
                // Prepare share data
                const shareData = {
                    files: files,
                    title: `Share ${files.length} blind images`
                };
                
                // Check if we can share multiple files
                if (navigator.canShare(shareData)) {
                    console.log(`Sharing ${files.length} images via Web Share API...`);
                    
                    // Update button to show sharing state
                    copyBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Opening share dialog...';
                    
                    await navigator.share(shareData);
                    console.log('Successfully shared images');
                    
                    copyBtn.innerHTML = '<i class="bi bi-check-circle"></i> Shared!';
                    setTimeout(() => {
                        copyBtn.innerHTML = originalText;
                        copyBtn.disabled = false;
                        updateSelectionInfo();
                    }, 2000);
                } else {
                    throw new Error('Browser cannot share multiple files');
                }
                
            } catch (error) {
                // If share was cancelled by user, offer ZIP download option
                if (error.name === 'AbortError') {
                    console.log('User cancelled share');
                    const offerZip = confirm(
                        'Share dialog was cancelled.\n\n' +
                        'Would you like to download the selected images as a ZIP file instead?\n\n' +
                        'You can then extract the ZIP and share the images via WhatsApp or any other app.'
                    );
                    
                    if (offerZip) {
                        // Call the ZIP download function
                        await downloadSelectedImagesAsZip();
                    }
                    
                    copyBtn.disabled = false;
                    copyBtn.innerHTML = originalText;
                    return;
                }
                
                // Handle permission denied errors specifically
                if (error.name === 'NotAllowedError' || error.message.includes('Permission denied')) {
                    console.error('Share permission denied:', error);
                    // Don't throw, fall through to clipboard fallback
                    throw new Error('Share permission denied. This usually happens if the page is not served over HTTPS or requires user interaction.');
                }
                
                console.error('Error sharing images:', error);
                throw error;
            }
        } else {
            // Fallback: Download images as ZIP or show instructions
            throw new Error('Web Share API not supported. Please use a mobile browser or Chrome/Edge on desktop.');
        }
        
    } catch (error) {
        console.error('Error sharing images:', error);
        
        // Ensure we have imageData before proceeding
        if (!imageData || imageData.length === 0) {
            alert('No images to share. Please select at least one blind image.');
            copyBtn.disabled = false;
            copyBtn.innerHTML = originalText;
            return;
        }
        
        // Fallback: Try to copy first image to clipboard as backup
        if (navigator.clipboard && navigator.clipboard.write && window.ClipboardItem) {
            try {
                console.log('Web Share API not available, falling back to copying first image...');
                const firstImage = imageData[0];
                
                let blob = null;
                try {
                    const response = await fetch(firstImage.url);
                    blob = await response.blob();
                } catch (fetchError) {
                    if (firstImage.element) {
                        blob = await copyImageViaCanvasToBlob(firstImage.url, firstImage.element);
                    } else {
                        blob = await copyImageViaCanvasToBlob(firstImage.url);
                    }
                }
                
                const clipboardItem = new ClipboardItem({ [blob.type || 'image/png']: blob });
                await navigator.clipboard.write([clipboardItem]);
                
                copyBtn.innerHTML = '<i class="bi bi-check-circle"></i> Copied!';
                
                if (imageData.length === 1) {
                    alert('Image copied to clipboard! You can now paste it in WhatsApp (Ctrl+V or Cmd+V).');
                } else {
                    alert(`Web Share API is not available in your browser.\n\nOnly the first image (1 of ${imageData.length}) has been copied to clipboard.\n\nFor multiple images, please:\n1. Use a mobile browser or Chrome/Edge on desktop\n2. Or right-click on each image and select "Copy Image"`);
                }
                
                setTimeout(() => {
                    copyBtn.innerHTML = originalText;
                    copyBtn.disabled = false;
                    updateSelectionInfo();
                }, 2000);
                return;
            } catch (clipboardError) {
                console.error('Clipboard fallback also failed:', clipboardError);
            }
        }
        
        // Final fallback: Offer to download images as ZIP or show instructions
        const shouldDownload = confirm(
            'Web Share API is not available.\n\n' +
            'Would you like to download all selected images as a ZIP file?\n\n' +
            'Click OK to download, or Cancel for manual copy instructions.'
        );
        
        if (shouldDownload) {
            try {
                // Download all images as ZIP using JSZip
                console.log('Downloading images as ZIP...');
                copyBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Creating ZIP...';
                
                // Check if JSZip is available, if not, load it dynamically
                if (typeof JSZip === 'undefined') {
                    const script = document.createElement('script');
                    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js';
                    document.head.appendChild(script);
                    await new Promise((resolve) => {
                        script.onload = resolve;
                        setTimeout(resolve, 2000); // Timeout after 2 seconds
                    });
                }
                
                if (typeof JSZip !== 'undefined') {
                    const zip = new JSZip();
                    let loadedCount = 0;
                    
                    for (let i = 0; i < imageData.length; i++) {
                        const imageInfo = imageData[i];
                        try {
                            const response = await fetch(imageInfo.url);
                            const blob = await response.blob();
                            const colorName = (imageInfo.color || 'blind').replace(/[^a-z0-9]/gi, '-').toLowerCase();
                            const extension = blob.type.split('/')[1] || 'png';
                            const fileName = `${colorName}-${i + 1}.${extension}`;
                            zip.file(fileName, blob);
                            loadedCount++;
                        } catch (fetchError) {
                            console.error(`Failed to load image ${i + 1} for ZIP:`, fetchError);
                        }
                    }
                    
                    if (loadedCount > 0) {
                        const zipBlob = await zip.generateAsync({ type: 'blob' });
                        const url = URL.createObjectURL(zipBlob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = `blinds-${imageData.length}-images.zip`;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        URL.revokeObjectURL(url);
                        
                        copyBtn.innerHTML = '<i class="bi bi-check-circle"></i> Downloaded!';
                        alert(`ZIP file with ${loadedCount} images downloaded! You can extract it and share the images via WhatsApp.`);
                    } else {
                        throw new Error('Failed to load any images for ZIP');
                    }
                } else {
                    throw new Error('JSZip library not available');
                }
            } catch (zipError) {
                console.error('ZIP download failed:', zipError);
                // Show manual instructions
                let errorMessage = 'Could not create ZIP file.\n\n';
                errorMessage += 'For sharing multiple images with WhatsApp:\n';
                errorMessage += '1. Use a mobile browser (iOS Safari or Chrome on Android) for Web Share\n';
                errorMessage += '2. Or use Chrome/Edge on desktop with HTTPS enabled\n';
                errorMessage += '3. Or right-click on each image and select "Copy Image", then paste in WhatsApp one by one';
                alert(errorMessage);
            }
        } else {
            // Show manual instructions
            let errorMessage = 'Manual copy instructions:\n\n';
            errorMessage += 'To share multiple images with WhatsApp:\n';
            errorMessage += '1. Right-click on the first image → Copy Image\n';
            errorMessage += '2. Paste in WhatsApp\n';
            errorMessage += '3. Repeat for each image\n\n';
            errorMessage += 'Or use a mobile browser where Web Share API works better.';
            alert(errorMessage);
        }
        
        copyBtn.disabled = false;
        copyBtn.innerHTML = originalText;
    }
}

// Load JSZip library if not already loaded
function ensureJSZip() {
    return new Promise((resolve, reject) => {
        if (typeof JSZip !== 'undefined') {
            resolve();
            return;
        }
        
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js';
        script.onload = () => {
            console.log('JSZip loaded');
            resolve();
        };
        script.onerror = () => {
            reject(new Error('Failed to load JSZip library'));
        };
        document.head.appendChild(script);
        
        // Timeout after 5 seconds
        setTimeout(() => {
            if (typeof JSZip === 'undefined') {
                reject(new Error('JSZip library load timeout'));
            }
        }, 5000);
    });
}

// Function to download selected images as ZIP
async function downloadSelectedImagesAsZip() {
    const checkboxes = document.querySelectorAll('.blind-card-checkbox:checked');
    
    if (checkboxes.length === 0) {
        alert('Please select at least one image to download.');
        return;
    }
    
    const zipBtn = document.getElementById('downloadZipBtn');
    const originalText = zipBtn.innerHTML;
    
    try {
        // Disable button and show loading state
        zipBtn.disabled = true;
        zipBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Preparing ZIP...';
        
        // Collect image data
        const imageData = [];
        checkboxes.forEach(checkbox => {
            const imageUrl = checkbox.getAttribute('data-image-url');
            const blindId = checkbox.getAttribute('data-blind-id');
            const blindColor = checkbox.getAttribute('data-blind-color') || 'blind';
            if (imageUrl) {
                const blindCard = checkbox.closest('.blind-card');
                const imgElement = blindCard ? blindCard.querySelector('img') : null;
                imageData.push({ url: imageUrl, element: imgElement, blindId: blindId, color: blindColor });
            }
        });
        
        if (imageData.length === 0) {
            alert('No images found in selected blinds.');
            zipBtn.disabled = false;
            zipBtn.innerHTML = originalText;
            return;
        }
        
        // Ensure JSZip is loaded
        zipBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Loading ZIP library...';
        await ensureJSZip();
        
        zipBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Creating ZIP file...';
        const zip = new JSZip();
        let loadedCount = 0;
        
        // Fetch all images and add to ZIP
        for (let i = 0; i < imageData.length; i++) {
            const imageInfo = imageData[i];
            try {
                let blob = null;
                
                // Try fetch first
                try {
                    const response = await fetch(imageInfo.url);
                    if (!response.ok) {
                        throw new Error('Failed to fetch image: ' + response.status);
                    }
                    blob = await response.blob();
                    if (!blob.type.startsWith('image/')) {
                        throw new Error('Response is not an image type');
                    }
                } catch (fetchError) {
                    // Fallback to canvas method
                    console.log(`Fetch failed for image ${i + 1}, trying canvas method:`, fetchError);
                    if (imageInfo.element) {
                        blob = await copyImageViaCanvasToBlob(imageInfo.url, imageInfo.element);
                    } else {
                        blob = await copyImageViaCanvasToBlob(imageInfo.url);
                    }
                }
                
                // Generate filename
                const colorName = (imageInfo.color || 'blind').replace(/[^a-z0-9]/gi, '-').toLowerCase();
                const extension = blob.type.split('/')[1] || 'png';
                const fileName = `${colorName}-${i + 1}.${extension}`;
                
                zip.file(fileName, blob);
                loadedCount++;
            } catch (error) {
                console.error(`Failed to load image ${i + 1} for ZIP:`, error);
            }
        }
        
        if (loadedCount > 0) {
            zipBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Generating ZIP...';
            const zipBlob = await zip.generateAsync({ type: 'blob' });
            const url = URL.createObjectURL(zipBlob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `blinds-${imageData.length}-images.zip`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            
            zipBtn.innerHTML = '<i class="bi bi-check-circle"></i> Downloaded!';
            setTimeout(() => {
                zipBtn.innerHTML = originalText;
                zipBtn.disabled = false;
            }, 2000);
        } else {
            throw new Error('Failed to load any images for ZIP');
        }
        
    } catch (error) {
        console.error('ZIP download failed:', error);
        alert('Could not create ZIP file. Please try again or use the Share button.\n\nError: ' + error.message);
        zipBtn.disabled = false;
        zipBtn.innerHTML = originalText;
    }
}

// Initialize selection info on page load
document.addEventListener('DOMContentLoaded', function() {
    updateSelectionInfo();
    updateToggleButton();
});
</script>

