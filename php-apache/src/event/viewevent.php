<?php
include_once '../navbar.php';
include_once "../connection.php";

if (isset($_POST["delete"])) {
    $event_id_escaped = mysqli_real_escape_string($conn, $_GET['id']);
    $event_id = $_GET['id'];

    $sql = "SELECT location, event_date FROM regattascoring.EVENT;";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $location = $row['location'];

    $sql = "DELETE FROM regattascoring.EVENT WHERE event_id = '$event_id_escaped';";

    if (!mysqli_query($conn, $sql)) {
        echo "Could not delete event" . mysqli_error($conn) . "</br>";
        exit;
    }
    echo "Regatta at " . "$location" . " deleted"; ?>
    <html>
    <body>
    <br>
    <a href="/">Return Home</a>
    <br>
    <a href="searchevent.php">View all events</a>
    <br>
    <a href="createevent.php">Submit another response</a>
    </body>
    </html>
    <?php
} elseif (isset($_POST["update"])) {
        $event_id_escaped = mysqli_real_escape_string($conn, $_GET['id']);
        $event_id = $_GET['id'];

        $new_location_escaped = mysqli_real_escape_string($conn, $_POST['location']);
        $new_date_escaped = mysqli_real_escape_string($conn, $_POST['date']);

        $errors = array();

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

        if (count($errors) != 0) {
            foreach ($errors as $error) {
                echo $error . "</br>";
            }
            mysqli_close($conn); ?>
            <br>
            <a href = <?php echo "viewevent.php?id=" . $_GET['id'] ?>>Return to update</a>
            <br>
            <a href="/">Return Home</a>
            <br>
            <a href="createevent.php">Submit another response</a>
            <br>
            <a href="searchevent.php">View all events</a>
            <?php
            exit;
        }

        $location = $_POST['location'];

        $sql = "UPDATE regattascoring.EVENT set location =
        '$new_location_escaped', event_date = '$new_date_escaped'
        WHERE event_id = '$event_id_escaped';";

        if (!mysqli_query($conn, $sql)) {
            echo "Could not update event" . mysqli_error($conn) . "</br>";
            exit;
        }
        echo "$location regatta updated"; ?>
        <html>
        <body>
        <br>
        <a href="/">Return Home</a>
        <br>
        <a href="createevent.php">Submit another response</a>
        <br>
        <a href="searchevent.php">View all events</a>
        </body>
        </html>
        <?php
    } else {
        $event_id = mysqli_real_escape_string($conn, $_GET['id']);

        $sql = "SELECT * FROM regattascoring.EVENT WHERE event_id = '$event_id';";

        $select = mysqli_query($conn, $sql);
        if (!$select) {
            echo "Could not select event table" . "</br>" .mysqli_error($conn) . "<br/>";
            exit;
        }
        echo "selected table successfully" . "</br>";

        if (mysqli_num_rows($select) == 0) {
            echo "Nothing Selected"; ?>
          <a href="/">Return Home</a>
          <br>
          <a href="createevent.php">Submit another response</a>
          <br>
          <a href="searchevent.php">View all events</a>
          <?php
            exit;
        } elseif (mysqli_num_rows($select) >1) {
            echo "Too many regattas selected";
            exit; ?>
          <a href="/">Return Home</a>
          <br>
          <a href="createevent.php">Submit another response</a>
          <br>
          <a href="searchevent.php">View all events</a>
          <?php
        }

        $row = mysqli_fetch_assoc($select); ?>
          <html>
          <body>

          <form action="viewevent.php" method ="POST">
            Location:
            <input type="text" name="location" value="<?php echo $row['location']?>" placeholder="Location">
            <br>
            Date:
            <input type="date" name="date" value="<?php echo $row['event_date']?>" placeholder="Date">
            <br>
            <button type="submit" name="update">Update</button>
            <button type="submit" name="delete">Delete</button>
          </form>
          <a href="/">Return Home</a>
          <br>
          <a href="createevent.php">Submit another response</a>
          <br>
          <a href="searchevent.php">View all events</a>
          </body>
          </html>
          <?php
  mysqli_close($conn);
    }
?>
