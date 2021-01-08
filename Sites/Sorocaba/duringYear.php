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
	
	<?php
	
		require 'db.php';
	
		if(isSet($_GET['Year'])){
			$year = $_GET["Year"];
	
	
			$queryGraph = "SELECT TIMESTAMP, GlobalRadiation FROM weatherstation WHERE YEAR(TIMESTAMP) = '$year' AND DATE(TIMESTAMP) = (SELECT date FROM dayfiltertags WHERE date = DATE(TIMESTAMP) AND tag='Sunny') AND DATE(TIMESTAMP) BETWEEN '2018-04-01' AND '2018-06-30' AND TIME(TIMESTAMP) = '12:00:00'";
			$resultGraph = mysqli_query($conn, $queryGraph);

			
			$myDaysSet = [];
			$myDataSet = [];
			
		
			while ($row = $resultGraph->fetch_array()) {
				
				array_push($myDaysSet, $row[0]); 
				array_push($myDataSet, floatval($row[1]));
				//$final[$row[0]] = $row[1];
			}
			
			/*$final = [];
			
			$totalDiff = strtotime(end($myDaysSet))-strtotime($myDaysSet[0]);
						
		
		
			$arrayIterator = 0;
			
			array_push($final,$myDataSet[0]);
		
			for ($i = 0; $i < count($myDaysSet)-1; $i++) {
				
				$diff = strtotime($myDaysSet[$i+1])-strtotime($myDaysSet[$i]);
				
				while($diff > 86400) {
					array_push($final, 0);
					$diff = $diff - 86400;
				}
				
				array_push($final, $myDataSet[$i+1]);
				
			}*/
		
			/*$final = array_combine($myDaysSet, $myDataSet);
			$pc = new C_PhpChartX(array($myDaysSet,$myDataSet),'basic_chart');
			$pc->draw();*/
		}
	?>
	
	
	<br><br><br>
		<form method="get" class="yeargraphForm" action=duringYear.php>
		<button type="Graph" class="graphSubmit" name="graph">Graph</button>
		<input type="number" id="yearGraph" name="Year" required>
		</form>
	  
	</body>
   </html>
  
<?php require 'footer.php'; ?>