<?php
//include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

//Form function
function boatform()
{
    ?>
<html>
  <body>
    <form action= searchboat.php method="POST">
      <input type="text" name="search_boat_number" placeholder="Search by boat number">
      <input type="text" name="search_boat_type" placeholder="Search by boat type">
      <input type="text" name="search_unit_name" placeholder="Search by unit name">
      <input type="text" name="search_boat_handicap" placeholder="Search by boat handicap">
      <button type="submit" name="search">Enter</button>
    </form>
  </body>
</html>
<?php
}

//IF search is submitted
if (isset($_POST['search'])) {
    //call boat form
    boatform();

    //define POST variables
    $search_boat_number_escaped = mysqli_real_escape_string($conn, $_POST['search_boat_number']);
    $search_boat_type_escaped = mysqli_real_escape_string($conn, $_POST['search_boat_type']);
    $search_unit_name_escaped = mysqli_real_escape_string($conn, $_POST['search_unit_name']);
    $search_boat_handicap_escaped = mysqli_real_escape_string($conn, $_POST['search_boat_handicap']);

    //validation if strings are empty
    if (!$_POST['search_boat_number'] and !$_POST['search_boat_type'] and !$_POST['search_unit_name'] and
     !$_POST['search_boat_handicap']) {
        close($conn, "Please search a value", "boat", "Boats");
        exit;
    }

    //variable array for function
    $variables = array(
    'boat_number' => array('Boat Number' => $search_boat_number_escaped),
    'boat_type' => array('Boat Type' => $search_boat_type_escaped),
    'unit_name' => array('Unit Name' => $search_unit_name_escaped),
    'boat_handicap' => array('Boat Handicap' => $search_boat_handicap_escaped));

    //call search function
    search(
        $conn,
        "boat",
        $variables,
        "regattascoring.BOAT NATURAL JOIN regattascoring.UNIT",
        "Boat",
        "Boats"
    );

    //call close function
    close($conn, $error, "boat", "Boats");
} else {
    //call boat form
    boatform();

    //variables array
    $variables = array('boat_number' => 'Boat Number', 'boat_type' => 'Boat Type',
    'unit_name' => 'Unit Name', 'boat_handicap' => 'Boat Handicap');

    //echo all data from table and close
    viewall(
        $conn,
        "boat",
        "regattascoring.BOAT NATURAL JOIN regattascoring.UNIT",
        $variables,
        "boat_id",
        "Boats"
    );
}
