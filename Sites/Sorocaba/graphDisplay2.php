
<?php require 'header.php' ?>

<!DOCTYPE html>
  <html lang="en" dir="ltr">
    <head>
      <meta charset="utf-8">
      <title>ADMIN PANEL</title>
      <link rel="stylesheet" href="css/style.css">
	  
	</head>
    <body>	
		
		<div id="chart_div" style="width: 900px; height: 500px;"></div>
		<!--<div id="chart_div" class="graph">-->
		
		
		
		<form method="get" class="graphForm" action=graphDisplay2.php>
		
		
		X:
		<select id="par1" name="par1">
			<option value="0">TIME</option>
			<option value="1">Radiation</option>
			<option value="2">Rain</option>
			<option value="3">AirTemperature</option>
			<option value="4">Humidity</option>
			<option value="5">WindSpeed</option>
			<option value="6">WindDirection</option>
			<option value="7">AirPressure</option>
		</select>
		<br>
		Y:
		<select id="par2" name="par2">
			<option value="0">Radiation</option>
			<option value="1">Rain</option>
			<option value="2">AirTemperature</option>
			<option value="3">Humidity</option>
			<option value="4">WindSpeed</option>
			<option value="5">WindDirection</option>
			<option value="6">AirPressure</option>
		</select>
		
		
		
		<button type="Graph" id="enterButton" class="graphSubmit">Graph</button>
		<input type="date" placeholder="yyyy-mm-dd" id="datetimeGraph" name="GraphDate" required>
		<input type="button" value="<---" onclick="decrement()"/>
		<input type="button" value="--->" onclick="increment()"/>
		</form>
		
		
		<p id="tagField" style="display: none;"></p>
		
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript">
	
		var linkAddress = window.location.href;
		var parts = linkAddress.split("GraphDate=");
		document.getElementById("datetimeGraph").value=parts[1];
		
		var parameters = "";
		var parameter1 = "";
		var parameter2 = "";
		
		if(parts[0].split("?")[1])
		{
			parameters = parts[0].split("?")[1].split("&");
			parameter1 = parameters[0].split("=")[1];
			parameter2 = parameters[1].split("=")[1];
		
			document.getElementById("par1").value = parameter1;
			document.getElementById("par2").value = parameter2;
		}
		switch (parseInt(parameter1)) {
			case 0:
				xTitle = "TIME";
				break;
				
			case 1:
				xTitle = "Radiation";
				break;
				
			case 2:
				xTitle = "Rain";
				break;
				
			case 3:
				xTitle = "AirTemperature";
				break;
				
			case 4:
				xTitle = "Humidity";
				break;
  
			case 5:
				xTitle = "WindSpeed";
				break;
				
			case 6:
				xTitle = "WindDirection";
				break;
				
			case 7:
				xTitle = "AirPressure";
				break;
				
			default:
				xTitle = "TIME";
  
		}
		
		switch (parseInt(parameter2)) {
				
			case 0:
				yTitle = "Radiation";
				break;
				
			case 1:
				yTitle = "Rain";
				break;
				
			case 2:
				yTitle = "AirTemperature";
				break;
				
			case 3:
				yTitle = "Humidity";
				break;
  
			case 4:
				yTitle = "WindSpeed";
				break;
				
			case 5:
				yTitle = "WindDirection";
				break;
				
			case 6:
				yTitle = "AirPressure";
				break;
				
			default:
				yTitle = "Radiation";
  
		}
		
		
		var inputDate = document.getElementById("datetimeGraph").value;
		
		var url = linkAddress.split("php")[1];
		var url = "graph2.php"+url;
		var xhr = new XMLHttpRequest();
		
		
		
		function httpGetAsync()
		{
			var xmlHttp = new XMLHttpRequest();
			var theUrl = "tag.php?GraphDate="+inputDate;
			xmlHttp.onreadystatechange = function() { 
			if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
				document.getElementById("tagField").value=xmlHttp.responseText;
			}
			xmlHttp.open("GET", theUrl, true); // true for asynchronous 
			xmlHttp.send(null);
		}
		
		httpGetAsync();
		
		var temp = 0;
		var titleValue = "";
		
		
	
		xhr.onload = function() {	
			if (xhr.readyState === 4) {
				if (xhr.status === 200) {
					
					var result = xhr.response;	
					
					
					google.charts.setOnLoadCallback(function() {
						data = google.visualization.arrayToDataTable(result);
						drawChart(data);
					})
				}
			}
		};
	
		xhr.responseType = 'json';
		xhr.open('GET', url, true);
		xhr.timeout = 60000;
		xhr.send(null);
		
		
		google.charts.load('current', {'packages':['corechart']});
		//google.charts.setOnLoadCallback(drawChart);
		
		
		function drawChart(data) {


			var options = {
				title: document.getElementById("tagField").value,
				hAxis: {title: xTitle},
				vAxis: {title: yTitle},
				legend: 'none'
			};

			var chart = new google.visualization.ScatterChart(document.getElementById('chart_div'));
			
			chart.draw(data, options);
		}
		
		
		
		
		function twoDigits(d) {
			if(0 <= d && d < 10) return "0" + d.toString();
			if(-10 < d && d < 0) return "-0" + (-1*d).toString();
			return d.toString();
		}
		
		Date.prototype.toMysqlFormat = function() {
			return this.getUTCFullYear() + "-" + twoDigits(1 + this.getUTCMonth()) + "-" + twoDigits(this.getUTCDate());
		};
		
		function increment() {
			
			if (typeof document.getElementById("datetimeGraph").stepUp === 'function') {
				try{
					document.getElementById("datetimeGraph").stepUp(1);
				}catch(ex){
					var date = new Date(document.getElementById("datetimeGraph").value);
					date.setDate(date.getDate()+1);
					document.getElementById("datetimeGraph").value = date.toMysqlFormat();
				}
			}
			
			document.getElementById("enterButton").click();
		}
		
		function decrement() {
			
			if (typeof document.getElementById("datetimeGraph").stepDown === 'function') {
				try{
					document.getElementById("datetimeGraph").stepDown();
				}catch(ex){
					var date = new Date(document.getElementById("datetimeGraph").value);
					date.setDate(date.getDate()-1);
					document.getElementById("datetimeGraph").value = date.toMysqlFormat();
				}
			}
			
			
			document.getElementById("enterButton").click();
		}
		
		
		
	
		</script>
	  
	</body>
  </html>
  
  <?php require 'footer.php'; ?>
