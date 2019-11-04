<html>
  <head>
    <title>View Classes</title>
    <link rel="stylesheet" type="text/css" href="../stylesheets/navbarstyle.css">
    <link rel="stylesheet" type="text/css" href="../stylesheets/pagestyle.css">
  </head>

<?php
//include the navigation bar, functions and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

//Form function
function classform()
{
    ?>
    <h1>View Classes</h1>
    <div class="search_form">
      <form action= searchclass.php method="POST">
        <div class="form_input">
          <div class="three_input">
            <input type="text" name="class_name" placeholder="Search class name">
            <input type="text" name="min_age" placeholder="Search minimum age">
            <input type="text" name="max_age" placeholder="Search maximum age">
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

//Form completed and user sumbitted search
if (isset($_POST['search'])) {

  //call form
    echo "  <div class='container'>
      <div class='content'>
        <body>";
    classform();

    //define POST variables
    $class_name_escaped = mysqli_real_escape_string($conn, $_POST['class_name']);
    $min_age_escaped = mysqli_real_escape_string($conn, $_POST['min_age']);
    $max_age_escaped = mysqli_real_escape_string($conn, $_POST['max_age']);

    //validation check for empty strings
    if (!$_POST['class_name'] and !$_POST['min_age'] and !$_POST['max_age']) {
        close($conn, "Please search a valid value", "class", "Classes");
        exit;
    }

    //call search function
    $search = array();
    $sql = "SELECT * FROM regattascoring.CLASS WHERE ";
    if ($class_name_escaped) {
        array_push($search, "class_name LIKE '%$class_name_escaped%'");
    }
    if ($min_age_escaped) {
        array_push($search, "min_age LIKE '%$min_age_escaped%'");
    }
    if ($max_age_escaped) {
        array_push($search, "max_age LIKE '%$max_age_escaped%'");
    }

    $join = join(" AND ", $search);
    $sql = $sql . $join . ";";

    //check if there are rows that match
    $search = mysqli_query($conn, $sql);
    if (mysqli_num_rows($search) == 0) {
        echo "<div class='message'>No matches in table</div
        <div class='close'>
          <ul>
           <li><a href='/'>Return Home</a></li>
           <li><a href= 'viewclass.php'>Edit Classes</a></li>
           <li><a href='searchclass.php'>View all Classes</a></li>
          </ul>
        </div>";
        mysqli_close($conn);
        exit;
    }

    //create html table
    echo "<table>
    <tr>
    <th>Class Name</th>
    <th>Minimum Age</th>
    <th>Maximum Age</th>
    </tr>";

    //echo data from table
    while ($row = mysqli_fetch_assoc($search)) {
        echo "<tr>";
        echo "<td>" . $row["class_name"] . "</td>";
        echo "<td>" . $row["min_age"] . "</td>";
        echo "<td>" . $row["max_age"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    //call close
    echo "<div class='close'>
      <ul>
       <li><a href='/'>Return Home</a></li>
       <li><a href= 'viewclass.php'>Edit Classes</a></li>
       <li><a href='searchclass.php'>View all Classes</a></li>
      </ul>
    </div>";
    mysqli_close($conn);
} else {
    //call class form
    echo "  <div class='container'>
        <div class='content'>
          <body>";
    classform();

    //echo all data from table and close
    $sql = "SELECT * FROM regattascoring.CLASS;";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        //create html table
        echo "<table>
        <tr>
        <th>Class Name</th>
        <th>Minimum Age</th>
        <th>Maximum Age</th>
        </tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row["class_name"] . "</td>";
            echo "<td>" . $row["min_age"] . "</td>";
            echo "<td>" . $row["max_age"] . "</td>";
            echo "</tr>";
        }

        echo "</table>
        <div class='close'>
          <ul>
           <li><a href='/'>Return Home</a></li>
           <li><a href= 'viewclass.php'>Edit Classes</a></li>
          </ul>
        </div>";
    } else {
        //if no data in the table
        echo "<div class='message'>No data to display</div>
        <div class='close'>
          <ul>
           <li><a href='/'>Return Home</a></li>
          </ul>
        </div>" . "</br>";
        mysqli_close($conn);
        exit;
    }
}
?>
