**Развертывание проекта**  
```
docker-compose up -d
docker run --rm -ti -v $pwd:/app composer composer install --ignore-platform-reqs --no-scripts
```
Сервис доступен на ``localhost:80``  

**Запуск тестов**  
```
docker exec -ti php ./vendor/bin/phpunit tests
```