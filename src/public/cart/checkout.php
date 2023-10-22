<h1><b>Checkout</b></h1>

<!-- Review Cart -->
<br>
<div>
	<h2><b>Review Cart</b></h2>
	<?php
		use bikeshop\app\database\models\DbProduct;
		use bikeshop\app\models\CartModel;
		use bikeshop\app\models\ModelBase;
		
		if (isset($data) && $data instanceof CartModel)
		{
			if (count($data->getProducts()) == 0)
			{
				echo '<p>No Products in your cart.</p>';
				die;
			}
			else {
				$total = 0;
				echo '
					<table>
					<thead>
						<tr>
							<th>Name</th>
							<th>Price</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>';
				foreach ($data->getProducts() as $p)
				{
					if ($p instanceof DbProduct)
					{
						echo '<tr style="text-align: right">';
						echo '<td>' . $p->getName() . ': </td>';
						echo '<td>$' . $p->getPrice() . '</td>';
						echo '<td><a href="/products/details?id=' . $p->getId() . '" style="color: blue; text-decoration: underline">View Details</a></td>';
						echo '<tr>';
					}
				}
				echo '
					</tbody>
					</table>';
			}
		}
	?>
</div>

<!-- Contact Details -->
<br>
<div>
<h2><b>Your Details</b></h2>
<?php
	if (isset($data) && $data instanceof ModelBase) {
		echo '<p>Your account details will be used for the order. <a href="/auth/edit-account" style="color: blue; text-decoration: underline;">Check your account</a> before ordering</p>';
	} else {
		echo '<p>You must <a style="color: blue; text-decoration: underline;" href="/auth/login">login</a> or <a href="/auth/create-account" style="color: blue; text-decoration: underline;">create an account</a> to order from this site. These are orders from Google and the company will be shut down if we cannot provide enough data about you to them</p>';
	}
?>
</div>

<!-- Pay With --->
<br>
<div>
	<h2><b>Pay With</b></h2>
	<ul>
		<li><input type="radio" name="payment-type" value="cc"/><label>Credit Cart</label></li>
		<li><input type="radio" name="payment-type" value="paypal"/><label>PayPal</label></li>
		<li><input type="radio" name="payment-type" value="after-pay"/><label>After Pay</label></li>
		<li><input type="radio" name="payment-type" value="google-pay"/><label>Google Pay</label></li>
	</ul>
</div>

<!-- Confirm and Pay -->
<br>
<h2><b>Order</b></h2>
<?php
	$postage = 10;
	$orderTotal = $total + $postage;
	
	echo '<p>Subtotal (' . count($data->getProducts()) . '): $' . $total . '</p>';
	echo '<p>Postage: $' . $postage . '</p>';
	echo '<br><hr><br>';
	echo '<p><b>Order Total: $' . $total . '</b></p>';
?>
<input type="submit" value="Confirm and Pay" style="color: blue; text-decoration: underline"/>
