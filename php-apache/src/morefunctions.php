<?php
function activity_view($conn, $result)
{
    if (mysqli_num_rows($result) > 0) {
        //create html table
        echo "<table border = '1'>
      <tr>
      <th>Activity Name</th>
      <th>Activity Bracket</th>
      <th>Scoring Method</th>
      <th>Classes</th>
      <th>Scored Group</th>
      </tr>";

        while ($row = mysqli_fetch_assoc($result)) {

          //define activity id
            $activity_id = $row['activity_id'];

            //set class as empty
            $classes = "";

            //if activity_bracket is class
            if ($row['activity_bracket'] == "class") {

              //select all classes from bracket
                $sql = "SELECT * FROM regattascoring.BRACKET NATURAL JOIN
          regattascoring.CLASS WHERE activity_id = $activity_id;";
                $outcome = mysqli_query($conn, $sql);
                $classes = array();

                while ($class = mysqli_fetch_assoc($outcome)) {
                    array_push($classes, $class['class_name']);
                }
                $classes = join(", ", $classes);
            }
            echo "<tr>";
            echo "<td>" . $row["activity_name"] . "</td>";
            echo "<td>" . $row["activity_bracket"] . "</td>";
            echo "<td>" . $row["scoring_method"] . "</td>";
            echo "<td>" . $classes . "</td>";
            echo "<td>" . $row['scored_by'] . "</td>";
            echo "</tr>";
        }

        echo "</table>
      <br>
      <a href='/'>Return Home</a>
      <br>";
    } else {
        //if no data in the table
        echo "No data to display" . "</br>"; ?>
      <br>
      <a href="/">Return Home</a>
      <?php
      mysqli_close($conn);
        exit;
    }
}
