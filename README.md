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

## Tailwind Setup 

You should only need to run these when you are modifying UI elements as output.css should have the most up-to-date CSS. Run shell script to install and to run the tailwind build process

```
./run-install.sh
```

To manually do the tailwind build process:

```
./tailwindcss -i ./input.css -o ./output.css --watch
```

Note: You can still run `run-install.sh` instead and it will ignore the install and just do the tailwind build process.

## MVC
In MVC, each there are Models, Views, and Controllers. 

When creating a new controller, you should call it 
'{name}Controller' (examples in /src/base/controller). Each controller should have multiple methods or 'actions'
which will be associated with a model and a view. To define a new controller, firstly create a new controller such as
`ExampleController`, make it implement `Controller`, and then go to `src/base/Router` and add a new element inside the 
`controllerMap` array. To create an index page that will be automatically navigated to when going to 
`example.com/controllerName`, you must implement the `IHasIndexPage` interface and override the `index(array $params)` 
function. 

To define an action, you must set up a .php file inside `/src/view/{controller}/{function/action}View.php`. This view
be called automatically when you call the function `$this->view({controller}, {action}, {your model / data});`, where 
your data can be anything. The data will be passed into the view, which will be described later.

An example controller implementation could look like:

```php
<?php

// Example Controller
class ExampleController extends Controller implements IHasIndexPage 
{
	// http://test.com/example
	function index(array $params) 
	{
		// todo - actually implement models and views into the framework
		return view("example", "index", "");
	} 
	
	// http://test.com/example/books
	function books(array $params) 
	{
		// create database access object
		$database = new DataAccess();
		
		$books = $database->selectAllBooks();
		return view("example", "books", $books);
	}
}

// Ensuring the router knows about this route's existence

// Router.php
		
		public function __construct()
		{
			// ...
			$this->controllerMap[ "example" ] = new ExampleController();
			// ...
		}
```

When creating an action, please ensure that it is a **unique** name. For example, If the user navigates to
`example.com/test`, the action `test()` will be run. But it will also accept `test2()`.
If you have a better solution, please change it at `src/base/Router` in the method `getActionFromStr()`.

Here is an example of a view you could use. This is following on the example for `/example/books`. Just
remember that when you call the `view()` function from inside your controller had the `$data` parameter which you should
be using in your view.

```php
<h1>Hello World!</h1>
<?php
    foreach ($data as $book) 
    {
        echo "<p>" . $book->name . "</p>";
    }
?>
```

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

Then, you can restart, and rebuild the docker container by running:

```
docker-compose up --build
```

## Before changing anything in the Docker container
If you make a mistake in the docker container, the best way to 'fix' it is to run either: `docker-compose down` or `docker-compose restart`.