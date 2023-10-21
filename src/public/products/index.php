<?php
	use bikeshop\app\core\ArrayWrapper;
	use bikeshop\app\database\models\DbProduct;
	use bikeshop\app\database\models\DbProductFilter;
	use bikeshop\app\models\ProductsViewModel;
	
	if (!isset($data) || !($data instanceof ProductsViewModel))
	{
		echo '<p>Well the data parameter isn\'t set for some reason. Good luck!</p>';
		die;
	}

	echo "<h1><b>" . $data->getProductDisplayName() . "</b></h1><br>";
	
    echo "<ul>";
    foreach ($data->getProductsFilterList() as $filter)
	{
        if ($filter instanceof DbProductFilter)
		{
            $get = new ArrayWrapper($_GET);
            $id = "fil-" . $filter->getId();
            
            if ($val = $get->getValueWithKey($id))
                if ($val == 1)
                    echo '<li><input class="' . $id . '" checked type="checkbox">'.$filter->getName().'</input><l/>';
                else
					echo '<li><input class="' . $id . '" type="checkbox">'.$filter->getName().'</input><l/>';
            
            else
				echo '<li><input id='. $id . ' name="' . $id . '" checked type="checkbox">'.$filter->getName().'</input><l/>';
        }
    }
    echo "</ul><br>";
    
	foreach ($data->getList() as $item)
	{
		if ($item instanceof DbProduct)
		{
			echo '<p>' . $item->getName() . '</p> <button onclick="addToCart(' . $item->getId() . ')">Add To Cart</button><br>';
		}
	}
	
	// Previous Button
	if ($data->getCurrentPage() > 0)
	{
		// Display Left Arrow because if it is greater than 0, user can go back a page
		$newPage = $data->getCurrentPage() - 1;
		
		echo '<form method="get" action="/' . $data->getProductHtmlName() . '">
			<input type="hidden" name="page" value="' . $newPage . '" />
			<input type="hidden" name="results" value="' . $data->getMaxResults() . '" />
			<input type="submit" value="_<_" style="background-color: darkgrey" />
			</form>';
	}
	
	echo "<div style='background-color: orange'>" . $data->getCurrentPage() + 1 . " / " . $data->getMaxPage();
	
	if ($data->getCurrentPage() < $data->getMaxPage() - 1)
	{
		$newPage = $data->getCurrentPage() + 1;
		
		echo '<form method="get" action="/' . $data->getProductHtmlName() . '">
			<input type="hidden" name="page" value="' . $newPage . '" />
			<input type="hidden" name="results" value="' . $data->getMaxResults() . '" />
			<input type="submit" value="_>_" style="background-color: darkgrey" />
			</form>';
	}
	echo __DIR__ . "/../ui-components/javascript/cart-management.js";
?>
<script>
    function onCheckChecked(event) {
        const val = event.target.checked;
        if (val) {
        
        }
    }
</script>
