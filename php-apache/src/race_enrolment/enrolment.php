<html>
  <head>
    <title>Enrolment</title>
    <link rel="stylesheet" type="text/css" href="../stylesheets/navbarstyle.css">
    <link rel="stylesheet" type="text/css" href="../stylesheets/pagestyle.css">
  </head>

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
    echo "<div class='container'>
      <div class='content'>
        <body>
        <div class='error>Cannot select from activity table</div>";
    home_close($conn);
}
if (mysqli_num_rows($result) == 0) {
    echo "<div class='container'>
      <div class='content'>
        <body>
        <div class='message'>Please create classes first</div>";
    home_close($conn);
}
//create dropdown form
?>
<div class='container'>
  <div class='content'>
    <body>
      <h1>Race Enrolment</h1>
      <div class="instruction">
        Select an activity to enter race results for
      </div>
      <ul class="labels">
        <li>Select Activity:</li>
      </ul>
        <form autocomplete="off" action = <?php echo "inputrace_enrolment.php?event_id=" . $event_id ?> method='POST'>
          <div class="inside-form">
            <input type="text" name="activity_name" list="activity_list">
            <datalist id="activity_list">
              <?php
              while ($row = mysqli_fetch_assoc($result)) {
                  echo "<option value='". $row['activity_name'] . "'>" . $row['activity_name'] . "</option>";
              } ?>
            </datalist>
          </div>
          <div class="button">
            <button type="submit" name="submit" >Enter</button>
          </div>
        </form>
      </body>
      <div class="close">
        <ul>
          <li><a href='/'>Return Home</a></li>
          <li><a href="../indexselectedevent.php?event_id=<?php echo $event_id ?>">Return to Event Page</a></li>
        </ul>
      </div>
    </div>
  </div>
