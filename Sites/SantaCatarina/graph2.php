<?php
	
	require 'db.php';
	

	$date = 0;

	

	if(isSet($_GET['GraphDate'])){
	$date = $_GET["GraphDate"];
	//$date = "2018-04-21";
	//echo $date." ";
	
	$par1 = "TIME(TIMESTAMP)";
	$par2 = "Radiation";
	
	if(isSet($_GET['par1'])){
		
	
		switch ($_GET['par1']) {
		case 0:
			$par1 = "TIME(TIMESTAMP)";
			break;
		case 1:
			$par1 = "Radiation";
			break;
		case 2:
			$par1 = "Energy1";
			break;
		case 3:
			$par1 = "Energy2";
			break;
		case 4:
			$par1 = "Temperature";
			break;
		case 5:
			$par1 = "Humidity";
			break;
		case 6:
			$par1 = "Rain";
			break;
		default:
			$par1 = "TIME(TIMESTAMP)";
			break;
		}
	}
	
	if(isSet($_GET['par2'])){
		
	
	
		switch ($_GET['par2']) {
		case 0:
			$par2 = "Radiation";
			break;
		case 1:
			$par2 = "Energy1";
			break;
		case 2:
			$par2 = "Energy2";
			break;
		case 3:
			$par2 = "Temperature";
			break;
		case 4:
			$par2 = "Humidity";
			break;
		case 5:
			$par2 = "Rain";
			break;
		default:
			$par2 = "Radiation";
			break;
		}
	}

	
	
	$username = (isset($_SESSION['username'])) ? $_SESSION['username'] : 'no name';


	$queryTag = "SELECT tag FROM dayfiltertags WHERE date = '$date'";
	
	$resultTag = mysqli_query($conn, $queryTag);
	
	
	
	//echo $resultTag->fetch_row()[0] ?? false;
	//echo "<br>$par2";

	
	$queryGraph = "SELECT $par1, $par2 FROM weatherstation WHERE DATE(TIMESTAMP) = CAST('$date' AS DATE)";
	

	$resultGraph = mysqli_query($conn, $queryGraph);

	
	
	if ($resultGraph->num_rows==0)
	{
		echo "\nNo data available";

	}else {
		
		$myXSet = [];
		$myDataSet = [];
		$line = [
			["x",
			"y"]
		];
		
		
		$i = 0;
		
		while ($row = $resultGraph->fetch_array()) {
				
				$val1 = $row[0];
				if ($par1 != "TIME(TIMESTAMP)") {
					$val1 = floatval($row[0]);
				}
				
				$line[] = [
				$val1,
				floatval($row[1])
			];
			$i = $i +1;
			}
		
	
		
		
		echo json_encode($line);
		
		}
	}
		
	
		
		
		?>