<?php
include_once "connection.php";


$first_name = mysqli_real_escape_string($conn, $_GET['first']);
$last_name = mysqli_real_escape_string($conn, $_GET['last']);
$dob = mysqli_real_escape_string($conn, $_GET['dob']);

$errors = array();


if ($first_name == "") {
    array_push($errors, "First name must be entered");
}

if (preg_match('/[^A-Za-z \-]/', $first_name)) {
    array_push($errors, "Please enter a valid first name");
}

if ($last_name == "") {
    array_push($errors, "Last name must be entered");
}
if (preg_match('/[^A-Za-z \-]/', $last_name)) {
    array_push($errors, "Please enter a valid last name");
}

if ($dob == "") {
    array_push($errors, "Date of birth must be entered");
}

if (count($errors) != 0) {
    foreach ($errors as $error) {
        echo $error . "</br>";
    }
    mysqli_close($conn); ?>
    <br>
    <a href="/">Return Home</a>
    <br>
    <a href="createindividual.php">Submit another response</a>
    <?php
    exit;
}

$sql = "SELECT * FROM regattascoring.INDIVIDUAL WHERE
first_name = '$first_name' AND last_name = '$last_name' AND dob = '$dob';";
if (!$sql) {
    echo "Could not select from INDIVIDUAL table";
    exit;
}
echo "successfully selected from INDIVIDUAL table" . "</br>";

$select = mysqli_query($conn, $sql);
if (!$select) {
    echo "Could not select INDIVIDUAL table" . mysqli_error($conn) . "<br/>";
    exit;
}
echo "selected table successfully" . "</br>";

if (mysqli_num_rows($select) > 0) {
    while ($row = mysqli_fetch_assoc($select)) {
        echo "First Name: " ."<div contenteditable=true>". $row["first_name"] . "</div>" . "</br>" . "Last Name: " .
    $row["last_name"] . "</br>". "Date of Birth: " . $row["dob"] . "</br>";
    }
} else {
    echo "Nothing selected";
}
?>
<html>
<body>

<form action="viewindividual.php" method ="POST">
  <input type="text" name="first" value= "<?php $first_name ?>" placeholder="First name">
  <br>
  <input type="text" name="last" placeholder="Last name">
  <br>
  <input type="date" name="dob" placeholder="Date of Birth">
  <br>
  <input type="text" name="comments" placeholder="Comments">
  <br>
  <button type="submit" name="submit">Enter</button>
</form>

</body>
</html>
<?php
mysqli_close($conn);
 ?>
 <br>
 <a href="/">Return Home</a>
 <br>
 <a href="createindividual.php">Submit another response</a>
