<?php

function displayHeader($title)
{

$HTML=<<<HTML
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>$title</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta content="Splitsbrowser, orienteering, split times, SportIdent, Emit, splits, graph, epunching, results" name="keywords">
<meta content="Splitsbrowser" name="description">

<!-- Orange #333355 -= Gray #666666 -->
<link rel="stylesheet" type="text/css" href="./sbstyles.css">

</head>

<body vlink="#000066" link="#cc6600" bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<!-- Top menu  -->
<table cellspacing="0" cellpadding="0" width="100%" bgcolor="#000000" background="./images/uninav_bg.gif" border="0">
   <tbody>
      <tr>
         <td align="right">
            <table cellspacing="3" cellpadding="0" align="right" border="0">
               <tbody>
                  <tr>
                     <td><font face="Verdana,Arial" color="#ffffff" size="1"><a class="nav" href="browseevents.php">Results </a>|</font></td>
                     <td><font face="Verdana,Arial" color="#ffffff" size="1"><a class="nav" href="addevent.php">Add event </a>|</font></td>
                     <td><font face="Verdana,Arial" color="#ffffff" class="tinyText"><a class="nav" href="EventSwitchBoard.php">Event Admin </a>|</font></td>	
                     <td><font face="Verdana,Arial" color="#ffffff" size="1"><a class="nav" href="development.shtml">Development </a>|</font></td>
                     <td><font face="Verdana,Arial" color="#ffffff" size="1"><a class="nav" href="mailto:support@splitsbrowser.org.uk">Contact</a></font>&nbsp;</td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>
<!-- End of top menu  -->
<!-- Splitsbrowser logo  -->
<table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ff9900" background="./images/orange_bg.gif" border="0">
   <tbody>
      <tr>
         <td valign="top" width="211"><a href="http://www.splitsbrowser.org.uk/"><img alt="Splitsbrowser logo" src="./images/sb.png" border="0" width="211" height="51"></a></td>
         <td valign="bottom" align="right" class="smallText">
           &nbsp;
         </td>
      </tr>
   </tbody>
</table>
<!-- End of splitsbrowser logo  -->

HTML;

echo $HTML;
}

function displayFooter()
{
$FOOTER=<<<FOOTER
<!-- Bottom bar  -->
<br>
<table cellspacing="0" cellpadding="0" width="100%" height="18" bgcolor="#000000" background="images/uninav_bg.gif" border="0">
   <tr>
      <td>&nbsp;</td>
   </tr>
</table>
<!-- End of bottom bar  -->
</body>
</html>
FOOTER;

echo $FOOTER;
}
?>
