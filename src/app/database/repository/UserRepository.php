<?php
	
	namespace bikeshop\app\database\repository;
	
	use bikeshop\app\database\DatabaseConnector;
	use bikeshop\app\database\entity\StaffShiftEntity;
	use bikeshop\app\database\entity\UserEntity;
	use bikeshop\app\models\CreateAccountModel;
	use DateTime;
	use Exception;
	
	class UserRepository extends DatabaseConnector
	{
		public function getRoster(DateTime $startDate, DateTime $endDate) : array
		{
			$this->connect();
			$sql = $this->mysqli->prepare("
				SELECT
					USER.*,
					STAFF_SHIFT.SHIFT_ID,
					STAFF_SHIFT.START_TIME,
					STAFF_SHIFT.END_TIME,
					STAFF_SHIFT.SHIFT_DATE
				FROM USER
				LEFT JOIN STAFF_SHIFT ON STAFF_SHIFT.USER_ID = USER.ID
				WHERE
					USER.USER_ROLE_ID IN (2, 3)
					AND (STAFF_SHIFT.START_TIME IS NULL OR STAFF_SHIFT.SHIFT_DATE BETWEEN DATE(?) AND DATE(?));");
			
			$sql->bind_param('ss', $startDateSql, $endDateSql);
			$startDateSql = $startDate->format('Y-m-d');
			$endDateSql = $endDate->format('Y-m-d');
			
			if ($sql->execute())
			{
				// Maps user id to user entity
				$users = [];
				
				// Maps user id to shifts. Shifts is an array that maps shift id to shift entity
				// [userId][shiftId] =
				$userShiftMap = [];
				
				$sql->bind_result($userId, $userRoleId, $emailAddress, $firstName, $lastName, $password, $address,
					$suburb, $state, $postcode, $country, $phone, $shiftId, $startTime, $endTime, $shiftDate);
				while ($sql->fetch())
				{
					$u = new UserEntity($userId, $userRoleId, $emailAddress, $firstName, $lastName, $password, $address,
						$suburb, $state, $postcode, $country, $phone);
					
					// Add user to user array if it does not exist
					if (!array_key_exists($userId, $users))
					{
						$users[$userId] = $u;
					}
					// Add found shift to map if it exists. Otherwise, init it
					if ($shiftId)
					{
						$userShiftMap[$userId][$shiftId] = new StaffShiftEntity($u, $shiftId,
							new DateTime($shiftDate.' '.$startTime),
							new DateTime($shiftDate.' '.$endTime));
					}
				}
				
				return
				[
					'users' => $users,
					'shifts' => $userShiftMap
				];
			}
			
			$this->disconnect();
			return [];
		}
		
		/**
		 * Selects every user role in the database
		 * @return array Array of all user roles
		 */
		public function selectAllUserRoles(): array
		{
			$this->connect();
			$sql = $this->mysqli->prepare("SELECT * FROM USER_ROLES");
			$userRoles = [];
			
			if ($sql->execute())
			{
				$sql->bind_result($id, $name);
				while ($sql->fetch())
				{
					$userRoles[$id] = $name;
				}
			}
			
			$this->disconnect();
			return $userRoles;
		}
		
		/**
		 * Finds a user from the database that has a specific email address
		 * @param $emailToFind
		 * @return UserEntity|null
		 */
		public function findUserWithEmailAddress($emailToFind) : UserEntity | null
		{
			$this->connect();
			$sql = $this->mysqli->prepare("SELECT * FROM USER WHERE USER.EMAIL_ADDRESS = ?");
			$sql->bind_param("s", $emailToFind);
			
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
		 * Finds a user from the database that has a specific id
		 * @param int $userId ID to find
		 * @return UserEntity|null Null if no user was found
		 */
		public function findUserWithId(int $userId): UserEntity | null
		{
			$this->connect();
			$sql = $this->mysqli->prepare("SELECT * FROM USER WHERE USER.ID = ?");
			$sql->bind_param("i", $userId);
			
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
			$sql->bind_param('i', $userRole);
			
			$records = [];
			if ($sql->execute())
			{
				$sql->bind_result($id, $userRoleId, $emailAddress, $firstName, $lastName, $password, $address, $suburb, $state, $postcode, $country, $phone);
				while ($sql->fetch())
				{
					$p = new UserEntity($id, $userRoleId, $emailAddress, $firstName, $lastName, $password, $address, $suburb, $state, $postcode, $country, $phone);
					$records[] = $p;
				}
			}
			
			$this->disconnect();
			return $records ?? null;
		}
		
		/**
		 * Updates a user in the database
		 * @throws Exception When user was not found in the database
		 * @return bool Whether the database query successfully updated the record
		 */
		public function updateUser(UserEntity $user) : bool
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
			
			$res = $sql->execute();
			$this->disconnect();
			return $res;
		}
		
		/**
		 * Deletes a user in the database
		 * @param int $accountId
		 * @return bool Whether the database query successfully updated the record
		 */
		public function deleteUser(int $accountId) : bool
		{
			$this->connect();
			$sql = $this->mysqli->prepare("DELETE FROM USER WHERE ID = ?");
			$sql->bind_param('i', $accountId);
			
			$res = $sql->execute();
			$this->disconnect();
			return $res;
		}
		
		/**
		 * Inserts a user into the database
		 * @throws Exception When an account already exists with a specified email address
		 * @return bool Whether the database query successfully updated the record
		 */
		public function insertUser(CreateAccountModel $newAcc) : bool
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
			
			$res = $sql->execute();
			$this->disconnect();
			return $res;
		}
		
	}