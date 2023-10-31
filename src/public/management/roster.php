<?php
	use bikeshop\app\core\ApplicationState;
	use bikeshop\app\core\ArrayWrapper;
	use bikeshop\app\database\entity\StaffShiftEntity;
	use bikeshop\app\database\entity\UserEntity;
	use bikeshop\app\models\RosterModel;
	
	/**
	 * Displays an entire record containing all of their shifts. Should be appended inside an existing table record.
	 * @param array $shiftMap A map of shift IDs to shifts. Should only contain shifts for a specific user
	 * @param array $dates A list of date strings (formatted to Y-m-d) to display
	 * @param UserEntity $user User who's shifts should be displayed
	 * @param bool $enableEditing If there should be an ability to edit the roster
	 * @return void
	 */
	function displayShiftsRow(array $shiftMap, array $dates, UserEntity $user, bool $enableEditing)
	{
		echo '<tr>';
		
		// Display User Information table
		echo '<td>'.$user->getEmailAddress().'</td>';
		echo '<td><a href="/auth/edit?a='.$user->getId().'" style="color: blue; text-decoration: underline">Details</a></td>';
		
		// Initialise a map of date => shift, so we don't have to have nested foreach loops.
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
			
			
			if ($enableEditing == 1)
				echo "<td>".displayEditableShiftRecordHtml($shift)."</td>";
			else
				echo "<td>".displayUnEditableShiftRecordHtml($shift)."</td>";
		}
		
		echo '</tr>';
	}
	
	function displayUnEditableShiftRecordHtml(StaffShiftEntity | null $shift) : string
	{
		if (!$shift)
			return "";
		
		$startTimeValue = $shift->getStartTime()->format('H:m');
		$endTimeValue = $shift->getEndTime()->format('H:m');
		$dateValue = $shift->getStartTime()->format('Y-m-d');
		$shiftIdValue = $shift->getShiftId();
		
		$html  = "<p>Start: ".$startTimeValue."</p>";
		$html .= "<p>End: ".$endTimeValue."</p>";
		
		return $html;
	}
	
	function displayEditableShiftRecordHtml(StaffShiftEntity | null $shift) : string
	{
		$html = "<form action='management/roster' method='post'>";
		
		if ($shift instanceof StaffShiftEntity)
		{
			$startTimeValue = $shift->getStartTime()->format('H:m');
			$endTimeValue = $shift->getEndTime()->format('H:m');
			$dateValue = $shift->getStartTime()->format('Y-m-d');
			$shiftIdValue = $shift->getShiftId();
		}
		else
		{
			$startTimeValue = "";
			$endTimeValue = "";
			$dateValue = "";
		}
		
		if (isset($shiftIdValue))
			$html .= "<input type='hidden' name='user-id' value='".$shift->getUser()->getId()."' />";
			
		$html .= "<input type='hidden' name='date' value='".$dateValue."' />";
		$html .= "<p>Start: <input type='time' name='start-time' value='".$startTimeValue."'/></p>";
		$html .= "<p>End: <input type='time' name='end-time' value='".$endTimeValue."'/></p>";
		$html .= "<p><a style='color: blue; text-decoration: underline'>Update</a></p>";
		
		$html .= "<form/>";
		
		return $html;
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
	
	$enableEditing =
		($data->getState()->getUser()->getUserRoleId() == 3) ||
		($data->getState()->getUser()->getUserRoleId() == 4);
	
	foreach ($data->getData()['users'] as $user)
	{
		if (!$user instanceof UserEntity)
			continue;
		
		// Check if user exists for shifts
		$shifts = $data->getData()['shifts'];
		if (!is_array($shifts) || !array_key_exists($user->getId(), $shifts))
			displayShiftsRow([], $dates, $user, $enableEditing); // This displays all empty rows instead of user shifts
		else
			displayShiftsRow($shifts[$user->getId()], $dates, $user, $enableEditing); // This displays all empty rows instead of user shifts
	}
	
	echo "</tbody></table>";
?>