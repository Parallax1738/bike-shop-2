<h1>Create Account</h1>
<form action="/auth/create-account" method="post">
	<?php
		use bikeshop\app\database\models\DbUserModel;
		
		if (isset($loggedInUser) && $loggedInUser instanceof DbUserModel)
		{
			echo '<p>Test</p>';
		}
	?>
	<input type="text" placeholder="Email Address" name="email" />
	<input type="password" placeholder="Password" name="password" />
	<input type="submit" value="Create Account Button" />
</form>