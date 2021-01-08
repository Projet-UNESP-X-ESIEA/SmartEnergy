<?php header("Cache-Control:no-cache"); ?>



<!DOCTYPE html>
<html lang="eng" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>IMPORT</title>

    <link rel="stylesheet" href="css/style.css">

    <?php require 'header.php' ?>

  </head>

  <div class="bannerImport">
    <h1> IMPORT </h1>
  </div>

  <body>

      <div class="Description">
        <p class="dataDes">
          In this import section, you can import data from a csv file into the database.
          For this just select your csv file and clic on import.
          <br>
          <b>Warning</b> : your file need to have exactly the same dimension than the default one.
          <br>
          Default : TIMESTAMP;	Energy1; Energy2; Temperature; Humidity; Rain; Radiation;
        </p>
      </div>


      <div class="CountainerImportForm">
        <form enctype="multipart/form-data" action="ImportScript.php" method="post">
          <strong>Import CSV file</strong>
          <table>
          <tr><td><p class="importTitle">Select CSV File:</p></td></tr>
          <td><input type="file" name="file" id="file" accept=".csv" ></td>

          <tr>

            <?php if ($sess == 0 || $_SESSION['level'] != 2) {
            ?>
            <td>
              <p>You need to be a tier 2 member to import data into the databank</p>
              <input type="button" value="submit" onclick="popupImportFunction()" >

            </td>

            <?php
            }elseif ($sess == 1) {
            ?>
              <td><button type="submit" name="import" class="btn-submit" >submit</td>
            <?php
            }
            ?>

          </tr>
          </table>
        </form>
      </div>

  </body>

  <script>
    function popupImportFunction() {
      var txt;

      confirm("you must be an administrator to be able to import data into the databank");
    }
  </script>

  <?php require 'footer.php'; ?>
</html>
