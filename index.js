//==============
//Version 1.1
//==============
//document.ready
$(document).ready(function(){
	//Prevent interaction with sundial checkbox
	//Can only be set by parameters.json
	$("#sundialSwitch").bind("click", false);
	//Update GUI interface with data like status and temperatures
	update();
	getWeather();
	$("#my-menu").mmenu();
		
	
});
//enddocument.ready
//getWeather
//Function to get weather information from openweathermap.org by using weatherforcast.php
//The function returns actual weather and a forecast for next 3h
//See openweathermap.org/api for reference
function getWeather(){
	//Stoping timer to avoid double start
	//The try and catch arrangement is for the initial case when the timer not have
	//been started yet
	try{
		clearTimeout($weathertimer);
	}
	catch(err) {
        console.log(err);
    }
    //Starting an AJAX request to openweather API
	var weather = new XMLHttpRequest();
		weather.onreadystatechange=function(){
			if(weather.readyState==4 && weather.status ==200){
				weathers = weather.responseText;
				weatherdata = weathers.split(';');
				//Using icon from openweather for GUI
				$("#wicon").attr("src","http://openweathermap.org/img/w/" + weatherdata[2] + ".png");
				$('#weathercond').text(weatherdata[1]);
				$("#wficon").attr("src","http://openweathermap.org/img/w/" + weatherdata[6] + ".png");
				$('#wfcond').text(parseInt(weatherdata[4],0) + " °C");
				$('#forecasttime').text(weatherdata[3]);
			}
		};
		weather.open("POST", "weatherforcast.php", true);
		weather.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		weather.send();
		
	//Starting timer to run this function once per hour
	try{
		$weathertimer = setInterval(getWeather, 3600000);
	}
	catch(err) {
		console.log(err);
		$weathertimer = setInterval(getWeather, 3600000);
	}
}
//endgetWeather
//update
function update(){
	//Stoping timer to avoid double start
	//The try and catch arrangement is for the initial case when the timer not have
	//been started yet
	try{
		clearTimeout($updatetimer);
	}
	catch(err) {
        console.log(err);
    }
		//Get parameters from parameters.json
		$.getJSON("parameters.json", function(data) {
			if(data["parameters"]["sundial"]) {
				console.log("Sundial is activated");
				$("#sundialSwitch").prop("checked", true);
			}else{
				$("#sundialSwitch").prop("checked", false);
			}
			airtempcorr = parseFloat(data["parameters"]["airtempcorr"]);
			pooltempcorr = parseFloat(data["parameters"]["pooltempcorr"]);
			outtempcorr = parseFloat(data["parameters"]["outtempcorr"]);
			updateinterval = data["parameters"]["updateinterval"];
			airtempfilter = parseFloat(data["parameters"]["airtempfilter"]);
			airtempcal = parseFloat(data["parameters"]["airtempcal"]);
			pooltempcal = parseFloat(data["parameters"]["pooltempcal"]);
			outtempcal = parseFloat(data["parameters"]["outtempcal"]);
			airtempinfl = parseFloat(data["parameters"]["airtempinfl"]);
		});
		//Starting an AJAX request to get temp values from server
		var tempvalues = new XMLHttpRequest();
		tempvalues.onreadystatechange=function(){
			if(tempvalues.readyState==4 && tempvalues.status ==200){
				values = tempvalues.responseText;
				value = values.split(':');
				//Calibration, ref http://nerdralph.blogspot.se/2015/11/ds18b20-temperature-sensor-calibration.html
				airtemp = parseFloat(value[0]) + airtempcal + ((parseFloat(value[0]) - 20) * airtempcorr);
				outtemp = parseFloat(value[1]) + outtempcal + ((parseFloat(value[1]) - 20) * outtempcorr) - (Math.pow((airtemp),4) * airtempinfl);
				pooltemp = parseFloat(value[2]) + pooltempcal + ((parseFloat(value[2]) - 20) * pooltempcorr) - (Math.pow((airtemp - 15),4) * airtempinfl);
				//Adding a low-pass filter to air temp, ref http://phrogz.net/js/framerate-independent-low-pass-filter.htmllive.se
				try{
					corrAirtemp += (airtemp - corrAirtemp) / airtempfilter
				}
				catch(err) {
					console.log(err);
					corrAirtemp = airtemp;
				}
				try{
					corrPooltemp += (pooltemp - corrPooltemp) / airtempfilter
				}
				catch(err) {
					console.log(err);
					corrPooltemp = pooltemp;
				}
				
				//Write values to GUI
				$("#air").text("Temperatur: " + corrAirtemp.toFixed(1) + " °C");
				$("#waterout").text("Vatten Ut: " + outtemp.toFixed(1) + " °C");
				$("#pooltemp").text(corrPooltemp.toFixed(1) + " °C");
				values = "";
				value = [];
			}
		};
		tempvalues.open("POST", "temp.php", true);
		tempvalues.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		tempvalues.send();
		
		//Starting an AJAX request to get port status from server and do depended actions
		//Port 09,22,27,17 goes high when there is a current draw in the line
		//Port 10 goes low  when there is a current draw in the line
		var portstatus = new XMLHttpRequest();
		portstatus.onreadystatechange=function(){
			if(portstatus.readyState==4 && portstatus.status ==200){
				var portstatusObj = { };
				var portArray = portstatus.responseText.split(",");
				for (var i = 0; i < portArray.length; i++) {
					tmpArray = portArray[i].split("=");
					portstatusObj[tmpArray[0]] = {"value":tmpArray[1]};
				}
				//Status on pool light relay
				if (portstatusObj["24"].value == 1) {
					$("#poolSwitch").prop("checked", true);
				}else{
					$("#poolSwitch").prop("checked", false);
				}
				//Status on Decoration light current draw
				if (portstatusObj["23"].value == 1) {
					$("#decorationSwitch").prop("checked", true);
				}else{
					$("#decorationSwitch").prop("checked", false);
				}
				//Status on Pool Pump current draw
				if (portstatusObj["10"].value == 0) {
					$("#pump").attr("src","assets/poolpump.png");
					$("#pooltemp").show();
					$("#pooltempBG").show();
					$("#waterout").show();
					$(".infoBG").attr("height","230px");
				}else{
					$("#pump").attr("src","assets/poolpump_stop.png");
					//Hide water temperatures since they not are
					//accurate when pool pump is in halt
					$("#pooltemp").hide();
					$("#pooltempBG").hide();
					$("#waterout").hide();
					$(".infoBG").attr("height","180px");
				}
				//Status on Heat Pump current draw
				if (portstatusObj["09"].value == 1) {
					$("#heatpump").attr("src","assets/icon-heat-pump_on.png");
				}else{
					$("#heatpump").attr("src","assets/icon-heat-pump.png");
				}
				//Status on Drain Pump current draw
				if (portstatusObj["27"].value == 1) {
					
				}else{
					
				}
				//Status on Pool Light current draw
				if (portstatusObj["22"].value == 1) {
					
				}else{
					
				}
				
			}
		};
		portstatus.open("POST", "portstatus.php", true);
		portstatus.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		portstatus.send("ports=09,10,14,15,17,18,22,23,24,27");
		
	//Set timer for next update
	try{
		$updatetimer = setInterval(update, updateinterval);
	}
	catch(err) {
		console.log(err);
		$updatetimer = setInterval(update, 1000);
	}
};
//endupdate
//poollight
function poollight(){
	if (document.getElementById('poolSwitch').checked) {
		var a= new XMLHttpRequest();
		a.open("POST", "on.php", true);
		a.onreadystatechange=function(){
			if(a.readyState==4){ if(a.status ==200){ 
		} else alert ("http error"); } }
		a.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		a.send("port=24");
	} else {
		var a= new XMLHttpRequest();
		a.open("POST", "off.php", true); a.onreadystatechange=function(){
		if(a.readyState==4){ if(a.status ==200){
		} else alert ("http error"); } }
		a.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		a.send("port=24");
	}
};
//endpoollight
//decorationlight
function decorationlight(){
	if (document.getElementById('decorationSwitch').checked) {
		var a= new XMLHttpRequest();
		a.open("POST", "on.php", true); a.onreadystatechange=function(){
		if(a.readyState==4){ if(a.status ==200){ 
		} else alert ("http error"); } }
		a.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		a.send("port=23");
	} else {
		var a= new XMLHttpRequest();
		a.open("POST", "off.php", true); a.onreadystatechange=function(){
		if(a.readyState==4){ if(a.status ==200){
		} else alert ("http error"); } }
		a.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		a.send("port=23");
	}
};
//enddecorationlight
