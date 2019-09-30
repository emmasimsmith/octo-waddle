<?php
//include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

//define event event_id
$event_id = $_GET['event_id'];

//Form function
function participantform($event_id)
{
    ?>
<html>
<h1>Participants</h1>
  <body>
    <?php echo "<form action= searchparticipant.php?event_id=$event_id method='POST'>" ?>
      <input type="number" name="participant_tag" placeholder="Search participant tag">
      <input type="text" name="first_name" placeholder="Search first name">
      <input type="text" name="last_name" placeholder="Search last name">
      <input type="text" name="dob" placeholder="Search date of birth">
      <input type="text" name="class_name" placeholder="Search class name">
      <input type="text" name="unit_name" placeholder="Search unit name">
      <input type="text" name="role" placeholder="Search role">
      <input type="text" name="comments" placeholder="Search comments">
      <button type="submit" name="search">Enter</button>
    </form>
  </body>
</html>
<?php
}

//Form completed and user sumbitted search
if (isset($_POST['search'])) {

    //call form
    participantform($_GET['event_id']);

    //define POST variables
    $participant_tag = mysqli_real_escape_string($conn, $_POST['participant_tag']);
    $class_name = mysqli_real_escape_string($conn, $_POST['class_name']);
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $unit_name = mysqli_real_escape_string($conn, $_POST['unit_name']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $comments = mysqli_real_escape_string($conn, $_POST['comments']);

    //validation check for empty strings
    if (!$participant_tag and !$class_name and !$first_name and !$last_name and
    !$dob and !$unit_name and !$role and !$comments) {
        close($conn, "Please search a valid value", "participant", "Participants");
        exit;
    }

    //call search function
    $search = array();
    $sql = "SELECT * FROM regattascoring.PARTICIPANT NATURAL JOIN regattascoring.INDIVIDUAL
    LEFT JOIN regattascoring.CLASS ON regattascoring.CLASS.class_id =
    regattascoring.PARTICIPANT.class_id LEFT JOIN regattascoring.UNIT ON
    regattascoring.UNIT.unit_id = regattascoring.INDIVIDUAL.unit_id WHERE event_id = '$event_id' AND ";

    if ($participant_tag) {
        array_push($search, "participant_tag LIKE '%$participant_tag%'");
    }
    if ($class_name) {
        array_push($search, "class_name LIKE '%$class_name%'");
    }
    if ($first_name) {
        array_push($search, "first_name LIKE '%$first_name%'");
    }
    if ($last_name) {
        array_push($search, "last_name LIKE '%$last_name%'");
    }
    if ($dob) {
        array_push($search, "dob LIKE '%$dob%'");
    }
    if ($unit_name) {
        array_push($search, "unit_name LIKE '%$unit_name%'");
    }
    if ($role) {
        array_push($search, "role LIKE '%$role%'");
    }
    if ($comments) {
        array_push($search, "comments LIKE '%$comments%'");
    }

    $join = join(" AND ", $search);
    $sql = $sql . $join . ";";

    //check if there are rows that match
    $search = mysqli_query($conn, $sql);
    if (mysqli_num_rows($search) == 0) {
        echo "No matches in table" . "</br>";
        echo "<br>
        <a href='/'>Return Home</a>
        <br>
        <a href= 'selectparticipant.php?event_id=$event_id'>Reselect participants</a>
        <br>
        <a href='searchparticipant.php'>View all participants</a>
        <br>
        <a href='../indexselectedevent.php?event_id=$event_id'>Return to Event Page</a>";
        mysqli_close($conn);
        exit;
    }

    //create html table
    echo "<table border = '1'>
    <tr>
    <th>Participant Tag</th>
    <th>First Name</th>
    <th>Last Name</th>
    <th>Date of Birth</th>
    <th>Class Name</th>
    <th>Unit Name</th>
    <th>Role</th>
    <th>Comments</th>
    </tr>";

    //echo data from table
    while ($row = mysqli_fetch_assoc($search)) {
        echo "<tr>";
        echo "<td>" . $row["participant_tag"] . "</td>";
        echo "<td>" . $row["first_name"] . "</td>";
        echo "<td>" . $row["last_name"] . "</td>";
        echo "<td>" . $row["dob"] . "</td>";
        echo "<td>" . $row["class_name"] . "</td>";
        echo "<td>" . $row["unit_name"] . "</td>";
        echo "<td>" . $row["role"] . "</td>";
        echo "<td>" . $row["comments"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    //call close
    echo "<br>
    <a href='/'>Return Home</a>
    <br>
    <a href= 'selectparticipant.php?event_id=$event_id'>Reselect participants</a>
    <br>
    <a href='searchparticipant.php?event_id=$event_id'>View all participants</a>
    <br>
    <a href='../indexselectedevent.php?event_id=$event_id'>Return to Event Page</a>";
    mysqli_close($conn);
} else {

    //call participant form
    participantform($_GET['event_id']);

    //echo all data from table and close
    $sql = "SELECT * FROM regattascoring.PARTICIPANT NATURAL JOIN regattascoring.INDIVIDUAL
    LEFT JOIN regattascoring.CLASS ON regattascoring.CLASS.class_id =
    regattascoring.PARTICIPANT.class_id LEFT JOIN regattascoring.UNIT ON
    regattascoring.UNIT.unit_id = regattascoring.INDIVIDUAL.unit_id WHERE
    event_id = '$event_id' ORDER BY participant_tag ASC;";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo 'what ' . mysqli_error($conn);
    }

    if (mysqli_num_rows($result) > 0) {
        //create html table
        echo "<table border = '1'>
        <tr>
        <th>Participant Tag</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Date of Birth</th>
        <th>Class Name</th>
        <th>Unit Name</th>
        <th>Role</th>
        <th>Comments</th>
        </tr>";

        //echo data from table
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row["participant_tag"] . "</td>";
            echo "<td>" . $row["first_name"] . "</td>";
            echo "<td>" . $row["last_name"] . "</td>";
            echo "<td>" . $row["dob"] . "</td>";
            echo "<td>" . $row["class_name"] . "</td>";
            echo "<td>" . $row["unit_name"] . "</td>";
            echo "<td>" . $row["role"] . "</td>";
            echo "<td>" . $row["comments"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<br>
        <a href='/'>Return Home</a>
        <br>
        <a href= 'selectparticipant.php?event_id=$event_id'>Reselect participants</a>
        <br>
        <a href='searchparticipant.php?event_id=$event_id'>View all participants</a>
        <br>
        <a href='../indexselectedevent.php?event_id=$event_id'>Return to Event Page</a>";
        mysqli_close($conn);
    } else {
        //if no data in the table
        echo "No data to display
        <br>
        <a href='/'>Return Home</a>
        <br>
        <a href='selectparticipant.php?event_id=$event_id'>Reselect participants</a>
        <br>
        <a href='../indexselectedevent.php?event_id=$event_id'>Return to Event Page</a>";
        mysqli_close($conn);
        exit;
    }
}
?>
