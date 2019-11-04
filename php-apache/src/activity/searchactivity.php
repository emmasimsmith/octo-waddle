<html>
  <head>
    <title>View Activities</title>
    <link rel="stylesheet" type="text/css" href="../stylesheets/navbarstyle.css">
    <link rel="stylesheet" type="text/css" href="../stylesheets/pagestyle.css">
  </head>

<?php
//include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';
include_once '../morefunctions.php';

//Form function
function activityform()
{
    ?>
    <h1>View Activities</h1>
    <div class="search_form">
      <form action= searchactivity.php method="POST">
        <div class="form_input">
          <div class="five_input">
            <input type="text" name="activity_name" placeholder="Search by activity name">
            <input type="text" name="activity_bracket" placeholder="Search by activity bracket">
            <input type="text" name="scoring_method" placeholder="Search by scoring method">
            <input type="text" name="class_name" placeholder="Search by class name">
            <input type="text" name="scored_by" placeholder="Search by scored group">
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

//IF search is submitted
if (isset($_POST['search'])) {
    //call activity form
    echo "  <div class='container'>
        <div class='content'>
          <body>";
    activityform();

    //define POST variables
    $search_activity_name = mysqli_real_escape_string($conn, $_POST['activity_name']);
    $search_activity_bracket = mysqli_real_escape_string($conn, $_POST['activity_bracket']);
    $search_scoring_method = mysqli_real_escape_string($conn, $_POST['scoring_method']);
    $search_class_name = mysqli_real_escape_string($conn, $_POST['class_name']);
    $search_scored_by = mysqli_real_escape_string($conn, $_POST['scored_by']);

    //validation if strings are empty
    if (!$_POST['activity_name'] and !$_POST['activity_bracket'] and
    !$_POST['scoring_method'] and !$_POST['class_name'] and !$_POST['scored_by']) {
        echo "<div class='error'>Please search a valid value</div>
        <div class='close'>
          <ul>
            <li><a href='/'>Return Home</a></li>
            <li><a href='searchactivity.php'>View all Activities</a></li>
          </ul>
        </div>
        </div>
        </div>";
        exit;
    }

    //call search
    $search = array();
    $sql = "SELECT * FROM regattascoring.ACTIVITY WHERE ";
    if ($search_activity_name) {
        array_push($search, "activity_name LIKE '%$search_activity_name%'");
    }
    if ($search_scoring_method) {
        array_push($search, "scoring_method LIKE '%$search_scoring_method%'");
    }
    if ($search_activity_bracket) {
        array_push($search, "activity_bracket LIKE '%$search_activity_bracket%'");
    }
    if ($search_scored_by) {
        array_push($search, "scored_by LIKE '%$search_scored_by%'");
    }
    if ($search_class_name) {
        //set array
        $class = array();
        //search for class within bracket to find activity ids
        $other = "SELECT activity_id FROM regattascoring.BRACKET NATURAL JOIN
      regattascoring.CLASS WHERE class_name LIKE '%$search_class_name%';";
        $result = mysqli_query($conn, $other);
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($class, "activity_id = " . $row['activity_id']);
        }
        $id_classes = join(" OR ", $class);
        //array push
        array_push($search, $id_classes);
    }

    $join = join(" AND ", $search);
    $sql = $sql . $join . ";";

    //check if there are rows that match
    $search = mysqli_query($conn, $sql);
    if (mysqli_num_rows($search) == 0) {
        echo "<div class='message'>No matches in table</div>
        <div class='close'>
          <ul>
            <li><a href='/'>Return Home</a></li>
            <li><a href='searchactivity.php'>View all Activities</a></li>
          </ul>
        </div>
        </div>
        </div>";
        mysqli_close($conn);
        exit;
    }

    //call activity view function
    activity_view($conn, $search);

    //call close function
    echo "<div class='close'>
      <ul>
        <li><a href='/'>Return Home</a></li>
        <li><a href='searchactivity.php'>View all Activities</a></li>
      </ul>
    </div>
    </div>
    </div>";
    mysqli_close($conn);
    exit;
} else {
    //call activity form
    echo "  <div class='container'>
        <div class='content'>
          <body>";
    activityform();

    //echo all data from table
    $sql = "SELECT * FROM regattascoring.ACTIVITY;";
    $result = mysqli_query($conn, $sql);

    activity_view($conn, $result);

    echo "<div class='close'>
      <ul>
        <li><a href='/'>Return Home</a></li>
      </ul>
    </div>
    </div>
    </div>";
}
