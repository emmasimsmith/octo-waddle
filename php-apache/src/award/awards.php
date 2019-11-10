<html>
  <head>
    <title>View Boats</title>
    <link rel="stylesheet" type="text/css" href="../stylesheets/navbarstyle.css">
    <link rel="stylesheet" type="text/css" href="../stylesheets/pagestyle.css">
  </head>
  <div class='container'>
      <div class='content'>
        <body>

<?php
//include navigation bar, functions and connection php files
include_once '../navbar.php';
include_once '../connection.php';
include_once '../functions.php';

$event_id = $_GET['event_id'];
//Form function
function awardform()
{
    ?>
    <h1>View Awards</h1>
    <div class="award_form">
      <form action= searchaward.php method="POST">
        <div class='form_input'>
          <div class="five_input">
            <input type="text" name="search_unit" placeholder="Search by Unit">
            <input type="text" name="search_certificate" placeholder="Search by Certificiate">
            <input type="text" name="search_place" placeholder="Search by Placing">
            <input type="text" name="search_first" placeholder="Search by First Name">
            <input type="text" name="search_last" placeholder="Search by Last Name">
          </div>
        </div>
        <div class="search_button">
          <button type="submit" name="search">Enter</button>
        </div>
      </form>
  </body>
<?php
}

//IF search is submitted
if (isset($_POST['search'])) {
    //call award form
    awardform();

    //define POST variables
    $search_unit_name_escaped = mysqli_real_escape_string($conn, $_POST['search_unit']);
    $searched_certificate_name_escaped = mysqli_real_escape_string($conn, $_POST['search_certificate']);
    $search_place_escaped = mysqli_real_escape_string($conn, $_POST['search_place']);
    $search_first_name_escaped = mysqli_real_escape_string($conn, $_POST['search_first']);
    $search_last_name_escaped = mysqli_real_escape_string($conn, $_POST['search_last']);

    //validation if strings are empty
    if (!$_POST['search_first'] and !$_POST['search_last'] and !$_POST['search_unit'] and
     !$_POST['search_certificate'] and !$_POST['search_place']) {
        close($conn, "Please search a value", "award", "awards");
        exit;
    }

    //variable array for function
    $variables = array(
      'unit_name' => array('Unit Name' => $search_unit_name_escaped),
      'certificate_name' => array('Certificate Name' => $searched_certificate_name_escaped),
      'place' => array('Place' => $searched_place_escaped),
      'first_name' => array('First Name' => $search_first_name_escaped),
      'last_name' => array('Last Name' => $search_last_name_escaped));

    //call search function
    search(
        $conn,
        "award",
        $variables,
        "regattascoring.AWARD NATURAL JOIN regattascoring.UNIT NATURAL JOIN
          regattascoring.CERTIFICATE",
        "award",
        "awards"
      );

    //call close function
    echo "<div class='close'>
      <ul>
        <li><a href='/'>Return Home</a></li>
        <li><a href= ../indexselectedevent.php?event_id=$event_id>Return to Event</a></li>
      </ul>
    </div>
  </div>
</div>";
    mysqli_close($conn);
} else {
    //call award form
    awardform();

    //variables array
    $variables = array('unit_name' => 'Unit Name', 'certificate_name' => 'Certificate Name',
    'place' => 'Place', 'first_name' => 'First Name', 'last_name' => 'Last Name');

    //echo all data from table and close
    awardviewall(
        $conn,
        "award",
        "regattascoring.AWARD NATURAL JOIN regattascoring.UNIT NATURAL JOIN
      regattascoring.CERTIFICATE",
        $variables,
        "award_id",
        "awards",
        $event_id
    );
}
//NATURAL JOIN regattascoring.PARTICIPANT LEFT JOIN regattascoring.INDIVIDUAL ON regattascoring.PARTICIPANT.individual_id = regattascoring.INDIVIDUAL.individual_id
