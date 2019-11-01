<?php
//include functions and connection php files
include_once '../../connection.php';
include_once '../../functions.php';
include_once '../../navbar.php';
include_once 'campingfunctions.php';

//define activity_id
$activity_id = $_GET['activity_id'];
$event_id = $_GET['event_id'];

//select the activity
$sql = "SELECT activity_name FROM regattascoring.ACTIVITY WHERE activity_id = '$activity_id';";
$activity = mysqli_query($conn, $sql);
$activity_row = mysqli_fetch_assoc($activity);

if (isset($_POST['delete'])) {
    //TODO delete option
    $sql = "DELETE FROM regattascoring.RACE_ENROLMENT WHERE event_id = '$event_id' AND activity_id = '$activity_id';";
    $result = mysqli_query($conn, $sql);

    echo $activity_row['activity_name'] . " results deleted";

// if update selected
} elseif (isset($_POST['update'])) {

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
        //push values into array
        $sort += [$unit_id => $_POST["$unit_name"]];
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

            //select race_id for update
            $race = race_id($conn, $event_id, $activity_id, $unit_id);
            //if new value
            if (mysqli_num_rows($race) == 0) {
                //insert value into table
                input($conn, $activity_id, $unit_id, 'completed', 100, $score, $event_id);
            } else {
                $race_row = mysqli_fetch_assoc($race);
                $race_id = $race_row['race_id'];

                //update value into table
                updatecamping($conn, 'completed', 100, $score, $race_id);
            }
        } else {
            //set number for calculate as one less than count
            $number = $count-1;

            //calculate score
            $placescore = $base - ($numunits - $number);
            //select race_id for update
            $race = race_id($conn, $event_id, $activity_id, $unit_id);

            if (mysqli_num_rows($race) == 0) {
                //insert value into table
                input($conn, $activity_id, $unit_id, 'completed', $placescore, $score, $event_id);
            } else {
                $race_row = mysqli_fetch_assoc($race);
                $race_id = $race_row['race_id'];
                //insert value into table
                updatecamping($conn, 'completed', $placescore, $score, $race_id);
            }
            //set base as calculated score
            $base = $placescore;
        }
        $count++;
    }

    //check if any teams are tied
    tied($conn, $event_id, $activity_id);

    echo $activity_row['activity_name'] . " results updated
    <br>
    <a href=editcamping.php?event_id=$event_id&activity_id=$activity_id>Edit Activity</a>";
} else {
    // present preentered results

    //select all units
    $sql = "SELECT * FROM regattascoring.UNIT;";
    $outcome = mysqli_query($conn, $sql);

    //create form for score input?>
  <html>
    <head>
      <title><?php echo $activity_row['activity_name']?></title>
    </head>
      <h1><?php echo $activity_row['activity_name']?></h1>
    <body>
      Camping results have already been entered
      <br><br>
      <form action = <?php echo "editcamping.php?event_id=$event_id&activity_id=$activity_id method='POST'>";
    while ($unit_row = mysqli_fetch_assoc($outcome)) {
        $unit_id = $unit_row['unit_id'];
        $unit_name = $unit_row['unit_name'];

        //Select Activity with activity id where matches unit_id
        $sql = "SELECT * FROM regattascoring.RACE_ENROLMENT NATURAL JOIN regattascoring.ACTIVITY
      WHERE activity_id = '$activity_id' and event_id = '$event_id' and unit_id = '$unit_id';";
        $result = mysqli_query($conn, $sql);
        $race_row = mysqli_fetch_assoc($result);

        echo $unit_row['unit_name'] . " Score: ";
        echo "<input type='number' name='$unit_name' placeholder='Score' min=0 max=150 value =" . $race_row["original_score"] . " required>";
        echo "<br>";
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
