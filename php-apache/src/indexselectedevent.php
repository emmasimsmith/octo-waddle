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
    "<ul>
      <li><a href=/participant/selectparticipant.php?event_id=$event_id>Select Participants</a></li>
      <li><a href=/participant/searchparticipant.php?event_id=$event_id>View all Participants</a></li>
      <li><a href=/race_enrolment/enrolment.php?event_id=$event_id>Enroll Participants</a></li>
      <li><a href=/award/calculateawards.php?event_id=$event_id>Calculate Awards</a></li>
    </ul>";
     ?>
  </body>
</html>
