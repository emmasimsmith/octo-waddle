<html>
  <head>
    <title>Create Individual</title>
    <link rel="stylesheet" type="text/css" href="../stylesheets/navbarstyle.css">
    <link rel="stylesheet" type="text/css" href="../stylesheets/createpagestyle.css">
  </head>

<?php
//include navigation bar, functions and connection php files
include_once '../connection.php';
include_once '../functions.php';

//function for the class form
function classform()
{
    ?>
    <h1>Create New Classes</h1>
      <ul class="labels">
        <li>Junior Junior Class Minimum Age:</li>
        <li>Junior Class Minimum Age:</li>
        <li>Intermediate Class Minimum Age:</li>
        <li>Senior Class Minimum Age:</li>
        <li>Senior Class Maximum Age:</li>
      </ul>
      <form action="createclass.php" method ="POST">
        <input type='number' name="jj_min_age" placeholder="Minimum Age" step="any" min="0" required>
        <input type='number' name="junior_min_age" placeholder="Minimum Age" step="any" min="0" required>
        <input type='number' name="intermediate_min_age" placeholder="Minimum Age" step="any" min="0" required>
        <input type='number' name="senior_min_age" placeholder="Minimum Age" step="any" min="0" required>
        <input type='number' name="senior_max_age" placeholder="Maximum Age" step="any" min="0" required>
        <button type="submit" name="submit">Enter</button>
      </form>
    </body>
<?php
}

$sql = "SELECT * FROM regattascoring.CLASS;";

if (mysqli_num_rows(mysqli_query($conn, $sql)) != 0) {
    //include navbar
    header("Location: viewclass.php");

//IF FORM SUBMITTED
} elseif (isset($_POST["submit"])) {

    //define POST variables
    $jj_min_age = mysqli_real_escape_string($conn, $_POST['jj_min_age']);
    $junior_min_age = mysqli_real_escape_string($conn, $_POST['junior_min_age']);
    $intermediate_min_age = mysqli_real_escape_string($conn, $_POST['intermediate_min_age']);
    $senior_min_age = mysqli_real_escape_string($conn, $_POST['senior_min_age']);
    $senior_max_age = mysqli_real_escape_string($conn, $_POST['senior_max_age']);

    //array for validation errors
    $errors = array();

    //input sanitsation
    if ($jj_min_age > $junior_min_age) {
        array_push($errors, "Junior Junior Class minimum age must be less than Junior Class minimum age");
    }
    if ($junior_min_age > $intermediate_min_age) {
        array_push($errors, "Junior Class minimum age must be less than Intermediate Class minimum age");
    }
    if ($intermediate_min_age > $senior_min_age) {
        array_push($errors, "Intermediate Class minimum age must be less than Senior Class minimum age");
    }
    if ($senior_min_age > $senior_max_age) {
        array_push($errors, "Senior Class minimum age must be less than Senior Class maximum age");
    }

    //if not valid echo error and form then exit
    if (count($errors) != 0) {

      //include navbar
        include_once '../navbar.php';

        //call form with existing values?>
        <div class="container">
          <div class="content">
            <body>
              <h1>Create New Class</h1>
              <ul class="labels">
                <li>Junior Junior Class Minimum Age:</li>
                <li>Junior Class Minimum Age:</li>
                <li>Intermediate Class Minimum Age:</li>
                <li>Senior Class Minimum Age:</li>
                <li>Senior Class Maximum Age:</li>
              </ul>
              <form action= createclass.php method ="POST">
                <input type='number' name="jj_min_age" value= '<?php echo $jj_min_age ?>' placeholder="Minimum Age" step="any" min="0" required>
                <input type='number' name="junior_min_age" value= '<?php echo $junior_min_age ?>' placeholder="Minimum Age" step="any" min="0" required>
                <input type='number' name="intermediate_min_age" value= '<?php echo $intermediate_min_age ?>' placeholder="Minimum Age" step="any" min="0" required>
                <input type='number' name="senior_min_age" value= '<?php echo $senior_min_age ?>' placeholder="Minimum Age" step="any" min="0" required>
                <input type='number' name="senior_max_age" value= '<?php echo $senior_max_age ?>' placeholder="Maximum Age" step="any" min="0" required>
                <button type="submit" name="submit">Enter</button>
              </form>
            </body>
        <?php

        $issue = '';
        foreach ($errors as $error) {
            $issue = $issue . $error . "</br>";
        }
        close($conn, $issue, "class", "Classes");
        exit;
    }

    //format min and max ages
    $jj_min_age_format = number_format($jj_min_age, 1, '.', ',');
    $junior_min_age_format = number_format($junior_min_age, 1, '.', ',');
    $intermediate_min_age_format = number_format($intermediate_min_age, 1, '.', ',');
    $senior_min_age_format = number_format($senior_min_age, 1, '.', ',');
    $senior_max_age_format = number_format($senior_max_age, 1, '.', ',');

    //insert classes into table
    $sql = "INSERT INTO regattascoring.CLASS (class_name, min_age, max_age)
        VALUES ('Junior Junior', '$jj_min_age_format','$junior_min_age_format');";
    $result = mysqli_query($conn, $sql);
    $sql = "INSERT INTO regattascoring.CLASS (class_name, min_age, max_age)
        VALUES ('Junior', '$junior_min_age_format','$intermediate_min_age_format');";
    $result = mysqli_query($conn, $sql);
    $sql = "INSERT INTO regattascoring.CLASS (class_name, min_age, max_age)
        VALUES ('Intermediate', '$intermediate_min_age_format','$senior_min_age_format');";
    $result = mysqli_query($conn, $sql);
    $sql = "INSERT INTO regattascoring.CLASS (class_name, min_age, max_age)
        VALUES ('Senior', '$senior_min_age_format','$senior_max_age_format');";
    $result = mysqli_query($conn, $sql);

    header("Location: /activity/createactivity.php");
} else {

  //include navbar
    include_once '../navbar.php';

    //call class form
    echo "  <div class='container'>
        <div class='content'>
          <body>";
    classform();

    //call class close
    close($conn, $error, "class", "Classes");
}
