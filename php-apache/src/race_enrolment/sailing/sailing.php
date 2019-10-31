<?php
//include functions and connection php files
//include navbar
include_once '../../navbar.php';
include_once '../../connection.php';
include_once '../../functions.php';
include_once 'sailingfunctions.php';

//define activity_id
$activity_id = $_GET['activity_id'];
$event_id = $_GET['event_id'];
$class_id = $_GET['class_id'];
$boat_type = $_GET['boat_type'];

//select class name to echo in messages
$sql = "SELECT class_name FROM regattascoring.CLASS WHERE class_id = '$class_id';";
$name = mysqli_query($conn, $sql);
$class_row = mysqli_fetch_assoc($name);

if (isset($_POST['submit'])) {
    //GET $race_number
    $race_number = $_GET['race_number'];
    //check there is not already an input for the unit
    //select all units
    $sql = "SELECT * FROM regattascoring.BOAT where boat_type ='$boat_type';";
    $result = mysqli_query($conn, $sql);

    //array for errors
    $errors = array();

    //input sanitisation
    while ($insert = mysqli_fetch_assoc($result)) {
        $boat_id = $insert['boat_id'];
        $boat_number = $insert['boat_number'];

        //run loop for all boats

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
        //Select Activity with activity id
        $sql = "SELECT * FROM regattascoring.ACTIVITY WHERE activity_id = '$activity_id';";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        //select all units
        $sql = "SELECT * FROM regattascoring.UNIT;";
        $outcome = mysqli_query($conn, $sql);

        //create form for score input?>
        <html>
          <head>
            <title><?php echo $class_row['class_name'] . " " . $row['activity_name']?></title>
          </head>
            <h1><?php echo $class_row['class_name'] . " " . $row['activity_name']?></h1>
            <h1>Race <?php echo $race_number ?></h1>
          <body>
            If unit completed sailing, select completed and enter score <br>
            Input results in seconds
            <br>
            <form action = <?php echo "sailing.php?event_id=$event_id&activity_id=$activity_id&class_id=$class_id&race_number=$race_number&boat_type=$boat_type method='POST'>";
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
              <button type="submit" name="submit">Enter</button>
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
            //insert value into table
            input($conn, $activity_id, $unit_id, $class_id, 'completed', '100', $score, $event_id, $race_number);
        } else {
            //if not first class
            //set number for calculate as one less than count
            $number = $count-1;
            //calculate place score
            $placescore = $base - ($numunits - $number);
            //insert value into table
            input($conn, $activity_id, $unit_id, $class_id, 'completed', $placescore, $score, $event_id, $race_number);
            //set base as calculated score
            $base = $placescore;
        }
        $count++;
    }

    //if did not finish (DNF)
    //select all units
    $sql = "SELECT * FROM regattascoring.UNIT;";
    $result = mysqli_query($conn, $sql);
    while ($completed = mysqli_fetch_assoc($result)) {
        //define unit id
        $unit_id = $completed['unit_id'];

        //check there is not already an input for the unit
        $sql = "SELECT * FROM regattascoring.RACE_ENROLMENT WHERE event_id = '$event_id'
      AND activity_id = '$activity_id' AND race_number = '$race_number' AND unit_id = '$unit_id' and class_id ='$class_id';";
        $check = mysqli_query($conn, $sql);

        //if not
        if (mysqli_num_rows($check) == 0) {
            //select boats from unit
            $sql = "SELECT * FROM regattascoring.BOAT WHERE boat_type='$boat_type' AND unit_id = '$unit_id';";
            $boat_result = mysqli_query($conn, $sql);
            while ($boat_completed = mysqli_fetch_assoc($boat_result)) {
                //define boat id
                $boat_id = $boat_completed['boat_id'];

                //if the boat DNF Race
                if ($_POST["$unit_id"] == "DNF") {
                    //set score
                    $placescore = $base-1;
                    //insert value into table
                    input($conn, $activity_id, $unit_id, $class_id, 'DNF', $placescore, 'NULL', $event_id, $race_number);
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
        while ($boat_completed = mysqli_fetch_assoc($boat_result)) {

              //check there is not already an input for the unit
            $sql = "SELECT * FROM regattascoring.RACE_ENROLMENT WHERE event_id = '$event_id'
            AND activity_id = '$activity_id' AND race_number = '$race_number' AND unit_id = '$unit_id' and class_id = '$class_id';";
            $check = mysqli_query($conn, $sql);

            //if not
            if (mysqli_num_rows($check) == 0) {

                //define boat id
                $boat_id = $boat_completed['boat_id'];

                //if the boat DNF Race
                if ($_POST["$unit_id"] == "DNC") {
                    //set score
                    $placescore = $base-2;
                    //insert value into table
                    input($conn, $activity_id, $unit_id, $class_id, 'DNC', $placescore, 'NULL', $event_id, $race_number);
                }
            }
        }
    }
    //check if any teams are tied
    tied($conn, $event_id, $activity_id, $class_id, $race_number);

    //add all results to sailing table to edit
    $sql ="SELECT * FROM regattascoring.UNIT;";
    $unit_result = mysqli_query($conn, $sql);
    while ($unit_row = mysqli_fetch_assoc($unit_result)) {
        //select unit id for the where statement and input
        $unit_id = $unit_row['unit_id'];
        $sql = "SELECT * FROM regattascoring.BOAT WHERE unit_id = $unit_id and boat_type = '$boat_type';";
        $boat_result = mysqli_query($conn, $sql);
        while ($boat_row = mysqli_fetch_assoc($boat_result)) {
            //define boat id, boat number, time and result for input into sailing table
            $boat_id = $boat_row['boat_id'];
            $boat_number = $boat_row['boat_number'];
            $sailing_time = $_POST["$boat_number"];
            if (!$sailing_time) {
                $sailing_time = 'NULL';
            }
            $sailing_result = $_POST["$boat_id"];
            //insert boat into regattascoring
            $sql = "INSERT INTO regattascoring.SAILING (activity_id, race_number, unit_id, boat_id,
          sailing_time, sailing_result) VALUES ('$activity_id', '$race_number', '$unit_id', '$boat_id',
          $sailing_time, '$sailing_result');";
            $input = mysqli_query($conn, $sql);
            if (!$input) {
                echo "Could not add boats to sailing table<br>" . mysqli_error($conn);
                mysqli_close($conn);
                exit;
            }
        }
    }

    echo "Race results added";
    echo "<br>
    <a href=editsailing.php?event_id=$event_id&activity_id=$activity_id&class_id=$class_id&race_number=$race_number&boat_type=$boat_type>Edit Activity</a>
    <br>
    <a href='/'>Return Home</a>
    <br>
    <a href=../enrolment.php?event_id=$event_id>Select Activity</a>
    <br>
    <a href=../../indexselectedevent.php?event_id=$event_id>Return to Event Page</a>";
} else {
    //select largest race number
    $sql = "SELECT MAX(race_number) as max FROM regattascoring.RACE_ENROLMENT WHERE activity_id = $activity_id
     AND event_id = $event_id AND class_id = $class_id;";
    $max_result = mysqli_query($conn, $sql);
    $max_races = mysqli_fetch_assoc($max_result);

    //set race_number
    if (!$max_races['max']) {
        $race_number = 1;
    } else {
        $race_number = $max_races['max'] + 1;
    }
    //Select Activity with activity id to put name in form
    $sql = "SELECT * FROM regattascoring.ACTIVITY WHERE activity_id = '$activity_id';";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    //select all units for the form
    $sql = "SELECT * FROM regattascoring.UNIT;";
    $outcome = mysqli_query($conn, $sql);

    //create form for score input?>
    <html>
      <head>
        <title><?php echo $class_row['class_name'] . " " . $row['activity_name']?></title>
      </head>
        <h1><?php echo $class_row['class_name'] . " " . $row['activity_name']?></h1>
        <h1>Race <?php echo $race_number ?></h1>
      <body>
        If unit completed sailing, select completed and enter score
        <br> Input result in seconds
        <br>
        <form action = <?php echo "sailing.php?event_id=$event_id&activity_id=$activity_id&class_id=$class_id&race_number=$race_number&boat_type=$boat_type method='POST'>";
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
                echo $boat_row['boat_number'] . " Score: ";
                echo "<input type='number' name='" . $boat_row['boat_number']. "' placeholder='Score' min=0 max=5000 >";
                echo "<select name='" . $boat_row['boat_id'] . "'>
                <option value='completed'>completed</option>
                <option value='DNC'>DNC</option>
                <option value='DNF'>DNF</option>
                </select>";
                echo "<br>";
            }
        }
    } ?>
          <button type="submit" name="submit">Enter</button>
        </form>
      </body>
    </html>
    <br>
    <a href='/'>Return Home</a>
    <br>
    <a href="../enrolment.php?event_id=<?php echo $event_id ?>">Select Activity</a>
    <br>
    <a href="../../indexselectedevent.php?event_id=<?php echo $event_id ?>">Return to Event Page</a>
    <?php
}
