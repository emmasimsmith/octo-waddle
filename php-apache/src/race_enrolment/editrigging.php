<?php
//include functions and connection php files
include_once '../connection.php';
include_once '../functions.php';
include_once '../navbar.php';

//define activity_id
$activity_id = $_GET['activity_id'];
$event_id = $_GET['event_id'];

//TODO present preentered results

//select all units
$sql = "SELECT * FROM regattascoring.UNIT;";
$outcome = mysqli_query($conn, $sql);

//create form for score input?>
<html>
  <head>
    <title><?php echo $row['activity_name']?></title>
  </head>
    <h1><?php echo $row['activity_name']?></h1>
  <body>
    If unit completed rigging, select completed and enter score
    <br>
    <form action = <?php echo "editrigging.php?event_id=$event_id&activity_id=$activity_id method='POST'>";
while ($unit_row = mysqli_fetch_assoc($outcome)) {
    $unit_id = $unit_row['unit_id'];
    $unit_name = $unit_row['unit_name'];

    //Select Activity with activity id where matches unit_id
    $sql = "SELECT * FROM regattascoring.RACE_ENROLMENT NATURAL JOIN regattascoring.ACTIVITY
    WHERE activity_id = '$activity_id' and event_id = '$event_id' and unit_id = '$unit_id';";
    $result = mysqli_query($conn, $sql);
    $race_row = mysqli_fetch_assoc($result);


    echo $unit_row['unit_name'] . " Score: ";
    echo "<input type='number' name='$unit_name' placeholder='Score' min=0 max=150 value =" . $race_row["original_score"] . ">";
    echo "<input type ='radio' name='$unit_id' value='completed'";
    if ($race_row["race_result"] == "completed") {
        echo " checked";
    }
    echo "> completed </input>";
    echo "<input type ='radio' name='$unit_id' value='DNC'" ;
    if ($race_row["race_result"] == "DNC") {
        echo " checked";
    }
    echo "> DNC </input>";
    echo "<input type ='radio' name='$unit_id' value='DNF'";
    if ($race_row["race_result"] == "DNF") {
        echo " checked";
    }
    echo "> DNF </input>";
    echo "<br>";
} ?>
      <button type="submit" name="submit">Enter</button>
    </form>
  </body>
</html>
<?php
//if form submited
