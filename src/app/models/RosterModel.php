<?php
	
	namespace bikeshop\app\models;
	
	use DateTime;
	
	class RosterModel
	{
		public function __construct
		(
			private DateTime $start,
			private DateTime $end,
			private array $data
		) { }
		
		public function getStart() : DateTime
		{
			return $this->start;
		}
		
		public function getEnd() : DateTime
		{
			return $this->end;
		}
		
		public function getData() : array
		{
			return $this->data;
		}
	}