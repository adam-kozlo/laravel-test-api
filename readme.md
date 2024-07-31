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




