<?php
if (isset($_POST["submit"])) {
    include_once 'connection.php';

    $first_name = mysqli_real_escape_string($conn, $_POST['first']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $comments = mysqli_real_escape_string($conn, $_POST['comments']);

    $errors = array();

    // TODO: perform input sanitisation
    if ($first_name == "") {
        array_push($errors, "First name must be entered");
    }

    if (preg_match('/[^A-Za-z \-]/', $first_name)) {
        array_push($errors, "Please enter a valid first name");
    }

    if ($last_name == "") {
        array_push($errors, "Last name must be entered");
    }
    if (preg_match('/[^A-Za-z \-]/', $last_name)) {
        array_push($errors, "Please enter a valid last name");
    }

    if ($dob == "") {
        array_push($errors, "Date of birth must be entered");
    }

    if (count($errors) != 0) {
        foreach ($errors as $error) {
            echo $error . "</br>";
        }
        mysqli_close($conn);
        exit;
    };

    $sql = "INSERT INTO regattascoring.INDIVIDUAL (first_name, last_name, dob, comments) VALUES ('$first_name','$last_name','$dob','$comments');";
    if (!mysqli_query($conn, $sql)) {
        echo "ERROR: Could add data" . mysqli_error($conn) . "</br>";
    }

    mysqli_close($conn);
    header('Location: viewindividual.php?first='.$first_name.'&last='.$last_name.'&dob='.$dob, true, 302); ?>
    <br>
    <a href="/">Return Home</a>
    <br>
    <a href="createindividual.php">Submit another response</a>
    <?php
} else {
        ?>
    <html>
    <head>
        <title>Create Individual</title>
    </head>
  <h1>Create New Individual</h1>
    <body>

    <form action="createindividual.php" method ="POST">
      <input type="text" name="first" placeholder="First name">
      <br>
      <input type="text" name="last" placeholder="Last name">
      <br>
      <input type="date" name="dob" placeholder="Date of Birth">
      <br>
      <input type="text" name="comments" placeholder="Comments">
      <br>
      <button type="submit" name="submit">Enter</button>
    </form>

    </body>
    </html>
  <?php
    };
