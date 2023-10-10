<?php
	use bikeshop\app\models\PagingModel;
	
	if (!(isset($data)) || !($data instanceof PagingModel))
{
	// throw error
	echo '<p>Well, shit. The $data variable inside view/bike/index.php is not working</p>';
	die;
}
	
	$data->displayItemsHTML();
?>
