<?php
include_once '../navbar.php';
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
    <input type="number" name="min_age" step="any" placeholder="Minimum Age">
    <br>
    Maximum Age:
    <input type="number" name="max_age" step="any" placeholder="Maximum Age">
    <br>
    <button type="submit" name="submit">Enter</button>
  </form>
  </body>
    <br>
    <a href="searchclass.php">View all classs</a>
    </html>
<?php
}

if (isset($_POST["submit"])) {
    include_once '../connection.php';
    classform();

    $class_name = mysqli_real_escape_string($conn, $_POST['class_name']);
    $min_age = mysqli_real_escape_string($conn, $_POST['min_age']);
    $max_age = mysqli_real_escape_string($conn, $_POST['max_age']);
    $errors = array();

    if (!$class_name) {
        array_push($errors, "Class name must be entered");
    }

    if (preg_match('/[^A-Za-z]/', $class_name)) {
        array_push($errors, "Please enter a valid class name");
    }

    if (count($errors) != 0) {
        foreach ($errors as $error) {
            echo $error . "</br>";
        }
        mysqli_close($conn); ?>

        <br>
        <a href="createclass.php">Submit another response</a>
        <br>
        <a href="searchclass.php">View all classes</a>
        <?php
        exit;
    }

    $sql = "INSERT INTO regattascoring.CLASS (class_name, min_age, max_age) VALUES ('$class_name', '$min_age','$max_age');";
    if (!mysqli_query($conn, $sql)) {
        echo "ERROR: Could not add class" . mysqli_error($conn) . "</br>"; ?>
        <br>
        <a href="createclass.php">Submit another response</a>
        <?php
        exit;
    }

    echo $_POST['class_name'] . " Class Created";
    mysqli_close($conn); ?>

    <br>
    <a href= <?php echo "viewclass.php?id=$class_id" ?>>Edit <?php echo $row['class_name'] ?></a>
    <br>
    <a href="createclass.php">Submit another response</a>
    <?php
} else {
        classform();
    }
