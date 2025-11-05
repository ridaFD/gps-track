<!-- intl-tel-input CSS - try multiple CDNs as fallback -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.0/build/css/intlTelInput.css" 
      onload="console.log('[DEBUG] intl-tel-input CSS loaded from jsDelivr')" 
      onerror="console.error('[DEBUG] Failed to load from jsDelivr, trying unpkg...'); this.href='https://unpkg.com/intl-tel-input@19.2.0/build/css/intlTelInput.css'; this.onerror=function(){console.error('[DEBUG] Failed to load from unpkg, trying cdnjs...'); this.href='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/19.2.0/css/intlTelInput.min.css';}">

<!-- intl-tel-input JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/19.2.0/js/intlTelInput.min.js" onload="console.log('[DEBUG] intl-tel-input JS loaded')" onerror="console.error('[DEBUG] Failed to load intl-tel-input JS')"></script>

<style>
/* Ensure flag sprite image loads - but DON'T override background-position */
/* The library's CSS sets country-specific background-position */
/* Note: We let the library's CSS handle background-position completely */
/* We only ensure the sprite image URLs are correct */

/* Ensure intl-tel-input displays properly and override Orchid styles */
.iti {
    width: 100% !important;
    display: block !important;
    position: relative;
}

.iti__flag-container {
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    z-index: 2;
    display: flex;
    align-items: center;
}

.iti__selected-flag {
    padding: 0 8px 0 8px !important;
    height: 100% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    border-right: 1px solid #ced4da !important;
    cursor: pointer;
    background-color: #f8f9fa !important;
    transition: background-color 0.2s;
    min-width: 52px;
    position: relative;
    z-index: 1;
}

.iti__selected-flag:hover {
    background-color: #e9ecef !important;
}

/* Flag styling - intl-tel-input uses CSS sprite images */
.iti__flag-box {
    display: inline-block !important;
    visibility: visible !important;
    opacity: 1 !important;
    width: 20px !important;
    height: 15px !important;
    line-height: 0 !important;
    position: relative !important;
}

.iti__flag {
    width: 20px !important;
    height: 15px !important;
    margin-right: 6px !important;
    display: inline-block !important;
    visibility: visible !important;
    opacity: 1 !important;
    vertical-align: middle !important;
    border: none !important;
    /* DO NOT override background-position - let library CSS handle country-specific positioning */
}

/* Additional flag visibility fixes */
.iti .iti__flag-box:not([style*="display: none"]) {
    display: inline-block !important;
}

.iti__selected-flag .iti__flag {
    display: inline-block !important;
    visibility: visible !important;
}

.iti__arrow {
    margin-left: 4px !important;
    width: 0 !important;
    height: 0 !important;
    border-top: 4px solid #495057 !important;
    border-left: 4px solid transparent !important;
    border-right: 4px solid transparent !important;
    border-bottom: none !important;
    vertical-align: middle !important;
}

.iti input[type="tel"],
.iti input[type="text"] {
    padding-left: 52px !important;
    width: 100% !important;
}

.iti.iti--separate-dial-code {
    display: flex !important;
    align-items: stretch !important;
}

.iti.iti--separate-dial-code .iti__selected-flag {
    border-right: 1px solid #ced4da !important;
    padding: 0 10px 0 8px !important;
    flex-shrink: 0;
    margin-right: 0 !important;
}

.iti.iti--separate-dial-code .iti__selected-dial-code {
    padding: 0 12px 0 16px !important;
    margin-left: 0 !important;
    display: flex !important;
    align-items: center !important;
    background-color: #f8f9fa !important;
    border-right: 1px solid #ced4da !important;
    border-left: 1px solid #ced4da !important;
    color: #495057;
    font-size: 14px;
    font-weight: 500;
    min-width: 50px;
}

.iti.iti--separate-dial-code input[type="tel"],
.iti.iti--separate-dial-code input[type="text"] {
    padding-left: 16px !important;
    flex: 1;
    border-left: none !important;
}

.iti__country-list {
    z-index: 1000 !important;
    max-height: 300px !important;
    overflow-y: auto !important;
    border: 1px solid #ced4da !important;
    border-radius: 4px !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
    background: white !important;
    margin-top: 2px !important;
    width: 300px !important;
}

.iti__country {
    padding: 8px 10px !important;
    display: flex !important;
    align-items: center !important;
    cursor: pointer;
    border-bottom: 1px solid #f0f0f0;
}

.iti__country:last-child {
    border-bottom: none !important;
}

.iti__country:hover {
    background-color: #f8f9fa !important;
}

.iti__country-name {
    margin-left: 8px !important;
    font-size: 14px !important;
    flex: 1;
}

