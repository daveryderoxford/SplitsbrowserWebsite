<?php

require_once("model/database.php");
require_once("model/paths.php");
require_once("utils/utils.php");

$EVENT_ID_FIELD = "ID";
$EVENT_NAME_FIELD = "NAME";
$EVENT_DATE_FIELD = "DATE";
$EVENT_NATIONALITY_FIELD = "NATIONALITY";
$EVENT_TYPE_FIELD = "TYPE";
$EVENT_CLUB_FIELD = "CLUB";
$EVENT_FILE_FORMAT_FIELD = "FORMAT";
$EVENT_WEB_PAGE_FIELD = "WEB_PAGE";
$EVENT_EMAIL_FIELD = "EMAIL";
$EVENT_PASSWORD_FIELD = "PASSWORD";
   
/* -----------------------------------------------------------------------------
 Function saveEvent
*/
function saveEvent($name,
                   $nationality,
                   $day,
                   $month,
                   $year,
                   $club,
                   $type,
                   $webPage,
                   $email,
                   $filename,
                   $format,
                   $password,
                   $confirmPassword,
                   &$eventId,
                   &$errorArray)
{
    $NAME_MAX_LEN = 50;
    $CLUB_MAX_LEN = 20;
    $WEBPAGE_MAX_LEN = 255;
    $EMAIL_MAX_LEN = 255;

    $PASSWORD_MAX_LEN = 20;
    $PASSWORD_MIN_LEN = 4;
   
    // Name
    $name = trim($name);
    if (strlen($name) > $NAME_MAX_LEN) {
       $errorArray[] = "Event name exceeds maximum length of " .$NAME_MAX_LEN." characters";
       return(FALSE);
    }
    // Club
    $club = trim($club);
    $club = strtoupper($club);

    if (strlen($club) > $CLUB_MAX_LEN) {
       $errorArray[] = "Club name exceeds maximum length of " .$CLUB_MAX_LEN." characters";
       return(FALSE);
    }
    
    // Web page
    if (strlen($webPage) > $WEBPAGE_MAX_LEN) {
       $errorArray[] = "Web page link exceeds maximum length of " .$WEBPAGE_MAX_LEN." characters";
       return(FALSE);
    }
    
    // Email
    if (strlen($email) > $EMAIL_MAX_LEN) {
       $errorArray[] = "Web page link exceeds maximum length of " .$EMAIL_MAX_LEN." characters";
       return(FALSE);
    }
    
    if (!is_valid_email($email)) {
       $errorArray[] = "Email address is invalid";
       return(FALSE);
    }
    
    // Date
    if (!checkdate ($month, $day, $year) ) {
       $errorArray[] = "Invalid date specified";
       return(FALSE);
    }
    // date 
    $date = mktime(0, 0, 0, $month, $day, $year);
	
	$today  = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
	if ($date > $today) {
       $errorArray[] = "Date can not be in the future";
       return(FALSE);
    }
	
    // Password
    $password = trim($password);
    $confirmPassword = trim($confirmPassword);
    
    if (strcmp($password, $confirmPassword) != 0) {
       $errorArray[] = "The password and confirmation do not match";
       return(FALSE);
    }
    
    $len = strlen($password);
    if (($len > $PASSWORD_MAX_LEN) || ($len < $PASSWORD_MIN_LEN)) {
       $errorArray[] = "The password mush be between" .$PASSWORD_MIN_LEN." and ".
                        $PASSWORD_MAX_LEN." characters ";
       return(FALSE);
    }

    // Add http to web address if it is not there
    $webPage = trim($webPage);
    if ($webPage != "") {
       if (!stristr ($webPage, "http://") ) {
          $webPage = "http://".$webPage;
       }
    }

    // Insert record into database
    $sql  = "INSERT INTO EVENTS SET ";
    $sql .= "NAME='$name', ";
    $sql .= "DATE=$date, ";
    $sql .= "NATIONALITY ='$nationality', ";
    $sql .= "CLUB ='$club', ";
    $sql .= "FILE_FORMAT =1, ";
    $sql .= "FORMAT ='$format', ";
    $sql .= "WEB_PAGE ='$webPage', ";
    $sql .= "EMAIL ='$email', ";
    $sql .= "TYPE =$type,";
    $sql .= "PASSWORD ='$password' ";
	
    $conId = eventDBConnect();
    
    $result = mysql_query ($sql)
          or die("Failed to insert event data into database<BR>".mysql_error()."<BR>".$sql);
     
    $eventId =  mysql_insert_id();

    eventDBDisconnect($conId);
     
    // Copy event data file persistent store
    if ($eventId != 0) {
	    saveResultsFile($eventId, $filename);
	}
	     
    // Send mail to indicate success
    mailNotification($name,
                   $nationality,
                   $type,
                   $day,
                   $month,
                   $year,
                   $club,
                   $webPage,
                   $email,
                   $filename,
                   $format,
                   $eventId);

    return(TRUE);
}

