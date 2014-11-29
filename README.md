BluePrint
=========
BluePrint is a simple, extensible framework for PHP

###Components
Components are key aspects of the framework, however they are not required by the core Framework. Some examples of components would be a templating class, which is needed on almost every page. Components can be accessesd by the following:
```php
$ValidName = BluePrint::DB("users")->exists("username", "25564"));
```
The above code will return true or false depending on if their is a row in the table users with the column username equal to 25564
**Components should be essential and because of this they are required on every page**


###Plugins
Plugins are classes which hold functionality which is not as essential as components. A few examples of classes are Encryption, Validation and Blog; while these classes are useful they are not required on every page and can be loaded when needed. A plugin can be loaded and ran by:
```php
BluePrint::loadPlugin("Cookie", "Cookie"); //Essentially Requires the Plugin
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

###Database
Currently there is a simple database component as standard and it is capable of basic queries but also has a raw function for more advanced queries. The component can be called by the following:
```php
$Table = BluePrint::DB("users");
```
#####__construct(String : TableName, Optional Boolean : CustomDB)
CustomDB defaults to false but if it is true it will ignore the DB outlined in the config and will instead require a DB is established via ConnectDB()



#####ConnectDB(Host, DB Name, Username, Password)
Creates a new DB connection



#####query(SQL , Optional String : Action Type  , Optional Array : Parameters)
Parameters are values that will replace placholder "?"'s in the SQL query before being run



#####insert(Array : InsertData)
The array must be set out like the following:
Array(
  "username"=>"25564",
  "group_id"=>3
);
This will insert a row with username equal to 25564 and group_id equal to 3



#####update(Array : updateData)
Set out in the same format as insert



#####delete(Optional Where parameters)
Can have same parameters as Where and yield same result



#####get(Optional Where Parameters, Int : Amount)
Amount limits the amount returned so lets say Amount was equal to three then it would only return three rows



#####count(Optional Where Parameters)
returns the amount of rows that meet the query



#####exists(Optional Where Parameters)
returns true if at least one row meets the query requirements



#####where($key, $operator, $value)
if no operator is supplied it is assumed to be "="
```php
DB()->where("username","25564")//Is just as valid as
DB()->where("username", "=","25564")
```
This same function is embedded within other functions such as exist, count and delete



#####skip(Int)
Will disregard the first INT amount of rows



###Validation
This validation plugin has two params, the first being an array of data and the second being a set of rules that the Data must meet. This Validation class can be used like this
```php
BluePrint::loadPlugin("Validate", "Validate\Validation");//Load Plugin
$Checked = BluePrint::Plugin("Validate")->Validate(//Will return bool whether or not all rules are met
		array(
			"Username"=>"cool371132"//Data being Checked
		),
		array(
			"Username" => array(//The key in the data these rules apply to
				"required" => true,//Must be set
				"min" => array( 
          //If a custom error message is wanted you can set the rule value to an array with two keys (Value being the actual rule value and CustomError being the custom error)
					"Value" => 7,
					"CustomError" => "Username is not long enough"
				),
				"max" => 20,//If no error message is needed then just pass the value not in array format
				"unique" => array( //Some rules do require an array input
					"Table" => "user",
					"Column"=> "username",
					"CustomError" => "{Value} is already taken"//{$Value} being replaced by the Data value (in this case "cool37132")
				),
			)
		)
	);
```
