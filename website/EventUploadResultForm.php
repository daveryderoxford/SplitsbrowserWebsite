<?php

require_once("controls/stdcntl.php");
require_once("model/oevent.php");
require_once("model/nations.php");
require_once("model/eventtypes.php");
require_once("model/importformat.php");
include_once("controls/header.php");


   if (empty($eventId) && empty($_POST["FirstPass"]))
   {
	 require_once("EventSwitchBoard.php");	 
   }

   if (!empty($eventId))
   {
	 $ok = getEvent($eventId, $eventName);
   }   

   if ( empty($_POST["FirstPass"]) )
   {
       // For first pass just show the form
       displayUploadForm("&nbsp", $eventId);
   } 
   else 
   {
          $FileFormat = $_POST["FileFormat"];
          $DataFile = $_FILES["DataFile"]["tmp_name"];

         // Otherwise type to save the event
         $ok = uploadEventData($ErrorString, $eventId);
          
         if ($ok) 
	     {
			 unset($targetUploadResult);
                      $errorMessage = "Results successfully uploaded";
		     require_once ("./EventSwitchBoard.php");
         } 
	     else 
		 {
	         include_once("controls/header.php");
	         displayHeader("Splitsbrowser: Upload event data");
             $errorString = $ErrorString;
             displayUploadForm($errorString, $eventId);
         }
   }

   blankSpace();
   displayFooter();
  
exit;

############################## Program Functions ##############################

/* -----------------------------------------------------------------------------
 Function blankSpace
*/

function blankSpace()
{
$BLANK=<<<BLANK
<table cellspacing="0" cellpadding="0" width="100%" height="100">
   <tr>
      <td>&nbsp;</td>
   </tr>
</table>
BLANK;
echo $BLANK;
}


/* -----------------------------------------------------------------------------
 Function uploadEventData
*/
function uploadEventData(&$ErrorString, $eventId) {

   global $DataFile, $FileFormat;

    $ok = validateParameters($ErrorString);
    
	if ($ok) 
	{
		updateResults($eventId,
		              $DataFile,
					  $FileFormat);	
		$ok = TRUE;	
    }
		
    return($ok);
}


/* -----------------------------------------------------------------------------
 Function validateParameters
*/
function validateParameters(&$ErrorString)
{
  $ok = TRUE;
  global $DataFile, $Formats, $Error, $FirstPass;
  
  if (!is_uploaded_file($DataFile)) {
     $ErrorString = "A valid file name has not been specified " ;
	 $ok = FALSE;
  }

  return ($ok); 
}

/* -----------------------------------------------------------------------------
 Function displayUploadForm
*/
function displayUploadForm($ErrorString, $eventId)
{

    # declare global form variables
    global $PHP_SELF; # The path and name of this file
    global $DataFile, $FileFormat, $Error, $FirstPass, $eventName;

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

<table cellpadding="0" cellspacing="0">
<tbody>
  <tr>
     <td width=40>
     &nbsp;
     </td>                        
     <td valign="top" align="left">&nbsp;</td>
     <td valign="top" align="left"><font face="Verdana,Arial"><br><br>
	 Upload result data for $eventName <br><br>
	 This will over write any existing result data for this event.<br><br><br>
     </font></td>
  </tr>
</tbody>
</table>
<br>
<table border="0" cellpadding="5" cellspacing="0">
  <td width=30>
     &nbsp;
  </td>
  <td>

<table border="1" cellpadding="0" cellspacing="0" bgcolor="#eeeeee">
      <td>
         <form method="POST" ACTION="EventUploadResultForm.php" enctype="multipart/form-data">
               <input type="hidden" name="FirstPass" value="$eventId" />
               <input type="hidden" name="eventId" value="$eventId"/>
               <table border="0" cellpadding="5" cellspacing="0" bgcolor="#eeeeee">
                  <tr>
                     <td></td>
                     <td>
                        $ErrorString
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
