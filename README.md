# Little MVC

####A little MVC framework to help in fast projects

### We have
* Very easy to Install
* MVC Structure
* Translate System
* Cache Content

### How to Install
To install the littleMVC application follow this steps:

**Start open global config file**
* Access file ```Config.php``` located on ```core``` folder;


**Now you have to define your global url that be used on all links**
* **Important:** Always end url with a slash "/" (ever!)
* Define your global URL on line ```27``` (Ex: http://myapplication.com/)


**Define Cache content activation.**
* **Important:** Make disabled this content on dinamically content
* On line ```33``` use boolean values ```true``` to enable and ```false``` to disable


**Now, is a important change. If your application are inside a folder, please change this line.**
* **Important:** Always end the folder with a slash "/" (ever!)
* On line ```38``` after slash "/" add ypu folder (Ex: $_SERVER['DOCUMENT_ROOT'] . '/littleMVC/')


**Simple? If you use. Now we start database configuration, try follow this**
* DB_HOST - Your database host (Ex: localhost or 127.0.0.1)
* DB_NAME - Your database name (Ex: littleMVC or anyway)
* DB_USER - Your user used to access database (Ex: root or anyway)
* DB_PASS - Your password used to access database


If you follow correctly this steps your application will start on currently page reload.
