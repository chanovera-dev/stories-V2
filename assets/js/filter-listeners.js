/**
 * Filter Listeners
 * 
 * Handles range input and number input listeners for property filters.
 * Updates display values and triggers AJAX fetch when changed.
 */
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('property-filters');
    
    if (!form) return;

    // Handle range inputs
    const rangeInputs = form.querySelectorAll('input[type="range"]');
    rangeInputs.forEach(range => {
        // Update display value on input
        range.addEventListener('input', function () {
            const valueSpan = document.getElementById(this.id + '-value');
            if (valueSpan) {
                valueSpan.textContent = this.value;
            }
        });

        // Trigger AJAX on change (when user releases mouse)
        range.addEventListener('change', function () {
            const changeEvent = new Event('change', { bubbles: true });
            form.dispatchEvent(changeEvent);
        });
    });

    // Handle number inputs for filters
    const numberInputs = form.querySelectorAll('input[type="number"]');
    numberInputs.forEach(input => {
        input.addEventListener('change', function () {
            const changeEvent = new Event('change', { bubbles: true });
            form.dispatchEvent(changeEvent);
        });
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
