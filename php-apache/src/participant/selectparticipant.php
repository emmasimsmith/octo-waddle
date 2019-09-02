<?php
//include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

//get event_id
$event_id = $_GET['event_id'];

//array for empty table error
$empty = array();

  //select all from indivdual table
  $sql = "SELECT * FROM regattascoring.INDIVIDUAL;";
$individual = mysqli_query($conn, $sql);
if (mysqli_num_rows($individual) == 0) {
    array_push($empty, "Please create an individual first");
}
  //select all from class table
$sql = "SELECT * FROM regattascoring.CLASS;";
$class = mysqli_query($conn, $sql);
if (mysqli_num_rows($class) == 0) {
    array_push($empty, "Please create a class first");
}

if (count($empty) != 0) {
    $error = "";
    foreach ($empty as $issue) {
        $error = $error . $issue . "</br>";
        participant_close($conn, $error, $event_id);
        exit;
    }
}

//select all from individual and participant where joins
$sql = "SELECT *, regattascoring.INDIVIDUAL.individual_id AS individual_id FROM regattascoring.INDIVIDUAL LEFT JOIN regattascoring.PARTICIPANT
  ON regattascoring.PARTICIPANT.individual_id = regattascoring.INDIVIDUAL.individual_id
  AND event_id = $event_id OR event_id = '';";
  $result = mysqli_query($conn, $sql);

//call form with individuals to select?>
<html>
  <title>Participants</title>
  <head>
    <h1>Select Participants</h1>
  </head>
    <?php echo "<form action=calculateparticipant.php?event_id=$event_id method='POST'>" ?>
        Select Participants:
      <br>
      <?php
      while ($row = mysqli_fetch_assoc($result)) {
          echo "<input type='checkbox' name='selected[]' value=" . $row['individual_id'];
          if ($row['participant_id']) {
              if ($row['event_id'] == $_GET['event_id'] or $row['event_id'] == "") {
                  echo " checked";
              }
          }
          echo  ">" . $row['first_name'] . " " . $row['last_name'];
          echo "</input>
          <br>";
      } ?>
      <button name="select">Select</button>
    </form>
</html>

<?php echo
"<br>
<a href='/'>Return Home</a>
<br>
<a href= searchparticipant.php?event_id=$event_id>View participants</a>";
