<?php
	require 'BluePrint/BluePrint.php';
	BluePrint::set("Data.Test", "This is some test data");

	BluePrint::loadPlugin("Cookie", "Cookie");
	BluePrint::Plugin("Cookie")->test());
	

	BluePrint::after("BluePrint\Engine\_start", function(){
		echo '<br>Finished Start';
	});

	echo BluePrint::get("Data.Test");
	BluePrint::start();
?>