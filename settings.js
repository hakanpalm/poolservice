//document.ready
$(document).ready(function(){
	
	update();
	
});
//enddocument.ready
//update
function update(){
	var d = new Date();
	var weekday = new Array(7);
	weekday[0] =  "Sunday";
	weekday[1] = "Monday";
	weekday[2] = "Tuesday";
	weekday[3] = "Wednesday";
	weekday[4] = "Thursday";
	weekday[5] = "Friday";
	weekday[6] = "Saturday";

	var n = weekday[d.getDay()];
	
	var par = [];
	$.getJSON("parameters.json", {_: new Date().getTime()}, function(data) {
			pumpruns = data["parameters"]["latestdrain"].split("=>");
			$("input[name='updateinterval']").val(data["parameters"]["updateinterval"]);
			$("input[name='airtempcorr']").val(data["parameters"]["airtempcorr"]);
			$("input[name='pooltempcorr']").val(data["parameters"]["pooltempcorr"]);
			$("input[name='outtempcorr']").val(data["parameters"]["outtempcorr"]);
			$("input[name='poolpump_start']").val(data["parameters"]["poolpump_start"][n]);
			$("input[name='poolpump_stop']").val(data["parameters"]["poolpump_stop"][n]);
			$("input[name='sundial']").val(data["parameters"]["sundial"]);
			$("input[name='sunset']").val(data["parameters"]["sunset"]);
			$("input[name='sunrise']").val(data["parameters"]["sunrise"]);
			$("input[name='offset_rise']").val(data["parameters"]["offset_rise"]);
			$("input[name='offset_set']").val(data["parameters"]["offset_set"]);
			$("input[name='airtempfilter']").val(data["parameters"]["airtempfilter"]);
			$("input[name='airtempcal']").val(data["parameters"]["airtempcal"]);
			$("input[name='pooltempcal']").val(data["parameters"]["pooltempcal"]);
			$("input[name='outtempcal']").val(data["parameters"]["outtempcal"]);
			$("input[name='airtempinfl']").val(data["parameters"]["airtempinfl"]);
			$("input[name='latestdrain']").val(pumpruns[0]);
			$("input[name='watchdog']").val(data["parameters"]["watchdog"]);
			par = data;
			data = [];
		});
};
//endupdate
//saving
function saving(){
	$( "#loading" ).show();
		var parameters = "";
		$(".settingscontent input[type=text]").each(function() {
			if(this.name != "latestdrain"){
				parameters = parameters + this.name + "|" + this.value + ";";
			}
		});
		parameters = parameters.substring(0,(parameters.length-1));
		
		$.post
			('savingpar.php',
			{data: parameters},
			function (data, status) {
				if(status == 'success'){
					setTimeout(
						function() 
							{
								$( "#loading" ).hide();
								update();
							}, 4000);
					
				}else{
					$( "#loading" ).hide();
				}
			});
};
//endsaving
