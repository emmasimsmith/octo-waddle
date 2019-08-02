<?php
//include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once "../connection.php";
include_once '../functions.php';

//form function
function unitform()
{
    ?>
  <html>
    <body>
      <form action= searchunit.php method="POST">
        <input type="text" name="unit_name" placeholder="Search unit name">
        <input type="number" name="unit_id" placeholder="Search unit id">
        <button type="submit" name="search">Enter</button>
      </form>
    </body>
  </html>
  <?php
}

if (isset($_POST['search'])) {
    //call unit form
    unitform();

    //define POST variables
    $search_unit_name_escaped = mysqli_real_escape_string($conn, $_POST['unit_name']);
    $search_unit_id_escaped = mysqli_real_escape_string($conn, $_POST['unit_id']);

    //validation if strings are empty
    if (!$_POST['unit_name'] and !$_POST['unit_id']) {
        close($conn, "Please enter a valid search", "unit", "Units");
        exit;
    }

    //variables array for function
    $variables = array('unit_name' => array('Unit Name' => $search_unit_name_escaped),
  'unit_id' => array('Unit ID' => $search_unit_id_escaped));

    //call search function
    search($conn, "unit", $variables, "regattascoring.UNIT", "Unit", "Units");

    //call close
    close($conn, $error, "unit", "Units");
} else {
    $sql = "SELECT * FROM regattascoring.UNIT;";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['unit_name'];
    }
    //call unit form
    unitform();

    //variables array
    $variables = array('unit_name' => 'Unit Name', 'unit_id' => 'Unit ID');

    //echo all data from table and close
    viewall($conn, "unit", "regattascoring.UNIT", $variables, "unit_id", "Units");
}
