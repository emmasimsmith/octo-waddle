<?php
if (isset($_POST["submit"])) {
    include_once 'connection.php';

    $first_name = mysqli_real_escape_string($conn, $_POST['first']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $comments = mysqli_real_escape_string($conn, $_POST['comments']);

    // TODO: perform input sanitisation

    $sql = "INSERT INTO regattascoring.INDIVIDUAL (first_name, last_name, dob, comments) VALUES ('$first_name','$last_name','$dob','$comments');";
    if (!mysqli_query($conn, $sql)) {
        echo "Records added successfully.";
    } else {
        echo "ERROR: Could not execute $sql." . mysqli_error($conn);
    };
    mysqli_close($conn); ?>
    <a href="/">Return Home</a>
    <a href="createindividual.php">Submit another response</a>
    <?php
} else {
        ?>
    <html>
    <head>
        <title></title>
    </head>
    <body>

    <form action="createindividual.php" method ="POST">
      <input type="text" name="first" placeholder="First name">
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
    };
