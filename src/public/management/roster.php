<?php
	use bikeshop\app\core\ArrayWrapper;
	use bikeshop\app\database\entity\StaffShiftEntity;
	use bikeshop\app\database\entity\UserEntity;
	use bikeshop\app\models\RosterModel;
	
	function displayShiftsRow(array $data, array $dates, UserEntity $user)
	{
		echo '<tr>';
		
		// Display User Information table
		echo '<td>'.$user->getEmailAddress().'</td>';
		echo '<td><a href="/auth/edit?a='.$user->getId().'" style="color: blue; text-decoration: underline">Details</a></td>';
		
		// Display Shifts
		if (!array_key_exists('shifts', $data))
			return;
		
		// Loop through every day in the time span. If a shift exists for the user in the same day, fill in input field.
		// Otherwise, empty input field
		foreach ($dates as $d)
		{
			echo '<td>
					<input type="hidden" name="date" />
					<p>Start: <input type="time" name="start-time" /></p>
					<p>End: <input type="time" name="end-time" /></p>
					<p><a style="color: blue; text-decoration: underline">Update</a></p>
				  </td>';
		}
		
		echo '</tr>';
	}
	
	if (!isset($data) || !$data instanceof RosterModel)
        die ("Data not set");
    
    // Loop until dateIncr is equal to $data->getEnd(). During the loop, increment dateIncr by one day, hence the while
    // loop
    echo "
    <table class='min-w-full table-auto'><thead>
    <tr>
        <th>Staff</th>
        <th>Details</th>
    ";
	flush();
	
	$dates = []; // Save dates array so we don't have to redo this while loop
    $plusOneDay = DateInterval::createFromDateString('1 Day');
    $dateIncr = $data->getStart();
    
    while (strcmp($dateIncr->format('Y-m-d'), $data->getEnd()->format('Y-m-d')) != 0)
	{
        echo '<th>'.$dateIncr->format('D, d M').'</th>';
		flush();
  
		$dates[] = new DateTime($dateIncr->format('Y-m-d'));
        $dateIncr = $dateIncr->add($plusOneDay);
	}
	
	echo "</tr></thead><tbody>";
	
	foreach ($data->getData()['users'] as $user)
	{
		if (!$user instanceof UserEntity)
			continue;
		
		displayShiftsRow($data->getData(), $dates, $user);
	}
	
	echo "</tbody></table>";
?>