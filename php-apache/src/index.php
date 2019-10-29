<?php
include_once 'navbar.php';
include_once 'connection.php';

?>
<html>
  <head>
    <title>Regatta Scoring</title>
    <link rel="stylesheet" type="text/css" href="stylesheets/navbarstyle.css">
    <link rel="stylesheet" type="text/css" href="stylesheets/indexstyle.css">
  </head>
  <body>
    <div class="outer-container">
      <div class="container">
        <div class="content">
          <h1>Welcome to Young Mariners Regatta Scoring System</h1>
          <p>
            This website enables regatta scorers an easy accessible method to input
            and calculate results. No longer do you have to worry about inefficient
            or complicated systems, use Young Mariners Regatta Scoring System for
            a breezy day of scoring.
          </p>
          <p>
            To calculate results, first create an Event. Continue by entering all
            the necessary information for Individuals, Boats, Units and Classes,
            accessed through the navigation bar. Once completed, you can select
            your event to enrol Individuals into it and enter race results.
            Finally, the last step is to calculate the regatta results!
          </p>
          <div class="content-one">
            <h2>Getting Started</h2>
            <p1>
              If you have not already started, first create an
              <a href="event/createevent.php">Event</a>
            </p1>
          </div>
          <div class="content-two">
            <h2>Already Started?</h2>
            <p1>Great! Continue by creating or viewing your information through the navigation
              bar, or
              <a href="indexevents.php">Select an Event</a>
          </div>
        </div>
      </div>
    </div>
    <div class="bottom-bar">
      <h3>Emma Sim-Smith &copy; 2019</h3>
    </div>
  </body>
</html>
