<?php
include_once 'navbar.php';
include_once 'connection.php';

?>
<html>
  <head>
      <title>Octo Waddle</title>
  </head>
      <h1>Events</h1>
  <body>
  <br>
    <?php
    //select all from event table
    $sql = "SELECT * FROM regattascoring.EVENT;";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "No events created";
        echo "<a href=/event/createevent.php>Create Event</a>";
        echo "<a href='/'>Return Home</a>";
    }
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<a href=indexselectedevent.php?event_id=" . $row['event_id'] . ">" . $row['location'] . " Event</a>";
        echo "<br>";
    } ?>
  </body>
</html>