<?php
	namespace bikeshop\app\models;
	
	use bikeshop\app\database\models\DbProduct;
	use Money\Money;
	
	/**
	 * This extends DbProduct, and contains a quantity which is something needed for cart items
	 */
	class CartProductModel extends DbProduct
	{
		public function __construct(private int $quantity, int $id, int $categoryId, string $name, Money $price)
		{
			parent::__construct($id, $categoryId, $name, $price);
		}
		
		public function getQuantity() : int
		{
			return $this->quantity;
		}
	}