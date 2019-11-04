<html>
  <head>
    <title>View Events</title>
    <link rel="stylesheet" type="text/css" href="../stylesheets/navbarstyle.css">
    <link rel="stylesheet" type="text/css" href="../stylesheets/pagestyle.css">
  </head>

<?php
//include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once "../connection.php";
include_once '../functions.php';

//event form function
function eventsearch()
{
    ?>
    <h1>View Events</h1>
    <div class="search_form">
      <form action= searchevent.php method="POST">
        <div class="form_input">
          <div class="two_input">
            <input type="text" name="location" placeholder="Location">
            <input type="date" name="Date" placeholder="Date">
          </div>
        </div>
        <div class="search_button">
          <button type="submit" name="search">Enter</button>
        </div>
      </form>
    </div>
    </body>
  <?php
}

//if search is submitted
if (isset($_POST['search'])) {
    //call search form
    echo "  <div class='container'>
        <div class='content'>
          <body>";
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
    echo "  <div class='container'>
        <div class='content'>
          <body>";
    eventsearch();

    //variables array
    $variables = array('location' => 'Location', 'event_date' => 'Date');

    //echo all data from table and close
    viewall($conn, "event", "regattascoring.EVENT", $variables, "event_id", "Events");
}
