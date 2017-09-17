<?php
function eventDBConnect()
{
 	error_reporting(63);
	set_time_limit(3600); // 1 hour
 
    $conId = mysql_pconnect("mysql66244.db.easily.co.uk", "mysql66244", "8rbHuHfmfQ73WRxf") or
                                   die("Connection failed: " . mysql_error());
    
    //echo("Connected to server<BR>");
    
    if ($conId !=0) {
        mysql_select_db("db66244", $conId) or
                  die("Database selection failed" . mysql_error());
    }
    
    //echo("Opened database<BR>");
    return($conId);
}

function eventDBDisconnect($conId)
{
    //echo("Closing database<BR>");
    mysql_close($conId);
}

function executeQuery($conId, &$queryresult, $querytext) {

	$queryresult = mysql_query($querytext, $conId);

 	if (($queryresult != false) and (mysql_errno() == 0)) {
		return true;
	} else {
		return false;
	}
}

function nextRow (&$querresu) {
	return mysql_fetch_array($querresu);
}

function clearQuery ($querresu) {
	mysql_free_result($querresu);
}
?>
