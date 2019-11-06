<?php
//include functions and connection php files
include_once '../connection.php';
include_once '../functions.php';

//GET and POST variables
$event_id = $_GET['event_id'];
$activity_name = $_POST['activity_name'];

//select activity where matches POST
$sql = "SELECT * FROM regattascoring.ACTIVITY WHERE activity_name = '$activity_name';";
$result = mysqli_query($conn, $sql);

//find if activity is class or unit based
$row = mysqli_fetch_assoc($result);

//define activity id
$activity_id = $row['activity_id'];

//check matches with activity_name
if (mysqli_num_rows($result) == 0) {
    include_once '../navbar.php';
    echo '<html>
      <head>
        <title>Enrolment</title>
        <link rel="stylesheet" type="text/css" href="../stylesheets/navbarstyle.css">
        <link rel="stylesheet" type="text/css" href="../stylesheets/pagestyle.css">
      </head>
      <div class="container">
      <div class="content">
        <body>';
    close($conn, "Please select a valid activity", "race_enrolment", "Race Enrolments");
    exit;
//if activity bracket is class, make user select class
} elseif ($row['activity_bracket'] == 'class') {
    //'];include navbar
    include_once '../navbar.php'; ?>
    <html>
      <head>
        <title>Enrolment</title>
        <link rel="stylesheet" type="text/css" href="../stylesheets/navbarstyle.css">
        <link rel="stylesheet" type="text/css" href="../stylesheets/pagestyle.css">
      </head>
    <div class='container'>
      <div class='content'>
        <body>
          <h1>Race Enrolment</h1>
          <ul class="labels">
            <li>Select <?php echo $activity_name?> Class:</li>
          </ul>
          <form action = <?php echo "redirectenrolment.php?event_id=$event_id&activity_id=$activity_id"?> method='POST'>
            <div class="inside-form">
              <select name='class_id'> <?php
          $sql = "SELECT * FROM regattascoring.BRACKET NATURAL JOIN regattascoring.CLASS WHERE activity_id = $activity_id;";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value=" . $row['class_id'] .">" . $row['class_name'] . "</option><br>";
    } ?>
              </select>
            </div>
            <div class="button">
              <button type="submit" name="submit">Enter</button>
            </div>
          </form>
        </body>
        <div class="close">
          <ul>
            <li><a href='/'>Return Home</a></li>
            <li><a href="enrolment.php?event_id=<?php echo $event_id ?>">Select Activity</a></li>
            <li><a href="../indexselectedevent.php?event_id=<?php echo $event_id ?>">Return to Event Page</a></li>
          </ul>
        </div>
      </div>
    </div>
  <?php
} else {
        header("Location: redirectenrolment.php?event_id=$event_id&activity_id=$activity_id");
    }
