#Проект "Filler"
**API сервер для игры Filler**

----
###Инструкция по запуску
>Для запуска необходимо иметь предустановленные docker и composer

Установить зависимости:  
```composer install```  
Сгенерировать ключ приложения:  
```php artisan key:generate```  
Сгенерировать секретный jwt ключ:  
```php artisan jwt:secret```  
Запустить сервисы docker:  
```./vendor/bin/sail up```  
Применить миграции:  
```./vendor/bin/sail artisan migrate```
Заполнить бд исходными данными:  
```./vendor/bin/sail artisan db:seed```
###*Дополнительно*
Обновить миграции:  
```./vendor/bin/sail artisan migrate:refresh```
Сгенерировать сваггер документацию:  
```./vendor/bin/sail artisan l5-swagger:generate```
Для просмотра сваггера можно перейти по адресу:  
http://localhost:80/api/documentation 
