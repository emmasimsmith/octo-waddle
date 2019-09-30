<?php
include_once 'connection.php';

$sql = "DROP DATABASE IF EXISTS regattascoring;";
$delete = mysqli_query($conn, $sql);
if (!$delete) {
    echo "Could not kill database" . mysqli_error($conn) . "<br/>";
    exit;
}
echo "database deleted successfully" . "<br/>";

$sql = "CREATE DATABASE regattascoring;";
$create = mysqli_query($conn, $sql);
if (!$create) {
    echo "Could not create database" . mysqli_error($conn) . "<br/>";
    exit;
}
echo "database created successfully" . "<br/>";

$sql = "USE regattascoring;";
$use = mysqli_query($conn, $sql);
if (!$use) {
    echo "Could not use database." . mysqli_error($conn) . "<br/>";
    exit;
}
echo "database used successfully" . "<br/>";

$sql = "CREATE TABLE EVENT (
  event_id INT AUTO_INCREMENT PRIMARY KEY,
  location VARCHAR (20) NOT NULL,
  event_date DATE
);";
$result = mysqli_query($conn, $sql);
if (!$result) {
    echo "Could not create EVENT table" . mysqli_error($conn) . "<br/>";
    exit;
}
echo "EVENT table made successfully" . "<br/>";

$sql = "CREATE TABLE CLASS (
  class_id INT AUTO_INCREMENT PRIMARY KEY,
  class_name VARCHAR (20) NOT NULL,
  min_age DECIMAL(3,1) NOT NULL,
  max_age DECIMAL(3,1) NOT NULL
);";
$result = mysqli_query($conn, $sql);
if (!$result) {
    echo "Could not create CLASS table" . mysqli_error($conn) . "<br/>";
    exit;
}
echo "CLASS table made successfully" . "<br/>";

$sql = "CREATE TABLE UNIT (
  unit_name VARCHAR(20),
  unit_id  INT AUTO_INCREMENT PRIMARY KEY
);";
$result = mysqli_query($conn, $sql);
if (!$result) {
    echo "Could not create UNIT table" . mysqli_error($conn) . "<br/>";
    exit;
}
echo "UNIT table made successfully" . "<br/>";

$sql = "CREATE TABLE INDIVIDUAL (
  individual_id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(20) NOT NULL,
  last_name VARCHAR(40) NOT NULL,
  dob DATE NOT NULL,
  unit_id INT NOT NULL,
  role VARCHAR(20) NOT NULL,
  comments VARCHAR(60),
  FOREIGN KEY (unit_id) REFERENCES UNIT (unit_id)
);";
$result = mysqli_query($conn, $sql);
if (!$result) {
    echo "Could not create INDIVIDUAL table" . mysqli_error($conn) . "<br/>";
    exit;
}
echo "INDIVIDUAL table made successfully" . "<br/>";

$sql = "CREATE TABLE ACTIVITY (
  activity_id INT AUTO_INCREMENT PRIMARY KEY,
  activity_name VARCHAR (35) NOT NULL,
  activity_bracket VARCHAR(20) NOT NULL,
  scoring_method VARCHAR (20) NOT NULL,
  scored_by VARCHAR (20) NOT NULL
);";
$result = mysqli_query($conn, $sql);
if (!$result) {
    echo "Could not create ACTIVITY table" . mysqli_error($conn) . "<br/>";
    exit;
}
echo "ACTIVITY table made successfully" . "<br/>";

$sql = "CREATE TABLE BRACKET (
  bracket_id INT AUTO_INCREMENT PRIMARY KEY,
  class_id INT NOT NULL,
  activity_id INT NOT NULL,
  FOREIGN KEY (class_id) REFERENCES CLASS (class_id),
  FOREIGN KEY (activity_id) REFERENCES ACTIVITY (activity_id)
);";
$result = mysqli_query($conn, $sql);
if (!$result) {
    echo "Could not create BRACKET table" . mysqli_error($conn) . "<br/>";
    exit;
}
echo "BRACKET table made successfully" . "<br/>";

$sql = "CREATE TABLE CERTIFICATE (
  certificate_id INT AUTO_INCREMENT PRIMARY KEY,
  certificate_name VARCHAR (40),
  placing INT NOT NULL,
  recipient VARCHAR (20) NOT NULL
);";
$result = mysqli_query($conn, $sql);
if (!$result) {
    echo "Could not create CERTIFICATE table" . mysqli_error($conn) . "<br/>";
    exit;
}
echo "CERTIFICATE table made successfully" . "<br/>";

$sql = "CREATE TABLE PARTICIPANT (
  participant_id INT AUTO_INCREMENT PRIMARY KEY,
  participant_tag INT,
  class_id INT,
  individual_id INT NOT NULL,
  event_id INT NOT NULL,
  FOREIGN KEY (class_id) REFERENCES CLASS (class_id),
  FOREIGN KEY (individual_id) REFERENCES INDIVIDUAL (individual_id),
  FOREIGN KEY (event_id) REFERENCES EVENT (event_id)
);";
$result = mysqli_query($conn, $sql);
if (!$result) {
    echo "Could not create PARTICIPANT table" . mysqli_error($conn) . "<br/>";
    exit;
}
echo "PARTICIPANT table made successfully" . "<br/>";

