# bike-shop

A bike shop made with PHP, mySQL and Tailwind CSS.

## Requirements:

- [Docker](https://www.docker.com/)
- [Tailwind CLI](https://tailwindcss.com/blog/standalone-cli) (or use install script in project if you are using an Unix system)

## Installation/Setup

### Setting up Docker container

Compose docker container:

```
docker-compose build && docker-compose up
```

### Installing Composer Packages

We need to have a package installed for the assessment (sorry). To install a package, firstly you must define the
package in `composer.json` under the `require` section (you can see an example in there already)

Then, find the docker container using `docker ps` (assuming it is already running). Then, using the docker container id,
run `docker exec -it {container id} composer install && composer update` to properly install the packages

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

### Controller 

The way this project implements MVC is not very good. Firstly, you should create a controller inside of 
`/src/app/controller`. Each controller should be a class that extends `Controller`, an example definition could be `class
TestController extends Controller`. Then, you should tell the Router about it inside `/src/app/core/Router.php`, and add 
the line `$this->controllerMap[ "test" ] = new TestController();`. 

With that line, when you navigate to `/test` in the browser, you will end up in that controller. If you implement the
`IHasIndexPage`, it will run the view. Otherwise, it will throw an error saying that the view doesn't exist (because we
haven't implemented it yet).

### Actions

Each method inisde your code will act as a viewmodel. It will gather all the data it needs, and provide it to the 
view. Ensure that all your methods pass `ApplicationState $state`, and if you want authentication in the page you are 
adding, ensure to pass `new ModelBase($state)` into the $view() function. Here is an example implementation:

### Example Controller/Action implementation

```php
use bikeshop\app\core\ApplicationState;class BooksPageModel extends ModelBase
{
	public function __construct(private array $books, ApplicationState $state)
	{
		parent::__construct($state);
	}
	
	public function getBooks(): array
	{
		return $this->books;
	}
}

class TestController extends Controller implements IHasIndexPage
{
	// /test/
	public function index(ApplicationState $state)
	{
		$database = new DatabaseConnector();
		$books = $database->selectAllBooks();
		
		$booksModel = new BooksPageModel($books, $state); 
		
		//  First Param: controller name, which in this case is Test (the class name without 'controller')
		// Second Param: action name, in this case it's index (the function name)
		//  Third Param: The model to pass into the view. Can be null if nothing is needed
		$this->view('test', 'index', $booksModel);
	}
	
	// /test/action
	public function action(ApplicationState $state)
	{
		$this->view('test', 'action');
	}
}
```

### Views

Views are even more obnoxious than the controller implementation. Calling `$this->view(...)` such as in the previous 
code example will find the `php` file inside `/src/public/{controller}/{action}.php`. For example, if you call `$this->
view('test', 'index');`, The file that will be loaded is `/src/public/test/index.php`. If the file doesn't exist, an 
error will be thrown. 

Passing data is ~~really easy~~, if you set the `$data` param in the `$this->view(...)` function, that will be visible 
to the view. The view is not a class. An example could be:

```php
// /src/public/test/index.php

<?php>
if (!isset($data) || !($data instanceof BooksPageModel))
{
	echo "Could not find page model";
	die;
}

foreach ($data->getBooks() as $book)
{
	// Lists all books from the data object
	echo "<p>" . $book->getName() . "</p>";
}

</?>
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
If you fuck something up in the docker container, the best way to 'fix' it is to run either: `docker-compose down` or `docker-compose restart`. It could save your life someday

## Environment Variables
There are some environment variables which you can set if you so desire. Here are all the currently available vars you 
can change inside `docker-compose.yml`:

- __DEFAULT_SEARCH_RESULT_COUNT = 10