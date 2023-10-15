<?php
	
	namespace bikeshop\app\core;
	class Controller
	{
		/**
		 * Finds the view for the controller and action. Deprecated, please use Controller::view
		 * @param string $controller Controller name to look for. Example for `TestController` class would be `test`
		 * @param string $action The action or view you want to load
		 * @param mixed $data to be passed into the function. Optional
		 * @return void
		 * @deprecated Please use Controller::view
		 */
		protected function deprecatedView(string $controller, string $action, mixed $data = null) : void
		{
			$fileName = __DIR__ . "/../../public/" . $controller . "/" . $action . ".php";
			if (!file_exists($fileName)) {
				echo "The view {" . $fileName . "} does not exist. Please ensure that the view you are
				 trying to display is inside src/public/" . $controller . "/" . $action . ".php.";
			} else {
				// Loads the file, which is where the HTML/CSS will be loaded for the user
				include $fileName;
			}
		}
		
		/**
		 * Finds the view for the controller and action
		 * @param ActionResult $result Contains information about the view to load, and the model it needs
		 * @return void
		 */
		protected function view(ActionResult $result): void
		{
			$fileName = $result->getViewFile(__DIR__ . "/../../public/");
			if (!file_exists($fileName))
			{
				$this->view($this->http404ResponseAction());
				return;
			}
			else
			{
				// Ensure to load data before including the PHP file so that the PHP file can read the data
				// (that sentence was needlessly long)
				$data = $result->getData();
				include $fileName;
			}
		}
		
		/**
		 * Wiki Definition:
		 *
		 * The server cannot or will not process the request due to an apparent client error (e.g., malformed request
		 * syntax, size too large, invalid request message framing, or deceptive request routing).
		 * @return ActionResult
		 */
		protected function http400ResponseAction(): ActionResult
		{
			return new ActionResult('error', 'http400');
		}
		
		/**
		 * Wiki Definition:
		 *
		 *
		 * Similar to 403 Forbidden, but specifically for use when authentication is required and has failed or has not
		 * yet been provided. The response must include a WWW-Authenticate header field containing a challenge
		 * applicable to the requested resource. See Basic access authentication and Digest access authentication.
		 * 401 semantically means "unauthorised", the user does not have valid authentication credentials for the target
		 * resource.
		 * @return ActionResult
		 */
		protected function http401ResponseAction(): ActionResult
		{
			return new ActionResult('error', 'http401');
		}
		
		/**
		 * Wiki Definition:
		 *
		 * The request contained valid data and was understood by the server, but the server is refusing action. This
		 * may be due to the user not having the necessary permissions for a resource or needing an account of some
		 * sort, or attempting a prohibited action (e.g. creating a duplicate record where only one is allowed). This
		 * code is also typically used if the request provided authentication by answering the WWW-Authenticate header
		 * field challenge, but the server did not accept that authentication. The request should not be repeated.
		 * @return ActionResult
		 */
		protected function http403ResponseAction(): ActionResult
		{
			return new ActionResult('error', 'http403');
		}
		
		/**
		 * Wiki Definition:
		 *
		 * The requested resource could not be found but may be available in the future. Subsequent requests by the client are permissible
		 * @return ActionResult
		 */
		protected function http404ResponseAction(): ActionResult
		{
			return new ActionResult('error', 'http404');
		}
		
		/**
		 * Wiki Definition:
		 *
		 * A request method is not supported for the requested resource; for example, a GET request on a form that requires data to be presented via POST, or a PUT request on a read-only resource.
		 * @return ActionResult
		 */
		protected function http405ResponseAction(): ActionResult
		{
			return new ActionResult('error', 'http405');
		}
	}
