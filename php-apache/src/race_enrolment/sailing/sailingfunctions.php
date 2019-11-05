<?php

//to check if any tied values
function tied($conn, $event_id, $activity_id, $class_id, $race_number)
{
    $sql = "SELECT original_score, COUNT(original_score) AS total FROM regattascoring.RACE_ENROLMENT WHERE event_id = $event_id
    and activity_id= $activity_id and class_id = $class_id and race_number = $race_number
    GROUP BY original_score HAVING (COUNT(original_score) > 1) ;";
    $multiple = mysqli_query($conn, $sql);
    while ($original = mysqli_fetch_assoc($multiple)) {

        //select max of calculated score
        $sql = "SELECT MAX(calculated_score) as max FROM regattascoring.RACE_ENROLMENT WHERE event_id = $event_id
        and activity_id= $activity_id and class_id = $class_id and race_number = $race_number and original_score =" . $original['original_score'] . ";";
        $maxscore = mysqli_query($conn, $sql);
        $score = mysqli_fetch_assoc($maxscore);
        $placescore = $score['max'];

        //select all with tied with same original_score
        $sql = "SELECT * FROM regattascoring.RACE_ENROLMENT WHERE event_id = $event_id
        and activity_id= $activity_id and class_id = $class_id and race_number = $race_number and original_score =" . $original['original_score'] . ";";
        $result = mysqli_query($conn, $sql);

        while ($tied = mysqli_fetch_assoc($result)) {
            //update calculated score table
            $sql = "UPDATE regattascoring.RACE_ENROLMENT set
      calculated_score = '$placescore' WHERE race_id = " . $tied['race_id'] . ";";
            $input = mysqli_query($conn, $sql);
            echo "<div class='error'>".mysqli_error($conn)."</div>";
        }
    }
}

///function to input values
function input($conn, $activity_id, $unit_id, $class_id, $result, $calculated_score, $original_score, $event_id, $race_number)
{
    //insert value into table
    $sql = "INSERT INTO regattascoring.RACE_ENROLMENT (activity_id, unit_id, class_id,
  race_result, calculated_score, original_score, event_id, race_number) VALUES
  ('$activity_id', '$unit_id', $class_id, '$result', '$calculated_score', $original_score, '$event_id', '$race_number');";
    $input = mysqli_query($conn, $sql);
    echo "<div class='error'>".mysqli_error($conn)."</div>";
}

//function to update values
function updatesailing($conn, $result, $calculated_score, $original_score, $race_id, $race_number)
{
    ///update values in Countable
    $sql = "UPDATE regattascoring.RACE_ENROLMENT set race_result = '$result',
calculated_score = $calculated_score, original_score = $original_score WHERE
race_id = $race_id AND race_number = $race_number;";
    $input = mysqli_query($conn, $sql);
    echo "<div class='error'>".mysqli_error($conn)."</div>";
}

//function to select race_id for update
function race_id($conn, $event_id, $activity_id, $unit_id, $class_id, $race_number)
{
    //select race_id for update
    $sql = "SELECT race_id FROM regattascoring.RACE_ENROLMENT WHERE
  event_id = '$event_id' AND activity_id = '$activity_id' AND unit_id = '$unit_id'
  AND class_id = '$class_id' AND race_number = '$race_number';";
    $race = mysqli_query($conn, $sql);
    return $race;
}
