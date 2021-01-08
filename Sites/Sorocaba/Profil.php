
<?php require 'header.php' ?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>PROFILE</title>

  </head>

  <div class="banner">
    <h1> PROFIL </h1>
  </div>

  <body>

    <?php
    if ($sess == 1) {
      ?>
      <p id="message"> Welcome <?php echo $_SESSION['username'];?> </p>
      <p id="message"> Your mail is <?php echo $_SESSION['email'];?> </p>
      <p id="message"> You subscribe the <?php echo $_SESSION['create_time'];?> </p>
      <p id="message"> Your privilege level is  <?php echo $_SESSION['level'];?> </p>

      <form method="post" class="DecoF" action="deco.php" enctype="multipart/form-data">
        <button type="submit" name="decoButton" >Disconnect</button>
      </form>

      <?php if($_SESSION['level'] == 2){
      ?>
        <p> You can go to the admin panel by clicking <a href="AdminUser.php">here</a></p>
      <?php
      }
      ?>

    <?php
    }
     ?>


  </body>

  <div class="vide">

  </div>

  <?php require 'footer.php'; ?>

</html>
