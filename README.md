## Coalition Technologies Test
* PHP >=7.2
* MySQL
## Based ON
* Laravel
## Setting Up The Project
* Run `composer install`
* Configure ENV and Databases
    * Run `cp .env.example .env`
    * Run `php artisan key:generate` to generate APP_KEY
    * Make sure you set `APP_URL` to match the exact URL of the app.
    * Configure DB details inside `.env`
   
*  Run `php artisan migrate` - This will: 
    * Migrate DB
    
*  Run `php artisan db:seed`(optional) - This will: 
    * Seed the database with some projects.
    
*   Run `php artisan serve` - This will: 
    * Boot up the server, and the local url will be shown in the terminal
   

## Running Tests
`./vendor/bin/phpunit`

## Encounter an Issue?

* Contact me via ishukpong418@gmail.com ❤️!

