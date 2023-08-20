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

## Before changing anything in the Docker container
If you fuck something up in the docker container, the best way to 'fix' it is to run either: `docker-compose down` or `docker-compose restart`. It could save your life someday