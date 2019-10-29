<html>
  <head>
    <title>Create Individual</title>
    <link rel="stylesheet" type="text/css" href="../stylesheets/navbarstyle.css">
    <link rel="stylesheet" type="text/css" href="../stylesheets/pagestyle.css">
  </head>

<?php
//include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

//if delete is selected in form
if (isset($_POST["delete"])) {

  //GET ID
    $event_id_escaped = mysqli_real_escape_string($conn, $_GET['id']);

    //Seleet event location from the table
    $sql = "SELECT location, event_date FROM regattascoring.EVENT WHERE event_id
    = '$event_id_escaped';";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    //call delete function
    deletevariable($conn, "event", $event_id_escaped, "regattascoring.EVENT", "Events");

    //echo event deleted and call closing function
    echo "  <div class='container'>
        <div class='content'>
          <body>";
    echo "<div class='message'>Regatta at " . $row['location'] . " deleted</div>";
    close($conn, $error, "event", "Events");

//if form submitted and update selected
} elseif (isset($_POST["update"])) {

  //GET ID
    $event_id_escaped = mysqli_real_escape_string($conn, $_GET['id']);

    //POST variables from form
    $new_location_escaped = mysqli_real_escape_string($conn, $_POST['location']);
    $new_date_escaped = mysqli_real_escape_string($conn, $_POST['date']);

    //array for errors
    $errors = array();

    //input sanitsation
    if (!$new_location_escaped) {
        array_push($errors, "Location must be entered");
    }
    if (preg_match('/[^A-Za-z \-]/', $new_location_escaped)) {
        array_push($errors, "Please enter a valid location");
    }
    if (!$new_date_escaped) {
        array_push($errors, "Date must be entered");
    }
    if ($new_date_escaped == true and strlen($new_date_escaped) != 10) {
        array_push($errors, "Please enter a valid date");
    }

    //if there are input sanitsation errors
    if (count($errors) != 0) {
        //call form with existing values?>
        <div class='container'>
            <div class='content'>
              <body>
                <h1>Create New Event</h1>
                <ul class="labels">
                  <li>Location:</li>
                  <li>Date:</li>
                </ul>
                <form action= <?php echo "viewevent.php?id=" . $_GET['id']?> method ="POST">
                  <div class="inside-form">
                    <input type="text" name="location" value="<?php echo $_POST['location']?>" placeholder="Location">
                    <input type="date" name="date" value="<?php echo $_POST['date']?>" placeholder="Date">
                  </div>
                  <div class="button">
                    <button type="submit" name="update">Update</button>
                    <button type="submit" name="delete">Delete</button>
                  </div>
                </form>
              </body>
            </html>
            <?php

        //echo input sanitsation errors
        $issue = "";
        foreach ($errors as $error) {
            $issue = $issue . $error . "</br>";
        }
        close($conn, $issue, "event", "Events");
        exit;
    }

    //Update table
    $sql = "UPDATE regattascoring.EVENT set location = '$new_location_escaped',
    event_date = '$new_date_escaped' WHERE event_id = '$event_id_escaped';";

    //Check table updated, if not exit
    if (!mysqli_query($conn, $sql)) {
        close($conn, "Could not update event", "event", "Events");
        exit;
    }

    //echo event updated and close
    echo "  <div class='container'>
        <div class='content'>
          <body>";
    echo "<div class='message'>" . $_POST['location'] . " regatta updated</div>";
    close($conn, $error, "event", "Events");
} else {
    //GET ID
    $event_id = mysqli_real_escape_string($conn, $_GET['id']);

    ///call table select function
    $row = viewselect($conn, $event_id, "event", "regattascoring.EVENT", "Events");

    //call form with previous values?>
    <div class='container'>
        <div class='content'>
          <body>
            <h1>Create New Event</h1>
            <ul class="labels">
              <li>Location:</li>
              <li>Date:</li>
            </ul>
            <form action= <?php echo "viewevent.php?id=" . $_GET['id'] ?> method ="POST">
              <div class="inside-form">
                <input type="text" name="location" value="<?php echo $row['location']?>" placeholder="Location">
                <input type="date" name="date" value="<?php echo $row['event_date']?>" placeholder="Date">
              </div>
              <div class="button">
                <button type="submit" name="update">Update</button>
                <button type="submit" name="delete">Delete</button>
              </div>
            </form>
          </body>
        </html>
        <?php

  //call close
  close($conn, $error, "event", "Events");
}
?>
