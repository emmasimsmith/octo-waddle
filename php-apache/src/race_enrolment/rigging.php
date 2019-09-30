<?php
//include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

//define activity_id
$activity_id = $_GET['activity_id'];

//if form submited
if (isset($_POST['submit'])) {
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
        if ($_POST["$unit_id"] == "finished" and !$_POST["$unit_name"]) {
            array_push($errors, "Enter a score for $unit_name");
        }
        if ($_POST["$unit_id"] == "DNC" and $_POST["$unit_name"]) {
            array_push($errors, "Cannot select DNC and enter a score for $unit_name");
        }
        if ($_POST["$unit_id"] == "DNF" and $_POST["$unit_name"]) {
            array_push($errors, "Cannot select DNF and enter a score for $unit_name");
        }
    }
    if ($errors != "") {
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
            <title><?php echo $row['activity_name']?></title>
          </head>
            <h1><?php echo $row['activity_name']?></h1>
          <body>
            If unit finished rigging, select finished and enter score
            <br>
            <form action = <?php echo "rigging.php?id=$event_id&activity_id=$activity_id method='POST'>";
        while ($unit_row = mysqli_fetch_assoc($outcome)) {
            $unit_id = $unit_row['unit_id'];
            $unit_name = $unit_row['unit_name'];
            echo $unit_row['unit_name'] . " Score: ";
            echo "<input type='number' name='$unit_name' placeholder='Score' min=0 max=150 value =" . $_POST["$unit_name"] . ">";
            echo "<input type ='radio' name='$unit_id' value='finished'";
            if ($_POST["$unit_id"] == "finished") {
                echo " checked";
            }
            echo "> Finished </input>";
            echo "<input type ='radio' name='$unit_id' value='DNC'" ;
            if ($_POST["$unit_id"] == "DNC") {
                echo " checked";
            }
            echo "> DNC </input>";
            echo "<input type ='radio' name='$unit_id' value='DNF'";
            if ($_POST["$unit_id"] == "DNF") {
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
    }
    //echo errors from the input sanitsation
    $issue = "";
    foreach ($errors as $error) {
        $issue = $issue . $error . "</br>";
    }
    echo $issue;
    echo "<br>
    <a href='/'>Return Home</a>
    <br>
    <a href=createrace_enrolment.php?event_id=$event_id>Select Activity</a>
    <br>
    <a href=../indexselectedevent.php?event_id=$event_id>Return to Event Page</a>";
    mysqli_close($conn);
    exit;

    //create array to sort finished results
    $sort = array();

    $sort += [$unit => $_POST["$unit"]];

    //sort array in descending order
    arsort($sort);
} else {
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
    <title><?php echo $row['activity_name']?></title>
  </head>
    <h1><?php echo $row['activity_name']?></h1>
  <body>
    If unit finished rigging, select finished and enter score
    <br>
    <form action = <?php echo "rigging.php?id=$event_id&activity_id=$activity_id method='POST'>";
    while ($unit_row = mysqli_fetch_assoc($outcome)) {
        echo $unit_row['unit_name'] . " Score: ";
        echo "<input type='number' name='" . $unit_row['unit_name']. "' placeholder='Score' min=0 max=150 >";
        echo "<input type ='radio' name='" . $unit_row['unit_id'] . "' value='finished'> Finished </input>";
        echo "<input type ='radio' name='" . $unit_row['unit_id'] . "' value='DNC'> DNC </input>";
        echo "<input type ='radio' name='" . $unit_row['unit_id'] . "' value='DNF'> DNF </input>";
        echo "<br>";
    } ?>
      <button type="submit" name="submit">Enter</button>
    </form>
  </body>
</html>
<br>
<a href='/'>Return Home</a>
<br>
<a href="createrace_enrolment.php?event_id=<?php echo $event_id ?>">Select Activity</a>
<br>
<a href="../indexselectedevent.php?event_id=<?php echo $event_id ?>">Return to Event Page</a>
<?php
}