.iti__dial-code {
    margin-left: auto !important;
    color: #6c757d !important;
    font-size: 14px !important;
    padding-left: 8px;
}

.iti__search-box {
    padding: 10px !important;
    border-bottom: 1px solid #ced4da !important;
    width: 100% !important;
    box-sizing: border-box !important;
    font-size: 14px !important;
    margin: 0 !important;
}

/* Ensure the form group container displays correctly */
.form-group:has(.iti) {
    position: relative;
}

/* Fix any overflow issues */
.iti__flag-box {
    display: inline-block !important;
    width: 20px !important;
    height: 15px !important;
    line-height: 0;
}

/* Ensure proper display for separate dial code mode */
.iti.iti--separate-dial-code {
    display: flex !important;
    flex-direction: row !important;
    align-items: stretch !important;
}

.iti.iti--separate-dial-code .iti__flag-container {
    position: static !important;
    display: flex !important;
}

.iti.iti--separate-dial-code .iti__selected-flag {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 46px !important;
    flex-shrink: 0;
    padding-right: 10px !important;
    margin-right: 0 !important;
}

.iti.iti--separate-dial-code .iti__selected-dial-code {
    display: flex !important;
    align-items: center !important;
    padding: 0 12px 0 16px !important;
    margin-left: 0 !important;
    min-width: 50px !important;
    flex-shrink: 0;
    font-weight: 500;
    border-left: 1px solid #ced4da !important;
}
</style>

