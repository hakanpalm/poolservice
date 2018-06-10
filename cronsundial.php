#!/usr/bin/env php
<?php
//Version: 1.0
//Runs once per day to get today's sunset and tomorrows sunrise
//Write respective time to paratemters.json
//Runs 11:00 every day to get new sunset and sunrise time
//***********************************************************

//Get paramter array from parameters.json

$parameterfile = file_get_contents("parameters.json");
$param = json_decode($parameterfile, true);

//Check if sundial function is activated, if not just exit the PHP file

if ($param["parameters"]["sundial"]) {
	
	//Get timezoneoffset
	
	$daylight_savings_offset_in_seconds = timezone_offset_get( timezone_open(  "Europe/Stockholm" ), new DateTime() );
	$daylight_savings_offset = $daylight_savings_offset_in_seconds/3600;
	
	//Get today's sunset times from sunrise-sunset.org
	//Check connectivity with sunset.sunrise.org
	
	system("ping -c 4 api.sunrise-sunset.org", $response);
	
	//If succesfull then fetch new data
	
	if($response == 0){
		$json = "https://api.sunrise-sunset.org/json?lat=59.53436&lng=18.07758&formatted=0";
		$jsonfile = file_get_contents($json);
		
		//Parse and calculate sunset time
		
		$res = json_decode(str_replace ('\"','"', $jsonfile), true);
		$sunset = date('Y:m:d H:i:s', strtotime(explode("+", $res["results"]["sunset"])[0]) + $daylight_savings_offset_in_seconds + $param["parameters"]["offset_set"]);
		
		//Get tomorrow's sunrise times from sunrise-sunset.org
		
		$json = "https://api.sunrise-sunset.org/json?lat=59.53436&lng=18.07758&formatted=0&date=tomorrow";
		$jsonfile = file_get_contents($json);
		
		//Parse and calculate sunrise time
		
		$res = json_decode(str_replace ('\"','"', $jsonfile), true);
		$sunrise = date('Y:m:d H:i:s', strtotime(explode("+", $res["results"]["sunrise"])[0]) + $daylight_savings_offset_in_seconds + $param["parameters"]["offset_rise"]);
		if(!isset($sunset) or !isset($sunrise)){
			//If values is null then do the calculation
			$sunrise = date('Y:m:d H:i:s', date_sunrise(strtotime('+1 day'), SUNFUNCS_RET_TIMESTAMP, 59.53436, 18.07758,90.583333,$daylight_savings_offset));
			$sunset = date('Y:m:d H:i:s', date_sunset(time(), SUNFUNCS_RET_TIMESTAMP, 59.53436, 18.07758,90.583333,$daylight_savings_offset));	
		}
	}else{
		
		//else do the calculation
		
		$sunrise = date('Y:m:d H:i:s', date_sunrise(strtotime('+1 day'), SUNFUNCS_RET_TIMESTAMP, 59.53436, 18.07758,90.583333,$daylight_savings_offset));
		$sunset = date('Y:m:d H:i:s', date_sunset(time(), SUNFUNCS_RET_TIMESTAMP, 59.53436, 18.07758,90.583333,$daylight_savings_offset));
	}
}else{
	
	//Exit the PHP file if not sundial is activated
	
	exit("Sundial is not activated");
}

//Write respective time to respective key in array

foreach ($param["parameters"] as $key => $test) {
	if ($key == "sunrise"){
		$param["parameters"][$key]=$sunrise;
		echo "New Sunrise =".$sunrise;
	}
	if ($key == "sunset"){
		$param["parameters"][$key]=$sunset;
		echo "New Sunset =".$sunset;
	}
}

//write updated array to parameters.json 

$newJsonString = json_encode($param, JSON_PRETTY_PRINT);
file_put_contents("parameters.json", $newJsonString);
?>
