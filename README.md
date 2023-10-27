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

You should only need to run these when you are modifying UI elements as output.css should have the most up-to-date CSS.
Run shell script to install and to run the tailwind build process

```
./run-install.sh
```

To manually do the tailwind build process:

```
./tailwindcss -i ./input.css -o ./output.css --watch
```

Note: You can still run `run-install.sh` instead, and it will ignore the installation and just do the tailwind build
process.

## MVC

### Controllers and Actions

In this framework, routes are mostly automated, and are formatted as such: `{controller}/{action}`. You must firstly 
create a class that extends `Controller`. A controller simply managers all of the actions that relate to a subject. 
For example, if you wanted to perform CRUD operations on a `BOOK` table inside your db, you'd create a `BookController` 
class. Here are some example routes you may want to implement for something like like:

| Action | Route           |
|--------|-----------------|
| index  | `/books`        |
| create | `/books/create` |
| delete | `/books/delete` |
| edit   | `/books/edit`   |

For this framework, to create an index page, must extend `IHasIndexPage` on your controller, and create an action. An
action is just a function which adds the `RouteAttribute` attribute, defining a method and a route. Here is an example
implementation:

```php
<?php
	class BooksController extends Controller implements IHasIndexPage
	{
		// /books/
		#[RouteAttribute(HttpMethod::GET, "")]
		public function indexGet() : void { /* ... */ }
		
		// /books/create
		// The HTML form
		#[RouteAttribute(HttpMethod::GET, "create")]
		public function createGet() : void { /* ... */ }
		
		// /books/create
		// Actually inserting something into database
		#[RouteAttribute(HttpMethod::POST, "create")
		public function createPost() : void { /* ... */ }
	}

```

### Database

While this may look fine, you still need to implement a model and view. We could use a `Book` class as a model that we
pass into our views. Maybe in the index page we grab all the books from the database and display it in at able, and
to do that we must firstly create a repository object which extends `DatabaseConnector`.

```php
<?php
	// Easier to manage if fields are readonly
	class BookEntity
	{
		public function __construct(
			private int $id, 
			private string $name,
			private string $description
		) {
		
		}
		
		public function getId() : int
		{
			return $this->id;
		}
		
		public function getName() : string
		{
			return $this->name;
		}
		
		public function getDescription() : string
		{
			return $this->description;
		}
	}
	
	class BookRepository extends DatabaseConnector
	{
		/**
 		 * Returns all books from the database 
		 * @return array|null Array of BookEntities. Null if error
 	 	 */
		public function getAllBooks() : array | null
		{
			$this->connect();
			$stmt = $this->mysqli->prepare('SELECT * FROM BOOKS');
			if ($stmt->execute())
			{
				$books = [];
				$stmt->bind_result($bookId, $bookName, $bookDescription);
				if ($stmt->execute())
				{
					$books[] = new BookEntity($bookId, $bookName, $bookDescription);
				}
				// Ensuring to disconnect from the database after each query
				$this->disconnect();
				return $books;
			}
			return null;
		}
	}
```

### Views

Now that we have the data we want to pass into the view, we must now call to the view and display it to the user! 
Accomplishing this is simple, but not elegant. Inside the `indexGet()` method, you must create a `BookRepository` 
object, get all the books, then as stated before, call to the view.

To call the view, you must call `$this->view` and pass in an ActionResult object with the action name and data as params

```php

// BooksController.php ...

#[RouteAttribute(HttpMethod::GET, "")]
public function indexGet() : void 
{ 
	$repo = new BookRepository();
	$books = $repo->getAllBooks();
	
	$this->view(new ActionResult('index', $books));
}
		
// ...

```

Create the file `/src/public/{controller}/{action}.php` as your view. This will just be plain HTML and PHP, so all
you'll need to do is check if the `$data` variable exists as seen in the example:

```php

<h1>Books</h1>
<?php
	// Make sure data object exists, and ensure it is an array and not null
	if (!isset($data) || !($data instanceof ArrayObject))
	{
		die ("Data not set");
	}

	foreach ($data as $b)
	{
		// Only print books
		if (!($b instanceof BookEntity))
			continue;
			
		echo '<br><hr><br><p> '.$b->getName().' | '.$b->getDescription().'</p>'; 
	}
?>

```

## Updating init.sql script

init.sql is what runs when the mysql docker container is created. If you change the container, you must run:

```shell
docker-compose down
docker volume ls
```

You should see something along the lines of `bike-shop_dbData`. Then, run

```shell
docker volume rm bike-shop_dbData # or whatever you found in the command
```

Then, you can restart, and rebuild the docker container by running:

```shell
docker-compose up --build
```

For simplicity, I have left a script called `docker-setup.sh` which you can run if you'd like to automatically do this.

## Environment Variables

There are some environment variables which you can set if you so desire. Here are all the currently available vars you
can change inside `docker-compose.yml`:

- __DEFAULT_SEARCH_RESULT_COUNT = 10