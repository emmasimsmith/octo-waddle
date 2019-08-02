<?php
//include the navigation bar, functions and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

//Form function
function classform()
{
    ?>
<html>
  <body>
    <form action= searchclass.php method="POST">
      <input type="text" name="class_name" placeholder="Search class name">
      <input type="text" name="min_age" placeholder="Search minimum age">
      <input type="text" name="max_age" placeholder="Search maximum age">
      <button type="submit" name="search">Enter</button>
    </form>
  </body>
</html>
<?php
}

//Form completed and user sumbitted search
if (isset($_POST['search'])) {

  //call form
    classform();

    //define POST variables
    $class_name_escaped = mysqli_real_escape_string($conn, $_POST['class_name']);
    $min_age_escaped = mysqli_real_escape_string($conn, $_POST['min_age']);
    $max_age_escaped = mysqli_real_escape_string($conn, $_POST['max_age']);

    //validation check for empty strings
    if (!$_POST['class_name'] and !$_POST['min_age'] and !$_POST['max_age']) {
        close($conn, "Please search a valid value", "class", "Classes");
        exit;
    }

    //variable array for function
    $variables = array('class_name' => array('Class Name' => $class_name_escaped),
    'min_age' => array('Minimum Age' => $min_age_escaped),
    'max_age' => array('Maximum Age' => $max_age_escaped));

    //call search function
    search($conn, "class", $variables, "regattascoring.CLASS", "Class", "Classes");

    //call close
    close($conn, $error, "class", "Classes");
} else {
    //call class form
    classform();

    //variales array for function
    $variables = array('class_name' => 'Class Name', 'min_age' => 'Minimum Age',
    'max_age' => 'Maximum Age');

    //echo all data from table and close
    viewall($conn, "class", "regattascoring.CLASS", $variables, "class_id", "Classes");
}
?>
