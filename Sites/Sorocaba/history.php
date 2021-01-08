
<!-- HISTORY : in this page you will be able to see the history of the activity in the website and the country of the exportation-->

<?php require 'header.php' ?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>HISTORY</title>

  </head>

  <!--Banner of the history ppage-->
  <div class="bannerHistory">
    <h1> History </h1>
  </div>

  <body>


    <!-- two div, who works like button to select the table we want to display -->
    <div class="selectStat">
      <div class="Hdiv" onclick="historyDisplay()">
        <a onclick="historyDisplay()">User activity</a>
      </div>

      <div class="Cdiv" onclick="countryDisplay()">
        <a onclick="countryDisplay()">Export Country</a>
      </div>
    </div>




    <!-- user activity table -->
    <div class="history" id="history">

      <table class="tableau">

        <!--table head : Description-->
        <thead>
          <tr>
            <td>Name</td>
            <td>Time</td>
            <td>Action</td>
          </tr>
        </thead>

        <?php

        require 'db.php'; //call the database

        $query = "SELECT * FROM historyuser ORDER BY id desc";//query to read the weatherstation table
        $result = mysqli_query($conn, $query);
        if(!$result)
        {
          echo "query impossible ($query);" . mysqli_error();
        }



        while ($row = $result->fetch_assoc()) {
          //read the data
          $id = $row["id"];
          $Who = $row["Who"];
          $When = $row["TWhen"];
          $What = $row["What"];

          ?>
          <tbody>
            <tr>
              <!-- add data in the table -->
              <td><?php echo $Who ?></td>
              <td><?php echo $When ?></td>
              <td><?php echo $What ?></td>
            </tr>

          </tbody>

          <?php
        }
         ?>


      </table>

    </div>




    <!-- Export country table -->
    <div class="country" id="country">

      <table class="tableau">

        <!--table head : Description-->
        <thead>
          <tr>
            <td>Country</td>
            <td>Time</td>
          </tr>
        </thead>

      <?php

        $sql = "SELECT * FROM country";//query to read the country table
        $result = $conn->query($sql);


        if($result->num_rows > 0)
        {
          while ($row = $result->fetch_assoc()) {
            //read result of the query
            $CountryH = $row["Country"];
            $When2 = $row["Export_Time"];

            ?>
            <tbody>
              <tr>
                <!--put data into the table-->
                <td><?php echo $CountryH ?></td>
                <td><?php echo $When2 ?></td>
              </tr>
            </tbody>
            <?php


          }
        }

       ?>

       </table>
    </div>


  </body>

  <!-- fill the page-->
  <div class="vide">
  </div>

  <?php require 'footer.php'; ?>

</html>

<script>

  //script to display the country table
  function countryDisplay() {
    document.getElementById("history").style.display = "none";
    document.getElementById("country").style.display = "block";
  }

  //script to display the history table
  function historyDisplay() {
    document.getElementById("history").style.display = "block";
    document.getElementById("country").style.display = "none";
  }

</script>
