<?php header("Cache-Control:no-cache");?>

<!-- header of the website -->


<link rel="stylesheet" href="css/style.css" >

<?php session_start();?>
<?php
  $username = (isset($_SESSION['username'])) ? $_SESSION['username'] : '| Connection |';
  //sess bool to check if the connection exist
  if ($username == '| Connection |') {
    $sess = 0;//session is close
  }else {
    $sess = 1;//session is open
  }
?>

<div class="navbar">

    <ul class="topnav">

      <a href="Home.php"> <img class="logo" src="img/logo2.png" alt="logo" ;/></a>

      <li><a href="Export.php">EXPORT</a></li>

      <li><a href="Import.php">IMPORT</a></li>

      <li><a href="Delete.php">DELETE</a></li>

      <li><a href="history.php">HISTORY</a></li>

      <li><a href="TheProject.php">THE PROJECT</a></li>

      <li><a href="ContactUs.php">CONTACT US</a></li>

      <?php
      if ($sess == 0)//if session close
      {
        ?>
        <li><a class="connection-Btn" href="Connection.php"> | Connection |</a></li>
        <?php
      }else {//if session open
        ?>
        <li><a class="Profil-Btn" href="Profil.php"> <?php echo '| '.$username.' |'?></a></li>
        <?php
      }
       ?>



    </ul>

</div>
