<?php
	
	namespace bikeshop\app\models;
	
	use bikeshop\app\core\ApplicationState;
	
	/**
	 * A base class that will display a list of items from the database
	 */
	class PagingModel extends ModelBase
	{
		/**
		 * @param array $list The list of things to be displayed
		 * @param int $currentPage The page the user is currently on
		 * @param int $pageCount The amount of pages for the SQL query
		 * @param int $maxResults The amount of items to be displayed
		 */
		public function __construct(private array $list, private int $currentPage, private int $pageCount, private int $maxResults, ApplicationState $state)
		{
			parent::__construct($state);
		}
		
		/**
		 * The list of things to be displayed
		 */
		public function getList() : array
		{
			return $this->list;
		}
		
		/**
		 * The current page that the user is on
		 */
		public function getCurrentPage() : int
		{
			return $this->currentPage;
		}
		
		/**
		 * The amount of pages
		 */
		public function getMaxPage() : int
		{
			return $this->pageCount;
		}
		
		/**
		 * How many list items should show
		 */
		public function getMaxResults() : int
		{
			return $this->maxResults;
		}
	}