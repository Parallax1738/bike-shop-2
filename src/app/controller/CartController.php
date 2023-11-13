<?php
	
	namespace bikeshop\app\controller;
	
	use bikeshop\app\core\ActionResult;
	use bikeshop\app\core\ApplicationState;
	use bikeshop\app\core\ArrayWrapper;
	use bikeshop\app\core\attributes\HttpMethod;
	use bikeshop\app\core\attributes\RouteAttribute;
	use bikeshop\app\core\Controller;
	use bikeshop\app\core\IHasIndexPage;
	use bikeshop\app\database\repository\ProductRepository;
	use bikeshop\app\models\CartModel;
	use Money\Currency;
	use Money\Money;
	
	class CartController extends Controller implements IHasIndexPage
	{
		private ProductRepository $db;
		
		public function __construct()
		{
			$this->db = new ProductRepository();
		}
		
		#[RouteAttribute( HttpMethod::GET, "index" )]
		public function index(ApplicationState $state) : void
		{
			// Get cookie
			$products = $this->db->selectAllProducts($this->getProductIdsFromCart());
			
			// Send of to view
			$this->view(new ActionResult('cart', 'index', ( new CartModel($products, $state) )));
		}
		
		private function getProductIdsFromCart() : array
		{
			$cookies = new ArrayWrapper($_COOKIE);
			if (!$cookies->keyExists('cart')) {
				return [];
			}
			
			$cartDecoded = base64_decode($cookies->getValueWithKey('cart'));
			$cartJson = json_decode($cartDecoded, true);
			
			// Convert it into PHP objects by looking at p-id in database
			$ids = [];
			foreach ($cartJson as $item) {
				$ids[] = $item[ 'p-id' ];
			}
			
			return $ids;
		}
		
		#[RouteAttribute( HttpMethod::GET, "checkout" )]
		public function checkout(ApplicationState $state) : void
		{
			// Get cookie
			$products = $this->db->selectAllProducts($this->getProductIdsFromCart());
			
			// Send of to view
			$this->view(new ActionResult('cart', 'checkout', ( new CartModel($products, $state) )));
		}
		
		#[RouteAttribute( HttpMethod::POST, "checkout" )]
		public function checkoutPost()
		{
			// TODO - Implement Checkout Stuffs
		}
	}