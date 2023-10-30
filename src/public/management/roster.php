<?php
	use bikeshop\app\core\ArrayWrapper;
	use bikeshop\app\models\RosterModel;
	
	if (!isset($data) || !$data instanceof RosterModel)
        die ("Data not set");
    
    // Loop until dateIncr is equal to $data->getEnd(). During the loop, increment dateIncr by one day, hence the while
    // loop
    echo "
    <table><thead>
    <tr>
        <th>Staff</th>
        <th>Details</th>
    ";
    $plusOneDay = DateInterval::createFromDateString('1 Day');
    $dateIncr = $data->getStart();
    
    echo $dateIncr->format('Y-m-d');
    echo '<br>';
    echo $data->getEnd()->format('Y-m-d');
    
    while (strcmp($dateIncr->format('Y-m-d'), $data->getEnd()->format('Y-m-d')) != 0)
	{
        echo '<th>'.$dateIncr->format('D, d M').'</th>';
        
        $dateIncr = $dateIncr->add($plusOneDay);
	}
?>