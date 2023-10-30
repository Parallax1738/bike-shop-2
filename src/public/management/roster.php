<?php
	use bikeshop\app\core\ArrayWrapper;
	use bikeshop\app\database\entity\UserEntity;
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
	flush();
	
    $plusOneDay = DateInterval::createFromDateString('1 Day');
    $dateIncr = $data->getStart();
    
    while (strcmp($dateIncr->format('Y-m-d'), $data->getEnd()->format('Y-m-d')) != 0)
	{
        echo '<th>'.$dateIncr->format('D, d M').'</th>';
		flush();
        
        $dateIncr = $dateIncr->add($plusOneDay);
	}
	
	echo "</tr></thead><tbody>";
	
	foreach ($data->getData()['users'] as $user)
	{
		if (!$user instanceof UserEntity)
			continue;
		echo '<tr>';
		flush();
		
		echo '<td>'.$user->getEmailAddress().'</td>';
		echo '<td><a style="color: blue; text-decoration: underline">Details</a></td>';
		flush();
		
		echo '</tr>';
		flush();
		
	}
	
	echo "</tbody></table>";
?>