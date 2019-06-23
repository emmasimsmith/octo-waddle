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

$sql = "CREATE TABLE INDIVIDUAL (
  individual_id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(20) NOT NULL,
  last_name VARCHAR(40) NOT NULL,
  dob DATE NOT NULL,
  comments VARCHAR(60)
);";
$result = mysqli_query($conn, $sql);
if (!$result) {
    echo "Could not create INDIVIDUAL table" . mysqli_error($conn) . "<br/>";
    exit;
}
echo "INDIVIDUAL table made successfully" . "<br/>";

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
  class_name VARCHAR (20),
  min_age DECIMAL(2,1) NOT NULL,
  max_age DECIMAL(2,1) NOT NULL
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

$sql = "CREATE TABLE ACTIVITY (
  activity_id INT AUTO_INCREMENT PRIMARY KEY,
  activity_name VARCHAR (20),
  scoring VARCHAR (20) NOT NULL
);";
$result = mysqli_query($conn, $sql);
if (!$result) {
    echo "Could not create ACTIVITY table" . mysqli_error($conn) . "<br/>";
    exit;
}
echo "ACTIVITY table made successfully" . "<br/>";

$sql = "CREATE TABLE CERTIFICATE (
  certificate_id INT AUTO_INCREMENT PRIMARY KEY,
  certificate_name VARCHAR (20),
  calculation VARCHAR (20) NOT NULL,
  placing INT
);";
$result = mysqli_query($conn, $sql);
if (!$result) {
    echo "Could not create CERTIFICATE table" . mysqli_error($conn) . "<br/>";
    exit;
}
echo "CERTIFICATE table made successfully" . "<br/>";

$sql = "CREATE TABLE PARTICIPANT (
  participant_id INT AUTO_INCREMENT PRIMARY KEY,
  participant_tag INT NOT NULL,
  class_id INT NOT NULL,
  individual_id INT NOT NULL,
  FOREIGN KEY (class_id) REFERENCES CLASS (class_id),
  FOREIGN KEY (individual_id) REFERENCES INDIVIDUAL (individual_id)
);";
$result = mysqli_query($conn, $sql);
if (!$result) {
    echo "Could not create PARTICIPANT table" . mysqli_error($conn) . "<br/>";
    exit;
}
echo "PARTICIPANT table made successfully" . "<br/>";

$sql = "CREATE TABLE BOAT (
  sail_number INT PRIMARY KEY,
  boat_class VARCHAR(20) NOT NULL,
  unit_id INT NOT NULL,
  handicap VARCHAR (20) NOT NULL,
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
  participant_id INT NOT NULL,
  score INT NOT NULL,
  FOREIGN KEY (activity_id) REFERENCES ACTIVITY (activity_id),
  FOREIGN KEY (unit_id) REFERENCES UNIT (unit_id),
  FOREIGN KEY (participant_id) REFERENCES PARTICIPANT (participant_id)
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
  individual_id INT NOT NULL,
  FOREIGN KEY (unit_id) REFERENCES UNIT (unit_id),
  FOREIGN KEY (certificate_id) REFERENCES CERTIFICATE (certificate_id),
  FOREIGN KEY (individual_id) REFERENCES INDIVIDUAL (individual_id)
);";
$result = mysqli_query($conn, $sql);
if (!$result) {
    echo "Could not create AWARD table" . mysqli_error($conn) . "<br/>";
    exit;
}
echo "AWARD table made successfully" . "<br/>";
