<?php
$activity_id = $_GET['activity_id'];
$event_id = $_GET['event_id'];
$class_id = $_POST['class_id'];

//if activity is sailing for cutter, sunburst, or optimist
if ($activity_id == 1) {
    header("Location: sailing/sailingindex.php?event_id=$event_id&activity_id=$activity_id&class_id=$class_id&boat_type=cutter");
}
if ($activity_id == 2) {
    header("Location: sailing/sailingindex.php?event_id=$event_id&activity_id=$activity_id&class_id=$class_id&boat_type=sunburst");
}
if ($activity_id == 3) {
    header("Location: sailing/sailingindex.php?event_id=$event_id&activity_id=$activity_id&class_id=$class_id&boat_type=optimist");
}
//if activity is cutter, sunburst or opti rigging
if ($activity_id == 4 || $activity_id == 5 || $activity_id == 6) {
    header("Location: rigging/rigging.php?event_id=$event_id&activity_id=$activity_id");
}
//if activity is pulling
if ($activity_id == 7) {
    header("Location: pulling/pulling.php?event_id=$event_id&activity_id=$activity_id&class_id=$class_id");
}
//if activity is canoeing
if ($activity_id == 8) {
    header("Location: canoeing/canoeing.php?event_id=$event_id&activity_id=$activity_id&class_id=$class_id");
}
//if activity is swimming
if ($activity_id == 9) {
    header("Location: swimming/swimming.php?event_id=$event_id&activity_id=$activity_id&class_id=$class_id");
}
//if activity is life saving
if ($activity_id == 10) {
    header("Location: lifesaving/lifesaving.php?event_id=$event_id&activity_id=$activity_id&class_id=$class_id");
}
//if activity is shooting
if ($activity_id == 11) {
    header("Location: shooting/shooting.php?event_id=$event_id&activity_id=$activity_id&class_id=$class_id");
}
//if activity is camping
if ($activity_id == 12 || $activity_id == 13 || $activity_id == 14 || $activity_id == 15 || $activity_id == 16 || $activity_id == 17 || $activity_id == 18) {
    header("Location: camping/camping.php?event_id=$event_id&activity_id=$activity_id");
}
//if activity is iron woman
if ($activity_id == 19) {
    header("Location: ironwoman/ironwoman.php?event_id=$event_id&activity_id=$activity_id");
}
//if activity is seamanship
if ($activity_id == 20 || $activity_id == 21 || $activity_id == 22 || $activity_id == 23 || $activity_id == 24 || $activity_id == 25 || $activity_id == 26) {
    header("Location: seamanship/seamanship.php?event_id=$event_id&activity_id=$activity_id");
}
