BluePrint
=========
BluePrint is a simple, extensible framework for PHP

###Components
Components are key aspects of the framework, however they are not required by the core Framework. Some examples of components would be a templating class, which is needed on almost every page.

Current there are no components. **Components should be essential and because of this they are required on every page**

###Plugins
Plugins are classes which hold functionality which is not as essential as components. A few examples of classes are Encryption, Validation and Blog; while these classes are useful they are not required on every page and can be loaded when needed. A plugin can be loaded and ran by:
```php
BluePrint::loadPlugin("Cookie", "Cookie");
BluePrint::Plugin("Cookie")->exists("Username"));
```

###Hooks
Hooks are events that occur at certain points. Currently there is two different types of hooks: before and after and these occur before or after a specific method is invoked.

```php
BluePrint::after("BluePrint\Engine\_start", function(){
  echo 'Finished Start';
});

echo "My beutiful web page<br>";
BluePrint::start();
```
This code would result in:
>My beutiful web page
>Finished Start

When standard methods are called such as start it actually will call two different function _start and start. start is triggered before anything so after("start") may cause issues instead use after("_start") for any standard methods.

###Variables
Blueprint comes with a Global variables:
```php
BluePrint::set("Data.Test", "This is some test data");//Set the name then value
if(BluePrint::has("Data.Test")){//Check if it is set
  echo BluePrint::get("Data.Test");//Echo the value
}
```
