

<?php

//disconnect the user if button disconect is pushed
if (isset($_POST['decoButton'])) {
  session_start();
  session_destroy();
  header("location:Home.php");
}
?>
