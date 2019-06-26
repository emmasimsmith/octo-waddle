<?php
if (isset($_POST["submit"])) {
    include_once 'connection.php'; ?>
    <html>
    <head>
        <title>Create Unit</title>
    </head>
    <h1>Create New Unit</h1>
    <body>
    <form action= <?php echo "createunit.php" . $_GET['id'] ?> method ="POST">
      Unit Name:
      <input type="text" name="unit_name" placeholder="Unit Name">
      <br>
      <button type="submit" name="submit">Enter</button>
    </form>
    </body>
    </html>
    <?php

    $unit_name = mysqli_real_escape_string($conn, $_POST['unit_name']);
    $errors = array();

    if ($unit_name == "") {
        array_push($errors, "Unit name must be entered");
    }

    if (preg_match('/[^A-Za-z \-]/', $unit_name)) {
        array_push($errors, "Please enter a valid unit name");
    }

    if (count($errors) != 0) {
        foreach ($errors as $error) {
            echo $error . "</br>";
        }
        mysqli_close($conn); ?>

        <br>
        <a href="/">Return Home</a>
        <br>
        <a href="createunit.php">Submit another response</a>
        <br>
        <a href="searchunit.php">View all Units</a>
        <?php
        exit;
    }

    $sql = "INSERT INTO regattascoring.UNIT (unit_name) VALUES ('$unit_name');";
    if (!mysqli_query($conn, $sql)) {
        echo "ERROR: Could not add unit" . mysqli_error($conn) . "</br>";
    }
    $unit_id = mysqli_insert_id($conn);
    $sql = "SELECT * FROM regattascoring.UNIT WHERE unit_id = '$unit_id';";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "ERROR: Could not select unit" . mysqli_error($conn) . "</br>";
    }

    if (mysqli_num_rows($result) == 0) {
        echo "Nothing Selected"; ?>
      <a href="/">Return Home</a>
      <br>
      <a href="createunit.php">Submit another response</a>
      <br>
      <a href="searchunit.php">View all Units</a>
      <?php
        exit;
    } elseif (mysqli_num_rows($result) >1) {
        echo "Too many units selected";
        exit; ?>
      <a href="/">Return Home</a>
      <br>
      <a href="createunit.php">Submit another response</a>
      <br>
      <a href="searchunit.php">View all Units</a>
      <?php
    }
    $row = mysqli_fetch_assoc($result);
    echo $row ['unit_name'] . " Unit Created"; ?>
    <br>
    <a href= <?php echo "viewunit.php?id=$unit_id" ?>>Edit <?php echo $row['unit_name'] ?></a>
    <?php
    mysqli_close($conn);
    //header("Location: viewunit.php?id=$unit_id", true, 302);?>
    <br>
    <a href="/">Return Home</a>
    <br>
    <a href="createunit.php">Submit another response</a>
    <?php
} else {
        ?>
    <html>
    <head>
        <title>Create Unit</title>
    </head>
  <h1>Create New Unit</h1>
    <body>
    <form action= "createunit.php" method ="POST">
      Unit Name:
      <input type="text" name="unit_name" placeholder="Unit Name">
      <br>
      <button type="submit" name="submit">Enter</button>
    </form>
    </bodys
    </html>
    <br>
    <a href="/">Return Home</a>
    <br>
    <a href="searchunit.php">View all Units</a>
  <?php
    };
