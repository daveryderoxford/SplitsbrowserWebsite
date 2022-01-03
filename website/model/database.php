<?php
function eventDBConnect()
{
 	error_reporting(63);
	set_time_limit(3600); // 1 hour

    $servername = "sql5c51a.megasqlservers.eu";  // server connect 
    $username = ****See password manager****;
    $password = ****See password manager****;
    $database = "db66244_splitsbrowser_org_uk";
  
    // Create connection
    $link =  mysqli_connect($servername, $username, $password, $database) or
         die("Connection failed"); 

    return($link);
}

function eventDBDisconnect($link)
{
    //echo("Closing database<BR>");
    mysqli_close($link);
}

function executeQuery($link, &$queryresult, $querytext) {

	$queryresult = mysqli_query($link, $querytext);

 	if (($queryresult != false) and (mysqli_errno($link) == 0)) {
		return true;
	} else {
		return false;
	}
}

function nextRow (&$queryresult) {
	return mysqli_fetch_array($queryresult);
}

function clearQuery ($queryresult) {
	mysqli_free_result($queryresult);
}

function numRows(&$queryresult) {
    return mysqli_num_rows($queryresult);
}
