<?php
	namespace bikeshop\app\models;
	
	use bikeshop\app\database\entity\ProductEntity;
	use Money\Money;
	
	/**
	 * This extends DbProduct, and contains a quantity which is something needed for cart items
	 */
	class CartProductEntity extends ProductEntity
	{
		public function __construct(private int $quantity, int $id, int $categoryId, string $name, string $description, float $price)
		{
			parent::__construct($id, $categoryId, $name, $description, $price);
		}
		
		public function getQuantity() : int
		{
			return $this->quantity;
		}
	}