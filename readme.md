# API PORTFOLIO
## Welcome :) :) :)

The general idea of this project is to build a Developer Portfolio where you can find a test API section to test an API that I have built, thereby showcase my back end skills. In this section, you will be able to test multiple functionalities like: authentification, multiple images upload, search catalog, user profile, etc...

This is the API part. 

The front end part is developed with Gatsby (React), currently deployed on Netlify. Front end part project's link : https://github.com/anaisalfonsi/my-portfolio


### MAIN TECHNOLOGIES
#### - MySQL 5.7
#### - PHP 8.1
#### - Symfony 6.1
#### - API Platform 3.0


### NOTE CORRECTEURS
Ce projet étant mon portfolio, je continuerai à développer pendant la période d'évaluation mais sur d'autres branches que main.

### FRONT END PART LINK
https://github.com/anaisalfonsi/my-portfolio


### HOW TO INSTALL IT
#### Commands to run:

- git clone "project link"

- gco -b "your new branch Name"

- composer install

(create .env.local file from .env file and define your database)

- php bin/console doctrine:database:create

- php bin/console make:migration (!!! Before making a new migration, make sure the migrations file is empty. Otherwise, delete all the migrations inside the folder.)

- php bin/console doctrine:migrations:migrate

- php bin/console doctrine:fixtures:load --no-interaction

- symfony server:start -d (so your server is running in the background, and you don't have to use an additional terminal)

=> Go to http://localhost:8000 in your browser.

### IMPORTANT NOTES
!!! You have to run your API server on http://localhost:8000 and the front-end server on http://localhost:8001

!!! Create folders "images" and "tarot_images" inside public/uploads for image uploads

!!! You will need to uncomment some code in datafixtures if you want all the data

