This will be a POS system based on *[Material Design](https://material.io/)* with the *[Materialize](https://materializecss.com/)* framework.

## Vorraussetzungen:
  * A Webserver with **MySQL** and **PHP5+**

## Setup:
  * Execute the SQL code from the file `inc/database.sql` in MySQL; create your own DB *user* if necessary.
  * In the `inc` folder, copy the `config.example.php` file to `config.inc.php` and fill it in with the connection details of the desired MPOS database.
  * A default Admin user is available (commented out) in the `inc/database.sql` file with *user* as `admin@example.com` and *password* as `password`
  * Register a new user at `register.php`
