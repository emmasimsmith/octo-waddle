<?php
//include functions and connection php files
include_once '../connection.php';
include_once '../functions.php';

//get event_id
$event_id = $_GET['event_id'];

//array for the largest unit of each class
$class_num = array(1 => "", 2 => "", 3 => "", 4 => "");

//Select all from unit table
$sql = "SELECT * FROM regattascoring.UNIT;";
$outcome = mysqli_query($conn, $sql);
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
            if ($class_key == $row['class_id']) {
                if (!$class_amount) {
                    $class_amount = 0;
                }
                if ($number['total'] > $class_amount) {
                    $replace = array($row['class_id'] => $number['total']);
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

    //define unit_id
    $unit_id = $unit_row['unit_id'];

    //set min to 0 for class age
    $min = 0;

    //for each class
    foreach ($class_num as $class_id => $class_max) {
        //if class max is empty set as 0
        if (!$class_max) {
            $class_max = 0;
        }

        //set tag as min for class
        $class_tag = $min;

        //select participants within event and unit
        $sql = "SELECT * FROM regattascoring.PARTICIPANT NATURAL JOIN
          regattascoring.INDIVIDUAL WHERE event_id = '$event_id' AND unit_id = '$unit_id' AND class_id = '$class_id';";

        $result = mysqli_query($conn, $sql);

        if (!$result) {
            echo "somethings wrong";
            exit;
        }

        //set tag for each participant
        while ($participant_row = mysqli_fetch_assoc($result)) {

            //calculate participant tag
            $participant_tag = ($unit_id * 100) + $class_tag;

            //update participant tag
            $sql = "UPDATE regattascoring.PARTICIPANT set participant_tag =
              $participant_tag WHERE participant_id = " . $participant_row['participant_id'] . ";";
            $tag = mysqli_query($conn, $sql);

            //increase tag by one for next participant
            $class_tag++;
        }
        $min = $min + $class_max;
    }


    //if participant has NULL class_id
    $sql = "SELECT * FROM regattascoring.PARTICIPANT NATURAL JOIN
        regattascoring.INDIVIDUAL WHERE event_id = $event_id AND unit_id = $unit_id;";
    $woop = mysqli_query($conn, $sql);
    while ($participant_row = mysqli_fetch_assoc($woop)) {
        if ($participant_row['class_id'] == "") {

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
