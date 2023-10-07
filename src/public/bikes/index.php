<?php
	use bikeshop\app\models\BikeDisplayModel;
	
	if (!(isset($data)) || !($data instanceof BikeDisplayModel))
{
	// throw error
	echo '<p>Well, shit. The $data variable inside view/bike/index.php is not working</p>';
	die;
}

foreach ($data->getBikes() as $bike)
{
	echo '<p>' . $bike . '</p>';
}

if ($data->getPageIndex() > 0)
{
	// Display Left Arrow because if it is greater than 0, user can go back a page
	$newPage = $data->getPageIndex() - 1;
	$results = $data->getResultCount();
	echo '<form method="post" action="http://localhost/bikes?page=' . $newPage . '&results=' . $results . '">
		<input type="submit" value="_<_" style="background-color: darkgrey" />
	</form>';
}

echo $data->getPageIndex();

// TODO - Give data a maximum amount of pages
if ($data->getPageIndex() < $data->getTotalPageNum())
{
	// Oposite to if statement above
	$newPage = $data->getPageIndex() + 1;
	echo '<form method="post" action="http://localhost/bikes?page=' . $newPage . '&results=10">
		<input type="submit" value="_>_" style="background-color: darkgrey"/>
	</form>';
}
?>
