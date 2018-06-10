<?php
//Open the database and get values from
//latestpumpruns for use in the graph
$db = new PDO('sqlite:/var/www/html/poolservice.sqlite3');
$sql="SELECT * FROM drainpumpruns ORDER BY start_time DESC LIMIT 50";
$result = $db->query($sql) or die('Query failed');
$daylight_savings_offset_in_seconds = timezone_offset_get( timezone_open(  "Europe/Stockholm" ), new DateTime() );
  foreach($result as $row) {
      $start=gmdate('Y-m-d H:i',$row["start_time"] + $daylight_savings_offset_in_seconds);
      $count=$row["time_since_last"];
      $dataArray[$start]=$count;
  }
$db=null;
//Customize the graph as a line type
include("services/php/phpgraphlib.php");
$graph=new PHPGraphLib(1000,600);
$graph->addData($dataArray);
$graph->setTitle("Time between Runs (sec)");
$graph->setupXAxis(20);
$graph->setLineColor("blue");
$graph->setBars(false);
$graph->setLine(true);
//$graph->setGoalLine(82400, "red", "solid");
$graph->createGraph();
?>
