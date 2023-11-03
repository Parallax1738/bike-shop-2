<div class="flex pt-10 justify-center">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold mb-4">Create Account</h1>
        <form action="/auth/create" method="post" class="space-y-4">
            <?php

			use bikeshop\app\database\entity\UserEntity;
			use bikeshop\app\models\CreateAccountModel;
			use bikeshop\app\models\ModelBase;

			require '/var/www/html/src/public/ui-components/input.php';

			if (isset($data) && $data instanceof CreateAccountModel) {
				$user = $data->getState()->getUser();
				if ($user && ($user->getUserRoleId() == 4 || $user->getUserRoleId() == 3)) {
					// Print all user roles so that the admin or manager can create user accounts with specific account type
					echo "<div id='user-role-buttons' class='space-y-4'>";
					foreach ($data->getUserRoles() as $key => $value) {
						// Don't allow anyone to create another sysadmin, unless they are one themselves
						if ($user->getUserRoleId() != 4 && $value == 4) continue;
						echo "<div class='flex items-center'><input type='radio' name='user-role' value='" . $key . "' class='mr-2'/><label>" . $value . "</label></div>";
					}
					echo "</div>";
				}
			}
			echo input('email', 'Email Address', null, 'email', true);
			echo input('password', 'Password', null, 'password', true);
			?>

            <div class="flex justify-center">
                <?php
				button(['text' => 'Create Account', 'type' => 'submit']);
				?>
            </div>
        </form>
    </div>
</div>