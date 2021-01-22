
<!DOCTYPE html>
<html lang="eng" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>CONTACT US</title>

    <link rel="stylesheet" href="css/style.css">

    <?php require 'header.php' ?>


  </head>

  <div class="bannerContactUS">
    <h1> CONTACT US </h1>
  </div>

  <body>

    <div class="containerContactUs">
      <div class="textContact">
        <p>
          If you have some question you can contact us by mail. <br>

          Professor Antonio Martins : antonio.martins@unesp.br <br>

          Alexandre Ter : ter@et.esiea.fr <br>

          If you want us to nototify you about new content you can leave you email for futur contact here. <br>

        </p>

        <form class="ContactUSForm"  action="ContactUsScript.php" method="post">
          <input type="email" class="mail-contactUSbox" name="mailUsr" placeholder="your mail">
          <button type="submit" name="send" class="btn-submit" >SEND </button>

        </form>
      </div>

    </div>

  </body>


 <?php require 'footer.php'; ?>

</html>
