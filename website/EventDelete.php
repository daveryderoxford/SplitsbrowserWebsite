<?php

require_once("controls/stdcntl.php");
require_once("model/oevent.php");
require_once("model/nations.php");
require_once("model/eventtypes.php");
require_once("model/importformat.php");
include_once("controls/header.php");

   if (empty($_POST["eventId"]))
   {
	 require_once("EventSwitchBoard.php");
   }

   if ( empty($_POST["FirstPass1"]) )
   {
	   blankSpace();
	   displaywarning($eventId);
   }
   else
   {
           $targetDeleteConfirm = $_POST["targetDeleteConfirm"];
           $FirstPass1 = $_POST["FirstPass1"];

	   $eventId = $FirstPass1;

	   if ($targetDeleteConfirm)
	   {
                   echo " deleting " & $eventId;
		   $ok = deleteEvent($eventId);
		   unset($_POST["eventId"]);
		   $errorMessage = "Event sucessfully deleted";
		}

	    require_once ("EventSwitchBoard.php");	   
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
 Function displaywarning
*/
function displaywarning($eventId)
{
$HTML=<<<HTMLX
<table height = "60" width = "300" border="1" cellpadding="0" cellspacing="0" bgcolor="#eeeeee" align = "center" >
<tbody>
	<tr><td>
		
		<table cellpadding="0" cellspacing="0">
		<tr>
		<td width="50">&nbsp;</td>
		</tr>
		<tr>
		<td width="50">&nbsp;</td>
		</tr>
		</table>
			
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td width="5">&nbsp;</td>
				<td valign="top" align="center"><font size="3" face="Verdana,Arial">
				<b>Are you sure you want to delete event $eventId?</b>
				</td></font>
			</tr>
		</table>


		<form method="POST" action="EventDelete.php" class=".smalltext">
			 <input type="hidden" name="FirstPass1" value="$eventId" />
			 <input type="hidden" name="eventId" value="$eventId" />
			 <table height = "60" width = "300" border="0" cellpadding="5" cellspacing="0" bgcolor="#eeeeee" align = "center">
				<tr>
					<td width="40">&nbsp;</td>
					<td height="40" align="center"><input type="submit" value="Yes" name="targetDeleteConfirm" src="images/go.gif" width="40" height="40"></td>
					<td width="30">&nbsp;</td>
					<td height="40" align="center"><input type="submit" value="No" name="targetDeleteRefused" src="images/go.gif" width="40" height="40"></td>
				</tr>
				<tr>
				<td></td>
				<td width="30">&nbsp;</td>
				</tr>
			</table>
		</form>
	</tr></td>
</tbody>                       
</table>
HTMLX;
echo $HTML;
}

?>