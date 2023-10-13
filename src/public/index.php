<?php
    namespace public;
	require "../../vendor/autoload.php";
	use bikeshop\app\database\models\DbUserModel;
	use bikeshop\public\Bootstrapper;
	
	$bootstrapper = new Bootstrapper();
    $loggedInUser = $bootstrapper->InitAuth();
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
            $bootstrapper->Start();
		?>
    </main>
</body>

</html>