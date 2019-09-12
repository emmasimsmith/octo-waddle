<?php
//include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

//get event ID
$event_id = $_GET['event_id'];

//select all activities for the form
$sql = "SELECT * FROM regattascoring.ACTIVITY;";
$result = mysqli_query($conn, $sql);

//create dropdown form
?>
<html>
  <head>
    <title>Race Enrolment</title>
  </head>
    <h1>Race Enrolment</h1>
  <body>
    <form action = <?php echo "inputrace_enrollment.php?id=" . $event_id ?> method='POST'>
      Activity:
      <input type="text" name="activity_name" list="activity_list">
      <datalist id="activity_list">
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='". $row['activity_name'] . "'>" . $row['activity_name'] . "</option>";
        } ?>
      </datalist>
      <br>
      <button type="submit" name="submit" >Enter</button>
    </form>
  </body>
</html>
