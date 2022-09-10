### Сервис для рассылки уведомлений об истекающих подписках.

Сборка проекта
--------
```composer install```

Команды
--------

```php bin/console app:send-notifications-about-expiring-subscription```

Запуск тестов
--------
```php bin/phpunit tests```


Стек
--------
- Сервис написан на базе фреймворка ```Symfony 6.1```.
- В качестве БД используется ```sqllite```.

Функции
--------
- функции лежат по пути ```src/app.php```
- ```check_email``` - проверка email на валидность через внешний сервис
- ```send_email``` - отправка email 
- ```is_valid_email``` - проверка на валидность email

