<?php
//include connections
include_once '../../connection.php';

//define get variables
$activity_id = $_GET['activity_id'];
$event_id = $_GET['event_id'];
$class_id = $_GET['class_id'];
$boat_type = $_GET['boat_type'];

//TODO check that there are boats made
$sql = "SELECT * FROM regattascoring.BOAT WHERE boat_type ='$boat_type';";
$boats = mysqli_query($conn, $sql);
if (mysqli_num_rows($boats) == 0) {
    include_once '../../navbar.php'; ?>

    <html>
      <head>
        <title>Sailing</title>
        <link rel="stylesheet" type="text/css" href="../../stylesheets/navbarstyle.css">
        <link rel="stylesheet" type="text/css" href="../../stylesheets/pagestyle.css">
      </head>
      <div class='container'>
        <div class='content'>
          <body>
            <h1>Select Race</h1>
            <div class='message'>Create $boat_type boats first</div>
              <div class='close'>
                <ul>
                  <li><a href='/'>Return Home</a></li>
                  <li><a href='../enrolment.php?event_id=$event_id'>Select Activity</a></li>
                  <li><a href=../../indexselectedevent.php?event_id=$event_id>Return to Event Page</a></li>
                </ul>
              </div>
            </div>
          </div>
        <?php
    mysqli_close($conn);
    exit;
}

//select all from race enrolment for this sailing class
$sql = "SELECT DISTINCT race_number FROM regattascoring.RACE_ENROLMENT WHERE event_id = '$event_id'
 AND activity_id = '$activity_id' and class_id = $class_id;";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) != 0) {
    //include navigation bar
    include_once '../../navbar.php'; ?>
    <html>
      <head>
        <title>Sailing</title>
        <link rel="stylesheet" type="text/css" href="../../stylesheets/navbarstyle.css">
        <link rel="stylesheet" type="text/css" href="../../stylesheets/pagestyle.css">
      </head>
      <div class='container'>
        <div class='content'>
          <body>
            <h1>Select Race</h1>
      <?php

    //echo links to all races avaliable
    echo "<div class='message'>
            <ul>";
    while ($rows=mysqli_fetch_assoc($result)) {
        $race_number = $rows['race_number'];
        echo "<li><a href='editsailing.php?event_id=$event_id&activity_id=$activity_id&class_id=$class_id&race_number=$race_number&boat_type=$boat_type'>Race $race_number</a></li>";
    } ?>
    </ul> <br> or <br>
      <li><a href='sailing.php?event_id=$event_id&activity_id=$activity_id&class_id=$class_id&boat_type=$boat_type'>Create New Race</a></li></div>
    <div class='close'>
      <ul>
        <li><a href='/'>Return Home</a></li>
        <li><a href='../enrolment.php?event_id=$event_id'>Select Activity</a></li>
        <li><a href=../../indexselectedevent.php?event_id=<?php echo $event_id?>>Return to Event Page</a></li>
      </ul>
    </div>
  </div>
</div>
<?php
    mysqli_close($conn);
    exit;
} else {
    header("Location: sailing.php?event_id=$event_id&activity_id=$activity_id&class_id=$class_id&boat_type=$boat_type");
}
