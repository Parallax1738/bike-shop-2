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
		public function __construct(
			private array $list,
			private int   $currentPage,
			private int   $pageCount,
			private int   $maxResults,
			ApplicationState $state) {
			parent::__construct($state);
		}
		
		/**
		 * The list of things to be displayed
		 */
		public function getList(): array {
			return $this->list;
		}
		
		/**
		 * The current page that the user is on
		 */
		public function getCurrentPage(): int {
			return $this->currentPage;
		}
		/**
		 * The amount of pages
		 */
		public function getMaxPage(): int {
			return $this->pageCount;
		}
		
		/**
		 * How many list items should show
		 */
		public function getMaxResults(): int {
			return $this->maxResults;
		}
		
		/**
		 * Displays a single product
		 */
		public function displayItem(int $index): string {
			return "<div>" . $this->list[$index] . "</div>";
		}
		
		/**
		 * Displays all products, and arrows to move left and right
		 */
		public function displayItemsHTML() {
			for ($i = 0; $i < count($this->list); $i++) {
				echo $this->displayItem($i);
			}
			
			$results = $this->maxResults;
			
			// Previous Button
			if ($this->currentPage > 0)
			{
				// Display Left Arrow because if it is greater than 0, user can go back a page
				$newPage = $this->currentPage - 1;
				echo '<form method="get" action="http://localhost/bikes?page=' . $newPage . '&results=' . $results . '">
					<input type="submit" value="_<_" style="background-color: darkgrey" />
				</form>';
			}
			
			echo "<div style='background-color: orange'>" . $this->currentPage + 1 . " / " . $this->pageCount;
			
			// Next Button
			if ($this->currentPage < $this->pageCount - 1)
			{
				// Oposite to if statement above
				$newPage = $this->currentPage + 1;
				echo '<form method="get" action="http://localhost/bikes?page=' . $newPage . '&results=' . $results . '">
					<input type="submit" value="_>_" style="background-color: darkgrey"/>
				</form>';
			}
		}
	}