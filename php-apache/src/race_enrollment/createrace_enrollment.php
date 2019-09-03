<?php
//include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

//select all activities for the form
$sql = "SELECT * FROM regattascoring.ACTIVITY NATURAL JOIN regattascoring.CLASS;";
$result = mysqli_query($conn, $sql);

//create dropdown form
?>
<html>
  <head>
    <title>Race Enrolment</title>
  </head>
    <h1>Race Enrolment</h1>
  <body>
    <form action ="createrace_enrollment.php" method='POST'>
      <label for="activity_name">Activity:e</label>
      <input type="text" name="activity_name" id="activity_name" list="activity_list">
      <datalist id="activity_list">
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['class_name'] . " " . $row['activity_name'] . "'></option>";
        } ?>
      </datalist>
      <br>
      <button type="submit" name="submit" >Enter</button>
    </form>
  </body>
</html>
