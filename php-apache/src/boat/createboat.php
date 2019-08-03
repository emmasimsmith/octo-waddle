<?php
//include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

//function for the boat form
function boatform($result)
{
    ?>
  <html>
  <head>
    <title>Create Boat</title>
  </head>
    <h1>Create Boat</h1>
  <body>
  <p>
    <form action="createboat.php" method="POST">
       Boat Number:
       <input type="text" name="boat_number" placeholder="Boat Number">
       <br>
       Boat Type:
       <select name="boat_type" placeholder="Boat Type">
         <option value="cutter">Cutter</option>
         <option value="sunburst">Sunburst</option>
         <option value="optimist">Optimist</option>
       </select>
       <br>
       Unit:
       <select name = "unit_id" placeholder="Unit Name">
         <?php
         while ($row = mysqli_fetch_assoc($result)) {
             echo "<option value=" . $row['unit_id'] . ">" . $row['unit_name'] . "</option>";
         } ?>
      </select>
       <br>
       Handicap:
       <input type="number" name="boat_handicap" step="any" placeholder="Handicap">
       <br>
       <button type="submit" name="submit">Enter</button>
     </form>
  </p>
  </body>
  </html>
<?php
}

//if form is submitted
if (isset($_POST["submit"])) {

    //POST variables from form
    $boat_number = mysqli_real_escape_string($conn, $_POST['boat_number']);
    $boat_type = mysqli_real_escape_string($conn, $_POST['boat_type']);
    $unit_id = mysqli_real_escape_string($conn, $_POST['unit_id']);
    $boat_handicap = mysqli_real_escape_string($conn, $_POST['boat_handicap']);

    $errors = array();

    if (!$boat_number) {
        array_push($errors, "Please enter a valid boat number");
    }
    if ($boat_type == "cutter") {
        if (preg_match('/[^A-Za-z0-9]/', $boat_number)) {
            array_push($errors, "Please enter a valid cutter number");
        }
    }
    if ($boat_type == "sunburst") {
        if (!is_numeric($boat_number) and $boat_number < 0) {
            array_push($errors, "Please enter a valid sunburst number");
        }
    }
    if ($boat_type == "optimist") {
        if (!is_numeric($boat_number) and $boat_number < 0) {
            array_push($errors, "Please enter a valid optimist number");
        }
    }
    if (!$boat_handicap) {
        $boat_handicap = "1.00";
    }
    if (!is_numeric($boat_handicap)) {
        array_push($errors, "Please enter a valid value");
    }

    //select all function from specific table
    $result = selectall($conn, "unit_name", "regattascoring.UNIT", "Unit", "boat", "Boats");

    if (count($errors) != 0) {
        //call form with existing values?>
      <html>
      <head>
        <title>Create Boat</title>
      </head>
        <h1>Create Boat</h1>
      <body>
      <p>
        <form action="createboat.php" method="POST">
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
           <input type="number" name="boat_handicap" value="<?php echo $boat_handicap?>" step="any" placeholder="Handicap">
           <br>
           <button type="submit" name="submit">Enter</button>
         </form>
      </p>
      </body>
      </html>
      <?php
      //echo errors from input sanitsation
      $issue = "";
        foreach ($errors as $error) {
            $issue = $issue . $error . "</br>";
        }
        close($conn, $issue, "boat", "Boats");
        exit;
    }

    //Insert variables into boat table, if false echo error and exit
    $sql = "INSERT INTO regattascoring.BOAT (boat_number, boat_type, unit_id,
      boat_handicap) VALUES ('$boat_number','$boat_type', '$unit_id', '$boat_handicap');";
    if (!mysqli_query($conn, $sql)) {
        echo mysqli_error($conn);

        close($conn, "Could not add data", "boat", "Boats");
        exit;
    }

    //echo boat created
    echo $_POST['boat_number'] . " " . $_POST['boat_type'] . " Created";
    $boat_id = mysqli_insert_id($conn); ?>
    <br>
    <a href = <?php echo "viewboat.php?id=$boat_id"?>>Edit <?php echo $_POST['boat_number'] . " " . $_POST['boat_type']?></a>
    <?php

    //call select all function for form
    $result = selectall($conn, "unit_name", "regattascoring.UNIT", "Unit", "boat", "Boats");

    //call boat form
    boatform($result);

    //call close
    close($conn, $error, "boat", "Boats");
} else {
    //call select all function for form
    $result = selectall($conn, "unit_name", "regattascoring.UNIT", "Unit", "boat", "Boats");

    //call boat form
    boatform($result);

    //call close
    close($conn, $error, "boat", "Boats");
}