/* --------------------------------------------------------
 Function updateEventDetails
*/
function updateEventDetails($name,
                   $nationality,
                   $day,
                   $month,
                   $year,
                   $club,
                   $type,
                   $webPage,
                   $email,
                   $format,
                   $eventId,
                   &$errorArray)
{
    $NAME_MAX_LEN = 50;
    $CLUB_MAX_LEN = 20;
    $WEBPAGE_MAX_LEN = 255;
    $EMAIL_MAX_LEN = 255;

    $PASSWORD_MAX_LEN = 20;
    $PASSWORD_MIN_LEN = 4;
	
    // Name
    $name = trim($name);
    if (strlen($name) > $NAME_MAX_LEN) {
       $errorArray[] = "Event name exceeds maximum length of " .$NAME_MAX_LEN." characters";
       return(FALSE);
    }
    // Club
    $club = trim($club);
    $club = strtoupper($club);

    if (strlen($club) > $CLUB_MAX_LEN) {
       $errorArray[] = "Club name exceeds maximum length of " .$CLUB_MAX_LEN." characters";
       return(FALSE);
    }
    
    // Web page
    if (strlen($webPage) > $WEBPAGE_MAX_LEN) {
       $errorArray[] = "Web page link exceeds maximum length of " .$WEBPAGE_MAX_LEN." characters";
       return(FALSE);
    }
    
    // Email
    if (strlen($email) > $EMAIL_MAX_LEN) {
       $errorArray[] = "Web page link exceeds maximum length of " .$EMAIL_MAX_LEN." characters";
       return(FALSE);
    }
    
    if (!is_valid_email($email)) {
       $errorArray[] = "Email address is invalid";
       return(FALSE);
    }
    
    // Date
    if (!checkdate ($month, $day, $year) ) {
       $errorArray[] = "Invalid date specified";
       return(FALSE);
    }
	
    $date = mktime(0, 0, 0, $month, $day, $year);
	
	$today  = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
	if ($date > $today) {
       $errorArray[] = "Date can not be in the future";
       return(FALSE);
    }

    // Add http to web address if it is not there
    $webPage = trim($webPage);
    if ($webPage != "") {
       if (!stristr ($webPage, "http://") ) {
          $webPage = "http://".$webPage;
       }
    }

    // Update record into database
    $sql  = "UPDATE EVENTS  SET ";
    $sql .= "NAME='$name', ";
    $sql .= "DATE=$date, ";
    $sql .= "NATIONALITY ='$nationality', ";
    $sql .= "CLUB ='$club', ";
    $sql .= "FILE_FORMAT =1, ";
    $sql .= "FORMAT ='$format', ";
    $sql .= "WEB_PAGE ='$webPage', ";
    $sql .= "EMAIL ='$email', ";
    $sql .= "TYPE =$type";
	$sql .= " WHERE ID=" . $eventId;
    
    $conId = eventDBConnect();
	
    $result = mysql_query ($sql)
          or die("Failed to update event in database<BR>".mysql_error()."<BR>".$sql);
    	     
    eventDBDisconnect($conId);
	
	return(TRUE);
}

