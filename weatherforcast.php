<?php
//version 1.0
$url="http://api.openweathermap.org/data/2.5/weather?id=2665452&units=metric&lang=se&APPID=e8ac35cd5c489d3e21f17f889db97e2b";

$json=file_get_contents($url);
$data=json_decode($json,true);
//Get current Temperature in Celsius
echo $data['main']['temp'].";";
//Get weather condition
echo ucwords($data['weather'][0]['description']).";";
//Get cloud percentage
echo $data['weather'][0]['icon'].";";

$url="http://api.openweathermap.org/data/2.5/forecast?id=2665452&units=metric&lang=se&APPID=e8ac35cd5c489d3e21f17f889db97e2b";

$json=file_get_contents($url);
$data=json_decode($json,true);

if (date('H:i', strtotime('+1 hours +30 minutes'))<date('H:i',$data['list'][0]['dt'])) {
	echo date('H:i',$data['list'][0]['dt']).";";
	echo $data['list'][0]['main']['temp'].";";
	//Get weather condition
	echo ucwords($data['list'][0]['weather'][0]['description']).";";
	//Get cloud percentage
	echo $data['list'][0]['weather'][0]['icon'];
}else{
	echo date('H:i',$data['list'][1]['dt']).";";
	echo $data['list'][1]['main']['temp'].";";
	//Get weather condition
	echo ucwords($data['list'][1]['weather'][0]['description']).";";
	//Get cloud percentage
	echo $data['list'][1]['weather'][0]['icon'];
	
}
?>
