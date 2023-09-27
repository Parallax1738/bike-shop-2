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
    ?>
    <main>
        <?php
            if (array_key_exists('token', $_COOKIE) && !empty($_COOKIE['token']))
            {
                // The user has a token. Verify it
                $token = JwtToken::decode($_COOKIE['token']);
                echo $token->getPayload()->toJson();
            }
            
			$router = new Router();
			$router->manageUrl();
		?>
    </main>
</body>

</html>