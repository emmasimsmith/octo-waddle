<html>
  <head>
    <title>Select Event</title>
    <link rel="stylesheet" type="text/css" href="../stylesheets/navbarstyle.css">
    <link rel="stylesheet" type="text/css" href="../stylesheets/pagestyle.css">
  </head>

<?php
include_once 'navbar.php';
include_once 'connection.php';

?>
<div class='container'>
  <div class='content'>
    <body>
      <h1>Events</h1>
      <div class="event_list">
        <ul>
        <?php
        //select all from event table
        $sql = "SELECT * FROM regattascoring.EVENT;";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 0) {
            echo "<div class='message'>No events created</div>
            <div class='close'>
              <ul>
                <li><a href=/event/createevent.php>Create Event</a></li>
                <li><a href='/'>Return Home</a></li>
              </ul>
              </div>
              </div>
              </div>";
            mysqli_close($conn);
            exit;
        }
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li><a href=indexselectedevent.php?event_id=" . $row['event_id'] . ">" . $row['location'] . " Event ". $row['event_date']."</a></li>";
        } ?>
        </ul>
      </div>
    </body>
    <div class='close'>
      <ul>
        <li><a href=/event/createevent.php>Create Event</a></li>
        <li><a href='/'>Return Home</a></li>
      </ul>
    </div>
  </div>
</div>
