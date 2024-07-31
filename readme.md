Example of simple laravel REST API based on Docker.

Technical Requirements:
- Docker
- Laravel 11
- MySQL
- Unit tests

Task Requirements:
As a user (doctor), I want to be able to:

- Add documents for a patient whose treatment I am managing
- The document storage operation should be asynchronous
- The document being added must be a PDF file no larger than a specified number of MB. By default, this should be 5MB, with the ability to configure it via an environment variable
- The user (doctor) can manage documents only for their own patients

Notes:
There is no need to implement login and registration using bearer tokens. For the purposes of this task, use basic auth with the user's email and password. Only the implementation of patient document management is required. Sample records of patients and doctors should be provided in the submitted solution.


HOW TO FIRE PROJECT LOCALLY:
- clone repository from github
- Use docker compose to set up project:
docker-compose -f docker-compose.local.yml up -d --build
- Download dependencies:
docker-compose -f docker-compose.local.yml run --rm composer install
- Set up and seed database:
docker-compose -f docker-compose.local.yml exec php php /var/www/html/artisan migrate:fresh --seed
- API works under 8010 port, database 4420 port - if you need to change it use docker-compose.local.yml
- You can log in to api as (using Basic Auth)
user:test_user1@example.com 
pass:lorem25# 
or 
user:test_user2@example.com
pass:lorem26# 
- All API endpoints and examples can be found in enclosed to project Postman file. 
- In .env file you can change UPLOAD_MAX_FILE_SIZE_KB, clear cache after that:
docker-compose -f docker-compose.local.yml exec php php /var/www/html/artisan config:cache
- Run unit tests:
docker-compose -f docker-compose.local.yml exec php php /var/www/html/artisan test


API DESCRIPTION:
- GET http://localhost:8010/api/v1/health - gives information that api works
- GET http://localhost:8010/api/v1/patients - reads patients of authorized doctor
- POST http://localhost:8000/api/v1/patients/1/document - allows to upload document for patient with id 1 in async way. Table async_actions keeps the status of uploading.
After upload the status of process is in_progress. Then start the queueing (the command is below) - status changes to success, and the file is in storage/app/public/uploads
- GET http://localhost:8010/api/v1/async-action-status/1 - allows to check status of async action with ID 1 (action of uploading document)

MORE:
- Starting queueing:
docker-compose -f docker-compose.local.yml exec php php /var/www/html/artisan queue:listen
- Going inside php container:
docker-compose -f docker-compose.local.yml exec php /bin/sh
- Using artisan:
docker-compose -f docker-compose.local.yml exec php php /var/www/html/artisan --version


In case of problems - mail to me:
adam@coolcode.pl




