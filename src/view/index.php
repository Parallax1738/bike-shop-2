<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Bike shop</title>
    <link href="../../output.css" rel="stylesheet">
</head>

<body>
    <?php
		include './ui-components/navbar.php';
        require '../../vendor/autoload.php';
        require '../database/DatabaseConnector.php';
    ?>
    <main>
        <?php
			require '../base/MvcUri.php';
			require '../base/Router.php';
			
			$router = new Router();
			$router->manageUrl();
		?>
    </main>
    </div>
</body>

</html>