<script>
(function() {
    let checkTimeout;
    let lastCheckedPhone = '';
    let iti = null; // intl-tel-input instance
    
    // Function to find phone input with multiple selectors
    function findPhoneInput() {
        // Try different possible selectors (for both order and customer screens)
        const selectors = [
            'input[name="order[customer_phone]"]',
            'input[name="customer[phone]"]',
            'input[name*="customer_phone"]',
            'input[name*="[phone]"]',
            'input[type="tel"][name*="customer_phone"]',
            'input[type="tel"][name*="phone"]',
            'input[type="tel"]'
        ];
        
        for (const selector of selectors) {
            const input = document.querySelector(selector);
            if (input) {
                console.log('Found phone input with selector:', selector);
                return input;
            }
        }
        
        console.warn('Phone input not found with any selector');
        return null;
    }
    
    function initializePhoneInput() {
        const phoneInput = findPhoneInput();
        const firstNameInput = document.querySelector('input[name="order[customer_first_name]"]') || 
                               document.querySelector('input[name="customer[first_name]"]') ||
                               document.querySelector('input[name*="customer_first_name"]') ||
                               document.querySelector('input[name*="first_name"]');
        const lastNameInput = document.querySelector('input[name="order[customer_last_name]"]') || 
                              document.querySelector('input[name="customer[last_name]"]') ||
                              document.querySelector('input[name*="customer_last_name"]') ||
                              document.querySelector('input[name*="last_name"]');
        
        if (!phoneInput) {
            console.warn('Phone input field not found, retrying...');
            setTimeout(initializePhoneInput, 500);
            return;
        }
        
        // Check if intl-tel-input library is loaded
        if (typeof intlTelInput === 'undefined') {
            console.error('intl-tel-input library not loaded');
            // Try loading again after a delay
            setTimeout(initializePhoneInput, 500);
            return;
        }
        
        // Check if already initialized
        if (phoneInput.closest('.iti')) {
            console.log('intl-tel-input already initialized on this field');
            return;
        }
        
        console.log('Initializing intl-tel-input on phone field...');
        
        // Get existing phone value if editing
        // Get existing phone from input value, but also check for data attribute or stored value
        let existingPhone = phoneInput.value || phoneInput.getAttribute('value') || '';
        
        // If the phone is in E.164 format but doesn't have +, ensure it does
        // Also, if it has spaces or formatting, we'll let intl-tel-input handle it
        if (existingPhone && existingPhone.trim()) {
            existingPhone = existingPhone.trim();
        }
        
        console.log('[DEBUG] Initial phone input value:', existingPhone);
        
        try {
            // All African countries (ISO2 codes) for preferred countries list
            const africanCountries = [
                // Northern Africa
                'dz', // Algeria
                'eg', // Egypt
                'ly', // Libya
                'ma', // Morocco
                'sd', // Sudan
                'tn', // Tunisia
                'eh', // Western Sahara
                // Western Africa
                'bj', // Benin
                'bf', // Burkina Faso
                'cv', // Cape Verde
                'ci', // Côte d'Ivoire
                'gm', // Gambia
                'gh', // Ghana
                'gn', // Guinea
                'gw', // Guinea-Bissau
                'lr', // Liberia
                'ml', // Mali
                'mr', // Mauritania
                'ne', // Niger
                'ng', // Nigeria
                'sn', // Senegal
                'sl', // Sierra Leone
                'tg', // Togo
                // Central Africa
                'ao', // Angola
                'cm', // Cameroon
                'cf', // Central African Republic
                'td', // Chad
                'cg', // Congo (Brazzaville)
                'cd', // Democratic Republic of the Congo
                'gq', // Equatorial Guinea
                'ga', // Gabon
                'st', // São Tomé and Príncipe
                // Eastern Africa
                'bi', // Burundi
                'km', // Comoros
                'dj', // Djibouti
                'er', // Eritrea
                'et', // Ethiopia
                'ke', // Kenya
                'mg', // Madagascar
                'mw', // Malawi
                'mu', // Mauritius
                'mz', // Mozambique
                'rw', // Rwanda
                'sc', // Seychelles
                'so', // Somalia
                'ss', // South Sudan
                'tz', // Tanzania
                'ug', // Uganda
                'zm', // Zambia
                'zw', // Zimbabwe
                // Southern Africa
                'bw', // Botswana
                'sz', // Eswatini (Swaziland)
                'ls', // Lesotho
                'na', // Namibia
                'za'  // South Africa
            ];
            
            // Combine with some common international countries for convenience
            const preferredCountries = ['us', 'gb', 'ca', 'au'].concat(africanCountries);
            
            iti = intlTelInput(phoneInput, {
                initialCountry: 'us',
                separateDialCode: true,
                preferredCountries: preferredCountries,
                utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/19.2.0/js/utils.min.js',
                nationalMode: false,
                formatOnDisplay: true,
                autoHideDialCode: false,
                allowDropdown: true,
                autoPlaceholder: 'aggressive',
                dropdownContainer: document.body
            });
            
            console.log('intl-tel-input initialized successfully');
            
            // Verify initialization
            if (!phoneInput.closest('.iti')) {
                console.error('intl-tel-input wrapper not found after initialization');
                return;
            }
        } catch (error) {
            console.error('Error initializing intl-tel-input:', error);
            return;
        }
        
        // Wait for utils script to load before setting number
        const setExistingNumber = function() {
            if (existingPhone) {
                console.log('[DEBUG] Setting existing phone number:', existingPhone);
                try {
                    // Ensure the phone is in E.164 format (starts with +)
                    let phoneToSet = existingPhone.trim();
                    if (phoneToSet && !phoneToSet.startsWith('+')) {
                        // If it doesn't start with +, try to add it
                        // Remove any non-digit characters except +
                        phoneToSet = phoneToSet.replace(/[^\d+]/g, '');
                        if (!phoneToSet.startsWith('+')) {
                            phoneToSet = '+' + phoneToSet;
                        }
                    }
                    
                    console.log('[DEBUG] Phone number to set (normalized):', phoneToSet);
                    
                    // IMPORTANT: Clear the input value BEFORE calling setNumber
                    // This ensures intl-tel-input doesn't try to parse the existing value incorrectly
                    phoneInput.value = '';
                    
                    // Small delay to let the input clear
                    setTimeout(function() {
                        // When using separateDialCode, setNumber should handle the full international format
                        // The library will automatically extract and display the dial code separately
                        iti.setNumber(phoneToSet);
                        
                        // Verify it was set correctly after a brief delay
                        setTimeout(function() {
                            const setValue = phoneInput.value;
                            const e164Value = iti.getNumber();
                            const detectedCountry = iti.getSelectedCountryData();
                            
                            console.log('[DEBUG] After setNumber - Input value (national):', setValue);
                            console.log('[DEBUG] After setNumber - E.164 format (full):', e164Value);
                            console.log('[DEBUG] After setNumber - Detected country:', detectedCountry ? {
                                iso2: detectedCountry.iso2,
                                name: detectedCountry.name,
                                dialCode: detectedCountry.dialCode
                            } : 'null');
                            
                            // Ensure the value persists in a data attribute for form submission
                            if (e164Value) {
                                phoneInput.setAttribute('data-intl-tel-input-value', e164Value);
                                
                                // Verify the display matches what we expect
                                const selectedDialCode = phoneInput.closest('.iti')?.querySelector('.iti__selected-dial-code');
                                if (selectedDialCode) {
                                    console.log('[DEBUG] Selected dial code element text:', selectedDialCode.textContent);
                                }
                            }
                        }, 100);
                    }, 50);
                    
                        // Force flag to display after setting number
                        setTimeout(function() {
                            const flagBox = phoneInput.closest('.iti')?.querySelector('.iti__flag-box');
                            const flag = phoneInput.closest('.iti')?.querySelector('.iti__flag');
                            
                            if (flagBox) {
                                flagBox.style.display = 'inline-block';
                                flagBox.style.visibility = 'visible';
                                flagBox.style.opacity = '1';
                            }
                            
                            if (flag) {
                                flag.style.display = 'inline-block';
                                flag.style.visibility = 'visible';
                                flag.style.opacity = '1';
                                
                                // Check if background image is loaded
                                const computedStyle = window.getComputedStyle(flag);
                                const bgImage = computedStyle.backgroundImage;
                                console.log('[DEBUG] Initial flag background-image:', bgImage);
                                
                                if (!bgImage || bgImage === 'none') {
                                    console.warn('[DEBUG] Flag sprite CSS may not be loaded from CDN');
                                }
                            }
                        }, 100);
                } catch (e) {
                    console.error('[DEBUG] Could not parse existing phone number:', e);
                }
            } else {
                console.log('[DEBUG] No existing phone number to set');
            }
        };
        
        if (iti.utilsScript) {
            console.log('[DEBUG] Utils script configured, waiting 300ms...');
            // Wait for utils to load
            setTimeout(setExistingNumber, 300);
        } else {
            console.log('[DEBUG] No utils script, setting number immediately');
            setExistingNumber();
        }
        
        // Auto-detect country when user types a number with country code
        // Use a debounced approach to detect country after user stops typing
        let autoDetectTimeout;
        let lebanonAutoDetectTimeout;
        let lastDetectedCountry = '';
        
        phoneInput.addEventListener('input', function(e) {
            const currentValue = phoneInput.value.trim();
            console.log('[DEBUG] Input event - currentValue:', currentValue);
            
            // Clear previous timeouts
            if (autoDetectTimeout) {
                clearTimeout(autoDetectTimeout);
            }
            if (lebanonAutoDetectTimeout) {
                clearTimeout(lebanonAutoDetectTimeout);
            }
            
            // Check if user entered an 8-digit number without country code
            // Remove all non-digit characters to check if it's exactly 8 digits
            const digitsOnly = currentValue.replace(/\D/g, '');
            
            // If exactly 8 digits and doesn't start with +, debounce and then set to Lebanon
            if (digitsOnly.length === 8 && !currentValue.startsWith('+')) {
                // Debounce: wait 600ms after user stops typing
                lebanonAutoDetectTimeout = setTimeout(function() {
                    const valueSnap = phoneInput.value.trim();
                    const digitsSnap = valueSnap.replace(/\D/g, '');
                    
                    // Double-check: still 8 digits and no + prefix
                    if (digitsSnap.length === 8 && !valueSnap.startsWith('+')) {
                        const currentCountry = iti.getSelectedCountryData();
                        
                        // Only auto-set to Lebanon if not already Lebanon or if country is default (us)
                        if (!currentCountry || currentCountry.iso2 !== 'lb') {
                            console.log('[DEBUG] Detected 8-digit number without country code - setting to Lebanon (+961)');
                            
                            // Set country to Lebanon
                            iti.setCountry('lb');
                            
                            // Format as +961 + 8 digits
                            const lebanonNumber = '+961' + digitsSnap;
                            iti.setNumber(lebanonNumber);
                            
                            console.log('[DEBUG] Auto-formatted as:', lebanonNumber);
                        }
                    }
                }, 600); // Wait 600ms after user stops typing
                
                return; // Don't continue with other auto-detection for now
            }
            
            // Only try to auto-detect if:
            // 1. User types a number starting with +
            // 2. The value has enough digits to potentially identify a country code (at least 4 chars, e.g., +123)
            if (currentValue.startsWith('+') && currentValue.length >= 4) {
                console.log('[DEBUG] Number starts with + and has 4+ chars, scheduling auto-detect...');
                autoDetectTimeout = setTimeout(function() {
                    const valueSnap = phoneInput.value.trim();
                    console.log('[DEBUG] Auto-detect timeout fired - valueSnap:', valueSnap);
                    
                    // Double check the value hasn't changed during timeout
                    if (valueSnap.startsWith('+') && valueSnap.length >= 4) {
                        try {
                            // Check current selected country
                            const currentCountry = iti.getSelectedCountryData();
                            console.log('[DEBUG] Current country before detection:', currentCountry ? {
                                iso2: currentCountry.iso2,
                                name: currentCountry.name,
                                dialCode: currentCountry.dialCode
                            } : 'null');
                            
                            // Try to detect country from the typed number
                            // The library's setNumber will parse and select the correct country
                            // Store cursor position before formatting
                            const cursorBefore = phoneInput.selectionStart;
                            
                            console.log('[DEBUG] Calling iti.setNumber with:', valueSnap);
                            
                            // When using separateDialCode, setNumber will extract the dial code
                            // and show it separately, keeping only the national number in the input
                            iti.setNumber(valueSnap);
                            
                            // Check what happened after setNumber
                            const newCountry = iti.getSelectedCountryData();
                            const formattedNumber = iti.getNumber();
                            let nationalNumber = null;
                            try {
                                if (typeof intlTelInputUtils !== 'undefined') {
                                    nationalNumber = iti.getNumber(intlTelInputUtils.numberFormat.NATIONAL);
                                }
                            } catch (e) {
                                console.warn('[DEBUG] Could not get national number format:', e);
                            }
                            
                            console.log('[DEBUG] After setNumber - newCountry:', newCountry ? {
                                iso2: newCountry.iso2,
                                name: newCountry.name,
                                dialCode: newCountry.dialCode
                            } : 'null');
                            console.log('[DEBUG] After setNumber - formattedNumber (E.164):', formattedNumber);
                            console.log('[DEBUG] After setNumber - nationalNumber:', nationalNumber);
                            console.log('[DEBUG] After setNumber - phoneInput.value:', phoneInput.value);
                            
                            // Force flag to display after country detection
                            setTimeout(function() {
                                const flagBox = phoneInput.closest('.iti')?.querySelector('.iti__flag-box');
                                const flag = phoneInput.closest('.iti')?.querySelector('.iti__flag');
                                
                                if (flagBox) {
                                    flagBox.style.display = 'inline-block';
                                    flagBox.style.visibility = 'visible';
                                    flagBox.style.opacity = '1';
                                    console.log('[DEBUG] Flag box displayed');
                                } else {
                                    console.warn('[DEBUG] Flag box not found!');
                                }
                                
                                if (flag) {
                                    flag.style.display = 'inline-block';
                                    flag.style.visibility = 'visible';
                                    flag.style.opacity = '1';
                                    
                                    // Don't override background-size or background-image
                                    // The library CSS handles these correctly
                                    if (flag.className) {
                                        console.log('[DEBUG] Flag classes:', flag.className);
                                    }
                                    
                                    // Check if background image exists
                                    const computedStyle = window.getComputedStyle(flag);
                                    const bgImage = computedStyle.backgroundImage;
                                    const bgPosition = computedStyle.backgroundPosition;
                                    const bgSize = computedStyle.backgroundSize;
                                    console.log('[DEBUG] Flag background-image:', bgImage);
                                    console.log('[DEBUG] Flag background-position:', bgPosition);
                                    console.log('[DEBUG] Flag background-size:', bgSize, '(should be 5762px 15px for correct sprite alignment)');
                                    console.log('[DEBUG] Flag className:', flag.className);
                                    
                                    // If background-size is wrong, fix it
                                    if (bgSize !== '5762px 15px' && bgSize !== '2881px 7.5px') {
                                        console.warn('[DEBUG] ⚠️ Background-size mismatch! Expected ~5762px, got:', bgSize, '- fixing...');
                                        flag.style.backgroundSize = '5762px 15px';
                                        // Re-check after fix
                                        setTimeout(() => {
                                            const newSize = window.getComputedStyle(flag).backgroundSize;
                                            console.log('[DEBUG] After fix - background-size:', newSize);
                                        }, 100);
                                    }
                                    
                                    // If background-position is 0% 0% or 0px 0px, the country-specific CSS didn't load
                                    // Fetch the CSS and extract the correct background-position for this country
                                    const bgPosValue = String(bgPosition).trim();
                                    const isZeroPosition = bgPosValue === '0% 0%' || bgPosValue === '0px 0px' || bgPosValue === '0 0' || bgPosValue === '0%' || /^0\s*(px|%)?\s*0/.test(bgPosValue);
                                    
                                    if (newCountry && isZeroPosition) {
                                        console.warn('[DEBUG] Background position is 0,0 - CSS rules not loaded. Fetching CSS rule...');
                                        
                                        const countryCode = newCountry.iso2.toLowerCase();
                                        
                                        // Fetch the CSS file and extract the background-position for this country
                                        fetch('https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.0/build/css/intlTelInput.css')
                                            .then(response => {
                                                if (!response.ok) {
                                                    throw new Error('Failed to fetch CSS');
                                                }
                                                return response.text();
                                            })
                                            .then(cssText => {
                                                // Find the CSS rule for this country flag
                                                // Pattern: .iti__flag.iti__lb { ... background-position: -2024px 0px; ... }
                                                const regex = new RegExp(`\\.iti__flag\\.iti__${countryCode}\\s*\\{[^}]*background-position:\\s*([^;\\}]+)`, 'i');
                                                const match = cssText.match(regex);
                                                
                                                if (match && match[1]) {
                                                    const position = match[1].trim();
                                                    flag.style.backgroundPosition = position;
                                                    console.log('[DEBUG] ✓ Injected CSS rule for', countryCode, '- position:', position);
                                                    
                                                    // Also inject into a style tag for persistence
                                                    let styleEl = document.getElementById('iti-flag-fixes');
                                                    if (!styleEl) {
                                                        styleEl = document.createElement('style');
                                                        styleEl.id = 'iti-flag-fixes';
                                                        document.head.appendChild(styleEl);
                                                    }
                                                    const countryRule = `.iti__flag.iti__${countryCode} { background-position: ${position} !important; }`;
                                                    if (!styleEl.textContent.includes(countryRule)) {
                                                        styleEl.textContent += '\n' + countryRule;
                                                    }
                                                } else {
                                                    console.error('[DEBUG] ✗ Could not find CSS rule for country:', countryCode);
                                                }
                                            })
                                            .catch(err => {
                                                console.error('[DEBUG] Failed to fetch CSS:', err);
                                            });
                                    } else if (newCountry) {
                                        console.log('[DEBUG] Background position is correctly set:', bgPosition, 'for country', newCountry.iso2);
                                    }
                                    
                                    if (bgImage && bgImage !== 'none') {
                                        console.log('[DEBUG] Flag background image is set');
                                    } else {
                                        console.error('[DEBUG] Flag background image is NOT set! CSS sprite may not be loading.');
                                    }
                                    
                                    console.log('[DEBUG] Flag displayed');
                                } else {
                                    console.warn('[DEBUG] Flag element not found!');
                                }
                            }, 100);
                            
                            // Check if country changed
                            if (newCountry && (!currentCountry || newCountry.iso2 !== currentCountry.iso2)) {
                                // Country was auto-detected successfully
                                lastDetectedCountry = newCountry.iso2;
                                console.log('[DEBUG] Country changed from', currentCountry?.iso2 || 'null', 'to', newCountry.iso2);
                                
                                // Try to restore cursor position after a short delay
                                setTimeout(function() {
                                    try {
                                        // With separateDialCode, the input only contains the national number
                                        // So we want cursor at the end of the input
                                        if (phoneInput.setSelectionRange) {
                                            const newValue = phoneInput.value;
                                            const targetPos = Math.min(cursorBefore, newValue.length);
                                            phoneInput.setSelectionRange(targetPos, targetPos);
                                        }
                                    } catch (e) {
                                        console.warn('[DEBUG] Error setting cursor position:', e);
                                    }
                                }, 50);
                                
                                // The library automatically triggers countrychange event, so we don't need to manually dispatch it
                            } else {
                                console.log('[DEBUG] Country did not change or is the same');
                            }
                        } catch (e) {
                            console.error('[DEBUG] Error during auto-detection:', e);
                            console.error('[DEBUG] Error stack:', e.stack);
                        }
                    } else {
                        console.log('[DEBUG] Value changed during timeout or no longer starts with +');
                    }
                }, 400); // Wait 400ms after user stops typing
            } else {
                console.log('[DEBUG] Not triggering auto-detect - value:', currentValue, 'startsWith +:', currentValue.startsWith('+'), 'length:', currentValue.length);
            }
        });
        
        // Update input with formatted number before form submission
        const form = phoneInput.closest('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Get the full international number (E.164 format) and update the input
                // This is important when using separateDialCode mode where the input only shows national number
                try {
                    // Use the global reference or local iti variable
                    const itiInstance = window.phoneInputIti || iti;
                    const phoneInputField = window.phoneInputField || phoneInput;
                    
                    if (itiInstance && phoneInputField) {
                        let fullNumber = null;
                        
                        // Try to get the number from intl-tel-input
                        try {
                            fullNumber = itiInstance.getNumber();
                            console.log('[DEBUG] Form submit - Full E.164 from iti.getNumber():', fullNumber);
                        } catch (err) {
                            console.warn('[DEBUG] Form submit - Error getting number from iti:', err);
                        }
                        
                        // If we didn't get a number or it doesn't start with +, try to construct it
                        if (!fullNumber || !fullNumber.startsWith('+')) {
                            const currentValue = phoneInputField.value || '';
                            const countryData = itiInstance.getSelectedCountryData();
                            const dialCode = countryData?.dialCode;
                            
                            console.log('[DEBUG] Form submit - Constructing number:', {
                                currentValue: currentValue,
                                dialCode: dialCode,
                                countryIso2: countryData?.iso2
                            });
                            
                            if (dialCode && currentValue) {
                                // Remove any non-digit characters from the current value
                                const nationalNumber = currentValue.replace(/[^\d]/g, '');
                                if (nationalNumber) {
                                    fullNumber = '+' + dialCode + nationalNumber;
                                    console.log('[DEBUG] Form submit - Constructed E.164:', fullNumber);
                                }
                            }
                        }
                        
                        if (fullNumber && fullNumber.startsWith('+')) {
                            // Set the full international number in the input
                            // Use multiple methods to ensure it's captured
                            phoneInputField.value = fullNumber;
                            phoneInputField.setAttribute('value', fullNumber);
                            phoneInputField.setAttribute('data-intl-tel-input-value', fullNumber);
                            
                            // Force a sync by triggering input event
                            phoneInputField.dispatchEvent(new Event('input', { bubbles: true }));
                            
                            console.log('[DEBUG] Form submit - ✅ Updated phone input with E.164:', fullNumber);
                            console.log('[DEBUG] Form submit - Input value after update:', phoneInputField.value);
                        } else {
                            console.error('[DEBUG] Form submit - ❌ Could not get valid E.164 number!', {
                                fullNumber: fullNumber,
                                currentValue: phoneInputField.value,
                                hasIti: !!itiInstance
                            });
                        }
                    } else {
                        console.warn('[DEBUG] Form submit - intl-tel-input not initialized', {
                            iti: !!itiInstance,
                            phoneInput: !!phoneInputField
                        });
                    }
                } catch (e) {
                    // If error getting formatted number, log and use current value
                    console.error('[DEBUG] Error formatting phone number on submit:', e);
                }
            }, true); // Use capture phase to run before other handlers
        }
        
        // Style the wrapper to match Shopify's look and ensure flags display
        const wrapper = phoneInput.closest('.form-group') || phoneInput.parentElement;
        if (wrapper) {
            const itiWrapper = phoneInput.closest('.iti') || phoneInput.parentElement.querySelector('.iti');
            if (itiWrapper) {
                itiWrapper.style.width = '100%';
                
                // Ensure flag is visible after initialization - check multiple times
                const ensureFlagVisible = function(attempts) {
                    attempts = attempts || 0;
                    if (attempts > 10) return; // Stop after 10 attempts
                    
                    const flagBox = itiWrapper.querySelector('.iti__flag-box');
                    const flag = itiWrapper.querySelector('.iti__flag');
                    
                    if (flagBox) {
                        flagBox.style.display = 'inline-block';
                        flagBox.style.visibility = 'visible';
                        flagBox.style.opacity = '1';
                    }
                    
                    if (flag) {
                        flag.style.display = 'inline-block';
                        flag.style.visibility = 'visible';
                        flag.style.opacity = '1';
                        // Force background image to show
                        if (!flag.style.backgroundImage && flag.classList.contains('iti__flag')) {
                            // Flag sprite should be loaded from CSS, but ensure it's applied
                            flag.style.width = '20px';
                            flag.style.height = '15px';
                        }
                    }
                    
                    // Also ensure selected flag container is visible
                    const selectedFlag = itiWrapper.querySelector('.iti__selected-flag');
                    if (selectedFlag) {
                        selectedFlag.style.display = 'flex';
                        selectedFlag.style.alignItems = 'center';
                    }
                    
                    // If flag still not visible, try again
                    if (!flag || !flag.offsetParent) {
                        setTimeout(function() {
                            ensureFlagVisible(attempts + 1);
                        }, 100);
                    }
                };
                
                // Initial check
                setTimeout(function() {
                    ensureFlagVisible(0);
                }, 100);
                
                // Additional check after longer delay
                setTimeout(function() {
                    ensureFlagVisible(0);
                }, 500);
            }
        }
        
        // Get order ID from URL if editing
        const urlMatch = window.location.pathname.match(/\/orders\/(\d+)\/edit/);
        const orderId = urlMatch ? urlMatch[1] : null;
        
        // Create warning message container - place it after the phone input wrapper
        let warningContainer = document.getElementById('customer-warning-container');
        if (!warningContainer) {
            warningContainer = document.createElement('div');
            warningContainer.id = 'customer-warning-container';
            warningContainer.style.cssText = 'margin-top: 10px; padding: 10px; border-radius: 4px;';
            // Insert after the phone input's parent container
            const phoneContainer = phoneInput.closest('.form-group') || phoneInput.parentElement;
            if (phoneContainer && phoneContainer.parentElement) {
                phoneContainer.parentElement.insertBefore(warningContainer, phoneContainer.nextSibling);
            } else {
                phoneInput.parentElement.appendChild(warningContainer);
            }
        }
        
        // Set iti reference in outer scope for checkCustomer function
        window.phoneInputIti = iti;
        window.phoneInputField = phoneInput;
        
        function showWarning(message) {
            warningContainer.innerHTML = '<div class="alert alert-warning" role="alert">' +
                '<strong>⚠ Warning:</strong> ' + message +
                '</div>';
            warningContainer.style.display = 'block';
        }
        
        function hideWarning() {
            warningContainer.innerHTML = '';
            warningContainer.style.display = 'none';
        }
        
        function checkCustomer() {
            // Get phone number using intl-tel-input API if available, otherwise use raw value
            const phoneInputField = window.phoneInputField || phoneInput;
            const itiInstance = window.phoneInputIti || iti;
            let phone;
            
            if (itiInstance && phoneInputField) {
                try {
                    // Try to get E.164 format (international format with +)
                    const number = itiInstance.getNumber();
                    if (number) {
                        phone = number;
                    } else {
                        // Fallback to raw value
                        phone = phoneInputField.value.trim();
                    }
                } catch (e) {
                    // If error, use raw value
                    phone = phoneInputField.value.trim();
                }
            } else if (phoneInputField) {
                // Fallback to raw value if intl-tel-input is not initialized
                phone = phoneInputField.value.trim();
            } else {
                return; // No phone input found
            }
            
            const firstNameInput = document.querySelector('input[name="order[customer_first_name]"]') || 
                                   document.querySelector('input[name="customer[first_name]"]') ||
                                   document.querySelector('input[name*="customer_first_name"]') ||
                                   document.querySelector('input[name*="first_name"]');
            const lastNameInput = document.querySelector('input[name="order[customer_last_name]"]') || 
                                  document.querySelector('input[name="customer[last_name]"]') ||
                                  document.querySelector('input[name*="customer_last_name"]') ||
                                  document.querySelector('input[name*="last_name"]');
            
            const firstName = firstNameInput ? firstNameInput.value.trim() : '';
            const lastName = lastNameInput ? lastNameInput.value.trim() : '';
            
            // Skip customer check on customer edit screen
            // Check if we're on customer edit screen (not order edit screen)
            @php
                $isCustomerEditScreen = false;
                try {
                    $currentRoute = request()->route()->getName();
                    // If we're on customer edit screen, skip the check
                    if ($currentRoute === 'platform.customers.edit') {
                        $isCustomerEditScreen = true;
                    }
                } catch (\Exception $e) {
                    // Can't determine route, assume it's not customer edit
                }
                
                // Only check route if not on customer edit screen
                $customerCheckRoute = null;
                if (!$isCustomerEditScreen) {
                    try {
                        $customerCheckRoute = route('platform.orders.check-customer');
                    } catch (\Exception $e) {
                        // Route doesn't exist
                        $customerCheckRoute = null;
                    }
                }
            @endphp
            const isCustomerEditScreen = @json($isCustomerEditScreen);
            const checkCustomerUrl = @json($customerCheckRoute);
            
            if (isCustomerEditScreen || !checkCustomerUrl) {
                // On customer edit screen, skip customer existence check
                // (We're already editing an existing customer, no need to check)
                return;
            }
            
            // Only check if phone number is different from last check
            if (phone === lastCheckedPhone) {
                return;
            }
            
            lastCheckedPhone = phone;
            
            // Clear previous timeout
            if (checkTimeout) {
                clearTimeout(checkTimeout);
            }
            
            // Hide warning if phone is empty
            if (!phone) {
                hideWarning();
                return;
            }
            
            // Debounce: wait 800ms after user stops typing
            checkTimeout = setTimeout(function() {
                // Get CSRF token
                let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!csrfToken) {
                    const tokenInput = document.querySelector('input[name="_token"]');
                    csrfToken = tokenInput ? tokenInput.value : '';
                }
                
                // Make AJAX request
                fetch(checkCustomerUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        phone: phone,
                        firstName: firstName,
                        lastName: lastName,
                        customerName: '',
                        orderId: orderId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        showWarning(data.message);
                    } else {
                        hideWarning();
                    }
                })
                .catch(error => {
                    console.error('Error checking customer:', error);
                });
            }, 800);
        }
        
        // Listen to input events on phone field
        phoneInput.addEventListener('input', checkCustomer);
        phoneInput.addEventListener('blur', function() {
            // Check immediately on blur
            if (checkTimeout) {
                clearTimeout(checkTimeout);
            }
            checkCustomer();
        });
        
        // Also listen to country change if intl-tel-input is initialized
        if (iti && phoneInput) {
            phoneInput.addEventListener('countrychange', function() {
                // Re-check when country changes
                lastCheckedPhone = ''; // Reset to force re-check
                checkCustomer();
            });
        }
        
        // Also check when first/last name changes (in case phone was empty)
        if (firstNameInput) {
            firstNameInput.addEventListener('input', function() {
                if (!phoneInput.value.trim()) {
                    checkCustomer();
                }
            });
        }
        
        if (lastNameInput) {
            lastNameInput.addEventListener('input', function() {
                if (!phoneInput.value.trim()) {
                    checkCustomer();
                }
            });
        }
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializePhoneInput);
    } else {
        // DOM is already loaded
        initializePhoneInput();
    }
})();
</script>

