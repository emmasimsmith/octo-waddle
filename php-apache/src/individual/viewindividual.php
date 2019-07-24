<?php
//include navigation bar ,functions and connection php files
include_once '../navbar.php';
include_once "../connection.php";
include_once '../functions.php';

//function variables
$name = "individual";
$table_name = "INDIVIDUAL";
$plural_name = 'Individuals';

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
    deletevariable($conn, $name, $individual_id_escaped, $table_name, $plural_name);

    //echo individual deleted and call closing function
    echo $row['first_name'] . " " . $row['last_name'] . " deleted";
    close($conn, $error, $name, $plural_name);

//if update was selected
} elseif (isset($_POST["update"])) {

  //GET ID
    $individual_id_escaped = mysqli_real_escape_string($conn, $_GET['id']);

    //POST variables from form
    $new_first_name_escaped = mysqli_real_escape_string($conn, $_POST['first']);
    $new_last_name_escaped = mysqli_real_escape_string($conn, $_POST['last']);
    $new_dob_escaped = mysqli_real_escape_string($conn, $_POST['dob']);
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
      foreach ($errors as $error) {
          $issue = '';
          $issue = $issue . $error . "</br>";
      }
        close($conn, $issue, $name, $plural_name);
        exit;
    }

    //Update table
    $sql = "UPDATE regattascoring.INDIVIDUAL set first_name =
    '$new_first_name_escaped', last_name = '$new_last_name_escaped',
    dob = '$new_dob_escaped', comments = '$new_comments_escaped'
    WHERE individual_id = '$individual_id_escaped';";

    //Check table updated, if not exit
    if (!mysqli_query($conn, $sql)) {
        $error = "Could not update individual";
        close($conn, $error, $name, $plural_name);
        exit;
    }

    //echo individual updated and close
    echo $_POST['first'] . " " . $_POST['last'] . " updated";
    close($conn, $error, $name, $plural_name);
} else {
    //GET ID from URL
    $individual_id = mysqli_real_escape_string($conn, $_GET['id']);

    //call table select function
    $row = viewselect($conn, $individual_id, $name, $table_name, $plural_name);

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
  close($conn, $error, $name, $plural_name);
}
?>
