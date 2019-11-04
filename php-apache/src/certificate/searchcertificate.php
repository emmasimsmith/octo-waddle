<html>
  <head>
    <title>View Certificates</title>
    <link rel="stylesheet" type="text/css" href="../stylesheets/navbarstyle.css">
    <link rel="stylesheet" type="text/css" href="../stylesheets/pagestyle.css">
  </head>

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
    <h1>View Certificates</h1>
    <div class="search_form">
      <form action= searchcertificate.php method="POST">
        <div class="form_input">
          <div class="three_input">
            <input type="text" name="certificate_name" placeholder="Certificate Name">
            <input type="number" name="placing" placeholder="Number of Placings">
            <input type="text" name="recipient" placeholder="Recipients">
          </div>
        </div>
        <div class="search_button">
          <button type="submit" name="search">Enter</button>
        </div>
      </form>
    </div>
  </body>
  <?php
}

//Form completed and user submitted search
if (isset($_POST['search'])) {

    //call form again
    echo "  <div class='container'>
        <div class='content'>
          <body>";
    certificateform();

    //define searched variables
    $certificate_name = mysqli_real_escape_string($conn, $_POST['certificate_name']);
    $placing = mysqli_real_escape_string($conn, $_POST['placing']);
    $recipient = mysqli_real_escape_string($conn, $_POST['recipient']);

    //validation check incase strings are empty
    if (!$_POST['certificate_name'] and !$_POST['placing'] and !$_POST['recipient']) {
        echo "
        <div class='error'>Please search a valid value</div>
        <div class='close'>
          <ul>
            <li><a href='/'>Return Home</a></li>
            <li><a href='searchcertificate.php'>View all Certificates</a></li>
          </ul>
        </div>";
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
        echo "
      <div class='message'>No matches in table</div>
      <div class='close'>
        <ul>
          <li><a href='/'>Return Home</a></li>
          <li><a href='searchcertificate.php'>View all Certificates</a></li>
        </ul>
      </div>";
        mysqli_close($conn);
        exit;
    }

    //call certificate view function
    certificate_view($conn, $search);

    //close
    echo "
    <div class='close'>
      <ul>
        <li><a href='/'>Return Home</a></li>
        <li><a href='searchcertificate.php'>View all Certificates</a></li>
      </ul>
    </div>";
} else {
    //call certificate form
    echo "  <div class='container'>
        <div class='content'>
          <body>";
    certificateform();

    //echo all data from table
    $sql = "SELECT * FROM regattascoring.CERTIFICATE;";
    $result = mysqli_query($conn, $sql);

    certificate_view($conn, $result);
    echo"
    <div class='close'>
      <ul>
        <li><a href='/'>Return Home</a></li>
      </ul>
    </div>";
}
