<?php
	
	class HomeController extends Controller implements IHasIndexPage
	{
        public function index(array $params) : void
		{
//            echo "<form method='post' action='./'>
//                <input name='test' value='4' />
//                <input style='background-color: darkgrey;' type='submit' value='Submit' />
//            </form>";
            $this->view('home', 'index', 10);
		}
		
		public function test() : void
		{
			echo "<h1>Test</h1>";
		}
	}