<?php
	
	namespace bikeshop\app\controller;
	
	use ArrayObject;
	use bikeshop\app\core\ApplicationState;
	use bikeshop\app\core\ArrayWrapper;
	use bikeshop\app\core\Controller;
	use bikeshop\app\core\IHasIndexPage;
	use bikeshop\app\database\DatabaseConnector;
	use bikeshop\app\database\models\DbProduct;
	use bikeshop\app\models\CartModel;
	use Money\Currency;
	use Money\Money;
	
	class CartController extends Controller implements IHasIndexPage
	{
		private DatabaseConnector $db;
		
		public function __construct()
		{
			$this->db = new DatabaseConnector();
		}
		
		public function index(ApplicationState $state)
		{
			if ($_SERVER ["REQUEST_METHOD"] == "GET")
			{
				// Get cookie
				$cookies = new ArrayWrapper($_COOKIE);
				$cartDecoded = base64_decode($cookies->getValueWithKey('cart'));
				$cartJson = json_decode($cartDecoded, true);
				
				// Convert it into PHP objects by looking at p-id in database
				$ids = [ ];
				foreach ($cartJson as $item)
				{
					$ids[] = $item['p-id'];
				}
				// TODO - Call to database using $ids
//				$products = $this->db->selectAllProducts($ids);
				$products = [
					new DbProduct(1, 1, 'test', new Money(10, new Currency('AUD'))),
					new DbProduct(2, 3, 'test 2', new Money(20, new Currency('AUD'))),
				];
				
				// Send of to view
				return new CartModel($products, $state);
			}
			else
			{
				$this->view($this->http405ResponseAction());
			}
		}
	}