<?php
	
	namespace bikeshop\app\controller;
	
	use bikeshop\app\core\ApplicationState;
	use bikeshop\app\models\PagingModel;
	
	class SearchModel extends PagingModel
	{
		public function __construct(
			private string $searchQuery,
			array $list,
			int $currentPage,
			int $pageCount,
			int $maxResults,
			ApplicationState $state)
		{
			parent::__construct($list,$currentPage,$pageCount,$maxResults,$state);
		}
		
		public function getSearchQuery(): string
		{
			return $this->searchQuery;
		}
	}