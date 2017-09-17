<?php

include("controls/header.php");

require_once("controls/stdcntl.php");
require_once("model/oevent.php");
require_once("model/nations.php");
require_once("model/eventtypes.php");
require_once("model/importformat.php");

  displayHeader("Splitsbrowser: Add event");

   if ( empty($_POST["FirstPass"]) ) {
       // For first pass just show the form
       displayForm("&nbsp");
   } else {
         $FirstPass = $_POST["FirstPass"];
         $Name = $_POST["Name"];
         $Nationality = $_POST["Nationality"];
         $Day = $_POST["Day"];
         $Month = $_POST["Month"];
         $Year = $_POST["Year"];
         $Club = $_POST["Club"];
         $Type = $_POST["Type"];
         $Password = $_POST["Password"];
         $ConfirmPassword = $_POST["ConfirmPassword"];
         $WebPage = $_POST["WebPage"];
         $EMail = $_POST["EMail"];
         $DataFile = $_FILES["DataFile"]["tmp_name"];
         $FileFormat = $_POST["FileFormat"];


     // Otherwise type to save the event
     $ok = createEvent($ErrorArray, $eventId);
     
     if ($ok) {
         displaySuccessMessage($eventId);
     } else {
          $errorString = makeErrorList($ErrorArray);
         displayForm($errorString);
     }
   }
   
   displayFooter();

exit;

############################## Program Functions ##############################

/* -----------------------------------------------------------------------------
 Function createEvent
*/
function createEvent(&$ErrorArray, &$eventId) {

   global $Name, $Nationality, $Day, $Month, $Year, $Club, $Type,
          $Password, $ConfirmPassword, $WebPage, $EMail, $DataFile, $FileFormat;

    $ok = validateParameters($ErrorArray);
    if ($ok) {
       $ok = saveEvent($Name,
                   $Nationality,
                   $Day,
                   $Month,
                   $Year,
                   $Club,
                   $Type,
                   $WebPage,
                   $EMail,
                   $DataFile,
                   $FileFormat,
                   $Password,
                   $ConfirmPassword,
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
          $DataFile, $Password, $ConfirmPassword, $FileFormat, $Error, $FirstPass;

  $ErrorArray = array();

  // check non-null parameters
  if (!$Name)        $ErrorArray[] = "Event name must be entered";
  if (!$Nationality) $ErrorArray[] = "Nationality must be entered";
  if (!$Club)        $ErrorArray[] = "A club name must be specified";
  if (!$EMail)       $ErrorArray[] = "Contact email must be entered";
  if (!$Password)    $ErrorArray[] = "A password must be entered";
  if (!$ConfirmPassword) $ErrorArray[] = "The password must be confirmed";
  if ($FileFormat=="UNDEFINED") $ErrorArray[] = "File format must be specified";

  if (!is_uploaded_file($DataFile)) {
    $ErrorArray[] = "A valid file name has not been specified " ;
  }

  $ok = count($ErrorArray)==0;
  return ($ok);
}



/* -----------------------------------------------------------------------------
  Function makeErrorList
*/
function makeErrorList($ErrorArray) {

    $errorString = "<div class=error><BR>\n";

    foreach ($ErrorArray as $Error) {
       $errorString = $errorString . $Error. "<BR>\n";
    }

    $errorString = $errorString .  "</div>";

  return($errorString);
}

/* -----------------------------------------------------------------------------
 Function DisplayForm
*/
function displayForm($ErrorString)
{

    # declare global form variables
    global $PHP_SELF; # The path and name of this file
    global $Name, $Nationality, $Day, $Month, $Year, $Club, $Type,
           $Password, $ConfirmPassword, $WebPage, $EMail, $DataFile, $FileFormat,
           $Error, $FirstPass;

    // Set default values
    if (empty($FirstPass)) {
        $now = getdate(); 
        $Day = $now[mday]; 
        $Month = $now[mon];
        $Year = $now[year]; 
        $Nationality = "---";
        $Type = 0;
        $FileFormat = "UNDEFINED";
    }
    
    $Days = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31);   
    $DayOptions = makeOptionsFromSimpleArray ($Days, $Day);

    $Months = array(1,2,3,4,5,6,7,8,9,10,11,12);
    $MonthText = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
    $MonthOptions = makeOptionsFromArray ($Months, $MonthText, $Month);

    $Year = $now[year];;
    for($i=2000; $i<=$Year; $i++) {
      $Years[] = $i;
    }

    # $Years=array("2000", "2001", "2002", "2003", "2004", "2005", "2006", "2007", "2008", "2009", "2010");
    $YearOptions = makeOptionsFromSimpleArray ($Years, $Year);

    $TypeOptions = makeOptionsFromArray (getEventTypeIds(), getEventTypeNames(), $Type);
   
    $NationNames = getNationNames();
    $Nations = getNations();
    $NationalityOptions = makeOptionsFromArray ($Nations, $NationNames, $Nationality);
        
    $FormatNames = getFormatNames();
    Array_unshift($FormatNames, "Select a format");
    $Formats = getFormatIDs();
    Array_unshift($Formats , "UNDEFINED");
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
         <form method="POST" ACTION="$PHP_SELF" enctype="multipart/form-data">
               <input type="hidden" name="FirstPass" value="No" />
               <table border="0" cellpadding="5" cellspacing="0" bgcolor="#eeeeee">
                  <tr>
                     <td></td>
                     <td>
                        $ErrorString
                     </td>
                  </tr>
                  <tr>
                     <td align="right">  &nbsp;&nbsp;&nbsp;&nbsp; Event Name <font color="#FF0000">*</font></td>
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
                     <td align="right">Event Type <font color="#FF0000">*</font></td>
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
                     <td align="right">Event webpage</td>
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
                     <td align="right">Password<font color="#ff0000">*</font></td>
                     <td align="left">
                         <input type="password" name="Password" size="50" value ="$Password"></td>
                  </tr>
                  <tr>
                     <td align="right">Confirm Password<font color="#ff0000">*</font></td>
                     <td align="left">
                       <input type="password" name="ConfirmPassword" size="50" value ="$ConfirmPassword">
                     </td>
                  </tr>
                  <tr>
                     <td align="right">&nbsp;</td>
                     <td>&nbsp;</td>
                  </tr>
                  <tr>
                     <td align="right">File Name <font color="#ff0000">*</font></td>
                     <td>
                     <INPUT TYPE=HIDDEN NAME=MAX_FILE_SIZE VALUE=2000000> 
                     <input type="file" name="DataFile" size="50" value="$DataFile"></td>
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

?>

