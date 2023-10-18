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
        inputs[i].addEventListener("input", (event) => {
            let quantity = event.target.value;
            let productId = event.target.dataset.id;
            changeQuantityInCart(Number(productId), Number(quantity)).then();
        })
    }
    initQuantities();
    
    function initQuantities() {
        getCart().then((cart) => {
            let inputs = document.querySelectorAll('.quantity');
            for (let i = 0; i < inputs.length; i++) {
                let foundProductId = findProductInCart(cart, inputs[i].dataset.id);
                if (foundProductId === -1) continue;
                // changeQuantity(inputs[i].dataset.id, cart[i]['q']);
                inputs[i].value = cart[i]['q'];
            }
        });
    }
</script>
