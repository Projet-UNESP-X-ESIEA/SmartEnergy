<?php

require_once("phpChart_Lite/conf.php");

?>

<?php require 'header.php' ?>

<!DOCTYPE html>
  <html lang="en" dir="ltr">
    <head>
      <meta charset="utf-8">
      <title>ADMIN PANEL</title>
      <link rel="stylesheet" href="css/style.css">
	  
	</head>
    <body>

	<div class="graph">
	<?php
	
	require 'db.php';

	$date = 0;

	

	if(isSet($_GET['GraphDate'])){
	$date = $_GET["GraphDate"];
	//$date = "2018-04-21";
	echo $date." ";
	
	$par1 = "TIME(TIMESTAMP)";
	$par2 = "GlobalRadiation";
	
	if(isSet($_GET['par1'])){
		
	
		switch ($_GET['par1']) {
		case 0:
			$par1 = "TIME(TIMESTAMP)";
			break;
		case 1:
			$par1 = "GlobalRadiation";
			break;
		case 2:
			$par1 = "Rain";
			break;
		case 3:
			$par1 = "AirTemperature";
			break;
		case 4:
			$par1 = "RelHumidity";
			break;
		case 5:
			$par1 = "WindSpeed";
			break;
		case 6:
			$par1 = "WindDirection";
			break;
		case 7:
			$par1 = "RelAirPressure";
			break;
		default:
			$par1 = "TIME(TIMESTAMP)";
			break;
		}
	}
	
	if(isSet($_GET['par2'])){
		
	
	
		switch ($_GET['par2']) {
		case 0:
			$par2 = "GlobalRadiation";
			break;
		case 1:
			$par2 = "Rain";
			break;
		case 2:
			$par2 = "AirTemperature";
			break;
		case 3:
			$par2 = "RelHumidity";
			break;
		case 4:
			$par2 = "WindSpeed";
			break;
		case 5:
			$par2 = "WindDirection";
			break;
		case 6:
			$par2 = "RelAirPressure";
			break;
		default:
			$par2 = "GlobalRadiation";
			break;
		}
	}
	
	//echo str_repeat("&nbsp;", 60);
	$username = (isset($_SESSION['username'])) ? $_SESSION['username'] : 'no name';


	$queryTag = "SELECT tag FROM dayfiltertags WHERE date = '$date'";
	
	$resultTag = mysqli_query($conn, $queryTag);
	
	//$tag = $resultTag->fetch_assoc();
	
	echo $resultTag->fetch_row()[0] ?? false;
	echo "<br>$par2";

	
	$queryGraph = "SELECT TIME(TIMESTAMP), $par2 FROM weatherstation WHERE DATE(TIMESTAMP) = CAST('$date' AS DATE)";
	

	$resultGraph = mysqli_query($conn, $queryGraph);

	
	
	if ($resultGraph->num_rows==0)
	{
		echo "\nNo data available";

	}else {
		
		$myXSet = [];
		$myDataSet = [];
		$line = [];
		
		$i = 0;
		
		while ($row = $resultGraph->fetch_array()) {
				
				array_push($myXSet, floatval($row[0]));
				array_push($myDataSet, floatval($row[1]));
				array_push($line, array(floatval($row[0]), floatval($row[1])));
	
			}
		
	
		
		if ($par1 != "TIME(TIMESTAMP)") {
			//$pc = new C_PhpChartX(array($myXSet, $myDataSet),'basic_chart');
			$pc = new C_PhpChartX($line,'basic_chart');
			//echo implode($line[0]);
			//echo "<br>".implode($line[20]);
		}
		else {
			$pc = new C_PhpChartX(array($myDataSet),'basic_chart');
		}
		
		$pc->draw();
		
		}
	}
		
	
		
		
		?>
		</div>
		
		
		
		
		
		
		
		<form method="get" class="graphForm" action=graphDisplay.php>
		
		
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
		
		<script type="text/javascript">
	
		var linkAddress = window.location.href;
		var parts = linkAddress.split("GraphDate=");
		document.getElementById("datetimeGraph").value=parts[1];
		
		
		var inputDate = document.getElementById("datetimeGraph");
		
		//alert(Number(inputDate.step));
		
		function twoDigits(d) {
			if(0 <= d && d < 10) return "0" + d.toString();
			if(-10 < d && d < 0) return "-0" + (-1*d).toString();
			return d.toString();
		}
		
		Date.prototype.toMysqlFormat = function() {
			return this.getUTCFullYear() + "-" + twoDigits(1 + this.getUTCMonth()) + "-" + twoDigits(this.getUTCDate());
		};
		
		function increment() {
			
			if (typeof inputDate.stepUp === 'function') {
				try{
					inputDate.stepUp(1);
				}catch(ex){
					var date = new Date(inputDate.value);
					date.setDate(date.getDate()+1);
					inputDate.value = date.toMysqlFormat();
				}
			}
			
			
			document.getElementById("enterButton").click();
		}
		
		function decrement() {
			
			if (typeof inputDate.stepDown === 'function') {
				try{
					inputDate.stepDown();
				}catch(ex){
					var date = new Date(inputDate.value);
					date.setDate(date.getDate()-1);
					inputDate.value = date.toMysqlFormat();
				}
			}
			
			
			document.getElementById("enterButton").click();
		}
		
		
		function switchGraph() {
			
			window.location.href = "tempGraph.php?GraphDate="+parts[1];
			
		}
	
		</script>
	  
	</body>
  </html>
  
  <?php require 'footer.php'; ?>
