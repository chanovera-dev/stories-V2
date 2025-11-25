/**
 * Filter Listeners
 * 
 * Handles range input and number input listeners for property filters.
 * Updates display values and triggers AJAX fetch when changed.
 * Synchronizes numeric inputs with range sliders.
 * Formats numbers with thousands separators.
 */

// Function to format numbers with thousands separators
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('property-filters');
    
    if (!form) return;

    // Sync price range slider with number inputs
    const priceRangeInput = form.querySelector('#price_range');
    const priceMinInput = form.querySelector('input[name="price_min"]');
    const priceMaxInput = form.querySelector('input[name="price_max"]');

    if (priceRangeInput) {
        priceRangeInput.addEventListener('input', function () {
            const valueSpan = document.getElementById('price-range-value');
            if (valueSpan) {
                valueSpan.textContent = formatNumber(this.value);
            }
            if (priceMinInput && !priceMinInput.value) {
                priceMinInput.value = this.value;
            }
        });

        priceRangeInput.addEventListener('change', function () {
            const changeEvent = new Event('change', { bubbles: true });
            form.dispatchEvent(changeEvent);
        });
    }

    // Sync construction range slider with number inputs
    const constructionRangeInput = form.querySelector('#construction_range');
    const constructionMinInput = form.querySelector('input[name="construction_min"]');
    const constructionMaxInput = form.querySelector('input[name="construction_max"]');

    if (constructionRangeInput) {
        constructionRangeInput.addEventListener('input', function () {
            const valueSpan = document.getElementById('construction-range-value');
            if (valueSpan) {
                valueSpan.textContent = formatNumber(this.value);
            }
            if (constructionMinInput && !constructionMinInput.value) {
                constructionMinInput.value = this.value;
            }
        });

        constructionRangeInput.addEventListener('change', function () {
            const changeEvent = new Event('change', { bubbles: true });
            form.dispatchEvent(changeEvent);
        });
    }

    // Sync land range slider with number inputs
    const landRangeInput = form.querySelector('#land_range');
    const landMinInput = form.querySelector('input[name="land_min"]');
    const landMaxInput = form.querySelector('input[name="land_max"]');

    if (landRangeInput) {
        landRangeInput.addEventListener('input', function () {
            const valueSpan = document.getElementById('land-range-value');
            if (valueSpan) {
                valueSpan.textContent = formatNumber(this.value);
            }
            if (landMinInput && !landMinInput.value) {
                landMinInput.value = this.value;
            }
        });

        landRangeInput.addEventListener('change', function () {
            const changeEvent = new Event('change', { bubbles: true });
            form.dispatchEvent(changeEvent);
        });
    }

    // Handle number inputs for filters
    const numberInputs = form.querySelectorAll('input[type="number"]');
    numberInputs.forEach(input => {
        input.addEventListener('change', function () {
            const changeEvent = new Event('change', { bubbles: true });
            form.dispatchEvent(changeEvent);
        });

        // Sync number inputs to range sliders
        if (input.name === 'price_min' && priceRangeInput) {
            input.addEventListener('input', function () {
                if (this.value) {
                    priceRangeInput.value = this.value;
                    const valueSpan = document.getElementById('price-range-value');
                    if (valueSpan) {
                        valueSpan.textContent = formatNumber(this.value);
                    }
                }
            });
        }

        if (input.name === 'construction_min' && constructionRangeInput) {
            input.addEventListener('input', function () {
                if (this.value) {
                    constructionRangeInput.value = this.value;
                    const valueSpan = document.getElementById('construction-range-value');
                    if (valueSpan) {
                        valueSpan.textContent = formatNumber(this.value);
                    }
                }
            });
        }

        if (input.name === 'land_min' && landRangeInput) {
            input.addEventListener('input', function () {
                if (this.value) {
                    landRangeInput.value = this.value;
                    const valueSpan = document.getElementById('land-range-value');
                    if (valueSpan) {
                        valueSpan.textContent = formatNumber(this.value);
                    }
                }
            });
        }
    });

    // Handle text input (search) with debounce
    const searchInput = form.querySelector('input[type="text"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const changeEvent = new Event('change', { bubbles: true });
                form.dispatchEvent(changeEvent);
            }, 500); // 500ms debounce for search
        });
    }
});
