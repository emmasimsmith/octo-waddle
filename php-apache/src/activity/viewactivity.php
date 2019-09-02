<?php
//include navigation bar ,functions and connection php files
include_once '../navbar.php';
include_once "../connection.php";
include_once '../functions.php';

//if delete is selected in form
if (isset($_POST["delete"])) {

    // Get the id number
    $activity_group = mysqli_real_escape_string($conn, $_GET['id']);

    //Select activity name from the table
    $sql = "SELECT DISTINCT activity_name FROM regattascoring.ACTIVITY WHERE
    activity_group = '$activity_group';";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    //delete variables with certain group
    $sql = "DELETE FROM regattascoring.ACTIVITY WHERE activity_group = '$activity_group';";

    //check activity was deleted
    if (!mysqli_query($conn, $sql)) {
        close($conn, "Could not delete activity", "activity", "Activities");
        exit;
    }
    //echo activity deleted and call closing function
    echo $row['activity_name'] . " deleted";
    close($conn, $error, "activity", "Activities");

//if update was selected
} elseif (isset($_POST["update"])) {

    //GET ID
    $activity_group = mysqli_real_escape_string($conn, $_GET['id']);

    //POST variables from form
    $activity_name = mysqli_real_escape_string($conn, $_POST['activity_name']);
    $scoring = mysqli_real_escape_string($conn, $_POST['scoring']);
    $class = $_POST['class'];

    //array for errors
    $errors = array();

    //input sanitsation
    if (!$activity_name) {
        array_push($errors, "Activity name must be entered");
    }

    if (preg_match('/[^A-Za-z \-]/', $activity_name)) {
        array_push($errors, "Please enter a valid activity name");
    }

    //Select class table
    $result = selectall($conn, "class_name", "regattascoring.CLASS", "Class", "activity", "Activities");

    //if there are input sanitsation errors
    if (count($errors) != 0) {
        //call form with existing values?>
    <html>
      <head>
          <title>Create Activity</title>
      </head>
      <h1>Create New Activity</h1>
      <body>
        <form action="viewactivity.php" method ="POST">
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
          <button type="submit" name="update">Update</button>
          <button type="submit" name="delete">Delete</button>
        </form>
      </body>
    </html>
    <?php

      //echo input sanitsation errors
      $issue = '';
        foreach ($errors as $error) {
            $issue = $issue . $error . "</br>";
        }
        close($conn, $issue, "activity", "Activities");
        exit;
    }

    //delete variables with certain group
    $sql = "DELETE FROM regattascoring.ACTIVITY WHERE activity_group = '$activity_group';";

    //check activity was deleted
    if (!mysqli_query($conn, $sql)) {
        close($conn, "Could not delete activity", "activity", "Activities");
        exit;
    }

    //foreach loop of selected classes
    foreach ($class as $class_id) {
        //Insert variables into activity table, if false echo error and exit
        $sql = "INSERT INTO regattascoring.ACTIVITY (activity_name, scoring, class_id, activity_group) VALUES
      ('$activity_name','$scoring', '$class_id', '$activity_group');";
        if (!mysqli_query($conn, $sql)) {
            echo mysqli_error($conn);
            close($conn, "Could not add data", "activity", "Activities");
            exit;
        }
    }

    //echo activity updated and close
    echo $_POST['activity_name'] . " updated";
    close($conn, $error, "activity", "Activities");
} else {
    //GET ID from URL
    $activity_group = mysqli_real_escape_string($conn, $_GET['id']);

    //select activities where matches activity group
    $sql = "SELECT DISTINCT * FROM regattascoring.ACTIVITY WHERE activity_group = $activity_group;";
    $select = mysqli_query($conn, $sql);

    //check if selected
    if (!$select) {
        close($conn, "Could not select table", "activity", "Activities");
        exit;
    }
    if (mysqli_num_rows($select) == 0) {
        close($conn, "Nothing selected", "activity", "Activities");
        exit;
    }

    $row = mysqli_fetch_assoc($select);

    //select class table
    $result = selectall($conn, "class_name", "regattascoring.CLASS", "Class", "activity", "Activities");

    //call form with previous values?>
    <html>
      <head>
          <title>Create Activity</title>
      </head>
      <h1>Create New Activity</h1>
      <body>
        <form action= <?php echo "viewactivity.php?id=$activity_group" ?> method ="POST">
          Activity Name:
          <input type="text" name="activity_name" value="<?php echo $row['activity_name'] ?>" placeholder="Activity name">
          <br>
          Scoring Type:
          <select name="scoring" placeholder="Calculation Method">
            <option value="placing" <?php if ($row['scoring'] == "placing") {
        echo " selected";
    } ?>>Placing</option>
            <option value="scoring"<?php if ($row['scoring'] == "scoring") {
        echo " selected";
    } ?>>Scoring</option>
            <option value="time"<?php if ($row['scoring'] == "time") {
        echo " selected";
    } ?>>Time</option>
          </select>
          <br>
          Classes:
          <br>
          <?php
          while ($class_row = mysqli_fetch_assoc($result)) {
              echo "<input type='checkbox' name='class[]' value=" . $class_row['class_id'] . " ";
              //select all classes entered for the group
              $sql = "SELECT class_id FROM regattascoring.ACTIVITY WHERE activity_group = $activity_group;";
              $activity_class = mysqli_query($conn, $sql);
              while ($activity_class_selected = mysqli_fetch_assoc($activity_class)) {
                  if ($class_row['class_id'] == $activity_class_selected['class_id']) {
                      echo "checked";
                  }
              }
              echo " >" . $class_row['class_name'] . "</input> <br>";
          } ?>
          <br>
          <button type="submit" name="update">Update</button>
          <button type="submit" name="delete">Delete</button>
        </form>
      </body>
    </html>
  <?php

  //call closing function
  close($conn, $error, "activity", "Activities");
}
?>
