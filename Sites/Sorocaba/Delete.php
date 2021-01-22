
<!DOCTYPE html>
<html lang="eng" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>DELETE</title>

    <link rel="stylesheet" href="css/style.css">

    <?php require 'header.php' ?>

  </head>

  <div class="bannerDelete">
    <h1> DELETE </h1>
  </div>

  <body>

    <div class="Description">
      <p class="dataDes">In this section you can delete data of the weatherstation databank by choosing a interval between two dates.
        <br>Optionnaly you can add more filters, like interval between time or days filters.  <br><br>
        <b>Warning</b> : the data will be deleted permanently.
      </p>

    </div>



    <div class="CountainerDeleteForm">



      <form method="post" class="dataForm" action="DeleteScript.php" enctype="multipart/form-data">

      <!-- date filter -->
         <p class="FormDesc"> Select Date </p>
         <p class="FormDesc"> From <input type="date" id="datetime1" name="Plage1" required>
             to <input type="date" id="datetime2" name="Plage2" required> </p>

      <!-- button "others" to show other filters -->
        <input id="how-other" name="how" type="checkbox">
        <label for="how-other" class="side-label">Other filters...</label>

        <div class = "more-Filters">

      <!-- hours filter -->
         <p class="FormDesc"> Select Time </p>
         <p> From <input type="time" id="time1" name="hour1" min="00:00" max="23:59">
             to <input type="time" id="time2" name="hour2" min="00:00" max="23:59"> </p>

      <!-- days filter -->
         <p class="FormDesc"> Select Days Filter </p>

         <p class=days>
           <input type="checkbox" class="day" id="Everyday" name="Everyday" label="everyday">
           <label for="Everyday">Everyday</label>
         </p>
         <p>
           <input type="checkbox" class="day" id="Monday" name="Monday">
           <label for="Monday">Monday</label>
         </p>
         <p class=days>
           <input type="checkbox" class="day" id="Tuesday" name="Tuesday">
           <label for="Tuesday">Tuesday</label>
         </p>
         <p class=days>
           <input type="checkbox" class="day" id="Wednesday" name="Wednesday">
           <label for="Wednesday">Wednesday</label>
         </p>
         <p class=days>
           <input type="checkbox" class="day" id="Thursday" name="Thursday">
           <label for="Thursday">Thursday</label>
         </p>
         <p class=days>
           <input type="checkbox" class="day" id="Friday" name="Friday">
           <label for="Friday">Friday</label>
         </p>
         <p class=days>
           <input type="checkbox" class="day" id="Saturday" name="Saturday">
           <label for="Saturday">Saturday</label>
         </p>
         <p class=days>
           <input type="checkbox" class="day" id="Sunday" name="Sunday">
           <label for="Sunday">Sunday</label>
         </p>

      <!-- weather filter -->
         <p class="FormDesc"> Select Weather Filter </p>
         <input type="checkbox" class="weather" name="Sunny">
         <label for="Sunny">Sunny</label>
         <input type="checkbox" class="weather" name="Rainy">
         <label for="Rainy">Rainy</label>
         <input type="checkbox" class="weather" name="Cloudy">
         <label for="Cloudy">Cloudy</label>



         <p></p>
         <p></p>

        </div>

      <!-- validation button -->
      <div class="block" id="block"> </div>
      <?php if ($sess == 0 || $_SESSION['level'] != 2) {
      ?>
        <br>
        <button type="button" class="btn-validate" onclick="ConfirmDelete()" name="delete" >DELETE</button>
        <p class="nope">You need to be admin to delete data !</p>

      <?php
    }elseif ($sess == 1 && $_SESSION['level'] == 2) {
      ?>
        <br>
        <button type="button" class="btn-validate" onclick="openConfirmation()" name="delete" >DELETE</button>

        <div class="confirmDeletePopup" id="confirmDeletePopup">
          <p><b class="nope">WARNING</b> : The Data will be permanently deleted !</p>
          <p>Do you comfirm the deletion?</p>
          <button type="submit" class="btn-validate" onclick="ConfirmDelete()" name="delete" >YES DELETE</button>
          <button type="button" class="btn-validate" onclick="closeConfirmation()" name="delete" >CANCEL</button>

        </div>
      <?php
      }
      ?>


      </form>


    </div>


  </body>

  <?php require 'footer.php'; ?>
</html>




<script>

  function openConfirmation() {

    document.getElementById("block").style.display = "block";
    document.getElementById("confirmDeletePopup").style.display = "block";

  }

  function closeConfirmation() {
    document.getElementById("block").style.display = "none";
    document.getElementById("confirmDeletePopup").style.display = "none";

  }


</script>
