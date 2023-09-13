<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Bike shop</title>
    <link href="../../output.css" rel="stylesheet">
</head>

<body>
    <?php
        include './components/navbar.php';
        require '../../vendor/autoload.php';
    ?>
    <main>
        <?php
			
			require '../base/MvcUri.php';
			require '../base/Router.php';
			
			/**
			 * Loop through URL's string. Add all characters to $str. When / is found, save that string to find get
			 * the controller/action. Once ? is found, add to parameters array. Therefore, we should loop through
			 * the string's characters and break every time we find a / or a ?. This is a fucking awful 'solution'
			 * @return MvcUri The Uri as an object to get whatever the fuck the user entered as an object
			 */
			function getUri() : MvcUri
			{
				$url = $_SERVER[ "REQUEST_URI" ];
				
				$controller = "";
				$action = "";
				$params = [];
				$tempString = "";
				
				// | http://example.com | OR | http://example.com/ |
				if ($url == "" || $url == "/") {
					return new MvcUri($controller, $action, $params);
				}
				
				$urlSplit = mb_str_split($url);
				$urlSplit = array_slice($urlSplit, 1); // Get rid of the first '/' to not break things :(
				
				foreach ($urlSplit as $char) {
					// If neither controller nor action is empty and there is a /, then add it to controller or action
					if (!empty($controller) && !empty($action)) {
						// do parameters
					} else if ($char == "/") {
						// If the controller is empty, then we know that it isn't set, and that we must do it now.
						if (empty($controller)) {
							$controller = $tempString;
							$tempString = "";
						}
					} else if ($char == "?" && empty($action)) {
						// The action usually becomes before parameters via '?', for example:
						// http://example.com/controller/"action?test=5"
						$action = $tempString;
						$tempString = "";
					} else {
						$tempString .= $char;
					}
				}
				if (empty($controller)) {
					$controller = $tempString;
				} else if (empty($action)) {
					$action = $tempString;
				}
				
				return new MvcUri($controller, $action, $params);
			}
			
			$router = new Router();
			$router->manageUrl(getUri());
			
			getUri();
		?>
    </main>
    </div>
</body>

</html>