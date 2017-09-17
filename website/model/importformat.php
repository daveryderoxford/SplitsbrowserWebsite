<?php

require_once("model/paths.php");

function getFormatNames() {
  $formats = array("SportIdent CSV","SportIdent HTML","Splitsbrowser CSV", "ABM HTML format");
  return($formats);
}

function getFormatIDs() {
  $formats = array("SICSV","SIHTML","SBCSV","ABMHTML");
  return($formats);
}	

function getFormatLinks() {
  $root = getBaseURL()."formathelp/";
  $links = array($root."sicsv.shtml",$root."sihtml.shtml",$root."csv.shtml" ,$root."abmhtml.shtml");
  return($links );
}

?>
