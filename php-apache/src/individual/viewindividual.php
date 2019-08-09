<?php
//include navigation bar ,functions and connection php files
include_once '../navbar.php';
include_once "../connection.php";
include_once '../functions.php';

//if delete is selected in form
if (isset($_POST["delete"])) {

  // Get the id number
    $individual_id_escaped = mysqli_real_escape_string($conn, $_GET['id']);

    //Select individual name from the table
    $sql = "SELECT first_name, last_name FROM regattascoring.INDIVIDUAL WHERE
    individual_id = '$individual_id_escaped';";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    //call delete function
    deletevariable($conn, "individual", $individual_id_escaped, "regattascoring.INDIVIDUAL", "Individuals");

    //echo individual deleted and call closing function
    echo $row['first_name'] . " " . $row['last_name'] . " deleted";
    close($conn, $error, "individual", "Individuals");

//if update was selected
} elseif (isset($_POST["update"])) {

  //GET ID
    $individual_id_escaped = mysqli_real_escape_string($conn, $_GET['id']);

    //POST variables from form
    $new_first_name_escaped = mysqli_real_escape_string($conn, $_POST['first']);
    $new_last_name_escaped = mysqli_real_escape_string($conn, $_POST['last']);
    $new_dob_escaped = mysqli_real_escape_string($conn, $_POST['dob']);
    $new_unit_id_escaped = mysqli_real_escape_string($conn, $_POST['unit']);
    $new_role_escaped = mysqli_real_escape_string($conn, $_POST['role']);
    $new_comments_escaped = mysqli_real_escape_string($conn, $_POST['comments']);

    //array for errors
    $errors = array();

    //input sanitsation
    if (!$new_first_name_escaped) {
        array_push($errors, "First name must be entered");
    }
    if (preg_match('/[^A-Za-z \-]/', $new_first_name_escaped)) {
        array_push($errors, "Please enter a valid first name");
    }
    if (!$new_last_name_escaped) {
        array_push($errors, "Last name must be entered");
    }
    if (preg_match('/[^A-Za-z \-]/', $new_last_name_escaped)) {
        array_push($errors, "Please enter a valid last name");
    }
    if (!$new_dob_escaped) {
        array_push($errors, "Date of birth must be entered");
    }
    if (strlen($new_dob_escaped) != 10) {
        array_push($errors, "Please enter a valid date of birth");
    }

    //Select Unit table
    $result = selectall($conn, "unit_name", "regattascoring.UNIT", "Unit", "individual", "Individuals");

    //if there are input sanitsation errors
    if (count($errors) != 0) {
        //call form with existing values?>
      <html>
        <head>
          <title>Create Individual</title>
        </head>
          <h1>Create New Individual</h1>
        <body>
          <form action= <?php echo "viewindividual.php?id=" . $_GET['id']?> method ="POST">
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
              <option value="mariner" <?php if ($_POST['role'] == "mariner") {
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
            <button type="submit" name="update">Update</button>
            <button type="submit" name="delete">Delete</button>
          </form>
        </body>
      </html>
      <?php

      //echo input sanitsation errors
      $issue = '';
        foreach ($errors as $error) {
            $issue = $issue . $error . "</br>";
        }
        close($conn, $issue, "individual", "Individuals");
        exit;
    }

    //Update table
    $sql = "UPDATE regattascoring.INDIVIDUAL set first_name =
    '$new_first_name_escaped', last_name = '$new_last_name_escaped',
    dob = '$new_dob_escaped', unit_id = '$new_unit_id_escaped',
    role = '$new_role_escaped', comments = '$new_comments_escaped'
    WHERE individual_id = '$individual_id_escaped';";

    //Check table updated, if not exit
    if (!mysqli_query($conn, $sql)) {
        close($conn, "Could not update individual", "individual", "Individuals");
        exit;
    }

    //echo individual updated and close
    echo $_POST['first'] . " " . $_POST['last'] . " updated";
    close($conn, $error, "individual", "Individuals");
} else {
    //GET ID from URL
    $individual_id = mysqli_real_escape_string($conn, $_GET['id']);

    //call table select function
    $row = viewselect($conn, $individual_id, "individual", "regattascoring.INDIVIDUAL", "Individuals");

    //select UNIT table
    $result = selectall($conn, "unit_name", "regattascoring.UNIT", "Unit", "individual", "Individuals");

    //call form with previous values?>
  <html>
    <head>
        <title>Create Individual</title>
    </head>
      <h1>Create New Individual</h1>
    <body>
      <form action= <?php echo "viewindividual.php?id=" . $_GET['id'] ?> method ="POST">
        First Name:
        <input type="text" name="first" value= "<?php echo $row['first_name'] ?>" placeholder="First Name">
        <br>
        Last Name:
        <input type="text" name="last" value="<?php echo $row['last_name'] ?>" placeholder="Last Name">
        <br>
        Date of Birth:
        <input type="date" name="dob" value="<?php echo $row['dob'] ?>" placeholder="Date of Birth">
        <br>
        Unit:
        <select name="unit">
          <?php
          while ($unit_row = mysqli_fetch_assoc($result)) {
              echo "<option value=" . $unit_row['unit_id'] . " ";
              if ($row['unit_id'] == $unit_row['unit_id']) {
                  echo "selected";
              }
              echo " >" . $unit_row['unit_name'] . "</option>";
          } ?>
        </select>
        <br>
        Role:
        <select name="role">
          <option value="mariner" <?php if ($_POST['role'] == "mariner") {
              echo "selected";
          } ?>>Mariner</option>
          <option value="other" <?php if ($_POST['role'] == "other") {
              echo "selected";
          } ?>>Parent/Sibling/Leader</option>
        </select>
        <br>
        Comments:
        <input type="text" name="comments" value="<?php echo $row['comments'] ?>" placeholder="Comments">
        <br>
          <button type="submit" name="update">Update</button>
          <button type="submit" name="delete">Delete</button>
        </form>
      </body>
    </html>
  <?php

  //call closing function
  close($conn, $error, "individual", "Individuals");
}
?>
