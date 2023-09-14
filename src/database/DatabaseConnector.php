<?php
	
	namespace database;
	class DatabaseConnector
	{
		private string $databaseUser;
		private string $databasePwd;
		private string $databaseName;
		private string $databaseHost;
		private string $databasePort;
		private mysqli | null $mysqli;
		
		public function __construct(string $user, string $pwd, string $dbname, string $host, string $port)
		{
			$this->databaseUser = $user;
			$this->databasePwd = $pwd;
			$this->databaseName = $dbname;
			$this->databaseHost = $host;
			$this->databasePort = $port;
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
		private function connect() : void
		{
			if ($this->isConnected()) {
				return;
			}
			
			$this->mysqli = new mysqli($this->databaseHost, $this->databaseUser, $this->databasePwd, $this->databaseName, $this->databasePort);
		}
		
		/**
		 * @return bool If the database is connected to PHP ocl
		 */
		private function isConnected() : bool
		{
			return empty($this->mysqli);
		}
		
		/**
		 * Disconnects from the database if the connection is still active
		 * @return void
		 */
		private function disconnect() : void
		{
			echo $this->mysqli == null ? "true" : 'false';
			if (!$this->isConnected()) {
				return;
			}
			
			$this->mysqli->close();
			$this->mysqli = null;
		}
	}