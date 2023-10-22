<?php
	namespace bikeshop\app\database;
	use bikeshop\app\database\entity\ProductEntity;
	use bikeshop\app\database\entity\ProductFilterEntity;
	use bikeshop\app\database\entity\UserEntity;
	use bikeshop\app\models\CreateAccountModel;
	use Exception;
	use Money\Currency;
	use Money\Money;
	use mysqli;
	
	require_once 'entity/ProductEntity.php';
	require_once 'entity/UserEntity.php';
	require_once __DIR__ . '/../models/CreateAccountModel.php';
	
	class DatabaseConnector
	{
		private string $databaseUser;
		private string $databasePwd;
		private string $databaseName;
		private string $serverName;
		private mysqli | null $mysqli;
		
		public function __construct(
			string $user = "user",
			string $pwd = "password",
			string $dbname = "BIKE_SHOP",
			string $serverName = "bike-shop-database")
		{
			$this->databaseUser = $user;
			$this->databasePwd = $pwd;
			$this->databaseName = $dbname;
			$this->serverName = $serverName;
		}
		
		public function __destruct()
		{
			// Ensure to delete all active connections
			$this->disconnect();
		}
		
		/**
		 * @return array Key=UserRoleId, Value=UserRoleName
		 */
		public function selectAllUserRoles(): array
		{
			$this->connect();
			$sql = $this->mysqli->prepare("SELECT * FROM USER_ROLES");
			$userRoles = [];
			if ($sql->execute()) {
				$sql->bind_result($id, $name);
				while ($sql->fetch())
				{
					$userRoles[$id] = $name;
				}
			}
			return $userRoles;
		}
		
		public function findUserWithEmailAddress($emailToFind) : UserEntity | null
		{
			$this->connect();
			$sql = $this->mysqli->prepare("SELECT * FROM USER WHERE USER.EMAIL_ADDRESS = ?");
			$sql->bind_param("s", $sqlEmail);
			$sqlEmail = $emailToFind;
			
			if ($sql->execute())
			{
				$sql->bind_result($id, $userRoleId, $emailAddress, $firstName, $lastName, $password, $address, $suburb, $state, $postcode, $country, $phone);
				while ($sql->fetch())
				{
					$user = new UserEntity($id, $userRoleId, $emailAddress, $firstName, $lastName, $password, $address, $suburb, $state, $postcode, $country, $phone);
				}
			}
			$this->disconnect();
			return $user ?? null;
		}
		
		/**
		 * @throws Exception
		 */
		public function selectAllProducts(array $ids) : array
		{
			$this->connect();
			
			if (empty($ids)) {
				return [ ];
			}
			
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
				$records = $stmt->bind_result($id, $catId, $name, $description, $price);
				while ($stmt->fetch())
				{
					$products[] = new ProductEntity($id, $catId, $name, $description, $price);
				}
			}
			
			$this->disconnect();
			return $products;
		}
		
		/**
		 * Gets the amount of products that were returned from a search query
		 * @param string $query What to search for in the db
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
					return $c;
				}
			}
			return 0;
		}
		
		public function selectProductCountWithQuery(string $query, int | null $categoryId, array | null $productFilters) : int
		{
			if (empty($query)) return 0;
			
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
				$sql = "SELECT COUNT(*) FROM PRODUCT AS _P";
			else
				$sql = "SELECT COUNT(*) FROM PRODUCT AS _P
						INNER JOIN PRODUCT_FILTER_LINK AS _PFL ON _P.ID = _PFL.PRODUCT_ID";
			
			// If category exists, ensure to add it to WHERE clause
			if ($hasCategoryId) {
				$sql .= " WHERE _P.CATEGORY_ID = ?";
				$params .= "i";
			}
			
			// If product filters exist, ensure to check if PRODUCT_FILTER_ID IN []
			if ($hasProductFilters) {
				if ($hasCategoryId)
					$sql .= " AND";
				else
					$sql .= " WHERE";
				
				$placeholders = implode(',', array_fill(0, count($productFilters), '?'));
				
				$sql .= " _PFL.PRODUCT_FILTER_ID IN (" . $placeholders . ")";
				$params .= str_repeat('i', count($productFilters));
			}
			
			// Where clause already included
			if ($hasCategoryId || $hasProductFilters)
			{
				$sql .= ' AND _P.`NAME` LIKE ?;';
			}
			else
			{
				$sql .= ' WHERE _P.`NAME` LIKE ?';
			}
			
			$params .= 's';
			$stmt = $this->mysqli->prepare($sql);
			
			// Bind Params
			if ($hasCategoryId) {
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
					
					$stmt->bind_param($params, $categoryId, ...$productFilterIds);
				}
				else
					$stmt->bind_param($params, $categoryId, $query);
			} else {
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
					
					$stmt->bind_param($params, ...$productFilterIds);
				}
				else
				{
					$stmt->bind_param($params, $query);
				}
			}
			
			// Execute Query
			if ($stmt->execute())
			{
				$stmt->bind_result($count);
				if ($stmt->fetch())
					return $count;
			}
			
			return 0;
		}
		
		/**
		 * @throws Exception
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
				$sql = "SELECT _P.ID, _P.NAME, _P.PRICE, _P.DESCRIPTION, _P.CATEGORY_ID FROM PRODUCT AS _P";
			else
				$sql = "SELECT _P.ID, _P.NAME, _P.PRICE, _P.DESCRIPTION, _P.CATEGORY_ID FROM PRODUCT AS _P
						INNER JOIN PRODUCT_FILTER_LINK AS _PFL ON _P.ID = _PFL.PRODUCT_ID";
			
			// If category exists, ensure to add it to WHERE clause
			if ($hasCategoryId) {
				$sql .= " WHERE _P.CATEGORY_ID = ?";
				$params .= "i";
			}
			
			// If product filters exist, ensure to check if PRODUCT_FILTER_ID IN []
			if ($hasProductFilters) {
				if ($hasCategoryId)
					$sql .= " AND";
				else
					$sql .= " WHERE";
				
				$placeholders = implode(',', array_fill(0, count($productFilters), '?'));
				
				$sql .= " _PFL.PRODUCT_FILTER_ID IN (" . $placeholders . ")";
				$params .= str_repeat('i', count($productFilters));
			}
			
			$sql .= ' LIMIT ? OFFSET ?;';
			$params .= 'ii';
			$stmt = $this->mysqli->prepare($sql);
			
			// Bind Params
			if ($hasCategoryId) {
				if ($hasProductFilters)
				{
					// Convert $productFilters into int array instead of DbProductFilter array so that it can be
					// properly binded
					$productFilterIds = [];
					foreach ($productFilters as $p)
						if ($p instanceof ProductFilterEntity)
							$productFilterIds[] = $p->getId();
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
						if ($p instanceof ProductFilterEntity)
							$productFilterIds[] = $p->getId();
					$productFilterIds[] = $limit;
					$productFilterIds[] = $offset;
					
					$stmt->bind_param($params, ...$productFilterIds);
				}
				else
				{
					$stmt->bind_param($params, $limit, $offset);
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
				$sql = "SELECT _P.ID, _P.`NAME`, _P.PRICE, _P.DESCRIPTION, _P.CATEGORY_ID FROM PRODUCT AS _P";
			else
				$sql = "SELECT _P.ID, _P.`NAME`, _P.PRICE, _P.DESCRIPTION, _P.CATEGORY_ID FROM PRODUCT AS _P
						INNER JOIN PRODUCT_FILTER_LINK AS _PFL ON _P.ID = _PFL.PRODUCT_ID";
			
			// If category exists, ensure to add it to WHERE clause
			if ($hasCategoryId) {
				$sql .= " WHERE _P.CATEGORY_ID = ?";
				$params .= "i";
			}
			
			// If product filters exist, ensure to check if PRODUCT_FILTER_ID IN []
			if ($hasProductFilters) {
				if ($hasCategoryId)
					$sql .= " AND";
				else
					$sql .= " WHERE";
				
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
			if ($hasCategoryId) {
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
					
					$stmt->bind_param($params, $categoryId, ...$productFilterIds);
				}
				else
					$stmt->bind_param($params, $categoryId, $query, $limit, $offset);
			} else {
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
		 * @param int|null $categoryId
		 * @param int $offset
		 * @param int $count
		 * @param string $query
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
			
			if ($stmt->execute())
			{
				$records = [];
				$stmt->bind_result($id, $name);
				while ($stmt->fetch())
				{
					$records[] = new ProductFilterEntity($id, $name);
				}
				return $records;
			}
			return [ ];
		}
		
		/**
		 * @throws Exception When an account already exists with a specified email address
		 */
		public function insertUser(CreateAccountModel $newAcc) : void
		{
			// make sure a user does not exist already
			$foundUser = $this->findUserWithEmailAddress($newAcc->getEmail());
			if ($foundUser instanceof UserEntity)
			{
				throw new Exception("An account with the email address " . $foundUser->getEmailAddress() . " already exists!");
			}
			
			$hashedPassword = password_hash($newAcc->getPassword(), PASSWORD_BCRYPT);
			
			$this->connect();
			$sql = $this->mysqli->prepare("INSERT INTO BIKE_SHOP.`USER`(`USER_ROLE_ID`, `EMAIL_ADDRESS`, `PASSWORD`) VALUES(?, ?, ?)");
			$sql->bind_param("iss", $sqlRoleId, $sqlEmail, $sqlPass);
			
			$sqlRoleId = $newAcc->getRoleId();
			$sqlEmail = $newAcc->getEmail();
			$sqlPass = $hashedPassword;
			
			$sql->execute();
			$this->disconnect();
		}
		
		/**
		 * Connects to the database
		 * @return void
		 */
		private function connect() : void
		{
			if ($this->isConnected())
			{
				return;
			}
			
			$this->mysqli = new mysqli($this->serverName, $this->databaseUser, $this->databasePwd, $this->databaseName);
		}
		
		/**
		 * @return bool If the database is connected to PHP ocl
		 */
		private function isConnected() : bool
		{
			return !empty($this->mysqli);
		}
		
		/**
		 * Disconnects from the database if the connection is still active
		 * @return void
		 */
		private function disconnect() : void
		{
			if (!$this->isConnected())
			{
				return;
			}
			
			$this->mysqli->close();
			$this->mysqli = null;
		}
		
		/**
		 * Finds a user from the database that has a specific id
		 * @param int $userId Id to find
		 * @return UserEntity|null Null if no user was found
		 */
		public function findUserWithId(int $userId): UserEntity | null
		{
			$this->connect();
			$sql = $this->mysqli->prepare("SELECT * FROM USER WHERE USER.ID = ?");
			$sql->bind_param("i", $sqlId);
			$sqlId = $userId;
			
			if ($sql->execute())
			{
				$sql->bind_result($id, $userRoleId, $emailAddress, $firstName, $lastName, $password, $address, $suburb, $state, $postcode, $country, $phone);
				if ($sql->fetch())
				{
					$this->disconnect();
					return new UserEntity($id, $userRoleId, $emailAddress, $firstName, $lastName, $password, $address, $suburb, $state, $postcode, $country, $phone);
				}
			}
			
			$this->disconnect();
			return null;
		}
		
		
		/**
		 * Selects all users in the database who have the user role provided in the parameter $userRoles
		 * @param $userRole int of the roles that the database should query
		 * @return array | null All users in the database, null if error
		 */
		public function selectAllUsers(int $userRole): array | null
		{
			$this->connect();
			$sql = $this->mysqli->prepare("SELECT * FROM USER WHERE USER_ROLE_ID = ?");
			$sql->bind_param('i', $sqlUserRole);
			$sqlUserRole = $userRole;
			
			if ($sql->execute())
			{
				$records = [];
				$sql->bind_result($id, $userRoleId, $emailAddress, $firstName, $lastName, $password, $address, $suburb, $state, $postcode, $country, $phone);
				while ($sql->fetch())
				{
					$p = new UserEntity($id, $userRoleId, $emailAddress, $firstName, $lastName, $password, $address, $suburb, $state, $postcode, $country, $phone);
					$records[] = $p;
				}
				return $records;
			}
			else
			{
				return null;
			}
		}
		
		/**
		 * @throws Exception when user was not found in the database
		 */
		public function updateUser(UserEntity $user)
		{
			// Check if user exists
			if (!$this->findUserWithId($user->getId()))
				throw new Exception("User with id was not found");
			
			$this->connect();
			$sql = $this->mysqli->prepare("UPDATE USER SET EMAIL_ADDRESS=?, FIRST_NAME=?, LAST_NAME=?, PASSWORD=?,
                ADDRESS=?, SUBURB=?, STATE=?, POSTCODE=?, COUNTRY=?, PHONE=? WHERE ID=?");
			
			$sql->bind_param('ssssssssssi', $sqlEmail, $sqlFirstName, $sqlLastName, $sqlPassword, $sqlAddress, $sqlSuburb, $sqlState, $sqlPostcode, $sqlCountry, $sqlPhone, $sqlId);
			$sqlEmail = $user->getEmailAddress();
			$sqlFirstName = $user->getFirstName();
			$sqlLastName = $user->getLastName();
			$sqlPassword = $user->getPassword();
			$sqlAddress = $user->getAddress();
			$sqlSuburb = $user->getSuburb();
			$sqlState = $user->getState();
			$sqlPostcode = $user->getPostcode();
			$sqlCountry = $user->getCountry();
			$sqlPhone = $user->getPhone();
			$sqlId = $user->getId();
			
			if ($sql->execute())
			{
				echo "Successful!";
			}
			else
			{
				echo "Unsuccessful";
			}
		}
		
		public function deleteUser(int $accountId)
		{
			$this->connect();
			$sql = $this->mysqli->prepare("DELETE FROM USER WHERE ID = ?");
			$sql->bind_param('i', $sqlId);
			$sqlId = $accountId;
			$sql->execute();
			$this->disconnect();
		}
		
		public function getCategoryName(int $categoryId): string | null
		{
			$this->connect();
			$sql = $this->mysqli->prepare("SELECT `NAME` FROM CATEGORY WHERE ID = ?");
			$sql->bind_param('i', $categoryId);
			$sql->bind_result($categoryName);
			if ($sql->execute() && $sql->fetch()) {
				return $categoryName;
			}
			return null;
		}
	}