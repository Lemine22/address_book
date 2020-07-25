To run the app after cloning the project: 
- composer install 
- create data folder in var, then create contact.sqlite in it 
- run php bin/console doctrine:database:create to create the database
- run php bin/console doctrine:schema:validate to validate the mappings
- run php bin/console doctrine:schema:update --force to create tables in the database
- run php bin/console server:run  to launch the server

