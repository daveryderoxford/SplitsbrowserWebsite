<?php 

 // Set the MIME type for the output and character set
 header("Content-type: application/rss+xml; charset=utf-8");
 
 require_once("controls/stdcntl.php");
 require_once("model/oevent.php");
 
 writeheader();

 /* Query database */
 $conId = eventDBConnect();
 $sql = makeQuery();

 $ok = executeQuery($conId, $queryresult, $sql);
 
 if ($ok) {
    $num_rows = numRows($queryresult);
    for( $i=0; $i<$num_rows; $i++) {
        $row = nextRow($queryresult);
        writeItem($row);
    }
  }

 clearQuery($queryresult);

 writeFooter();

 eventDBDisconnect($conId);

exit;

/* -----------------------------------------------------------------------------
Function cleanString
*/

function cleanString($s) 
{
	// Escape XML characters
	$s = str_replace('&',  '&amp;', $s);
	$s = str_replace("'",  '&apos;',  $s);
	$s = str_replace('"', '&quot;', $s);
	$s = str_replace('<',  '&lt;',  $s);
	$s = str_replace('>',  '&gt;', $s);	
    $s = str_replace('�',  '&#8221;', $s); 
    
	$s = cleanUTF8($s);
	
	return($s);
}

      
/* -----------------------------------------------------------------------------
Function cleanUTF8

Cleans a UTF8 string
/* -----------------------------------------------------------------------------
Function cleanUTF8
*/
function cleanUTF8($string)
{
return strtr($string,
  "???????��������������������������������������������������������������",
  "SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy");
} 

/* -----------------------------------------------------------------------------
Function makeQuery
*/
function makeQuery()
{
    global $EVENT_NATIONALITY_FIELD, $EVENT_DATE_FIELD;

    $sql = "SELECT * FROM EVENTS ";
    $sql.= " ORDER BY ". $EVENT_DATE_FIELD . " DESC," . $EVENT_NATIONALITY_FIELD ." LIMIT 0,15;";

    return($sql);
}

/* -----------------------------------------------------------------------------
Function writeItem
*/
function writeItem($row)
{

    global $EVENT_DATE_FIELD, $EVENT_CLUB_FIELD, $EVENT_NAME_FIELD, $EVENT_ID_FIELD;
    
	$date = $row[$EVENT_DATE_FIELD];
	$title =  cleanString($row[$EVENT_NAME_FIELD]. "  ". $row[$EVENT_CLUB_FIELD]."  ".date("d M y", $date));
	$link = "http://www.splitsbrowser.org.uk/splitsgraph.php?eventId=" . $row[$EVENT_ID_FIELD];
	$desc = $title;
	
	print("<item>\n");
	print("  <title>". $title ."</title>\n");
	print("  <link>".$link."</link>\n");
	print("  <description>". $desc. "</description>\n");
	print("</item>\n\n");
}

/* -----------------------------------------------------------------------------
Function writeheader
*/
function writeheader()
{

print <<<END
<rss version="2.0">
  <channel>
    <title>SplitsBrowser</title>
    <link>http://www.splitsbrowser.org.uk</link>
    <description>Splitsbrowser orienteering split time analysis</description> 
    <language>en-gb</language>

END;

}

/* -----------------------------------------------------------------------------
Function writeFooter
*/
function writeFooter()
{
    print("</channel>\n");
    print("</rss>\n");
}

?>




