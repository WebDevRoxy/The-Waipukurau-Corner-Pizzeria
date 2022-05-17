<!DOCTYPE HTML>
<html><head><title>Browse members</title> </head>
<body>
<?php
include "config.php"; //load in any variables
$DBC = mysqli_connect("localhost", DBUSER, DBPASSWORD, DBDATABASE);
 
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
    exit;
}
 
$query = 'SELECT memberID,firstname,lastname FROM member ORDER BY lastname';
$result = mysqli_query($DBC,$query);
$rowcount = mysqli_num_rows($result); 
/* turnoff PHP to use some HTML - this quicker to do than php echos,
   we have an example of embedding php in small parts – see member count below
*/
?>
<h1>Member list</h1>
<h2>Member count <?php echo $rowcount; ?></h2>
<h2><a href='registermember.php'>[Create new member]</a></h2>
<table border="1">
<thead><tr><th>Firstname</th><th>Lastname</th><th>actions</th></tr></thead>
<?php
 
//makes sure we have members
if ($rowcount > 0) {  
    while ($row = mysqli_fetch_assoc($result)) {
	  $id = $row['memberID'];	
	  echo '<tr><td>'.$row['firstname'].'</td><td>'.$row['lastname'].'</td>';
        echo     '<td><a href="viewmember.php?id='.$id.'">[view]</a>';
	  echo     '<a href="editmember.php?id='.$id.'">[edit]</a>';
	  echo     '<a href="deletemember.php?id='.$id.'">[delete]</a></td>';
      echo '</tr>'.PHP_EOL;
   }
} else echo "<h2>No members found!</h2>"; //suitable feedback
 
mysqli_free_result($result); 
mysqli_close($DBC);
?>
</table>
</body>
</html>