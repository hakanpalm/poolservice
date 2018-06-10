#!/usr/bin/env php
<?php
//version: 1.0
error_reporting(E_ALL);
ini_set('display_errors', 1);

$parameterstr = $_POST['data'];
$parameters = explode(';', $parameterstr);

//Get paramter array from parameters.json
$parameterfile = file_get_contents("parameters.json");
$param = json_decode($parameterfile, true);

//Write parametrs from Form to resp key
foreach ($param["parameters"] as $key => $test) {
	foreach ($parameters as $items) {
		$item = explode('|', $items);
		if ($item[0] == $key){
			if ($key == "poolpump_start" or $key == "poolpump_stop") {
				$param["parameters"][$key][date('l')] = $item[1];
			}else{
				$param["parameters"][$key] = $item[1];
			}
		}
	}
}
//write updated array to parameters.json
$newJsonString = json_encode($param, JSON_PRETTY_PRINT);
file_put_contents("parameters.json", $newJsonString);
?>
