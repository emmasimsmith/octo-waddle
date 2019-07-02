<?php
include_once '../navbar.php';
include_once '../connection.php';
function boatform()
{
    $sql = "SELECT * FROM regattascoring.UNIT;";
    $result = mysqli_query($conn, $sql);
    if (!mysqli_num_rows($result)) {
        echo "Please create Units first" . mysqli_error($conn);
        mysqli_close($conn); ?>
      <br>
      <a href="/">Return Home</a>
      <?php
      exit;
    } ?>
<html>
<head>
<title>Add Boat</title>
</head>
<h1>Add Boat</h1>
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
 <select name = "unit" placeholder="Unit">
   <?php
   while ($row = mysqli_fetch_assoc($result)) {
       echo "<option value=" . $row['unit_name'] . ">" . $row['unit_name'] . "</option";
   } ?>
 <br>
 Handicap:
 <input type="number" name="boat_handicap" placeholder="Handicap">
 <br>
 <button type="submit" name="submit">Enter</button>
</form>
<br>
<a href="searchboat.php">View all boats</a>
</p>
</body>
</html>
<?php
}
if (isset($_POST["submit"])) {
    //TODO make submit
    boatform();

    $boat_number = mysqli_real_escape_string($conn, $_POST['boat_number']);
    $boat_type = mysqli_real_escape_string($conn, $_POST['boat_number']);
    $boat_handicap = mysqli_real_escape_string($conn, $_POST['boat_handicap']);

    $errors = array();

    if ($boat_type == "cutter") {
        if (preg_match('/[^(YM)(ym)0-9]/', $boat_number)) {
            array_push($errors, "Please enter a valid cutter number");
        }
    }
    if ($boat_type == "sunburst") {
        if (preg_match('/[0-9*]/', $boat_number)) {
            array_push($errors, "Please enter a valid sunburst number");
        }
    }
    if ($boat_type == "optimist") {
        if (preg_match('/[0-9*]/', $boat_number)) {
            array_push($errors, "Please enter a valid optimist number");
        }
    }

    if (count($errors) != 0) {
        foreach ($errors as $error) {
            echo $error . "</br>";
        }
        mysqli_close($conn); ?>
      <br>
      <a href= "/boat/createboat.php">Submit another response</a>
      <br>
      <a href = "/boat/searchboat.php">View all Boats</a>
      <?php
      exit;
    }
    $sql = "INSERT INTO regattascoring.BOAT (boat_number, boat_type, boat_handicap)
    VALUES ('$boat_number','$boat_type','$boat_handicap');";
    if (!mysqli_query($conn, $sql)) {
        echo "ERROR: Could not add data" . mysqli_error($conn) . "</br>";
    }

    $boat_id = mysqli_insert_id($conn);
    echo $_POST['boat_number'] . "Created"; ?>
    <br>
    <a href = <?php echo "viewboat.php?$boat_id"?>>Edit <?php echo $_POST['boat_number']?></a>
    <?php
    mysqli_close($conn); ?>
    <br>
    <a href="/">Return Home</a>
    <br>
    <a href = "createboat.php">Submit another response</a>
    <?php
} else {
        boatform();
    }
 ?>
