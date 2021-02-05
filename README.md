Это Single Page Application, для работы с заказами и клиентами. Полностью на Vue.js через ajax.  
После добавления клиента, клиент появляется в таблице. После нажатие на кнопку редактировать, все поля заполняются данными клиента, после чего можно их изменять и сохранять. Клиента можно удалить из таблицы. Также, если у клиента есть заказы, справа от информации клиента появляются его заказы. Заказы можно изменить, удалить, можно добавить заказ к уже существующим, либо если заказов еще нету добавить заказ. Система поиска реализована по одному из -  ID, имени, фамилии, или телефону клиента. После нахождения записей в базе, все поля для редактирования также сразу заполняются, либо сообщение об ошибке, если записи отсутствуют. Реализована валидация входящих данных, с текстовыми уведомлениями об ошибках. Получить данные по API можно по следующему адресу ‘api/users’, здесь доступна информация о клиенте, а также все его заказы. Также заказы с клиентами доступны по адресу ‘api/orders’. Все действия с сущностями на сервере реализованы в контроллерах в папке ‘app\Http\Controllers\API\’. Логика в компонентах Vue.   
  
Laravel 7  
  
installation:  
  
Создайте свой ENV файл  
establish a database connection in the .env file  
DB_DATABASE= `your name`  
DB_USERNAME= `your login`  
DB_PASSWORD= `your password`  
  
launch the command line:  
`php artisan migrate`  
   
для заполнения профилей рандомными данными выполните  
`php artisan tinker`    
  
`>>>factory(App\User::class, 10)->create()`  
