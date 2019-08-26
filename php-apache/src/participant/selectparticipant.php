<?php
//include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

//select all from participant table
$sql = "SELECT * FROM regattascoring.PARTICIPANT;";
$result = mysqli_query($conn, $sql);

//get event_id
$event_id = $_GET['id'];

//function for closing
function participant_close($conn, $error, $event_id)
{
    if ($error) {
        echo $error . "</br>";
    } ?>
  <br>
  <a href="/">Return Home</a>
  <br>
  <a href= <?php echo "searchparticipant.php?id=" . $event_id . ">View participants</a>";
    mysqli_close($conn);
}

if (isset($_POST["select"])) {

  //check if any mariners are not in the age limit
    $undefined = array();
    //select all from individual table
    $sql = "SELECT * FROM regattascoring.INDIVIDUAL;";
    $individual = mysqli_query($conn, $sql);

    //run for each indivdual
    while ($individual_row = mysqli_fetch_assoc($individual)) {

        //if $_POST returns if selected
        $individual_id = $individual_row['individual_id'];
        if ($_POST["$individual_id"]) {
            //if individual is a mariner
            if ($individual_row['role'] == 'mariner') {
                //select event date from event table
                $sql = "SELECT event_date FROM regattascoring.EVENT WHERE event_id = " . $_GET['id'] . ";";
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

    //select all from indivdual table
    $sql = "SELECT * FROM regattascoring.INDIVIDUAL;";
    $individual = mysqli_query($conn, $sql);

    //run for each indivdual
    while ($individual_row = mysqli_fetch_assoc($individual)) {
        echo $individual_row['first_name'] . " ";

        //if $_POST returns if selected
        $individual_id = $individual_row['individual_id'];
        if ($_POST["$individual_id"]) {
            echo $_POST["$individual_id"] . " ";

            //if individual is a mariner
            if ($individual_row['role'] == 'mariner') {
                echo $individual_row['role'] . " ";

                //select event date from event table
                $sql = "SELECT event_date FROM regattascoring.EVENT WHERE event_id = " . $_GET['id'] . ";";
                $result = mysqli_query($conn, $sql);
                echo $sql;

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
                echo " " . $age . "</br>";
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
                    (class_id, individual_id, event_id) VALUES (" . $class . "," .
                      $individual_row['individual_id'] . "," . $event_id . ");";

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
    }

    //TODO set participant tag

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
            . " AND unit_id = ". $unit_row['unit_id'] ." AND event_id = " . $event_id . ";";
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
                        echo mysqli_error($conn) . "</br>";
                    }
                }
            }
        }
    }
    echo mysqli_error($conn);
    print_r($class_num);

    //call small close
    participant_close($conn, $error, $event_id);

//if participants already selected
} elseif (mysqli_num_rows($result) != 0) {

    //form with preselected values
    $sql = "SELECT * FROM regattascoring.INDIVIDUAL LEFT JOIN regattascoring.PARTICIPANT
      ON regattascoring.PARTICIPANT.individual_id = regattascoring.INDIVIDUAL.individual_id;";
    $result = mysqli_query($conn, $sql);
    echo mysqli_error($conn);

    //call form with individuals previously selected?>
  <html>
    <title>Reselect Participants</title>
    <head>
      <h1>Reselect Participants</h1>
    </head>
      <form action= <?php echo "selectparticipant.php?id=" . $_GET['id'] ?> method="POST">
        Select Participants:
        <br>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<input type='checkbox' name=" . $row['individual_id'] . " value='selected'";
            if ($row['participant_id'] and $row['event_id'] == $_GET['id']) {
                echo " checked";
            }
            echo ">" . $row['first_name'] . " " . $row['last_name'] . "</input>";
            echo "<br>";
        } ?>
        <button name="select">Select</button>
      </form>
  </html>
  <?php
    participant_close($conn, "", $event_id);

//else if nothing selected yet
} else {

    //array for empty table error
    $empty = array();

    //select all from indivdual table
    $sql = "SELECT * FROM regattascoring.INDIVIDUAL;";
    $individual = mysqli_query($conn, $sql);
    if (mysqli_num_rows($individual) == 0) {
        array_push($empty, "Please create an individual first");
    }

    //select all from class table
    $sql = "SELECT * FROM regattascoring.CLASS;";
    $class = mysqli_query($conn, $sql);
    if (mysqli_num_rows($class) == 0) {
        array_push($empty, "Please create a class first");
    }

    if (count($empty) != 0) {
    } elseif (mysqli_num_rows($result) != 0) {
        $error = "";
        foreach ($empty as $issue) {
            $error = $error . $issue . "</br>";
        }
        participant_close($conn, $error, $event_id);
        exit;
    }

    //call form with individuals to select?>
  <html>
    <title>Select Participants</title>
    <head>
      <h1>Select Participants</h1>
    </head>
      <form action= <?php echo "selectparticipant.php?id=" . $_GET['id'] ?> method="POST">
        Select Participants:
        <br>
        <?php
        while ($row = mysqli_fetch_assoc($individual)) {
            echo "<input type='checkbox' name=" . $row['individual_id'] . " value='selected'>" . $row['first_name'] . " " . $row['last_name'] . "</input>";
            echo "<br>";
        } ?>
        <button name="select">Select</button>
      </form>
  </html>
  <?php
}
