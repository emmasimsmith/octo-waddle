<?php
include_once 'navbar.php';
include_once 'connection.php';

//GET event id
$event_id = $_GET['event_id'];

$sql = "SELECT * FROM regattascoring.EVENT WHERE event_id = $event_id;";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

?>
<html>
  <head>
      <title>Octo Waddle</title>
  </head>
      <h1> <?php echo $row['location'] ?> Event</h1>
  <body>
    <?php
    echo
    "<a href=/participant/selectparticipant.php?event_id=$event_id>Select Participants</a>
    <br>
    <a href=/participant/searchparticipant.php?event_id=$event_id>View all Participants</a>
    <br>
    <a href=/race_enrolment/createrace_enrolment.php?event_id=$event_id>Enroll Participants</a>";
     ?>
     <br>
  </body>
</html>
