<?php
	
	namespace bikeshop\app\database\repository;
	
	use bikeshop\app\database\DatabaseConnector;
	use bikeshop\app\database\entity\ProductEntity;
	use bikeshop\app\database\entity\ProductFilterEntity;
	
	class ProductRepository extends DatabaseConnector
	{
		/**
		 * Selects all products from the ids array
         * @param array $ids The products you want to select
		 * @return array of <code>ProductEntity</code>: The entire product records from the database
		 */
		public function selectAllProducts(array $ids) : array
		{
			$this->connect();
			
			if (empty($ids))
				return [ ];
			
			// If there are 3 ids in the array, $placeholders = (???)
			$placeholders = implode(',', array_fill(0, count($ids), '?'));
			$sql = "SELECT * FROM PRODUCT WHERE ID IN ($placeholders)";
			$stmt = $this->mysqli->prepare($sql);
			
			// If there are 3 ids in the array, $types = (iii)
			$types = str_repeat('i', count($ids)); // Assumes all IDs are integers
			$stmt->bind_param($types, ...$ids);
			
			$products = [ ];
			
			if ($stmt->execute())
			{
				$stmt->bind_result($id, $catId, $name, $description, $price);
				while ($stmt->fetch())
				{
					$products[] = new ProductEntity($id, $catId, $name, $description, $price);
				}
			}
			
			$this->disconnect();
			return $products;
		}
		
		
		// TODO - Make select product count have parameters like product filters, query string, etc
		/**
		 * Gets the amount of products that were returned from a search query
		 * @param int|null $categoryId
		 * @return int the amount of records found
		 */
		public function selectProductCount(int | null $categoryId) : int
		{
			$this->connect();
			
			$sql = "SELECT COUNT(*) FROM BIKE_SHOP.`PRODUCT`";
			
			if ($categoryId)
			{
				$sql .= " WHERE PRODUCT.CATEGORY_ID = ?";
			}
			
			$stmt = $this->mysqli->prepare($sql);
			
			if ($categoryId)
			{
				$stmt->bind_param('i', $categoryId);
			}
			
			if ($stmt->execute())
			{
				$stmt->bind_result($c);
				if ($stmt->fetch())
				{
					$this->disconnect();
					return $c;
				}
			}
			
			$this->disconnect();
			return 0;
		}
		
		/**
		 * Selects products from the database using the parameters from this function as seen bellow
		 * @param int|null $categoryId The category of the product
		 * @param array|null $productFilters All filters to include. Selecting multiple filters won't disclude product
		 * if it doesn't have all filters. For example, if a product has filters [ 4, 5], but this method called
		 * [ 4, 5, 6], It will still be returned
		 * @param int $offset Records to skip from the end result query
		 * @param int $limit Amount of records to return
		 * @return array of <code>ProductEntity</code>All products found
		 */
		public function selectProducts(int | null $categoryId, array | null $productFilters, int $offset = 0, int $limit = 0) : array
		{
			$this->connect();
			
			// -- QUERY GENERATION --
			// Initialise base sql query
			$hasCategoryId = $categoryId != null;
			$hasProductFilters = $productFilters != null && count($productFilters) > 0;
			$params = "";
			
			if (!$hasProductFilters)
			{
				$sql = "SELECT _P.ID, _P.NAME, _P.PRICE, _P.DESCRIPTION, _P.CATEGORY_ID FROM PRODUCT AS _P";
			}
			else
			{
				$sql = "SELECT _P.ID, _P.NAME, _P.PRICE, _P.DESCRIPTION, _P.CATEGORY_ID FROM PRODUCT AS _P
						INNER JOIN PRODUCT_FILTER_LINK AS _PFL ON _P.ID = _PFL.PRODUCT_ID";
			}
			
			// If category exists, ensure to add it to WHERE clause
			if ($hasCategoryId)
			{
				$sql .= " WHERE _P.CATEGORY_ID = ?";
				$params .= "i";
			}
			
			// If product filters exist, ensure to check if PRODUCT_FILTER_ID IN []
			if ($hasProductFilters)
			{
				if ($hasCategoryId)
				{
					$sql .= " AND";
				}
				else
				{
					$sql .= " WHERE";
				}
				
				$placeholders = implode(',', array_fill(0, count($productFilters), '?'));
				
				$sql .= " _PFL.PRODUCT_FILTER_ID IN (" . $placeholders . ")";
				$params .= str_repeat('i', count($productFilters));
			}
			
			$sql .= ' LIMIT ? OFFSET ?;';
			$params .= 'ii';
			$stmt = $this->mysqli->prepare($sql);
			
			// -- BIND PARAMS --
			if ($hasCategoryId)
			{
				if ($hasProductFilters)
				{
					// Convert $productFilters into int array instead of DbProductFilter array so that it can be
					// properly binded
					$productFilterIds = [];
					foreach ($productFilters as $p)
					{
						if ($p instanceof ProductFilterEntity)
						{
							$productFilterIds[] = $p->getId();
						}
					}
					
					$productFilterIds[] = $limit;
					$productFilterIds[] = $offset;
					
					$stmt->bind_param($params, $categoryId, ...$productFilterIds);
				}
				else
					$stmt->bind_param($params, $categoryId, $limit, $offset);
			} else {
				if ($hasProductFilters)
				{
					$productFilterIds = [];
					foreach ($productFilters as $p)
					{
						if ($p instanceof ProductFilterEntity)
						{
							$productFilterIds[] = $p->getId();
						}
					}
					
					$productFilterIds[] = $limit;
					$productFilterIds[] = $offset;
					
					$stmt->bind_param($params, ...$productFilterIds);
				}
				else
				{
					$stmt->bind_param($params, $limit, $offset);
				}
			}
			
			// -- QUERY EXECUTION --
			$records = [];
			if ($stmt->execute())
			{
				$stmt->bind_result($resultId, $resultName, $resultPrice, $resultDescription, $resultCategoryId);
				while ($stmt->fetch())
				{
					$records[] = new ProductEntity($resultId, $resultCategoryId, $resultName, $resultDescription, $resultPrice);
				}
			}
			
			$this->disconnect();
			return $records;
		}
		
		/**
		 * Extension to <code>ProductRepository::selectProducts</code>. The only reason this exists is because prepared
		 * sql statements can become really hard to do with multiple optional parameters, so it was easier to make this
		 * a seperate query. This function is about as bad as its going to get.
		 * @param string $query Search term that is compared against PRODUCT.NAME
		 * @param int|null $categoryId The category of the product
		 * @param array|null $productFilters All filters to include. Selecting multiple filters won't disclude product
		 * if it doesn't have all filters. For example, if a product has filters [ 4, 5], but this method called
		 * [ 4, 5, 6], It will still be returned
		 * @param int $offset Records to skip from the end result query
		 * @param int $limit Amount of records to return
		 * @return array of <code>ProductEntity</code> All products found
		 */
		public function selectProductsWithQuery(string $query, int | null $categoryId, array | null $productFilters, int $offset = 0, int $limit = 0) : array
		{
			if  (empty($query)) return [];
			
			// Filter and sanatise query before inserting into query
			$query = str_replace('+', ' ', $query);
			$query = '%' . $query . '%';
			
			$this->connect();
			
			// -- QUERY GENERATION --
			// Initialise base sql query
			$hasCategoryId = $categoryId != null;
			$hasProductFilters = $productFilters != null && count($productFilters) > 0;
			$params = "";
			
			if (!$hasProductFilters)
			{
				$sql = "SELECT _P.ID, _P.`NAME`, _P.PRICE, _P.DESCRIPTION, _P.CATEGORY_ID FROM PRODUCT AS _P";
			}
			else
			{
				$sql = "SELECT _P.ID, _P.`NAME`, _P.PRICE, _P.DESCRIPTION, _P.CATEGORY_ID FROM PRODUCT AS _P
						INNER JOIN PRODUCT_FILTER_LINK AS _PFL ON _P.ID = _PFL.PRODUCT_ID";
			}
			
			// If category exists, ensure to add it to WHERE clause
			if ($hasCategoryId)
			{
				$sql .= " WHERE _P.CATEGORY_ID = ?";
				$params .= "i";
			}
			
			// If product filters exist, ensure to check if PRODUCT_FILTER_ID IN []
			if ($hasProductFilters)
			{
				if ($hasCategoryId)
				{
					$sql .= " AND";
				}
				else
				{
					$sql .= " WHERE";
				}
				
				$placeholders = implode(',', array_fill(0, count($productFilters), '?'));
				
				$sql .= " _PFL.PRODUCT_FILTER_ID IN (" . $placeholders . ")";
				$params .= str_repeat('i', count($productFilters));
			}
			
			// Where clause already included
			if ($hasCategoryId || $hasProductFilters)
			{
				$sql .= ' AND _P.`NAME` LIKE ? LIMIT ? OFFSET ?;';
			}
			else
			{
				$sql .= ' WHERE _P.`NAME` LIKE ? LIMIT ? OFFSET ?;';
			}
			
			$params .= 'sii';
			$stmt = $this->mysqli->prepare($sql);
			
			// Bind Params
			if ($hasCategoryId)
			{
				if ($hasProductFilters)
				{
					// Convert $productFilters into int array instead of DbProductFilter array so that it can be
					// properly binded
					$productFilterIds = [];
					foreach ($productFilters as $p)
					{
						if ($p instanceof ProductFilterEntity)
						{
							$productFilterIds[] = $p->getId();
						}
					}
					
					// Forcefully add query to bind_params because PHP is fucking annoying
					$productFilterIds[] = $query;
					$productFilterIds[] = $limit;
					$productFilterIds[] = $offset;
					
					$stmt->bind_param($params, $categoryId, ...$productFilterIds);
				}
				else
				{
					$stmt->bind_param($params, $categoryId, $query, $limit, $offset);
				}
			}
			else
			{
				if ($hasProductFilters)
				{
					// Convert $productFilters into int array instead of DbProductFilter array so that it can be
					// properly binded
					$productFilterIds = [];
					foreach ($productFilters as $p)
						if ($p instanceof ProductFilterEntity)
							$productFilterIds[] = $p->getId();
					
					// Forcefully add query to bind_params because PHP is fucking annoying
					$productFilterIds[] = $query;
					$productFilterIds[] = $limit;
					$productFilterIds[] = $offset;
					
					$stmt->bind_param($params, ...$productFilterIds);
				}
				else
				{
					$stmt->bind_param($params, $query, $limit, $offset);
				}
			}
			
			// Execute Query
			$records = [];
			if ($stmt->execute())
			{
				$stmt->bind_result($resultId, $resultName, $resultPrice, $resultDescription, $resultCategoryId);
				while ($stmt->fetch())
				{
					$records[] = new ProductEntity($resultId, $resultCategoryId, $resultName, $resultDescription, $resultPrice);
				}
			}
			
			return $records;
		}
		
		/**
		 * This function returns an array of DbProductFilters coming from a SQL query. For each product, it selects
		 * finds all PRODUCT_FILTER ids, and groups them together.
		 * @param int|null $categoryId Category of the product
		 * @param string $query Search term that is compared against PRODUCT.NAME
		 * @return array
		 */
		public function selectFiltersFromProducts(int | null $categoryId, string $query = "") : array
		{
			if ($query)
			{
				$query = str_replace('+', ' ', $query);
				$query = ('%'.$query.'%');
			}
			$this->connect();
			
			// I ACTUALLY WANT TO KILL MYSELF. It is this long because prepared statements are not easy at all
			
			// If the category id is null, then that means the query does not need `WHERE PRODUCT.CATEGORY_ID = ?`. But
			// because prepared statements aren't very flexible, I have to split them as you see bellow.
			
			// Then in each if statement, there is another one that checks if the $query string exists. If it does, then
			// it needs to add the `WHERE PRODUCT.NAME LIKE %$query%`. But for the same reason as before, it has to be
			// seperated in this hideous way
			
			if ($categoryId == null)
			{
				// Selects all products in between $count and $offset, only returning the PRODUCT_FILTER.ID grouped
				$sql = "SELECT PRODUCT_FILTER.ID, PRODUCT_FILTER.NAME
				FROM BIKE_SHOP.`PRODUCT`
				INNER JOIN PRODUCT_FILTER_LINK ON PRODUCT.ID = PRODUCT_FILTER_LINK.PRODUCT_ID
				INNER JOIN PRODUCT_FILTER ON PRODUCT_FILTER_LINK.PRODUCT_FILTER_ID = PRODUCT_FILTER.ID";
				
				if ($query)
				{
					$sql .= " WHERE PRODUCT.`NAME` LIKE ? GROUP BY PRODUCT_FILTER.ID;";
					$stmt = $this->mysqli->prepare($sql);
					$stmt->bind_param('s', $query);
				}
				else
				{
					$sql .= " GROUP BY PRODUCT_FILTER.ID;";
					$stmt = $this->mysqli->prepare($sql);
				}
			}
			else
			{
				$sql ="SELECT PRODUCT_FILTER.ID, PRODUCT_FILTER.NAME
				FROM BIKE_SHOP.`PRODUCT`
				INNER JOIN PRODUCT_FILTER_LINK ON PRODUCT.ID = PRODUCT_FILTER_LINK.PRODUCT_ID
				INNER JOIN PRODUCT_FILTER ON PRODUCT_FILTER_LINK.PRODUCT_FILTER_ID = PRODUCT_FILTER.ID
				WHERE PRODUCT.CATEGORY_ID = ?";
				if ($query)
				{
					$sql .= " AND PRODUCT.`NAME` LIKE ? GROUP BY PRODUCT_FILTER.ID;";
					$stmt = $this->mysqli->prepare($sql);
					$stmt->bind_param('is', $categoryId, $query);
				}
				else
				{
					$sql .= " GROUP BY PRODUCT_FILTER.ID;";
					$stmt = $this->mysqli->prepare($sql);
					$stmt->bind_param('i', $categoryId);
				}
			}
			
			$records = [];
			if ($stmt->execute())
			{
				$stmt->bind_result($id, $name);
				while ($stmt->fetch())
				{
					$records[] = new ProductFilterEntity($id, $name);
				}
			}
			
			$this->disconnect();
			return $records;
		}
		
		/**
		 * Gets the category name from an ID
		 * @param int $categoryId ID to search for
		 * @return string|null The category name
		 */
		public function getCategoryName(int $categoryId): string | null
		{
			$this->connect();
			$sql = $this->mysqli->prepare("SELECT `NAME` FROM CATEGORY WHERE ID = ?");
			
			$sql->bind_param('i', $categoryId);
			$sql->bind_result($categoryName);
			
			/**
			 * @noinspection PhpIfWithCommonPartsInspection Cannot do that as disconnection must happen before
			 * execution
			 */
			if ($sql->execute() && $sql->fetch())
			{
				$this->disconnect();
				return $categoryName;
			}
			
			$this->disconnect();
			return null;
		}
	}