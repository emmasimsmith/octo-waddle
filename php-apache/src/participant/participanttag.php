<?php
//include functions and connection php files
include_once '../connection.php';
include_once '../functions.php';

//get event_id
$event_id = $_GET['event_id'];

//Select all from unit table
$sql = "SELECT * FROM regattascoring.UNIT;";
$outcome = mysqli_query($conn, $sql);

//array for the largest unit of each class
$class_num = array();
$sql = "SELECT * FROM regattascoring.CLASS;";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    array_push($class_num, $row['unit_id']);
}

while ($unit_row = mysqli_fetch_assoc($outcome)) {

    //select all from class table
    $sql = "SELECT * FROM regattascoring.CLASS;";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        //count from participant for each class for each unit
        $sql = "SELECT COUNT(*) AS total FROM regattascoring.PARTICIPANT
        RIGHT JOIN regattascoring.INDIVIDUAL ON regattascoring.INDIVIDUAL.individual_id
        = regattascoring.PARTICIPANT.individual_id WHERE class_id = " . $row['class_id']
        . " AND unit_id = ". $unit_row['unit_id'] ." AND event_id = $event_id;";
        $count = mysqli_query($conn, $sql);
        $number = mysqli_fetch_assoc($count);

        foreach ($class_num as $class_key => $class_amount) {
            if ($class_key == $unit_row['unit_id']) {
                if (!$class_amount) {
                    $class_amount = 0;
                }
                if ($number['total'] > $class_amount) {
                    $replace = array($unit_row['unit_id'] => $number['total']);
                    $class_num = array_replace($class_num, $replace);
                }
            }
        }
    }
}

//calculate participant tag by unit
$sql = "SELECT * FROM regattascoring.UNIT;";
$unit = mysqli_query($conn, $sql);
while ($unit_row = mysqli_fetch_assoc($unit)) {

  //set index key as 0 to match array
    $index_key = 0;

    //set min for class id as 0
    $min = 0;

    //select participants in unit where specific class
    $sql = "SELECT * FROM regattascoring.CLASS;";
    $class = mysqli_query($conn, $sql);
    while ($class_row = mysqli_fetch_assoc($class)) {

        //find max of this class
        $max = $class_num[$index_key];

        //increase index key by one
        $index_key++;

        //set count for participants as 0 after changing class and/or unit
        $class_tag = $min;

        //check to match participants
        $sql = "SELECT * FROM regattascoring.PARTICIPANT NATURAL JOIN
            regattascoring.INDIVIDUAL WHERE event_id = $event_id;";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            echo "theres an error but how?";
        }

        //select participant if matches
        while ($participant_row = mysqli_fetch_assoc($result)) {
            if ($participant_row['unit_id'] == $unit_row['unit_id']
                and $participant_row['class_id'] == $class_row['class_id']) {

                //calculate participant tag
                $participant_tag = $unit_row['unit_id'] * 100 + $class_tag;

                //update participant tag
                $sql = "UPDATE regattascoring.PARTICIPANT set participant_tag =
                    $participant_tag WHERE participant_id = " . $participant_row['participant_id'] . ";";
                $result = mysqli_query($conn, $sql);

                //increase class tag by one for each participant
                $class_tag++;
            }
        }

        //increase next class min, by adding max
        $min = $min + $max;
    }
    //if participant has NULL class_id
    $sql = "SELECT * FROM regattascoring.PARTICIPANT NATURAL JOIN
        regattascoring.INDIVIDUAL WHERE event_id = $event_id;";
    $woop = mysqli_query($conn, $sql);
    while ($participant_row = mysqli_fetch_assoc($woop)) {
        if ($participant_row['unit_id'] == $unit_row['unit_id'] and
          $participant_row['class_id'] == "") {

            //calculate participant tag
            $participant_tag = $unit_row['unit_id'] * 100 + $min;

            //update participant table
            $sql = "UPDATE regattascoring.PARTICIPANT set participant_tag =
            $participant_tag WHERE participant_id = " . $participant_row['participant_id'] . ";";
            $result = mysqli_query($conn, $sql);

            //increase minimum by one
            $min++;
        }
    }
}

header("Location: searchparticipant.php?event_id=$event_id");
