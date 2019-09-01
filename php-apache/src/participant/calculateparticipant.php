<?php
//include navigation bar, functions and connection php files
include_once '../connection.php';
include_once '../functions.php';

//get event_id
$event_id = $_GET['event_id'];

//check if any mariners are not in the age limit
  $undefined = array();

  $join = join(",", $_POST['selected']);

  //select all from individual table
  $sql = "SELECT * FROM regattascoring.INDIVIDUAL WHERE individual_id IN ($join);";
  $individual = mysqli_query($conn, $sql);

  //run for each indivdual
  while ($individual_row = mysqli_fetch_assoc($individual)) {

      //if individual is a mariner
      if ($individual_row['role'] == 'mariner') {

          //select event date from event table
          $sql = "SELECT event_date FROM regattascoring.EVENT WHERE event_id = $event_id;";
          $result = mysqli_query($conn, $sql);

          //check $result returns true
          if (!$result) {
              participant_close($conn, "No date selected", $event_id);
          }

          //set event date and dob as date values
          $event_row = mysqli_fetch_assoc($result);
          $event_date = new DateTime($event_row['event_date']);
          $dob = new DateTime($individual_row ['dob']);

          //find date difference to find individuals age
          $datediff = date_diff($dob, $event_date);
          $age = $datediff->y + $datediff->m/12;

          //select all from class table
          $sql = "SELECT * FROM regattascoring.CLASS;";
          $result = mysqli_query($conn, $sql);

          while ($class_row = mysqli_fetch_assoc($result)) {
              //check age is in the class
              if ($age > $class_row['min_age'] && $age < $class_row['max_age']) {
                  $class = $class_row['class_id'];
              }
          }
          if (!$class) {
              array_push($undefined, $individual_row['first_name'] . " " . $individual_row['last_name'] . "is not in the age limit");
          }
      }
  }

  //if mariner is out of the age limit
  if (count($undefined) != 0) {
      foreach ($undefined as $error) {
          echo $error . "</br>";
          participant_close($conn, "", $event_id);
          exit;
      }
  }

//once past check that all mariners are within the class ages

//delete all from participant for event
$sql = "DELETE FROM regattascoring.PARTICIPANT WHERE event_id = '$event_id';";
if (!mysqli_query($conn, $sql)) {
    participant_close($conn, "Could not delete previous participants", $event_id);
    exit;
}

$join = join(",", $_POST['selected']);

//select all from individual table
$sql = "SELECT * FROM regattascoring.INDIVIDUAL WHERE individual_id IN ($join);";
$individual = mysqli_query($conn, $sql);


//run for each indivdual
while ($individual_row = mysqli_fetch_assoc($individual)) {

    //if individual is a mariner
    if ($individual_row['role'] == 'mariner') {

        //select event date from event table
        $sql = "SELECT event_date FROM regattascoring.EVENT WHERE event_id = " . $_GET['event_id'] . ";";
        $result = mysqli_query($conn, $sql);

        //check $result returns true
        if (!$result) {
            participant_close($conn, "No date selected", $event_id);
        }

        //set event date and dob as date values
        $event_row = mysqli_fetch_assoc($result);
        $event_date = new DateTime($event_row['event_date']);
        $dob = new DateTime($individual_row ['dob']);

        //find date difference to find individuals age
        $datediff = date_diff($dob, $event_date);
        $age = $datediff->y + $datediff->m/12;

        //select all from class table
        $sql = "SELECT * FROM regattascoring.CLASS;";
        $result = mysqli_query($conn, $sql);
        while ($class_row = mysqli_fetch_assoc($result)) {

                //check age is in the class
            if ($age > $class_row['min_age'] && $age < $class_row['max_age']) {
                $class = $class_row['class_id'];
            }
        }

        //input individual into participant table
        $sql = "INSERT INTO regattascoring.PARTICIPANT
                (class_id, individual_id, event_id) VALUES ($class," .
                  $individual_row['individual_id'] . ", $event_id);";

        //check if participant created
        if (!mysqli_query($conn, $sql)) {
            echo mysqli_error($conn);
            participant_close($conn, "Cannot create participant" . $individual_row['individual_id'], $event_id);
            exit;
        }

        //if role is other
    } else {
        //input individual into participant table
        $sql = "INSERT INTO regattascoring.PARTICIPANT (individual_id, event_id)
            VALUES (" . $individual_row['individual_id'] . "," . $event_id . ");";

        //check if participant created
        if (!mysqli_query($conn, $sql)) {
            echo mysqli_error($conn);
            participant_close($conn, "Cannot create participant", $event_id);
            exit;
        }
    }
}

header("Location: participanttag.php?event_id=$event_id");
