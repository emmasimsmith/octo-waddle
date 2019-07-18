<?php
include_once '../navbar.php';
function activityform()
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
    <button type="submit" name="submit">Enter</button>
  </form>
  </body>
  </html>
  <?php
}
if (isset($_POST["submit"])) {
    include_once '../connection.php';

    $activity_name = mysqli_real_escape_string($conn, $_POST['activity_name']);
    $scoring = mysqli_real_escape_string($conn, $_POST['scoring']);

    $errors = array();

    if (!$activity_name) {
        array_push($errors, "Activity name must be entered");
    }

    if (preg_match('/[^A-Za-z \-]/', $activity_name)) {
        array_push($errors, "Please enter a valid activity name");
    }

    if (count($errors) != 0) {
        foreach ($errors as $error) {
            echo $error . "</br>";
        }
        mysqli_close($conn); ?>
        <br>
        <a href="/">Return Home</a>
        <br>
        <a href="createactivity.php">Submit another response</a>
        <br>
        <a href="searchactivity.php">View all activitys</a>
        <?php
        exit;
    }

    $sql = "INSERT INTO regattascoring.ACTIVITY (activity_name, scoring) VALUES
    ('$activity_name','$scoring');";

    if (!mysqli_query($conn, $sql)) {
        echo "ERROR: Could not add data" . mysqli_error($conn) . "</br>";
    }
    $activity_id = mysqli_insert_id($conn);
    echo $_POST['activity_name'] . " Activity Created"; ?>
    <br>
    <a href = <?php echo "viewactivity.php?id=$activity_id"?>>Edit
      <?php echo $_POST['activity_name'] . " Activity" ?></a>
    <php>
    <?php
    activityform();
    mysqli_close($conn); ?>
    <br>
    <a href="/">Return Home</a>
    <br>
    <a href="createactivity.php">Submit another response</a>
    <br>
    <a href="searchactivity.php">View all activitys</a>
    <?php
} else {
        activityform();
    };
