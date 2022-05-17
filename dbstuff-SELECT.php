<!DOCTYPE HTML>
<html><head><title>MySQL examples</title> </head>
<body>
<?php
include "config.php"; //load in any variables
$DBC = mysqli_connect("localhost", DBUSER , DBPASSWORD, DBDATABASE);
 
//check if the connection was good
if (!$DBC) {
    echo "Error: Unable to connect to MySQL.\n". mysqli_connect_errno()."=".mysqli_connect_error() ;
    exit; //stop processing the page further
};
//insert DB code from here onwards

echo "<pre>";  
//prepare a query and send it to the server
$query = 'SELECT memberID,firstname,lastname,email FROM member';
$result = mysqli_query($DBC,$query);
 
//check result for data
if (mysqli_num_rows($result) > 0) {
	/* retrieve a row from the results
	   one at a time until no rows left in the result */
    echo "Record count: ".mysqli_num_rows($result).PHP_EOL;
    while ($row = mysqli_fetch_assoc($result)) {
	  echo "member ID ".$row['memberID'] . PHP_EOL;
	  echo "Firstname ".$row['firstname'] . PHP_EOL;
	  echo "Lastname ".$row['lastname'] . PHP_EOL;
	  echo "Email ".$row['email'] . PHP_EOL;
	  echo "<hr />";
   }
   mysqli_free_result($result); //free any memory used by the query
}
echo "</pre>";

/* show a quick confirmation that we have a connection
   this can be removed - not required for normal activities
*/
	echo "Connected via ".mysqli_get_host_info($DBC); //show some info on the connection 
 
mysqli_close($DBC); //close the connection once done

/* If you are connecting to the localhost, you need to change "127.0.0.1" to "localhost". 
Also if you are using MAMP with Mac, you need to add extension=mysqli to php.ini file 
which is located at /Applications/MAMP/conf/php7.4.2/php.ini.*/
?>
</body>
</html>
