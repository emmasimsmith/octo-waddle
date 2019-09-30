<?php
//include the navigation bar and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';
include_once '../morefunctions.php';

// Form function
function certificateform()
{
    ?>
  <html>
    <body>
      <form action= searchcertificate.php method="POST">
      <input type="text" name="certificate_name" placeholder="Search certificate name">
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
    $certificate_name = mysqli_real_escape_string($conn, $_POST['certificate_name']);
    $placing = mysqli_real_escape_string($conn, $_POST['placing']);
    $recipient = mysqli_real_escape_string($conn, $_POST['recipient']);

    //validation check incase strings are empty
    if (!$_POST['certificate_name'] and !$_POST['placing'] and !$_POST['recipient']) {
        echo "Please search a valid value
      <br>
      <a href='/'>Return Home</a>
      <br>
      <a href='searchcertificate.php'>View all Certificates</a>";
        mysqli_close($conn);
        exit;
    }

    //call search
    $search = array();
    $sql = "SELECT * FROM regattascoring.CERTIFICATE WHERE ";
    if ($certificate_name) {
        array_push($search, "certificate_name LIKE '%$certificate_name%'");
    }
    if ($placing) {
        array_push($search, "certificate_name LIKE '%$placing%'");
    }
    if ($recipient) {
        array_push($search, "certificate_name LIKE '%$recipient%'");
    }

    $join = join(" AND ", $search);
    $sql = $sql . $join . ";";

    //check if there are rows that match
    $search = mysqli_query($conn, $sql);
    if (mysqli_num_rows($search) == 0) {
        echo "No matches in table" . "</br>";
        echo "<br>
        <a href='/'>Return Home</a>
        <br>
        <a href='searchcertificate.php'>View all Certificates</a>";
        mysqli_close($conn);
        exit;
    }

    //call certificate view function
    certificate_view($conn, $search);

    //close
    echo "<br>
    <a href='/'>Return Home</a>
    <br>
    <a href='searchcertificate.php'>View all Certificates</a>";
} else {
    //call certificate form
    certificateform();

    //echo all data from table
    $sql = "SELECT * FROM regattascoring.CERTIFICATE;";
    $result = mysqli_query($conn, $sql);

    certificate_view($conn, $result);
}
