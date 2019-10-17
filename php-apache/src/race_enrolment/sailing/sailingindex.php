<?php
//include connections
include_once '../../connection.php';

//define get variables
$activity_id = $_GET['activity_id'];
$event_id = $_GET['event_id'];
$class_id = $_GET['class_id'];

//select all from race enrolment for this sailing class
$sql = "SELECT * FROM regattascoring.RACE_ENROLMENT WHERE event_id = '$event_id'
 AND activity_id = '$activity_id' and class_id = $class_id;";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) != 0) {
    echo "something happens here";
} else {
    header("Location: sailing.php?event_id=$event_id&activity_id=$activity_id&class_id=$class_id");
}
