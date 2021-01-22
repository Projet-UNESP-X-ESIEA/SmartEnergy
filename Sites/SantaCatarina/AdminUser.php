
<!--AdminUser : this page is for the admin, admin is able change the password and the privilege level of all the member-->
<?php require 'header.php' ?>

<!--this page ONLY for the admin-->
<?php if ($sess == 1 && $_SESSION['level'] == 2): ?>

  <!DOCTYPE html>
  <html lang="en" dir="ltr">
    <head>
      <meta charset="utf-8">
      <title>ADMIN PANEL</title>
      <link rel="stylesheet" href="css/style.css">


    </head>
    <body>
	<div class="adminData">
    <table class="userTable">
        <thead>
          <tr>
            <td>
              <p>Name</p>
            </td>

            <td>
              <p>mail</p>
            </td>

            <td>
              <p>create_time</p>
            </td>

            <td>
              <p>privilege</p>
            </td>
          </tr>
        </thead>



        <?php


          require "db.php";//call the database
          $query = "SELECT * FROM user";
          $result = mysqli_query($conn, $query);
          $username = (isset($_SESSION['username'])) ? $_SESSION['username'] : '';

          echo "<br><br>privilege 1 = member <br>";
          echo "privilege 2 = admin <br>";

          while ($row = $result->fetch_assoc()) {

            $name = $row["username"];

            ?>

            <!-- Form of the admin panel-->
            <form class="changeAdmin" action="AdminUser.php" method="post">

            <tr>
              <td>

                <input type="radio" class="nameAdminRadio" name="names" value="<?php echo $name; ?>" checked>
                <?php echo $name; ?></input>
              </td>

              <td>
                <?php echo $row["email"]; ?>
              </td>

              <td>
                <?php echo $row["create_time"]; ?>
              </td>

              <td>
                <?php echo "    ".$row["privilege"];?>
              </td>


            </tr>

            <tr>
            </tr>



            <?php
            echo "<br>";
          }
       ?>
    </table>
	
	<br><br>

      <!-- form to changing privilege or change password-->
	  
      <button type="submit" name="changePrivilege">Change Privilege</button>

      <input type="password" class="input-box" name="mdp" placeholder="New password">
      <input type="password" class="input-box" name="mdp2" placeholder="Confirm password">

      <button type="submit" name="changePassword">Change Password</button>
	  </div>


    </form>



    </body>
  </html>


  <?php
  if (isset($_POST["changePrivilege"]))
  {
    $names = $_POST["names"];
    $privi = 0;

    $priviQuery = "SELECT privilege FROM user WHERE username = '$names'";//check the privilege level of the select user
    $resprivi = mysqli_query($conn,$priviQuery);
    while($row = $resprivi->fetch_assoc()){
      $privi = $row["privilege"];
    };

    if ($privi == 2) {
      $changeQuery = "UPDATE user SET privilege = '1' WHERE username = '$names'"; //if the user us admin, we change him to member

      echo "<br> $names is now just a member <br>";

    }elseif ($privi == 1) {
      $changeQuery = "UPDATE user SET privilege = '2' WHERE username = '$names'";//if the user us member, we change him to admin

      echo "<br> $names is now a admin <br>";
    }

    $res = mysqli_query($conn,$changeQuery);//start the rquery


  }

  //change the password
  if (isset($_POST["changePassword"]))
  {
    $names = $_POST["names"];

    if(!empty($_POST["mdp"])){

      $mdp = $_POST["mdp"];
      $mdp2 = $_POST["mdp2"];

      if($mdp == $mdp2){//if the password and confirm password correspond
        $mdpchiffre = hash("sha256", $names."monsalage".$mdp);
        $changeQuery = "UPDATE user SET password = '$mdpchiffre' WHERE username = '$names'";
        $res = mysqli_query($conn,$changeQuery);
        echo "you have change the password of $names<br>";
      }else {
        echo " password do not correspond ";
      }

    }else {
      echo "empty";
    }

  }



   ?>

   <?php require 'footer.php' ?>
<?php else :

    echo "Sorry this page is only for admin";

    endif?>
