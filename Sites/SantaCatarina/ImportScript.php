
<?php

if (isset($_POST["import"]))
{
	//header('Location: importAlert.php');
	
  require 'db.php'; //appel de la base de donnée

  //____________________put the export into the history_______________________________________________________////////
    session_start();
    $username = (isset($_SESSION['username'])) ? $_SESSION['username'] : 'no name';

    //rajout de l'historique de connection
    $histimp = "INSERT INTO `historyuser` (`id`, `Who`, `TWhen`, `What`) VALUES (NULL, '$username', CURRENT_TIMESTAMP, 'IMPORT');";

    $resultHistimp = $conn->query($histimp);
    if(!$resultHistimp){
      echo "query impossible ($resultHistimp);" . mysqli_error();
      exit;
    }

  //_____________________________________________________________________________________________________////////




  //-- recuperation du fichier
  $filename=$_FILES["file"]["tmp_name"];
  $ext=substr($filename,strrpos($filename,"."),(strlen($filename)-strrpos($filename,".")));

  $dupliInsert = 0;//variable to count Insert values.
  $dupliCount = 0;//variable to count duplication values.

  

  //we check,file must be have csv extention
  if($ext==".csv" || $ext==".tmp")
  {
      $file = fopen($filename, "r");//open the file in read mode

      $i = 0;

      while (($Data = fgetcsv($file, 10000, ',','"')) !== FALSE)//csv file separate by ','
      {
        //rename data variable
		
		if ($i == 0)
		{
			$i++;
			continue;
		}
		
        $TIMESTAMP = $Data[0]; //need to be 'YYYY-MM-DD hh:mm:ss'


        //Given format - DD/MM/YY is ambiguous in conversion, so there is a change to DD/MM/YYYY before
        

		if (strlen($TIMESTAMP) < 19) {
			$pattern = "/-\d{2}\s{1}/";

			$matches = [];

			preg_match($pattern, $TIMESTAMP, $matches);

			$replacement = "-".strval(intval(str_replace("-", "", $matches[0]))+2000)." ";

			$TIMESTAMP = preg_replace($pattern, $replacement,$TIMESTAMP);
			
		}

        //if the date is in DD/MM/YYYY hh:mm format (french format), we change into YYYY-MM-DD hh:mm:ss

        $TIMESTAMPToData = str_replace("/","-",$TIMESTAMP);
        $TIMESTAMPToData = date("Y-m-d H:i:s", strtotime($TIMESTAMPToData));//changing the date format
        $TIMESTAMP = $TIMESTAMPToData;
        

        //$RECORD          = $Data[1];//if you want to have the record value uncomment this part and put '$RECORD' instead of NULL line 91

        $Energy1           = floatval(str_replace(",",".",$Data[1]));//replace all the "," of floting data with "." because mysql read "."
        $Energy2       = floatval(str_replace(",",".",$Data[2]));
        $Temperature        = floatval(str_replace(",",".",$Data[3]));
        $Humidity        = floatval(str_replace(",",".",$Data[4]));
        $Rain    = floatval(str_replace(",",".",$Data[5]));
        $Radiation    = floatval(str_replace(",",".",$Data[6]));


        //INSERT INTO `weatherstation` (`TIMESTAMP`, `RECORD`, `PTemp`, `batt_volt`, `ML01_Rad`, `ML02_Rad`, `TempContact1`, `TempContact2`, `Rain`, `AirTemperature`, `RelHumidity`, `WindSpeed`, `WindDirection`, `RelAirPressure`, `GlobalRadiation`) VALUES ('2020-07-01 00:00:00', '33', '0.1', '0.12', '0.42', '0.452', '0.452', '0.45', '0.45', '0.452', '0.42', '0.45', '0.45', '0.45', '0.452');

        //check duplication
        $duplication = 0;//boolean to know if they are duplication

        $checkTimestamp = "SELECT TIMESTAMP FROM weatherstation2 ORDER BY TIMESTAMP desc";
        $query = mysqli_query($conn, $checkTimestamp) or die (mysqli_error($conn));

        while ($row = $query->fetch_assoc())
        {
          if($row["TIMESTAMP"] == $TIMESTAMP)
          {
            $duplication = 1;// 1 = duplication trouvé
            $dupliCount++;
          }
        }


        //query to insert the csv data into the database if they are not duplication
        if($duplication == 0)//il n'y a pas de duplication on insert les données
        {

          //sql query to insert data into the databank
          $sql = "INSERT INTO weatherstation(TIMESTAMP, Energy1, Energy2, Temperature, Humidity, Rain, Radiation)
                  VALUES('$TIMESTAMP', '$Energy1', '$Energy2', '$Temperature', '$Humidity', '$Rain', '$Radiation')";

          //echo $sql;//print the sql command
          echo "-";
          ini_set('max_execution_time', 0);
          $result = mysqli_query($conn, $sql);
          if(!$result)
          {
            echo "query impossible ($sql);" . mysqli_error();
          }
          $dupliInsert++;
          //echo "<br>";

        }/*else {
          echo "duplication value \n";
          echo "<br>";
        }
        */ //test dupli

     }
     fclose($file);//close the file


     //message after importation
     echo "We insert $dupliInsert values into the database. <br>";
     echo "They are $dupliCount values in your file that are already in the database. <br>";
     echo "CSV File has been successfully Imported.<br>";



  }
  else {
      echo "Error: Please Upload only CSV File";

  }
  
  include 'tagProcessing.php';
}



echo "You will be redirect into the import section in 5sec.<br>"; //redirection
header("Refresh:5; url=Import.php");
?>
