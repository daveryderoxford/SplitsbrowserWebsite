<?php

require_once("controls/stdcntl.php");
require_once("model/oevent.php");
require_once("model/nations.php");
require_once("model/eventtypes.php");
require_once("model/importformat.php");
include_once("controls/header.php");

   echo "hi";

   if (empty($eventId) && empty($_POST["FirstPass"]))
   {
	    require_once("EventSwitchBoard.php");
   }

   if (!empty($eventId))
   {
	    getOriginalForm($eventId);
   }

   if ( empty($_POST["FirstPass"]) )
   {
       // For first pass just show the form
       displayForm("&nbsp", $eventId);
   }
   else
   {

        echo "ho";
    
	      $eventId = $_POST["FirstPass"];

        $Name = $_POST["Name"];
        $Nationality = $_POST["Nationality"];
        $Day = $_POST["Day"];
        $Month = $_POST["Month"];
        $Year = $_POST["Year"];
        $Club = $_POST["Club"];
        $Type = $_POST["Type"];
        $WebPage = $_POST["WebPage"];
        $EMail = $_POST["EMail"];

     // Otherwise type to update the event
     $ok = updateEvent($ErrorArray, $eventId);

     if ($ok)
	 {
        include_once("controls/header.php");
	    displayHeader("Splitsbrowser: Update event detail");
        displaySuccessMessage($eventId);
     }
	 else
	 {
	     include_once("controls/header.php");
	     displayHeader("Splitsbrowser: Update event detail");
         $errorString = makeErrorList1($ErrorArray);
         displayForm($errorString, $eventId);
      }
   }

    displayFooter();

exit;

############################## Program Functions ##############################

/* -----------------------------------------------------------------------------
 Function getOriginalForm
*/
function getOriginalForm($eventId) {

	global $Name, $Nationality, $Day, $Month, $Year, $Club, $Type, $WebPage, $EMail, $FileFormat;

	global $EVENT_DATE_FIELD, $EVENT_NAME_FIELD, $EVENT_NATIONALITY_FIELD, $EVENT_FILE_FORMAT_FIELD,
           $EVENT_CLUB_FIELD, $EVENT_TYPE_FIELD, $EVENT_WEB_PAGE_FIELD, $EVENT_EMAIL_FIELD;

	getEventDetails($eventId, &$eventDetails);

	$Name			= $eventDetails[$EVENT_NAME_FIELD];
	$Nationality	= $eventDetails[$EVENT_NATIONALITY_FIELD];
	$Club			= $eventDetails[$EVENT_CLUB_FIELD];
	$Type			= $eventDetails[$EVENT_TYPE_FIELD];
	$WebPage		= $eventDetails[$EVENT_WEB_PAGE_FIELD];
	$EMail			= $eventDetails[$EVENT_EMAIL_FIELD];
	$FileFormat     = $eventDetails[$EVENT_FILE_FORMAT_FIELD];
	$Date           = $eventDetails[$EVENT_DATE_FIELD];

	// Decode date
	$Day = date("j", $Date);
	$Month = date("n", $Date);
	$Year = date("Y", $Date);
}

/* -----------------------------------------------------------------------------
 Function updateEvent
*/
function updateEvent(&$ErrorArray, $eventId) {

   global $Name, $Nationality, $Day, $Month, $Year, $Club, $Type,
           $WebPage, $EMail, $FileFormat;

    $ok = validateParameters($ErrorArray);

	if ($ok)
	{
		$ok = updateEventDetails($Name,
							$Nationality,
							$Day,
							$Month,
							$Year,
							$Club,
							$Type,
							$WebPage,
							$EMail,
							$FileFormat,
							$eventId,
							$ErrorArray);

    }

    return($ok);
}


