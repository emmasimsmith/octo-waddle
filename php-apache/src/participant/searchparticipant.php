<html>
  <head>
    <title>View Participants</title>
    <link rel="stylesheet" type="text/css" href="../stylesheets/navbarstyle.css">
    <link rel="stylesheet" type="text/css" href="../stylesheets/pagestyle.css">
  </head>

<?php
//include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

//define event event_id
$event_id = $_GET['event_id'];
$sql = "SELECT location FROM regattascoring.EVENT WHERE event_id = '$event_id';";
$location_result = mysqli_fetch_assoc(mysqli_query($conn, $sql));
$location = $location_result['location'];

//Form function
function participantform($event_id, $location)
{
    ?>
    <h1>View Participants in <?php echo "$location" ?> Regatta</h1>
      <div class="search_form">
        <form action= searchparticipant.php?event_id=<?php echo $event_id?> method='POST'>
          <div class="form_input">
            <div class="eight_input">
              <input type="number" name="participant_tag" placeholder="Participant Tag">
              <input type="text" name="first_name" placeholder="First Name">
              <input type="text" name="last_name" placeholder="Last Name">
              <input type="text" name="dob" placeholder="Date of Birth">
              <input type="text" name="class_name" placeholder="Class Name">
              <input type="text" name="unit_name" placeholder="Unit Name">
              <input type="text" name="role" placeholder="Role">
              <input type="text" name="comments" placeholder="Comments">
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

//Form completed and user sumbitted search
if (isset($_POST['search'])) {

    //call form
    echo "  <div class='container'>
        <div class='content'>
          <body>";
    participantform($event_id, $location);

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
        echo "<div class='error'>No matches in table<div>
      <div class='close'>
        <ul>
          <li><a href='/'>Return Home</a></li>
          <li><a href= 'selectparticipant.php?event_id=$event_id'>Reselect participants</a></li>
          <li><a href='searchparticipant.php'>View all participants</a></li>
          <li><a href='../indexselectedevent.php?event_id=$event_id'>Return to Event Page</a></li>
        </ul>
      </div>";
        mysqli_close($conn);
        exit;
    }

    //create html table
    echo "<table>
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
    echo "<div class='close'>
      <ul>
        <li><a href='/'>Return Home</a></li>
        <li><a href= 'selectparticipant.php?event_id=$event_id'>Reselect participants</a></li>
        <li><a href='searchparticipant.php'>View all participants</a></li>
        <li><a href='../indexselectedevent.php?event_id=$event_id'>Return to Event Page</a></li>
      </ul>
    </div>";
    mysqli_close($conn);
} else {

    //call participant form
    echo "  <div class='container'>
        <div class='content'>
          <body>";
    participantform($event_id, $location);

    //echo all data from table and close
    $sql = "SELECT * FROM regattascoring.PARTICIPANT NATURAL JOIN regattascoring.INDIVIDUAL
    LEFT JOIN regattascoring.CLASS ON regattascoring.CLASS.class_id =
    regattascoring.PARTICIPANT.class_id LEFT JOIN regattascoring.UNIT ON
    regattascoring.UNIT.unit_id = regattascoring.INDIVIDUAL.unit_id WHERE
    event_id = '$event_id' ORDER BY participant_tag ASC;";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo mysqli_error($conn);
    }

    if (mysqli_num_rows($result) > 0) {
        //create html table
        echo "<table>
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
        echo "<div class='close'>
          <ul>
            <li><a href='/'>Return Home</a></li>
            <li><a href= 'selectparticipant.php?event_id=$event_id'>Reselect participants</a></li>
            <li><a href='searchparticipant.php'>View all participants</a></li>
            <li><a href='../indexselectedevent.php?event_id=$event_id'>Return to Event Page</a></li>
          </ul>
        </div>";
        mysqli_close($conn);
    } else {
        //if no data in the table
        echo "<div class='message'>No data to display</div>
        <div class='close'>
          <ul>
            <li><a href='/'>Return Home</a></li>
            <li><a href= 'selectparticipant.php?event_id=$event_id'>Reselect participants</a></li>
            <li><a href='searchparticipant.php'>View all participants</a></li>
            <li><a href='../indexselectedevent.php?event_id=$event_id'>Return to Event Page</a></li>
          </ul>
        </div>";
        mysqli_close($conn);
        exit;
    }
}
?>
