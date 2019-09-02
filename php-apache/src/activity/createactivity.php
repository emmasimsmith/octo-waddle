<?php
//include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

function activityform($result)
{
    ?>
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
    Scoring Type:
    <select name="scoring" placeholder="Calculation Method">
      <option value="placing">Placing</option>
      <option value="scoring">Scoring</option>
      <option value="time">Time</option>
    </select>
    <br>
    Classes:
    <br>
    <?php
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<input type='checkbox' name='class[]' value=" . $row['class_id'];
        echo ">" . $row['class_name'] . "</input>" . "<br>";
    } ?>
    <br>
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
            Classes:
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
    //find how many groups there are
    $sql = "SELECT COUNT(DISTINCT activity_group) as total FROM regattascoring.ACTIVITY;";
    $result = mysqli_query($conn, $sql);
    $groups = mysqli_fetch_assoc($result);
    $group = $groups['total'];

    //increase group by one
    $group++;

    //foreach loop of selected classes
    foreach ($class as $class_id) {
        //Insert variables into activity table, if false echo error and exit
        $sql = "INSERT INTO regattascoring.ACTIVITY (activity_name, scoring, class_id, activity_group) VALUES
      ('$activity_name','$scoring', '$class_id', '$group');";
        if (!mysqli_query($conn, $sql)) {
            close($conn, "Could not add data", "activity", "Activities");
            exit;
        }
    }

    //echo activity created
    echo $_POST['activity_name'] . " Activity Created"; ?>
    <br>
    <a href = <?php echo "viewactivity.php?id=$group>Edit ";
    echo $_POST['activity_name'] . " Activity</a>";

    //call select all function for form
    $row = selectall($conn, "class_name", "regattascoring.CLASS", "Class", "activity", "Activities");

    //call activity form
    activityform($row);

    //call closing function
    close($conn, $error, "activity", "Activities");
} else {

  //call select all function for form
    $row = selectall($conn, "class_name", "regattascoring.CLASS", "Class", "activity", "Activities");

    //call activity form
    activityform($row);

    //call close
    close($conn, $error, "activity", "Activities");
}
