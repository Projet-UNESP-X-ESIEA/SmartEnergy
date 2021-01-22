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
	//echo str_repeat("&nbsp;", 60);
	$username = (isset($_SESSION['username'])) ? $_SESSION['username'] : 'no name';


	$queryTag = "SELECT tag FROM dayfiltertags WHERE date = '$date'";
	
	$resultTag = mysqli_query($conn, $queryTag);
	
	//$tag = $resultTag->fetch_assoc();
	
	echo $resultTag->fetch_row()[0] ?? false;
	echo "<br>Temperature";

	
	$queryGraph = "SELECT TIME(TIMESTAMP), AirTemperature FROM weatherstation WHERE DATE(TIMESTAMP) = CAST('$date' AS DATE)";
	
	//$countGraph = "SELECT count(*) AS nb FROM weatherstation";

	$resultGraph = mysqli_query($conn, $queryGraph);

	//$ResultCount = mysqli_query($conn, $countGraph);
	/*if(!$ResultCount)
		{
	echo "query impossible ($count);" . mysqli_error();
	}
*/
	//$row = $ResultCount->fetch_assoc();
	
	if ($resultGraph->num_rows==0)
	{
		echo "No data available";

	}else {
		
		$myDataSet = [];
		
		while ($row = $resultGraph->fetch_assoc()) {
				
				array_push($myDataSet, floatval($row["AirTemperature"]));
				
			}
		
	
		$pc = new C_PhpChartX(array($myDataSet),'basic_chart');
		$pc->draw();
		}
	}
		
	
		
		
		?>
		</div>
		
		
		<button type="button" id="switchToRadiation" class="graphSwitch" onclick="switchGraph()">Radiation graph</button>
		
		
		
		<form method="get" class="graphForm" action=tempGraph.php>
		<button type="submit" id="enterButton" class="graphSubmit">Graph</button>
		<input type="date" placeholder="yyyy-mm-dd" id="datetimeGraph" name="GraphDate" required>
		<input type="button" value="<---" onclick="decrement()"/>
		<input type="button" value="--->" onclick="increment()"/>
		</form>
		
		<script type="text/javascript">
	
		var linkAddress = window.location.href;
		var parts = linkAddress.split("=");
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
			
			window.location.href = "graphDisplay.php?GraphDate="+parts[1];
			
		}
	
		</script>
	  
	</body>
  </html>
  
  <?php require 'footer.php'; ?>
