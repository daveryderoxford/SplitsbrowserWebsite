<?php

require_once("utility/stdcntl.php");
require_once("model/EventMgr.php");
require_once("model/NationalityMgr.php");
require_once("model/EventTypeMgr.php");
require_once("model/importformat.php");


include("utility/header.php");

   displayHeader("Splitsbrowser: Add event");
   
   echo $_POST["FirstPass"];

   if ( empty($_POST["FirstPass"]) )
   {
       // For first pass just show the form
       displayForm("&nbsp");
   } 
   else
   {
     echo "got to herer";
     echo $_POST;

     $Name= $_POST["Name"];
     $Nationality= $_POST["Nationality"];
     $Day= $_POST["Day"];
     $Month= $_POST["Month"];
     $Year= $_POST["Year"];
     $Club= $_POST["Club"];
     $Type= $_POST["Type"];
     $Password= $_POST["Password"];
     $ConfirmPassword= $_POST["ConfirmPassword"];
     $WebPage= $_POST["WebPage"];
     $EMail= $_POST["EMail"];
     $DataFile= $_POST["DataFile"];
     $FileFormat= $_POST["FileFormat"];

     // Otherwise type to save the event
     $ok = createEvent($ErrorArray, $eventId);

     if ($ok) 
	 {
         displaySuccessMessage($eventId);
     } 
	 else 
	 {
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
                   $Type,
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
          $DataFile, $Password, $ConfirmPassword, $Formats, $Error, $FirstPass;

  $ErrorArray = array();

  // check non-null parameters
  if (!$Name)        $ErrorArray[] = "Event name must be entered";
  if (!$Nationality) $ErrorArray[] = "Nationality must be entered";
  if (!$Club)        $ErrorArray[] = "A club name must be specified";
  if (!$EMail)       $ErrorArray[] = "Contact email must be entered";
  if (!$Password)    $ErrorArray[] = "A password must be entered";
  if (!$ConfirmPassword) $ErrorArray[] = "The password must be confirmed";

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
    global $Name, $Nationality, $Day, $Month, $Year, $Club,
           $Password, $ConfirmPassword, $WebPage, $EMail, $DataFile, $FileFormat,
           $Error, $FirstPass;

    $Days = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31);
    $DayOptions = makeOptionsFromSimpleArray ($Days, $Day);
    
    $Months = array(1,2,3,4,5,6,7,8,9,10,11,12);
    $MonthText = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
    $MonthOptions = makeOptionsFromArray ($Months, $MonthText, $Month);

    $Years=array("2000", "2001", "2002");
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

   if (empty($FirstPass)) {
      $FormatRadio = makeLinkedRadioFromArray( "FileFormat", getFormats(), getFormatLinks(), 0);
   } else {
      $FormatRadio = makeLinkedRadioFromArray( "FileFormat", getFormats(), getFormatLinks(), $FileFormat);
   }
   
$HTML=<<<HTML
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
                     <td align="right">Password<font color="#ff0000">*</font></td>
                     <td align="left">
                         <input type="password" name="Password" size="50" value ="$Password"></td>
                  </tr>
                  <tr>
                     <td align="right">ConfirmPassword<font color="#ff0000">*</font></td>
                     <td align="left">
                       <input type="password" name="ConfirmPassword" size="50" value ="$ConfirmPassword">
                     </td>
                  </tr>
                  <tr>
                     <td align="right">&nbsp;</td>
                     <td>&nbsp;</td>
                  </tr>
                  <tr>
                     <td align="right">File name <font color="#FF0000">*</font></td>
                     <td>
                     <INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="2000000">
                     <input type="file" name="DataFile" size="50" value="$DataFile"></td>
                  </tr>
                  <tr>
                     <td valign="top" align="right">File Format <font color="#FF0000">*</font></td>
                     <td>
                        $FormatRadio
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
       <td colspan="2">&nbsp;
          $Name results sucessfully submitted<br><br>
          Nationality: $Nationality<br>
          Club: $Club<br>
          Date: $Day $Month $Year<br>
          Contact email: $EMail<br>
          Web page: $WebPage<br><br>
          Add a link to $graphURL to link to splits graph<br>
          Add a link to $textURL to link to html text of splits<br><br>
          If the entry is incorrect in any way and requires updating contact <a href="mailto:support@splitsbrowser.org.uk">support</a>

       <td>
       </tr>
</table>
</table>
</center>
HTML;
echo $HTML;
}

?>