/* -----------------------------------------------------------------------------
 Function validateParameters
*/
function validateParameters(&$ErrorArray)
{
   global $Name, $Nationality, $Day, $Month, $Year, $Club, $WebPage, $EMail,
          $DataFile, $Password, $ConfirmPassword, $Formats, $Error, $FirstPass, $FileFormat;

  $ErrorArray = array();

  // check non-null parameters
  if (!$Name)        $ErrorArray[] = "Event name must be entered";
  if (!$Nationality) $ErrorArray[] = "Nationality must be entered";
  if (!$Club)        $ErrorArray[] = "A club name must be specified";
  if (!$EMail)       $ErrorArray[] = "Contact email must be entered";

  if ($FileFormat=="UNDEFINED") $ErrorArray[] = "File format must be specified";

  $ok = count($ErrorArray)==0;
  return ($ok);
}

/* -----------------------------------------------------------------------------
  Function makeErrorList1
*/
function makeErrorList1($ErrorArray) {

    $errorString = "<div class=error><BR>\n";

    foreach ($ErrorArray as $Error) {
       $errorString = $errorString . $Error. "<BR>\n";
    }

    $errorString = $errorString .  "</div>";

  return($errorString);
}

/* -----------------------------------------------------------------------------
 Function displaySuccessMessage
*/
function displaySuccessMessage($eventId)
{
   global $Name, $Nationality, $Day, $Month, $Year, $Club, $WebPage, $EMail;

   $graphURL = getBaseURL(). "splitsgraph.php?eventId=" . $eventId;
   $textURL = getBaseURL() . "splitstext.php?eventId=" . $eventId;;

$HTML=<<<HTML
<center>
<BR>
<BR>
<BR>
<table border="1" cellpadding="0" cellspacing="0">

   <table border="0" cellpadding="5" cellspacing="0">
       <tr>
       <td colspan="2">

          Results successfully submitted<br><br>
		  Name: $Name<br>
          Nationality: $Nationality<br>
          Club: $Club<br>
          Date: $Day $Month $Year<br>
          Contact email: $EMail<br>
          Web page: $WebPage<br><br>

		  <a href=EventSwitchBoard.php?eventId=$eventId>Event Admin</a><br><br>

		  <a href=splitsgraph.php?eventId=$eventId>View splits</a><br>

       <td>
       </tr>
</table>
</table>
</center>
HTML;
echo $HTML;
}

/* -----------------------------------------------------------------------------
 Function DisplayForm
*/
function displayForm($ErrorString, $eventId)
{

    # declare global form variables
    global $PHP_SELF; # The path and name of this file
    global $Name, $Nationality, $Day, $Month, $Year, $Club, $Type,
           $Password, $ConfirmPassword, $WebPage, $EMail, $DataFile, $FileFormat,
           $Error, $FirstPass;

    $Days = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31);
    $DayOptions = makeOptionsFromSimpleArray ($Days, $Day);

    $Months = array(1,2,3,4,5,6,7,8,9,10,11,12);
    $MonthText = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
    $MonthOptions = makeOptionsFromArray ($Months, $MonthText, $Month);

  #  $EndYear = date("Y");
  #  for($i=$2000; $i<=$EndYear; $i++) {
  #    $Years[] = $i;
  #  }
     $Years = array(200,2001,2002,2003,2004,2005,2006,2007,2008,2009,2010,2011);
    $YearOptions = makeOptionsFromSimpleArray ($Years, $Year);

   if ( empty($Type) ) {
     $TypeOptions = makeOptionsFromArray (getEventTypeIds(), getEventTypeNames(), 0);
   } else {
     $TypeOptions = makeOptionsFromArray (getEventTypeIds(), getEventTypeNames(), $Type);
   }

    $NationNames = getNationNames();
    $Nations = getNations();

   if ( empty($Nationality) ) {
      $NationalityOptions = makeOptionsFromArray ($Nations, $NationNames, "---");
   } else {
     $NationalityOptions = makeOptionsFromArray ($Nations, $NationNames, $Nationality);
   }

   $FormatNames = getFormatNames();
   $Formats = getFormatIDs();
   if (empty($FirstPass))
   {
      $FileFormat = getFileFormat($eventId);
   }
   $FormatOptions = makeOptionsFromArray ($Formats, $FormatNames, $FileFormat);

