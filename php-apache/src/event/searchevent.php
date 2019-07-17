<?php
include_once '../navbar.php';
include_once "../connection.php";
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

if (isset($_POST['search'])) {
    eventsearch();
    $search_location_escaped = mysqli_real_escape_string($conn, $_POST['location']);
    $search_location = $_POST['location'];
    $search_date_escaped = mysqli_real_escape_string($conn, $_POST['date']);
    $search_date = $_POST['date'];

    if (!$_POST['location'] and !$_POST['date']) {
        echo "Please enter a valid search" . mysqli_error($conn);
        mysqli_close($conn); ?>
      <br>
      <br>
      <a href="/">Return Home</a>
      <br>
      <a href="createevent.php">Submit another response</a>
      <br>
      <a href="searchevent.php">View all events</a>
      <?php
      exit;
    }
    $search = array();

    $sql = "SELECT * FROM regattascoring.EVENT WHERE ";

    if ($search_location_escaped != "") {
        array_push($search, "location LIKE '%$search_location_escaped%'");
    }
    if ($search_date_escaped != "") {
        array_push($search, "event_date LIKE '%$search_date_escaped%'");
    }
    $join = join(" AND ", $search);
    $sql = $sql . $join . ";";

    $search = mysqli_query($conn, $sql);
    if (mysqli_num_rows($search) == 0) {
        echo "No matches in table" . mysqli_error($conn);
        mysqli_close($conn); ?>
        <br>
        <br>
        <a href="/">Return Home</a>
        <br>
        <a href="createevent.php">Submit another response</a>
        <br>
        <a href="searchevent.php">View all events</a>
        <?php
        exit;
    }
    echo "<table border = '1'>
        <tr>
        <th>Location</th>
        <th>Date</th>
        <th>View Event</th>
        </tr>";

    while ($row = mysqli_fetch_assoc($search)) {
        echo "<tr>";
        echo "<td>" . $row['location'] . "</td>";
        echo "<td>" . $row['event_date'] . "</td>";
        echo "<td> <a href=\"viewevent.php?id=$event_id\"> view </a> </td>";
        echo "</tr>";
    }
    echo "</table>";
    mysqli_close($conn); ?>
  <a href="/">Return Home</a>
  <br>
  <a href="createevent.php">Submit another response</a>
  <br>
  <a href="searchevent.php">View all events</a>
  <?php
} else {
        eventsearch();

        $sql = "SELECT * FROM regattascoring.EVENT;";
        $result = mysqli_query($conn, $sql);

        echo "<table border = '1'>
        <tr>
        <th>Location</th>
        <th>Date</th>
        <th>View Event</th>
        </tr>";

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $event_id = $row['event_id'];
                echo "<tr>";
                echo "<td>" . $row['location'] . "</td>";
                echo "<td>" . $row['event_date'] . "</td>";
                echo "<td> <a href=\"viewevent.php?id=$event_id\"> view </a> </td>";
                echo "</tr>";
            }
        }
        echo "</table>";
        mysqli_close($conn); ?>
  <br>
  <a href="/">Return Home</a>
  <br>
  <a href="createevent.php">Submit another response</a>
  <br>
  <a href="searchevent.php">View all events</a>
  <?php
    }
