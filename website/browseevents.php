<?php
require_once("controls/header.php");
require_once("controls/stdcntl.php");

require_once("model/oevent.php");
require_once("model/nations.php");
require_once("model/eventtypes.php");

  // Set default values
   if ( empty($_POST["type"]) ) {
      $type = $WILDCARD;
   } else {
       $type = $_POST["type"];
   }

   if ( empty($_POST["year"]) ) {
         $year =  $WILDCARD;
   } else {
       $year = $_POST["year"];
   }

   if ( empty($_POST["nationality"]) )  {
      $nationality  = $WILDCARD;
   } else {
       $nationality = $_POST["nationality"];
   }

   if (empty($_POST["club"]) ) {
      $club  = "";
   } else {
       $club = $_POST["club"];
   }

   displayHeader("Browse results");

   $conId = eventDBConnect();
   $sql = makeQuery($nationality, $type, $club, $year);

   $ok = executeQuery($conId, $queryresult, $sql);

   if (!$ok) {
       print "<br><br>";
       print("Unexpected error encountered executing query<BR>".$sql."<BR>");
   } else {
      startTable();
      displayForm($nationality, $type, $club, $year);
      displayResultsTable($queryresult);
      endTable();
   }

   clearQuery($queryresult);

   displayFooter();

   eventDBDisconnect($conId);

exit;

/* -----------------------------------------------------------------------------
  Function addCondition
*/
function addCondition(&$sql, $value, $field, &$clause)
{
    global $WILDCARD;

    if ($value != $WILDCARD) {
        $sql.= $clause . $field . "= '".$value."' ";
        $clause = " AND ";
    }
}
/* -----------------------------------------------------------------------------
  Function makeQuery
*/
function makeQuery($nationality, $type, $club, $year)
{
    global $WILDCARD, $EVENT_DATE_FIELD, $EVENT_NATIONALITY_FIELD, $EVENT_CLUB_FIELD,
           $EVENT_TYPE_FIELD;

     $sql = "SELECT * FROM EVENTS ";

    $clause = " WHERE ";
    addCondition($sql, $nationality, $EVENT_NATIONALITY_FIELD, $clause);

    if ($club!="") {
      $club = strtoupper($club);
      addCondition($sql, $club, $EVENT_CLUB_FIELD, $clause);
    }

    if ($type != 0) {
       $sql.= $clause. " $EVENT_TYPE_FIELD >=".$type;
       $clause = " AND ";
    }

    if ($year != $WILDCARD) {
      $startdate = mktime(0, 0, 0, 1, 1, $year);
      $enddate = mktime(0, 0, 0, 1, 1, ($year+1));
      $sql.= $clause. " $EVENT_DATE_FIELD >=".$startdate;
      $sql.= " AND $EVENT_DATE_FIELD <".$enddate;
    }

    $sql.= " ORDER BY ". $EVENT_DATE_FIELD . " DESC,". $EVENT_NATIONALITY_FIELD." LIMIT 0,30;";
    return($sql);
}

/* -----------------------------------------------------------------------------
  Function startTable
*/
function startTable()
{
    print('<table width=100%><tr><td width=30></td><td>');
}

/* -----------------------------------------------------------------------------
  Function endTable
*/
function endTable()
{
    print("</td></tr></table>");
}


/* -----------------------------------------------------------------------------
  Function displayForm
*/
function displayForm($nationality, $type, $club, $year)
{
    global $WILDCARD, $PHP_SELF;

    $nationNames = getNationNames();
    Array_unshift($nationNames, $WILDCARD);
    $nations = getNations();
    Array_unshift($nations, $WILDCARD);

    $nationalityOptions = makeOptionsFromArray($nations, $nationNames, $nationality);

    $typeOptions = makeOptionsFromArray(getEventTypeIds(), getEventTypeNames(), $type);


   # $now = getdate();
    $EndYear = date("Y");
    $years[]= $WILDCARD;
    for($i=$EndYear; $i>=2000; $i--) {
      $years[] = $i;
    }
  #  Array_unshift($years, $WILDCARD);
    $yearOptions = makeOptionsFromSimpleArray ($years, $year);

$HTML=<<<HTML
<form method="POST" ACTION="$PHP_SELF" enctype="multipart/form-data">
  <input type="hidden" name="firstPass" value="No" />
  <table border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td class=smalltext>
           Nationality<br>
           <select size="1" class="smallText" name="nationality" tabindex="1">$nationalityOptions</select>
       </td>
       <td class=smalltext>
           Event type<br>
           <select size="1" class="smallText" name="type" tabindex="2">$typeOptions</select>
       </td>
       <td class=smalltext>
           Year<br>
           <select size="1" class="smallText" name="year" tabindex="3">$yearOptions</select>
       </td>
       <td class=smalltext>
           &nbsp<br>
           <input type="submit" class="smallText" value="Go" name="Submit">&nbsp</input>
       </td>
     </tr>
  </table>
</form>
HTML;
echo $HTML;
}