/* --------------------------------------------------------
 Function mailNotification
*/
function mailNotification($name,
                   $nationality,
                   $type,
                   $day,
                   $month,
                   $year,
                   $club,
                   $webPage,
                   $email,
                   $filename,
                   $format,
                   &$eventId)
{
    $to = "support@splitsbrowser.org.uk";
    $subject = "Event added - ". $name;
    $body = "EventId:     ". $eventId . "\n" .
            "Nationality: ". $nationality . "\n" .
            "Club:        ". $club . "\n" .
            "Date:        ". $day . " ". $month. " " . $year . "\n" .
            "Email:       ". $email . "\n" .
            "Web page:    ". $webPage . "\n" .
            "Format:      ". $format . "\n" .
            "Type:        ". $type . "\n" .
            "Club: ". $club . "\n";
            
    mail($to, $subject, $body);
}

/* --------------------------------------------------------
 Function getEventNames
*/
function getEventNames(&$eventIds, &$eventNames) {

    global $EVENT_DATE_FIELD, $EVENT_NAME_FIELD, $EVENT_NATIONALITY_FIELD,
           $EVENT_CLUB_FIELD, $EVENT_ID_FIELD;
           
    $conId = eventDBConnect();
    
    $sql = "SELECT ID, NAME, DATE, NATIONALITY, CLUB FROM EVENTS ORDER BY DATE DESC, NATIONALITY";
    
    executeQuery($conId, $queryresult, $sql) or
       die("Unexpected error encountered executing query<BR>".$sql."<BR>");

    while( $row=nextRow($queryresult) ) {
       $eventIds[] = $row[$EVENT_ID_FIELD];
       $date = $row[$EVENT_DATE_FIELD];
       $title = date("d M y", $date).
            "&nbsp; $row[$EVENT_CLUB_FIELD] &nbsp; $row[$EVENT_NAME_FIELD]";
       $eventNames[] = $title;
    }
    
   clearQuery($queryresult);
}

/* --------------------------------------------------------
 Function getEvent
*/
function getEvent($eventId, &$eventName){

    global $EVENT_DATE_FIELD, $EVENT_NAME_FIELD, $EVENT_NATIONALITY_FIELD,
           $EVENT_CLUB_FIELD, $EVENT_ID_FIELD;
           
    $conId = eventDBConnect();
    
    $sql = "SELECT ID, NAME, DATE, NATIONALITY, CLUB, WEB_PAGE, EMAIL, TYPE FROM EVENTS WHERE ".$EVENT_ID_FIELD ."=". $eventId;
    
    executeQuery($conId, $queryresult, $sql) or
       die("Unexpected error encountered executing query<BR>".$sql."<BR>");
   
   $num_rows = mysql_num_rows($queryresult);

   if ($num_rows == 0)
   {
	  return (FALSE);
   }

   $row=nextRow($queryresult);

   $date = $row[$EVENT_DATE_FIELD];
   $title = "$row[$EVENT_NAME_FIELD]  ".date("d M y", $date);
   
   $eventName =  $title;    
    
   clearQuery($queryresult);

   return (TRUE);
}

/* --------------------------------------------------------
 Function getEventDetails
*/
function getEventDetails($eventId, &$eventDetails) {

    global $EVENT_DATE_FIELD, $EVENT_NAME_FIELD, $EVENT_NATIONALITY_FIELD, $EVENT_FILE_FORMAT_FIELD,
           $EVENT_CLUB_FIELD, $EVENT_ID_FIELD, $EVENT_TYPE_FIELD, $EVENT_WEB_PAGE_FIELD, $EVENT_EMAIL_FIELD;
           
    $conId = eventDBConnect();
    
    $sql = "SELECT ID, NAME, DATE, NATIONALITY, CLUB, WEB_PAGE, EMAIL, TYPE, FORMAT FROM EVENTS WHERE ".$EVENT_ID_FIELD ."=". $eventId;
    
    executeQuery($conId, $queryresult, $sql) or
       die("Unexpected error encountered executing query<BR>".$sql."<BR>");

	$num_rows = mysql_num_rows($queryresult);

	/*Event not found.*/
	if ($num_rows == 0)
	{
	    $eventDetails[] = 0;
    }

	$row=nextRow($queryresult);

	/*Populate the array*/
    $eventDetails[$EVENT_DATE_FIELD]        = $row[$EVENT_DATE_FIELD];
	$eventDetails[$EVENT_NAME_FIELD]        = $row[$EVENT_NAME_FIELD];
    $eventDetails[$EVENT_NATIONALITY_FIELD]	= $row[$EVENT_NATIONALITY_FIELD];
    $eventDetails[$EVENT_CLUB_FIELD]        = $row[$EVENT_CLUB_FIELD];
    $eventDetails[$EVENT_WEB_PAGE_FIELD]    = $row[$EVENT_WEB_PAGE_FIELD];
    $eventDetails[$EVENT_EMAIL_FIELD]       = $row[$EVENT_EMAIL_FIELD];
    $eventDetails[$EVENT_TYPE_FIELD]        = $row[$EVENT_TYPE_FIELD];
    $eventDetails[$EVENT_FILE_FORMAT_FIELD] = $row[$EVENT_FILE_FORMAT_FIELD];
	       
   clearQuery($queryresult);
}

