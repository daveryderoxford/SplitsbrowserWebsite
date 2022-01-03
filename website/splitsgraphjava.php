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
} else if (numRows($queryresult)==0) {
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
    $filename = "eventdata.php?eventId=".$row[$EVENT_ID_FIELD];
    $eventHomePage = $row[$EVENT_WEB_PAGE_FIELD];
    $editEventLink = "EventSwitchBoard.php?eventId=".$row[$EVENT_ID_FIELD];
    
    if (is_null($eventHomePage) || trim($eventHomePage)=="")
    {
      $eventTableEntry = " ";
    } else {
      $eventTableEntry = "<td><font face=\"Verdana,Arial\" color=\"#ffffff\" size=\"1\"><a class=\"nav\" href=\"$eventHomePage\">Event Home </a>|</font></td>";
	}
			
    if ($format=="ABMHTML") {
        display_old($eventName, $format, $filename);
    }  else {
		display_normal($eventName, $format, $filename, $eventTableEntry);
    }
}
 

function display_normal($eventName, $format, $filename, $eventTableEntry) 
{
$HTML_OLD=<<<HTML_OLD
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>

<HEAD>
<TITLE>$eventName</TITLE>

<META http-equiv=Content-Type content="text/html; charset=iso-8859-1">

<meta content="Splitsbrowser, orienteering, split times, SportIdent, Emit, splits, graph, epunching, results" name="keywords">
<meta content="Splitsbrowser" name="description">

<!-- Orange #333355 -= Gray #666666 -->
<link rel="stylesheet" type="text/css" href="./sbstyles.css">

</head>

<body vlink="#000066" link="#cc6600" bgcolor="#ffffff" scroll=no leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table cellspacing="0" cellpadding="0" width="100%"  height=100%  bgcolor="#000000" background="./images/uninav_bg.gif" border="0">
<tbody>


      <tr>
         <td align="right" height=20>
            <table cellspacing="3" cellpadding="0" align="left" border="0">
               <tbody>
                  <tr>
                     <td><font face="Verdana,Arial" color="#ffffff" size="1"><a class="nav" href="index.html">&nbsp;&nbsp;Splitsbrowser Home </a></font></td>
  
                  </tr>
               </tbody>
            </table>
            <table cellspacing="3" cellpadding="0" align="right" border="0">
               <tbody>
                  <tr>
                     $eventTableEntry
                     <td><font face="Verdana,Arial" color="#ffffff" size="1"><a class="nav" href="./browseevents.php">Select Event </a>|</font></td>
                     <td><font face="Verdana,Arial" color="#ffffff" size="1"><a class="nav" href="./help/mainhelp.shtml">Help </a></font>&nbsp;</td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>

<tr>
<td>

<APPLET name=splitsbrowser code=Splitsbrowser.class align=center width=100% height=100% ARCHIVE=splitsbrowser.jar>
	<PARAM NAME="src" VALUE=$filename>
	<PARAM NAME="dataformat"  VALUE=$format>
	<PARAM NAME="color1" VALUE="CBF5FF">
	<PARAM NAME="color2" VALUE="D8DCFF">
	<PARAM NAME="graphbackground" VALUE="99CCFF">
	<PARAM NAME="backgroundcolor" VALUE="99CCFF">
    A Java enabled browser is required to view this page<br>
	<a href="http://java.sun.com/getjava/installer.html">Get Java here</a>
</APPLET>

</td>
</tr>

</tbody>

</table>

</BODY>
</HTML>
HTML_OLD;
echo $HTML_OLD;
}

function display_old($eventName, $format, $filename) 
{
$HTML_OLD=<<<HTML_OLD
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<HTML><HEAD><TITLE>$eventName</TITLE>
<META http-equiv=Content-Type content="text/html; charset=iso-8859-1">
<BODY leftMargin=0 topMargin=0 scroll=no marginheight="0" marginwidth="0">

<APPLET name=splitsbrowser code=SplitsBrowser.class align=center width=100% height=100% ARCHIVE=splitsbrowser1.jar>
    <PARAM NAME="color1" VALUE="CBF5FF">
    <PARAM NAME="color2" VALUE="D8DCFF">
    <PARAM NAME="graphbackground" VALUE="99CCFF">
    <PARAM NAME="background" VALUE="3399FF">
    <PARAM NAME="dataformat" VALUE=2>
    <PARAM NAME="zipped" VALUE="false">
    <PARAM NAME="inputdata" VALUE=$filename>
    A Java enabled browser is required to view this page<br>
	<a href="http://java.sun.com/getjava/installer.html">Get Java here</a>
</APPLET>

</SCRIPT>

</BODY>
</HTML>
HTML_OLD;
echo $HTML_OLD;
}

?>
