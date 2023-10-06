<?php
	
	class BikeDisplayModel
	{
		public function __construct(
			private array $bikes,
			private int $totalPageNum,
			private int $pageIndex,
			private int $resultCount)
		{
		
		}
		
		public function getBikes(): array
		{
			return $this->bikes;
		}
		
		public function getTotalPageNum(): int
		{
			return $this->totalPageNum;
		}
		
		public function getPageIndex(): int
		{
			return $this->pageIndex;
		}
		
		public function getResultCount(): int
		{
			return $this->resultCount;
		}
	}