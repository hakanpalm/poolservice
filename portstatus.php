<?php
//version: 1.0
	$ports = $_POST['ports'];
	$arrPort = explode(',',$ports);
	$strStatus="";
	
	foreach($arrPort as $port) {
		$status = exec(" gpio -g read ".$port);
		$strStatus = $strStatus.$port."=".$status.",";
	}
	
	unset($port);
	
	echo substr($strStatus, 0, -1);
?>
