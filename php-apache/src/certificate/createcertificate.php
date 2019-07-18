<?php
include_once '../navbar.php';
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
if (isset($_POST["submit"])) {
    include_once '../connection.php';

    $certificate_name = mysqli_real_escape_string($conn, $_POST['certificate_name']);
    $calculation = mysqli_real_escape_string($conn, $_POST['calculation']);
    $placing = mysqli_real_escape_string($conn, $_POST['placing']);
    $recipient = mysqli_real_escape_string($conn, $_POST['recipient']);

    $errors = array();

    if (!$certificate_name) {
        array_push($errors, "Certificate name must be entered");
    }

    if (preg_match('/[^A-Za-z \-]/', $certificate_name)) {
        array_push($errors, "Please enter a valid certificate name");
    }

    if (count($errors) != 0) {
        foreach ($errors as $error) {
            echo $error . "</br>";
        }
        mysqli_close($conn); ?>
        <br>
        <a href="/">Return Home</a>
        <br>
        <a href="createcertificate.php">Submit another response</a>
        <br>
        <a href="searchcertificate.php">View all certificates</a>
        <?php
        exit;
    }

    $sql = "INSERT INTO regattascoring.CERTIFICATE (certificate_name, calculation,
      placing, recipient) VALUES ('$certificate_name','$calculation','$placing', '$recipient');";
    if (!mysqli_query($conn, $sql)) {
        echo "ERROR: Could not add data" . mysqli_error($conn) . "</br>";
    }
    $certificate_id = mysqli_insert_id($conn);
    echo $_POST['certificate_name'] . " Certificate Created"; ?>
    <br>
    <a href = <?php echo "viewcertificate.php?id=$certificate_id"?>>Edit
      <?php echo $_POST['certificate_name'] . " Certificate" ?></a>
    <php>
    <?php
    certificateform();
    mysqli_close($conn); ?>
    <br>
    <a href="/">Return Home</a>
    <br>
    <a href="createcertificate.php">Submit another response</a>
    <br>
    <a href="searchcertificate.php">View all certificates</a>
    <?php
} else {
        certificateform();
    };
