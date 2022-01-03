<?php

require_once("controls/stdcntl.php");
require_once("model/oevent.php");
require_once("model/nations.php");


   if ( empty($_GET['nationality']) ) {
      $nationality  = $WILDCARD;
   }else {
      $nationality = $_GET['nationality'] ;
   }

   $conId = eventDBConnect();

   $sql = makeResultsQuery($nationality);

   $ok = executeQuery($conId, $queryresult, $sql);

   if (!$ok) {
       print "<br><br>";
       print("Unexpected error encountered executing query<BR>".$sql."<BR>");
   } else {
      displayPage($nationality, $queryresult);
   }

   clearQuery($queryresult);

   eventDBDisconnect($conId);

exit;

/* -----------------------------------------------------------------------------
  Function makeResultsQuery
*/
function makeResultsQuery($nationality)
{
    global $WILDCARD, $EVENT_NATIONALITY_FIELD, $EVENT_DATE_FIELD;

    $sql = "SELECT * FROM EVENTS ";

    if ($nationality != $WILDCARD) {
       $sql.= "WHERE $EVENT_NATIONALITY_FIELD = '$nationality'";
    }

    $sql.= " ORDER BY ". $EVENT_DATE_FIELD . " DESC," . $EVENT_NATIONALITY_FIELD ." LIMIT 0,6;";
    return($sql);
}

/* -----------------------------------------------------------------------------
  Function tableRow
*/

function tableRow($title, $linktext, $linkurl)
{
    $row= '<tr>
     <td valign="top" class="smallText" width="6" height="29">
     <img src="images/arrow_small.gif" width="6" height="11" valign="top">
    </td>
    <td class="smallText" valign="top" align="left" height="29">'.$title.
      '<a class="link" href="'.$linkurl.'"><br>'.$linktext.'</a>
    </td>
    </tr>';

    return($row);
}

/* -----------------------------------------------------------------------------
  Function makeResultsTable
*/
function makeResultsTable($queryresult) {

    global $EVENT_DATE_FIELD, $EVENT_NAME_FIELD, $EVENT_NATIONALITY_FIELD,
           $EVENT_CLUB_FIELD, $EVENT_ID_FIELD;

    $num_rows = numRows($queryresult);

    if ($num_rows==0) {
       $tableText= "<TR><TD class=error colspan=2>No events found<TD></TR>";
    }

    $tableText = "";

    for( $i=0; $i<$num_rows; $i++) {
        $row = nextRow($queryresult);
        $date = $row[$EVENT_DATE_FIELD];
        $title = date("d M y", $date).
            "&nbsp;&nbsp; $row[$EVENT_CLUB_FIELD] &nbsp;&nbsp; $row[$EVENT_NATIONALITY_FIELD]";

        $linkurl = "./splitsgraph.php?eventId=" . $row[$EVENT_ID_FIELD];

        $tableText.= tableRow($title, $row[$EVENT_NAME_FIELD], $linkurl);
    }
    return($tableText);
}

