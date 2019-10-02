<?php
//include connection.php
include_once '../connection.php';
include_once '../navbar.php';

$sql = "USE regattascoring;";
$use = mysqli_query($conn, $sql);
if (!$use) {
    echo "Could not use database." . mysqli_error($conn) . "<br/>";
    exit;
}
$sql = "DELETE FROM BRACKET;";
$delete = mysqli_query($conn, $sql);
$sql = "DELETE FROM ACTIVITY;";
$delete = mysqli_query($conn, $sql);
if (!$delete) {
    echo "Could not delete all from activity table" . mysqli_error($conn) . "<br/>";
    exit;
}

//create all activities

//Cutter Sailing
$sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Cutter Sailing', 'class', 'place', 'unit');";
$result = mysqli_query($conn, $sql);
$activity_id = mysqli_insert_id($conn);
$sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('2', '$activity_id');";
$result = mysqli_query($conn, $sql);
$sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('3', '$activity_id');";
$result = mysqli_query($conn, $sql);
$sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('4', '$activity_id');";
$result = mysqli_query($conn, $sql);

//Sunburst Sailing
$sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Sunburst Sailing', 'class', 'place', 'unit');";
$result = mysqli_query($conn, $sql);
$activity_id = mysqli_insert_id($conn);
$sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('2', '$activity_id');";
$result = mysqli_query($conn, $sql);
$sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('3', '$activity_id');";
$result = mysqli_query($conn, $sql);
$sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('4', '$activity_id');";
$result = mysqli_query($conn, $sql);

//Optimist Sailing
$sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Optimist Sailing', 'class', 'place', 'unit');";
$result = mysqli_query($conn, $sql);
$activity_id = mysqli_insert_id($conn);
$sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('1', '$activity_id');";

//Sunburst Rigging
$sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Sunburst Rigging', 'unit', 'score', 'unit');";
$result = mysqli_query($conn, $sql);

//Cutter Rigging
$sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Cutter Rigging', 'unit', 'score', 'unit');";
$result = mysqli_query($conn, $sql);

//Optimist Rigging
$sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Optimist Rigging', 'unit', 'score', 'unit');";
$result = mysqli_query($conn, $sql);

//Pulling
$sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Pulling', 'class', 'place', 'unit');";
$result = mysqli_query($conn, $sql);
$activity_id = mysqli_insert_id($conn);
$sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('2', '$activity_id');";
$result = mysqli_query($conn, $sql);
$sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('3', '$activity_id');";
$result = mysqli_query($conn, $sql);
$sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('4', '$activity_id');";
$result = mysqli_query($conn, $sql);

//Canoeing
$sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Canoeing', 'class', 'time', 'individual');";
$result = mysqli_query($conn, $sql);
$activity_id = mysqli_insert_id($conn);
$sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('1', '$activity_id');";
$result = mysqli_query($conn, $sql);
$sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('2', '$activity_id');";
$result = mysqli_query($conn, $sql);
$sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('3', '$activity_id');";
$result = mysqli_query($conn, $sql);
$sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('4', '$activity_id');";
$result = mysqli_query($conn, $sql);

//Swimming
$sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Swimming', 'class', 'time', 'individual');";
$result = mysqli_query($conn, $sql);
$activity_id = mysqli_insert_id($conn);
$sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('1', '$activity_id');";
$result = mysqli_query($conn, $sql);
$sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('2', '$activity_id');";
$result = mysqli_query($conn, $sql);
$sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('3', '$activity_id');";
$result = mysqli_query($conn, $sql);
$sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('4', '$activity_id');";
$result = mysqli_query($conn, $sql);

//Lifesaving
$sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Lifesaving', 'class', 'score', 'unit');";
$result = mysqli_query($conn, $sql);
$activity_id = mysqli_insert_id($conn);
$sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('1', '$activity_id');";
$result = mysqli_query($conn, $sql);
$sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('2', '$activity_id');";
$result = mysqli_query($conn, $sql);
$sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('3', '$activity_id');";
$result = mysqli_query($conn, $sql);
$sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('4', '$activity_id');";
$result = mysqli_query($conn, $sql);

//Shooting
$sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Shooting', 'class', 'score', 'individual');";
$result = mysqli_query($conn, $sql);
$activity_id = mysqli_insert_id($conn);
$sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('1', '$activity_id');";
$result = mysqli_query($conn, $sql);
$sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('2', '$activity_id');";
$result = mysqli_query($conn, $sql);
$sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('3', '$activity_id');";
$result = mysqli_query($conn, $sql);
$sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('4', '$activity_id');";
$result = mysqli_query($conn, $sql);

//Camping Set up
$sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Camping Set Up', 'unit', 'score', 'unit');";
$result = mysqli_query($conn, $sql);

//Camping Friday Evening
$sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Camping Friday Evening', 'unit', 'score', 'unit');";
$result = mysqli_query($conn, $sql);
echo mysqli_error($conn);

//Camping Saturday Morning
$sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Camping Saturday Morning', 'unit', 'score', 'unit');";
$result = mysqli_query($conn, $sql);

//Camping Saturday Evening
$sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Camping Saturday Evening', 'unit', 'score', 'unit');";
$result = mysqli_query($conn, $sql);

//Camping Sunday Morning
$sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Camping Sunday Morning', 'unit', 'score', 'unit');";
$result = mysqli_query($conn, $sql);

//Camping Sunday Evening
$sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Camping Sunday Evening', 'unit', 'score', 'unit');";
$result = mysqli_query($conn, $sql);

//Camping Monday Morning
$sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Camping Monday Morning', 'unit', 'score', 'unit');";
$result = mysqli_query($conn, $sql);

//Iron Woman
$sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Iron Woman', 'unit', 'place', 'unit');";
$result = mysqli_query($conn, $sql);

//Seamanship - Anchoring
$sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Seamanship - Anchoring', 'unit', 'score', 'unit');";
$result = mysqli_query($conn, $sql);

//Seamanship - Boat Handling
$sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Seamanship - Boat Handling', 'unit', 'score', 'unit');";
$result = mysqli_query($conn, $sql);

//Seamanship - First Aid
$sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Seamanship - First Aid', 'unit', 'score', 'unit');";
$result = mysqli_query($conn, $sql);

//Seamanship - Knots
$sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Seamanship - Knots', 'unit', 'score', 'unit');";
$result = mysqli_query($conn, $sql);

//Seamanship - Navigation
$sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Seamanship - Navigation', 'unit', 'score', 'unit');";
$result = mysqli_query($conn, $sql);

//Seamanship - Reefing
$sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Seamanship - Reefing', 'unit', 'score', 'unit');";
$result = mysqli_query($conn, $sql);

//Seamanship - Sailing Rules
$sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Seamanship - Sailing Rules', 'unit', 'score', 'unit');";
$result = mysqli_query($conn, $sql);

//echo classes created
echo "Classes Created
</br>
<a href='viewclass.php'>Edit Classes</a>
<br>
<a href='/'>Return Home</a>
<br>
<a href='searchclass.php'>View Classes</a>";
