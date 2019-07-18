<?php
// include navigation bar and connection php files
include_once '../navbar.php';
include_once "../connection.php";

//if delete button is selected in form
if (isset($_POST["delete"])) {

  //GET the id number
    $certificate_id_escaped = mysqli_real_escape_string($conn, $_GET['id']);
    $certificate_id = $_GET['id'];

    //Select certificate name from the table
    $sql = "SELECT certificate_name FROM regattascoring.CERTIFICATE WHERE
    certificate_id = '$certificate_id_escaped';";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $certificate_name = $row['certificate_name'];

    //Delete certificate from table
    $sql = "DELETE FROM regattascoring.CERTIFICATE WHERE certificate_id = '$certificate_id_escaped';";

    //Check if certificate has been deleted
    if (!mysqli_query($conn, $sql)) {
        echo "Could not delete certificate" . mysqli_error($conn) . "</br>";
        exit;
    }
    echo "$certificate_name" . " deleted"; ?>
    <html>
    <body>
    <br>
    <a href="/">Return Home</a>
    <br>
    <a href="searchcertificate.php">View all Certificates</a>
    <br>
    <a href="createcertificate.php">Submit another response</a>
    </body>
    </html>
    <?php

//if update button is selected
} elseif (isset($_POST["update"])) {
    // Get ID from URL
    $certificate_id_escaped = mysqli_real_escape_string($conn, $_GET['id']);
    $certificate_id = $_GET['id'];

    //Define variables from form
    $new_certificate_name_escaped = mysqli_real_escape_string($conn, $_POST['certificate_name']);
    $certificate_name = $_POST['certificate_name'];
    $new_calculation_escaped = mysqli_real_escape_string($conn, $_POST['calculation']);
    $new_placing_escaped = mysqli_real_escape_string($conn, $_POST['placing']);
    $new_recipient_escaped = mysqli_real_escape_string($conn, $_POST['recipient']);

    //Create array for errors
    $errors = array();

    if ($new_certificate_name_escaped == "") {
        array_push($errors, "Certificate name must be entered");
    }

    if (preg_match('/[^A-Za-z \-]/', $new_certificate_name_escaped)) {
        array_push($errors, "Please enter a valid certificate name");
    }

    //If there are errors, print and exit
    if (count($errors) != 0) {
        foreach ($errors as $error) {
            echo $error . "</br>";
        }
        mysqli_close($conn); ?>
            <br>
            <a href = <?php echo "viewcertificate.php?id=" . $_GET['id'] ?>>Return to update</a>
            <br>
            <a href="/">Return Home</a>
            <br>
            <a href="createcertificate.php">Submit another response</a>
            <br>
            <a href="searchcertificate.php">View all certificates</a>
            <?php
            exit;
    };

    //Update table
    $sql = "UPDATE regattascoring.CERTIFICATE set certificate_name =
        '$new_certificate_name_escaped', calculation = '$new_calculation_escaped',
        placing = '$new_placing_escaped', recipient = '$new_recipient_escaped'
        WHERE certificate_id = '$certificate_id_escaped';";

    //Check table updated, if not exit
    if (!mysqli_query($conn, $sql)) {
        echo "Could not update certificate" . mysqli_error($conn) . "</br>";
        exit;
    }
    echo "$certificate_name certificate updated"; ?>
        <html>
        <body>
        <br>
        <a href="/">Return Home</a>
        <br>
        <a href="createcertificate.php">Submit another response</a>
        <br>
        <a href="searchcertificate.php">View all certificates</a>
        </body>
        </html>
        <?php
// if nothing has been selected
} else {
    //GET ID from URL
    $certificate_id = mysqli_real_escape_string($conn, $_GET['id']);

    //Select certificate matching GET ID
    $sql = "SELECT * FROM regattascoring.CERTIFICATE WHERE certificate_id = '$certificate_id';";
    $select = mysqli_query($conn, $sql);
    if (!$select) {
        echo "Could not select CERTIFICATE table" . "</br>" .mysqli_error($conn) . "<br/>";
        exit;
    }
    echo "selected table successfully" . "</br>";

    //Check only one certificate selected
    if (mysqli_num_rows($select) == 0) {
        echo "Nothing Selected"; ?>
          <a href="/">Return Home</a>
          <br>
          <a href="createcertificate.php">Submit another response</a>
          <br>
          <a href="searchcertificate.php">View all certificates</a>
          <?php
            exit;
    } elseif (mysqli_num_rows($select) >1) {
        echo "Too many certificates selected";
        exit; ?>
          <a href="/">Return Home</a>
          <br>
          <a href="createcertificate.php">Submit another response</a>
          <br>
          <a href="searchcertificate.php">View all certificates</a>
          <?php
    }

    //Echo form with previous values set as default
    $row = mysqli_fetch_assoc($select); ?>
      <html>
      <form action="<?php echo "viewcertificate.php?id=" . $_GET['id'] ?>" method ="POST">

        Certificate Name:
        <input type="text" name="certificate_name" value="<?php echo $row['certificate_name']?>" placeholder="Certificate name">
        <br>

        Calculation:
        <select name="calculation" placeholder="Calculation Method">
          <option value="selected" <?php if ($row['calculation'] == "selected") {
        echo "selected";
    } ?>  >selected</option>
          <option value="scoring" <?php if ($row['calculation'] == "scoring") {
        echo "selected";
    } ?> >Scoring</option>
  </select>
        <br>

        Placings:
        <select name="placing" placeholder="Select Placings">
          <option value="3" <?php if ($row['placing'] == "3") {
        echo "selected";
    } ?>>Three Placings</option>
          <option value="1"<?php if ($row['placing'] == "1") {
        echo "selected";
    } ?>>One Placing</option>
        </select>
        <br>

        Recipient:
        <select name="recipient">
          <option value="individual" <?php if ($row['recipient'] == "individual") {
        echo "selected";
    } ?>>Individual</option>
          <option value="Unit" <?php if ($row['recipient'] == "unit") {
        echo "selected";
    } ?>>Unit</option>
        </select>
        <br>

        <button type="submit" name="update">Update</button>
        <button type="submit" name="delete">Delete</button>
      </form>

  <br>
  <a href="/">Return Home</a>
  <br>
  <a href="createcertificate.php">Submit another response</a>
  <br>
  <a href="searchcertificate.php">View all certificates</a>
  </html>
  <?php

  //Close connection
  mysqli_close($conn);
}
?>
