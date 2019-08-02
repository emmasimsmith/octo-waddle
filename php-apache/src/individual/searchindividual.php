<?php
//include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

//Form function
function individualform()
{
    ?>
<html>
  <body>
    <form action= searchindividual.php method="POST">
      <input type="text" name="search_first" placeholder="Search by first name">
      <input type="text" name="search_last" placeholder="Search by last name">
      <input type="text" name="search_dob" placeholder="Search by date of birth">
      <input type="text" name="search_unit" placeholder="Search by unit">
      <input type="text" name="search_role" placeholder="Search by role">
      <input type="text" name="search_comments" placeholder="Search comments">
      <button type="submit" name="search">Enter</button>
    </form>
  </body>
</html>
<?php
}

//IF search is submitted
if (isset($_POST['search'])) {
    //call individual form
    individualform();

    //define POST variables
    $search_first_name_escaped = mysqli_real_escape_string($conn, $_POST['search_first']);
    $search_last_name_escaped = mysqli_real_escape_string($conn, $_POST['search_last']);
    $search_dob_escaped = mysqli_real_escape_string($conn, $_POST['search_dob']);
    $search_unit_name_escaped = mysqli_real_escape_string($conn, $_POST['search_unit']);
    $search_role_escaped = mysqli_real_escape_string($conn, $_POST['search_role']);
    $search_comments_escaped = mysqli_real_escape_string($conn, $_POST['search_comments']);

    //validation if strings are empty
    if (!$_POST['search_first'] and !$_POST['search_last'] and !$_POST['search_dob'] and
     !$_POST['search_unit'] and !$_POST['search_role'] and !$_POST['search_comments']) {
        close($conn, "Please search a value", "individual", "Individuals");
        exit;
    }

    //variable array for function
    $variables = array(
    'first_name' => array('First Name' => $search_first_name_escaped),
    'last_name' => array('Last Name' => $search_last_name_escaped),
    'dob' => array('Date of Birth' => $search_dob_escaped),
    'unit_name' => array('Unit Name' => $search_unit_name_escaped),
    'role' => array('Role' => $search_role_escaped),
    'comments' => array('Comments' => $search_comments_escaped));

    //call search function
    search(
        $conn,
        "individual",
        $variables,
        "regattascoring.INDIVIDUAL NATURAL JOIN regattascoring.UNIT",
        "Individual",
        "Individuals"
    );

    //call close function
    close($conn, $error, "individual", "Individuals");
} else {
    //call individual form
    individualform();

    //variables array
    $variables = array('first_name' => 'First Name', 'last_name' => 'Last Name',
    'dob' => 'Date of Birth', 'unit_name' => 'Unit Name', 'role' => 'Role',
    'comments' => 'Comments');

    //echo all data from table and close
    viewall(
        $conn,
        "individual",
        "regattascoring.INDIVIDUAL NATURAL JOIN regattascoring.UNIT",
        $variables,
        "individual_id",
        "Individuals"
    );
}
