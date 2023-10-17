<?php
	use bikeshop\app\database\models\DbProduct;
	use bikeshop\app\models\PagingModel;
	
	if (!isset($data) || !($data instanceof PagingModel))
	{
		echo '<p>Well the data parameter isn\'t set for some reason. Good luck!</p>';
	}
	
	foreach ($data->getList() as $item)
	{
		if ($item instanceof DbProduct)
		{
			echo '<p>' . $item->getName() . '</p>';
		}
	}
	
	// Previous Button
	if ($data->getCurrentPage() > 0)
	{
		// Display Left Arrow because if it is greater than 0, user can go back a page
		$newPage = $this->currentPage - 1;
		
		echo '<form method="get" action="/bikes">
			<input type="hidden" name="page" value="' . $newPage . '" />
			<input type="hidden" name="results" value="' . $data->getMaxResults() . '" />
			<input type="submit" value="_>_" style="background-color: darkgrey" />
			</form>';
	}
	
	echo "<div style='background-color: orange'>" . $data->getCurrentPage() + 1 . " / " . $data->getMaxPage();
	
	if ($data->getCurrentPage() < $data->getMaxPage() - 1)
	{
		$newPage = $data->getCurrentPage() + 1;
		
		echo '<form method="get" action="/bikes">
			<input type="hidden" name="page" value="' . $newPage . '" />
			<input type="hidden" name="results" value="' . $data->getMaxResults() . '" />
			<input type="submit" value="_>_" style="background-color: darkgrey" />
			</form>';
	}
	
?>
