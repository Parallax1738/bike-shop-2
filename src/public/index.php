<?php
    namespace public;
	require "../../vendor/autoload.php";
	use bikeshop\app\core\ApplicationState;
	use bikeshop\app\database\models\DbUserModel;
	use bikeshop\public\Bootstrapper;
 
?>

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
    ?>
    <main>
        <?php
            // Create Bootstrapper Object
            $bootstrapper = new Bootstrapper();
            
            // Get Application State Information
			$loggedInUser = $bootstrapper->InitAuth();
			$state = new ApplicationState($loggedInUser);
            
            $bootstrapper->Start($state);
		?>
    </main>
</body>

</html>