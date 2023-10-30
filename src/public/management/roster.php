<h1>Roster</h1>

<?php
	use bikeshop\app\core\ArrayWrapper;
	
	if (!isset($data) || !is_array($data))
        die ("Data is not set");
    
    $dataWrapper = new ArrayWrapper($data);
    
    if (!array_key_exists('users', $data) || !array_key_exists('shifts', $data))
        die ("No user or shifts available");
?>