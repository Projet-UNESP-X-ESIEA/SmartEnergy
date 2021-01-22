<?php

require 'db.php' //call the sql database?>

<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Form</title>
    <link rel="stylesheet" href="css/styleRegister.css">

    <?php require 'header.php' ?>

  </head>
  <body>

    <!-- Subscribe form-->
    <div class="Subscribe">
      <h1> Subscribe </h1>
      <form method="POST" action="Subscribe.php">
        <input type="text" class="input-box" name="pseudo" placeholder="Pseudo">
        <input type="email" class="input-box" name="mail" placeholder="e-mail adress">
        <input type="password" class="input-box" name="mdp" placeholder="Password">
        <input type="password" class="input-box" name="mdp2" placeholder="Confirm password">
        <p><span><input type="checkbox" name="check"></span> I agree to check this box </p>


<?php

//when we push the validate button
if(isset($_POST['validate']))
{
  $pseudo = htmlspecialchars(trim($_POST['pseudo']));
  $mail = htmlspecialchars(trim($_POST['mail']));
  $mdp = htmlspecialchars(trim($_POST['mdp']));
  $mdp2 = htmlspecialchars(trim($_POST['mdp2']));
  $mdpchiffre = hash("sha256", $pseudo."monsalage".$mdp); //encrypt the password

  //test correct entries
  if($pseudo&&$mail&&$mdp&&$mdp2)
  {

    /*test to know if the username is already use version 1
    $req = $bdd->prepare("SELECT * FROM users WHERE pseudo='$pseudo'");
    $reg->execute();
    $rows = $reg->rowCount();*/

    //test to know if the username is already use
    $req = "SELECT * FROM user WHERE username = '$pseudo'";
    $res = $conn->query($req);
    if(!$res){
        echo "query user exist impossible ($req);" . mysqli_error();
        exit;
      }
    $userexist = $res->num_rows;
    //echo $userexist;

    /*test to know if the mail is already use
    $reg2 = $bdd->prepare("SELECT * FROM users WHERE mail='$mail'");
    $reg2->execute();
    $rows2 = $reg2->rowCount();
    */

    $req2 = "SELECT * FROM user WHERE email = '$mail'";
    $res2 = $conn->query($req2);
    if(!$res2){
        echo "query mail exist impossible ($req2);" . mysqli_error();
        exit;
      }
    $mailexist = $res2->num_rows;
    //echo $mailexist;


      if (isset($_POST['check']))//test if the checkbox is check
      {
          if($userexist == 0)//test username already use
          {

            if($mailexist == 0)
            {
              //test password = confirmation password
              if($mdp==$mdp2)
              {

                $req3 = "INSERT INTO `user` (`idUser`, `username`, `email`, `password`, `create_time`, `privilege`) VALUES (NULL, '$pseudo', '$mail', '$mdpchiffre', CURRENT_TIMESTAMP, '1');";
                $res3 = $conn->query($req3);
                if(!$res3){
                    echo "query INSERT impossible ($req3);".mysqli_error();
                    exit;
                  }
				else{
					//if(mail($mail, "Registration", "You have been succesfully registered to UNESP weatherstation data"))
					//{						
					echo '<script type="text/javascript">alert("You have been added to subscription");</script>';
					//}					
				}

                //header("location:connexion.php");//redirection sur connexion.php




              }else{
                ?>
                 <p id="errormessage">The two password do not correspond !<br></p>
               <?php
              };
            }else{
                ?>
                 <p id="errormessage">This mail is already use, please choose another one<br></p>
               <?php
            }

          }else{
            ?>
             <p id="errormessage">This username is already use, please choose another one<br></p>
           <?php
          };
      }
      else
      {

           ?>
            <p id="errormessage">You need to check the box<br></p>
          <?php
      }

  }else{
     ?>
      <p id="errormessage"> Please enter all fields <br></p>
    <?php
  }

}
 ?>

 <button type="submit" class="btn-validate" name="validate">Subscribe</button>

 <p>Already a member? <a href="Connection.php">Login now</a> </p>

  </form>
  </div>
  </body>

  <?php require 'footer.php'; ?>

 </html>
