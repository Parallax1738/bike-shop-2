<?php
	use bikeshop\app\database\models\DbProduct;
	use bikeshop\app\models\CartModel;
	use Money\Currency;
	use Money\Money;
	
	if (!isset($data) || !($data instanceof CartModel))
	{
		echo 'No model was provided';
		die;
	}
	
	$total = 0;
	echo '<h1>Cart</h1>';
	foreach ($data->getProducts() as $p)
	{
		if ($p instanceof DbProduct)
		{
			echo '<p>' . $p . '</p>';
			$total += $p->getPrice()->getAmount();
		}
	}
	echo '
		<br><hr><br><p>Total: $' . $total . '</p>
		<a style="color: blue; text-decoration: underline">Purchase</a>';