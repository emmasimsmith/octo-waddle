<?php
//include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

//Form function
function certificateform()
{
    ?>
  <html>
  <head>
      <title>Create Certificate</title>
  </head>
  <h1>Create New Certificate</h1>
  <body>

  <form action="createcertificate.php" method ="POST">
    Certificate Name:
    <input type="text" name="certificate_name" placeholder="Certificate name">
    <br>
    Calculation:
    <select name="calculation" placeholder="Calculation Method">
      <option value="selected">Selected</option>
      <option value="scoring">Scoring</option>
    </select>
    <br>
    Placings:
    <select name="placing" placeholder="Select Placings">
      <option value="3">Three Placings</option>
      <option value="1">One Placing</option>
    </select>
    <br>
    Recipient:
    <select name="recipient">
      <option value="individual">Individual</option>
      <option value="Unit">Unit</option>
    </select>
    <br>
    <button type="submit" name="submit">Enter</button>
  </form>
  </body>
  </html>
  <?php
}

//if search is submitted
if (isset($_POST["submit"])) {

    //define POST variables
    $certificate_name = mysqli_real_escape_string($conn, $_POST['certificate_name']);
    $calculation = mysqli_real_escape_string($conn, $_POST['calculation']);
    $placing = mysqli_real_escape_string($conn, $_POST['placing']);
    $recipient = mysqli_real_escape_string($conn, $_POST['recipient']);

    //array for input sanitsation errors
    $errors = array();

    //input sanitsation
    if (!$certificate_name) {
        array_push($errors, "Certificate name must be entered");
    }
    if (preg_match('/[^A-Za-z \-]/', $certificate_name)) {
        array_push($errors, "Please enter a valid certificate name");
    }

    //echo errors then exit
    if (count($errors) != 0) {
        //call form with existing values?>
        <html>
          <form action= "createcertificate.php" method ="POST">

            Certificate Name:
            <input type="text" name="certificate_name" value="<?php echo $_POST['certificate_name']?>" placeholder="Certificate name">
            <br>

            Calculation:
            <select name="calculation" placeholder="Calculation Method">
              <option value="selected" <?php if ($_POST['calculation'] == "selected") {
            echo "selected";
        } ?>  >selected</option>
              <option value="scoring" <?php if ($_POST['calculation'] == "scoring") {
            echo "selected";
        } ?> >Scoring</option>
            </select>
            <br>

            Placings:
            <select name="placing" placeholder="Select Placings">
              <option value="3" <?php if ($_POST['placing'] == "3") {
            echo "selected";
        } ?>>Three Placings</option>
              <option value="1"<?php if ($_POST['placing'] == "1") {
            echo "selected";
        } ?>>One Placing</option>
            </select>
            <br>

            Recipient:
            <select name="recipient">
              <option value="individual" <?php if ($_POST['recipient'] == "individual") {
            echo "selected";
        } ?>>Individual</option>
              <option value="Unit" <?php if ($_POST['recipient'] == "Unit") {
            echo "selected";
        } ?>>Unit</option>
            </select>
            <br>
            <button type="submit" name="submit">Enter</button>
          </form>
        </html>
        <?php
        //echo errors from the input sanitsation
        $issue = '';
        foreach ($errors as $error) {
            $issue = $issue . $error . "</br>";
        }
        close($conn, $issue, "certificate", "Certificates");
        exit;
    }

    //Insert variables into certificate table, if false echo error and exit
    $sql = "INSERT INTO regattascoring.CERTIFICATE (certificate_name, calculation,
      placing, recipient) VALUES ('$certificate_name','$calculation','$placing', '$recipient');";
    if (!mysqli_query($conn, $sql)) {
        close($conn, "Could not add data", "certificate", "Certificates");
        exit;
    }

    //echo certificate created
    echo $_POST['certificate_name'] . " Certificate Created";
    $certificate_id = mysqli_insert_id($conn); ?>
    <br>
    <a href = <?php echo "viewcertificate.php?id=$certificate_id"?>>Edit
      <?php echo $_POST['certificate_name'] . " Certificate" ?></a>
    <?php

    //call certificate form
    certificateform();

    //call close
    close($conn, $error, "certificate", "Certificates");
} else {
    //call certificate form
    certificateform();

    //call close
    close($conn, $error, "certificate", "Certificates");
}
