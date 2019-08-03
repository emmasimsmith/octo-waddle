<?php
//include navigation bar ,functions and connection php files
include_once '../navbar.php';
include_once "../connection.php";
include_once '../functions.php';

//if delete is selected in form
if (isset($_POST["delete"])) {

  // Get the id number
    $boat_id_escaped = mysqli_real_escape_string($conn, $_GET['id']);

    //Select boat name from the table
    $sql = "SELECT boat_number, boat_type FROM regattascoring.BOAT WHERE
    boat_id = '$boat_id_escaped';";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    //call delete function
    deletevariable($conn, "boat", $boat_id_escaped, "regattascoring.BOAT", "Boats");

    //echo boat deleted and call closing function
    echo $row['boat_number'] . " " . $row['boat_type'] . " deleted";
    close($conn, $error, "boat", "Boats");

//if update was selected
} elseif (isset($_POST["update"])) {

  //GET ID
    $boat_id_escaped = mysqli_real_escape_string($conn, $_GET['id']);

    //POST variables from form
    $new_boat_number_escaped = mysqli_real_escape_string($conn, $_POST['boat_number']);
    $new_boat_type_escaped = mysqli_real_escape_string($conn, $_POST['boat_type']);
    $new_unit_id_escaped = mysqli_real_escape_string($conn, $_POST['unit_id']);
    $new_boat_handicap_escaped = mysqli_real_escape_string($conn, $_POST['boat_handicap']);

    //array for errors
    $errors = array();

    //input sanitsation
    if (!$new_boat_number_escaped) {
        array_push($errors, "Please enter a valid boat number");
    }
    if ($new_boat_type_escaped == "cutter") {
        if (preg_match('/[^A-Za-z0-9]/', $new_boat_number_escaped)) {
            array_push($errors, "Please enter a valid cutter number");
        }
    }
    if ($new_boat_type_escaped == "sunburst") {
        if (!is_numeric($new_boat_number_escaped) and $new_boat_number_escaped < 0) {
            array_push($errors, "Please enter a valid sunburst number");
        }
    }
    if ($new_boat_type_escaped == "optimist") {
        if (!is_numeric($new_boat_number_escaped) and $new_boat_number_escaped < 0) {
            array_push($errors, "Please enter a valid optimist number");
        }
    }
    if (!$new_boat_handicap_escaped) {
        $new_boat_handicap_escaped = "1.00";
    }
    if (!is_numeric($new_boat_handicap_escaped)) {
        array_push($errors, "Please enter a valid value");
    }

    //Select Unit table
    $result = selectall($conn, "unit_name", "regattascoring.UNIT", "Unit", "boat", "Boats");

    //if there are input sanitsation errors
    if (count($errors) != 0) {
        //call form with existing values?>
      <html>
      <head>
        <title>Create Boat</title>
      </head>
        <h1>Create Boat</h1>
      <body>
      <p>
        <form action= <?php echo "viewboat.php?id=" . $_GET['id']?> method="POST">
           Boat Number:
           <input type="text" name="boat_number" value="<?php echo $_POST['boat_number']?>" placeholder="Boat Number">
           <br>
           Boat Type:
           <select name="boat_type" placeholder="Boat Type">
             <option value="cutter" <?php if ($_POST['boat_type'] == "cutter") {
            echo "selected";
        } ?>>Cutter</option>
             <option value="sunburst"<?php if ($_POST['boat_type'] == "sunburst") {
            echo "selected";
        } ?>>Sunburst</option>
             <option value="optimist"<?php if ($_POST['boat_type'] == "optimist") {
            echo "selected";
        } ?>>Optimist</option>
           </select>
           <br>
           Unit:
           <select name = "unit" placeholder="Unit">
             <?php
             while ($row = mysqli_fetch_assoc($result)) {
                 echo "<option value=" . $row['unit_id'] . " ";
                 if ($_POST['unit_id'] == $row['unit_id']) {
                     echo "selected";
                 }
                 echo " >" . $row['unit_name'] . "</option>";
             } ?>
          </select>
           <br>
           Handicap:
           <input type="number" name="boat_handicap" value="<?php
          echo $_POST['boat_handicap'] ?>" step="any" placeholder="Handicap">
           <br>
           <button type="submit" name="update">Update</button>
           <button type="submit" name="delete">Delete</button>
           </form>
      </p>
      </body>
      </html>
      <?php

      //echo input sanitsation errors
        $issue = '';
        foreach ($errors as $error) {
            $issue = $issue . $error . "</br>";
        }
        close($conn, $issue, "boat", "Boats");
        exit;
    }

    //Update table
    $sql = "UPDATE regattascoring.BOAT set boat_number =
    '$new_boat_number_escaped', boat_type = '$new_boat_type_escaped',
    unit_id = '$new_unit_id_escaped', boat_handicap = '$new_boat_handicap_escaped'
    WHERE boat_id = '$new_boat_id_escaped';";

    //Check table updated, if not exit
    if (!mysqli_query($conn, $sql)) {
        mysqli_error($conn);
        close($conn, "Could not update boat", "boat", "Boats");
        exit;
    }

    //echo boat updated and close
    echo $_POST['boat_number'] . " " . $_POST['boat_type'] . " updated";
    close($conn, $error, "boat", "Boats");
} else {
    //GET ID from URL
    $boat_id = mysqli_real_escape_string($conn, $_GET['id']);

    //call table select function
    $row = viewselect($conn, $boat_id, "boat", "regattascoring.BOAT", "Boats");

    //select UNIT table
    $result = selectall($conn, "unit_name", "regattascoring.UNIT", "Unit", "boat", "Boats");

    //call form with previous values?>
  <html>
  <head>
    <title>Create Boat</title>
  </head>
    <h1>Create Boat</h1>
  <body>
  <p>
    <form action= <?php echo "viewboat.php?id=" . $_GET['id']?> method="POST">
       Boat Number:
       <input type="text" name="boat_number" value="<?php echo $row['boat_number']?>" placeholder="Boat Number">
       <br>
       Boat Type:
       <select name="boat_type" placeholder="Boat Type">
         <option value="cutter" <?php if ($row['boat_type'] == "cutter") {
        echo "selected";
    } ?>>Cutter</option>
         <option value="sunburst"<?php if ($row['boat_type'] == "sunburst") {
        echo "selected";
    } ?>>Sunburst</option>
         <option value="optimist"<?php if ($row['boat_type'] == "optimist") {
        echo "selected";
    } ?>>Optimist</option>
       </select>
       <br>
       Unit:
       <select name = "unit" placeholder="Unit">
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
       Handicap:
       <input type="number" name="boat_handicap" step="any" value="<?php echo $row['boat_handicap']?>" placeholder="Handicap">
       <br>
       <button type="submit" name="update">Update</button>
       <button type="submit" name="delete">Delete</button>
      </form>
  </p>
  </body>
  </html>
  <?php

  //call closing function
  close($conn, $error, "boat", "Boats");
}
?>
