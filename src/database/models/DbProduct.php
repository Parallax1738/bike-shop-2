<?php
	
	use Money\Money;
	
	class DbProduct
	{
		private int $id;
		private int $categoryId;
		private string $name;
		private Money $price;
		
		public function __construct(int $id, int $categoryId, string $name, Money $price)
		{
			$this->id = $id;
			$this->categoryId = $categoryId;
			$this->name = $name;
			$this->price = $price;
		}
		
		public function getId(): int
		{
			return $this->id;
		}
		
		public function getCategoryId(): int
		{
			return $this->categoryId;
		}
		
		public function getName(): string
		{
			return $this->name;
		}
		
		public function getPrice(): Money
		{
			return $this->price;
		}
		
		public function __toString(): string
		{
			return $this->name . ': $' . $this->price->getAmount();
		}
	}