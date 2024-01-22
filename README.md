I foun it and it is work!

                Настраиваем контейнеры Docker, Docker-compose (PHP + MySQL + Nginx  + phpMyAdmin)             
Настроим инструменты разработчика PHP через Docker.
Давным-давно в далекой-далекой галактике я пользовался проектом Denwer, удобная сборка (PHP, MySQL, PhpMyAdmin, Apache)

Теперь я пользуюсь Docker и Docker-compose в частности.
Docker технология для создания и управления контейнерами.
- оборачиваем приложение в контейнеры для того, чтобы Docker обеспечивал одинаковое поведение в разных окружениях.
- можно брать Docker контейнеры и запускать их где угодно, где есть Docker. Все поведение будет зафиксировано в контейнере.
- можем сами решать, какую версию приложения использовать внутри контейнера.
- все приложения стоят внутри контейнеров и не засоряют компьютер.

Скачать и установим Docker Desktop (https://www.docker.com/products/docker-desktop/)
github с моей конфигурацией Docker-compose (PHP, MySQL, PhpMyAdmin, Nginx) (https://github.com/komissarovev/docker_php)

Скачиваем проект, запускаем консоль CMD и заходим в папку с проектом.
Запускаем команду
docker-compose build (создаст контейнеры для запуска)
docker-compose up (запустит контейнеры)
Эти команды можно объединить docker-compose up --build
Если добавить флаг -d, то консоль не заблокируется docker-compose up --build -d
Если консоль заблокирована процессом, то процесс можно остановить нажав Ctrl+C
Команда, чтобы остановить процесс выполнения контейнеров docker-compose down
Запустим контейнеры

![изображение](https://github.com/Filin15488/Docker-compose-PHP-MySQL-Nginx-phpMyAdmin-/assets/92125747/194abda3-a35a-412a-b8d4-94faef344a2e)

Получаем вывод статики php на 80-й порт, которая лежит в проекте по пути ./src/public/. Сейчас там один файл index.php и phpMyAdmin на 8000-й порт.

![изображение](https://github.com/Filin15488/Docker-compose-PHP-MySQL-Nginx-phpMyAdmin-/assets/92125747/c6c6a72c-9541-42df-9bf0-60e9106dd16b)

![изображение](https://github.com/Filin15488/Docker-compose-PHP-MySQL-Nginx-phpMyAdmin-/assets/92125747/a11a22cc-d68d-4a92-a1b1-4916424733bb)

Далее надо настроить базу и пользователя MySQL
В файле docker-compose.yml мы задавали пароль root для MySQL
volume (- mysql_php:/mysql.sql) мы подключаем файл с базой в контейнер, чтобы наша база не разрушалась каждый раз когда мы делаем docker-compose build

![изображение](https://github.com/Filin15488/Docker-compose-PHP-MySQL-Nginx-phpMyAdmin-/assets/92125747/9d79bf1b-7d5d-4a9b-a0d1-1b603f8c75f0)

Можно создать базу зайдя в phpMyAdmin под пользователем root, но тогда придется из php тоже подключаться к базе из под root, что не очень хорошо.
Поэтому пойдем другим путем.
В CMD введем команду подключения к запущенному контейнеру docker exec -it tools_php-mysql sh
tools_php-mysql - это имя контейнера, его можно посмотреть в docker-compose.yml -> container_name или ввести команду docker ps - покажет запущенные процессы

![изображение](https://github.com/Filin15488/Docker-compose-PHP-MySQL-Nginx-phpMyAdmin-/assets/92125747/2df8f5f2-6029-4b81-bc86-0eee87192020)

Подключимся к контейнеру MySQL
docker exec -it tools_php-mysql sh
Для подключения к самой MySQL внутри контейнера наберем команду
mysql -uroot -p
Пароль задан в файле docker-compose.yml

![изображение](https://github.com/Filin15488/Docker-compose-PHP-MySQL-Nginx-phpMyAdmin-/assets/92125747/28d168bf-d6a4-499d-ad92-a140265acec5)

Создадим базу данных CREATE DATABASE `test`;
Посмотрим на все базы данных SHOW DATABASES;

![изображение](https://github.com/Filin15488/Docker-compose-PHP-MySQL-Nginx-phpMyAdmin-/assets/92125747/e7b43d83-3a64-4178-8b28-17235268be59)

Создадим пользователя CREATE USER 'user1'@'%' IDENTIFIED BY 's123';
% обозначает что пользователь может подключаться из под любого хоста - это важно т.к. разные контейнеры расположены на разных хостах(ip разные)
Добавим пользователю все права на базу созданную ранее
GRANT ALL PRIVILEGES ON `test` . * TO 'user1'@'%';

![изображение](https://github.com/Filin15488/Docker-compose-PHP-MySQL-Nginx-phpMyAdmin-/assets/92125747/bc9c368f-e468-4a2e-99d9-93f1be8c7b5b)

Для того чтобы удалить пользователя нужно будет сначала убрать права, а потом удалить.
REVOKE ALL PRIVILEGES, GRANT OPTION FROM 'user1'@'%';
DROP USER 'user1'@'%';
Теперь можно подключиться новым пользователем к базе через phpMyAdmin.
Имя сервера mysql указано в файле docker-compose.yml.
Дальнейшую настройку базы удобно производить в phpMyAdmin

![изображение](https://github.com/Filin15488/Docker-compose-PHP-MySQL-Nginx-phpMyAdmin-/assets/92125747/d1473cf3-4c47-4b54-81bc-f69a0748d373)

![изображение](https://github.com/Filin15488/Docker-compose-PHP-MySQL-Nginx-phpMyAdmin-/assets/92125747/974ebdb1-a782-4c9f-9f23-08708edd2495)

Посмотрим настройки docker-compose.yml контейнера phpMyAdmin

![изображение](https://github.com/Filin15488/Docker-compose-PHP-MySQL-Nginx-phpMyAdmin-/assets/92125747/4bf25051-cf7f-4937-81aa-983ef507e233)

Переменная среды -PMA_ARBITRARY=1 обозначает, что можно подключаться к любому серверу сети, а не только localhost.
-UPLOAD_LIMIT=1024M
-MEMORY_LIMIT=1024M
-MAX_EXECUTION_TIME=300
Этими переменными задается максимальный размер бекапа, который можно загрузить через phpMyAdmin (по умолчанию он очень маленький)
phpMyAdmin запускается в отдельном контейнере с Apache2 на 80 порту, но у нас 80 порт уже занят контейнером с веб-сервером Nginx, который на 80 порту публикует статику PHP.
Поэтому мы перебрасываем 80 порт на 8000 (ports: - 8000:80)

![изображение](https://github.com/Filin15488/Docker-compose-PHP-MySQL-Nginx-phpMyAdmin-/assets/92125747/06922ee1-f0a1-4423-b643-200b73484890)

В Nginx в первом volume (- ./src:/var/www) подключаем статику сайта с компьютера в контейнер для публикации
Во втором volume (- ./nginx:/etc/nginx/conf.d) настройки самого Nginx для работы с php

![изображение](https://github.com/Filin15488/Docker-compose-PHP-MySQL-Nginx-phpMyAdmin-/assets/92125747/79c0b339-c29b-4fcb-aef2-f0b6b1962b98)

fastcgi_pass php:9000; - этой строкой Nginx понимает, где расположен сервер с PHP.
Контейнер с PHP по умолчанию расположен на 9000 порту, php - это имя сервиса, которое мы задали в файле docker-compose.yml.

![изображение](https://github.com/Filin15488/Docker-compose-PHP-MySQL-Nginx-phpMyAdmin-/assets/92125747/4232ea7c-84de-445e-8cb2-6941bbe1080e)

volume подключаем к контейнеру статику (- ./src:/var/www)
Переменными среды передаем в контейнер настройки MySQL для подключения к базе.
- MYSQL_HOST=mysql 
- MYSQL_DATABASE=test_db 
- MYSQL_USER=user1
- MYSQL_PASSWORD=s123
В PHP потом их можно взять из глобального массива $_ENV

![изображение](https://github.com/Filin15488/Docker-compose-PHP-MySQL-Nginx-phpMyAdmin-/assets/92125747/cb7c9883-f11a-45c2-b6e0-2b7e2841fc38)

<?
$connect = mysqli_connect($_ENV["MYSQL_HOST"],$_ENV["MYSQL_USER"],$_ENV["MYSQL_PASSWORD"],$_ENV["MYSQL_DATABASE"]);
if (mysqli_connect_errno()) {
    printf("error: %s\n", mysqli_connect_error());
    exit();
}
mysqli_query($connect, "SET NAMES utf8");
?>


Еще хочу обратить внимание в файле docker-compose.yml мы подключаем images, из которых будет создан контейнер. Тут можно выбрать версию приложения. Для важных вещей есть смысл указывать точную версию, ну а например phpmyadmin можно брать всегда последнюю версию
