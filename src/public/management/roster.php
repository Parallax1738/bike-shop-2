<?php
	use bikeshop\app\core\ArrayWrapper;
	use bikeshop\app\database\entity\StaffShiftEntity;
	use bikeshop\app\database\entity\UserEntity;
	use bikeshop\app\models\RosterModel;
	
	/**
	 * Displays an entire record containing all of their shifts. Should be appended inside an existing table record.
	 * @param array $shiftMap A map of shift IDs to shifts. Should only contain shifts for a specific user
	 * @param array $dates A list of date strings (formatted to Y-m-d) to display
	 * @param UserEntity $user User who's shifts should be displayed
	 * @return void
	 */
	function displayShiftsRow(array $shiftMap, array $dates, UserEntity $user)
	{
		echo '<tr>';
		
		// Display User Information table
		echo '<td>'.$user->getEmailAddress().'</td>';
		echo '<td><a href="/auth/edit?a='.$user->getId().'" style="color: blue; text-decoration: underline">Details</a></td>';
		
		// Initialise a map of date => shift so we don't have to have nested foreach loops.
		// Maps Date (Y-m-d) to Shift
		$dateShiftMap = [];
		foreach ($shiftMap as $shiftId => $shift)
		{
			if (!$shift instanceof StaffShiftEntity)
				continue;
			
			$dateShiftMap[$shift->getStartTime()->format('Y-m-d')] = $shift;
		}
		
		// Loop through every day in the time span. If a shift exists for the user in the same day, fill in input field.
		// Otherwise, empty input field
		$shifts = new ArrayWrapper($shiftMap);
		
		// Remember, dates is auto-formatted as Y-m-d
		foreach ($dates as $d)
		{
			if (array_key_exists($d, $dateShiftMap))
				$shift = $dateShiftMap[$d];
			else
				$shift = null;
			
			if (!$shift instanceof StaffShiftEntity)
			{
				// No shift found.
				// TODO - Only allow managers and sysadmins to change times
				echo '<td>
						<input type="hidden" name="user-id" value="'.$user->getId().'"
						<input type="hidden" name="date" />
						<p>Start: <input type="time" name="start-time" /></p>
						<p>End: <input type="time" name="end-time" /></p>
						<p><a style="color: blue; text-decoration: underline">Update</a></p>
					  </td>';
			}
			else
			{
				// Shift found. Include hidden input for shift id
				$formattedStartTime = $shift->getStartTime()->format('H:m');
				$formattedEndTime = $shift->getEndTime()->format('H:m');
				echo '<td>
						<input type="hidden" name="user-id" value="'.$user->getId().'"
						<input type="hidden" name="date" value="'.$d.'"/>
						<p>Start: <input type="time" name="start-time" value="'.$formattedStartTime.'"/></p>
						<p>Start: <input type="time" name="end-time" value="'.$formattedEndTime.'"/></p>
						<p><a style="color: blue; text-decoration: underline">Update</a></p>
					  </td>';
			}
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
  
		$dates[] = $dateIncr->format('Y-m-d');
        $dateIncr = $dateIncr->add($plusOneDay);
	}
	
	echo "</tr></thead><tbody>";
	
	foreach ($data->getData()['users'] as $user)
	{
		if (!$user instanceof UserEntity)
			continue;
		
		// Check if user exists for shifts
		$shifts = $data->getData()['shifts'];
		if (!is_array($shifts) || !array_key_exists($user->getId(), $shifts))
			displayShiftsRow([], $dates, $user); // This displays all empty rows instead of user shifts
		else
			displayShiftsRow($shifts[$user->getId()], $dates, $user); // This displays all empty rows instead of user shifts
	}
	
	echo "</tbody></table>";
?>