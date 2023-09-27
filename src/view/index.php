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
            
			$router = new Router();
			$router->manageUrl();
		?>
    </main>
</body>

</html>