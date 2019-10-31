<?php
//include functions and connection php files
include_once '../../connection.php';
include_once '../../functions.php';
include_once 'ironwomanfunctions.php';

//define activity_id
$activity_id = $_GET['activity_id'];
$event_id = $_GET['event_id'];

//check if activity has been already made
$sql = "SELECT * FROM regattascoring.RACE_ENROLMENT where event_id = '$event_id'
AND activity_id = '$activity_id';";
$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) != 0) {
    //if activity has already been made, go to edit ironwoman page
    header("Location: editironwoman.php?event_id=$event_id&activity_id=$activity_id");
} elseif (isset($_POST['submit'])) {
    //include navbar
    include_once '../../navbar.php';

    //create array to sort completed results
    $sort = array();

    //add all completed results to array
    //select all units
    $sql = "SELECT * FROM regattascoring.UNIT;";
    $result = mysqli_query($conn, $sql);
    $numunits = (mysqli_num_rows($result));

    while ($completed = mysqli_fetch_assoc($result)) {
        //define variables from loop
        $unit_id = $completed['unit_id'];
        $unit_name = $completed['unit_name'];

        //if result is completed
        if ($_POST["$unit_id"] != "DNC") {
            //push values into array
            $sort += [$unit_id => $_POST["$unit_id"]];
        }
    }
    //sort array
    asort($sort);
    //array for errors
    $errors = array();
    //define the last value
    $last = end($sort);

    foreach ($sort as $unit_id => $placing) {
        $tie = array_count_values($sort);

        if ($tie["$placing"] > 1) {

            // if more than one of place
            //set tie count to reach as number of units tied - 1
            $tie_count = $tie["$placing"];

            //set count as 1
            $count = 1;

            while ($count != $tie_count) {

                //calculate next placing
                $next_result = $placing + $count;

                //check if there is next placing
                if ($tie["$next_result"] != 0) {
                    array_push($errors, "Cannot have unit placed at $next_result with a tie at $placing");
                }
                //increase count by one
                $count++;
            }
            //check if unit is placed after tie
            $after = $placing + $count;
            if ($after < $last) {
                if ($tie["$after"] == 0) {
                    array_push($errors, "Must have a unit placed $after");
                }
            }
        }
        //if not tied check to see if there is a following result
        if ($tie["$placing"] == 1) {
            //check not the last result
            if ($placing != $last) {
                //calculate the next placing
                $next_placing = $placing + 1;
                if ($tie["$next_placing"] == 0) {
                    array_push($errors, "Must have a unit placed at $next_placing");
                }
            }
        }
    }

    if (count($errors) != 0) {
        //Select Activity with activity id
        $sql = "SELECT * FROM regattascoring.ACTIVITY WHERE activity_id = '$activity_id';";
        $result = mysqli_query($conn, $sql);
        $activity_row = mysqli_fetch_assoc($result);

        //select all units
        $sql = "SELECT * FROM regattascoring.UNIT;";
        $outcome = mysqli_query($conn, $sql);
        $numunits = mysqli_num_rows($outcome);

        //create form for score input?>
      <html>
        <head>
          <title><?php echo $activity_row['activity_name']?></title>
        </head>
          <h1><?php echo $activity_row['activity_name']?></h1>
        <body>
          <br>
          <form action = <?php echo "ironwoman.php?event_id=$event_id&activity_id=$activity_id method='POST'>";
        while ($unit_row = mysqli_fetch_assoc($outcome)) {
            $unit_id = $unit_row['unit_id'];
            echo $unit_row['unit_name'] . " Placing: ";
            echo "<select name='". $unit_row['unit_id'] . "'>";

            //set count as one
            $count = 1;

            while ($count <= $numunits) {
                echo "<option value='$count'";
                if ($_POST["$unit_id"] == $count) {
                    echo " selected ";
                }
                echo ">$count</option>";
                //increase count by one
                $count++;
            }
            echo "<option value='DNC'";
            if ($_POST["$unit_id"] == "DNC") {
                echo " selected ";
            }
            echo ">DNC</option>";
            echo "</select>";
            echo "<br>";
        } ?>
            <button type="submit" name="submit">Enter</button>
          </form>
        </body>
      </html>
      <?php
      //remove all duplicate strings in errors
        $unique = array_unique($errors);
        //echo errors from the input sanitsation
        $issue = "";
        foreach ($unique as $error) {
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
    //set count as 1
    $count = 0;
    //set score as 100
    $base = 100;

    //foreach loop to calculate score
    foreach ($sort as $unit_id => $score) {
        //if first unit
        if ($count == 0) {
            //insert value into table
            input($conn, $activity_id, $unit_id, 'completed', '100', $score, $event_id);
        } else {
            //if not first class
            //set number for calculate as one less than count
            $number = $count-1;
            //calculate place score
            $placescore = $base - ($numunits - $number);
            //insert value into table
            input($conn, $activity_id, $unit_id, 'completed', $placescore, $score, $event_id);
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
        $unit_id = $completed['unit_id'];
        if ($_POST["$unit_id"] == "DNF") {
            //set score
            $placescore = $base-1;
            //insert value into table
            input($conn, $activity_id, $unit_id, 'DNF', $placescore, 'NULL', $event_id);
        }
    }
    //if did not compete (DNC)
    //select all units
    $sql = "SELECT * FROM regattascoring.UNIT;";
    $result = mysqli_query($conn, $sql);
    while ($completed = mysqli_fetch_assoc($result)) {
        $unit_id = $completed['unit_id'];
        if ($_POST["$unit_id"] == "DNC") {
            //set score
            $placescore = $base-2;
            //insert value into table
            input($conn, $activity_id, $unit_id, 'DNC', $placescore, 'NULL', $event_id);
        }
    }
    //check if any teams are tied
    tied($conn, $event_id, $activity_id);

    echo "Race results added";
    echo "<br>
    <a href=editironwoman.php?event_id=$event_id&activity_id=$activity_id>Edit Activity</a>
    <br>
    <a href='/'>Return Home</a>
    <br>
    <a href=../enrolment.php?event_id=$event_id>Select Activity</a>
    <br>
    <a href=../../indexselectedevent.php?event_id=$event_id>Return to Event Page</a>";
} else {
    //include navbar
    include_once '../../navbar.php';

    //Select Activity with activity id
    $sql = "SELECT * FROM regattascoring.ACTIVITY WHERE activity_id = '$activity_id';";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    //select all units
    $sql = "SELECT * FROM regattascoring.UNIT;";
    $outcome = mysqli_query($conn, $sql);
    $numunits = mysqli_num_rows($outcome);

    //create form for score input?>
    <html>
      <head>
        <title><?php echo $row['activity_name']?></title>
      </head>
        <h1><?php echo $row['activity_name']?></h1>
      <body>
        <br>
        <form action = <?php echo "ironwoman.php?event_id=$event_id&activity_id=$activity_id method='POST'>";
    while ($unit_row = mysqli_fetch_assoc($outcome)) {
        echo $unit_row['unit_name'] . " Placing: ";
        echo "<select name='". $unit_row['unit_id'] . "'>";

        //set count as include_once
        $count = 1;

        while ($count <= $numunits) {
            echo "<option value='$count'>$count</option>";
            //increase count by one
            $count++;
        }
        echo "<option value='DNC'>DNC</option>";
        echo "</select>";
        echo "<br>";
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
