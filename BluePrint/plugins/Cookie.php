<?php
namespace BluePrint\plugins;

class Cookie {
	public function exists($name){
		//Checks the existance of cookie and returns true if it exists
		return (isset($_COOKIE[$name])) ? true : false;	
	}
	
	public function get($name){
		//Abstract return cookie value
		return $_COOKIE[$name];
	}

	public function put($name, $value, $expiry){
		//Creates a cookie
		if(setcookie($name, $value, time() + $expiry, '/'))	{
			return true;	
		}
		return false;
	}
	
	public function delete($name) {
		//Deletes the cookie by setting its expiry time to a point that has already passed
		$this->put($name, '', time()-1);
	}
}
?>