$HTML=<<<HTML
<script language="JavaScript">

<!-- Begin
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, 'FormatHelp', 'toolbar=1,scrollbars=1,location=1,statusbar=1,menubar=1,resizable=1,width=600,height=400');");
}
// End -->
</script>

<br>
<table border="0" cellpadding="5" cellspacing="0">
<tr>
  <td width=30>
     &nbsp;
  </td>
  <td>
     <a href="./organisers.shtml">Help on filling out this form</a>
  </td>
<tr>
  <td width=30>
     &nbsp;
  </td>
  <td>

<table border="1" cellpadding="0" cellspacing="0" bgcolor="#eeeeee">
      <td>
         <form method="POST" ACTION="EventUpdateDetailsForm.php" enctype="multipart/form-data">
               <input type="hidden" name="FirstPass" value="$eventId" />
               <table border="0" cellpadding="5" cellspacing="0" bgcolor="#eeeeee">
                  <tr>
                     <td></td>
                     <td>
                        $ErrorString
                     </td>
                  </tr>
                  <tr>
                     <td align="right">  &nbsp;&nbsp;&nbsp;&nbsp; Event name <font color="#FF0000">*</font></td>
                     <td><input type="text" name="Name" size="50" tabindex="1" value= "$Name"></td>
                  </tr>
                  <tr>
                     <td align="right">Event Date <font color="#FF0000">*</font></td>
                     <td valign="top" align="left">&nbsp;
                        <select size="1" name="Day" >
                            $DayOptions
                        </select>&nbsp;
                        <select size="1" name="Month" >
                            $MonthOptions
                        </select>
                        <select size="1" name="Year" >
                            $YearOptions
                        </select></td>
                  </tr>
                  <tr>
                     <td align="right">Nationality <font color="#FF0000">*</font></td>
                     <td align="left" ><select size="1" name="Nationality" tabindex="3" >
                           $NationalityOptions
                        </select></td>
                  </tr>
                  <tr>
                     <td align="right">Event type <font color="#FF0000">*</font></td>
                     <td align="left"><select size="1" name="Type" tabindex="4" >
                           $TypeOptions
                        </select></td>
                  </tr>
                  <tr>
                     <td align="right">Club <font color="#FF0000">*</font></td>
                     <td align="left">
                      <input type="text" name="Club" value= "$Club" size="20" ></td>
                  </tr>
                  <tr>
                     <td align="right">&nbsp;</td>
                     <td align="left">&nbsp;</td>
                  </tr>
                  <tr>
                     <td align="right">Event web page</td>
                     <td align="left">
                       <input type="text" name="WebPage" size="50" value = "$WebPage"></td>
                  </tr>
                  <tr>
                     <td align="right">Contact E-Mail <font color="#ff0000">*</font></td>
                     <td align="left">
                        <input type="text" name="EMail" size="50" value ="$EMail">
                     </td>
                  </tr>
                  <tr>
                     <td align="right">&nbsp;</td>
                     <td>&nbsp;</td>
                  </tr>

                  <tr>
                     <td align="right">File Format <font color="#FF0000">*</font></td>
                     <td align="left" >
                        <select size="1" name="FileFormat" >
                           $FormatOptions
                        </select>
                        &nbsp;&nbsp;
                        <A HREF="javascript:popUp('/formathelp/index.html')">Help on formats</A>
                     </td>
                  </tr>
                  <tr>
                     <td>&nbsp;&nbsp;</td>
                     <td>&nbsp;</td>
                  </tr>
                  <tr>
                     <td colspan="2"><input type="submit" value="Submit" name="Submit">&nbsp</td>
                  </tr>
               </table>
         </form>
      </td>
</table>
  </td>
</table>

HTML;
echo $HTML;
}

?>
