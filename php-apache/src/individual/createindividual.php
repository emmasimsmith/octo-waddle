<html>
  <head>
    <title>Create Individual</title>
    <link rel="stylesheet" type="text/css" href="../stylesheets/navbarstyle.css">
    <link rel="stylesheet" type="text/css" href="../stylesheets/createpagestyle.css">
  </head>

<?php
//include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

//function for the individual form
function individualform($result)
{
    ?>
        <h1>Create New Individual</h1>
          <ul class="labels">
            <li>First Name:</li>
            <li>Last Name:</li>
            <li>Date of Birth:</li>
            <li>Unit:</li>
            <li>Role:</li>
            <li>Comments:</li>
          </ul>
        <form action="createindividual.php" method ="POST">
          <input type="text" name="first" placeholder="First name">
          <input type="text" name="last" placeholder="Last name">
          <input type="date" name="dob" placeholder="Date of Birth">
          <select name = "unit">
          <?php
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<option value=" . $row['unit_id'] . ">" . $row['unit_name'] . "</option>";
          } ?>
          </select>
          <select name="role">
            <option value="mariner">Mariner</option>
            <option value="other">Parent/Sibling/Leader</option>
          </select>
          <input type="text" name="comments" placeholder="Comments">
          <button type="submit" name="submit">Enter</button>
        </form>
      </body>

  <?php
}

//if Form is submitted
if (isset($_POST["submit"])) {

    //POST variables from form
    $first_name = mysqli_real_escape_string($conn, $_POST['first']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $unit = mysqli_real_escape_string($conn, $_POST['unit']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $comments = mysqli_real_escape_string($conn, $_POST['comments']);

    //array for input sanitsation errors
    $errors = array();

    //input sanitsation
    if (!$first_name) {
        array_push($errors, "First name must be entered");
    }

    if (preg_match('/[^A-Za-z \-]/', $first_name)) {
        array_push($errors, "Please enter a valid first name");
    }

    if (!$last_name) {
        array_push($errors, "Last name must be entered");
    }
    if (preg_match('/[^A-Za-z \-]/', $last_name)) {
        array_push($errors, "Please enter a valid last name");
    }

    if (!$dob) {
        array_push($errors, "Date of birth must be entered");
    }
    if (strlen($dob) != 10) {
        array_push($errors, "Please enter a valid date of birth");
    }

    //select all function from specific table
    $result = selectall($conn, "unit_name", "regattascoring.UNIT", "Unit", "individual", "Individuals");

    //echo errors then exit
    if (count($errors) != 0) {
        //call form with existing values?>
        <div class="container">
          <div class="content">
            <body>
              <h1>Create New Individual</h1>
              <ul class="labels">
                <li>First Name:</li>
                <li>Last Name:</li>
                <li>Date of Birth:</li>
                <li>Unit:</li>
                <li>Role:</li>
                <li>Comments:</li>
              </ul>
              <form action="createindividual.php" method ="POST">
                <input type="text" name="first" value= "<?php echo $_POST['first'] ?>" placeholder="First Name">
                <input type="text" name="last" value="<?php echo $_POST['last'] ?>" placeholder="Last Name">
                <input type="date" name="dob" value="<?php echo $_POST['dob'] ?>" placeholder="Date of Birth">
                <select class="select" name="unit">
                  <?php
                  while ($row = mysqli_fetch_assoc($result)) {
                      echo "<option value=" . $row['unit_id'] . " ";
                      if ($_POST['unit'] == $row['unit_id']) {
                          echo "selected";
                      }
                      echo " >" . $row['unit_name'] . "</option>";
                  } ?>
                </select>
                <select class="select" name="role">
                  <option value="mariner" <?php if ($_POST['role'] == "mariner") {
                      echo "selected";
                  } ?>>Mariner</option>
                  <option value="other" <?php if ($_POST['role'] == "other") {
                      echo "selected";
                  } ?>>Parent/Sibling/Leader</option>
                </select>
                <input type="text" name="comments" value="<?php echo $_POST['comments'] ?>" placeholder="Comments">
                <button type="submit" name="submit">Enter</button>
            </form>
          </body>

        <?php
        //echo errors from the input sanitsation
        $issue = "";
        foreach ($errors as $error) {
            $issue = $issue . $error . "</br>";
        }
        close($conn, $issue, "individual", "Individuals");
        exit;
    }

    //Insert variables into individual table, if false echo error and exit
    $sql = "INSERT INTO regattascoring.INDIVIDUAL (first_name, last_name, dob,
      unit_id, role, comments) VALUES ('$first_name','$last_name','$dob', '$unit', '$role', '$comments');";
    if (!mysqli_query($conn, $sql)) {
        close($conn, "Could not add data", "individual", "Individuals");
        exit;
    }

    echo "  <div class='container'>
        <div class='content'>
          <body>";
    //echo individual created
    echo "<div class='message'>" . $_POST['first'] . " " . $_POST['last'] . " Created";
    $individual_id = mysqli_insert_id($conn); ?>
    <br>
    <a href = <?php echo "viewindividual.php?id=$individual_id"?>>Edit <?php echo $_POST['first'] . " " . $_POST['last'] ?></a></div>
    <?php

    //call select all function for form
    $rows = selectall($conn, "unit_name", "regattascoring.UNIT", "Unit", "individual", "Individuals");

    //call individual form
    individualform($rows);

    //call closing function
    close($conn, $error, "individual", "Individuals");
} else {

    //select all function from specific table
    $result = selectall($conn, "unit_name", "regattascoring.UNIT", "Unit", "individual", "Individuals");

    //call individual form
    echo "  <div class='container'>
        <div class='content'>
          <body>";
    individualform($result);

    //call closing function
    close($conn, $error, "individual", "Individuals");
}
