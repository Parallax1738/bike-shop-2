<?php
	require '../models/DbUserModel.php';
	
	class DatabaseConnector
	{
		private string $databaseUser;
		private string $databasePwd;
		private string $databaseName;
		private string $serverName;
		private mysqli | null $mysqli;
		
		public function __construct(string $user, string $pwd, string $dbname, string $serverName)
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
		
		public function getUsers(): array
		{
			$users = [];
			
			$this->connect();
			$sql = $this->mysqli->prepare("SELECT * FROM USER");
			
			if ($sql->execute())
			{
				$sql->bind_result($id, $firstName, $lastName, $emailAddress, $password, $address, $suburb, $state, $postcode, $country, $phone);
				while ($sql->fetch())
				{
					$user = new DbUserModel($id, $firstName, $lastName, $emailAddress, $password, $address, $suburb, $state, $postcode, $country, $phone);
					$user->print();
				}
			}
			return $users;
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
			if (!$this->isConnected()) {
				return;
			}
			
			$this->mysqli->close();
			$this->mysqli = null;
		}
	}