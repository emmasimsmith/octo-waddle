<?php
//include the navigation bar and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

// Form function
function certificateform()
{
    ?>
  <html>
    <body>
      <form action= searchcertificate.php method="POST">
      <input type="text" name="certificate_name" placeholder="Search certificate name">
      <input type="text" name="calculation" placeholder="Search calculation">
      <input type="number" name="placing" placeholder="Search number of placings">
      <input type="text" name="recipient" placeholder="Search recipients">
      <button type="submit" name="search">Enter</button>
      </form>
    </body>
  </html>
  <?php
}

//Form completed and user submitted search
if (isset($_POST['search'])) {

    //call form again
    certificateform();

    //define searched variables
    $certificate_name_escaped = mysqli_real_escape_string($conn, $_POST['certificate_name']);
    $calculation_escaped = mysqli_real_escape_string($conn, $_POST['calculation']);
    $placing_escaped = mysqli_real_escape_string($conn, $_POST['placing']);
    $recipient_escaped = mysqli_real_escape_string($conn, $_POST['recipient']);

    //validation check incase strings are empty
    if (!$_POST['certificate_name'] and !$_POST['calculation'] and
    !$_POST['placing'] and !$_POST['recipient']) {
        close($conn, "Please search a valid value", "certificate", "Certificates");
        exit;
    }
    //variable array for function
    $variables = array('certificate_name' => array('Certificate Name' => $certificate_name_escaped),
    'calculation' => array('Calculation' => $calculation_escaped),
    'placing' => array('Placing' => $placing_escaped),
    'recipient' => array('Recipient' => $recipient_escaped));

    //call search function
    search($conn, "certificate", $variables, "regattascoring.CERTIFICATE", "Certificate", "Certificates");

    //call close function
    close($conn, $error, "certificate", "Certificates");
} else {
    //call certificate form
    certificateform();

    //variables array
    $variables = array('certificate_name' => 'Certificate Name', 'calculation' =>
    'Calculation', 'placing' => 'Placing', 'recipient' => 'Recipient');

    //echo all data from table and close
    viewall($conn, "certificate", "regattascoring.CERTIFICATE", $variables, "certificate_id", "Certificates");
}
