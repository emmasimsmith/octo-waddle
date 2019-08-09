<?php
//function to delete
function deletevariable($conn, $name, $name_id, $table_name, $plural_name)
{
    //Delete
    $sql = "DELETE FROM " . $table_name . " WHERE " . $name . "_id = '$name_id';";

    //check if individual was deleted
    if (!mysqli_query($conn, $sql)) {
        echo "Could not delete ". $name . mysqli_error($conn) . "</br>"; ?>
          <br>
          <a href="/">Return Home</a>
          <br>
          <a href= <?php echo "create" . $name . ".php" ?>>Submit another response</a>
          <br>
          <a href=<?php echo "search" . $name . ".php" ?>>View all <?php echo $plural_name ?></a>
          <?php
          mysqli_close($conn);
        exit;
    }
}

function selectall($conn, $select_name, $select_table_name, $select_cap_name, $name, $plural_name)
{
    //select all
    $sql = "SELECT * FROM " . $select_table_name . ";";
    $result = mysqli_query($conn, $sql);
    if (!mysqli_num_rows($result)) {
        echo "Please create a " . $select_cap_name . " first" . mysqli_error($conn); ?>
      <br>
      <a href="/">Return Home</a>
      <br>
      <a href= <?php echo "create" . $name . ".php" ?>>Submit another response</a>
      <br>
      <a href=<?php echo "search" . $name . ".php" ?>>View all <?php echo $plural_name?></a>
      <?php
      mysqli_close($conn);
        exit;
    }
    //$row = mysqli_fetch_assoc($result);

    return $result;
}

//function for selecting entity matching ID
function viewselect($conn, $name_id, $name, $table_name, $plural_name)
{
    //Select individual matching GET ID
    $sql = "SELECT * FROM " . $table_name . " WHERE " . $name. "_id = '". $name_id ."';";
    $select = mysqli_query($conn, $sql);

    //check if can select
    if (!$select) {
        echo "Could not select " . $table_name . " table"; ?>
        <br>
        <a href="/">Return Home</a>
        <br>
        <a href= <?php echo "create" . $name . ".php" ?>>Submit another response</a>
        <br>
        <a href=<?php echo "search" . $name . ".php" ?>>View all <?php echo $plural_name?></a>
        <?php
        mysqli_close($conn);
        exit;
    }

    //Check only one individual selected
    if (mysqli_num_rows($select) == 0) {
        echo "Nothing Selected"; ?>
        <br>
        <a href="/">Return Home</a>
        <br>
        <a href= <?php echo "create" . $name . ".php" ?>>Submit another response</a>
        <br>
        <a href=<?php echo "search" . $name . ".php" ?>>View all <?php echo $plural_name?></a>
        <?php
        mysqli_close($conn);
        exit;
    } elseif (mysqli_num_rows($select) >1) {
        echo "Too many selected"; ?>
        <br>
        <a href="/">Return Home</a>
        <br>
        <a href= <?php echo "create" . $name . ".php" ?>>Submit another response</a>
        <br>
        <a href=<?php echo "search" . $name . ".php" ?>>View all <?php echo $plural_name?></a>
        <?php
        mysqli_close($conn);
        exit;
    }

    //Echo form with previous values
    $row = mysqli_fetch_assoc($select);

    //return $row
    return $row;
}




//Function for closing
function close($conn, $error, $name, $plural_name)
{
    if ($error) {
        echo $error . "</br>";
    } ?>
  <br>
  <a href="/">Return Home</a>
  <br>
  <a href= <?php echo "create" . $name . ".php" ?>>Submit another response</a>
  <br>
  <a href=<?php echo "search" . $name . ".php" ?>>View all <?php echo $plural_name ?></a>
  <?php
  mysqli_close($conn);
}

function small_close($conn, $error)
{
    if ($error) {
        echo $error . "</br>";
    } ?>
  <br>
  <a href="/">Return Home</a>
  <br>
  <?php
  mysqli_close($conn);
}

//Search function
function search($conn, $name, $variables, $table_name, $capitalised_name, $plural_name)
{
    $search = array();
    $sql = "SELECT * FROM ". $table_name ." WHERE ";
    foreach ($variables as $column => $array) {
        foreach ($array as $column_name => $variable) {
            if ($variable != "") {
                array_push($search, $column . " LIKE '%$variable%'");
            }
        }
    }
    $join = join(" AND ", $search);
    $sql = $sql . $join . ";";
    
    //check if there are rows that match
    $search = mysqli_query($conn, $sql);
    if (mysqli_num_rows($search) == 0) {
        echo "No matches in table" . "</br>"; ?>
      <br>
      <a href="/">Return Home</a>
      <br>
      <a href= <?php echo "create" . $name . ".php" ?>>Submit another response</a>
      <br>
      <a href=<?php echo "search" . $name . ".php" ?>>View all <?php echo $plural_name ?></a>
      <?php
      mysqli_close($conn);
        exit;
    }

    //create html table
    echo "<table border = '1'>
    <tr>";
    foreach ($variables as $column => $array) {
        foreach ($array as $column_name => $variable) {
            echo "<th>$column_name</th>";
        }
    }
    echo "<th>View " . $name . "</th>
    </tr>";

    //echo data from table
    while ($row = mysqli_fetch_assoc($search)) {
        echo "<tr>";
        foreach ($variables as $column => $array) {
            foreach ($array as $column_name => $variable) {
                echo "<td>" . $row["$column"] . "</td>";
            }
        }
        echo "<td> <a href=\"view" . $name . ".php?id=$name_id\"> view </a> </td>";
        echo "</tr>";
    }
    echo "</table>";
}




//function view all
function viewall($conn, $name, $table_name, $variables, $name_id, $plural_name)
{
    //echo all data from table
    $sql = "SELECT * FROM ".$table_name.";";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        //create html table
        echo "<table border = '1'>
        <tr>";
        foreach ($variables as $column => $column_name) {
            echo "<th>$column_name</th>";
        }
        echo "<th>View " . $name . "</th>
        </tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            foreach ($variables as $column => $column_name) {
                echo "<td>" . $row["$column"] . "</td>";
            }
            echo "<td> <a href=\"view" . $name . ".php?id=" . $row["$name_id"] . "\"> view </a> </td>";
            echo "</tr>";
        }
    } else {
        //if no data in the table
        echo "No data to display" . "</br>"; ?>
        <br>
        <a href="/">Return Home</a>
        <br>
        <a href= <?php echo "create" . $name . ".php" ?>>Submit another response</a>
        <br>
        <a href=<?php echo "search" . $name . ".php" ?>>View all <?php echo $plural_name ?></a>
        <?php
        mysqli_close($conn);
        exit;
    }
    echo "</table>"; ?>
    <br>
    <a href="/">Return Home</a>
    <br>
    <a href= <?php echo "create" . $name . ".php" ?>>Submit another response</a>
    <br>
    <a href=<?php echo "search" . $name . ".php" ?>>View all <?php echo $plural_name ?></a>
    <?php
    mysqli_close($conn);
}
