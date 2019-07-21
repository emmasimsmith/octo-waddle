<?php
//include navigation bar and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

//function for the individual form
function individualform()
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

    //echo errors then exit
    if (count($errors) != 0) {
        //call form with existing values?>
        <html>
          <head>
            <title>Create Individual</title>
          </head>
          <h1>Create New Individual</h1>
          <body>
            <form action="viewindividual.php" method ="POST">
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
              <button type="submit" name="submit">Enter</button>
            </form>
          </body>
        </html>
        <?php
        //echo errors from the input sanitsation
        foreach ($errors as $error) {
            $issue = "";
            $issue = $issue . $error . "</br>";
        }
        close($conn, $issue, $name);
        exit;
    }

    //Insert variables into individual table, if false echo error and exit
    $sql = "INSERT INTO regattascoring.INDIVIDUAL (first_name, last_name, dob,
      comments) VALUES ('$first_name','$last_name','$dob','$comments');";
    if (!mysqli_query($conn, $sql)) {
        $error = "Could not add data";
        close($conn, $error, $name);
        exit;
    }

    //echo class created
    echo $_POST['first'] . " " . $_POST['last'] . " Created";
    $individual_id = mysqli_insert_id($conn); ?>
    <br>
    <a href = <?php echo "viewindividual.php?id=$individual_id"?>>Edit <?php echo $_POST['first'] . " " . $_POST['last'] ?></a>
    <php>
    <?php

    //call individual form
    individualform();

    //call closing function
    close($conn, $error, $name);
} else {
    //call individual form
    individualform();

    ///call closing function
    close($conn, $error, $name);
};
