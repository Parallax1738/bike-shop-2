<?php
    // IF THE USER HAS A TOKEN, REDIRECT TO HOME SCREEN
    // TODO - Move this into another page as it breaks the MVC thing
	use bikeshop\app\core\Authentication\JwtToken;
	
	if (!empty($data) && $data instanceof JwtToken)
    {
        echo "
        <p>"  . $data->getPayload()->toJson() . "</p>
        <script>
        cookieStore.set('token', '" . $data->encode() . "');
//        location.href = '/';
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