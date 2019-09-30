<?php
//include navigation bar, functions and connection php files
include_once '../connection.php';

$sql = "USE regattascoring;";
$use = mysqli_query($conn, $sql);
if (!$use) {
    echo "Could not use database." . mysqli_error($conn) . "<br/>";
    exit;
}

$sql = "DELETE FROM CERTIFICATE;";
$delete = mysqli_query($conn, $sql);
if (!$delete) {
    echo "Could not delete all from certificate table" . mysqli_error($conn) . "<br/>";
    exit;
}
echo "deleted successfully" . "<br/>";

//create all certificates

//Camping
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Camping', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//JJ Canoeing
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('JJ Canoeing', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Junior Canoeing
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Junior Canoeing', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Intermediate Canoeing
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Intermediate Canoeing', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Senior Canoeing
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Senior Canoeing', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Canoeing Relay
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Canoeing Relay', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Open Canoeing
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Open Canoeing', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Overall Canoeing
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Overall Canoeing', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Participation
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Participation', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//JJ Lifesaving
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('JJ Lifesaving', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Junior Lifesaving
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Junior Lifesaving', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Intermediate Lifesaving
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Intermediate Lifesaving', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Senior Lifesaving
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Senior Lifesaving', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Overall Lifesaving
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Overall Lifesaving', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Junior Pulling
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Junior Pulling', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Intermediate Pulling
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Intermediate Pulling', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Senior Pulling
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Senior Pulling', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Open Pulling
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Open Pulling', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Overall Pulling
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Overall Pulling', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Cutter Rigging
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Cutter Rigging', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Sunburst Rigging
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Sunburst Rigging', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Optimist Rigging
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Optimist Rigging', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Junior Cutter Sailing
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Junior Cutter Sailing', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Intermediate Cutter Sailing
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Intermediate Cutter Sailing', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Senior Cutter Sailing
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Senior Cutter Sailing', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Open Cutter Sailing
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Open Cutter Sailing', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Junior Sunburst Sailing
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Junior Sunburst Sailing', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Intermediate Sunburst Sailing
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Intermediate Sunburst Sailing', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Senior Sunburst Sailing
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Senior Sunburst Sailing', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Open Sunburst Sailing
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Open Sunburst Sailing', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//JJ Optimist Sailing
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('JJ Optimist Sailing', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Overall Junior Sailing
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Overall Junior Sailing', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Overall Intermediate Sailing
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Overall Intermediate Sailing', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Overall Senior Sailing
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Overall Senior Sailing', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Overall Open Sailing
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Overall Open Sailing', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Overall Sailing
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Overall Sailing', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Seamanship
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Seamanship', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Ron Bird Seamanship
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Ron Bird Seamanship', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Boat Handling
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Boat Handling', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Iron Woman
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Iron Woman', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//JJ Swimming
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('JJ Swimming', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Junior Swimming
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Junior Swimming', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Intermediate Swimming
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Intermediate Swimming', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Senior Swimming
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Senior Swimming', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Relay Swimming
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Swimming Relay', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Open Swimming
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Open Swimming', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Overall Swimming
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Overall Swimming', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//JJ Shooting
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('JJ Shooting', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Junior Shooting
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Junior Shooting', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Intermediate Shooting
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Intermediate Shooting', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Senior Shooting
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Senior Shooting', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Overall Shooting
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Overall Shooting', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Friendship
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Friendship', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Outstanding Mariner
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Outstanding Mariner', '1', 'individual');";
$result = mysqli_query($conn, $sql);

//Leading Mariner
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Leading Mariner', '1', 'individual');";
$result = mysqli_query($conn, $sql);

//Outstanding Mariner Runner Up
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Outstanding Mariner Runner Up', '1', 'individual');";
$result = mysqli_query($conn, $sql);

//Overall Open
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Overall Open', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Brian Orpen JJ Leadership
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Brian Orpen JJ Leadership', '1', 'individual');";
$result = mysqli_query($conn, $sql);

//Overall Top JJ Unit
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Overall Top JJ Unit', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Overall Top Junior Unit
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Overall Top Junior Unit', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Overall Top Intermediate Unit
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Overall Top Intermediate Unit', '3', 'unit');";
$result = mysqli_query($conn, $sql);

//Overall Top Senior Unit
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Overall Top Senior Unit', '3', 'unit');";
$result = mysqli_query($conn, $sql);

// Top Unit
$sql = "INSERT INTO CERTIFICATE (certificate_name, placing, recipient) VALUES ('Top Unit', '3', 'unit');";
$result = mysqli_query($conn, $sql);
