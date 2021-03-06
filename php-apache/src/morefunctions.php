<?php
function activity_view($conn, $result)
{
    if (mysqli_num_rows($result) > 0) {
        //create html table
        echo "<table>
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

        echo "</table>";
    } else {
        //if no data in the table
        echo "
        <div class='message'>No data to display</div>
        <div class='close'>
          <ul>
            <li><a href='/'>Return Home</a></li>
          </ul>
        </div>";
        mysqli_close($conn);
        exit;
    }
}

function certificate_view($conn, $result)
{
    if (mysqli_num_rows($result) > 0) {
        //create html table
        echo "<table>
      <tr>
      <th>Certificate ID</th>
      <th>Certificate Name</th>
      <th>Placing</th>
      <th>Recipient</th>
      </tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row["certificate_id"] . "</td>";
            echo "<td>" . $row["certificate_name"] . "</td>";
            echo "<td>" . $row["placing"] . "</td>";
            echo "<td>" . $row['recipient'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        //if no data in the table
        echo "
        <div class='message'>No data to display</div>
        <div class='close'>
          <ul>
            <li><a href='/'>Return Home</a></li>
          </ul>
        </div>";
        mysqli_close($conn);
        exit;
    }
}
