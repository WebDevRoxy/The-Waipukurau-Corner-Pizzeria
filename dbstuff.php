<!DOCTYPE HTML>
<html><head><title>MySQL examples</title> </head>
<body>
<?php
include "config.php"; //load in any variables
$DBC = mysqli_connect(DBHOST, DBUSER , DBPASSWORD, DBDATABASE); //db ip: http://185.27.134.10/
 
//check if the connection was good
if (!$DBC) {
    echo "Error: Unable to connect to MySQL.\n". mysqli_connect_errno()."=".mysqli_connect_error() ;
    exit; //stop processing the page further
};
//insert DB code from here onwards
/* show a quick confirmation that we have a connection
   this can be removed - not required for normal activities
*/
	echo "Connected via ".mysqli_get_host_info($DBC); //show some info on the connection 
 
mysqli_close($DBC); //close the connection once done

/* If you are connecting to the localhost, you need to change DBHOST to DBHOST. 
Also if you are using MAMP with Mac, you need to add extension=mysqli to php.ini file 
which is located at /Applications/MAMP/conf/php7.4.2/php.ini.*/
?>
</body>
</html>
