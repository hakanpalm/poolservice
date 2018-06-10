<?php
//version 1.0
//Read values from temperature sensors
//AIR TEMP
//File to read
$file = '/sys/devices/w1_bus_master1/28-000008fdccb9/w1_slave';
//Read the file line by line
$lines = file($file);
//Get the temp from second line
$airtemp = explode('=', $lines[1]);
//Setup some nice formatting (i.e., 21,3)
$airtemp = number_format(($airtemp[1] / 1000), 1);
//WATER OUT TEMP
//File to read
$file = '/sys/devices/w1_bus_master1/28-000008feb23a/w1_slave';
//Read the file line by line
$lines = file($file);
//Get the temp from second line
$waterouttemp = explode('=', $lines[1]);
//Setup some nice formatting (i.e., 21,3)
$waterouttemp = number_format(($waterouttemp[1] / 1000)+1, 1);
//POOL TEMP
//File to read
$file = '/sys/devices/w1_bus_master1/28-0000080ae19b/w1_slave';
//Read the file line by line
$lines = file($file);
//Get the temp from second line
$pooltemp = explode('=', $lines[1]);
//Setup some nice formatting (i.e., 21,3)
$pooltemp = number_format(($pooltemp[1] / 1000), 1);

//And echo a string with values in Celcius deg
//divided by ":"
echo $airtemp.":".$waterouttemp.":".$pooltemp;
?>
