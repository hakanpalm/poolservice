<!--
version: 1.1
-->
<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes" /> 
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript" src="/mmenu/jquery.mmenu.all.js"></script>
<script type="text/javascript" src="index.js"></script>
<link rel="stylesheet" type="text/css" href="app.css">
<link type="text/css" href="/mmenu/jquery.mmenu.all.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css?family=Asset" rel="stylesheet">
<link rel="icon" href="assets/poolicon.png">

</head>
<body>
<div class="mainview">
	<div class="header"><div class="menuicon"><a href="#my-menu" ><img src='/assets/menu-3-512.png' style="width:32px;height:32px"></a></div><div class="title">PoolService</div></div>
	<div id='pooltemp'></div>
	<div id='pooltempBG'></div>
	<div class='infoBg'></div>
	<div class='info'>
		<h1>Conditions</h1>
		<div id='air'></div>
		<div id='waterout'></div>
		<div id='weather'><div id='now'>Nu</div><img id="wicon" src="http://openweathermap.org/img/w/10d.png"><div id='weathercond'></div></div>		
		<div id='forecast'><div id='forecasttime'></div><div><img id="wficon" src="http://openweathermap.org/img/w/10d.png"></div><div id='wfcond'></div></div>
		<div id='weatherlink'><a target="_blank" href='https://openweathermap.org/city/2665452'>Mer väder på Openweathermap.com</a></div>
	</div>
	<div class='controlsBg'></div>
	<div id='controls'>
		<h1>Controls</h1>
		<table class="pooltable">
			<tr><td><label class="poolswitch">
				<input id='poolSwitch' type="checkbox" onclick="poollight()"></input>
				<div class="poolslider"></div></label></td>
				<td>PoolBelysning</td></tr>
			<tr><td><label class="poolswitch">
				<input id='decorationSwitch' type="checkbox" onclick="decorationlight()"></input>
				<div class="poolslider"></div></label></td>
				<td>AltanBelysning</td></tr>
			<tr id='sundial'><td><label class="">
				<input id='sundialSwitch' type="checkbox" onclick="sundial()"></input>
				<div class=""></div></label></td>
				<td>Solursfunktion</td></tr>
			<tr id='poolpumpicon'><td><img id='pump' src='assets/poolpump_stop.png' alt='Pool Pump' height='45px' width='45px'>
				</td><td>PoolPump</td></tr>
			<tr id='heatpumpicon'><td><img id='heatpump' src='assets/icon-heat-pump.png' alt='Pool Pump' height='45px' width='45px'>
				</td><td>VärmePump</td></tr>
		</table>
	</div>
	<div class="miscControls">
		<button class="iconbutton" id='updatedoc' style='background:url(assets/music-icon.png) no-repeat;background-Size: contain;' onclick="window.open('http://192.168.1.123:6680/iris/#/discover/featured','_blank')"></button>
		<button class="iconbutton" id='updatedoc' style='background:url(assets/speaker-icon.png) no-repeat;background-Size: contain;' onclick="window.open('http://192.168.1.123:3000','_blank')"></button>
	</div>
	<nav id="my-menu">
	   <ul>
		  <li><a href="settings.php">Inställningar</a></li>
		  <li><a href="doc/_build/index.html">Dokumentation</a></li>
		  <li><a href="/traq/poolservice/tickets">Ärendehantering</a></li>
	   </ul>
	</nav>
	<iframe class='nowplaying'src="mopidy.html" style="position:absolute;height:430px;width:330px;right:-30px;top:265px;outline: none;" frameborder="0"></iframe>
</div>
</body>

</html>