/* --------------------------------------------------------
 Function getFileFormat
*/
function getFileFormat($eventId)
{
    global $EVENT_ID_FIELD, $EVENT_FILE_FORMAT_FIELD;
           
    $conId = eventDBConnect();
    
    $sql = "SELECT ID, FORMAT FROM EVENTS WHERE ".$EVENT_ID_FIELD ."=". $eventId;
    
    executeQuery($conId, $queryresult, $sql) or
       die("Unexpected error encountered executing query<BR>".$sql."<BR>");
   
   $num_rows = mysql_num_rows($queryresult);

   if ($num_rows == 0)
   {
	  return (FALSE);
   }

   $row=nextRow($queryresult);

   $fileformat = $row[$EVENT_FILE_FORMAT_FIELD];
      
   clearQuery($queryresult);
   
   return ($fileformat);
}

/* --------------------------------------------------------
 Function validateEventPassword
*/
function validateEventPassword($eventId, $password) {

    global  $EVENT_PASSWORD_FIELD, $EVENT_ID_FIELD;
	
	$ADMIN_PASSWORD = "PloPloPlo";
	          
    $conId = eventDBConnect();

    $sql = "SELECT ID, PASSWORD FROM EVENTS WHERE ".
            $EVENT_ID_FIELD ."=". $eventId;
			
    executeQuery($conId, $queryresult, $sql) or
       die("Unexpected error encountered executing query<BR>".$sql."<BR>");

    $num_rows = mysql_num_rows($queryresult);
    if ($num_rows==0) {
      return(FALSE);
    }
    
    $row=nextRow($queryresult);
	
	// Return true if or ADMIN passowrd
	$userMatches = (strcmp($row[$EVENT_PASSWORD_FIELD], trim($password) ) == 0 );
	$adminMatches = (strcmp($ADMIN_PASSWORD, trim($password)) == 0 );
	
    return ( $userMatches || $adminMatches );
}

/* --------------------------------------------------------
 Function deleteEvent
*/
function deleteEvent($eventId) {

    global $EVENT_ID_FIELD;
           
    /* Delete row from database */
    $conId = eventDBConnect();

    $sql = "DELETE FROM EVENTS WHERE ".
            $EVENT_ID_FIELD ."=". $eventId;
			
    executeQuery($conId, $queryresult, $sql) or
       die("Unexpected error encountered executing query<BR>".$sql."<BR>");
	
	deleteResultsFile($eventId);
	
	return(TRUE);
}

/* --------------------------------------------------------
 Function updateResults
*/
function updateResults($eventId, $filename, $fileFormat)
{
   deleteResultsFile($eventId);
   
   saveResultsFile($eventId, $filename);
   
   return(TRUE);   
}

/* --------------------------------------------------------
 Function deleteResultsFile
*/
function deleteResultsFile($eventId)
{	
	$targetfile = getResultsRoot() . $eventId . ".gz";
    if ( file_exists($targetfile) )  {
        unlink($targetfile);
	}
}

/* --------------------------------------------------------
 Function saveResultsFile
*/
function saveResultsFile($eventId, $filename)
{
    // Read contents of the file
    $file = fopen($filename, "rb");
    $contents = fread ($file, filesize ($filename));
    fclose ($file);
        
    // Save file compressed with zlib
    $targetfile = getResultsRoot().$eventId . ".gz";
    $zp = gzopen($targetfile, "w9");
    gzwrite($zp, $contents);
    gzclose($zp);
}

?>
