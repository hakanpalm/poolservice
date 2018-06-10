#!/usr/bin/env php
<?php
//Version: 2.0
//Runs once per minute to handling time depending actions
//********************************************************

require_once "Mail.php";

//Get paramter array from parameters.json

$parameterfile = file_get_contents("parameters.json");
$param = json_decode($parameterfile, true);

//Check if sundial function is activated
//If True then turn decoration light on/off depending on
//sun status

if ($param["parameters"]["sundial"]) {
	
	$sunrise = $param["parameters"]["sunrise"];
	$sunset = $param["parameters"]["sunset"];
	
	//Turn off/on light depending on sun zone
	
	if (date('Y:m:d H:i:s') < $sunrise && date('Y:m:d H:i:s') > $sunset ){
		system(" gpio -g mode 23 out ");
		system(" gpio -g write 23 1");
		echo "We are in dark zone, turns on light<br>";
	}else{
		system(" gpio -g mode 23 out ");
		system(" gpio -g write 23 0");
		echo "We are in daylight zone, turns off light<br>";	
	}
}
echo $sunrise."/".$sunset."/".date('Y:m:d H:i:s')."<br>";

//Handling Auto start/stop of Pool Pump

$pumpstart = ($param["parameters"]["poolpump_start"][date('l')]);
$pumpstop = ($param["parameters"]["poolpump_stop"][date('l')]);

if (date('H:i') > $pumpstart && date('H:i') < $pumpstop ){
	system(" gpio -g mode 18 out ");
	system(" gpio -g write 18 1");
	echo "We are in the automatic pump run zone, pump is starting<br>";
}else{
	system(" gpio -g mode 18 out ");
	system(" gpio -g write 18 0");
	echo "We are outside the automatic pump run zone, pump is stopped<br>";
}
echo $pumpstart."/".$pumpstop."/".date('H:i');

//Handling the watchdog

$pumptimes = explode("=>", $param['parameters']['latestdrain']);
$watchdog = time() - strtotime($pumptimes[0]);
if ($watchdog > $param['parameters']['watchdog']*86400 && !file_exists('services/watchdog/watchdog')){
	$message = "More than ".$param['parameters']['watchdog']." day(s) since latest pumprun ".gmdate("H:i:s",$watchdog);

	$from = "hakan@familjen-palm.se";
	$to = "hakan@familjen-palm.se";
	$subject = "Poolservice alert!";
	$body = $message;
	$host = "ssl://send.one.com";
	$port = "465";
	$username = "hakan@familjen-palm.se";
	$password = "Linette02";
	$headers = array ('From' => $from,
	  'To' => $to,
	  'Subject' => $subject);
	$smtp = Mail::factory('smtp',
	  array ('host' => $host,
		'port' => $port,
		'auth' => true,
		'username' => $username,
		'password' => $password));
	$mail = $smtp->send($to, $headers, $body);
	if (PEAR::isError($mail)) {
	  echo("<p>" . $mail->getMessage() . "</p>");
	 } else {
	  echo("<p>Message successfully sent!</p>");
	 }
	 
	 fopen('services/watchdog/watchdog','w');
}

//Write latest Drain Pump Run to parameters.json

if (file_exists('services/done/done')){
	$latestrun = file_get_contents('services/done/done');
	foreach ($param["parameters"] as $key => $test) {
	if ($key == "latestdrain"){
		$formerrun=$param["parameters"][$key];
		$param["parameters"][$key]=$latestrun;
		echo "Latest Drain Pump Run =".$latestrun;
	}}
	
	//write updated array to parameters.json 
	
	$newJsonString = json_encode($param, JSON_PRETTY_PRINT);
	file_put_contents("parameters.json", $newJsonString);
	unlink("services/done/done");
	if (file_exists('services/watchdog/watchdog')){
		unlink('services/watchdog/watchdog');
	}
	
	//write pumprun to database
	
	$pumptimes = explode("=>", $latestrun);
	$formertimes = explode("=>", $formerrun);
	$duration = strtotime($pumptimes[1]) - strtotime($pumptimes[0]);
	$since = strtotime($pumptimes[0]) - strtotime($formertimes[0]);
	$strSQL="INSERT INTO drainpumpruns (start_time, stop_time, duration, time_since_last)
			VALUES ('".strtotime($pumptimes[0])."','".strtotime($pumptimes[1])."','"
			.$duration."','".$since."')";

	$db = new PDO('sqlite:/var/www/html/poolservice.sqlite3');
	$db->exec("CREATE TABLE IF NOT EXISTS drainpumpruns (post_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,start_time INTEGER, stop_time INTEGER, duration INTEGER, time_since_last INTEGER)");

	$db->exec($strSQL);
	
	$db=null;
	
}
?>
