<?php
include_once "../connection.php";
include_once '../navbar.php';

if (isset($_POST["delete"])) {
    $unit_id_escaped = mysqli_real_escape_string($conn, $_GET['id']);
    $unit_id = $_GET['id'];

    $unit_name = $_POST['unit_name'];

    $sql = "DELETE FROM regattascoring.UNIT WHERE unit_id = '$unit_id_escaped';";

    if (!mysqli_query($conn, $sql)) {
        echo "Could not delete unit" . mysqli_error($conn) . "</br>";
        exit;
    }
    echo "$unit_name" . " deleted"; ?>
    <html>
    <body>
    <br>
    <a href="/">Return Home</a>
    <br>
    <a href="createunit.php">Submit another response</a>
    <br>
    <a href="searchunit.php">View all units</a>
    </body>
    </html>
    <?php
} elseif (isset($_POST["update"])) {
        $unit_id_escaped = mysqli_real_escape_string($conn, $_GET['id']);
        $unit_id = $_GET['id'];

        $new_unit_name_escaped = mysqli_real_escape_string($conn, $_POST['unit_name']);

        $errors = array();

        if ($new_unit_name_escaped == "") {
            array_push($errors, "Unit name must be entered");
        }

        if (preg_match('/[^A-Za-z \-]/', $new_unit_name_escaped)) {
            array_push($errors, "Please enter a valid unit name");
        }

        if (count($errors) != 0) {
            foreach ($errors as $error) {
                echo $error . "</br>";
            }
            mysqli_close($conn); ?>
            <br>
            <a href="/">Return Home</a>
            <br>
            <a href="createunit.php">Submit another response</a>
            <br>
            <a href="searchunit.php">View all Units</a>
            <br>
            <a href = <?php echo "viewunit.php?id=" . $_GET['id'] ?>>Return to update</a>
            <?php
            exit;
        };

        $unit_name = $_POST['unit_name'];

        $sql = "UPDATE regattascoring.UNIT set unit_name =
        '$new_unit_name_escaped' WHERE unit_id = '$unit_id_escaped';";

        if (!mysqli_query($conn, $sql)) {
            echo "Could not update unit" . mysqli_error($conn) . "</br>";
            exit;
        }
        echo "$unit_name updated"; ?>
        <html>
        <body>
        <br>
        <a href="/">Return Home</a>
        <br>
        <a href="createunit.php">Submit another response</a>
        <br>
        <a href="searchunit.php">View all Units</a>
        </body>
        </html>
        <?php
    } else {
        $unit_id = mysqli_real_escape_string($conn, $_GET['id']);

        $sql = "SELECT * FROM regattascoring.UNIT WHERE unit_id = '$unit_id';";
        $select = mysqli_query($conn, $sql);
        if (!$select) {
            echo "Could not select UNIT table" . "</br>" .mysqli_error($conn) . "<br/>";
            exit;
        }
        echo "selected table successfully" . "</br>";

        if (mysqli_num_rows($select) == 0) {
            echo "Nothing Selected"; ?>
          <a href="/">Return Home</a>
          <br>
          <a href="createunit.php">Submit another response</a>
          <br>
          <a href="searchunit.php">View all individuals</a>
          <?php
            exit;
        } elseif (mysqli_num_rows($select) >1) {
            echo "Too many units selected";
            exit; ?>
          <a href="/">Return Home</a>
          <br>
          <a href="createunit.php">Submit another response</a>
          <br>
          <a href="searchunit.php">View all individuals</a>
          <?php
        }

        $row = mysqli_fetch_assoc($select); ?>
  <html>
  <body>
    <form action= <?php echo "viewunit.php?id=" . $_GET['id'] ?> method ="POST">
      Unit Name:
      <input type="text" name="unit_name" value="<?php echo $row['unit_name']?>"
      placeholder="Unit Name">
      <br>
      <button type="submit" name="update">Update</button>
      <button type="submit" name="delete">Delete</button>
      <br>
  <a href="/">Return Home</a>
  <br>
  <a href="createunit.php">Submit another response</a>
  <br>
  <a href="searchunit.php">View all units</a>
  </body>
  </html>
  <?php
  mysqli_close($conn);
    }
?>
