<?php
//include functions and connection php files
include_once '../../connection.php';
include_once '../../functions.php';
include_once '../../navbar.php';
include_once 'sailingfunctions.php';

//define activity_id
$activity_id = $_GET['activity_id'];
$event_id = $_GET['event_id'];
$class_id = $_GET['class_id'];
$race_number = $_GET['race_number'];
$boat_type = $_GET ['boat_type'];

//select the activity name for messages
$sql = "SELECT activity_name FROM regattascoring.ACTIVITY WHERE activity_id = '$activity_id';";
$activity = mysqli_query($conn, $sql);
$activity_row = mysqli_fetch_assoc($activity);

//select class name for messages
$sql = "SELECT class_name FROM regattascoring.CLASS WHERE class_id = '$class_id';";
$class = mysqli_query($conn, $sql);
$class_row = mysqli_fetch_assoc($class);

if (isset($_POST['delete'])) {
    //delete option
    //delete from database
    $sql = "DELETE FROM regattascoring.RACE_ENROLMENT WHERE event_id = '$event_id'
    AND activity_id = '$activity_id' AND class_id = '$class_id' AND race_number = '$race_number';";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo mysqli_error($conn);
        mysqli_close($conn);
        exit;
    }
    //delete from sailing table
    $sql = "DELETE FROM regattascoring.SAILING WHERE event_id = '$event_id' and
    activity_id = '$activity_id' and race_number = '$race_number';";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo mysqli_error($conn);
        mysqli_close($conn);
        exit;
    }

    echo $class_row['class_name'] . " " . $activity_row['activity_name'] . " results deleted";

