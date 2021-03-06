<html>
  <head>
    <title>Edit Classes</title>
    <link rel="stylesheet" type="text/css" href="../stylesheets/navbarstyle.css">
    <link rel="stylesheet" type="text/css" href="../stylesheets/pagestyle.css">
  </head>

<?php
// include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once "../connection.php";
include_once '../functions.php';

//if update button is selected
if (isset($_POST["update"])) {

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
        //call form with existing values?>
        <div class='container'>
            <div class='content'>
              <body>
                <h1>Edit Classes</h1>
                  <ul class="labels">
                    <li>Junior Junior Class Minimum Age:</li>
                    <li>Junior Class Minimum Age:</li>
                    <li>Intermediate Class Minimum Age:</li>
                    <li>Senior Class Minimum Age:</li>
                    <li>Senior Class Maximum Age:</li>
                  </ul>
                  <form action= viewclass.php method ="POST">
                    <div class="inside-form">
                      <input type='number' name="jj_min_age" value= '<?php echo $jj_min_age ?>' placeholder="Minimum Age" step="any" min="0" max="99" required>
                      <input type='number' name="junior_min_age" value= '<?php echo $junior_min_age ?>' placeholder="Minimum Age" step="any" min="0" max="99" required>
                      <input type='number' name="intermediate_min_age" value= '<?php echo $intermediate_min_age ?>' placeholder="Minimum Age" step="any" min="0" max="99" required>
                      <input type='number' name="senior_min_age" value= '<?php echo $senior_min_age ?>' placeholder="Minimum Age" step="any" min="0" max="99" required>
                      <input type='number' name="senior_max_age" value= '<?php echo $senior_max_age ?>' placeholder="Maximum Age" step="any" min="0" max="99" required>
                    </div>
                    <div class="button">
                      <button type="submit" name="update">Update</button>
                    </div>
                  </form>
                </body>
                </html>
                <?php

      $issue = '';
        foreach ($errors as $error) {
            $issue = $issue . $error . "</br>";
        }
        close($conn, $issue, "class", "Classes");
        exit;
    }

    $jj_min_age_format = number_format($jj_min_age, 1, '.', ',');
    $junior_min_age_format = number_format($junior_min_age, 1, '.', ',');
    $intermediate_min_age_format = number_format($intermediate_min_age, 1, '.', ',');
    $senior_min_age_format = number_format($senior_min_age, 1, '.', ',');
    $senior_max_age_format = number_format($senior_max_age, 1, '.', ',');

    //insert classes into table
    $sql = "SELECT class_id FROM regattascoring.CLASS WHERE class_name = 'Junior Junior';";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $class_id = $row['class_id'];
    $sql = "UPDATE regattascoring.CLASS set min_age = '$jj_min_age_format',
        max_age = '$junior_min_age_format' WHERE class_id = '$class_id';";
    $result = mysqli_query($conn, $sql);

    $sql = "SELECT class_id FROM regattascoring.CLASS WHERE class_name = 'Junior';";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $class_id = $row['class_id'];
    $sql = "UPDATE regattascoring.CLASS set min_age = '$junior_min_age_format',
        max_age = '$intermediate_min_age_format' WHERE class_id = '$class_id';";
    $result = mysqli_query($conn, $sql);

    $sql = "SELECT class_id FROM regattascoring.CLASS WHERE class_name = 'Intermediate';";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $class_id = $row['class_id'];
    $sql = "UPDATE regattascoring.CLASS set min_age = '$intermediate_min_age_format',
        max_age = '$senior_min_age_format' WHERE class_id = '$class_id';";
    $result = mysqli_query($conn, $sql);

    $sql = "SELECT class_id FROM regattascoring.CLASS WHERE class_name = 'Senior';";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $class_id = $row['class_id'];
    $sql = "UPDATE regattascoring.CLASS set min_age = '$senior_min_age_format',
        max_age = '$senior_max_age_format' WHERE class_id = '$class_id';";
    $result = mysqli_query($conn, $sql);


    //echo classes created
    echo "  <div class='container'>
        <div class='content'>
          <body>";
    echo "<div class='message'> Classes updated" . "</br>";
    echo "<a href='viewclass.php'>Edit Classes</a><div>";
    echo "<div class='close'>
            <ul>
              <li><a href='/'>Return Home</a></li>
              <li><a href='searchclass.php'>View all Classes</a></li>
            </ul>
          </div>
        </div>
      </div>";
    mysqli_close($conn);

// if nothing has been selected
} else {

    //call form with existing values;?>
    <div class='container'>
        <div class='content'>
          <body>
            <h1>Edit Classes</h1>
              <ul class="labels">
                <li>Junior Junior Class Minimum Age:</li>
                <li>Junior Class Minimum Age:</li>
                <li>Intermediate Class Minimum Age:</li>
                <li>Senior Class Minimum Age:</li>
                <li>Senior Class Maximum Age:</li>
              </ul>
              <form action= viewclass.php method ="POST">
                <div class="inside-form">
                  <input type='number' name="jj_min_age"
                  <?php
                  $sql = "SELECT min_age FROM regattascoring.CLASS WHERE class_name = 'Junior Junior';";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    echo "value=" . $row['min_age'] ?> placeholder="Minimum Age" step="any" min="0" max="99" required>
                  <input type='number' name="junior_min_age"
                  <?php
                  $sql = "SELECT min_age FROM regattascoring.CLASS WHERE class_name = 'Junior';";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    echo "value=" . $row['min_age'] ?>  placeholder="Minimum Age" step="any" min="0" max="99" required>
                  <input type='number' name="intermediate_min_age"
                  <?php
                  $sql = "SELECT min_age FROM regattascoring.CLASS WHERE class_name = 'Intermediate';";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    echo "value=" . $row['min_age'] ?> placeholder="Minimum Age" step="any" min="0" max="99" required>
                  <input type='number' name="senior_min_age"
                  <?php
                  $sql = "SELECT * FROM regattascoring.CLASS WHERE class_name = 'Senior';";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    echo "value=" . $row['min_age'] ?> placeholder="Minimum Age" step="any" min="0" max="99" required>
                  <input type='number' name="senior_max_age" value= '<?php echo $row['max_age'] ?>' placeholder="Maximum Age" step="any" max="99" required>
                </div>
                <div class="button">
                  <button type="submit" name="update">Update</button>
                </div>
              </form>
            </body>
          </html>
          <?php

  //call closing function
  echo "<div class='close'>
          <ul>
            <li><a href='/'>Return Home</a></li>
            <li><a href='searchclass.php'>View all Classes</a></li>
          </ul>
        </div>
      </div>
    </div>";
    mysqli_close($conn);
}
?>
