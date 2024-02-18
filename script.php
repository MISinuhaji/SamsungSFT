<?php
// Retrieve the data sent from JavaScript
$myData = $_POST['myData'];

$file = fopen('textData/markerLocations.txt', 'a');
fwrite($file, $myData . "\n");
fclose($file);
?>