<h1><b>Staff Management</b></h1>
<p>Create, Modify, and Delete Staff Members</p>

<?php

use bikeshop\app\database\entity\UserEntity;
use bikeshop\app\models\StaffManagementModel;

// Ensure data exists
if (!isset($data) || !($data instanceof StaffManagementModel)) {
	echo '<p><b style="color: red;">Error; data provided is not valid</b></p>';
}

// List All Managers
echo '<br>';
echo '<h1><b>Managers</b></h1>';
if (count($data->getManagers()) == 0) {
	echo '<p>No Managers Found</p>';
} else {
	foreach ($data->getManagers() as $manager) {
		if (!$manager instanceof UserEntity) continue;
		echo '<p>' . $manager->getFirstName() . ' ' . $manager->getLastName() . ' <a style="color:blue; text-decoration: underline"
			                                                href="/auth/edit?a=' . $manager->getId() . '">
			                                                Edit
                                                         </a></p>';
	}
}

// List All Staff Members
echo '<br><h1><b>Staff Members</b></h1>';
if (count($data->getStaffMembers()) == 0) {
	echo '<p>No Staff Members Found</p>';
} else {
	foreach ($data->getStaffMembers() as $staff) {
		if (!$staff instanceof UserEntity) continue;
		echo '<p>' . $staff->getFirstName() . ' ' . $staff->getLastName() . ' <a style="color:blue; text-decoration: underline"
                    href="/auth/edit?a=' . $staff->getId() . '">
                    Edit
                  </a> <a style="color:blue; text-decoration: underline"
                    href="/auth/delete-account?a=' . $staff->getId() . '"></p>';
	}
}
?>

<br>
<p><a href="/auth/create" style="color: blue !important; text-decoration: underline;">Create Staff Account</a></p>
<p><a href="/management/roster" style="color: blue; text-decoration: underline;">View Roster</a></p>