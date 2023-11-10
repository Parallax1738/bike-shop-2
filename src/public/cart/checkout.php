<h1><b>Checkout</b></h1>

<!-- Review Cart -->
<br>
<div>
    <h2><b>Review Cart</b></h2>
    <?php
		use bikeshop\app\database\entity\ProductEntity;
		use bikeshop\app\models\CartModel;
		use bikeshop\app\models\ModelBase;
	
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
            <a href="/cart" style="color: blue; text-decoration: underline;">Modify Cart</a>
            <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price Per Unit</th>
                    <th>Sub Total</th>
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
				echo '<td><p class="sub-total" data-id="'.$p->getId().'">$0.00</p></td>';
				echo '<td><p class="quantity" data-id="'.$p->getId().'" data-price="'.$p->getPrice().'">$0</p></td>';
				echo '<td><a href="/products/details?product=' . $p->getId() . '" style="text-decoration: underline; color: blue;">View Details</a></td>';
				echo '<tr>';
			}
		}
		echo '
            </tbody>
            </table>';
        }
	?>
</div>

<!-- Contact Details -->
<br><hr><br>
<div>
    <h2><b>Your Details</b></h2>
    <?php
        if ($data->getState()->getUser()) {
            // User exists, tell them that their details will be used for the order. READ THE TEXT
		    echo '<p>Your account details will be used for the order. <a href="/auth/edit" style="color: blue; text-decoration: underline;">Check your account</a> before ordering</p>';
        } else {
            // Otherwise, tell them to login
		    echo '<p>You must <a style="color: blue; text-decoration: underline;" href="/auth/login">login</a> or <a href="/auth/create" style="color: blue; text-decoration: underline;">create an account</a> to order from this site. These are orders from Google and the company will be shut down if we cannot provide enough data about you to them</p>';
        }
    ?>
</div>

<!-- Pay With --->
<br><hr><br>
<div>
    <h2><b>Pay With</b></h2>
    <p>Be aware that this functionality has not been implemented and is here as boilerplate, and you will not be charged (or given an option to enter any details)</p>
    <br>
    <ul>
        <li><input type="radio" name="payment-type" value="cc" /><label>Credit Cart</label></li>
        <li><input type="radio" name="payment-type" value="paypal" /><label>PayPal</label></li>
        <li><input type="radio" name="payment-type" value="after-pay" /><label>After Pay</label></li>
        <li><input type="radio" name="payment-type" value="google-pay" /><label>Google Pay</label></li>
    </ul>
</div>

<!-- Confirm and Pay -->
<br><hr><br>
<h2><b>Order</b></h2>
<?php
	$postage = 10;
    if (!isset($total)) $total = 0;
    
	$orderTotal = $total + $postage;
	
	echo '<p id="total">Subtotal: $' . $total . '</p>';
	echo '<p>Postage: $' . $postage . '</p>';
	echo '<br><hr><br>';
	echo '<p><b id="total-plus-shipping">Order Total: $' . $total . '</b></p>';
?>
<input type="submit" value="Confirm and Pay" style="color: blue; text-decoration: underline" />

<script>
    const QUANTITY_INPUTS = document.querySelectorAll('.quantity')
    const SUB_TOTAL_INPUTS = document.querySelectorAll('.sub-total');
    const TOTAL_TEXT = document.querySelector('#total');
    const TOTAL_PLUS_SHIPPING_TEXT = document.querySelector('#total-plus-shipping');

    initQuantities();

    function initQuantities() {
        getCart().then((cart) => {
            let total = 0;

            for (let i = 0; i < QUANTITY_INPUTS.length; i++) {
                let foundProductId = findProductInCart(cart, QUANTITY_INPUTS[i].dataset.id);
                if (foundProductId === -1) continue;

                let price = QUANTITY_INPUTS[i].dataset.price ?? 0;
                let quantity = cart[i]['q'];
                let subtotalPrice = price * quantity;

                // Set Quantity
                QUANTITY_INPUTS[i].innerHTML = quantity;
                SUB_TOTAL_INPUTS[i].innerHTML = "$" + subtotalPrice.toFixed(2);

                total += price * quantity;
            }
            TOTAL_TEXT.innerHTML = "Subtotal: $" + total.toFixed(2);
            TOTAL_PLUS_SHIPPING_TEXT.innerHTML = "Order Total: $" + (total + 10).toFixed(2);
        });
    }
</script>