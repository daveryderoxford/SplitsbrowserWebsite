<?php

require_once("model/paths.php");

require_once("model/oevent.php");

require_once("utils/browsertype.php");

$eventId = $_GET["eventId"];

// Read event from database
$conId = eventDBConnect();

$sql = "SELECT * FROM EVENTS WHERE ". $EVENT_ID_FIELD. "=". $eventId;

$ok = executeQuery($conId, $queryresult, $sql);

if ($ok!=true) {
    print("Unexpected error encountered executing query<BR>");
} else if (mysql_num_rows($queryresult)==0) {
    print("Event ". $eventId . "Event not found in the database<BR>");
} else {
  $row = nextRow($queryresult);
  displaySplits($row);
}

clearQuery($queryresult);

eventDBDisconnect($conId);

exit;


/* -----------------------------------------------------------------------------
  Function displaySplits
*/
function displaySplits(&$row)
{

    global $EVENT_NAME_FIELD, $EVENT_FILE_FORMAT_FIELD, $EVENT_ID_FIELD, $EVENT_WEB_PAGE_FIELD;

    $eventName = $row[$EVENT_NAME_FIELD];
    $format = $row[$EVENT_FILE_FORMAT_FIELD];
    $filename = "eventdata2.php?eventId=".$row[$EVENT_ID_FIELD];
    $eventHomePage = $row[$EVENT_WEB_PAGE_FIELD];
    $editEventLink = "EventSwitchBoard.php?eventId=".$row[$EVENT_ID_FIELD];


    if (is_null($eventHomePage) || trim($eventHomePage)=="")
    {
      $eventTableEntry = " ";
    } else {
      $eventTableEntry =  "<li><a href=\"$eventHomePage\"><span>Event Home</span></a></li>";
	  }

    if ( $format == "ABMHTML")
    {
       print("Javascript graph is not supported for ABMHTM format splits data<BR>");
    }  else {
       display_javascript($eventName, $format, $filename, $eventTableEntry);
    }
}

function display_javascript($eventName, $format, $filename, $eventTableEntry)
{
$HTML_JS=<<<HTML_JS

<!DOCTYPE html>
<HTML>

<HEAD>
<TITLE>$eventName</TITLE>
 <meta charset="utf-8">
<META http-equiv=Content-Type content="text/html; charset=iso-8859-1">

<meta content="Splitsbrowser, orienteering, split times, SportIdent, Emit, splits, graph, epunching, results" name="keywords">
<meta content="Splitsbrowser" name="description">

<!-- Orange #333355 -= Gray #666666 -->
<link rel="stylesheet" type="text/css" href="./sbstyles.css">
<link rel="stylesheet" type="text/css" href="./graphstyles.css">

</head>

 <script type="text/javascript" charset="utf-8" src="js/modernizr-2.7.1-svg.min.js"></script>
 <script type="text/javascript">
  if (!Modernizr.svg) {
    document.location = "unsupported-browser.html";
  }
 </script>

 <script type="text/javascript" charset="utf-8" src="http://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
 <script type="text/javascript" charset="utf-8" src="http://d3js.org/d3.v4.min.js"></script>
 <script type="text/javascript" charset="utf-8" src="js/splitsbrowser.min.js"></script>
 <script type="text/javascript" charset="utf-8" src="js/messages-en_gb.js"></script>

</head>

<body>

  <div id='topBar'>
    <ul>
      <li><a href="./index.php"><span>Home</span></a></li>
       $eventTableEntry
      <li><a href=./browseevents.php><span>Select event</span></a></li>
      <li  class='last' ><a href="./help/mainhelp.shtml"></span>Help<span></a></li>
    </ul>
  </div>



<script type="text/javascript">
  SplitsBrowser.loadEvent('$filename', "#topBar");
 </script>
 <noscript>
  <h1>SplitsBrowser &ndash; JavaScript is disabled</h1>

  <p>Your browser is set to disable JavaScript.</p>

  <p>SplitsBrowser cannot run if JavaScript is not enabled.  Please enable JavaScript in your browser.</p>
 </noscript>

</body>

</html>
HTML_JS;
echo $HTML_JS;
}

?>
