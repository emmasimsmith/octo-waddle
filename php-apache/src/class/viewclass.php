<?php
// include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once "../connection.php";
include_once '../functions.php';

//if delete button is selected in form
if (isset($_POST["delete"])) {

    //Select class name from the table
    $sql = "DELETE FROM regattascoring.CLASS;";
    $result = mysqli_query($conn, $sql);

    //echo class deleted and call closing
    echo "Classes deleted
    <br>
    <a href='/'>Return Home</a>
    <br>
    <a href='createclass.php'>Create Classes</a>";
    mysqli_close($conn);

//if update button is selected
} elseif (isset($_POST["update"])) {

    //while loop for each class
    $count = 0;

    //array for validation errors
    $errors = array();

    while ($count < $_GET['num_classes']) {

        //increase count by one
        $count++;

        //define POST variables from form
        $class_name = mysqli_real_escape_string($conn, $_POST["class$count"]);
        $min_age = mysqli_real_escape_string($conn, $_POST["min_age$count"]);
        if ($count == $_GET['num_classes']) {
            $max_age = mysqli_real_escape_string($conn, $_POST['max_age']);
        }

        //validation of variables
        if (!$class_name) {
            array_push($errors, "Class $count name must be entered");
        } elseif (preg_match('/[^A-Za-z ]/', $class_name)) {
            array_push($errors, "Please enter a valid class $count name");
        }
        if (!$min_age) {
            array_push($errors, "Minimum age for class $count must be entered");
        } elseif (!is_numeric($min_age) or $min_age < 0) {
            array_push($errors, "Please enter a valid minimum age for class $count");
        }

        //check min age is greater than the last
        $amount = $_GET['num_classes'] - 1;
        if ($count < $amount) {
            $number = $count;
            $number++;
            if ($min_age > $_POST["min_age$number"]) {
                array_push($errors, "Class $count minimum age cannot be greater than Class $number minimum age");
            }
        }

        //if last class check max age
        if ($count == $_GET['num_classes']) {
            if (!$max_age) {
                array_push($errors, "Maximum age for class $count must be entered");
            }
            if (!is_numeric($max_age) or $max_age < 0) {
                array_push($errors, "Please enter a valid maximum age for class $count");
            }
            if ($min_age >= $max_age) {
                array_push($errors, "The maximum age must be greater than the minimum age for class $count");
            }
        }
    }


    //if not valid echo error and form then exit
    if (count($errors) != 0) {
        //call form with existing values?>
      <html>
      <head>
          <title>Update Classes</title>
      </head>
      <h1>Update Classes</h1>
      <body>
      <form action= <?php echo "viewclass.php?num_classes=" . $_GET['num_classes'] ?> method ="POST">
        <?php
        $count = 0;
        while ($count < $_GET['num_classes']) {
            $count++;
            echo "Class " . $count . ":" . "<br>";
            echo "Class Name: ";
            echo "<input type='text' name='class$count' value=" . $_POST["class$count"] . " placeholder='Class Name'>";
            echo "<br>" . "Minimum Age: ";
            echo "<input type='number' name='min_age$count' value=" . $_POST["min_age$count"] . " placeholder='Minimum Age' step='any'>";
            echo "<br>";
        }
        echo "Maximum Age: ";
        echo "<input type='number' name='max_age' value=" . $_POST["max_age"] . " step='any' placeholder='Maximum Age'>"; ?>
        <br>
        <button type="submit" name="update">Update</button>
        <button type="submit" name="delete">Delete</button>
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

    //select id from table
    $sql = "SELECT * FROM regattascoring.CLASS;";
    $result = mysqli_query($conn, $sql);
    $count = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $count++;
        if ($count == mysqli_num_rows($result)) {

            //set variables for insert
            $class_name = $_POST["class$count"];
            $min_age = $_POST["min_age$count"];
            $max_age = $_POST["max_age"];
            $min_age_formated = number_format($min_age, 1, '.', ',');
            $max_age_formated = number_format($max_age, 1, '.', ',');

            //insert variables into class table, if false echo error and exit
            $sql = "UPDATE regattascoring.CLASS set class_name = '$class_name',
            min_age = '$min_age_formated', max_age = '$max_age_formated'
            WHERE class_id = " . $row['class_id'] . ";";

            //check class added
            if (!mysqli_query($conn, $sql)) {
                close($conn, "Could not update classes", "class", "Classes");
                exit;
            }
        } else {

        //set variables for insert
            $number = $count;
            $number++;
            $class_name = $_POST["class$count"];
            $min_age = $_POST["min_age$count"];
            $max_age = $_POST["min_age$number"];
            $min_age_formated = number_format($min_age, 1, '.', ',');
            $max_age_formated = number_format($max_age, 1, '.', ',');

            //insert variables into class table, if false echo error and exit
            $sql = "UPDATE regattascoring.CLASS set class_name = '$class_name',
              min_age = '$min_age_formated', max_age = '$max_age_formated'
              WHERE class_id = " . $row['class_id'] . ";";

            //check class added
            if (!mysqli_query($conn, $sql)) {
                echo mysqli_error($conn) . "</br>";
                close($conn, "Could not update classes", "class", "Classes");
                exit;
            }
        }
    }

    //echo classes created
    $count = 0;
    while ($count < $_GET['num_classes']) {
        $count++;
        echo $_POST["class$count"] . " Class updated" . "</br>";
    }
    echo "<a href='viewclass.php'>Edit Classes</a>";

    //call closing function
    close($conn, $error, "class", "Classes");

// if nothing has been selected
} else {
    //select all from class table
    $sql = "SELECT * FROM regattascoring.CLASS;";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);
    //call form with existing values?>
    <html>
    <head>
        <title>Update Classes</title>
    </head>
    <h1>Update Classes</h1>
    <body>
    <form action= <?php echo "viewclass.php?num_classes=" . $count ?> method ="POST">
      <?php
      $count = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $count++;
        echo "Class " . $count . ":" . "<br>";
        echo "Class Name: ";
        echo "<input type='text' name='class$count' value='" . $row['class_name'] . "' placeholder='Class Name'>";
        echo "<br>" . "Minimum Age: ";
        echo "<input type='number' name='min_age$count' value=" . $row["min_age"] . " placeholder='Minimum Age' step='any'>";
        echo "<br>";
        if ($count == mysqli_num_rows($result)) {
            echo "Maximum Age: ";
            echo "<input type='number' name='max_age' value=" . $row["max_age"] . " step='any' placeholder='Maximum Age'>";
        }
    } ?>
      <br>
      <button type="submit" name="update">Update</button>
      <button type="submit" name="delete">Delete</button>
    </form>
    </body>
   </html>
    <?php

  //call closing function
  echo "<br>
  <a href='/'>Return Home</a>
  <br>
  <a href='searchclass.php'>View all Classes</a>";
    mysqli_close($conn);
}
?>