$sql = "CREATE TABLE BOAT (
  boat_id INT AUTO_INCREMENT PRIMARY KEY,
  boat_number VARCHAR(10) NOT NULL,
  boat_type VARCHAR(20) NOT NULL,
  unit_id INT NOT NULL,
  boat_handicap DECIMAL (3,2) NOT NULL,
  FOREIGN KEY (unit_id) REFERENCES UNIT (unit_id)
);";
$result = mysqli_query($conn, $sql);
if (!$result) {
    echo "Could not create BOAT table" . mysqli_error($conn) . "<br/>";
    exit;
}
echo "BOAT table made successfully" . "<br/>";

$sql = "CREATE TABLE RACE_ENROLMENT (
  race_id INT PRIMARY KEY AUTO_INCREMENT,
  activity_id INT NOT NULL,
  unit_id INT NOT NULL,
  participant_id INT,
  calculated_score INT NOT NULL,
  original_score INT,
  event_id INT NOT NULL,
  FOREIGN KEY (activity_id) REFERENCES ACTIVITY (activity_id),
  FOREIGN KEY (unit_id) REFERENCES UNIT (unit_id),
  FOREIGN KEY (participant_id) REFERENCES PARTICIPANT (participant_id),
  FOREIGN KEY (event_id) REFERENCES EVENT (event_id)
);";
$result = mysqli_query($conn, $sql);
if (!$result) {
    echo "Could not create RACE_ENROLMENT table" . mysqli_error($conn) . "<br/>";
    exit;
}
echo "RACE_ENROLMENT table made successfully" . "<br/>";

$sql = "CREATE TABLE AWARD (
  award_id INT PRIMARY KEY AUTO_INCREMENT,
  unit_id INT NOT NULL,
  place INT,
  certificate_id INT NOT NULL,
  participant_id INT NOT NULL,
  race_id INT,
  event_id INT NOT NULL,
  FOREIGN KEY (unit_id) REFERENCES UNIT (unit_id),
  FOREIGN KEY (certificate_id) REFERENCES CERTIFICATE (certificate_id),
  FOREIGN KEY (participant_id) REFERENCES PARTICIPANT (participant_id),
  FOREIGN KEY (race_id) REFERENCES RACE_ENROLMENT (race_id),
  FOREIGN KEY (event_id) REFERENCES EVENT (event_id)
);";
$result = mysqli_query($conn, $sql);
if (!$result) {
    echo "Could not create AWARD table" . mysqli_error($conn) . "<br/>";
    exit;
}
echo "AWARD table made successfully" . "<br/>";

//Insert values into tables for testing
$sql = "INSERT INTO UNIT (unit_name) VALUES ('Pakuranga');";
$result = mysqli_query($conn, $sql);
$sql = "INSERT INTO UNIT (unit_name) VALUES ('Akarana');";
$result = mysqli_query($conn, $sql);
$sql = "INSERT INTO UNIT (unit_name) VALUES ('Ohuirangi');";
$result = mysqli_query($conn, $sql);


$sql = "INSERT INTO INDIVIDUAL (first_name, last_name, dob, unit_id, role, comments) VALUES ('Emma', 'Sim-Smith', '2001-10-03', '1', 'mariner', 'nut allergy');";
$result = mysqli_query($conn, $sql);
if (!$result) {
    echo "Could not create Emma Sim-Smith" . mysqli_error($conn) . "<br/>";
    exit;
}
echo "Emma Individual made successfully" . "<br/>";
$sql = "INSERT INTO INDIVIDUAL (first_name, last_name, dob, unit_id, role) VALUES ('Carina', 'Sim-Smith', '1976-04-26', '1', 'other');";
$result = mysqli_query($conn, $sql);

$sql = "INSERT INTO INDIVIDUAL (first_name, last_name, dob, unit_id, role) VALUES ('Alice', 'Denham', '2001-01-23', '3', 'mariner');";
$result = mysqli_query($conn, $sql);

$sql = "INSERT INTO INDIVIDUAL (first_name, last_name, dob, unit_id, role) VALUES ('Kayla', 'Trumper', '2008-10-10', '1', 'mariner');";
$result = mysqli_query($conn, $sql);

$sql = "INSERT INTO INDIVIDUAL (first_name, last_name, dob, unit_id, role) VALUES ('Alicia', 'Vano', '2002-10-11', '1', 'mariner');";
$result = mysqli_query($conn, $sql);

$sql = "INSERT INTO INDIVIDUAL (first_name, last_name, dob, unit_id, role) VALUES ('Bran', 'Jury', '2005-12-10', '2', 'mariner');";
$result = mysqli_query($conn, $sql);

$sql = "INSERT INTO INDIVIDUAL (first_name, last_name, dob, unit_id, role) VALUES ('Megan', 'Noronha', '2006-10-10', '2', 'mariner');";
$result = mysqli_query($conn, $sql);

$sql = "INSERT INTO INDIVIDUAL (first_name, last_name, dob, unit_id, role) VALUES ('Sam', 'Mebius', '2007-10-10', '2', 'mariner');";
$result = mysqli_query($conn, $sql);

$sql = "INSERT INTO INDIVIDUAL (first_name, last_name, dob, unit_id, role) VALUES ('Mark', 'Sim-Smith', '1973-08-14', '1', 'other');";
$result = mysqli_query($conn, $sql);

$sql = "INSERT INTO INDIVIDUAL (first_name, last_name, dob, unit_id, role) VALUES ('Mia', 'Nelson', '2009-10-10', '3', 'mariner');";
$result = mysqli_query($conn, $sql);

$sql = "INSERT INTO INDIVIDUAL (first_name, last_name, dob, unit_id, role) VALUES ('Leo', 'Nelson', '2005-10-10', '3', 'other');";
$result = mysqli_query($conn, $sql);
