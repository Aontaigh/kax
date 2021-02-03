# Weather App

## Setup Repository

```bash
# clone repo
$ git clone git://github.com/Aontaigh/kax

# initialise submodules
$ cd kax
$ git submodule update --init --recursive
```

## Environment Setup Docker

* Create .env in docker folder based on env-example
* Change: APP_CODE_PATH_HOST=../kax/
* Run environment:

```bash
# navigate to laradock directory
$ cd {folder-with-kax}/laradock

# start containers
$ docker-compose up -d nginx mysql
```

## Environment Setup Valet

* Install Valet:

```bash
# composer install valet
$ composer global require laravel/valet

# valet installation
$ valet install

# set valet to listen in the folder
$ cd {parent-folder-with-kax}
$ valet park
```

* Create a .env file from the .env.sample file
* Create a local database and connect to said database using your newly created .env file
* Connect to the various endpoints (i.e. http://kax.test/api/affiliates)

## Build Setup

```bash
# install dependencies
$ composer install

# run migrations and seeds
$ php artisan migrate:fresh --seed

# general optimizatiion before running
$ php artisan optimize
```

## Endpoints

* (GET)    {url}/affiliates : this endpoint will display all affiliates stored on the system (Query Parameters: per_page - int - the number of resources to display per page for pagination | order_by - string - the column to order by which could be, for example, ID | sort_by - string - asc or desc | search - string - the search string to find affiliates | search_fields - array - you can pass this array of the fields you wish to search within which could be ['id', 'name']).
* (POST)   {url}/affiliates : this endpoint will store a new affiliate in the system.
* (GET)    {url}/affiliates/{id} : this endpoint will display an affiliate based on the ID.
* (PUT)    {url}/affiliates/{id} : this endpoint will update an affiliate based on the ID.
* (DELETE) {url}/affiliates/{id} : this endpoint will delete an affiliate based on the ID.
* (GET)    {url}/affiliates/affiliates-dublin-proximity : this endpoint will display affiliates within a hundred kilometres of the office in Dublin (Query Parameters: max_distance).