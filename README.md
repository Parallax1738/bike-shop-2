# bike-shop

A bike shop made with PHP, mySQL and Tailwind CSS.

## Requirements:

- [Docker](https://www.docker.com/)
- [Tailwind CLI](https://tailwindcss.com/blog/standalone-cli) (or use install script in project if you are using an Unix system)

## Installation/Setup

Compose docker container:

```
docker-compose build && docker-compose up
```

Run shell script to install and to run the tailwind build process

```
./run-install.sh
```

To manually do the tailwind build process:

```
./tailwindcss -i ./input.css -o ./output.css --watch
```

Note: You can still run `run-install.sh` instead and it will ignore the install and just do the tailwind build process.

## MVC
In MVC, each there are Models, Views, and Controllers. When creating a new controller, you should call it 
'{name}Controller' (examples in /src/base/controller). Each controller should have multiple methods or 'actions'
which will be asociated with a model and a view. To define a new controller, firstly create a new controller such as 
`ExampleController`, make it implement `Controller`, and then go to `src/base/Router` and add a new element inside the 
`controllerMap` array. To create an index page that will be automatically navigated to when going to 
`example.com/controllerName`, you must implement the `IHasIndexPage` interface and override the `index(array $params)` 
function. An example implementation colud look like:

```php
<?php

// Example Controller
class ExampleController extends Controller implements IHasIndexPage 
{
	// http://test.com/example
	function index(array $params) 
	{
		// todo - actually implement models and views into the framework
		echo '<h1>Example</h1>';
	} 
	
	// http://test.com/example/books
	function books(array $params) 
	{
		// holy shit there is more to this project than I thought
		// create database access object
		$database = new DataAccess();
		
		$books = $database->selectAllBooks();
		foreach ($books as $b) 
		{
			echo '<p>' . $books->name . '</p>';
		}
	}
}

// Ensuring the router knows about this route's existence

// Router.php
		
		public function __construct()
		{
			// ...
			$this->controllerMap[ "example" ] = new ExapmleController();
			// ...
		}
```

When creating an action, please ensure that it is a **unique** name. For exmaple, If the user navigates to
`example.com/test`, the action `test()` will be run. But it will also accept `test2()` because I am bad at programming. 
If you have a better solution, please change it at `src/base/Rotuer` in the method `getActionFromStr()`. 

Also, while the method is being called, all of the parameters from the URL will be directly parsed as an array and passed 
into the method which may cause some issues (when I implement it eventually).

## Updating init.sql script
init.sql is what runs when the mysql docker container is created. If you change the container, you must run:

```
docker-compose down
docker volume ls
```

You should see something along the lines of `bike-shop_dbData`. Then, run

```
docker volume rm bike-shop_dbData # or whatever you found in the command
```

Then, you can restart the docker container by running:

```
docker-compose up
```

## Before changing anything in the Docker container
If you fuck something up in the docker container, the best way to 'fix' it is to run either: `docker-compose down` or `docker-compose restart`. It could save your life someday