<?php
//include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

//get event ID
$event_id = $_GET['event_id'];

//select all activities for the form
$sql = "SELECT * FROM regattascoring.ACTIVITY;";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "Cannot select from activity table";
    home_close($conn);
}
if (mysqli_num_rows($result) == 0) {
    echo "please create classes first";
    home_close($conn);
}
//create dropdown form
?>
<html>
  <head>
    <title>Race Enrolment</title>
  </head>
    <h1>Race Enrolment</h1>
  <body>
    <form autocomplete="off" action = <?php echo "inputrace_enrolment.php?event_id=" . $event_id ?> method='POST'>
      Activity:
      <input type="text" name="activity_name" list="activity_list">
      <datalist id="activity_list">
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='". $row['activity_name'] . "'>" . $row['activity_name'] . "</option>";
        } ?>
      </datalist>
      <br>
      <button type="submit" name="submit" >Enter</button>
    </form>
  </body>
</html>
<br>
<a href='/'>Return Home</a>
<br>
<a href="../indexselectedevent.php?event_id=<?php echo $event_id ?>">Return to Event Page</a>
