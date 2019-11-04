<?php
//calculate awards page

//include connection and functions
include_once '../connection.php';
include_once '../functions.php';
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

$place = 1;
foreach ($placing as $unit_id => $score) {
    $sql = "INSERT INTO regattascoring.PLACING (unit_id, score, place) VALUES ($unit_id,
  $score, $place);";
    $input = mysqli_query($conn, $sql);
    $place++;
}

award_tied($conn);

//select all units placed 1st, 2nd, 3rd
$sql = "SELECT * FROM regattascoring.PLACING WHERE place = '1' or place ='2' or place = '3';";
$outcome = mysqli_query($conn, $sql);
while ($input = mysqli_fetch_assoc($outcome)) {
    $unit_id = $input['unit_id'];
    $place = $input['place'];

    $sql = "INSERT INTO regattascoring.AWARD (unit_id, place, certificate_id, event_id)
  VALUES ('$unit_id', '$place', '1', '$event_id');";
    $enter = mysqli_query($conn, $sql);
    if (!$enter) {
        echo mysqli_error($conn);
        mysqli_close($conn);
        exit;
    }
}

$sql = "DELETE FROM regattascoring.PLACING;";
$delete = mysqli_query($conn, $sql);





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
$place = 1;
foreach ($placing as $unit_id => $score) {
    $sql = "INSERT INTO regattascoring.PLACING (unit_id, score, place) VALUES ($unit_id,
  $score, $place);";
    $input = mysqli_query($conn, $sql);
    $place++;
}
award_tied($conn);
//select all units placed 1st, 2nd, 3rd
$sql = "SELECT * FROM regattascoring.PLACING WHERE place = '1' or place ='2' or place = '3';";
$outcome = mysqli_query($conn, $sql);
while ($input = mysqli_fetch_assoc($outcome)) {
    $unit_id = $input['unit_id'];
    $place = $input['place'];

    $sql = "INSERT INTO regattascoring.AWARD (unit_id, place, certificate_id, event_id)
  VALUES ('$unit_id', '$place', '40', '$event_id');";
    $enter = mysqli_query($conn, $sql);
    if (!$enter) {
        echo mysqli_error($conn);
        mysqli_close($conn);
        exit;
    }
}
$sql = "DELETE FROM regattascoring.PLACING;";
$delete = mysqli_query($conn, $sql);



//lifesaving awards
//JJ lifesaving
//array for results
$placing = array();
//select all results from each day
$sql = "SELECT * FROM regattascoring.UNIT;";
$result = mysqli_query($conn, $sql);
while ($unit_row = mysqli_fetch_assoc($result)) {
    $score = 0;
    //define unit id for sql statements
    $unit_id = $unit_row['unit_id'];

    //select original score from lifesaving
    $sql = "SELECT calculated_score FROM regattascoring.RACE_ENROLMENT WHERE unit_id
  = $unit_id and class_id = '1' and activity_id = '10';";
    $score_result = mysqli_query($conn, $sql);
    $score_row = mysqli_fetch_assoc($score_result);
    $score = $score + $score_row['original_score'];

    $placing += [$unit_id => $score];
}

$place = 1;
foreach ($placing as $unit_id => $score) {
    $sql = "INSERT INTO regattascoring.PLACING (unit_id, score, place) VALUES ($unit_id,
  $score, $place);";
    $input = mysqli_query($conn, $sql);
    $place++;
}

award_tied($conn);

//select all units placed 1st, 2nd, 3rd
$sql = "SELECT * FROM regattascoring.PLACING WHERE place = '1' or place ='2' or place = '3';";
$outcome = mysqli_query($conn, $sql);
while ($input = mysqli_fetch_assoc($outcome)) {
    $unit_id = $input['unit_id'];
    $place = $input['place'];

    $sql = "INSERT INTO regattascoring.AWARD (unit_id, place, certificate_id, event_id)
  VALUES ('$unit_id', '$place', '10', '$event_id');";
    $enter = mysqli_query($conn, $sql);
    if (!$enter) {
        echo mysqli_error($conn);
        mysqli_close($conn);
        exit;
    }
}
$sql = "DELETE FROM regattascoring.PLACING;";
$delete = mysqli_query($conn, $sql);

