<?php
//include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

echo $_POST['activity_name'];

if (!$_POST['activity_name']) {
    close($conn, "Please select an activity", "race_enrollment", "Race Enrollments");
} elseif (($_POST['submit'])) {
}
