<?php

require 'db.php';

$tagQuery = "INSERT IGNORE INTO dayfiltertags (date, tag) SELECT DATE(TIMESTAMP), (SELECT 'Rainy' FROM weatherstation WHERE 'Rain'> 0) FROM weatherstation";

$resultTagQuery = $conn->query($tagQuery);
   
if(!$resultTagQuery){
	echo "query impossible ($resultTagQuery);" . mysqli_error($conn);
	exit;
}

$tagRainyUpdate = "UPDATE dayfiltertags SET tag='Rainy' WHERE date=(SELECT DISTINCT DATE(TIMESTAMP) FROM weatherstation WHERE (Rain > 0 AND DATE(TIMESTAMP) = date))";

$resultRainyQuery = $conn->query($tagRainyUpdate);
   
if(!$resultRainyQuery){
	echo "query impossible ($resultRainyQuery);" . mysqli_error($conn);
	exit;
}


$datesArray = [];

$nullQuery = "SELECT date FROM dayfiltertags WHERE tag IS NULL";
$resultNull = $conn->query($nullQuery);

while ($row = $resultNull->fetch_assoc()) {
	
	array_push($datesArray, $row["date"]);
	
}

foreach ($datesArray as $value) {
	
	
	$dayQuery = "SELECT TIME_TO_SEC(TIME(TIMESTAMP))/86400, ASIN(GlobalRadiation/(SELECT MAX(GlobalRadiation) FROM weatherstation WHERE DATE(TIMESTAMP) = '$value')) FROM weatherstation WHERE DATE(TIMESTAMP) = '$value' AND TIME(TIMESTAMP) BETWEEN '06:00:00' AND '17:00:00'";
	
	$resultDayData = $conn->query($dayQuery);
	
	if ($resultDayData->num_rows > 0) {
		
	
		$timeTable = [];
		$radiationTable = [];
	
		while ($row = $resultDayData->fetch_array(MYSQLI_NUM)) {
		
			array_push($timeTable, $row[0]);
		
			if ($row[0]<0.5) {
				array_push($radiationTable, $row[1]);
			}
			else {
				array_push($radiationTable, pi() - $row[1]);
			}
		}

		$rUp = 0;
		$rDownOne = 0;
		$rDownTwo = 0;
	
		if (count($timeTable) == 0 || count($radiationTable) == 0)
		{
			echo $value." ";
		}
	
		$averageX = array_sum($timeTable)/count($timeTable);
		$averageY = array_sum($radiationTable)/count($radiationTable);
	
		for ($i = 0; $i < count($timeTable); $i++) {
		
			$rUp = $rUp + ($timeTable[$i]-$averageX)*($radiationTable[$i]-$averageY);
	
		}
	
		for ($i = 0; $i < count($timeTable); $i++) {

			$rDownOne = $rDownOne + pow($timeTable[$i]-$averageX, 2);

		}		
	
		for ($i = 0; $i < count($timeTable); $i++) {

			$rDownTwo = $rDownTwo + pow($radiationTable[$i]-$averageY, 2);

		}	
	
	
		$r = $rUp/pow($rDownOne*$rDownTwo, (1/2));
	
		/*if ($value == "2020-07-14" ) {
		
			echo "Porównanie: ".pow($r,2)." ";

		}*/
		
		$r_power = pow($r,2);
		
		/*if ($r_power >= 0.973) {
			
			$sunnyQuery = "UPDATE dayfiltertags SET tag='Sunny' WHERE date = '$value'";
			$conn->query($sunnyQuery);			
			
		} 
		
		if ($r_power <= 0.892) {
			
			//echo $value.": ".$r." ";
			$cloudyQuery = "UPDATE dayfiltertags SET tag='Cloudy' WHERE date = '$value'";
			$conn->query($cloudyQuery);
			
		}*/
		
		if ($r_power >= 0.99) {
			$sunnyQuery = "UPDATE dayfiltertags SET tag='Sunny' WHERE date = '$value'";
			$conn->query($sunnyQuery);
		}
		else {
			$cloudyQuery = "UPDATE dayfiltertags SET tag='Cloudy' WHERE date = '$value'";
			$conn->query($cloudyQuery);
		}
	
	}
	
	ini_set('max_execution_time', 0);
}

?>