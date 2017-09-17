<?php

require_once("controls/header.php");

require_once("controls/stdcntl.php");
require_once("model/oevent.php");

	displayHeader("Splitsbrowser: Event switch board");

		 /// Initialise variables
		 $eventPassword = "";
		 $errorMessage = "";
		 $eventId = $_GET["eventId"];

        /// Set variables from post data
        if ( !empty($_POST["eventId"]) ) {
		   $eventPassword = $_POST["eventPassword"];
		   $errorMessage = $_POST["errorMessage"];
		   $eventId = $_POST["eventId"];
		   $targetEditEvent = $_POST["targetEditEvent"];
		   $targetUploadResult = $_POST["targetUploadResult"];
		   $targetDeleteResult = $_POST["targetDeleteResult"];
        }


    // If action requested then validate the password and display relivant form
	// Validate user name and password

	$actionRequested = !empty($targetEditEvent) || !empty($targetUploadResult) || !empty($targetDeleteResult);

	if ($actionRequested)
	{
	    $validPassword = validateEventPassword($eventId, $eventPassword);

		if ( !$validPassword ) {
		    displayEventSwitchBoard($eventId, $eventPassword, "Incorrect password supplied");
		}
		else
		{
		    //  If a button was pressed redirect to the relivant form
		    if (!empty($targetEditEvent)) {
			    include("EventUpdateDetailsForm.php");
		    }
			else if (!empty($targetUploadResult) ){
			    include("EventUploadResultForm.php");
            }  
            else if (!empty($targetDeleteResult)) {
			    include("EventDelete.php");
		   }
		}
	}
	else
	{
		// If no button was pressed display the admin form
        displayEventSwitchBoard($eventId, $eventPassword, $errorMessage);
    }

$BLANK=<<<BLANK
<table cellspacing="0" cellpadding="0" width="100%" height="100">
   <tr>
      <td>&nbsp;</td>
   </tr>
</table>
BLANK;
echo $BLANK;

   displayFooter();

exit;

############################## Program Functions ##############################

/* -----------------------------------------------------------------------------
  Function makeErrorList
*/
function makeErrorList($ErrorArray) {

    $errorString = "<div class=error><BR>\n";

    if ($ErrorArray) {
       $errorString = $errorString . $ErrorArray. "<BR>\n";
    }

    $errorString = $errorString .  "</div>";

  return($errorString);
}

/* -----------------------------------------------------------------------------
 Function displayEventSwitchBoard
*/
function displayEventSwitchBoard($eventId, $eventPassword, $errorMessage)
{
    global $PHP_SELF, $eventId;

    $errorString = makeErrorList($errorMessage);

    getEventNames($eventIds, $eventNames);

    if ( empty($eventId) ) {
        $eventOptions = makeOptionsFromArray ($eventIds, $eventNames, "");
    } else {
       $eventOptions = makeOptionsFromArray ($eventIds, $eventNames, $eventId);
    }

$HTML=<<<HTMLX
<br>
<table border="0" cellpadding="5" cellspacing="0">
<tr>
  <td width=30>
     &nbsp;
  </td>
  <td>
		If you have forgotten your password click <a href="mailto:support@splitsbrowser.org.uk"><b>here</b></a> to notify system administrator.<br><br>
  </td>
<tr>
  <td width=30>
     &nbsp;
  </td>
  <td>

<table border="1" cellpadding="0" cellspacing="0" bgcolor="#eeeeee">
      <td>
            <form method="POST" action="EventSwitchBoard.php" class=".smalltext">
               <input type="hidden" name="errorMessage" value=$errorMessage />
               <table border="0" cellpadding="5" cellspacing="0" bgcolor="#eeeeee">
                        <tr>
                           <td></td>
                           <td>$errorString</td>
                        </tr>
                        <tr>
                           <td align="left" class=".smallText">&nbsp;&nbsp;&nbsp;&nbsp; Event <font color="#FF0000">*</font></td>
                           <td>
                              <select size="1" name="eventId" class="smallText">
                                     $eventOptions
                               </select>
                           </td>
                        </tr>
                        <tr>
                           <td class=".smallText" align="left">&nbsp;&nbsp;Password <font color="#FF0000">*</font></td>
                           <td valign="top" align="left">
                             <input class=".smallText" type="password" name="eventPassword" size="20"></td>
                        </tr>
						<tr>
                           <td height="24" align="center"><input type="submit" value="Go" name="targetEditEvent" src="images/go.gif" width="21" height="21"></td>
                           <td height="24">Edit event information</td>
                        </tr>
                        <tr>
                           <td height="24" align="center"><input type="submit" value="Go" name="targetUploadResult" src="images/go.gif" width="21" height="21"></td>
                           <td height="24">Upload event result</td>
                        </tr>
                        <tr>
                           <td height="24" align="center"><input type="submit" value="Go" name="targetDeleteResult" src="images/go.gif" width="21" height="21"></td>
                           <td height="24">Delete event</td>
                        </tr>
               </table>
         </form>
      </td>
</table>
  </td>
</table>

HTMLX;
echo $HTML;
}

?>
