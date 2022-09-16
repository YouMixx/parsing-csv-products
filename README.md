## О проекте

Финальное задание международной цифровой олимпиады «Волга-IT`22». 

## Требования
<ul>
    <li>PHP версии 8.0</li>
    <li>Установленный Git</li>
    <li>Установленный Composer</li>
    <li>Локальный сервер для запуска проекта</li>
</ul>

## Установка
Зайти в папку для установки проекта

    cd namefoled
Клонировать репозиторий

    git clone https://github.com/YouMixx/parsing-csv-products.git .
Произвести установку зависимости пакетов

    composer install
Переименовать .env.example в .env
Проставить в нем актуальные данные от БД
Произвести миграции

    php artisan migrate

Сгенерировать ключ 

    php artisan key:generate
Запустить сервер

    php artisan serve

## Инструкия по использованию
Запуск скрипта осуществляется следующей командой:

    php artisan import:handler {filename} {chunk}

{filename} - название CSV файла, который необходимо загрузить в папку /storage/public/
{chunk} (необязательный параметр, по умолчанию 1000) - взависимости от лимита памяти на вашей устройстве, можно указать количество записей для одновременной вставки в БД (чем больше - тем быстрее будет выполняться скрипт, но больше съедать памяти).