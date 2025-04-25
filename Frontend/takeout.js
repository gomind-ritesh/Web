document.addEventListener('DOMContentLoaded', () => {
    const addToCartButtons = document.querySelectorAll(".add-to-cart"); // Buttons to add items to the cart
    const cartItemCount = document.querySelector(".cart--icon span"); // Cart item count
    const cartItemList = document.querySelector(".cart-items"); // Cart item list container
    const cartTotal = document.querySelector(".cart_total"); // Cart total amount
    const sidebar = document.getElementById("sidebar"); // Sidebar container

    let cartItems = [];
    let totalAmount = 0;

    // Sidebar toggling (open/close)
    const cartIcon = document.querySelector(".cart--icon");
    cartIcon.addEventListener('click', () => {
        sidebar.classList.toggle('open');
    });

    const closeButton = document.querySelector('.sidebar-close');
    closeButton.addEventListener('click', () => {
        sidebar.classList.remove('open');
    });

    // Add to cart functionality
    addToCartButtons.forEach((button, index) => {
        button.addEventListener('click', () => {
            const item = {
                // Retrieve item name and price from the PHP-generated structure
                name: document.querySelectorAll('.menu_items .item_title')[index].textContent,
                price: parseFloat(document.querySelectorAll('.menu_items .price')[index].textContent.replace('RS ', '')),
                quantity: 1,
            };

            // Check if item already exists in the cart
            const existingItem = cartItems.find(cartItem => cartItem.name === item.name);
            if (existingItem) {
                existingItem.quantity++;
            } else {
                cartItems.push(item);
            }

            totalAmount += item.price;
            updateCartUI();
        });
    });

    // Update cart UI (item count, list, and total)
    function updateCartUI() {
        updateCartItemCount(cartItems.length);
        updateCartList();
        updateCartTotal();
    }

    function updateCartItemCount(count) {
        cartItemCount.textContent = count; // Update cart item count
    }

    function updateCartList() {
        cartItemList.innerHTML = ''; // Clear existing items in the cart list
        cartItems.forEach((item, index) => {
            const cartItem = document.createElement('div');
            cartItem.classList.add('cart--items', 'individual_cart_items');
            cartItem.innerHTML = `
                <span>(${item.quantity}x) ${item.name}</span>
                <span class="cart-item-price">Rs ${(item.quantity * item.price).toFixed(2)}
                <button class="remove_button" data-index="${index}">
                    <i class="fa-solid fa-times"></i>
                </button>
                </span>
            `;
            cartItemList.append(cartItem);
        });

        // Enable remove functionality for each item in the cart
        const removeButtons = document.querySelectorAll('.remove_button');
        removeButtons.forEach(button => {
            button.addEventListener('click', event => {
                const index = event.currentTarget.dataset.index;
                removeItemFromCart(index);
            });
        });
    }

    function removeItemFromCart(index) {
        const removedItem = cartItems.splice(index, 1)[0]; // Remove item from the cart
        totalAmount -= removedItem.price * removedItem.quantity; // Deduct item's price from total
        updateCartUI();
    }

    function updateCartTotal() {
        cartTotal.textContent = `Rs ${totalAmount.toFixed(2)}`; // Update total amount
    }
});