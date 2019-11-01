<?php
//calculate awards page

//include connection
include_once '../connection.php';
//get event_id for input
$event_id = $_GET['event_id'];

//delete awards for this event if already exist
$sql = "SELECT * FROM regattascoring.AWARD WHERE event_id = $event_id;";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) != 0) {
    $sql = "DELETE FROM regattascoring.AWARD WHERE event_id = $event_id;";
    $delete = mysqli_query($conn, $sql);
    if (!$delete) {
        echo mysqli_error($conn);
        mysqli_close($conn);
        exit;
    }
}

//camping awards
//array for results
$placing = array();
//select all results from each day
$sql = "SELECT * FROM regattascoring.UNIT;";
$result = mysqli_query($conn, $sql);
while ($unit_row = mysqli_fetch_assoc($result)) {
    $score = 0;
    //define unit id for sql statements
    $unit_id = $unit_row['unit_id'];

    //select original score from camping set up
    $sql = "SELECT original_score FROM regattascoring.RACE_ENROLMENT WHERE unit_id
  = $unit_id and activity_id = '12';";
    $score_result = mysqli_query($conn, $sql);
    $score_row = mysqli_fetch_assoc($score_result);
    $score = $score + $score_row['original_score'];

    //select original score from friday evening
    $sql = "SELECT original_score FROM regattascoring.RACE_ENROLMENT WHERE unit_id
  = $unit_id and activity_id = '13';";
    $score_result = mysqli_query($conn, $sql);
    $score_row = mysqli_fetch_assoc($score_result);
    $score = $score + $score_row['original_score'];

    //select original score from saturday morning
    $sql = "SELECT original_score FROM regattascoring.RACE_ENROLMENT WHERE unit_id
  = $unit_id and activity_id = '14';";
    $score_result = mysqli_query($conn, $sql);
    $score_row = mysqli_fetch_assoc($score_result);
    $score = $score + $score_row['original_score'];

    //select original score from saturday evening
    $sql = "SELECT original_score FROM regattascoring.RACE_ENROLMENT WHERE unit_id
  = $unit_id and activity_id = '15';";
    $score_result = mysqli_query($conn, $sql);
    $score_row = mysqli_fetch_assoc($score_result);
    $score = $score + $score_row['original_score'];

    //select original score from sunday morning
    $sql = "SELECT original_score FROM regattascoring.RACE_ENROLMENT WHERE unit_id
  = $unit_id and activity_id = '16';";
    $score_result = mysqli_query($conn, $sql);
    $score_row = mysqli_fetch_assoc($score_result);
    $score = $score + $score_row['original_score'];

    //select original score from sunday evening
    $sql = "SELECT original_score FROM regattascoring.RACE_ENROLMENT WHERE unit_id
  = $unit_id and activity_id = '17';";
    $score_result = mysqli_query($conn, $sql);
    $score_row = mysqli_fetch_assoc($score_result);
    $score = $score + $score_row['original_score'];

    //select original score from monday morning
    $sql = "SELECT original_score FROM regattascoring.RACE_ENROLMENT WHERE unit_id
  = $unit_id and activity_id = '18';";
    $score_result = mysqli_query($conn, $sql);
    $score_row = mysqli_fetch_assoc($score_result);
    $score = $score + $score_row['original_score'];

    $placing += [$unit_id => $score];
}

arsort($placing);
$count = 1;
foreach ($placing as $unit_id => $score) {
    if ($count != 4) {
        $sql = "INSERT INTO regattascoring.AWARD (unit_id, place, certificate_id, event_id)
   VALUES ('$unit_id', '$count', '1', '$event_id');";
        $enter = mysqli_query($conn, $sql);
        if (!$enter) {
            echo mysqli_error($conn);
            mysqli_close($conn);
            exit;
        }
    }
    $count++;
}


//ironwoman awards
//array for results
$placing = array();
//select all results from each day
$sql = "SELECT * FROM regattascoring.UNIT;";
$result = mysqli_query($conn, $sql);
while ($unit_row = mysqli_fetch_assoc($result)) {
    $score = 0;
    //define unit id for sql statements
    $unit_id = $unit_row['unit_id'];

    //select original score from camping set up
    $sql = "SELECT calculated_score FROM regattascoring.RACE_ENROLMENT WHERE unit_id
  = $unit_id and activity_id = '40';";
    $score_result = mysqli_query($conn, $sql);
    $score_row = mysqli_fetch_assoc($score_result);
    $score = $score + $score_row['original_score'];

    $placing += [$unit_id => $score];
}

arsort($placing);
$count = 1;
foreach ($placing as $unit_id => $score) {
    if ($count != 4) {
        $sql = "INSERT INTO regattascoring.AWARD (unit_id, place, certificate_id, event_id)
   VALUES ('$unit_id', '$count', '1', '$event_id');";
        $enter = mysqli_query($conn, $sql);
        if (!$enter) {
            echo mysqli_error($conn);
            mysqli_close($conn);
            exit;
        }
    }
    $count++;
}

//lifesaving awards
//rigging awards
//sailing awards
//seamanship

header("Location: awards.php?event_id=$event_id");
