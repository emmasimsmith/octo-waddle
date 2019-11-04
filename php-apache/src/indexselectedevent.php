<?php
include_once 'navbar.php';
include_once 'connection.php';

//GET event id
$event_id = $_GET['event_id'];

$sql = "SELECT * FROM regattascoring.EVENT WHERE event_id = $event_id;";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$location = $row['location'];?>
<html>
  <head>
    <title><?php echo $location?> Event</title>
    <link rel="stylesheet" type="text/css" href="../stylesheets/navbarstyle.css">
    <link rel="stylesheet" type="text/css" href="../stylesheets/pagestyle.css">
  </head>
  <div class='container'>
    <div class='content'>
      <body>
        <h1>Events</h1>
        <div class="event_list">
        <?php
        echo
        "<ul>
          <li><a href=/participant/selectparticipant.php?event_id=$event_id>Select Participants</a></li>
          <li><a href=/participant/searchparticipant.php?event_id=$event_id>View all Participants</a></li>
          <li><a href=/race_enrolment/enrolment.php?event_id=$event_id>Enroll Participants</a></li>
          <li><a href=/award/calculateawards.php?event_id=$event_id>Calculate Awards</a></li>
        </ul>";
     ?>
   </div>
  </body>
  <div class='close'>
    <ul>
      <li><a href='/'>Return Home</a></li>
      <li><a href='indexevents.php'>Select Event</a></li>
    </ul>
  </div>
</div>
</div>
