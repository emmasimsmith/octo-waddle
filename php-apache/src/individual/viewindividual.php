<?php
include_once '../navbar.php';
include_once "../connection.php";

if (isset($_POST["delete"])) {
    $individual_id_escaped = mysqli_real_escape_string($conn, $_GET['id']);
    $individual_id = $_GET['id'];

    $sql = "SELECT first_name, last_name FROM regattascoring.INDIVIDUAL;";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];

    $sql = "DELETE FROM regattascoring.INDIVIDUAL WHERE individual_id = '$individual_id_escaped';";

    if (!mysqli_query($conn, $sql)) {
        echo "Could not delete individual" . mysqli_error($conn) . "</br>";
        exit;
    }
    echo "$first_name" . " " . "$last_name" . " deleted"; ?>
    <html>
    <body>
    <br>
    <a href="/">Return Home</a>
    <br>
    <a href="searchindividual.php">View all Individuals</a>
    <br>
    <a href="createindividual.php">Submit another response</a>
    </body>
    </html>
    <?php
} elseif (isset($_POST["update"])) {
        $individual_id_escaped = mysqli_real_escape_string($conn, $_GET['id']);
        $individual_id = $_GET['id'];

        $new_first_name_escaped = mysqli_real_escape_string($conn, $_POST['first']);
        $new_last_name_escaped = mysqli_real_escape_string($conn, $_POST['last']);
        $new_dob_escaped = mysqli_real_escape_string($conn, $_POST['dob']);
        $new_comments_escaped = mysqli_real_escape_string($conn, $_POST['comments']);

        $errors = array();

        if ($new_first_name_escaped == "") {
            array_push($errors, "First name must be entered");
        }

        if (preg_match('/[^A-Za-z \-]/', $new_first_name_escaped)) {
            array_push($errors, "Please enter a valid first name");
        }

        if ($new_last_name_escaped == "") {
            array_push($errors, "Last name must be entered");
        }
        if (preg_match('/[^A-Za-z \-]/', $new_last_name_escaped)) {
            array_push($errors, "Please enter a valid last name");
        }

        if ($new_dob_escaped == "") {
            array_push($errors, "Date of birth must be entered");
        }
        if (strlen($new_dob_escaped) != 10) {
            array_push($errors, "Please enter a valid date of birth");
        }

        if (count($errors) != 0) {
            foreach ($errors as $error) {
                echo $error . "</br>";
            }
            mysqli_close($conn); ?>
            <br>
            <a href = <?php echo "viewindividual.php?id=" . $_GET['id'] ?>>Return to update</a>
            <br>
            <a href="/">Return Home</a>
            <br>
            <a href="createindividual.php">Submit another response</a>
            <br>
            <a href="searchindividual.php">View all individuals</a>
            <?php
            exit;
        };

        $first_name = $_POST['first'];
        $last_name = $_POST['last'];

        $sql = "UPDATE regattascoring.INDIVIDUAL set first_name =
        '$new_first_name_escaped', last_name = '$new_last_name_escaped',
        dob = '$new_dob_escaped', comments = '$new_comments_escaped'
        WHERE individual_id = '$individual_id_escaped';";

        if (!mysqli_query($conn, $sql)) {
            echo "Could not update individual" . mysqli_error($conn) . "</br>";
            exit;
        }
        echo "$first_name $last_name updated"; ?>
        <html>
        <body>
        <br>
        <a href="/">Return Home</a>
        <br>
        <a href="createindividual.php">Submit another response</a>
        <br>
        <a href="searchindividual.php">View all individuals</a>
        </body>
        </html>
        <?php
    } else {
        $individual_id = mysqli_real_escape_string($conn, $_GET['id']);

        $sql = "SELECT * FROM regattascoring.INDIVIDUAL WHERE individual_id = '$individual_id';";

        $select = mysqli_query($conn, $sql);
        if (!$select) {
            echo "Could not select INDIVIDUAL table" . "</br>" .mysqli_error($conn) . "<br/>";
            exit;
        }
        echo "selected table successfully" . "</br>";

        if (mysqli_num_rows($select) == 0) {
            echo "Nothing Selected"; ?>
          <a href="/">Return Home</a>
          <br>
          <a href="createindividual.php">Submit another response</a>
          <br>
          <a href="searchindividual.php">View all individuals</a>
          <?php
            exit;
        } elseif (mysqli_num_rows($select) >1) {
            echo "Too many people selected";
            exit; ?>
          <a href="/">Return Home</a>
          <br>
          <a href="createindividual.php">Submit another response</a>
          <br>
          <a href="searchindividual.php">View all individuals</a>
          <?php
        }

        $row = mysqli_fetch_assoc($select); ?>
  <html>
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
  <br>
  <a href="/">Return Home</a>
  <br>
  <a href="createindividual.php">Submit another response</a>
  <br>
  <a href="searchindividual.php">View all individuals</a>
  </body>
  </html>
  <?php
  mysqli_close($conn);
    }
?>
