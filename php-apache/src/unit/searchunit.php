<?php
include_once '../navbar.php';
include_once "../connection.php";

if (isset($_POST['search'])) {
    ?>
    <html>
    <body>
    <form action= searchunit.php method="POST">
    <input type="number" name="search_unit_id" placeholder="Search unit id">
    <input type="text" name="search_unit" placeholder="Search unit name">
    <button type="submit" name="search">Enter</button>
    </form>
    </body>
    </html>
    <?php

    $search_unit_name_escaped = mysqli_real_escape_string($conn, $_POST['search_unit']);
    $search_unit_id_escaped = mysqli_real_escape_string($conn, $_POST['search_unit_id']);

    if (!$_POST['search_unit'] and !$_POST['search_unit_id']) {
        echo "Please enter a valid search" . mysqli_error($conn);
        mysqli_close($conn); ?>
        <br>
        <br>
        <a href="/">Return Home</a>
        <br>
        <a href="createunit.php">Submit another response</a>
        <br>
        <a href="searchunit.php">View all UNITs</a>
        <?php
        exit;
    }
    $search = array();

    $sql = "SELECT * FROM regattascoring.UNIT WHERE ";

    if ($search_unit_name_escaped != "") {
        array_push($search, "unit_name LIKE '%$search_unit_name_escaped%'");
    }
    if ($search_unit_id_escaped != "") {
        array_push($search, "unit_id LIKE '%$search_unit_id_escaped%'");
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
        <a href="createunit.php">Submit another response</a>
        <br>
        <a href="searchunit.php">View all UNITs</a>
        <?php
        exit;
    }
    echo "<table border = '1'>
        <tr>
        <th>Unit id</th>
        <th>Unit Name</th>
        <th>View Unit</th>
        </tr>";

    while ($row = mysqli_fetch_assoc($search)) {
        echo "<tr>";
        echo "<td>" . $row['unit_id'] . "</td>";
        echo "<td>" . $row['unit_name'] . "</td>";
        echo "<td> <a href=\"viewunit.php?id=$unit_id\"> view </a> </td>";
        echo "</tr>";
    }
    echo "</table>";
    mysqli_close($conn); ?>
  <a href="/">Return Home</a>
  <br>
  <a href="createunit.php">Submit another response</a>
  <br>
  <a href="searchunit.php">View all Units</a>
  <?php
} else {
        ?>
  <html>
    <body>
        <form action= searchunit.php method="POST">
        <input type="number" name="search_unit_id" placeholder="Search unit id">
        <input type="text" name="search_unit" placeholder="Search unit name">
        <button type="submit" name="search">Enter</button>
        </form>
    </body>
  </html>
  <?php

  $sql = "SELECT * FROM regattascoring.UNIT;";
        $result = mysqli_query($conn, $sql);

        echo "<table border = '1'>
            <tr>
            <th>Unit id</th>
            <th>Unit Name</th>
            <th>View Unit</th>
            </tr>";
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $unit_id = $row['unit_id'];
                echo "<tr>";
                echo "<td>" . $row['unit_id'] . "</td>";
                echo "<td>" . $row['unit_name'] . "</td>";
                echo "<td> <a href=\"viewunit.php?id=$unit_id\"> view </a> </td>";
                echo "</tr>";
            }
        }
        echo "</table>";
        mysqli_close($conn); ?>
  <br>
  <a href="/">Return Home</a>
  <br>
  <a href="createunit.php">Submit another response</a>
  <br>
  <a href="searchunit.php">View all units</a>
  <?php
    }
