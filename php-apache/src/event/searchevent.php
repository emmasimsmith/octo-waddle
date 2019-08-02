<?php
//include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once "../connection.php";
include_once '../functions.php';

//event form function
function eventsearch()
{
    ?>
  <html>
    <body>
      <form action= searchevent.php method="POST">
        <input type="text" name="location" placeholder="Location">
        <input type="date" name="Date" placeholder="Date">
      <button type="submit" name="search">Enter</button>
      </form>
    </body>
  </html>
  <?php
}

//if search is submitted
if (isset($_POST['search'])) {
    //call search form
    eventsearch();

    //POST variables
    $search_location_escaped = mysqli_real_escape_string($conn, $_POST['location']);
    $search_date_escaped = mysqli_real_escape_string($conn, $_POST['date']);

    //validation if strings are empty
    if (!$_POST['location'] and !$_POST['date']) {
        close($conn, "Please enter a valid search", "event", "Events");
        exit;
    }

    //variable array for function
    $variables = array('location' => array('Location' => $search_location_escaped),
  'event_date' => array('Date' => $search_date_escaped));

    //call search function
    search($conn, "event", $variables, "regattascoring.EVENT", "Event", "Events");

    //call close function
    close($conn, $error, "event", "Events");
} else {
    //call event search form
    eventsearch();

    //variables array
    $variables = array('location' => 'Location', 'event_date' => 'Date');

    //echo all data from table and close
    viewall($conn, "event", "regattascoring.EVENT", $variables, "event_id", "Events");
}
