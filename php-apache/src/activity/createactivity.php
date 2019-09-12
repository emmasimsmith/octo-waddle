<?php
//include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

//get event id from GET
$event_id = $_GET['event_id'];

function activityform($result, $event_id)
{
    $sql = "SELECT * FROM regattascoring.EVENT WHERE event_id = $event_id;"; ?>
  <html>
  <head>
      <title>Create Activity</title>
  </head>
  <h1>Create New Activity</h1>
  <body>

  <form action="createactivity.php" method ="POST">
    Activity Name:
    <input type="text" name="activity_name" placeholder="Activity name">
    <br>
    Scoring Method:
    <select name="scoring" placeholder="Calculation Method">
      <option value="placing">Placing</option>
      <option value="scoring">Scoring</option>
      <option value="time">Time</option>
    </select>
    <br>
    Unit or Classes:
    <select name="bracket">
      <option value="unit">Units</option>
      <option value="class">Classes</option>
    </select>
    <br>
    If classes selected, specify which classes:
    <br>
    <?php
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<input type='checkbox' name='class[]' value=" . $row['class_id'];
        echo ">" . $row['class_name'] . "</input>" . "<br>";
    } ?>
    <button type="submit" name="submit">Enter</button>
  </form>
  </body>
  </html>
  <?php
}
if (isset($_POST["submit"])) {

  //POST variables from form
    $activity_name = mysqli_real_escape_string($conn, $_POST['activity_name']);
    $scoring = mysqli_real_escape_string($conn, $_POST['scoring']);
    $bracket = mysqli_real_escape_string($conn, $_POST['bracket']);
    $class = $_POST['class'];

    //array for input sanitsation errors
    $errors = array();

    //input sanitsation
    if (!$activity_name) {
        array_push($errors, "Activity name must be entered");
    }

    if (preg_match('/[^A-Za-z \-]/', $activity_name)) {
        array_push($errors, "Please enter a valid activity name");
    }
    if ($bracket == "unit" and $class != "") {
        array_push($errors, "Cannot select unit and classes");
    }

    //select all function from specific table
    $result = selectall($conn, "class_name", "regattascoring.CLASS", "Class", "activity", "Activities");

    //if errors, echo and exit
    if (count($errors) != 0) {
        //call form with existing values?>
      <html>
        <head>
            <title>Create Activity</title>
        </head>
        <h1>Create New Activity</h1>
        <body>
          <form action="createactivity.php" method ="POST">
            Activity Name:
            <input type="text" name="activity_name" value="<?php echo $_POST['activity_name'] ?>" placeholder="Activity name">
            <br>
            Scoring Type:
            <select name="scoring" placeholder="Calculation Method">
              <option value="placing" <?php if ($_POST['scoring'] == "placing") {
            echo " selected";
        } ?>>Placing</option>
              <option value="scoring"<?php if ($_POST['scoring'] == "scoring") {
            echo " selected";
        } ?>>Scoring</option>
              <option value="time"<?php if ($_POST['scoring'] == "time") {
            echo " selected";
        } ?>>Time</option>
            </select>
            <br>
            Unit or Class:
            <select name="bracket">
              <option value="unit" <?php if ($_POST['bracket'] == "unit") {
            echo " selected";
        } ?>>Units</option>
              <option value="class" <?php if ($_POST['bracket'] == "class") {
            echo " selected";
        } ?>>Classes</option>
            </select>
            <br>
            If classes selected, specify which classes:
            <br>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<input type='checkbox' name='class[]' value=" . $row['class_id'] . " ";
                foreach ($class as $match) {
                    if ($row['class_id'] == $match) {
                        echo "checked";
                    }
                }
                echo " >" . $row['class_name'] . "</input> <br>";
            } ?>
            <br>
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
        close($conn, $issue, "activity", "Activities");
        exit;
    }

    //Insert variables into activity table, if false echo error and exit
    $sql = "INSERT INTO regattascoring.ACTIVITY (activity_name, scoring, activity_bracket) VALUES
    ('$activity_name','$scoring', '$bracket');";
    if (!mysqli_query($conn, $sql)) {
        echo mysqli_error($conn);
        close($conn, "Could not add data", "activity", "Activities");
        exit;
    }

    //select most previous id
    $activity_id = mysqli_insert_id($conn);

    if ($bracket == "class") {
        foreach ($class as $input) {
            // add to bracket table
            $sql = "INSERT INTO regattascoring.BRACKET (activity_id, class_id) VALUES
            ('$activity_id', '$input');";
            if (!mysqli_query($conn, $sql)) {
                echo mysqli_error($conn);
                close($conn, "Could not add data to bracket table", "activity", "Activities");
                exit;
            }
        }
    }

    //echo activity created
    echo $_POST['activity_name'] . " Activity Created"; ?>
    <br>
    <a href = <?php echo "viewactivity.php?id=$activiy_id>Edit ";
    echo $_POST['activity_name'] . " Activity</a>";

    //call select all function for form
    $row = selectall($conn, "class_name", "regattascoring.CLASS", "Class", "activity", "Activities");

    //call activity form
    activityform($row, $event_id);

    //call closing function
    close($conn, $error, "activity", "Activities");
} else {

  //call select all function for form
    $row = selectall($conn, "class_name", "regattascoring.CLASS", "Class", "activity", "Activities");

    //call activity form
    activityform($row, $event_id);

    //call close
    close($conn, $error, "activity", "Activities");
}
