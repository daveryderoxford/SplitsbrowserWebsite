<?php

header ("Content-Type: text/plain; charset=iso-8859-1");
header ("Content-Encoding: gzip");

require("model/paths.php");

$targetfile = getResultsRoot() . $_GET[eventId] . ".gz";

// Read contents of the file
$numbytes = readfile($targetfile);

?>

