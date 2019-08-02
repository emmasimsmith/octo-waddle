<?php
//include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

//function for the individual form
function individualform($result)
{
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
    Unit:
    <select name = "unit">
    <?php
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value=" . $row['unit_id'] . ">" . $row['unit_name'] . "</option>";
    } ?>
    </select>
    <br>
    Role:
    <select name="role">
      <option value="Mariner">Mariner</option>
      <option value="other">Parent/Sibling/Leader</option>
    </select>
    <br>
    Commments:
    <input type="text" name="comments" placeholder="Comments">
    <br>
    <button type="submit" name="submit">Enter</button>
  </form>

  </body>
  </html>
  <?php
}

//If Form is submitted
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
    $result = selectall($conn, "unit_name", "UNIT", "Unit", "individual", "Individuals");

    //echo errors then exit
    if (count($errors) != 0) {
        //call form with existing values?>
        <html>
          <head>
            <title>Create Individual</title>
          </head>
          <h1>Create New Individual</h1>
          <body>
            <form action="createindividual.php" method ="POST">
              First Name:
              <input type="text" name="first" value= "<?php echo $_POST['first'] ?>" placeholder="First Name">
              <br>
              Last Name:
              <input type="text" name="last" value="<?php echo $_POST['last'] ?>" placeholder="Last Name">
              <br>
              Date of Birth:
              <input type="date" name="dob" value="<?php echo $_POST['dob'] ?>" placeholder="Date of Birth">
              <br>
              Unit:
              <select name="unit">
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value=" . $row['unit_id'] . " ";
                    if ($_POST['unit'] == $row['unit_id']) {
                        echo "selected";
                    }
                    echo " >" . $row['unit_name'] . "</option>";
                } ?>
              </select>
              <br>
              Role:
              <select name="role">
                <option value="Mariner" <?php if ($_POST['role'] == "mariner") {
                    echo "selected";
                } ?>>Mariner</option>
                <option value="other" <?php if ($_POST['role'] == "other") {
                    echo "selected";
                } ?>>Parent/Sibling/Leader</option>
              </select>
              <br>
              Comments:
              <input type="text" name="comments" value="<?php echo $_POST['comments'] ?>" placeholder="Comments">
              <br>
              <button type="submit" name="submit">Enter</button>
            </form>
          </body>
        </html>
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

    //echo individual created
    echo $_POST['first'] . " " . $_POST['last'] . " Created";
    $individual_id = mysqli_insert_id($conn); ?>
    <br>
    <a href = <?php echo "viewindividual.php?id=$individual_id"?>>Edit <?php echo $_POST['first'] . " " . $_POST['last'] ?></a>
    <?php

    //call select all function for form
    $rows = selectall($conn, "unit_name", "UNIT", "Unit", "individual", "Individuals");

    //call individual form
    individualform($rows);

    //call closing function
    close($conn, $error, "individual", "Individuals");
} else {

    //select all function from specific table
    $result = selectall($conn, "unit_name", "UNIT", "Unit", "individual", "Individuals");

    //call individual form
    individualform($result);

    //call closing function
    close($conn, $error, "individual", "Individuals");
}
