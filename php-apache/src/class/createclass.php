<?php
//include navigation bar and connection php files
include_once '../navbar.php';
include_once '../connection.php';

//function to echo errors and links
function closeclass($error)
{
    if ($error) {
        echo $error . "</br>"; ?>
        <br>
        <a href="createclass.php">Submit another response</a>
        <?php
    } ?>
    <br>
    <a href="/">Return Home</a>
    <br>
    <a href="searchclass.php">View all classes</a>
    <?php
}

//function for the class form
function classform()
{
    ?>
  <html>
  <head>
      <title>Create Class</title>
  </head>
  <h1>Create New Class</h1>
  <body>
  <form action= <?php echo "createclass.php" . $_GET['id'] ?> method ="POST">
    Class Name:
    <input type="text" name="class_name" placeholder="Class Name">
    <br>
    Minimum Age:
    <input type="number" name="min_age" placeholder="Minimum Age" step="any">
    <br>
    Maximum Age:
    <input type="number" name="max_age" step="any" placeholder="Maximum Age">
    <br>
    <button type="submit" name="submit">Enter</button>
  </form>
  </body>
 </html>
<?php
}

//IF FORM SUBMITTED
if (isset($_POST["submit"])) {

    //define POST variables from form
    $class_name = mysqli_real_escape_string($conn, $_POST['class_name']);
    $min_age = mysqli_real_escape_string($conn, $_POST['min_age']);
    $max_age = mysqli_real_escape_string($conn, $_POST['max_age']);

    //array for validation errors
    $errors = array();

    //format numbers to one decimal placeholder
    $min_age_formated = number_format($min_age, 1, '.', ',');
    $max_age_formated = number_format($max_age, 1, '.', ',');

    //validation of variables
    if (!$class_name) {
        array_push($errors, "Class name must be entered");
    }
    if (preg_match('/[^A-Za-z ]/', $class_name)) {
        array_push($errors, "Please enter a valid class name");
    }
    if (!$min_age_formated) {
        array_push($errors, "Minimum age must be entered");
    }
    if (!is_numeric($min_age_formated)) {
        array_push($errors, "Please enter a valid minimum age");
    }
    if ($min_age_formated < 0) {
        array_push($errors, "Please enter a positive minimum age");
    }
    if (!$max_age_formated) {
        array_push($errors, "Maximum age must be entered");
    }
    if (!is_numeric($max_age_formated)) {
        array_push($errors, "Please enter a valid maximum age");
    }
    if ($max_age_formated < 0) {
        array_push($errors, "Please enter a positive maximum age");
    }
    if ($min_age_formated >= $max_age_formated) {
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
            <form action="createclass.php" method ="POST">
              Class Name:
              <input type="text" name="class_name" value="<?php echo $_POST['class_name']?>" placeholder="Class Name">
              <br>
              Minimum Age:
              <input type="number" name="min_age" value="<?php echo $_POST['min_age'] ?>" placeholder="Minimum Age" step="any">
              <br>
              Maximum Age:
              <input type="number" name="max_age" value="<?php echo $_POST['max_age'] ?>" step="any" placeholder="Maximum Age">
              <br>
              <button type="submit" name="submit">Enter</button>
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
    //insert variables into class table, if false echo error and exit
    $sql = "INSERT INTO regattascoring.CLASS (class_name, min_age, max_age)
    VALUES ('$class_name', '$min_age','$max_age');";
    if (!mysqli_query($conn, $sql)) {
        $error = "Could not add class";
        closeclass($error);
        mysqli_close($conn);
        exit;
    }

    //echo class created
    echo $_POST['class_name'] . " Class Created";
    $class_id = mysqli_insert_id($conn); ?>
    <br>
    <a href= <?php echo "viewclass.php?id=$class_id" ?>>Edit <?php echo
    $_POST['class_name'] ?></a>
    <?php

    //call class form
    classform();

    //call closing function
    closeclass($error);
    mysqli_close($conn);
} else {
    //call class form
    classform();

    //call class close
    closeclass($error);
    mysqli_close($conn);
}
