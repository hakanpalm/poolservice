<?php
	$port = $_POST['port'];
	system(" gpio -g mode ".$port." out ");
	system(" gpio -g write ".$port." 1");
?>
