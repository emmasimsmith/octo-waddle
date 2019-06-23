<?php
include_once "connection.php";

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
mysqli_close($conn);
