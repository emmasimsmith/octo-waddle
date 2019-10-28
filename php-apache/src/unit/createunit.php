<html>
  <head>
    <title>Create Individual</title>
    <link rel="stylesheet" type="text/css" href="../stylesheets/navbarstyle.css">
    <link rel="stylesheet" type="text/css" href="../stylesheets/createpagestyle.css">
  </head>

<?php
//include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

//function for the unit form
function unitform()
{
    ?>
    <h1>Create New Unit</h1>
      <ul class="labels">
        <li>Unit Name:</li>
      </ul>
      <form action="createunit.php" method ="POST">
        <input type="text" name="unit_name" placeholder="Unit Name" >
        <button type="submit" name="submit">Enter</button>
      </form>
    </body>
  <?php
}

//if form is submitted
if (isset($_POST["submit"])) {

    //POST variables from form
    $unit_name = mysqli_real_escape_string($conn, $_POST['unit_name']);

    ///array for input sanitsation
    $errors = array();

    //input sanitsation
    if ($unit_name == "") {
        array_push($errors, "Unit name must be entered");
    }
    if (preg_match('/[^A-Za-z \-]/', $unit_name)) {
        array_push($errors, "Please enter a valid unit name");
    }

    //echo errors then exit
    if (count($errors) != 0) {
        //call form with existing values?>
        <div class="container">
          <div class="content">
            <body>
              <h1>Create New Unit</h1>
              <ul class="labels">
                <li>Unit Name:</li>
              </ul>
              <form action="createunit.php" method ="POST">
                <input type="text" name="unit_name" value= "<?php echo $_POST['unit_name']?>" placeholder="Unit Name" >
                <button type="submit" name="submit">Enter</button>
              </form>
            </body>
      <?php

      //echo errors from the input sanisation
      $issue = '';
        foreach ($errors as $error) {
            $issue = $issue . $error . "</br>";
        }
        close($conn, $issue, "unit", "Units");
        exit;
    }

    //Insert variables into unit table, if false echo error and exit
    $sql = "INSERT INTO regattascoring.UNIT (unit_name) VALUES ('$unit_name');";
    if (!mysqli_query($conn, $sql)) {
        close($conn, "Could not add unit", "unit", "Units");
        exit;
    }

    echo "  <div class='container'>
        <div class='content'>
          <body>";
    //echo unit created
    echo "<div class='message'>" . $_POST['unit_name'] . " Unit Created";
    $unit_id = mysqli_insert_id($conn); ?>
    <br>
    <a href= <?php echo "viewunit.php?id=$unit_id" ?>>Edit <?php echo $_POST['unit_name'] ?> Unit</a></div>
    <?php

    //call unit form
    unitform();

    //call close
    close($conn, $error, "unit", "Units");
} else {
    //call unit form
    echo "  <div class='container'>
        <div class='content'>
          <body>";
    unitform();

    //call close
    close($conn, $error, "unit", "Units");
}
