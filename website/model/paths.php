<?php
function getResultsRoot() {
  // This appears to be the only place php scripts have permission  to create files.
  $path = $_SERVER['DOCUMENT_ROOT']."/cgi-bin/results/";
  return($path);
}

function getBaseURL() {
  return("http://www.splitsbrowser.org.uk/");
}

?>
