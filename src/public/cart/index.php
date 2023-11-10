<?php
	use bikeshop\app\database\entity\ProductEntity;
	use bikeshop\app\models\CartModel;
	use bikeshop\app\models\CartProductEntity;
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
            // Info - Uses javascript to load the quantities
            if ($p instanceof ProductEntity)
            {
                echo '<tr>';
                echo '<td>' . $p->getName() . '</td>';
                echo '<td>$' . $p->getPrice() . '</td>';
                echo '<td><input class="quantity" min=0 data-id="'.$p->getId().'" data-price="'.$p->getPrice().'" type="number" value="-1" step="1"/></td>';
                echo '<td><a href="/products/details?product=' . $p->getId() . '" style="text-decoration: underline; color: blue;">View Details</a></td>';
                echo '<tr>';
            }
        }
        echo '
            </tbody>
            </table>
            <br><hr><br><p id="total">Total: $' . $total . '</p>
            <a style="color: blue; text-decoration: underline">Purchase</a>';
    }
	?>
<script>
	let inputs = document.querySelectorAll('.quantity')
    let totalText = document.querySelector('.total');
    for (let i = 0; i < inputs.length; i++)
    {
        inputs[i].addEventListener("input", (event) => {
            let quantity = event.target.value;
            let productId = event.target.dataset.id;
            changeQuantityInCart(Number(productId), Number(quantity)).then(() => {
                // Once the quantity changes in the cookie, reload teh prices
                initQuantities();
            });
        })
    }
    initQuantities();
    
    function initQuantities() {
        getCart().then((cart) => {
            let total = 0;
            let inputs = document.querySelectorAll('.quantity');
            let totalText = document.querySelector('#total');
            for (let i = 0; i < inputs.length; i++) {
                let foundProductId = findProductInCart(cart, inputs[i].dataset.id);
                if (foundProductId === -1) continue;
                let price = inputs[i].dataset.price ?? 0;
                
                // Set Quantity
                let q = cart[i]['q'];
                inputs[i].value = q;
                
                total += price * q;
            }
            totalText.innerHTML = "Total: $" + total.toFixed(2);
        });
    }
</script>
