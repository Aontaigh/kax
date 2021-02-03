# Weather App

### Setup Repository

```bash
# clone the repository
$ git clone git://github.com/Aontaigh/kax

# initialise submodules
$ cd {folder-with-kax}
$ git submodule update --init --recursive
```

## Environment Setup

### Docker:

* Create an .env in docker folder based on env-example
* Change the following on line eight: `APP_CODE_PATH_HOST=../kax/`
* Run the docker environment:

```bash
# navigate to directory
$ cd {folder-with-kax}
$ cd laradock

# start containers
$ docker-compose up -d nginx mysql
```

### Valet:

* Install Valet:

```bash
# composer install valet
$ composer global require laravel/valet

# valet installation
$ valet install

# park valet in the directory - once the directory has been "parked" with valet,
# all of the directories within that directory will be accessible in your web browser 
# at http://<directory-name>.test:
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

* (GET) {url}/affiliates : this endpoint will display all affiliates stored on the system.
 * Query Parameters: 
   * (INT)(DEFAULT: 30) per_page : the number of resources to display per page for pagination.
   * (STRING)(DEFAULT: id) order_by : the column to order by which could be, for example, ID.
   * (STRING)(DEFAULT: asc) sort_by : 'asc' or 'desc'.
   * (STRING) search : to filter the affiliaties by your desired search query.
   * (ARRAY)(DEFAULT: ['affiliate_id', 'latitude', 'longitude']) search_fields : an array containing the fields to search within when the query parameter 'search' is passed. This, for example, could be ['id'].
* (POST) {url}/affiliates/file : this endpoint will display store a new affiliate on the system.
 * Parameters: 
   * (FILE) file : the file containing the affiliaties, a sample file for this can be found in the project and is called affiliates.txt.
* (GET)    {url}/affiliates/{id} : this endpoint will display an affiliate based on the ID.
* (PUT)    {url}/affiliates/{id} : this endpoint will update an affiliate based on the ID.
 * Parameters: 
   * (INT) affiliate_id
   * (STRING) latitude
   * (STRING) longitude
* (DELETE) {url}/affiliates/{id} : this endpoint will delete an affiliate based on the ID.
* (GET) {url}/affiliates-dublin-proximity : this endpoint will display all affiliates stored on the system within a specified proximity to the office in Dublin.
 * Query Parameters: 
   * (STRING)(DEFAULT: 100) max_distance : the max distance in kilometres the affiliate can be from the office in Dublin.