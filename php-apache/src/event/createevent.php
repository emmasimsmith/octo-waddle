<?php
//include navigation bar, connection and function files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

//variables for functions
$name = "event";
$plural_name = "Events";

//function for event form
function eventform()
{
    ?>
  <html>
  <head>
      <title>Create Event</title>
  </head>
  <h1>Create New Event</h1>
  <body>

  <form action="createevent.php" method ="POST">
    Location:
    <input type="text" name="location" placeholder="Location">
    <br>
    Date:
    <input type="date" name="date" placeholder="Date">
    <br>
    <button type="submit" name="submit">Enter</button>
  </form>

  </body>
  </html>
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
      <html>
        <head>
          <title>Create Event</title>
        </head>
        <h1>Create New Event</h1>
        <body>
          <form action="createevent.php" method ="POST">
            Location:
            <input type="text" name="location" value="<?php echo $_POST['location']?>" placeholder="Location">
            <br>
            Date:
            <input type="date" name="date" value="<?php echo $_POST['date']?>" placeholder="Date">
            <br>
            <button type="submit" name="submit">Enter</button>
          </form>
        </body>
      </html>
      <?php
      //echo errors from the input sanitsation
        foreach ($errors as $error) {
            $issue = "";
            $issue = $issue . $error . "</br>";
        }
        close($conn, $issue, $name, $plural_name);
        exit;
    }

    //Insert variables into event table, if false echo error and exit
    $sql = "INSERT INTO regattascoring.EVENT (location, event_date)
    VALUES ('$location','$date');";
    if (!mysqli_query($conn, $sql)) {
        $error = "Could not add event";
        close($conn, $error, $name, $plural_name);
        exit;
    }

    //echo event created
    echo "Regatta at " . $_POST['location'] . " created";
    $event_id = mysqli_insert_id($conn); ?>
    <br>
    <a href = <?php echo "viewevent.php?id=$event_id"?>><?php echo "Edit " . $_POST['location'] . " regatta"?></a>
    <?php

    //call event form
    eventform();

    //call closing function
    close($conn, $error, $name, $plural_name);
} else {
    //call event form
    eventform();

    //call closing function
    close($conn, $error, $name, $plural_name);
};
