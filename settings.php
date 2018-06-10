<!--
version: 1.0
-->
<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="app.css">
<script type="text/javascript" src="settings.js"></script>
<link rel="icon" href="assets/poolicon.png">
</head>
<body>
		<div class="settingsview"></div>
		<div class="settings">		
			<div id='loading'></div>
			<div class="settingscontent">
				<div><span class='settingcol'>Update Interval:</span><span><input class ="input" type="text" name="updateinterval" /></span></div>
				<div><span class='settingcol'>Air Temp Corr:</span><span><p><input class ="input" type="text" name="airtempcorr" /></span></div>
				<div><span class='settingcol'>Pool Temp Corr:</span><span><p><input class ="input" type="text" name="pooltempcorr" /></span></div>
				<div><span class='settingcol'>Out Temp Corr:</span><span><input class ="input" type="text" name="outtempcorr" /></span></div>
				<div><span class='settingcol'>Filter Air Temp:</span><span><input class ="input" type="text" name="airtempfilter" /></span></div>
				<div><span class='settingcol'>Cal Air Temp:</span><span><input class ="input" type="text" name="airtempcal" /></span></div>
				<div><span class='settingcol'>Cal Pool Temp:</span><span><input class ="input" type="text" name="pooltempcal" /></span></div>
				<div><span class='settingcol'>Cal Out Temp:</span><span><input class ="input" type="text" name="outtempcal" /></span></div>
				<div><span class='settingcol'>Infl Air Temp:</span><span><input class ="input" type="text" name="airtempinfl" /></span></div>
				<div><span class='settingcol'>Sundial Active:</span><span><input class ="input" type="text" name="sundial" /></span></div>
				<div><span class='settingcol'>Sunset:</span><span><input class ="input" type="text" name="sunset" /></span></div>
				<div><span class='settingcol'>Sunrise:</span><span><input class ="input" type="text" name="sunrise" /></span></div>
				<div><span class='settingcol'>Sunset Offset:</span><span><input class ="input" type="text" name="offset_rise" /></span></div>
				<div><span class='settingcol'>Sunrise Offset:</span><span><input class ="input" type="text" name="offset_set" /></span></div>
				<div><span class='settingcol'>Start Pool Pump:</span><span><input class ="input" type="text" name="poolpump_start" /></span></div>
				<div><span class='settingcol'>Stop Pool Pump:</span><span><input class ="input" type="text" name="poolpump_stop" /></span></div>
				<div><span class='settingcol'>Watchdog:</span><span><input class ="input" type="text" name="watchdog" /></span></div>
				<div><span class='settingcol'>Drain Pump Run:</span><span><input class ="input" type="text" name="latestdrain" /></span></div>
			</div>	
			<div class="buttonControls">
				<button class="button" onclick="saving()">Save</button>
				<button class="button" onclick="location.href='charts.html'">Graph</button>
				<button class="button" id='updated' onclick="window.open('updatedoc.php','_blank')">Update Docs</button>
			</div>
		</div>
</body>

</html>

