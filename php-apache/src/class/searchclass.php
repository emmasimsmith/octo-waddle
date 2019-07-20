<?php
//include the navigation bar and connection php files
include_once '../navbar.php';
include_once '../connection.php';

//function to echo errors and links
function closeclass($error)
{
    if ($error) {
        echo $error . "</br>";
    } ?>
    <br>
    <a href="/">Return Home</a>
    <br>
    <a href="createclass.php">Submit another response</a>
    <br>
    <a href="searchclass.php">View all classes</a>
    <?php
}

//Form function
function classform()
{
    ?>
<html>
<body>
<form action= searchclass.php method="POST">
<input type="text" name="class_name" placeholder="Search class name">
<input type="text" name="min_age" placeholder="Search minimum age">
<input type="text" name="max_age" placeholder="Search maximum age">
<button type="submit" name="search">Enter</button>
</form>
</body>
</html>
<?php
}

//Form completed and user sumbitted search
if (isset($_POST['search'])) {

  //call form
    classform($error);

    //define POST variables
    $class_name_escaped = mysqli_real_escape_string($conn, $_POST['class_name']);
    $min_age_escaped = mysqli_real_escape_string($conn, $_POST['min_age']);
    $max_age_escaped = mysqli_real_escape_string($conn, $_POST['max_age']);

    //validation check for empty strings
    if (!$_POST['class_name'] and !$_POST['min_age'] and !$_POST['max_age']) {
        $error = "Please search a valid value";
        closeclass($error);
        mysqli_close($conn);
        exit;
    }

    //Create MySQL string to search for similar values
    $search = array();
    $sql = "SELECT * FROM regattascoring.CLASS WHERE ";

    if ($class_name_escaped != "") {
        array_push($search, "class_name LIKE '%$class_name_escaped%'");
    }
    if ($min_age_escaped != "") {
        array_push($search, "min_age LIKE '%$min_age_escaped%'");
    }
    if ($max_age_escaped != "") {
        array_push($search, "max_age LIKE '%$max_age_escaped%'");
    }
    $join = join(" AND ", $search);
    $sql = $sql . $join . ";";

    //check if there are any rows which matches
    $search = mysqli_query($conn, $sql);
    if (mysqli_num_rows($search) == 0) {
        $error = "No matches in table";
        closeclass($error);
        mysqli_close($conn);
        exit;
    }

    //Create html table
    echo "<table border = '1'>
    <tr>
    <th>Class Name</th>
    <th>Minimum Age</th>
    <th>Maximum Age</th>
    </tr>";

    //Echo data from table
    while ($row = mysqli_fetch_assoc($search)) {
        $class_id = $row['class_id'];
        echo "<tr>";
        echo "<td>" . $row['class_name'] . "</td>";
        echo "<td>" . $row['min_age'] . "</td>";
        echo "<td>" . $row['max_age'] . "</td>";
        echo "<td> <a href=\"viewclass.php?id=$class_id\"> view </a></td>";
        echo "</tr>";
    }
    echo "</table>";
    closeclass($error);
    mysqli_close($conn);
} else {
    //call format
    classform();

    //echo all data from table
    $sql = "SELECT * FROM regattascoring.CLASS;";
    $result = mysqli_query($conn, $sql);
    echo "<table border = '1'>
  <tr>
  <th>Class Name</th>
  <th>Minimum Age</th>
  <th>Maximum Age</th>
  </tr>";

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $class_id = $row['class_id'];
            echo "<tr>";
            echo "<td>" . $row['class_name'] . "</td>";
            echo "<td>" . $row['min_age'] . "</td>";
            echo "<td>" . $row['max_age'] . "</td>";
            echo "<td> <a href=\"viewclass.php?id=$class_id\"> view </a></td>";
            echo "</tr>";
        }
    } else {
        //if no data in the mysql_list_tables
        $error = "No data to display";
        closeclass($error);
        mysqli_close($conn);
        exit;
    }
    echo "</table>";
    closeclass($error);
    mysqli_close($conn);
}
 ?>
