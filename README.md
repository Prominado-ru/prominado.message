# prominado.message

Модуль для вывода информационного сообщения на сайте.

Для вывода сообщения, добавьте в необходимом месте код компонента:

```php
<?$APPLICATION->IncludeComponent('promiando.message.show', '', array(
    'SITE_ID' => 's1' // Опционально, по умолчанию выведется сообщения для текущего сайта 
));

```

Управление сообщениями производится в админке по адресу:
```
http://site.ru/bitrix/admin/settings.php?mid=prominado.message&lang=ru
```