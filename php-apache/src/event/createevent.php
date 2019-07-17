<?php
include_once '../navbar.php';
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
if (isset($_POST["submit"])) {
    include_once '../connection.php';
    eventform();

    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);

    $errors = array();

    if (!$location) {
        array_push($errors, "Location must be entered");
    }

    if (preg_match('/[^A-Za-z \-]/', $location)) {
        array_push($errors, "Please enter a valid location");
    }

    if (!$date) {
        array_push($errors, "Date must be entered");
    }
    if ($date == true and strlen($date) != 10) {
        array_push($errors, "Please enter a valid date");
    }

    if (count($errors) != 0) {
        foreach ($errors as $error) {
            echo $error . "</br>";
        }
        mysqli_close($conn); ?>
        <br>
        <a href="/">Return Home</a>
        <br>
        <a href="createevent.php">Submit another response</a>
        <br>
        <a href="searchevent.php">View all Events</a>
        <?php
        exit;
    }

    $sql = "INSERT INTO regattascoring.EVENT (location, event_date)
    VALUES ('$location','$date');";
    if (!mysqli_query($conn, $sql)) {
        echo "ERROR: Could not add event" . mysqli_error($conn) . "</br>";
    }
    $event_id = mysqli_insert_id($conn);
    echo "Regatta at " . $_POST['location'] . " created"; ?>
    <br>
    <a href = <?php echo "viewevent.php?id=$event_id"?>><?php echo "Edit " . $_POST['location'] . " regatta"?></a>
    <?php
    mysqli_close($conn); ?>
    <br>
    <a href="/">Return Home</a>
    <br>
    <a href="createevent.php">Submit another response</a>
    <?php
} else {
        eventform();
    };
