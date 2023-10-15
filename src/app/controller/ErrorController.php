<?php
	
	namespace bikeshop\app\controller;
	
	use bikeshop\app\core\ActionResult;
	use bikeshop\app\core\Controller;
	
	class ErrorController extends Controller
	{
		public function http404()
		{
			$this->view(new ActionResult('error', 'http404'));
		}
		
		public function http400()
		{
			$this->view(new ActionResult('error', 'http400'));
		}
		
		public function http401()
		{
			$this->view(new ActionResult('error', 'http401'));
		}
		
		public function http403()
		{
			$this->view(new ActionResult('error', 'http403'));
		}
		
		public function http405()
		{
			$this->view(new ActionResult('error', 'http405'));
		}
	}