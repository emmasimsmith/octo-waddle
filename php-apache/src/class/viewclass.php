<?php
// include navigation bar and connection php files
include_once '../navbar.php';
include_once "../connection.php";

//function to echo errors and links
function closeclass($error)
{
    if ($error) {
        echo $error . "</br>";
    } ?>
    <br>
    <a href="/">Return Home</a>
    <br>
    <a href="createclass.php">Submit another response</a>
    <br>
    <a href="searchclass.php">View all classes</a>
    <?php
}

//if delete button is selected in form
if (isset($_POST["delete"])) {

  //GET the id number
    $class_id_escaped = mysqli_real_escape_string($conn, $_GET['id']);
    $class_id = $_GET['id'];

    //Select class name from the table
    $sql = "SELECT class_name FROM regattascoring.CLASS WHERE class_id = '$class_id_escaped';";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $class_name = $row['class_name'];

    //Delete class from table
    $sql = "DELETE FROM regattascoring.CLASS WHERE class_id = '$class_id_escaped';";

    //Check if class has been deleted
    if (!mysqli_query($conn, $sql)) {
        $error = "Could not delete class";
        closeclass($error);
        mysqli_close($conn);
        exit;
    }
    echo "$class_name" . " deleted";
    closeclass($error);

//if update button is selected
} elseif (isset($_POST["update"])) {
    // Get ID from URL
    $class_id_escaped = mysqli_real_escape_string($conn, $_GET['id']);
    $class_id = $_GET['id'];

    //POST variables from form
    $new_class_name_escaped = mysqli_real_escape_string($conn, $_POST['class_name']);
    $class_name = $_POST['class_name'];
    $new_min_age_escaped = mysqli_real_escape_string($conn, $_POST['min_age']);
    $new_max_age_escaped = mysqli_real_escape_string($conn, $_POST['max_age']);

    //Create array for errors
    $errors = array();

    //validation of variables
    if (!$new_class_name_escaped) {
        array_push($errors, "Class name must be entered");
    }
    if (preg_match('/[^A-Za-z ]/', $new_class_name_escaped)) {
        array_push($errors, "Please enter a valid class name");
    }
    if (!$new_min_age_escaped) {
        array_push($errors, "Minimum age must be entered");
    }
    if (!is_numeric($new_min_age_escaped)) {
        array_push($errors, "Please enter a valid minimum age");
    }
    if ($new_min_age_escaped < 0) {
        array_push($errors, "Please enter a positive minimum age");
    }
    if (!$new_max_age_escaped) {
        array_push($errors, "Maximum age must be entered");
    }
    if (!is_numeric($new_max_age_escaped)) {
        array_push($errors, "Please enter a valid maximum age");
    }
    if ($new_max_age_escaped < 0) {
        array_push($errors, "Please enter a positive maximum age");
    }
    if ($new_min_age_escaped >= $new_max_age_escaped) {
        array_push($errors, "The maximum age must be greater than the minimum age");
    }

    //if not valid echo error and form then exit
    if (count($errors) != 0) {
        //call form with existing values?>
        <html>
          <head>
              <title>Create Class</title>
          </head>
          <h1>Create New Class</h1>
          <body>
            <form action= <?php echo "viewclass.php?id=" . $_GET['id']?> method ="POST">
              Class Name:
              <input type="text" name="class_name" value="<?php echo $_POST['class_name']?>" placeholder="Class Name">
              <br>
              Minimum Age:
              <input type="number" name="min_age" value="<?php echo $_POST['min_age'] ?>" placeholder="Minimum Age" step="any">
              <br>
              Maximum Age:
              <input type="number" name="max_age" value="<?php echo $_POST['max_age'] ?>" step="any" placeholder="Maximum Age">
              <br>
              <button type="submit" name="update">Update</button>
              <button type="submit" name="delete">Delete</button>
            </form>
          </body>
        </html>
        <?php
        foreach ($errors as $error) {
            $issue = '';
            $issue = $issue . $error . "</br>";
        }
        closeclass($issue);
        mysqli_close($conn);
        exit;
    }

    //Update table
    $sql = "UPDATE regattascoring.CLASS set class_name =
        '$new_class_name_escaped', min_age = '$new_min_age_escaped',
        max_age = '$new_max_age_escaped' WHERE class_id = '$class_id_escaped';";

    //Check table updated, if not exit
    if (!mysqli_query($conn, $sql)) {
        $error = "Could not update class";
        closeclass($error);
        mysqli_close($conn);
        exit;
    }

    //echo class updated
    echo "$class_name class updated";
    closeclass($error);
    mysqli_close($conn);
// if nothing has been selected
} else {
    //GET ID from URL
    $class_id = mysqli_real_escape_string($conn, $_GET['id']);

    //Select class matching GET ID
    $sql = "SELECT * FROM regattascoring.CLASS WHERE class_id = '$class_id';";
    $select = mysqli_query($conn, $sql);
    if (!$select) {
        $error = "Could not select CLASS table";
        closeclass($error);
        mysqli_close($conn);
        exit;
    }
    echo "selected table successfully" . "</br>";

    //Check only one class selected
    if (mysqli_num_rows($select) == 0) {
        $error = "Nothing Selected";
        closeclass($error);
        mysqli_close($conn);
        exit;
    } elseif (mysqli_num_rows($select) >1) {
        $error = "Too many classes selected";
        closeclass($error);
        mysqli_close($conn);
        exit;
    }

    //Echo form with previous values
    $row = mysqli_fetch_assoc($select);

    //call form with existing values?>
    <html>
      <head>
          <title>Create Class</title>
      </head>
      <h1>Create New Class</h1>
      <body>
        <form action=<?php echo "viewclass.php?id=" . $_GET['id']?> method ="POST">
          Class Name:
          <input type="text" name="class_name" value="<?php echo $row['class_name']?>" placeholder="Class Name">
          <br>
          Minimum Age:
          <input type="number" name="min_age" value="<?php echo $row['min_age'] ?>" placeholder="Minimum Age" step="any">
          <br>
          Maximum Age:
          <input type="number" name="max_age" value="<?php echo $row['max_age'] ?>" step="any" placeholder="Maximum Age">
          <br>
          <button type="submit" name="update">Update</button>
          <button type="submit" name="delete">Delete</button>
        </form>
      </body>
    </html>
    <?php

  //call closing function
  closeclass($error);

    //Close connection
    mysqli_close($conn);
}
?>
