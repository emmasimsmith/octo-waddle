<?php
//include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

//function for the class form
function classform($num_classes)
{
    ?>
  <html>
  <head>
      <title>Create Class</title>
  </head>
  <h1>Create New Class</h1>
  <body>
  <form action= <?php echo "createclass.php?num_classes=" . $num_classes ?> method ="POST">
    <?php
    $count = 0;
    while ($count < $num_classes) {
        $count++;
        echo "Class " . $count . ":" . "<br>";
        echo "Class Name: ";
        echo "<input type='text' name='class$count' placeholder='Class Name'>";
        echo "<br>" . "Minimum Age: ";
        echo "<input type='number' name='min_age$count' placeholder='Minimum Age' step='any'>";
        echo "<br>";
    } ?>
    Maximum Age:
    <input type='number' name='max_age' step='any' placeholder='Maximum Age'>
    <br>
    <button type="submit" name="submit">Enter</button>
  </form>
  </body>
 </html>
<?php
}

$sql = "SELECT * FROM regattascoring.CLASS;";

if (mysqli_num_rows(mysqli_query($conn, $sql)) != 0) {
    close($conn, "Classes have already been created", "class", "Classes");
    exit;

//IF FORM SUBMITTED
} elseif (isset($_POST["submit"])) {

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
            <title>Create Class</title>
        </head>
        <h1>Create New Class</h1>
        <body>
        <form action= <?php echo "createclass.php?num_classes=" . $_GET['num_classes'] ?> method ="POST">
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
          <button type="submit" name="submit">Enter</button>
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

    //insert classes into table
    $count = 0;
    while ($count < $_GET['num_classes']) {
        $count++;
        if ($count == $_GET['num_classes']) {

        //set variables for insert
            $class_name = $_POST["class$count"];
            $min_age = $_POST["min_age$count"];
            $max_age = $_POST["max_age"];
            $min_age_formated = number_format($min_age, 1, '.', ',');
            $max_age_formated = number_format($max_age, 1, '.', ',');

            //insert variables into class table, if false echo error and exit
            $sql = "INSERT INTO regattascoring.CLASS (class_name, min_age, max_age)
        VALUES ('$class_name', '$min_age_formated','$max_age_formated');";

            //check class added
            if (!mysqli_query($conn, $sql)) {
                close($conn, "Could not add class", "class", "Classes");
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
            $sql = "INSERT INTO regattascoring.CLASS (class_name, min_age, max_age)
            VALUES ('$class_name', '$min_age_formated','$max_age_formated');";

            //check class added
            if (!mysqli_query($conn, $sql)) {
                echo mysqli_error($conn) . "</br>";
                close($conn, "Could not add class", "class", "Classes");
                exit;
            }
        }
    }


    //echo classes created
    $count = 0;
    while ($count < $_GET['num_classes']) {
        $count++;
        echo $_POST["class$count"] . " Class Created" . "</br>";
    }
    echo "<a href='viewclass.php'>Edit Classes</a>";

    //call closing function
    close($conn, $error, "class", "Classes");
} elseif (isset($_POST["entered"])) {

    //call class form
    classform($_POST['num_classes']);

    //call class close
    close($conn, $error, "class", "Classes");
} else {
    ?>
    <html>
      <head>
          <title>Create Class</title>
      </head>
          <h1>Create New Class</h1>
      <body>
        <form action= "createclass.php" method ="POST">
          Number of Classes:
          <input type="number" name="num_classes" placeholder="Number of Classes">
          <br>
          <button type="submit" name="entered">Enter</button>
        </form>
      </body>
    </html>
    <?php
}
