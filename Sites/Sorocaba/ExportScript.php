<?php

if (isset($_POST["export"]))
{
  require 'db.php'; //call the database

//____________________put the export into the history_______________________________________________________////////
  session_start();
  $username = (isset($_SESSION['username'])) ? $_SESSION['username'] : 'no name';

  //rajout de l'historique de connection
  $histexp = "INSERT INTO `historyuser` (`id`, `Who`, `TWhen`, `What`) VALUES (NULL, '$username', CURRENT_TIMESTAMP, 'EXPORT');";

  $resultHistexp = $conn->query($histexp);
  if(!$resultHistexp){
    echo "query impossible ($resultHistexp);" . mysqli_error();
    exit;
  }

//_____________________________________________________________________________________________________////////

//__________________put the data of country into the database_______________//

  $country = $_POST["country"];
  $contryReq = "INSERT INTO `country` VALUES (NULL, '$country', CURRENT_TIMESTAMP);";
  $countryRes = $conn->query($contryReq);


//_____________________________________________________________________________________________________////////



//________________________________make the export form work_______________________________________________////////



  //regarder si le fichier a un nom
  if(!empty($_POST['nameFile'])){
    $namefile = $_POST['nameFile'].".csv";
  }else {
    $namefile = "File.csv";
  }

  // make a download file
  //header("Content-type: text/csv");
  //header("Content-Disposition: attachment; filename=$namefile");
  //header("Pragma: no-cache");

  $queryExport = "SELECT * FROM weatherstation";//query to read the weatherstation table
  $countExport = "SELECT count(*) AS nb FROM weatherstation";//query to read the weatherstation table


  //filters variables initialization
  $filter1 = "";
  $filter2 = "";
  $filter3 = "";

  $and3 = 0;

  $Monday = "";
  $Tuesday = "";
  $Wednesday = "";
  $Thursday = "";
  $Friday = "";
  $Saturday = "";
  $Sunday = "";




//---------------------------------FILTERS----------------------------------------//

//date filters
  if((!empty($_POST["Plage1"])) && (!empty($_POST["Plage2"]))){
    $d1 = $_POST["Plage1"];
    $d2 =  $_POST["Plage2"];
    $filter1 = "(DATE(TIMESTAMP) BETWEEN '$d1' AND '$d2')";//query to read the weatherstation table with the date filters
  }

//hours filters
  if((!empty($_POST["hour1"])) && (!empty($_POST["hour2"]))){
    $h1 = $_POST["hour1"];
    $h2 =  $_POST["hour2"];

    //si il n'y a pas de filtre 1 on ecrit la requete sans AND
    if ($filter1 == "") {
      $filter2 = "(TIME(TIMESTAMP) BETWEEN '$h1' AND '$h2')";
    }else {
      $filter2 = "AND (TIME(TIMESTAMP) BETWEEN '$h1' AND '$h2')";//query to read the weatherstation table with the hour filters
    }

  }

  //bool pour savoir si ya plusieur filtre
  if ($filter1 == "" && $filter2 == "") {
    $and3 = 0;
  }else {
    $and3 = 1;
  }



  //days filters
  if(!empty($_POST["Monday"])){
    //test si il y a un filtre avant
    if ($and3 == 0) {//il n'y a pas de filtre
      $Monday = "(DAYNAME(TIMESTAMP) = 'Monday'";
    }else {//il y a un filtre on rajoute AND
      $Monday = "AND ((DAYNAME(TIMESTAMP) = 'Monday')";//")"
    }
    $and3 = 2;
  }
  if(!empty($_POST["Tuesday"])){
    //test si il y a un filtre avant
    if ($and3 == 0) {
      $Tuesday = "(DAYNAME(TIMESTAMP) = 'Tuesday'";
    }elseif($and3 == 1){//il y a un filtre selectionner on ajoute AND
      $Tuesday = "AND ((DAYNAME(TIMESTAMP) = 'Tuesday')";//")"//probleme de parenthèse avec mon editeur de texte
    }elseif($and3 == 2) {//il y a déja un jour selectionner on ajoute OR
      $Tuesday = "OR (DAYNAME(TIMESTAMP) = 'Tuesday')";
    }
    $and3 = 2;
  }
  if(!empty($_POST["Wednesday"])){
    if ($and3 == 0) {
      $Wednesday = "(DAYNAME(TIMESTAMP) = 'Wednesday'";
    }elseif($and3 == 1){
      $Wednesday = "AND ((DAYNAME(TIMESTAMP) = 'Wednesday')";//")"
    }elseif ($and3 == 2) {
      $Wednesday = "OR (DAYNAME(TIMESTAMP) = 'Wednesday')";
    }
    $and3 = 2;
  }
  if(!empty($_POST["Thursday"])){
    if ($and3 == 0) {
      $Thursday = "(DAYNAME(TIMESTAMP) = 'Thursday'";
    }elseif($and3 == 1){
      $Thursday = "AND ((DAYNAME(TIMESTAMP) = 'Thursday')";//")"
    }elseif ($and3 == 2) {
      $Thursday = "OR (DAYNAME(TIMESTAMP) = 'Thursday')";
    }
    $and3 = 2;
  }
  if(!empty($_POST["Friday"])){
    if ($and3 == 0) {
      $Friday = "(DAYNAME(TIMESTAMP) = 'Friday'";
    }elseif($and3 == 1){
      $Friday = "AND ((DAYNAME(TIMESTAMP) = 'Friday')";//")"
    }elseif ($and3 == 2) {
      $Friday = "OR (DAYNAME(TIMESTAMP) = 'Friday')";
    }
    $and3 = 2;
  }
  if(!empty($_POST["Saturday"])){
    if ($and3 == 0) {
      $Saturday = "(DAYNAME(TIMESTAMP) = 'Saturday'";
    }elseif($and3 == 1){
      $Saturday = "AND ((DAYNAME(TIMESTAMP) = 'Saturday')";//")"
    }elseif ($and3 == 2) {
      $Saturday = "OR (DAYNAME(TIMESTAMP) = 'Saturday')";
    }
    $and3 = 2;
  }
  if(!empty($_POST["Sunday"])){
    if ($and3 == 0) {
      $Sunday = "(DAYNAME(TIMESTAMP) = 'Sunday'";
    }elseif($and3 == 1){
      $Sunday = "AND ((DAYNAME(TIMESTAMP) = 'Sunday')";//")"
    }elseif ($and3 == 2) {
      $Sunday = "OR (DAYNAME(TIMESTAMP) = 'Sunday')";
    }

  }
  if(!empty($_POST["Everyday"])){
    //echo "Tout les jours";
  }else{
    $filter3 = "$Monday $Tuesday $Wednesday $Thursday $Friday $Saturday $Sunday)";//la parenthèse est importante, elle permet a mysql de lire le filtre des jours en une seule requete
  }


  //echo $filter1;//test
  //echo $filter2;
  //echo $filter3;
  
  
  
  //weather filters
  
  $filter4 = "";
  
  if(!empty($_POST["Rainy"])){
	$filter4 = " AND DATE(TIMESTAMP) = (SELECT date FROM dayfiltertags WHERE date = DATE(TIMESTAMP) AND tag='Rainy')";
	//SELECT DISTINCT DATE(TIMESTAMP) FROM weatherstation WHERE DATE(TIMESTAMP) = (SELECT date FROM dayfiltertags WHERE date = DATE(TIMESTAMP) AND tag="Rainy");	
  }
  
  if(!empty($_POST["Cloudy"])){
	$filter4 = " AND DATE(TIMESTAMP) = (SELECT date FROM dayfiltertags WHERE date = DATE(TIMESTAMP) AND tag='Cloudy')";	
  }
  
  if(!empty($_POST["Sunny"])){
	$filter4 = " AND DATE(TIMESTAMP) = (SELECT date FROM dayfiltertags WHERE date = DATE(TIMESTAMP) AND tag='Sunny')";	
  }


//addition of filters

  if (!($filter1 == "") ||  !($filter2 == "") || !($filter3 == "      )")) //il n'y a pas de filtre1 vide ou filtre 2 vide ou filtre 3 vide
  {
    if($filter3 == "      )"){//on enleve la parenthese en trop si il n'y a pas de filtre 3
      $filter3 = "";
    }
    $queryExport = "$queryExport WHERE $filter1 $filter2 $filter3";
    $countExport = "$countExport WHERE $filter1 $filter2 $filter3";
    //echo "<br>" .$queryExport . "<br>"; //test
  }
 
 
 
  if (!($filter4 == ""))
  {
	  $queryExport .= $filter4;
	  $countExport .= $filter4;
	  //echo "<br>" .$queryExport . "<br>"; //test
  }

$resultExport = mysqli_query($conn, $queryExport);
/*if(!$resultExport)
{
  echo "<script>alert($queryExport)</script>";
}
*/

//------------------- COUNT -----------------------------------//
$ResultCount = mysqli_query($conn, $countExport);
if(!$ResultCount)
{
  echo "query impossible ($countExport);" . mysqli_error();
}

//print the count of delete row
$row = $ResultCount->fetch_assoc();
if ($row['nb']==0)
{
echo "<script>alert(\"Sorry, there are no data available. Please try again later. \")</script>";
echo "Sorry, there are no data available with this filters. Please try again later or select other filters. <br> You will be redirect to the export page in 5sec";
header( "Refresh:5; url=Export.php");

}else {
  // make a download file
  header("Content-type: text/csv");
  header("Content-Disposition: attachment; filename=$namefile");
  header("Pragma: no-cache");


  //print the name of categories in the first line of the file
    if(!empty($_POST["case1"])){
      echo "TIMESTAMP;";
    }
    if(!empty($_POST["case2"])){
      echo "RECORD;";
    }
    if(!empty($_POST["case3"])){
      echo "Panel Temperature;";
    }
    if(!empty($_POST["case4"])){
      echo "Global radiation;";
    }
    if(!empty($_POST["case5"])){
      echo "Radiation ML01;";
    }
    if(!empty($_POST["case6"])){
      echo "Radiation ML02;";
    }
    if(!empty($_POST["case7"])){
      echo "Rain;";
    }
    if(!empty($_POST["case8"])){
      echo "Air Temperature;";
    }
    if(!empty($_POST["case9"])){
      echo "Wind Speed;";
    }
    if(!empty($_POST["case10"])){
      echo "Wind Direction;";
    }
    if(!empty($_POST["case11"])){
      echo "Air Pressure;";
    }
    echo "\n";

}











//---------------------------------FILTERS----------------------------------------//


if (!empty($_POST["dotcoma"])) {
  //echo $_POST["dotcoma"];


  if ($_POST["dotcoma"] == "dot") {
    //print selected data into the datasheet with dot
      while ($row = $resultExport->fetch_assoc()) {

        if(!empty($_POST["case1"])){
          echo $row["TIMESTAMP"] . ";";
        }
        if(!empty($_POST["case2"])){
          echo $row["RECORD"] . ";";
        }
        if(!empty($_POST["case3"])){
          echo $row["PTemp"] . ";";
        }
        if(!empty($_POST["case4"])){
          echo $row["GlobalRadiation"] . ";";
        }
        if(!empty($_POST["case5"])){
          echo $row["ML01_Rad"] . ";";
        }
        if(!empty($_POST["case6"])){
          echo $row["ML02_Rad"] . ";";
        }
        if(!empty($_POST["case7"])){
          echo $row["Rain"] . ";";
        }
        if(!empty($_POST["case8"])){
          echo $row["AirTemperature"] . ";";
        }
        if(!empty($_POST["case9"])){
          echo $row["WindSpeed"] . ";";
        }
        if(!empty($_POST["case10"])){
          echo $row["WindDirection"] . ";";
        }
        if(!empty($_POST["case11"])){
          echo $row["RelAirPressure"] . ";";
        }

        //echo "\n<br>";
        echo "\n";//retour a la ligne
      }

    }else{
        //print selected data into the datasheet with coma
$PTemp = "9";
          while ($row = $resultExport->fetch_assoc()) {

            $PTemp           = str_replace(".",",",$row["PTemp"]);//replace all the "." of floting data with ","
            $batt_volt       = str_replace(".",",",$row["GlobalRadiation"]);
            $ML01_Rad        = str_replace(".",",",$row["ML01_Rad"]);
            $ML02_Rad        = str_replace(".",",",$row["ML02_Rad"]);
            $Rain            = str_replace(".",",",$row["Rain"]);
            $AirTemperature  = str_replace(".",",",$row["AirTemperature"]);
            $WindSpeed       = str_replace(".",",",$row["WindSpeed"]);
            $WindDirection   = str_replace(".",",",$row["WindDirection"]);
            $RelAirPressure  = str_replace(".",",",$row["RelAirPressure"]);
            $GlobalRadiation = str_replace(".",",",$row["GlobalRadiation"]);

            if(!empty($_POST["case1"])){

              echo $row["TIMESTAMP"] . ";";
            }
            if(!empty($_POST["case2"])){
              echo $row["RECORD"] . ";";
            }
            if(!empty($_POST["case3"])){
              echo $PTemp . ";";
            }
            if(!empty($_POST["case4"])){
              echo $GlobalRadiation . ";";
            }
            if(!empty($_POST["case5"])){
              echo $ML01_Rad . ";";
            }
            if(!empty($_POST["case6"])){
              echo $ML02_Rad . ";";
            }
            if(!empty($_POST["case7"])){
              echo $Rain . ";";
            }
            if(!empty($_POST["case8"])){
              echo $AirTemperature . ";";
            }
            if(!empty($_POST["case9"])){
              echo $WindSpeed . ";";
            }
            if(!empty($_POST["case10"])){
              echo $WindDirection . ";";
            }
            if(!empty($_POST["case11"])){
              echo $RelAirPressure . ";";
            }

            //echo "\n<br>";
            echo "\n";//retour a la ligne

          }
      }
  }

}

?>
