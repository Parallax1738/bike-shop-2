<?php
	use bikeshop\app\database\entity\ProductEntity;

if (!isset($data))
{
	die ("No Product Found");
}

if ($data instanceof ProductEntity)
{
	echo "
	
	<h1><b>" . $data->getName() . "</b></h1>
	<p>" . $data->getDescription() . "</p>
	<p>$" . $data->getPrice() . " <a style='color: blue; text-decoration: underline;' onclick='addToCart(".$data->getId().")'>Add To Cart</a></p>
	
	";
}
else
{
	// TODO - Tell Dardan to create UI design for this
	echo "Product details not found";
}