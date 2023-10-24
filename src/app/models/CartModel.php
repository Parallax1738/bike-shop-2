<?php
	
	namespace bikeshop\app\models;
	
	use bikeshop\app\core\ApplicationState;
	use bikeshop\app\database\entity\ProductEntity;
	use bikeshop\app\models\ModelBase;
	use Money\Money;
	
	class CartModel extends ModelBase
	{
		public function __construct(
			private array $products,
			ApplicationState $state
		)
		{
			parent::__construct($state);
		}
		
		public function getProducts(): array
		{
			return $this->products;
		}
	}
	
	