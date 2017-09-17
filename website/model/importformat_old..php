<?php

require_once("model/paths.php");

function getFormats() {
  $formats = array("Splitsbrowser CSV","SportIdent HTML","ABM HTML format");
  return($formats);
}

function getFormatLinks() {
  $root = getBaseURL()."formathelp/";
  $links = array($root."csv.shtml" ,$root."sihtml.shtml",$root."abmhtml.shtml");
  return($links );
}

?>
