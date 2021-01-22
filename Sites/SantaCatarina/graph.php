


<?php

require 'db.php';

$date = 0;


if(isSet($_GET['GraphDate'])){
	$date = $_GET["GraphDate"];
	//echo $date;

//echo $date;
session_start();
$username = (isset($_SESSION['username'])) ? $_SESSION['username'] : 'no name';


//$queryGraph = "SELECT TIMESTAMP, GlobalRadiation FROM weatherstation WHERE DATE(TIMESTAMP) = '2018-04-23'";
$queryGraph = "SELECT TIME(TIMESTAMP), GlobalRadiation FROM weatherstation WHERE DATE(TIMESTAMP) = CAST('$date' AS DATE)";
//echo $queryGraph;
$countGraph = "SELECT count(*) AS nb FROM weatherstation";

$resultGraph = mysqli_query($conn, $queryGraph);

$ResultCount = mysqli_query($conn, $countGraph);
if(!$ResultCount)
{
  echo "query impossible ($count);" . mysqli_error();
}

$row = $ResultCount->fetch_assoc();
if ($row['nb']==0)
{
echo "<script>alert(\"Sorry, there are no data available. Please try again later. \")</script>";

}else {
	
	$myDataSet = [];
	$i = 0;
	while ($row = $resultGraph->fetch_assoc()) {
			
			$myDataSet[] = [
				'x' => $i,
				//'x' => date($row["TIME(TIMESTAMP)"]),
				'y' => intval($row["GlobalRadiation"])
			];
			$i = $i +1;
	}
	
	echo json_encode($myDataSet);
	
}
}
?>
