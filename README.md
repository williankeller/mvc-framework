# Little MVC

####A little MVC framework to help in fast projects

### We have
* Very easy to Install (2 steps)
* MVC Structure
* Translate System
* Cache Content

### How to Install
To install the littleMVC application follow this steps:

**Start open global config file**
* Access file ```Config.php``` located on ```core``` folder;

**1. Now you have to define your global url that be used on all links**
* **Important:** Always end url with a slash "/" (ever!)
* Define your global URL on line ```23``` (Ex: http://myapplication.com/)

**2. Now define database configuration, follow this:**
Go to line ```43```
* DB_HOST - Your database host (Ex: localhost or 127.0.0.1)
* DB_NAME - Your database name (Ex: littleMVC or anyway)
* DB_USER - Your user used to access database (Ex: root or anyway)
* DB_PASS - Your password used to access database

### Create a new Page
To create a new page in a MVC, you need three things, two of them being mandatory:
* M = Model
* V = View (required)
* C = Controller (required)

Our MVC uses a simple and well laid structure to use and create. Then see how to create the ** Controller **
Start by using the name of your page, and that's important.
If your page will be called ```test```, so your controller should be named ```TestController```, see the exemple below
```php
class TestController extends Controller {
  
}
```

Great! Inside, you'll need an action, then even if it is index, then:
```php
class TestController extends Controller {
  
  /**
   * Default action.
   */
  public function indexAction() {
  
  }
}
```

Now to render your page, follow this function to make content:

```php
class TestController extends Controller {
  
  /**
   * Default action.
   */
  public function indexAction() {
    
    // Taking the file in "ROOT/system/views/home/index.phtml"
    $content = $this->view->factory('home/index');
    
    $data = array(
      // Page Meta Title
      '_title' => 'LittleMVC', 
      // Page Meta Description
      '_description' => 'Welcome! This is a little MVC.',
      // Content created in Facroey function (required)
      '_content' => $content, 
    );
    
    // Content data to render
    $this->view->render($data);
  }
}
```

