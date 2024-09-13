Клонирование репозитория
git clone https://github.com/George-Karpenko/test-megahunt.git
Перейдите в корневой каталог.

$ cd test-megahunt
Копировать файл .env.example и переименовать в .env.

Запуск контейнеров
Запустите контейнеры с помощью docker-compose

$ docker-compose up -d

Так же нужно сделать генерацию ключа в laravel, миграции и запустить сиды. Для этого нужно выполнить команды

$ ./vendor/bin/sail artisan artisan key:generate
$ ./vendor/bin/sail artisan artisan migrate
$ ./vendor/bin/sail artisan artisan db:seed
