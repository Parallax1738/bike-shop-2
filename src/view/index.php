<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Bike shop</title>
    <link href="../../output.css" rel="stylesheet">
</head>

<body>
    <?php
		include_once './ui-components/navbar.php';
        require_once '../../vendor/autoload.php';
        require_once '../database/DatabaseConnector.php';
		require_once '../core/MvcUri.php';
		require_once '../core/Router.php';
		require_once '../core/jwt/JwtPayload.php';
        require_once '../core/AuthManager.php';
    ?>
    <main>
        <?php
            $manager = new AuthManager();
            $isLoggedIn = false;
            
            if (array_key_exists('token', $_COOKIE) && !empty($_COOKIE['token']))
            {
                // The user has a token. Verify it
				$isLoggedIn = $manager->verifyToken($_COOKIE['token']);
            }
            
            if ($isLoggedIn) {
                echo "You are logged in!";
            } else {
            
            }
            
			$router = new Router();
			$router->manageUrl();
		?>
    </main>
</body>

</html>