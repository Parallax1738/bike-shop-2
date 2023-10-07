<?php
    // IF THE USER HAS A TOKEN, REDIRECT TO HOME SCREEN
	use bikeshop\app\models\ModelBase;
	
	if (!empty($data) && $data instanceof ModelBase) {
        echo "
        <script>
        cookieStore.set('token', '" . $data->getJwtToken() . "');
        
        // TODO - Surely there is a way to set the URL without 'http://' there
        location.href = 'http://localhost/';
        </script>
        ";
	}
 
?>

<h1>Login</h1>
<form action="/auth/login" method="post">
	<label>
		<input type="text" placeholder="Email Address" name="email" />
	</label>
	<label>
		<input type="password" placeholder="Password" name="password" />
	</label>
	<input type="submit" value="Login Button" />
</form>

<!-- Add line break because Tailwind CSS is annoying -->
<br><hr><br>

<h1>Create Account</h1>
<form action="/auth/create-account" method="post">
    <input type="text" placeholder="Email Address" name="email" />
    <input type="password" placeholder="Password" name="password" />
	<input type="submit" value="Create Account Button" />
</form>