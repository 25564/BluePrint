<?php
	require 'BluePrint/BluePrint.php';
	$Table = BluePrint::DB("appdata");
	echo count($Table);//Same as SELECT COUNT(*) FROM table
?>