

# SSDP Finder

[Модуль](https://majordomo.smartliving.ru/forum/viewtopic.php?f=5&t=2756) для открытой платформы домашней автоматизации [Majordomo](majordomo.smartliving.ru).
Основное предназначение модуля - поиск и добавление в систему UPNP устройств. Конкретный функционал(список файлов, воспроизведение...и.т.) по работе с каждым устройством(сервер, рендер, камера, хромкаст...) в основном должен быть вынесен в другой модуль на подобии Простые устройства




**Возможности:**

 - Поиск устройств в локальной сети на основании UPNP протокола
 - Добавление устройств в систему(Терминал, Устройства онлайн)
 - Получение списка сервисов устройства
 - Управление устройствами проигрывания MediaRenderer (автоматически создается шаблон управления отдельными устройствами)
   Только необходимо добавить на собственную сцену управления
   Для воспроизведения ссылки необходимо изменить значение MediaRenderer01.playUrl (Воспроизвести ссылку) для нужного устройства.. 
   Остальное управление находится в шаблоне...
 - Получение списка файлов c MediaServer
 - 
 
 ![ScreenShot](/screen.png)


**TODO:**

 - ~~Добавление простых устройств с помощью модуля (Сначала MediaServer, MediaRender, dial(Chromecast))~~
 - Получения дополнитеньных устройств с одного UPNP
 - Добавлять устрйоства в MJD в зависимости от типа. (Если не MediaRenderer, то не предлагать создавать терминал....)
 - Рефакторинг
 - Шаблоны поведения для управления устройствами


----------


----------


----------


