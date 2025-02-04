document.addEventListener('DOMContentLoaded', () => {
    const addTocartButtons = document.querySelectorAll(".add-to-cart");
    const cartItemCount = document.querySelector(".cart--icon span"); 
    const cartItemList = document.querySelector(".cart-items"); 
    const cartTotal = document.querySelector(".cart_total");          
   
         
    const sidebar = document.getElementById("sidebar");

    let cartItems = [];
    let totalamount = 0;

    //sidebar opening and closing

    const cartIcon = document.querySelector(".cart--icon");  
    cartIcon.addEventListener('click', () => {
        sidebar.classList.toggle('open');
    });

    const closeButton = document.querySelector('.sidebar-close');
    closeButton.addEventListener('click', () => {
        sidebar.classList.remove('open');
    });


    //add to cart button 

    addTocartButtons.forEach((button, index) => {
        button.addEventListener('click', () => {
            const item = {

                //retrieve item name and price
                name: document.querySelectorAll('.menu_items .item_title')[index].textContent,
                price: parseFloat(document.querySelectorAll('div.price')[index].textContent.slice(3),
            ),
            quantity: 1,
            };

            //check if items already exist in cart
            const existingItems = cartItems.find(
                (cartItem) => cartItem.name === item.name
            );

            if (existingItems) {
                existingItems.quantity++;
            } else {
                cartItems.push(item);
            }

            totalamount += item.price;
            updateCartUI();

        });
    });

    function updateCartUI() {
        updateCartItemCount(cartItems.length);
        updateCartList();
        updateCartTotal();
    }

    function updateCartItemCount(count) {
        cartItemCount.textContent = count;
    }

    function updateCartList() {

        cartItemList.innerHTML = '';
        cartItems.forEach((item, index) => {

            const cartItem = document.createElement('div');
            cartItem.classList.add('cart--items','individual_cart_items');
            cartItem.innerHTML = `
                <span> (${item.quantity}x) ${item.name} </span>
                <span class="cart-item-price"> Rs${(item.quantity * item.price).toFixed(2)}
                <button class="remove_button" data-index="${index}">
                    <i class="fa-solid fa-times"></i>
                </button>
                </span>
            `;
            cartItemList.append(cartItem);
        });

        const removeButtons = document.querySelectorAll('.remove_button');
        removeButtons.forEach((button) => {
            button.addEventListener('click', (event) => {
                const index = event.currentTarget.dataset.index;
                removeItemFromCart(index);
            });
        });
    }

    function removeItemFromCart(index) {
        const removeItem = cartItems.splice(index, 1)[0];
        totalamount -= removeItem.price * removeItem.quantity;
        updateCartUI();
    }

    function updateCartTotal() {
        cartTotal.textContent = `Rs ${totalamount.toFixed(2)}`;
    }

});
