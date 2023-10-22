<?php
	
	namespace bikeshop\app\database\models;
	use Money\Money;
	
	class DbProduct
	{
		public function __construct(
			private int $id,
			private int $categoryId,
			private string $name,
			private string $description,
			private float $price
		)
		{
		}
		
		public function getId() : int
		{
			return $this->id;
		}
		
		public function getCategoryId() : int
		{
			return $this->categoryId;
		}
		
		public function getDescription() : string
		{
			return $this->description;
		}
		
		public function getName() : string
		{
			return $this->name;
		}
		
		public function getPrice() : float
		{
			return $this->price;
		}
		
		public function __toString() : string
		{
			return $this->name . ': $' . $this->price;
		}
	}