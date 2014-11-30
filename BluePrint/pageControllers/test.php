<?php
namespace BluePrint\pageControllers;
class test {
	public $testVar = "derp";
	public function create($Params){
		echo "Page Controller - Parameter Passed: ",  var_dump($Params);
	}

	public function __construct(){
		echo "Instantiated Page Controller<br>";
	}
}
?>