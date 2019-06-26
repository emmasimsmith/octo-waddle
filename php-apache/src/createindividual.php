<?php
if (isset($_POST["submit"])) {
    include_once 'connection.php'; ?>
    <html>
    <head>
        <title>Create Individual</title>
    </head>
    <h1>Create New Individual</h1>
    <body>

    <form action="createindividual.php" method ="POST">
      First Name:
      <input type="text" name="first" placeholder="First name">
      <br>
      Last Name:
      <input type="text" name="last" placeholder="Last name">
      <br>
      Date of Birth:
      <input type="date" name="dob" placeholder="Date of Birth">
      <br>
      Commments:
      <input type="text" name="comments" placeholder="Comments">
      <br>
      <button type="submit" name="submit">Enter</button>
    </form>

    </body>
    </html>
    <?php

    $first_name = mysqli_real_escape_string($conn, $_POST['first']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $comments = mysqli_real_escape_string($conn, $_POST['comments']);

    $errors = array();

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
    if (strlen($dob) != 10) {
        array_push($errors, "Please enter a valid date of birth");
    }

    if (count($errors) != 0) {
        foreach ($errors as $error) {
            echo $error . "</br>";
        }
        mysqli_close($conn); ?>
        <br>
        <a href="/">Return Home</a>
        <br>
        <a href="createindividual.php">Submit another response</a>
        <br>
        <a href="searchindividual.php">View all individuals</a>
        <?php
        exit;
    }

    $sql = "INSERT INTO regattascoring.INDIVIDUAL (first_name, last_name, dob, comments) VALUES ('$first_name','$last_name','$dob','$comments');";
    if (!mysqli_query($conn, $sql)) {
        echo "ERROR: Could not add data" . mysqli_error($conn) . "</br>";
    }
    $individual_id = mysqli_insert_id($conn);
    $sql = "SELECT * FROM regattascoring.INDIVIDUAL WHERE individual_id = '$individual_id';";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "ERROR: Could not select individual" . mysqli_error($conn) . "</br>";
    }

    if (mysqli_num_rows($result) == 0) {
        echo "Nothing Selected"; ?>
      <a href="/">Return Home</a>
      <br>
      <a href="searchindividual.php">View all individuals</a>
      <?php
        exit;
    } elseif (mysqli_num_rows($result) >1) {
        echo "Too many units selected";
        exit; ?>
      <a href="/">Return Home</a>
      <br>
      <a href="createunit.php">Submit another response</a>
      <br>
      <a href="searchunit.php">View all individuals</a>
      <?php
    }
    $row = mysqli_fetch_assoc($result);
    echo $row ['first_name'] . " " . ['last_name'] . " Created"; ?>
    <br>
    <a href = <?php echo "viewindividual.php?id=$individual_id"?>>Edit <?php echo $row['first_name'] . " " . $row['last_name'] ?></a>
    <?php
    mysqli_close($conn); ?>
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
      First Name:
      <input type="text" name="first" placeholder="First name">
      <br>
      Last Name:
      <input type="text" name="last" placeholder="Last name">
      <br>
      Date of Birth:
      <input type="date" name="dob" placeholder="Date of Birth">
      <br>
      Commments:
      <input type="text" name="comments" placeholder="Comments">
      <br>
      <button type="submit" name="submit">Enter</button>
    </form>

    </body>
    </html>
    <br>
    <a href="/">Return Home</a>
    <br>
    <a href="searchindividual.php">View all individuals</a>
  <?php
    };
