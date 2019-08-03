<?php
// include navigation bar and connection php files
include_once '../navbar.php';
include_once "../connection.php";
include_once '../functions.php';

//if delete button is selected in form
if (isset($_POST["delete"])) {

  //GET the id number
    $certificate_id_escaped = mysqli_real_escape_string($conn, $_GET['id']);

    //Select certificate name from the table
    $sql = "SELECT certificate_name FROM regattascoring.CERTIFICATE WHERE
    certificate_id = '$certificate_id_escaped';";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    //call delete function
    deletevariable($conn, "certificate", $certificate_id_escaped, "regattascoring.CERTIFICATE", "Certificates");

    //echo certificate deleted and call close
    echo $row['certificate_name'] . " deleted";
    close($conn, $error, "certificate", "Certificates");

//if update button is selected
} elseif (isset($_POST["update"])) {
    // Get ID from URL
    $certificate_id_escaped = mysqli_real_escape_string($conn, $_GET['id']);

    //Define variables from form
    $new_certificate_name_escaped = mysqli_real_escape_string($conn, $_POST['certificate_name']);
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
        //call form with existing values?>
      <html>
        <head>
          <title>Create Certificate</title>
        </head>
          <h1>Create New Certificate</h1>
        <body>
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
        </body>
      </html>
      <?php

      //echo input sanitsation errors
      $issue = '';
        foreach ($errors as $error) {
            $issue = $issue . $error . "</br>";
        }
        close($conn, $issue, "certificate", "Certificates");
        exit;
    }

    //Update table
    $sql = "UPDATE regattascoring.CERTIFICATE set certificate_name =
        '$new_certificate_name_escaped', calculation = '$new_calculation_escaped',
        placing = '$new_placing_escaped', recipient = '$new_recipient_escaped'
        WHERE certificate_id = '$certificate_id_escaped';";

    //Check table updated, if not exit
    if (!mysqli_query($conn, $sql)) {
        close($conn, "Could not update certificate", "certificate", "Certificates");
        exit;
    }
    echo $_POST['certificate_name'] . " certificate updated";
    close($conn, $error, "certificate", "Certificates");

// if nothing has been selected
} else {
    //GET ID from URL
    $certificate_id = mysqli_real_escape_string($conn, $_GET['id']);

    //call table select function
    $row = viewselect($conn, $certificate_id, "certificate", "regattascoring.CERTIFICATE", "Certificates");

    //call form with previous values?>
    <html>
      <head>
        <title>Create Certificate</title>
      </head>
        <h1>Create New Certificate</h1>
      <body>
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
      </body>
    </html>
    <?php

    //call close
    close($conn, $error, "certificate", "Certificates");
}
?>
