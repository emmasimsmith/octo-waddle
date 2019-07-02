<?php
include_once '../navbar.php';
include_once "../connection.php";

if (isset($_POST['search'])) {
    ?>
    <html>
    <body>
    <form action= searchindividual.php method="POST">
    <input type="text" name="search_first" placeholder="Search first name">
    <input type="text" name="search_last" placeholder="Search last name">
    <input type="text" name="search_dob" placeholder="Search date of birth">
    <input type="text" name="search_comments" placeholder="Search comments">
    <button type="submit" name="search">Enter</button>
    </form>
    </body>
    </html>
    <?php

    $search_first_name_escaped = mysqli_real_escape_string($conn, $_POST['search_first']);
    $search_first_name = $_POST['search_first'];
    $search_last_name_escaped = mysqli_real_escape_string($conn, $_POST['search_last']);
    $search_last_name = $_POST['search_last'];
    $search_dob_escaped = mysqli_real_escape_string($conn, $_POST['search_dob']);
    $search_dob = $_POST['search_dob'];
    $search_comments_escaped = mysqli_real_escape_string($conn, $_POST['search_comments']);
    $search_comments = $_POST['search_comments'];

    $search = array();

    $sql = "SELECT * FROM regattascoring.INDIVIDUAL WHERE ";

    if ($search_first_name_escaped != "") {
        array_push($search, "first_name LIKE '%$search_first_name_escaped%'");
    }
    if ($search_last_name_escaped != "") {
        array_push($search, "last_name LIKE '%$search_last_name_escaped%'");
    }
    if ($search_dob_escaped != "") {
        array_push($search, "dob LIKE '%$search_dob_escaped%'");
    }
    if ($search_comments != "") {
        array_push($search, "comments LIKE '%$search_comments_escaped'");
    }
    $join = join(" AND ", $search);
    $sql = $sql . $join . ";";

    $search = mysqli_query($conn, $sql);
    if (mysqli_num_rows($search) == 0) {
        echo "No matches in table " . mysqli_error($conn);
        mysqli_close($conn); ?>
        <br>
        <br>
        <a href="/">Return Home</a>
        <br>
        <a href="createindividual.php">Submit another response</a>
        <br>
        <a href="searchindividual.php">View all individuals</a>
        <?php
        exit;
    }
    echo "<table border = '1'>
        <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Date of Birth</th>
        <th>Comments</th>
        <th>View Individual</th>
        </tr>";

    while ($row = mysqli_fetch_assoc($search)) {
        echo "<tr>";
        echo "<td>" . $row['first_name'] . "</td>";
        echo "<td>" . $row['last_name'] . "</td>";
        echo "<td>" . $row['dob'] . "</td>";
        echo "<td>" . $row['comments'] . "</td>";
        echo "<td> <a href=\"viewindividual.php?id=$individual_id\"> view </a> </td>";
        echo "</tr>";
    }
    echo "</table>";
    mysqli_close($conn); ?>
  <a href="/">Return Home</a>
  <br>
  <a href="createindividual.php">Submit another response</a>
  <br>
  <a href="searchindividual.php">View all individuals</a>
  <?php
} else {
        ?>
  <html>
    <body>
      <form action= searchindividual.php method="POST">
      <input type="text" name="search_first" placeholder="Search first name">
      <input type="text" name="search_last" placeholder="Search last name">
      <input type="text" name="search_dob" placeholder="Search date of birth">
      <input type="text" name="search_comments" placeholder="Search comments">
      <button type="submit" name="search">Enter</button>
      </form>
    </body>
  </html>
  <?php

  $sql = "SELECT * FROM regattascoring.INDIVIDUAL;";
        $result = mysqli_query($conn, $sql);

        echo "<table border = '1'>
  <tr>
  <th>First Name</th>
  <th>Last Name</th>
  <th>Date of Birth</th>
  <th>Comments</th>
  <th>View Individual</th>
  </tr>";

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $individual_id = $row['individual_id'];
                echo "<tr>";
                echo "<td>" . $row['first_name'] . "</td>";
                echo "<td>" . $row['last_name'] . "</td>";
                echo "<td>" . $row['dob'] . "</td>";
                echo "<td>" . $row['comments'] . "</td>";
                echo "<td> <a href=\"viewindividual.php?id=$individual_id\"> view </a> </td>";
                echo "</tr>";
            }
        }
        echo "</table>";
        mysqli_close($conn); ?>
  <br>
  <a href="/">Return Home</a>
  <br>
  <a href="createindividual.php">Submit another response</a>
  <br>
  <a href="searchindividual.php">View all individuals</a>
  <?php
    }
