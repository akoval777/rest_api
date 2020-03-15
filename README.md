# REST API для сервиса хранения данных о магазинах

*Установка и настройка:*

1. Устанавливаем зависимости с помощью команды `composer install`

2. В файле .env.local указываем настройку подключения к БД:
   <pre>DATABASE_URL=mysql://root@127.0.0.1:3306/api?serverVersion=5.7</pre>

3. Создаем БД: `php bin/console doctrine:database:create`

4. Выполняем миграции: `php bin/console doctrine:migrations:migrate`

5. Добавляем тестовые данные (список магазинов) `php bin/console doctrine:fixtures:load`

*Использование:*

| Действие   | Метод   | Путь | Данные |
| :--------: | :-----: | :----: | :---: |
| Извлечение списка магазинов | GET | /shops?page=1&limit=5 | |
| Получение информации по магазину | GET | /shop/{id} | |
| Создание отзыва | POST | /review | ```javascript { "shop_id": 1, "text": "Good shop", "author": "Ivanov", "file": "/folder/image.png" } ```|
| Получение отзыва | GET | /review/{id} | |
| Получение списка отзывов | GET | /reviews | |
| Редактирование отзыва | PUT | /review/{id} | ```javascript {"author": "TEST"} ``` |
| Удаление отзыва | DELETE | /review/{id} | |