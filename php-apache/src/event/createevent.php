<html>
  <head>
    <title>Create Event</title>
    <link rel="stylesheet" type="text/css" href="../stylesheets/navbarstyle.css">
    <link rel="stylesheet" type="text/css" href="../stylesheets/pagestyle.css">
  </head>

<?php
//include navigation bar, connection and function files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

//function for event form
function eventform()
{
    ?>
    <h1>Create New Event</h1>
    <ul class="labels">
      <li>Location:</li>
      <li>Date:</li>
    </ul>
    <form action="createevent.php" method ="POST">
      <div class="inside-form">
        <input type="text" name="location" placeholder="Location">
        <input type="date" name="date" placeholder="Date">
      </div>
      <div class="button">
        <button type="submit" name="submit">Enter</button>
      </div>
    </form>
  </body>
  <?php
}

//if form submitted
if (isset($_POST["submit"])) {

  //POST variables from form
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);

    //array for input sanitsation errors
    $errors = array();

    //input sanitsation
    if (!$location) {
        array_push($errors, "Location must be entered");
    }

    if (preg_match('/[^A-Za-z \-\']/', $location)) {
        array_push($errors, "Please enter a valid location");
    }

    if (!$date) {
        array_push($errors, "Date must be entered");
    }
    if ($date == true and strlen($date) != 10) {
        array_push($errors, "Please enter a valid date");
    }

    //echo errors then exit
    if (count($errors) != 0) {
        //call form with existing values?>
        <div class="container">
          <div class="content">
            <body>
              <h1>Create New Event</h1>
              <ul class="labels">
                <li>Location:</li>
                <li>Date:</li>
              </ul>
              <form action="createevent.php" method ="POST">
                <div class="inside-form">
                  <input type="text" name="location" value="<?php echo $_POST['location']?>" placeholder="Location">
                  <input type="date" name="date" value="<?php echo $_POST['date']?>" placeholder="Date">
                </div>
                <div class="button">
                  <button type="submit" name="submit">Enter</button>
                </div>
              </form>
            </body>
          <?php

      //echo errors from the input sanitsation
      $issue = "";
        foreach ($errors as $error) {
            $issue = $issue . $error . "</br>";
        }
        close($conn, $issue, "event", "Events");
        exit;
    }

    //Insert variables into event table, if false echo error and exit
    $sql = "INSERT INTO regattascoring.EVENT (location, event_date)
    VALUES ('$location','$date');";
    if (!mysqli_query($conn, $sql)) {
        close($conn, "Could not add event", "event", "Events");
        exit;
    }

    echo "  <div class='container'>
        <div class='content'>
          <body>";
    //echo event created
    echo "<div class='message'>Regatta at " . $_POST['location'] . " created";
    $event_id = mysqli_insert_id($conn); ?>
    <br>
    <a href = <?php echo "viewevent.php?id=$event_id"?>><?php echo "Edit " . $_POST['location'] . " Regatta"?></a></div>
    <?php

    //call event form
    eventform();

    //call closing function
    close($conn, $error, "event", "Events");
} else {
    //call event form
    echo "  <div class='container'>
        <div class='content'>
          <body>";
    eventform();

    //call closing function
    close($conn, $error, "event", "Events");
};
