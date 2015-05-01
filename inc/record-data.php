<?php

// CREATOR
// Steven Braun
// braunsg
// 
// REPO
// interactive-map-survey
// 
// ORIGINAL CREATION DATE
// 2014-08-22
// 
// DESCRIPTION
// This code inserts submitted map survey comments into a database

date_default_timezone_set('America/Chicago');

// Connect to MySQL server


$con = mysqli_connect('database host', 'database user', 'database password', 'database name') or die(mysqli_connect_error());


$xCoords = $_POST['x'];
$yCoords = $_POST['y'];
$comment = mysqli_real_escape_string($con,$_POST['comment']);
$floor = $_POST['floor'];
$type = $_POST['type'];

$date = date('Y-m-d H:i:s');

$insertQuery = "INSERT INTO user_responses (dateStamp, xCoords, yCoords, comment, floor, type) VALUES ('$date','$xCoords','$yCoords','$comment', '$floor','$type')";
if(!mysqli_query($con,$insertQuery)) {
			die('scopus_all.publications.Error: ' . mysqli_error($con));
}

mysqli_close($con);


 
?>