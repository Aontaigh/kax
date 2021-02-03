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
$ cd laradock

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

# general optimizatiion before running
$ cd {folder-with-repo}
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