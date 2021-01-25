<?php require 'db.php' //appel de la base de donnée?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Connection</title>
    <link rel="stylesheet" href="css/styleRegister.css">

    <?php require 'header.php' ?>

  </head>
  <body>

    <div class="Connection">
      <h1> Connection </h1>
      <form method="POST" action="Connection.php">
        <input type="text" class="input-box" name="pseudo" placeholder="Pseudo">
        <input type="password" class="input-box" name="mdp" placeholder="Password">


  <?php


  if(isset($_POST['validate']))
  {

    $pseudo = $_POST['pseudo'];
    $mdp = $_POST['mdp'];

    //if they are pseudo and password
    if($pseudo && $mdp)
    {

      $mdpchiffre = hash("sha256", $pseudo."monsalage".$mdp);

      $sql = "SELECT * FROM user WHERE username = '$pseudo' && password = '$mdpchiffre'";

      $result = $conn->query($sql);
      if(!$result){
        echo "query impossible ($sql);" . mysqli_error();
        exit;
      }

      $userexist = $result->num_rows;


      //create variable for name, mail , create time and password
      if($result->num_rows > 0)
      {
        while ($row = $result->fetch_assoc()) {
          echo "id: " . $row["idUser"]. " - Name: " . $row["username"]. " " . $row["email"] . " " . $row["password"];
          $_SESSION['email'] = $row["email"];
          $_SESSION['create_time'] = $row["create_time"];
          $_SESSION['level'] = $row["privilege"];
        }
      }

      //if the user exist
      if($userexist==1)
      {


        $_SESSION['username'] = $pseudo;//we create the variable pseudo

        //rajout de l'historique de connection
        $sql2 = "INSERT INTO `historyuser` (`id`, `Who`, `TWhen`, `What`) VALUES (NULL, '$pseudo', CURRENT_TIMESTAMP, 'CONNECTION');";

        $result2 = $conn->query($sql2);
        if(!$result2){
          echo "query impossible ($sql2);" . mysqli_error();
          exit;
        }


        header("location:Home.php");
        //header("location:session.php");



      }else{
          ?>
           <p id="errormessage">Error pseudo or password<br></p>
         <?php
      };

    }else{
        ?>
         <p id="errormessage">Please complete all fields<br></p>
       <?php
    };

  }

  ?>

  <button type="submit" class="btn-validate" name="validate">Log In</button>

  <p><a href="oublier.php">Forgot password?</a> </p>
  <p>Not a member yet? <a href="Subscribe.php">Register now</a> </p>

  </form>
</div>
</body>

<?php require 'footer.php'; ?>

</html>
