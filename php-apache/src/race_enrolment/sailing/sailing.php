<?php
//include functions and connection php files
include_once '../../connection.php';
include_once '../../functions.php';
include_once 'sailingfunctions.php';

//define activity_id
$activity_id = $_GET['activity_id'];
$event_id = $_GET['event_id'];
$class_id = $_GET['class_id'];

//select class name
$sql = "SELECT class_name FROM regattascoring.CLASS WHERE class_id = '$class_id';";
$name = mysqli_query($conn, $sql);
$class_row = mysqli_fetch_assoc($name);

//check if activity has been already made
$sql = "SELECT * FROM regattascoring.RACE_ENROLMENT where event_id = '$event_id'
AND activity_id = '$activity_id' and class_id = '$class_id';";
$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) != 0) {
    //if activity has already been made, go to edit sailing page
    header("Location: editsailing.php?event_id=$event_id&activity_id=$activity_id&class_id=$class_id");
} elseif (isset($_POST['submit'])) {
    //include navbar
    include_once '../../navbar.php';

    //select all units
    $sql = "SELECT * FROM regattascoring.UNIT;";
    $result = mysqli_query($conn, $sql);

    //array for errors
    $errors = array();

    //input sanitisation
    while ($insert = mysqli_fetch_assoc($result)) {
        $unit_id = $insert['unit_id'];
        $unit_name = $insert['unit_name'];

        if (!$_POST["$unit_id"]) {
            array_push($errors, "Select a result for $unit_name");
        }
        if ($_POST["$unit_id"] == "completed" and !$_POST["$unit_name"]) {
            array_push($errors, "Enter a score for $unit_name");
        }
        if ($_POST["$unit_id"] == "DNC" and $_POST["$unit_name"]) {
            array_push($errors, "Cannot select DNC and enter a score for $unit_name");
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
          <body>
            If unit completed life saving, select completed and enter score
            <br>
            <form action = <?php echo "sailing.php?event_id=$event_id&activity_id=$activity_id&class_id=$class_id method='POST'>";
        while ($unit_row = mysqli_fetch_assoc($outcome)) {
            $unit_id = $unit_row['unit_id'];
            $unit_name = $unit_row['unit_name'];

            echo $unit_row['unit_name'] . " Score: ";
            echo "<input type='number' name='$unit_name' placeholder='Score' min=0 max=150 value =" . $_POST["$unit_name"] . ">";
            echo "<input type ='radio' name='$unit_id' value='completed'";
            if ($_POST["$unit_id"] == "completed") {
                echo " checked";
            }
            echo "> completed </input>";
            echo "<input type ='radio' name='$unit_id' value='DNC'" ;
            if ($_POST["$unit_id"] == "DNC") {
                echo " checked";
            }
            echo "> DNC </input>";
            echo "<br>";
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

    while ($completed = mysqli_fetch_assoc($result)) {
        //define variables from loop
        $unit_id = $completed['unit_id'];
        $unit_name = $completed['unit_name'];

        //if result is completed
        if ($_POST["$unit_id"] == "completed") {
            //push values into array
            $sort += [$unit_id => $_POST["$unit_name"]];
        }
    }

    //sort array in descending order
    arsort($sort);
    //set count as 1
    $count = 0;
    //set score as 100
    $base = 100;

    //foreach loop to calculate score
    foreach ($sort as $unit_id => $score) {
        //if first unit
        if ($count == 0) {
            //insert value into table
            input($conn, $activity_id, $unit_id, $class_id, 'completed', '100', $score, $event_id);
        } else {
            //if not first class
            //set number for calculate as one less than count
            $number = $count-1;
            //calculate place score
            $placescore = $base - ($numunits - $number);
            //insert value into table
            input($conn, $activity_id, $unit_id, $class_id, 'completed', $placescore, $score, $event_id);
            //set base as calculated score
            $base = $placescore;
        }
        $count++;
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
            input($conn, $activity_id, $unit_id, $class_id, 'DNC', $placescore, 'NULL', $event_id);
        }
    }
    //check if any teams are tied
    tied($conn);

    echo "Race results added";
    echo "<br>
    <a href=editsailing.php?event_id=$event_id&activity_id=$activity_id&class_id=$class_id>Edit Activity</a>
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

    //create form for score input?>
    <html>
      <head>
        <title><?php echo $class_row['class_name'] . " " . $row['activity_name']?></title>
      </head>
        <h1><?php echo $class_row['class_name'] . " " . $row['activity_name']?></h1>
      <body>
        If unit completed sailing, select completed and enter score
        <br>
        <form action = <?php echo "sailing.php?event_id=$event_id&activity_id=$activity_id&class_id=$class_id method='POST'>";
    while ($unit_row = mysqli_fetch_assoc($outcome)) {
        echo $unit_row['unit_name'] . " Score: ";
        echo "<input type='number' name='" . $unit_row['unit_name']. "' placeholder='Score' min=0 max=100 >";
        echo "<input type ='radio' name='" . $unit_row['unit_id'] . "' value='completed'> completed </input>";
        echo "<input type ='radio' name='" . $unit_row['unit_id'] . "' value='DNC'> DNC </input>";
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
