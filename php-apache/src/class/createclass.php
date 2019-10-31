<?php
//include navigation bar, functions and connection php files
include_once '../connection.php';
include_once '../functions.php';

//function for the class form
function classform()
{
    ?>
    <h1>Create New Classes</h1>
      <ul class="labels">
        <li>Junior Junior Class Minimum Age:</li>
        <li>Junior Class Minimum Age:</li>
        <li>Intermediate Class Minimum Age:</li>
        <li>Senior Class Minimum Age:</li>
        <li>Senior Class Maximum Age:</li>
      </ul>
      <form action="createclass.php" method ="POST">
        <div class="inside-form">
          <input type='number' name="jj_min_age" placeholder="Minimum Age" step="any" min="0" max="99" required>
          <input type='number' name="junior_min_age" placeholder="Minimum Age" step="any" min="0" rmax="99" equired>
          <input type='number' name="intermediate_min_age" placeholder="Minimum Age" step="any" min="0" max="99" required>
          <input type='number' name="senior_min_age" placeholder="Minimum Age" step="any" min="0" max="99" required>
          <input type='number' name="senior_max_age" placeholder="Maximum Age" step="any" min="0" max="99" required>
        </div>
        <div class="button">
          <button type="submit" name="submit">Enter</button>
        </div>
      </form>
    </body>
<?php
}

$sql = "SELECT * FROM regattascoring.CLASS;";