/* -----------------------------------------------------------------------------
  Function displayPage
*/
function displayPage($nationality, $queryresult)
{
   global $WILDCARD, $PHP_SELF;

   $nationNames = getNationNames();
   Array_unshift($nationNames, $WILDCARD);

   $nations = getNations();
   Array_unshift($nations, $WILDCARD);

   $nationalityOptions = makeOptionsFromArray($nations, $nationNames, $nationality);

   $resultsTable = makeResultsTable($queryresult);

$HTML=<<<HTML
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Splitsbrowser homepage</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta content="Splitsbrowser, orienteering, split times, SportIdent, Emit, splits, graph, epunching, results" name="keywords">
<meta content="Splitsbrowser" name="description">

<!-- Orange #333355 -= Gray #666666 -->
<link rel="stylesheet" type="text/css" href="sbstyles.css">

<!--  feed autodiscovery links  -->
<link rel="alternate" type="application/rss+xml" title="SplitsBrowser RSS" href="http://www.splitsbrowser.org.uk/rss.php" />


<script language="javascript">
function setNationality() {
   c = document.forms[0].nationality;
   value = c[c.selectedIndex].value;
   url = "index.php?nationality=" + value;
   if (url) location.href = url;
}
</script>

</head>

<body vlink="#000066" link="#cc6600" bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<!-- Top menu  -->
<table cellspacing="0" cellpadding="0" width="100%" bgcolor="#000000" background="images/uninav_bg.gif" border="0">
   <tbody>
      <tr>
         <td align="right">
            <table cellspacing="3" cellpadding="0" align="right" border="0">
               <tbody>
                  <tr>
                     <td><font face="Verdana,Arial" color="#ffffff" class="tinyText"><a class="nav" href="browseevents.php">Results </a>|</font></td>
                     <td><font face="Verdana,Arial" color="#ffffff" class="tinyText"><a class="nav" href="addevent.php">Add event </a>|</font></td>
                     <td><font face="Verdana,Arial" color="#ffffff" class="tinyText"><a class="nav" href="EventSwitchBoard.php">Event Admin </a>|</font></td>
                     <td><font face="Verdana,Arial" color="#ffffff" class="tinyText"><a class="nav" href="development.shtml">Development </a>|</font></td>
                     <td><font face="Verdana,Arial" color="#ffffff" class="tinyText"><a class="nav" href="mailto:support@splitsbrowser.org.uk">Contact</a></font>&nbsp;</td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>

<!-- End of top menu  -->

<!-- Splitsbrowser logo  -->

<table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ff9900" background="images/orange_bg.gif" border="0">
   <tbody>
      <tr>
         <td valign="top" width="211"><a href="http://www.splitsbrowser.org.uk/"><img alt="Splitsbrowser logo" src="images/sb.png" border="0" width="211" height="51"></a></td>
         <td valign="bottom" align="right" class="smallText">
            <form method="POST" ACTION="$PHP_SELF" class="smallText">
               <p>Nationality&nbsp; <select size="1" onChange="setNationality()" name="nationality" class="smallText">
                  $nationalityOptions
               </select>&nbsp;&nbsp;</p>
            </form>
         </td>
      </tr>
   </tbody>
</table>
<!-- End of splitsbrowser logo  -->

<!-- Main table -->

<table cellspacing="0" cellpadding="0" width="750" border="0" summary="main table">
   <tbody>
      <tr>
            <!-- Main text -->
         <td valign="top" align="middle" width="423">
            <table cellspacing="0" cellpadding="0" width="423" border="0" summary="header content">
               <tbody>
                  <tr>
                     <!-- to move main text across -->
                     <td valign="top" align="left" width="20">&nbsp;</td>
                     <td valign="top" align="left"><br><h2>Upgraded to make Javascript version of Splitsbrowser the default</h2>

                         <p>
                        The Splitsbrowser graph has been upgraded to use the Javascript version so it is supported on mobile devices and not subject to the security restrictions that have recently affected the Java platform.  </p>

                        <p>
                        The Java version is still available from the <a href="./browseevents.php">browse events page</a>
                         </p>

                         <p>
                         Issues encountered in this Javascript version may be reported <a href="https://github.com/LukeWoodward/SplitsBrowser">here</a>
                         </p>

                         <hr>


                         <b>What is Splitsbrowser</b><br>
                        Splitsbrowser is an open, web based, system for the display and analysis of orienteering split times. It uses a Java applet to display results submitted to the site by event organisers.
                        <p><b>Viewing results</b><br>
                        The most recent event results submitted are displayed in the table to the right.&nbsp;
                        All results in the event database may be browsed <a href="./browseevents.php">here</a>.</p>

                        <p><b>Submitting results</b><br>
                        Event organisers can upload results for display on the Splitsbrowser web site in several formats from the <a href="addevent.php">Add event page</a>.</p>

                        <p>
                        Existing event details may be edited from the <a href="./EventSwitchBoard.php">Event Admin page</a>.
                        </p>

                        <p>
                        Further details on using Splitsbrowser may be found <a href="./organisers.shtml">here</a>.</p>
                        <p><b>Helping to develop Splitsbrowser</b><br>
                        SplitsBrowser is developed as a open source project.  The source code is avaliable <a href="development.shtml">here</a>.
                        </p>
                        <p><b>RSS Feed</b><br>
                                An RSS feed is avaliable to inform users of recently added
                                results. The RSS URL is <b>http://www.splitsbrowser.org.uk/rss.php</b>.
                                <a href="rssinfo.shtml">...more details</a>.
                        </p>
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
          <!--Spacers -->
         <td width="20"><img height="10" src="images/spacer.gif" width="20"></td>
         <td width="1" bgcolor="#808080"><img height="10" src="images/spacer.gif" width="1"></td>
         <!-- Results -->
         <td valign="top" width="327" bgcolor="#ffffff">
            <table cellspacing="8" cellpadding="0" width="170" border="0">
               <tbody>
                  <tr>
                     <td valign="top" colspan="2" class="tableHead" align="center" height="25"><img border="0" src="images/recentresults.png" width="154" height="23"></td>
                  </tr>
                     $resultsTable
                  <tr>
                     <td valign="top" colspan="2" height="16"><a class="link" href="browseevents.php">...browse events</a></td>
                  </tr>
               </tbody>
            </table>
         </td>
         <td width="1" bgcolor="#808080"><img height="10" src="images/spacer.gif" width="1"></td>
         <td width="100%"></td>
      </tr>
   </tbody>
</table>

<!-- Bottom bar  -->
<br>
<table cellspacing="0" cellpadding="0" width="100%" height="18" bgcolor="#000000" background="images/uninav_bg.gif" border="0">
   <tr>
      <td>&nbsp;</td>
   </tr>
</table>
<!-- End of bottom bar  -->

<!-- o --></body>
</html>

HTML;
echo $HTML;
}
