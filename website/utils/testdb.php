<?php
   $servername = "sql5c51a.megasqlservers.eu";  // server connect 
   $username = ****See password manager****;
   $password = ****See password manager****;
   $database = "db66244_splitsbrowser_org_uk";

   echo "Database connection test  ";

   // Create connection
   $link =  mysqli_connect($servername, $username, $password, $database) or  
       die("Connection failed: ");

   if ( $link->connect_error ) {
      die("Connection failed: " . $link->connect_error );
   }

   echo "<BR>";
   echo "Connected successfully";
   

   // try to execute a query

   $sql = "SELECT * FROM EVENTS";
   
   $queryresult = mysqli_query($link, $sql)
      or die("Query failed: ");   

   if (mysqli_errno($link) != 0) {
      echo "<BR>";
      echo "Unexpected error encountered executing query<BR>".$sql;
   } 

   $num_rows = mysqli_num_rows($queryresult);
   echo "<BR>";
   echo "Query  sucessful: ".$num_rows." returned";

   mysqli_free_result($queryresult); 
   mysqli_close($link); 

?>
