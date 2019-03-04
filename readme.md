# Test application
Projects aim was to create a small application without using any frameworks.

## set up
```
composer install
cp config.example.php config.php
cp config.php tests/config.php
```
Change db_name, db_user and db_password in config.php and tests/config.php to suit your project
After database credentials have been set run:
```
composer db-init
```
This will create schema and seed some number of example tests

## testing
To run tests, you can use composer script:
```
composer tests
```

To run a local webserver, you can use script:
```
composer serve
```