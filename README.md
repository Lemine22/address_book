To run the app: 
- composer install 
- create data folder in var, then create contact.sqlite in it 
- run php bin/console doctrine:database:create 
- run php bin/console doctrine:schema:validate
- run php bin/console doctrine:schema:update --force
- run php bin/console server:run 