// if update selected
} elseif (isset($_POST['update'])) {
    //update option
    //select all units
    $sql = "SELECT * FROM regattascoring.BOAT where boat_type ='$boat_type';";
    $result = mysqli_query($conn, $sql);

    //array for errors
    $errors = array();

    //run loop for all boats
    //input sanitisation
    while ($insert = mysqli_fetch_assoc($result)) {
        $boat_id = $insert['boat_id'];
        $boat_number = $insert['boat_number'];

        if (!$_POST["$boat_id"]) {
            array_push($errors, "Select a result for $boat_number $boat_type");
        }
        if ($_POST["$boat_id"] == "completed" and !$_POST["$boat_number"]) {
            array_push($errors, "Enter a score for $boat_number $boat_type");
        }
        if ($_POST["$boat_id"] == "DNC" and $_POST["$boat_number"]) {
            array_push($errors, "Cannot select DNC and enter a score for $boat_number $boat_type");
        }
        if ($_POST["$boat_id"] == "DNF" and $_POST["$boat_number"]) {
            array_push($errors, "Cannot select DNF and enter a score for $boat_number $boat_type");
        }
    }
    if (count($errors) != 0) {
        //select all units
        $sql = "SELECT * FROM regattascoring.UNIT;";
        $outcome = mysqli_query($conn, $sql);

        //create form for score input?>
        <html>
          <head>
            <title><?php echo "Update ".$class_row['class_name'] . " " . $activity_row['activity_name']?></title>
          </head>
            <h1><?php echo "Update ".$class_row['class_name'] . " " . $activity_row['activity_name']?></h1>
            <h1>Race <?php echo $race_number ?></h1>
          <body>
            If unit completed sailing, select completed and enter score <br>
            Input results in seconds
            <br>
            <form action = <?php echo "editsailing.php?event_id=$event_id&activity_id=$activity_id&class_id=$class_id&race_number=$race_number&boat_type=$boat_type method='POST'>";
        while ($unit_row = mysqli_fetch_assoc($outcome)) {
            echo $unit_row['unit_name'] . "<br>";
            $unit_id = $unit_row['unit_id'];

            //select boats from unit
            $sql = "SELECT * FROM regattascoring.BOAT WHERE unit_id = $unit_id AND boat_type = '$boat_type'";
            $boats = mysqli_query($conn, $sql);

            //check if there are boats
            if (mysqli_num_rows($boats) == 0) {
                echo "<br> No Boats <br>";
            } else {
                while ($boat_row = mysqli_fetch_assoc($boats)) {
                    $boat_id = $boat_row['boat_id'];
                    $boat_num = $boat_row['boat_number'];

                    echo $boat_num . " Score: ";
                    echo "<input type='number' name='$boat_num' placeholder='Score' min=0 max=5000 value =" . $_POST["$boat_num"] . ">";
                    echo "<select name='$boat_id'>
                    <option value='completed'";
                    if ($_POST["$boat_id"] == "completed") {
                        echo " selected";
                    }
                    echo "> completed </option>";
                    echo "<option value='DNC'" ;
                    if ($_POST["$boat_id"] == "DNC") {
                        echo " selected";
                    }
                    echo "> DNC </option>";
                    echo "<option value='DNF'";
                    if ($_POST["$boat_id"] == "DNF") {
                        echo " selected";
                    }
                    echo "> DNF </option>
                    </select>";
                    echo "<br>";
                }
            }
        } ?>
            <button type="submit" name="update">Update</button>
            <button type="submit" name="delete">Delete</button>
            </form>
          </body>
        </html>
        <?php

        //echo errors from the input sanitsation
        $issue = "";
        foreach ($errors as $error) {
            $issue = $issue . $error . "</br>";
        }

        echo $issue;

        echo "<br>
        <a href='/'>Return Home</a>
        <br>
        <a href=../enrolment.php?event_id=$event_id>Select Activity</a>
        <br>
        <a href=../../indexselectedevent.php?event_id=$event_id>Return to Event Page</a>";
        mysqli_close($conn);
        exit;
    }
    //if passed input sanitsation
    //create array to sort completed results
    $sort = array();

    //add all completed results to array
    //select all units
    $sql = "SELECT * FROM regattascoring.UNIT;";
    $result = mysqli_query($conn, $sql);
    $numunits = (mysqli_num_rows($result));

    while ($unit_boat = mysqli_fetch_assoc($result)) {
        //define variables from loop
        $unit_id = $unit_boat['unit_id'];
        $unit_name = $unit_boat['unit_name'];
        //select all boats from unit
        $sql = "SELECT * FROM regattascoring.BOAT WHERE boat_type ='$boat_type'
        AND unit_id = $unit_id;";
        $boat_result = mysqli_query($conn, $sql);

        //set array to find best score
        $unit_array = array();

        while ($boat_row = mysqli_fetch_assoc($boat_result)) {
            //define id and name
            $boat_id = $boat_row['boat_id'];
            $boat_number = $boat_row['boat_number'];

            //if result is completed
            if ($_POST["$boat_id"] == "completed") {
                //select boat to apply handicap
                $sql = "SELECT * FROM regattascoring.BOAT WHERE boat_id = '$boat_id';";
                $handicap_result = mysqli_query($conn, $sql);
                $handicap = mysqli_fetch_assoc($handicap_result);
                $time = $_POST["$boat_number"];

                $final_time = $time * $handicap['boat_handicap'];
                //push values into array
                array_push($unit_array, $final_time);
            }
        }
        if ($unit_array) {
            $add = min($unit_array);
            $sort += [$unit_id => $add];
        }
    }

    //sort array in descending order
    asort($sort);

    //set count as 1
    $count = 0;

    //set score as 100
    $base = 100;

    //foreach loop to calculate score
    foreach ($sort as $unit_id => $score) {
        //if first unit
        if ($count == 0) {
            //select race_id for update
            $race = race_id($conn, $event_id, $activity_id, $unit_id, $class_id, $race_number);
            //if new value
            if (mysqli_num_rows($race) == 0) {
                //insert value into table
                input($conn, $activity_id, $unit_id, $class_id, 'completed', 100, $score, $event_id, $race_number);
            } else {
                $race_row = mysqli_fetch_assoc($race);
                $race_id = $race_row['race_id'];

                //update value into table
                updatesailing($conn, 'completed', 100, $score, $race_id, $race_number);
            }
        } else {
            //set number for calculate as one less than count
            $number = $count-1;
            //calculate score
            $placescore = $base - ($numunits - $number);
            //select race_id for update
            $race = race_id($conn, $event_id, $activity_id, $unit_id, $class_id, $race_number);

            if (mysqli_num_rows($race) == 0) {
                //insert value into table
                input($conn, $activity_id, $unit_id, $class_id, 'completed', $placescore, $score, $event_id, $race_number);
            } else {
                $race_row = mysqli_fetch_assoc($race);
                $race_id = $race_row['race_id'];
                //insert value into table
                updatesailing($conn, 'completed', $placescore, $score, $race_id, $race_number);
            }
            //set base as calculated score
            $base = $placescore;
        }
        $count++;
    }
    //if did not compete (DNF)
    //select all units
    $sql = "SELECT * FROM regattascoring.UNIT;";
    $result = mysqli_query($conn, $sql);
    while ($completed = mysqli_fetch_assoc($result)) {
        //define unit id
        $unit_id = $completed['unit_id'];

        //select boats from unit
        $sql = "SELECT * FROM regattascoring.BOAT WHERE boat_type='$boat_type' AND unit_id = '$unit_id';";

        $boat_result = mysqli_query($conn, $sql);
        //array for checking
        $check = array();
        $count = 0;

        while ($boat_completed = mysqli_fetch_assoc($boat_result)) {
            $boat_number_check = $boat_completed['boat_number'];
            $boat_id_check = $boat_completed['boat_id'];
            //check if DNF is best result
            $check += [$boat_number_check => $_POST["$boat_id_check"]];
        }
        foreach ($check as $boat_num => $sailing_result) {
            if ($sailing_result == "completed") {
                $count++;
            }
        }
        //IF DNF is the best result
        if ($count == 0) {
            //select boats from unit
            $sql = "SELECT * FROM regattascoring.BOAT WHERE boat_type='$boat_type' AND unit_id = '$unit_id';";
            $boat_result = mysqli_query($conn, $sql);
            while ($boat_completed = mysqli_fetch_assoc($boat_result)) {

                //define boat id
                $boat_id = $boat_completed['boat_id'];

                //check if there is a result
                $sql ="SELECT * FROM regattascoring.RACE_ENROLMENT WHERE event_id=$event_id
             and activity_id=$activity_id and class_id=$class_id and race_number=$race_number
             and unit_id=$unit_id;";
                $sql;
                $check_result = mysqli_query($conn, $sql);

                if ($_POST["$boat_id"] == "DNF") {
                    //set score
                    $placescore = $base-1;
                    //insert value into table

                    if (mysqli_num_rows($check_result) == 0) {
                        //if the boat DNF Race
                        input($conn, $activity_id, $unit_id, $class_id, 'DNF', $placescore, 'NULL', $event_id, $race_number);
                    } else {
                        //select race_id for update
                        $race = race_id($conn, $event_id, $activity_id, $unit_id, $class_id, $race_number);
                        $race_row = mysqli_fetch_assoc($race);
                        $race_id = $race_row['race_id'];
                        //update
                        updatesailing($conn, 'DNF', $placescore, 'NULL', $race_id, $race_number);
                    }
                }
            }
        }
    }

    //if did not compete (DNC)
    //select all units
    $sql = "SELECT * FROM regattascoring.UNIT;";
    $result = mysqli_query($conn, $sql);
    while ($completed = mysqli_fetch_assoc($result)) {
        //define unit id
        $unit_id = $completed['unit_id'];
        //select boats from unit
        $sql = "SELECT * FROM regattascoring.BOAT WHERE boat_type='$boat_type' AND unit_id = '$unit_id';";

        $boat_result = mysqli_query($conn, $sql);
        //array for checking
        $check = array();
        $count = 0;

        while ($boat_completed = mysqli_fetch_assoc($boat_result)) {
            $boat_number_check = $boat_completed['boat_number'];
            $boat_id_check = $boat_completed['boat_id'];
            //check if DNC is best result
            $check += [$boat_number_check => $_POST["$boat_id_check"]];
        }
        foreach ($check as $boat_num => $sailing_result) {
            if ($sailing_result == "completed" or $sailing_result == "DNF") {
                $count++;
            }
        }
        //IF DNC is the best result
        if ($count == 0) {
            //select boats from unit
            $sql = "SELECT * FROM regattascoring.BOAT WHERE boat_type='$boat_type' AND unit_id = '$unit_id';";
            $boat_result = mysqli_query($conn, $sql);
            while ($boat_completed = mysqli_fetch_assoc($boat_result)) {

                //define boat id
                $boat_id = $boat_completed['boat_id'];

                //check if there is a result
                $sql ="SELECT * FROM regattascoring.RACE_ENROLMENT WHERE event_id=$event_id
             and activity_id=$activity_id and class_id=$class_id and race_number=$race_number
             and unit_id=$unit_id;";
                $check_result = mysqli_query($conn, $sql);

                if ($_POST["$boat_id"] == "DNC") {
                    //set score
                    $placescore = $base-2;
                    //insert value into table

                    if (mysqli_num_rows($check_result) == 0) {
                        //if the boat DNC Race
                        input($conn, $activity_id, $unit_id, $class_id, 'DNC', $placescore, 'NULL', $event_id, $race_number);
                    } else {
                        //select race_id for update
                        $race = race_id($conn, $event_id, $activity_id, $unit_id, $class_id, $race_number);
                        $race_row = mysqli_fetch_assoc($race);
                        $race_id = $race_row['race_id'];
                        //update
                        updatesailing($conn, 'DNC', $placescore, 'NULL', $race_id, $race_number);
                    }
                }
            }
        }
    }

    //check if any teams are tied
    tied($conn, $event_id, $activity_id, $class_id, $race_number);

    //update sailing table
    $sql ="SELECT * FROM regattascoring.UNIT;";
    $unit_result = mysqli_query($conn, $sql);
    while ($unit_row = mysqli_fetch_assoc($unit_result)) {
        //select unit_id for the where statement and input/update
        $unit_id = $unit_row['unit_id'];

        //select all boats
        $sql = "SELECT * FROM regattascoring.BOAT;";
        $boat_result = mysqli_query($conn, $sql);
        while ($boat_row = mysqli_fetch_assoc($boat_result)) {
            //define boat id, boat_number, time and result for input into sailing table
            $boat_id = $boat_row['boat_id'];
            $boat_number = $boat_row['boat_number'];
            $sailing_result = $_POST["$boat_id"];
            $sailing_time = $_POST["$boat_number"];
            if (!$sailing_time) {
                $sailing_time = 'NULL';
            }

            //see if boat already exists in sailing table
            $sql = "SELECT * FROM regattascoring.SAILING WHERE event_id=$event_id AND
        activity_id=$activity_id and race_number=$race_number and class_id=$class_id
        and boat_id=$boat_id;";
            $result = mysqli_query($conn, $sql);

            //if no rows in table with conditions
            if (mysqli_num_rows($result) == 0) {
                $sql = "INSERT INTO regattascoring.SAILING (event_id, activity_id,
            class_id, race_number, unit_id, boat_id, sailing_time, sailing_result)
            VALUES ('$event_id', '$activity_id', '$class_id', '$race_number',
              '$unit_id', '$boat_id', $sailing_time, '$sailing_result');";
                $input = mysqli_query($conn, $sql);
                if (!$input) {
                    echo "Could not add boats to sailing table<br>" . mysqli_error($conn);
                    mysqli_close($conn);
                    exit;
                }
            } else {
                $sql = "UPDATE regattascoring.SAILING set sailing_time = $sailing_time,
           sailing_result = '$sailing_result' WHERE event_id = $event_id and
           activity_id = $activity_id and class_id = $class_id and race_number
           = $race_number and boat_id = $boat_id;";
                $update = mysqli_query($conn, $sql);
                if (!$update) {
                    echo "Could not update boat in sailing table<br>" . mysqli_error($conn);
                    mysqli_close($conn);
                    exit;
                }
            }
        }
    }

    echo $activity_row['activity_name'] . " Results Updated
    <br>
    <a href=editsailing.php?event_id=$event_id&activity_id=$activity_id&class_id=$class_id&race_number=$race_number&boat_type=$boat_type>Edit Activity</a>";
} else {
    // present preentered results
    //select all units
    $sql = "SELECT * FROM regattascoring.UNIT;";
    $outcome = mysqli_query($conn, $sql);

    //create form for score input?>
  <html>
    <head>
      <title><?php echo "Update " . $class_row['class_name'] . " " . $activity_row['activity_name']?></title>
    </head>
      <h1><?php echo "Update " . $class_row['class_name'] . " " . $activity_row['activity_name']?></h1>
      <h1>Race <?php echo $race_number ?></h1>
    <body>
      If unit completed sailing, select completed and enter score
      <br>
      Input results in seconds
      <br>
      <form action = <?php echo "editsailing.php?event_id=$event_id&activity_id=$activity_id&class_id=$class_id&race_number=$race_number&boat_type=$boat_type method='POST'>";
    while ($unit_row = mysqli_fetch_assoc($outcome)) {
        echo $unit_row['unit_name'] . "<br>";
        $unit_id = $unit_row['unit_id'];

        //select all boats from unit
        $sql = "SELECT * FROM regattascoring.BOAT WHERE unit_id = $unit_id AND boat_type = '$boat_type'";
        $boats = mysqli_query($conn, $sql);
        while ($boat_row = mysqli_fetch_assoc($boats)) {
            //define boat id for selecting boat from sailing table
            $boat_id = $boat_row['boat_id'];

            //select boat from sailing table where boat_id and unit_id match and activity_id
            $sql = "SELECT * FROM regattascoring.SAILING NATURAL JOIN regattascoring.BOAT
          WHERE activity_id = $activity_id and unit_id = $unit_id and boat_id = $boat_id;";
            $sailing_result = mysqli_query($conn, $sql);
            $sailing_row = mysqli_fetch_assoc($sailing_result);
            //define boat number and id for form
            $boat_number = $sailing_row['boat_number'];
            $boat_id = $sailing_row['boat_id'];

            echo $boat_number . " Score: ";
            echo "<input type='number' name='$boat_number' placeholder='Score' min=0 max=5000 value =" . $sailing_row["sailing_time"] . ">";
            echo "<select name='$boat_id'>
          <option value='completed'";
            if ($sailing_row["sailing_result"] == "completed") {
                echo " selected";
            }
            echo "> completed </option>";
            echo "<option value='DNC'" ;
            if ($sailing_row["sailing_result"] == "DNC") {
                echo " selected";
            }
            echo "> DNC </option>";
            echo "<option value='DNF'" ;
            if ($sailing_row["sailing_result"] == "DNF") {
                echo " selected";
            }
            echo "> DNF </option>
            </select>";
            echo "<br>";
        }
    } ?>
      <button type="submit" name="update">Update</button>
      <button type="submit" name="delete">Delete</button>
      </form>
    </body>
  </html>
  <?php
}

echo "
<br>
<a href='/'>Return Home</a>
<br>
<a href=../enrolment.php?event_id=$event_id>Select Activity</a>
<br>
<a href=../../indexselectedevent.php?event_id=$event_id>Return to Event Page</a>";