//Junior Life Saving
//array for results
$placing = array();
//select all results from each day
$sql = "SELECT * FROM regattascoring.UNIT;";
$result = mysqli_query($conn, $sql);
while ($unit_row = mysqli_fetch_assoc($result)) {
    $score = 0;
    //define unit id for sql statements
    $unit_id = $unit_row['unit_id'];

    //select original score from lifesaving
    $sql = "SELECT calculated_score FROM regattascoring.RACE_ENROLMENT WHERE unit_id
  = $unit_id and class_id = '2' and activity_id = '10';";
    $score_result = mysqli_query($conn, $sql);
    $score_row = mysqli_fetch_assoc($score_result);
    $score = $score + $score_row['original_score'];

    $placing += [$unit_id => $score];
}

$place = 1;
foreach ($placing as $unit_id => $score) {
    $sql = "INSERT INTO regattascoring.PLACING (unit_id, score, place) VALUES ($unit_id,
  $score, $place);";
    $input = mysqli_query($conn, $sql);
    $place++;
}

award_tied($conn);

//select all units placed 1st, 2nd, 3rd
$sql = "SELECT * FROM regattascoring.PLACING WHERE place = '1' or place ='2' or place = '3';";
$outcome = mysqli_query($conn, $sql);
while ($input = mysqli_fetch_assoc($outcome)) {
    $unit_id = $input['unit_id'];
    $place = $input['place'];

    $sql = "INSERT INTO regattascoring.AWARD (unit_id, place, certificate_id, event_id)
  VALUES ('$unit_id', '$place', '11', '$event_id');";
    $enter = mysqli_query($conn, $sql);
    if (!$enter) {
        echo mysqli_error($conn);
        mysqli_close($conn);
        exit;
    }
}
$sql = "DELETE FROM regattascoring.PLACING;";
$delete = mysqli_query($conn, $sql);


//Intermediate Life Saving
//array for results
$placing = array();
//select all results from each day
$sql = "SELECT * FROM regattascoring.UNIT;";
$result = mysqli_query($conn, $sql);
while ($unit_row = mysqli_fetch_assoc($result)) {
    $score = 0;
    //define unit id for sql statements
    $unit_id = $unit_row['unit_id'];

    //select original score from lifesaving
    $sql = "SELECT calculated_score FROM regattascoring.RACE_ENROLMENT WHERE unit_id
  = $unit_id and class_id = '3' and activity_id = '10';";
    $score_result = mysqli_query($conn, $sql);
    $score_row = mysqli_fetch_assoc($score_result);
    $score = $score + $score_row['original_score'];

    $placing += [$unit_id => $score];
}

$place = 1;
foreach ($placing as $unit_id => $score) {
    $sql = "INSERT INTO regattascoring.PLACING (unit_id, score, place) VALUES ($unit_id,
  $score, $place);";
    $input = mysqli_query($conn, $sql);
    $place++;
}

award_tied($conn);

//select all units placed 1st, 2nd, 3rd
$sql = "SELECT * FROM regattascoring.PLACING WHERE place = '1' or place ='2' or place = '3';";
$outcome = mysqli_query($conn, $sql);
while ($input = mysqli_fetch_assoc($outcome)) {
    $unit_id = $input['unit_id'];
    $place = $input['place'];

    $sql = "INSERT INTO regattascoring.AWARD (unit_id, place, certificate_id, event_id)
  VALUES ('$unit_id', '$place', '12', '$event_id');";
    $enter = mysqli_query($conn, $sql);
    if (!$enter) {
        echo mysqli_error($conn);
        mysqli_close($conn);
        exit;
    }
}
$sql = "DELETE FROM regattascoring.PLACING;";
$delete = mysqli_query($conn, $sql);

//Senior Life Saving
//array for results
$placing = array();
//select all results from each day
$sql = "SELECT * FROM regattascoring.UNIT;";
$result = mysqli_query($conn, $sql);
while ($unit_row = mysqli_fetch_assoc($result)) {
    $score = 0;
    //define unit id for sql statements
    $unit_id = $unit_row['unit_id'];

    //select original score from lifesaving
    $sql = "SELECT calculated_score FROM regattascoring.RACE_ENROLMENT WHERE unit_id
  = $unit_id and class_id = '4' and activity_id = '10';";
    $score_result = mysqli_query($conn, $sql);
    $score_row = mysqli_fetch_assoc($score_result);
    $score = $score + $score_row['original_score'];

    $placing += [$unit_id => $score];
}

$place = 1;
foreach ($placing as $unit_id => $score) {
    $sql = "INSERT INTO regattascoring.PLACING (unit_id, score, place) VALUES ($unit_id,
  $score, $place);";
    $input = mysqli_query($conn, $sql);
    $place++;
}

award_tied($conn);

