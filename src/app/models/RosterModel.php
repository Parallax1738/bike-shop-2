<?php
	
	namespace bikeshop\app\models;
	
	use bikeshop\app\core\ApplicationState;
	use DateTime;
	
	class RosterModel extends ModelBase
	{
		public function __construct(private DateTime $start, private DateTime $end, private array $data, ApplicationState $state)
		{
			parent::__construct($state);
		}
		
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