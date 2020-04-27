# Verkeersinformatie API
This is an API to fetch traffic information for all "A" and "N" roads in the Netherlands.

The API has the following features:
* Information about traffic jams
* Information about roadworks
* Information about speed cameras
* Historical data

## API documentation
The documentation is in OpenAPI format. Copy the contents of [openapi.json](openapi.json) in the root of the project to an editor e.g. 
[editor.swagger.io](https://editor.swagger.io).

## Getting started
This project ships with a docker-compose file. 
If you don't have docker installed, [install it first](https://docs.docker.com/install/).

* Open a command window at the root of the project
* Run `docker-compose up`

On first run, all containers will be downloaded and initialized before the application is ready.

* You will receive an error from composer that you are missing the ext-mysql extension, don't panic.
* Run `docker-compose run composer install --ignore-platform-reqs`


* Open your favorite mysql editor
* Create a connection to localhost:3306 (the password for `root` is `dev123`)
* Create a new schema with the name `traffic_information`
* Run `docker-compose run php /app/bin/console doctrine:migrations:migrate`

Your API is ready!

### Importing data
Your API does not have data yet. You can run a command that fetches the data from the ANWB.

* Run `docker-compose run php /app/bin/console app:fetch-anwb-traffic-information`

You can run this as much as you'd like.

#### Scheduling fetching of the traffic information
You can use Crontab on Linux or Scheduled tasks on Windows to have the command above run at given moments to keep your database up to date.
