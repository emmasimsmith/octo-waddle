<?php
//include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

//GET event id
$event_id = $_GET['event_id'];

//define POST variables
$activity_name = $_POST['activity_name'];

//select activity where matches POST
$sql = "SELECT * FROM regattascoring.ACTIVITY WHERE activity_name = '$activity_name';";
$result = mysqli_query($conn, $sql);

//check matches with activity_name
if (!$result) {
    close($conn, "Not a valid activity", "race_enrolment", "Race Enrolments");
    exit;
}

//find if activity is class or unit based
$row = mysqli_fetch_assoc($result);

//define activity id
$activity_id = $row['activity_id'];

//if activity bracket is class, make user select class
if ($row['activity_bracket'] == 'class') {
    ?>
  <html>
    <head>
      <title>Race Enrolment</title>
    </head>
      <h1>Race Enrolment</h1>
    <body>
      <form action = <?php echo "inputrace_enrolment.php?id=$event_id&activity_id=$activity_id method='POST'>";
    echo "Select $activity_name Class:" ?>
        <select name="class">
          <?php
          $sql = "SELECT * FROM regattascoring.BRACKET NATURAL JOIN regattascoring.CLASS WHERE activity_id = $activity_id;";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value=" . $row['class_id'] .">" . $row['class_name'] . "</option><br>";
    } ?>
        </select>
        <br>
        <button type="submit" name="submit">Enter</button>
      </form>
    </body>
  </html>
  <?php
} else {
        //TODO make thing if activity bracket is unit
    }
