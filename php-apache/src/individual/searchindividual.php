<?php
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

$name = "individual";
$table_name = "INDIVIDUAL";
$capitalised_name = "Individual";

//Form function
function individualform()
{
    ?>
<html>
  <body>
    <form action= searchindividual.php method="POST">
    <input type="text" name="search_first" placeholder="Search first name">
    <input type="text" name="search_last" placeholder="Search last name">
    <input type="text" name="search_dob" placeholder="Search date of birth">
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
    $search_comments_escaped = mysqli_real_escape_string($conn, $_POST['search_comments']);

    //validation if strings are empty
    if (!$_POST['search_first'] and !$_POST['search_last'] and !$_POST['search_dob'] and !$_POST['search_comments']) {
        $error = "Please search a value";
        close($conn, $error, $name);
        exit;
    }

    $variables = array(
    'first_name' => array('First Name' => $search_first_name_escaped),
    'last_name' => array('Last Name' => $search_last_name_escaped),
    'dob' => array('Date of Birth' => $search_dob_escaped),
    'comments' => array('Comments' => $search_comments_escaped));

    //call search function
    search($conn, $name, $variables, $table_name, $capitalised_name);

    //call close function
    close($conn, $error, $name);
} else {
    //call individual form
    individualform();

    //variables array
    $variables = array('first_name' => 'First Name', 'last_name' => 'Last Name',
    'dob' => 'Date of Birth', 'comments' => 'Comments');

    //echo all data from table
    viewall($conn, $name, $table_name, $variables);
}
