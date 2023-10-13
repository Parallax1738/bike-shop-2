<h1>Create Account</h1>
<form action="/sys-admin/createStaffMember" method="post">
	<div id="account-type">
	<?php
		use bikeshop\app\models\CreateStaffMemberModel;
		// Generates radio buttons for user account type
		if (isset($data) && $data instanceof CreateStaffMemberModel)
		{
			foreach ($data->getAvailableUserRoleIds() as $userRoleId => $userRoleName)
			{
				echo '<div>';
				echo '<input type="radio" name="account-type" value="' . $userRoleId . '"</input>';
				echo '<label>' . $userRoleName . '</label>';
				echo '</div>';
			}
		}
	?>
	</div>
	<input type="text" placeholder="Email Address" name="email" />
	<input type="password" placeholder="Password" name="password" />
	<input type="submit" value="Create Account Button" />
</form>
