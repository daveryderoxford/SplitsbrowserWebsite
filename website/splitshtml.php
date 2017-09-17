<?php

require_once("model/paths.php");

$eventId =$_GET["eventId"];

$targetfile = getresultsRoot() . $eventId . ".html";

// Read contents of the file and write to standard output
$numbytes = readfile($targetfile);

exit;
?>
