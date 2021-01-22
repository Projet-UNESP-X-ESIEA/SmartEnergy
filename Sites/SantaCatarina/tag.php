<?php
	
	require 'db.php';

	$date = 0;

	

	if(isSet($_GET['GraphDate'])){
		$date = $_GET["GraphDate"];
		
		$queryTag = "SELECT tag FROM dayfiltertags WHERE date = '$date'";
	
		$resultTag = mysqli_query($conn, $queryTag);
		
		
		echo $date." ".$resultTag->fetch_row()[0] ?? false;;
	}