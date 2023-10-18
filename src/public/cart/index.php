<?php
	use bikeshop\app\database\models\DbProduct;
	use bikeshop\app\models\CartModel;
	use bikeshop\app\models\CartProductModel;
	use Money\Currency;
	use Money\Money;
	
	if (!isset($data) || !($data instanceof CartModel))
	{
		echo 'No model was provided';
		die;
	}
	
	if (count($data->getProducts()) == 0)
	{
		echo '<p>No Products in your cart.</p>';
	}
	else {
        $total = 0;
        echo '
            <h1>Cart</h1>
            <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>';
        foreach ($data->getProducts() as $p)
        {
            if ($p instanceof DbProduct)
            {
                echo '<tr>';
                echo '<td>' . $p->getName() . '</td>';
                echo '<td>$' . $p->getPrice()->getAmount() . '</td>';
                echo '<td><input class="quantity" min=0 data-id="'.$p->getId().'" type="number" value="-1" step="1"/></td>';
                echo '<td><a href="/products/details?id=' . $p->getId() . '">View Details</a></td>';
                echo '<tr>';
            }
        }
        echo '
            </tbody>
            </table>
            <br><hr><br><p>Total: $' . $total . '</p>
            <a style="color: blue; text-decoration: underline">Purchase</a>';
    }
	?>
<script>
	let inputs = document.querySelectorAll('.quantity')
    for (let i = 0; i < inputs.length; i++)
    {
        inputs[i].addEventListener("input", changeProductQuantity)
    }
    initQuantities();
    
    function initQuantities() {
        getCart().then((cart) => {
            let inputs = document.querySelectorAll('.quantity');
            for (let i = 0; i < inputs.length; i++) {
                let foundProductId = findProduct(cart, inputs[i].dataset.id);
                if (foundProductId === -1) continue;
                // changeQuantity(inputs[i].dataset.id, cart[i]['q']);
                inputs[i].value = cart[i]['q'];
            }
        });
    }
    
	function changeProductQuantity(event) {
        let quantity = event.target.value;
        let productId = event.target.dataset.id;
        changeQuantity(Number(productId), Number(quantity)).then();
	}
    
    async function getCart() {
        // Get current cart cookie and store it from base 64. Don't worry if not exists
        let cartCookie = (await cookieStore.get('cart'))
        if (cartCookie) {
            // Convert from json into js object. Create new object if cookie doesn't exist
            let cartCookieValue = atob(cartCookie['value'])
            return JSON.parse(cartCookieValue)
        }
        return [];
    }
    
    function findProduct(cart, productId) {
        for (let i = 0; i < cart.length; i++) {
            if (cart[i]['p-id'] === Number(productId)) {
                return i;
            }
        }
        return -1;
    }
    
    async function changeQuantity(productId, quantity) {
        let cart = await getCart();
        
        let reloadPage = false;
        
        // Add item to products list
        for (let i = 0; i < cart.length; i++)
        {
            if (cart[i]['p-id'] === productId) {
                if (quantity === 0) {
                    // Delete the thing from the cookies and reload the page because I'm lazy
                    cart.splice(i, 1);
                    window.location.href = "/cart"
                    reloadPage = true;
                } else {
                    cart[i]['q'] = quantity;
                    console.log(cart[i]['q']);
                    break;
                }
            }
        }

        // Convert to json, then to base 64
        let cartJson = JSON.stringify(cart)
        let cartCookie = btoa(cartJson)
        
        console.log(cartJson);

        // Set new cookie
        await cookieStore.set('cart', cartCookie)
        
        if (reloadPage) window.location.href = '/cart';
    }
</script>
