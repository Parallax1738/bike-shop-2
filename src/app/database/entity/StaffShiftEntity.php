<?php
	
	namespace bikeshop\app\database\entity;
	
	use DateTime;
	
	class StaffShiftEntity
	{
		public function __construct
		(
			private readonly UserEntity $user,
			private readonly int        $shiftId,
			private readonly DateTime   $startTime,
			private readonly DateTime   $endTime,
		) { }
		
		public function getUser() : UserEntity
		{
			return $this->user;
		}
		
		public function getShiftId() : int
		{
			return $this->shiftId;
		}
		
		public function getStartTime() : DateTime
		{
			return $this->startTime;
		}
		
		public function getEndTime() : DateTime
		{
			return $this->endTime;
		}
	}