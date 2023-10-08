<?php
	namespace bikeshop\app\database;
	use bikeshop\app\database\models\DbProduct;
	use bikeshop\app\database\models\DbUserModel;
	use bikeshop\app\models\CreateAccountModel;
	use Money\Currency;
	use Money\Money;
	use mysqli;
	
	require_once 'models/DbProduct.php';
	require_once 'models/DbUserModel.php';
	require_once __DIR__ . '/../models/CreateAccountModel.php';
	
	class DatabaseConnector
	{
		private string $databaseUser;
		private string $databasePwd;
		private string $databaseName;
		private string $serverName;
		private mysqli | null $mysqli;
		
		public function __construct(string $user, string $pwd, string $dbname, string $serverName = "bike-shop-database")
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
		
		public function findUserWithEmailAddress($emailToFind) : DbUserModel | null
		{
			$this->connect();
			$sql = $this->mysqli->prepare("SELECT * FROM USER WHERE USER.EMAIL_ADDRESS = ?");
			$sql->bind_param("s", $sqlEmail);
			$sqlEmail = $emailToFind;
			
			if ($sql->execute()) {
				$sql->bind_result($id, $emailAddress, $firstName, $lastName, $password, $address, $suburb, $state, $postcode, $country, $phone);
				while ($sql->fetch()) {
					$this->disconnect();
					return new DbUserModel($id, $emailAddress, $firstName, $lastName, $password, $address, $suburb, $state, $postcode, $country, $phone);
				}
			}
			$this->disconnect();
			return null;
		}
		
		public function selectAllProducts(int $offset = 0, int $count = 0) : array
		{
			$this->connect();
			
			$stmt = $this->mysqli->prepare("SELECT * FROM PRODUCT LIMIT ? OFFSET ?");
			$stmt->bind_param('ii', $limitSql, $offsetSql);
			
			$offsetSql = $offset;
			$limitSql = $count;
			
			if ($stmt->execute()) {
				$records = [];
				$stmt->bind_result($resultId, $resultCategory, $resultName, $resultPrice);
				while ($record = $stmt->fetch()) {
					$p = new DbProduct($resultId, $resultCategory, $resultName, new Money($resultPrice, new Currency('AUD')));
					$records[] = $p;
				}
				return $records;
			}
			throw new Exception("Unable to do somtehming with the database");
		}
		
		/**
		 * Gets the amount of bikes that were returned from a search query
		 * @param string $query What to search for in the db
		 * @return int the amount of records found
		 */
		public function selectBikesCount(string $query = "") : int
		{
			$this->connect();
			
			$stmt = $this->mysqli->prepare("SELECT COUNT(*) FROM PRODUCT WHERE CATEGORY_ID = 1");
			
			if ($stmt->execute()) {
				$stmt->bind_result($c);
				if ($stmt->fetch()) {
					return $c;
				}
			}
			return 0;
		}
		
		public function selectBikes(int $offset = 0, int $count = 0, string $query = "") : array
		{
			$this->connect();
			
			$stmt = $this->mysqli->prepare("SELECT * FROM PRODUCT WHERE CATEGORY_ID = 1 LIMIT ? OFFSET ?");
			$stmt->bind_param('ii', $limitSql, $offsetSql);
			
			$offsetSql = $offset;
			$limitSql = $count;
			
			if ($stmt->execute()) {
				$records = [];
				$stmt->bind_result($resultId, $resultCategory, $resultName, $resultPrice);
				while ($record = $stmt->fetch()) {
					$p = new DbProduct($resultId, $resultCategory, $resultName, new Money($resultPrice, new Currency('AUD')));
					$records[] = $p;
				}
				return $records;
			}
			throw new Exception("Unable to do somtehming with the database");
		}
		
		/**
		 * @throws Exception When an account already exists with a specified email address
		 */
		public function insertUser(CreateAccountModel $newAcc) : void
		{
			// make sure a user does not exist already
			$foundUser = $this->findUserWithEmailAddress($newAcc->getEmail());
			if ($foundUser instanceof DbUserModel) {
				throw new Exception("An account with the email address " . $foundUser->getEmailAddress() . " already exists!");
			}
			
			$hashedPassword = password_hash($newAcc->getPassword(), PASSWORD_BCRYPT);
			
			$this->connect();
			$sql = $this->mysqli->prepare("INSERT INTO USER (EMAIL_ADDRESS, PASSWORD) VALUES(?, ?)");
			$sql->bind_param("ss", $sqlEmail, $hashedPassword);
			
			$sqlEmail = $newAcc->getEmail();
			$sqlPass = $newAcc->getPassword();
			
			$sql->execute();
			$this->disconnect();
		}
		
		/**
		 * Connects to the database
		 * @return void
		 */
		private function connect() : void
		{
			if ($this->isConnected()) {
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
			if (!$this->isConnected()) {
				return;
			}
			
			$this->mysqli->close();
			$this->mysqli = null;
		}
		
		public function findUserWithIdAddress(int $userId)
		{
			$this->connect();
			$sql = $this->mysqli->prepare("SELECT * FROM USER WHERE USER.ID = ?");
			$sql->bind_param("i", $sqlId);
			$sqlId = $userId;
			
			if ($sql->execute()) {
				$sql->bind_result($id, $emailAddress, $firstName, $lastName, $password, $address, $suburb, $state, $postcode, $country, $phone);
				while ($sql->fetch()) {
					$this->disconnect();
					return new DbUserModel($id, $emailAddress, $firstName, $lastName, $password, $address, $suburb, $state, $postcode, $country, $phone);
				}
			}
			$this->disconnect();
			return null;
		}
	}