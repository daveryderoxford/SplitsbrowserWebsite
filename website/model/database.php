<?php
function eventDBConnect()
{
 	error_reporting(63);
	set_time_limit(3600); // 1 hour

   $conId = mysql_pconnect("sql5c51a.megasqlservers.eu", "splitsbrow573561", "OrientDB7") or
                                   die("Connection failed: " . mysql_error());   
 
    echo("Connected to server<BR>");
    
    if ($conId !=0) {
        mysql_select_db("db66244_splitsbrowser_org_uk", $conId) or
                  die("Database selection failed" . mysql_error());
    }

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
