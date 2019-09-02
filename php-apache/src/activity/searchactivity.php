<?php
//include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

//Form function
function activityform()
{
    ?>
<html>
  <body>
    <form action= searchactivity.php method="POST">
      <input type="text" name="activity_name" placeholder="Search by activity name">
      <input type="text" name="scoring" placeholder="Search by scoring method">
      <input type="text" name="class_name" placeholder="Search by class">
      <button type="submit" name="search">Enter</button>
    </form>
  </body>
</html>
<?php
}

//IF search is submitted
if (isset($_POST['search'])) {
    //call activity form
    activityform();

    //define POST variables
    $search_activity_name = mysqli_real_escape_string($conn, $_POST['activity_name']);
    $search_scoring = mysqli_real_escape_string($conn, $_POST['scoring']);
    $search_class_name = mysqli_real_escape_string($conn, $_POST['class_name']);

    //validation if strings are empty
    if (!$_POST['activity_name'] and !$_POST['scoring'] and !$_POST['class_name']) {
        close($conn, "Please search a value", "activity", "Activities");
        exit;
    }

    //variable array for function
    $variables = array(
    'activity_name' => array('Activity Name' => $search_activity_name),
    'scoring' => array('Scoring Method' => $search_scoring),
    'class_name' => array('Class Name' => $search_class_name));

    //call search function
    search(
        $conn,
        "activity",
        $variables,
        "regattascoring.ACTIVITY NATURAL JOIN regattascoring.CLASS",
        "Activity",
        "Activities"
    );

    //call close function
    close($conn, $error, "activity", "Activities");
} else {
    //call activity form
    activityform();

    //variables array
    $variables = array('activity_name' => 'Activity Name',
    'scoring' => 'Scoring Method', 'class_name' => 'Class Name');

    //echo all data from table and close
    viewall(
        $conn,
        "activity",
        "regattascoring.ACTIVITY NATURAL JOIN regattascoring.CLASS",
        $variables,
        "activity_group",
        "Activities"
    );
}
