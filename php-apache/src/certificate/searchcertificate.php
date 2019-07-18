<?php
//include the navigation bar and connection php files
include_once '../navbar.php';
include_once '../connection.php';

// Form function
function certificateform()
{
    ?>
  <html>
  <body>
  <form action= searchcertificate.php method="POST">
  <input type="text" name="certificate_name" placeholder="Search certificate name">
  <input type="text" name="calculation" placeholder="Search calculation">
  <input type="text" name="placing" placeholder="Search number of placings">
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
    $certificate_name = $_POST['certificate_name'];
    $calculation_escaped = mysqli_real_escape_string($conn, $_POST['calculation']);
    $calculation = $_POST['calculation'];
    $placing_escaped = mysqli_real_escape_string($conn, $_POST['placing']);
    $placing = $_POST['placing'];

    //validation check incase strings are empty
    if (!$_POST['certificate_name'] and !$_POST['calculation'] and !$_POST['placing']) {
        echo "Please search a valid value" . mysqli_error($conn);
        mysqli_close($conn); ?>
        <br>
        <br>
        <a href="/">Return Home</a>
        <br>
        <a href="createcertificate.php">Submit another response</a>
        <br>
        <a href="searchcertificate.php">View all certificates</a>
        <?php
        exit;
    }

    //create MySQL string to search for simliar values
    $search = array();
    $sql = "SELECT * FROM regattascoring.CERTIFICATE WHERE ";

    if ($certificate_name_escaped != "") {
        array_push($search, "certificate_name LIKE '%$certificate_name_escaped%'");
    }
    if ($calculation_escaped != "") {
        array_push($search, "calculation LIKE '%$calculation_escaped%'");
    }
    if ($placing_escaped != "") {
        array_push($search, "placing LIKE '%$placing_escaped%'");
    }
    $join = join(" AND ", $search);
    $sql = $sql . $join . ";";

    //check if there are any rows which matches
    $search = mysqli_query($conn, $sql);
    if (mysqli_num_rows($search) == 0) {
        echo "No matches in table " . mysqli_error($conn);
        mysqli_close($conn); ?>
        <br>
        <br>
        <a href="/">Return Home</a>
        <br>
        <a href="createcertificate.php">Submit another response</a>
        <br>
        <a href="searchcertificate.php">View all certificates</a>
        <?php
        exit;
    }

    //create  html table
    echo "<table border = '1'>
        <tr>
        <th>Certificate Name</th>
        <th>Calculation</th>
        <th>Placing</th>
        <th>View Certificate</th>
        </tr>";

    //echo data from table
    while ($row = mysqli_fetch_assoc($search)) {
        $certificate_id = $row['certificate_id'];
        echo "<tr>";
        echo "<td>" . $row['certificate_name'] . "</td>";
        echo "<td>" . $row['calculation'] . "</td>";
        echo "<td>" . $row['placing'] . "</td>";
        echo "<td> <a href=\"viewcertificate.php?id=$certificate_id\"> view </a> </td>";
        echo "</tr>";
    }
    echo "</table>";
    mysqli_close($conn); ?>
    <a href="/">Return Home</a>
    <br>
    <a href="createcertificate.php">Submit another response</a>
    <br>
    <a href="searchcertificate.php">View all certificates</a>
    <?php
} else {
        //call form
        certificateform();

        //echo all data from table
        $sql = "SELECT * FROM regattascoring.CERTIFICATE;";
        $result = mysqli_query($conn, $sql);
        echo "<table border = '1'>
    <tr>
    <th>Certificate Name</th>
    <th>Calculation</th>
    <th>Placing</th>
    <th>View Certificate</th>
    </tr>";
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $certificate_id = $row['certificate_id'];
                echo "<tr>";
                echo "<td>" . $row['certificate_name'] . "</td>";
                echo "<td>" . $row['calculation'] . "</td>";
                echo "<td>" . $row['placing'] . "</td>";
                echo "<td> <a href=\"viewcertificate.php?id=$certificate_id\"> view </a> </td>";
                echo "</tr>";
            }
        } else {

            //if no data in the table
            echo "No data to display";
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
        echo "</table>";
        mysqli_close($conn); ?>
  <br>
  <a href="/">Return Home</a>
  <br>
  <a href="createcertificate.php">Submit another response</a>
  <br>
  <a href="searchcertificate.php">View all certificates</a>
  <?php
    }
