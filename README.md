# Weather App

## Environment Setup

Create a .env file from the .env.sample file
Create a local database and connect to said database using your newly created .env file

## Build Setup

```bash
# install dependencies
$ composer install

# run migrations and seeds
$ php artisan migrate:fresh --seed

# general optimizatiion before running
$ php artisan optimize
```