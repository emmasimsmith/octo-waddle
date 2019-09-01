<?php
include_once 'navbar.php';
include_once 'connection.php';



?>
<html>
<head>
    <title>Octo Waddle</title>
</head>
<body>

<h1>This is the home page</h1>
<a href="/event/createevent.php">Create an event</a>
<br>

<?php
//select all from event table
$sql = "SELECT * FROM regattascoring.EVENT;";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<a href=/participant/selectparticipant.php?event_id=" . $row['event_id'] . ">Select " . $row['location'] . " Event</a>";
        echo "<br>";
    }
} ?>
</body>
</html>
