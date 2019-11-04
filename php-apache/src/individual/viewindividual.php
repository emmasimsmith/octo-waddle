<html>
  <head>
    <title>Edit Individual</title>
    <link rel="stylesheet" type="text/css" href="../stylesheets/navbarstyle.css">
    <link rel="stylesheet" type="text/css" href="../stylesheets/pagestyle.css">
  </head>

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
    echo "  <div class='container'>
        <div class='content'>
          <body>";
    echo "<div class='message'>" . $row['first_name'] . " " . $row['last_name'] . " deleted</div>";
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
        <div class='container'>
            <div class='content'>
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
          <form action= <?php echo "viewindividual.php?id=" . $_GET['id']?> method ="POST">
            <div class="inside-form">
              <input type="text" name="first" value= "<?php echo $_POST['first'] ?>" placeholder="First Name">
              <input type="text" name="last" value="<?php echo $_POST['last'] ?>" placeholder="Last Name">
              <input type="date" name="dob" value="<?php echo $_POST['dob'] ?>" placeholder="Date of Birth">
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
              <select name="role">
                <option value="mariner" <?php if ($_POST['role'] == "mariner") {
                    echo "selected";
                } ?>>Mariner</option>
                <option value="other" <?php if ($_POST['role'] == "other") {
                    echo "selected";
                } ?>>Parent/Sibling/Leader</option>
              </select>
              <input type="text" name="comments" value="<?php echo $_POST['comments'] ?>" placeholder="Comments">
            </div>
            <div class="button">
              <button type="submit" name="update">Update</button>
              <button type="submit" name="delete">Delete</button>
            </div>
          </form>
        </body>
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
    echo "  <div class='container'>
        <div class='content'>
          <body>";
    echo "<div class='message'>" . $_POST['first'] . " " . $_POST['last'] . " updated</div>";
    close($conn, $error, "individual", "Individuals");
} else {
    //GET ID from URL
    $individual_id = mysqli_real_escape_string($conn, $_GET['id']);

    //call table select function
    $row = viewselect($conn, $individual_id, "individual", "regattascoring.INDIVIDUAL", "Individuals");

    //select UNIT table
    $result = selectall($conn, "unit_name", "regattascoring.UNIT", "Unit", "individual", "Individuals");

    //call form with previous values?>
    <div class='container'>
        <div class='content'>
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
            <form action= <?php echo "viewindividual.php?id=" . $_GET['id'] ?> method ="POST">
              <div class="inside-form">
                <input type="text" name="first" value= "<?php echo $row['first_name'] ?>" placeholder="First Name">
                <input type="text" name="last" value="<?php echo $row['last_name'] ?>" placeholder="Last Name">
                <input type="date" name="dob" value="<?php echo $row['dob'] ?>" placeholder="Date of Birth">
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
                <select name="role">
                  <option value="mariner" <?php if ($_POST['role'] == "mariner") {
                      echo "selected";
                  } ?>>Mariner</option>
                  <option value="other" <?php if ($_POST['role'] == "other") {
                      echo "selected";
                  } ?>>Parent/Sibling/Leader</option>
                </select>
                <input type="text" name="comments" value="<?php echo $row['comments'] ?>" placeholder="Comments">
              </div>
              <div class="button">
                <button type="submit" name="update">Update</button>
                <button type="submit" name="delete">Delete</button>
              </div>
              </form>
            </body>
        <?php

  //call closing function
  close($conn, $error, "individual", "Individuals");
}
?>