if (mysqli_num_rows(mysqli_query($conn, $sql)) != 0) {
    //include navbar
    header("Location: viewclass.php");

//IF FORM SUBMITTED
} elseif (isset($_POST["submit"])) {
    //include navbar
    include_once '../navbar.php'; ?> <html>
      <head>
        <title>Create Individual</title>
        <link rel="stylesheet" type="text/css" href="../stylesheets/navbarstyle.css">
        <link rel="stylesheet" type="text/css" href="../stylesheets/pagestyle.css">
      </head>
    <?php
    //define POST variables
    $jj_min_age = mysqli_real_escape_string($conn, $_POST['jj_min_age']);
    $junior_min_age = mysqli_real_escape_string($conn, $_POST['junior_min_age']);
    $intermediate_min_age = mysqli_real_escape_string($conn, $_POST['intermediate_min_age']);
    $senior_min_age = mysqli_real_escape_string($conn, $_POST['senior_min_age']);
    $senior_max_age = mysqli_real_escape_string($conn, $_POST['senior_max_age']);

    //array for validation errors
    $errors = array();

    //input sanitsation
    if ($jj_min_age > $junior_min_age) {
        array_push($errors, "Junior Junior Class minimum age must be less than Junior Class minimum age");
    }
    if ($junior_min_age > $intermediate_min_age) {
        array_push($errors, "Junior Class minimum age must be less than Intermediate Class minimum age");
    }
    if ($intermediate_min_age > $senior_min_age) {
        array_push($errors, "Intermediate Class minimum age must be less than Senior Class minimum age");
    }
    if ($senior_min_age > $senior_max_age) {
        array_push($errors, "Senior Class minimum age must be less than Senior Class maximum age");
    }

    //if not valid echo error and form then exit
    if (count($errors) != 0) {
        //call form with existing values?>
        <div class="container">
          <div class="content">
            <body>
              <h1>Create New Classes</h1>
              <ul class="labels">
                <li>Junior Junior Class Minimum Age:</li>
                <li>Junior Class Minimum Age:</li>
                <li>Intermediate Class Minimum Age:</li>
                <li>Senior Class Minimum Age:</li>
                <li>Senior Class Maximum Age:</li>
              </ul>
              <form action= createclass.php method ="POST">
                <div class="inside-form">
                  <input type='number' name="jj_min_age" value= '<?php echo $jj_min_age ?>' placeholder="Minimum Age" step="any" min="0" max="99" required>
                  <input type='number' name="junior_min_age" value= '<?php echo $junior_min_age ?>' placeholder="Minimum Age" step="any" min="0" max="99" required>
                  <input type='number' name="intermediate_min_age" value= '<?php echo $intermediate_min_age ?>' placeholder="Minimum Age" step="any" min="0" max="99" required>
                  <input type='number' name="senior_min_age" value= '<?php echo $senior_min_age ?>' placeholder="Minimum Age" step="any" min="0" max="99" required>
                  <input type='number' name="senior_max_age" value= '<?php echo $senior_max_age ?>' placeholder="Maximum Age" step="any" min="0" max="99" required>
                </div>
                <div class="button">
                  <button type="submit" name="submit">Enter</button>
                </div>
              </form>
            </body>
        <?php

        $issue = '';
        foreach ($errors as $error) {
            $issue = $issue . $error . "</br>";
        }
        close($conn, $issue, "class", "Classes");
        exit;
    }

    //format min and max ages
    $jj_min_age_format = number_format($jj_min_age, 1, '.', ',');
    $junior_min_age_format = number_format($junior_min_age, 1, '.', ',');
    $intermediate_min_age_format = number_format($intermediate_min_age, 1, '.', ',');
    $senior_min_age_format = number_format($senior_min_age, 1, '.', ',');
    $senior_max_age_format = number_format($senior_max_age, 1, '.', ',');

    //insert classes into table
    $sql = "INSERT INTO regattascoring.CLASS (class_name, min_age, max_age)
        VALUES ('Junior Junior', '$jj_min_age_format','$junior_min_age_format');";
    $result = mysqli_query($conn, $sql);
    $sql = "INSERT INTO regattascoring.CLASS (class_name, min_age, max_age)
        VALUES ('Junior', '$junior_min_age_format','$intermediate_min_age_format');";
    $result = mysqli_query($conn, $sql);
    $sql = "INSERT INTO regattascoring.CLASS (class_name, min_age, max_age)
        VALUES ('Intermediate', '$intermediate_min_age_format','$senior_min_age_format');";
    $result = mysqli_query($conn, $sql);
    $sql = "INSERT INTO regattascoring.CLASS (class_name, min_age, max_age)
        VALUES ('Senior', '$senior_min_age_format','$senior_max_age_format');";
    $result = mysqli_query($conn, $sql);

    $sql = "USE regattascoring;";
    $use = mysqli_query($conn, $sql);
    if (!$use) {
        echo "Could not use database." . mysqli_error($conn) . "<br/>";
        exit;
    }
    $sql = "DELETE FROM BRACKET;";
    $delete = mysqli_query($conn, $sql);
    $sql = "DELETE FROM ACTIVITY;";
    $delete = mysqli_query($conn, $sql);
    if (!$delete) {
        echo "Could not delete all from activity table" . mysqli_error($conn) . "<br/>";
        exit;
    }

    //create all activities

    //Cutter Sailing
    $sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Cutter Sailing', 'class', 'place', 'unit');";
    $result = mysqli_query($conn, $sql);
    $activity_id = mysqli_insert_id($conn);
    $sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('2', '$activity_id');";
    $result = mysqli_query($conn, $sql);
    $sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('3', '$activity_id');";
    $result = mysqli_query($conn, $sql);
    $sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('4', '$activity_id');";
    $result = mysqli_query($conn, $sql);

    //Sunburst Sailing
    $sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Sunburst Sailing', 'class', 'place', 'unit');";
    $result = mysqli_query($conn, $sql);
    $activity_id = mysqli_insert_id($conn);
    $sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('2', '$activity_id');";
    $result = mysqli_query($conn, $sql);
    $sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('3', '$activity_id');";
    $result = mysqli_query($conn, $sql);
    $sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('4', '$activity_id');";
    $result = mysqli_query($conn, $sql);

    //Optimist Sailing
    $sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Optimist Sailing', 'class', 'place', 'unit');";
    $result = mysqli_query($conn, $sql);
    $activity_id = mysqli_insert_id($conn);
    $sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('1', '$activity_id');";

    //Sunburst Rigging
    $sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Sunburst Rigging', 'unit', 'score', 'unit');";
    $result = mysqli_query($conn, $sql);

    //Cutter Rigging
    $sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Cutter Rigging', 'unit', 'score', 'unit');";
    $result = mysqli_query($conn, $sql);

    //Optimist Rigging
    $sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Optimist Rigging', 'unit', 'score', 'unit');";
    $result = mysqli_query($conn, $sql);

    //Pulling
    $sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Pulling', 'class', 'place', 'unit');";
    $result = mysqli_query($conn, $sql);
    $activity_id = mysqli_insert_id($conn);
    $sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('2', '$activity_id');";
    $result = mysqli_query($conn, $sql);
    $sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('3', '$activity_id');";
    $result = mysqli_query($conn, $sql);
    $sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('4', '$activity_id');";
    $result = mysqli_query($conn, $sql);

    //Canoeing
    $sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Canoeing', 'class', 'time', 'individual');";
    $result = mysqli_query($conn, $sql);
    $activity_id = mysqli_insert_id($conn);
    $sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('1', '$activity_id');";
    $result = mysqli_query($conn, $sql);
    $sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('2', '$activity_id');";
    $result = mysqli_query($conn, $sql);
    $sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('3', '$activity_id');";
    $result = mysqli_query($conn, $sql);
    $sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('4', '$activity_id');";
    $result = mysqli_query($conn, $sql);

    //Swimming
    $sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Swimming', 'class', 'time', 'individual');";
    $result = mysqli_query($conn, $sql);
    $activity_id = mysqli_insert_id($conn);
    $sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('1', '$activity_id');";
    $result = mysqli_query($conn, $sql);
    $sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('2', '$activity_id');";
    $result = mysqli_query($conn, $sql);
    $sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('3', '$activity_id');";
    $result = mysqli_query($conn, $sql);
    $sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('4', '$activity_id');";
    $result = mysqli_query($conn, $sql);

    //Lifesaving
    $sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Lifesaving', 'class', 'score', 'unit');";
    $result = mysqli_query($conn, $sql);
    $activity_id = mysqli_insert_id($conn);
    $sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('1', '$activity_id');";
    $result = mysqli_query($conn, $sql);
    $sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('2', '$activity_id');";
    $result = mysqli_query($conn, $sql);
    $sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('3', '$activity_id');";
    $result = mysqli_query($conn, $sql);
    $sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('4', '$activity_id');";
    $result = mysqli_query($conn, $sql);

    //Shooting
    $sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Shooting', 'class', 'score', 'individual');";
    $result = mysqli_query($conn, $sql);
    $activity_id = mysqli_insert_id($conn);
    $sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('1', '$activity_id');";
    $result = mysqli_query($conn, $sql);
    $sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('2', '$activity_id');";
    $result = mysqli_query($conn, $sql);
    $sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('3', '$activity_id');";
    $result = mysqli_query($conn, $sql);
    $sql = "INSERT INTO BRACKET (class_id, activity_id) VALUES ('4', '$activity_id');";
    $result = mysqli_query($conn, $sql);

    //Camping Set up
    $sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Camping Set Up', 'unit', 'score', 'unit');";
    $result = mysqli_query($conn, $sql);

    //Camping Friday Evening
    $sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Camping Friday Evening', 'unit', 'score', 'unit');";
    $result = mysqli_query($conn, $sql);
    echo mysqli_error($conn);

    //Camping Saturday Morning
    $sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Camping Saturday Morning', 'unit', 'score', 'unit');";
    $result = mysqli_query($conn, $sql);

    //Camping Saturday Evening
    $sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Camping Saturday Evening', 'unit', 'score', 'unit');";
    $result = mysqli_query($conn, $sql);

    //Camping Sunday Morning
    $sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Camping Sunday Morning', 'unit', 'score', 'unit');";
    $result = mysqli_query($conn, $sql);

    //Camping Sunday Evening
    $sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Camping Sunday Evening', 'unit', 'score', 'unit');";
    $result = mysqli_query($conn, $sql);

    //Camping Monday Morning
    $sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Camping Monday Morning', 'unit', 'score', 'unit');";
    $result = mysqli_query($conn, $sql);

    //Iron Woman
    $sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Iron Woman', 'unit', 'place', 'unit');";
    $result = mysqli_query($conn, $sql);

    //Seamanship - Anchoring
    $sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Seamanship - Anchoring', 'unit', 'score', 'unit');";
    $result = mysqli_query($conn, $sql);

    //Seamanship - Boat Handling
    $sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Seamanship - Boat Handling', 'unit', 'score', 'unit');";
    $result = mysqli_query($conn, $sql);

    //Seamanship - First Aid
    $sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Seamanship - First Aid', 'unit', 'score', 'unit');";
    $result = mysqli_query($conn, $sql);

    //Seamanship - Knots
    $sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Seamanship - Knots', 'unit', 'score', 'unit');";
    $result = mysqli_query($conn, $sql);

    //Seamanship - Navigation
    $sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Seamanship - Navigation', 'unit', 'score', 'unit');";
    $result = mysqli_query($conn, $sql);

    //Seamanship - Reefing
    $sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Seamanship - Reefing', 'unit', 'score', 'unit');";
    $result = mysqli_query($conn, $sql);

    //Seamanship - Sailing Rules
    $sql = "INSERT INTO ACTIVITY (activity_name, activity_bracket, scoring_method, scored_by) VALUES ('Seamanship - Sailing Rules', 'unit', 'score', 'unit');";
    $result = mysqli_query($conn, $sql);

    echo "<div class='container'>
        <div class='content'>
          <body>
    <div class='message'>Classes Created</div>
          <div class='close'>
            <ul>
              <li><a href='viewclass.php'>Edit Classes</a></li>
              <li><a href='/'>Return Home</a></li>
              <li><a href='searchclass.php'>View all Classes</a></li>
            </ul>
          </div>
        </div>
      </div>";
} else {
    ?> <html>
    <head>
      <title>Create Individual</title>
      <link rel="stylesheet" type="text/css" href="../stylesheets/navbarstyle.css">
      <link rel="stylesheet" type="text/css" href="../stylesheets/pagestyle.css">
    </head>
  <?php
  //include navbar
    include_once '../navbar.php';

    //call class form
    echo "  <div class='container'>
        <div class='content'>
          <body>";
    classform();

    //call class close
    close($conn, $error, "class", "Classes");
}
