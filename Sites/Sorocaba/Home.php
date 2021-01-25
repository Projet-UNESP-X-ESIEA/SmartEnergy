<!--Homepage-->
<!DOCTYPE html>
<html lang="eng" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>HOME</title>

    <link rel="stylesheet" href="css/style.css" >

    <?php require "header.php" ?>

  </head>

  <!--banner : image + text-->
  <div class="bannerHome">
    <h1> DATA FOR ENERGY PRODUCTION </h1>
  </div>

  <body>

    <!--Description-->
    <div class="containerHome1">
      <p class="homeDescription"> This website is a tool to collect data from a meteorological station locate in UNESP, Sorocaba Brazil for analysis and other projects. </p>
    </div>


    <div class="datainfo">

      <div>
        <h1 class="dataDes">THE DATA</h1>
        <p class="dataDes">
          This is data from the meteorological station of UNESP, locate in Sorocaba Brasil 23.47906125 South and 47.41717289 West.
          <br>
          <br>
          Every minute, this station give measurement of :<br><br>

          -	Temperature in degree Celsius. <br>

          -	Batterie Level in Volts, that keep the station working. <br>

          -	Radiation ML01 in W/m^2 (efficient close to a solar panel) <br>

          -	Radiation ML02 in umol/s/m^2 <br>

          -	Rain in mm <br>

          -	Temperature of the panel in Deg C <br>

          -	Air Temperature in Deg C <br>

          -	Air humidity in % <br>

          -	Wind Speed in m/s <br>

          -	Wind Direction in Degree <br>

          -	Air Pressure <br>

          -	Global radiation in W/m^2 <br>

          <br>
          In this website you will be able to export the data into a csv file.
          And you can Import or delete data if you have admin right.

        </p>
      </div>
      <div class="WeatherIMG">
      </div>

    </div>

    <div class="imgDiv">
      <div class="imgAndButton">
        <a href="Data.php"> <img class="homeImg" src="img/Sun.png"> </a>
        <p> EXPORT </p>
      </div>
      <div class="imgAndButton">
        <a href="Import.php"> <img class="homeImg" src="img/Cloudy.png"> </a>
        <p> IMPORT </p>
      </div>
      <div class="imgAndButton">
        <a href="Delete.php"> <img class="homeImg" src="img/Rainy.png"> </a>
        <p> DELETE </p>
      </div>

    </div>

    <div class="titleWeatherHome">
      <h1>WEATHER FORECAST</h1>
      <p>This is the weather forecast from <a href=https://www.cptec.inpe.br/sp/sorocaba#>cptec</a> website.</p>
      <p>We can see the weather from today and the prevision for the next 5 days.</p>
    </div>

    <div class="websiteImport">


      <?php require 'DataFromCptec.php'; ?>



      <!-- TODAY prevision-->
      <div class="today">


        <?php if($tmp == 0){ ?>
        <h1> TODAY </h1>
        <p> <?php echo $today[0];//date?> </p>
        <img> <?php echo $today[1];//img?> </img>
        <p> Temp Max : <?php echo $today[2];//tempMax?> </p>
        <p> Temp Min :<?php echo $today[3];//tempMin?> </p>
        <p> Humidity :<?php echo $today[4];//Humidity?> </p>
        <p> UV :<?php echo $today[5];//UV?> </p>
        <?php } ?>

        <?php if($tmp == 1){ ?>
        <h1> TODAY </h1>
        <p> <?php echo $today[0];//date?> </p>
        <p> Afternoon <img> <?php echo $today[1];//img?> </img> </p>
        <p> Night <img> <?php echo $today[2];//img?> </img> </p>
        <p> Temp Max : <?php echo $today[3];//tempMax?> </p>
        <p> Temp Min :<?php echo $today[4];//tempMin?> </p>
        <p> Humidity :<?php echo $today[5];//Humidity?> </p>
        <p> UV :<?php echo $today[6];//UV?> </p>
        <?php } ?>

        <?php if($tmp == 2){ ?>
        <h1> TODAY </h1>
        <p> <?php echo $today[0];//date?> </p>
        <p> Morning <img> <?php echo $today[1];//img?> </img> </p>
        <p> Afternoon <img> <?php echo $today[2];//img?> </img> </p>
        <p> Night <img> <?php echo $today[3];//img?> </img> </p>
        <p> Temp Max : <?php echo $today[4];//tempMax?> </p>
        <p> Temp Min :<?php echo $today[5];//tempMin?> </p>
        <p> Humidity :<?php echo $today[6];//Humidity?> </p>
        <p> UV :<?php echo $today[7];//UV?> </p>
        <?php } ?>


      </div>


    <!-- 5 other days prevision-->
    <div class="prevision">
      <h1> PREVISION </h1>


      <div class="prev1">
        <?php echo $prevision1[0]; ?>
        <?php echo $prevision1[1]; ?>
		<p>Temp max : <?php echo $prevision1[3]; ?>&deg</p>
        <p>Temp min : <?php echo $prevision1[2]; ?>&deg</p>
        <p>Humidity : <?php echo $prevision1[4]; ?> </p>
        <p>UV : <?php echo $prevision1[5]; ?></p>

      </div>

      <div class="prev2">
        <?php echo $prevision2[0]; ?>
        <?php echo $prevision2[1]; ?>
		<p>Temp max : <?php echo $prevision2[3]; ?>&deg</p>
        <p>Temp min : <?php echo $prevision2[2]; ?>&deg</p>
        <p>Humidity : <?php echo $prevision2[4]; ?> </p>
        <p>UV : <?php echo $prevision2[5]; ?></p>
      </div>

      <div class="prev3">
        <?php echo $prevision3[0]; ?>
        <?php echo $prevision3[1]; ?>
		<p>Temp max : <?php echo $prevision3[3]; ?>&deg</p>
        <p>Temp min : <?php echo $prevision3[2]; ?>&deg </p>
        <p>Humidity : <?php echo $prevision3[4]; ?> </p>
        <p>UV : <?php echo $prevision3[5]; ?></p>
      </div>

      <div class="prev4">
        <?php echo $prevision4[0]; ?>
        <?php echo $prevision4[1]; ?>
		<p>Temp max : <?php echo $prevision4[3]; ?>&deg</p>
        <p>Temp min : <?php echo $prevision4[2]; ?>&deg </p>
        <p>Humidity : <?php echo $prevision4[4]; ?> </p>
        <p>UV : <?php echo $prevision4[5]; ?></p>
      </div>

      <div class="prev5">
        <?php echo $prevision5[0]; ?>
        <?php echo $prevision5[1]; ?>
        <p>Temp max : <?php echo $prevision5[3]; ?>&deg</p>
		<p>Temp min : <?php echo $prevision5[2]; ?>&deg </p>
        <p>Humidity : <?php echo $prevision5[4]; ?> </p>
        <p>UV : <?php echo $prevision5[5]; ?></p>
      </div>

    </div>


  </div>

  </body>


  <?php require 'footer.php'; ?>

</html>
