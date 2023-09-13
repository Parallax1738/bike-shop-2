<?php
	
	class DatabaseConnector
	{
		private string $databaseUser;
		private string $databasePwd;
		private string $databaseName;
		private string $databaseHost;
		private string $databasePort;
		private mysqli|null $mysqli;
		
		/**
		 * @param string $user The username for the database
		 * @param string $pwd The password of the user for the database
		 * @param string $dbname The database name
		 * @param string $host The host name (127.0.0.1) that the database is running on
		 */
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
			$this->Disconnect();
		}
		
		/**
		 * Connects to the database
		 * @return void
		 */
		private function Connect() {
			if ($this->IsConnected()) {
				return;
			}
			
			$this->mysqli = new mysqli(
				$this->databaseHost,
				$this->databaseUser,
				$this->databasePwd,
				$this->databaseName,
				$this->databasePort
			);
		}
		
		/**
		 * @return bool If the database is connected to PHP ocl
		 */
		private function IsConnected(): bool {
			return $this->mysqli != null;
		}
		
		/**
		 * Disconnects from the database if the connection is still active
		 * @return void
		 */
		private function Disconnect() {
			if ($this->mysqli == null) {
				return;
			}
			
			$this->connection->close();
			$this->connection = null;
		}
	}