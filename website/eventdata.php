<?php header ("Content-Type: application/zip");

require("model/paths.php");

$targetfile = getResultsRoot() . $_GET[eventId] . ".gz";
     
// Read contents of the file
$numbytes = readfile($targetfile);

?>
