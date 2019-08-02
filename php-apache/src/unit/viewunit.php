<?php
//include navigation bar, functions and connection php files
include_once "../connection.php";
include_once '../navbar.php';
include_once '../functions.php';

//if delete is selected in form
if (isset($_POST["delete"])) {

  //GET the id from url
    $unit_id_escaped = mysqli_real_escape_string($conn, $_GET['id']);

    //Select unit name from table
    $sql = "SELECT unit_name FROM regattascoring.UNIT WHERE unit_id = '$unit_id_escaped';";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    //call delete function
    deletevariable($conn, "unit", $unit_id_escaped, "regattascoring.UNIT", "Units");

    //echo unit deleted and close
    echo $_POST['unit_name'] . " deleted";
    close($conn, $error, "unit", "Units");

//if update was selected
} elseif (isset($_POST["update"])) {
    //GET ID
    $unit_id_escaped = mysqli_real_escape_string($conn, $_GET['id']);

    //POST variables from form
    $new_unit_name_escaped = mysqli_real_escape_string($conn, $_POST['unit_name']);

    //array for errors
    $errors = array();

    //input sanitsation
    if ($new_unit_name_escaped == "") {
        array_push($errors, "Unit name must be entered");
    }
    if (preg_match('/[^A-Za-z \-]/', $new_unit_name_escaped)) {
        array_push($errors, "Please enter a valid unit name");
    }
    if (count($errors) != 0) {
        //call form with existing values?>
        <html>
          <head>
              <title>Create Unit</title>
          </head>
              <h1>Create New Unit</h1>
          <body>
            <form action= <?php echo "viewunit.php?id=" . $_GET['id']?> method ="POST">
              Unit Name:
              <input type="text" name="unit_name" value= "<?php echo $_POST['unit_name']?>" placeholder="Unit Name">
              <br>
              <button type="submit" name="update">Update</button>
              <button type="submit" name="delete">Delete</button>
            </form>
          </body>
        </html>
        <?php

        //echo input sanisation errors
        $issue = '';
        foreach ($errors as $error) {
            $issue = $issue . $error . "</br>";
        }
        close($conn, $issue, "unit", "Units");
        exit;
    }

    //Update table
    $sql = "UPDATE regattascoring.UNIT set unit_name =
        '$new_unit_name_escaped' WHERE unit_id = '$unit_id_escaped';";

    //Check table updated, if not exit
    if (!mysqli_query($conn, $sql)) {
        close($conn, "Could not update unit", "unit", "Units");
        exit;
    }

    //echo table updated and close
    echo $_POST['unit_name'] . " updated";
    close($conn, $error, "unit", "Units");
} else {

    //GET ID from URL
    $unit_id = mysqli_real_escape_string($conn, $_GET['id']);

    //call table select function
    $row = viewselect($conn, $unit_id, "unit", "regattascoring.UNIT", "Units");

    //call form with previous values?>
  <html>
    <head>
        <title>Create Unit</title>
    </head>
        <h1>Create New Unit</h1>
    <body>
      <form action= <?php echo "viewunit.php?id=" . $_GET['id'] ?> method ="POST">
        Unit Name:
        <input type="text" name="unit_name" value="<?php echo $row['unit_name']?>"
        placeholder="Unit Name">
        <br>
        <button type="submit" name="update">Update</button>
        <button type="submit" name="delete">Delete</button>
        <br>
      </form>
    </body>
  </html>
  <?php

  //call close
  close($conn, $error, "unit", "Units");
}
?>
