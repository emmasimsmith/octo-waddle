<?php
//function to delete
function deletevariable($conn, $name, $name_id, $table_name, $plural_name)
{
    //Delete
    $sql = "DELETE FROM " . $table_name . " WHERE " . $name . "_id = '$name_id';";

    //check if individual was deleted
    if (!mysqli_query($conn, $sql)) {
        echo " <div class='error'>Could not delete ". $name . mysqli_error($conn) . "</div>"; ?>
          <div class="close">
            <ul>
              <li><a href="/">Return Home</a></li>
              <li><a href= <?php echo "create" . $name . ".php" ?>>Submit another response</a></li>
              <li><a href=<?php echo "search" . $name . ".php" ?>>View all <?php echo $plural_name ?></a></li>
            </ul>
          </div>
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
        echo "<div class='message'>Please create a " . $select_cap_name . " first" . mysqli_error($conn). "</div>"; ?>
        <div class="close">
          <ul>
            <li><a href="/">Return Home</a></li>
            <li><a href= <?php echo "create" . $name . ".php" ?>>Submit another response</a></li>
            <li><a href=<?php echo "search" . $name . ".php" ?>>View all <?php echo $plural_name ?></a></li>
          </ul>
        </div>
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
        echo "<h1>Edit $plural_name</h1>
        <div class='message'>Could not select " . $table_name . " table</div>"; ?>
        <div class='close'>
          <ul>
            <li><a href="/">Return Home</a></li>
            <li><a href= <?php echo "create" . $name . ".php" ?>>Submit another response</a></li>
            <li><a href=<?php echo "search" . $name . ".php" ?>>View all <?php echo $plural_name?></a></li>
          </ul>
        </div>
      </div>
    </div>
        <?php
        mysqli_close($conn);
        exit;
    }

    //Check only one individual selected
    if (mysqli_num_rows($select) == 0) {
        echo "<h1>Edit $plural_name</h1>
        <div class='message'>Nothing Selected</div>"; ?>
        <div class='close'>
          <ul>
            <li><a href="/">Return Home</a></li>
            <li><a href= <?php echo "create" . $name . ".php" ?>>Submit another response</a></li>
            <li><a href=<?php echo "search" . $name . ".php" ?>>View all <?php echo $plural_name?></a></li>
          </ul>
        </div>
      </div>
    </div>
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
        ?><div class="error"><?php echo $error ?></div><?php
    } ?>
      <div class="close">
        <ul>
          <li><a href="/">Return Home</a></li>
          <li><a href= <?php echo "create" . $name . ".php" ?>>Submit another response</a></li>
          <li><a href=<?php echo "search" . $name . ".php" ?>>View all <?php echo $plural_name ?></a></li>
        </ul>
      </div>
    </div>
  </div>
  <?php
  mysqli_close($conn);
}

function small_close($conn, $error)
{
    if ($error) {
        echo "<div class='error'>$error</div>";
    } ?>
    <div class="close">
      <ul>
        <li><a href="/">Return Home</a></li>
      </ul>
    </div>
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
        ?><div class='message'>No matches in table</div>
      <div class="close">
        <ul>
          <li><a href="/">Return Home</a></li>
          <li><a href= <?php echo "create" . $name . ".php" ?>>Submit another response</a></li>
          <li><a href=<?php echo "search" . $name . ".php" ?>>View all <?php echo $plural_name ?></a></li>
        </ul>
      </div>
      <?php
      mysqli_close($conn);
        exit;
    }

    //create html table
    echo "<table>
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
        echo "<td> <a href=view" . $name . ".php?id=".$name."_id> view </a></td>";
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
        echo "<table>
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
        echo "</table>";
    } else {
        //if no data in the table?>
        <div class="message">
          No data to display
        </div>
        <div class="close">
          <ul>
            <li><a href="/">Return Home</a></li>
            <li><a href= <?php echo "create" . $name . ".php" ?>>Submit another response</a></li>
            <li><a href=<?php echo "search" . $name . ".php" ?>>View all <?php echo $plural_name ?></a></li>
          </ul>
        </div>
      </div>
    </div>
        <?php
        mysqli_close($conn);
        exit;
    } ?>
    <div class="close">
      <ul>
        <li><a href="/">Return Home</a></li>
        <li><a href= <?php echo "create" . $name . ".php" ?>>Submit another response</a></li>
        <li><a href=<?php echo "search" . $name . ".php" ?>>View all <?php echo $plural_name ?></a></li>
      </ul>
    </div>
  </div>
</div>
    <?php
    mysqli_close($conn);
}

//function for closing
function participant_close($conn, $error, $event_id)
{
    if ($error) {
        echo "<div class='error'>$error</div>";
    } ?>
    <div class="close">
      <ul>
        <li><a href="/">Return Home</a></li>
        <li><a href=searchparticipant.php?id=<?php echo $event_id ?>>View participants</a></li>
      </ul>
    </div>
  <?php;
    mysqli_close($conn);
}

function home_close($conn)
{
    ?>
    <div class="close">
      <ul>
        <li><a href="/">Return Home</a></li>
      </ul>
    </div>
    <?php
    mysqli_close($conn);
    exit;
}

function award_tied($conn)
{
    $sql = "SELECT score, COUNT(score) AS total FROM regattascoring.PLACING
          GROUP BY score HAVING (COUNT(score)> 1);";
    $multiple = mysqli_query($conn, $sql);

    //foreach tied value
    while ($tie = mysqli_fetch_assoc($multiple)) {
        //select max of calculated score
        $sql = "SELECT MIN(place) as min FROM regattascoring.PLACING WHERE score =
    " . $tie['score'].";";
        $minplacing = mysqli_query($conn, $sql);
        $score = mysqli_fetch_assoc($minplacing);
        $tiedplacing = $score['min'];

        //select all tied with same score
        $sql = "SELECT * FROM regattascoring.PLACING WHERE score =" . $tie['score']. ";";
        $answer = mysqli_query($conn, $sql);

        while ($tied = mysqli_fetch_assoc($answer)) {
            //update placing score table
            $sql = "UPDATE regattascoring.PLACING set place = $tiedplacing WHERE placing_id=" . $tied['placing_id'] . ";";
            $update = mysqli_query($conn, $sql);
            echo mysqli_error($conn);
        }
    }
}
