#Веб-интерфейс для управления аккаунтами абонентов. 
Интерфейс должен поддерживать следующие функции:
добавление абонента (все данные абонента, кроме кода (ID), должны быть доступны для ввода в форме добавления);
удаление абонента;
редактирование данных абонента (все данные абонента, кроме кода (ID), должны быть доступны для редактирования);
постраничный просмотр списка абонентов;
сортировка списка абонентов по коду, логину, email, дате, группе. (Внимание! Сортировка всего result set, а не сортировка записей на текущей странице средствами javascript);
фильтрация списка абонентов по группе и по дате, до которой будет действителен абонентский аккаунт;
добавление группы;
удаление группы;
редактирование имени группы;
постраничный просмотр списка групп;
сортировка списка групп по коду и названию группы.

Примечание.
Картинка-аватар абонента загружается при помощи формы в специальную папку (например, /upload/consumer_avatar) и переименовывается по шаблону: 
[id абонента].[оригинальное расширение картинки].

Приложение должно иметь механизм авторизации и аутентификации, вход в приложение должен осуществляться через специальную форму по паролю. 
Параметры доступа в приложение необходимо вынести в конфигурационный файл (один логин и пароль для входа).
