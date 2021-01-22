<?php

if(isset($_POST["send"]))
{
  require "db.php";

  //we add the email into the databank

  $mailCU = $_POST["mailUsr"];

  if(filter_var($mailCU, FILTER_VALIDATE_EMAIL)){
    $req = "INSERT INTO `mail` (`ID_mail`, `mail`) VALUES (NULL, '$mailCU')";
    $res = mysqli_query($conn,$req);
    echo "We added your email $mailCU to our databse ! <br>";
    echo "We will notify you about news and uptdate of our website <br> ";
  }else {
    echo "ERROR : Invalid Email <br>";
  }

}

  header( "Refresh:8; url=ContactUS.php");
  echo "You'll be redirected in about 5 secs. If not, click <a href=\"ContactUsScript.php\">here</a>.";

 ?>
