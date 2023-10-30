<h1>Create Account</h1>
<form action="/auth/create" method="post">
	<?php
		use bikeshop\app\database\entity\UserEntity;
		use bikeshop\app\models\CreateAccountModel;
		use bikeshop\app\models\ModelBase;
		
		if (isset($data) && $data instanceof CreateAccountModel)
		{
            $user = $data->getState()->getUser();
            if ($user && ($user->getUserRoleId() == 4 || $user->getUserRoleId() == 3))
			{
                // Print all user roles so that the admin or manager can create user accounts with specific account type
                echo "
                <div id='user-role-buttons'>";
                foreach ($data->getUserRoles() as $key => $value)
				{
					// Don't allow anyone to create another sysadmin, unless they are one themselves
                    if ($user->getUserRoleId() != 4 && $value == 4) continue;
                    
                    echo "<div><input type='radio' name='user-role' value='" . $key ."' /><label>" . $value . "</label></div>";
                }
                echo "</div>";
            }
        }
	?>
	<input type="text" placeholder="Email Address" name="email" />
	<input type="password" placeholder="Password" name="password" />
	<input type="submit" value="Create Account Button" />
</form>