//select all units placed 1st, 2nd, 3rd
$sql = "SELECT * FROM regattascoring.PLACING WHERE place = '1' or place ='2' or place = '3';";
$outcome = mysqli_query($conn, $sql);
while ($input = mysqli_fetch_assoc($outcome)) {
    $unit_id = $input['unit_id'];
    $place = $input['place'];

    $sql = "INSERT INTO regattascoring.AWARD (unit_id, place, certificate_id, event_id)
  VALUES ('$unit_id', '$place', '13', '$event_id');";
    $enter = mysqli_query($conn, $sql);
    if (!$enter) {
        echo mysqli_error($conn);
        mysqli_close($conn);
        exit;
    }
}
$sql = "DELETE FROM regattascoring.PLACING;";
$delete = mysqli_query($conn, $sql);


//rigging awards
//sailing awards



//seamanship
//array for results
$placing = array();
//select all results from each day
$sql = "SELECT * FROM regattascoring.UNIT;";
$result = mysqli_query($conn, $sql);
while ($unit_row = mysqli_fetch_assoc($result)) {
    $score = 0;
    //define unit id for sql statements
    $unit_id = $unit_row['unit_id'];

    //select original score from seamanship
    $sql = "SELECT original_score FROM regattascoring.RACE_ENROLMENT WHERE unit_id
  = $unit_id and activity_id = '20';";
    $score_result = mysqli_query($conn, $sql);
    $score_row = mysqli_fetch_assoc($score_result);
    $score = $score + $score_row['original_score'];

    //select original score from seamanship
    $sql = "SELECT original_score FROM regattascoring.RACE_ENROLMENT WHERE unit_id
  = $unit_id and activity_id = '21';";
    $score_result = mysqli_query($conn, $sql);
    $score_row = mysqli_fetch_assoc($score_result);
    $score = $score + $score_row['original_score'];

    //select original score from seamanship
    $sql = "SELECT original_score FROM regattascoring.RACE_ENROLMENT WHERE unit_id
  = $unit_id and activity_id = '22';";
    $score_result = mysqli_query($conn, $sql);
    $score_row = mysqli_fetch_assoc($score_result);
    $score = $score + $score_row['original_score'];

    //select original score from seamanship
    $sql = "SELECT original_score FROM regattascoring.RACE_ENROLMENT WHERE unit_id
  = $unit_id and activity_id = '23';";
    $score_result = mysqli_query($conn, $sql);
    $score_row = mysqli_fetch_assoc($score_result);
    $score = $score + $score_row['original_score'];

    //select original score from seamanship
    $sql = "SELECT original_score FROM regattascoring.RACE_ENROLMENT WHERE unit_id
  = $unit_id and activity_id = '24';";
    $score_result = mysqli_query($conn, $sql);
    $score_row = mysqli_fetch_assoc($score_result);
    $score = $score + $score_row['original_score'];

    //select original score from seamanship
    $sql = "SELECT original_score FROM regattascoring.RACE_ENROLMENT WHERE unit_id
  = $unit_id and activity_id = '25';";
    $score_result = mysqli_query($conn, $sql);
    $score_row = mysqli_fetch_assoc($score_result);
    $score = $score + $score_row['original_score'];
    $placing += [$unit_id => $score];

    //select original score from seamanship
    $sql = "SELECT original_score FROM regattascoring.RACE_ENROLMENT WHERE unit_id
  = $unit_id and activity_id = '26';";
    $score_result = mysqli_query($conn, $sql);
    $score_row = mysqli_fetch_assoc($score_result);
    $score = $score + $score_row['original_score'];

    $placing += [$unit_id => $score];
}

$place = 1;
foreach ($placing as $unit_id => $score) {
    $sql = "INSERT INTO regattascoring.PLACING (unit_id, score, place) VALUES ($unit_id,
  $score, $place);";
    $input = mysqli_query($conn, $sql);
    $place++;
}

award_tied($conn);

//select all units placed 1st, 2nd, 3rd
$sql = "SELECT * FROM regattascoring.PLACING WHERE place = '1' or place ='2' or place = '3';";
$outcome = mysqli_query($conn, $sql);
while ($input = mysqli_fetch_assoc($outcome)) {
    $unit_id = $input['unit_id'];
    $place = $input['place'];

    $sql = "INSERT INTO regattascoring.AWARD (unit_id, place, certificate_id, event_id)
  VALUES ('$unit_id', '$place', '37', '$event_id');";
    $enter = mysqli_query($conn, $sql);
    if (!$enter) {
        echo mysqli_error($conn);
        mysqli_close($conn);
        exit;
    }
}
$sql = "DELETE FROM regattascoring.PLACING;";
$delete = mysqli_query($conn, $sql);

header("Location: awards.php?event_id=$event_id");
