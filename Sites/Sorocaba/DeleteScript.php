<?php


if (isset($_POST["delete"]))
{


  require 'db.php'; //call the database

//____________________put the Delete into the history_______________________________________________________////////
  session_start();
  $username = (isset($_SESSION['username'])) ? $_SESSION['username'] : 'no name';

  //rajout de l'historique de connection
  $histdel = "INSERT INTO `historyuser` (`id`, `Who`, `TWhen`, `What`) VALUES (NULL, '$username', CURRENT_TIMESTAMP, 'DELETE');";

  $resultHistdel = $conn->query($histdel);
  if(!$resultHistdel){
    echo "query impossible ($resultHistdel);" . mysqli_error();
    exit;
  }

//_____________________________________________________________________________________________________////////

  //IMPORTANT/// I use weatherstation2 for testing, change to weatherstation to work on the main table
  $queryDelete = "DELETE FROM weatherstation";//query to read the weatherstation table
  $countDelete = "SELECT count(*) AS nb FROM weatherstation";//query to read the weatherstation table



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

  //bool pour savoir si ya
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

//addition of filters

  if (!($filter1 == "") ||  !($filter2 == "") || !($filter3 == "      )")) //il n'y a pas de filtre1 vide ou filtre 2 vide ou filtre 3 vide
  {
    if($filter3 == "      )"){//on enleve la parenthese en trop si il n'y a pas de filtre 3
      $filter3 = "";
    }
    $queryDelete = "$queryDelete WHERE $filter1 $filter2 $filter3";
    $countDelete = "$countDelete WHERE $filter1 $filter2 $filter3";


  }

  //echo "<br>" .$queryDelete . "<br>"; //test
  //echo "<br>" .$countDelete . "<br>"; //test

  $ResultCount = mysqli_query($conn, $countDelete);
  if(!$ResultCount)
  {
    echo "query impossible ($countDelete);" . mysqli_error();
  }

  //print the count of delete row
  $row = $ResultCount->fetch_assoc();
  echo "you have delete ".$row['nb']." rows.";
  echo "<br>You will be redirect to the Delete page in 5sec";
  header("Refresh:5; url=Delete.php");



  //delete data
  $resultDelete = mysqli_query($conn, $queryDelete);
  if(!$resultDelete)
  {
    echo "query impossible ($queryDelete);" . mysqli_error();
  }




}

?>