/* -----------------------------------------------------------------------------
  Function displayResultsTable
*/
function displayResultsTable(&$queryresult) {

    global $EVENT_DATE_FIELD, $EVENT_NAME_FIELD, $EVENT_NATIONALITY_FIELD,
           $EVENT_CLUB_FIELD, $EVENT_TYPE_FIELD, $EVENT_ID_FIELD,
           $EVENT_WEB_PAGE_FIELD, $EVENT_EMAIL_FIELD;

$HTML=<<<HTML
<style>
.odd         { font-size: 11px; font-family: Arial, Helvetica, sans-serif; background-color: #ffffff}
.even        { font-size: 11px; font-family: Arial, Helvetica, sans-serif; background-color: #eeeeee}
.header      { font-size: 10px;  font-family: Arial, Helvetica, sans-serif; font-weight: bold }
.error       { font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: red}
</style>
HTML;
echo $HTML;

    print('<TABLE border="0" cellpadding="5" cellspacing="0">');

    // Print headings
    print ("<TR>");
        print("<TD class=header>Date</TD>");
        print("<TD class=header>Event name</TD>");
        print("<TD class=header>Nat</TD>");
        print("<TD class=header>Club</TD>");
        print("<TD class=header>Graph</TD>");
        print("<TD class=header>Old Java version</TD>");

        print("<TD class=header>Home <BR>web page</TD>");
        print("<TD class=header>Edit</TD>");
    print ("</TR>");

    // Print query boxes

    $num_rows = mysql_num_rows($queryresult);
    if ($num_rows==0) {
       print ("<TR><TD class=error colspan=7>No events found matching specified criteria<TD></TR>");
    }

    $oddRow = 0;
    for( $i=0; $i<$num_rows; $i++) {
        $row = nextRow($queryresult);
        print ("<TR>");

        if ($oddRow) {
            $style = "odd";
        } else {
            $style = "even";
        }
        $oddRow = !$oddRow;

        $date = $row[$EVENT_DATE_FIELD];
        print("<TD class=".$style.">" . date("d M y", $date) . "</TD>");
        print("<TD class=".$style.">" . $row[$EVENT_NAME_FIELD]. "</TD>");
        print("<TD class=".$style.">" . $row[$EVENT_NATIONALITY_FIELD]. "</TD>");
        print("<TD class=".$style.">" . $row[$EVENT_CLUB_FIELD]. "</TD>");

        print("<TD class=".$style."> <A HREF=splitsgraph.php?eventId=" . $row[$EVENT_ID_FIELD] . "> Graph</A> </TD>");

        print("<TD class=".$style.">&nbsp&nbsp <A HREF=splitsgraphjava.php?eventId=" . $row[$EVENT_ID_FIELD] . ">Old Java version</A> &nbsp&nbsp</TD>");


        if ( $row[$EVENT_WEB_PAGE_FIELD] != "" ) {
           print("<TD class=".$style."> <A HREF=".$row[$EVENT_WEB_PAGE_FIELD]."> Home</A> </TD>");
        } else {
          print("<TD class=".$style."></TD>");
        }

        print("<TD class=".$style."> <A HREF=EventSwitchBoard.php?eventId=" . $row[$EVENT_ID_FIELD] . "> Edit</A> </TD>");
        print ("</TR>");
    }
    print("</TABLE>");
}

?>
