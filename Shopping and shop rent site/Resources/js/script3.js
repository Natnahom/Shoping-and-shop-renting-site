function filterItemsShop() {
    const selectedCategory = document.getElementById("categorySelect").value;
    const checkboxes = document.querySelectorAll('.panel_shop input[type="checkbox"]');
    const selectedPriceRanges = [];
    
    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            selectedPriceRanges.push(checkbox.value);
        }
    });

    const items = document.querySelectorAll('.contentSecDiv .item');
    let visibleCount = 0;

    items.forEach(item => {
        const price = parseFloat(item.getAttribute('data-price'));
        const category = item.getAttribute('data-category');
        let priceMatch = false;

        // If no price range is selected, show all items
        if (selectedPriceRanges.length === 0) {
            priceMatch = true;
        } else {
            for (const range of selectedPriceRanges) {
                if (range === "200000+") {
                    if (price >= 200000) {
                        priceMatch = true;
                        break;
                    }
                } else {
                    const [minStr, maxStr] = range.split('-');
                    const min = parseFloat(minStr);
                    const max = maxStr === '+' ? Infinity : parseFloat(maxStr);
                    
                    if (price >= min && price <= max) {
                        priceMatch = true;
                        break;
                    }
                }
            }
        }

        // Check if category matches
        const categoryMatch = selectedCategory === "" || category === selectedCategory;

        // Show or hide the item based on matches
        if (priceMatch && categoryMatch) {
            item.style.display = "block"; // Show the item
            visibleCount++; // Increment visible item count
        } else {
            item.style.display = "none"; // Hide the item
        }
    });

    // Update the item count
    document.getElementById('count').textContent = visibleCount;
}

function searchItems() {
    const query = document.getElementById('search').value.toLowerCase();
    const items = document.querySelectorAll('.conts');

    items.forEach(item => {
        // const uname = item.querySelector('.username').textContent.toLowerCase();
        const name = item.querySelector('.name').textContent.toLowerCase();
        const brand = item.querySelector('.brand').textContent.toLowerCase();
        const model = item.querySelector('.model').textContent.toLowerCase();
        // const productId = item.querySelector('.productId').textContent.toLowerCase();

        // Check if the query matches any part of the product details
        if (brand.includes(query) || model.includes(query) || name.includes(query)) {
            item.style.display = 'block'; // Show item
        } else {
            item.style.display = 'none'; // Hide item
        }
    });
}