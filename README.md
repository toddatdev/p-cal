<p align="center"><a href="https://laravel.com" target="_blank"><img src="http://13.51.201.32/assets/admin/logo.svg" width="400"></a></p>

## Project Details

- Laravel Version: ^9.0
- PHP Version: ^8.0

## Developer's Guide: Project Configuration

Follow the steps below to configure the project on your local environment:

- Clone the project repository from [GitLab](https://gitlab.com/ddnoman/dd-p-cal.git)


- Navigate to the project directory and run the following command to install the project dependencies:
  
  `composer install`


- Create a new **.env** file in the project root directory. You can use the **.env.example** file as reference.


- In the **.env** file, update the **DB_DATABASE** value to match the name of the database you created in your local PHPMyAdmin.


- Run the following command to migrate the database tables:
  
  `php artisan migrate`


- Seed the database with initial data by running the following command:

  `php artisan db:seed --class=TablesSeeder`


- Generate the autoload files by running the following command:

  `composer dump-autoload`


- Finally, start the local development server by running the following command:

  `php artisan serve`


The project is now configured and ready for use. You can access it by visiting the URL provided by the local development server.

Please make sure that your environment meets all the system requirements specified in the project details before starting the configuration process.
