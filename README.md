**Для запуска проекта должен быть установлен docker и docker-compose**

**Клонируем проект**

`git clone https://github.com/NikitaPilez/xpartners.git`

**Переходим в директорию**

`cd xpartners`

**Билд докера**

`docker-compose build`

**Поднимаем сервисы**

`docker-compose up -d` (либо можно использовать без флага -d, тогда в консоли можно будет увидеть логи, а последующие команды выполнять в отдельной консоли)

**Устанавливаем зависимости**

`docker exec -it xpartners_php composer i`

**Накатываем миграции**

`docker exec -it xpartners_php php bin/console doctrine:migrations:migrate`

**Проект можно потестировать с помощью curl или через insomnia/postman, документация:**
[http://localhost:8080/doc/](http://localhost:8080/doc/)

**Для авторизации следует добавить заголовок AUTH-TOKEN значение 12345678.**

**Для того чтоб остановить работу с контейнерами следует выполнить**

`docker-compose down`

**Тестирование**

**Скопировать файл .env.test в .env.test.local и последней строкой добавить**
`DATABASE_URL="postgresql://admin:root@postgres/xpartners?serverVersion=16&charset=utf8"`

**Создаем базу для тестов**
`docker exec -it xpartners_php php bin/console --env=test doctrine:database:create`

**Накатываем миграции**
`docker exec -it xpartners_php php bin/console --env=test doctrine:schema:create`

**Фикстуры накатываем**
`docker exec -it xpartners_php php bin/console --env=test doctrine:fixtures:load`

**Запустить тесты**
`docker exec -it xpartners_php php bin/phpunit`