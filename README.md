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
