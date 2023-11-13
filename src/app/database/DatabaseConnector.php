<?php
	namespace bikeshop\app\database;
	use bikeshop\app\core\ArrayWrapper;
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
		protected mysqli | null $mysqli;
		
		public function __construct()
		{
			$env = new ArrayWrapper($_ENV);
			
			if (!$env->keyExists('MYSQL_USER'))
				die ("Must set MYSQL_USER environment variable");
			
			if (!$env->keyExists('MYSQL_PASSWORD'))
				die ("Must set MYSQL_USER environment variable");
			
			if (!$env->keyExists('MYSQL_DATABASE'))
				die ("Must set MYSQL_USER environment variable");
			
			$this->databaseUser = $env->getValueWithKey('MYSQL_USER');
			$this->databasePwd = $env->getValueWithKey('MYSQL_PASSWORD');
			$this->databaseName = $env->getValueWithKey('MYSQL_DATABASE');
			$this->serverName = "bike-shop-database";
			
		}
		
		public function __destruct()
		{
			// Ensure to delete all active connections
			$this->disconnect();
		}
		
		/**
		 * Connects to the database
		 * @return void
		 */
		protected function connect() : void
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
		protected function isConnected() : bool
		{
			return !empty($this->mysqli);
		}
		
		/**
		 * Disconnects from the database if the connection is still active
		 * @return void
		 */
		protected function disconnect() : void
		{
			if (!$this->isConnected())
			{
				return;
			}
			
			$this->mysqli->close();
			$this->mysqli = null;
		}

	}