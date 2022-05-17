<!DOCTYPE HTML>
<html><head><title>Browse orders</title> </head>
<body>
<?php
include "config.php"; //load in any variables
$DBC = mysqli_connect("localhost", DBUSER, DBPASSWORD, DBDATABASE);
 
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
    exit;
}


 
$query = 'SELECT orders.orderID, booking.bookingDate, customer.lastname, customer.firstname
FROM orders, customer, booking  
WHERE orders.bookingID = booking.bookingID
AND booking.customerID = customer.customerID';
$result = mysqli_query($DBC,$query);
$rowcount = mysqli_num_rows($result); 
/* turnoff PHP to use some HTML - this quicker to do than php echos,
   we have an example of embedding php in small parts – see member count below
*/
?>
<h1>Current Orders</h1>
<h2>Order count <?php echo $rowcount; ?></h2>
<h2><a href='placeOrder.php'>[Place an order]</a></h2>
<h2><a href='index.php'>[Return to main page]</a></h2>
<table border="1">
<thead><tr><th>Orders (Date of order, Order number)</th><th>Customer</th><th>Action</th></tr></thead>
<?php
 
//makes sure we have members
if ($rowcount > 0) {  
    while ($row = mysqli_fetch_assoc($result)) {
	  $id = $row['orderID']; /*check orderID is the right place. bookingdate is from booking,
      orderID could be orderlinesID instead. first and lastname from customer */	
	  echo '<tr><td>'.$row['bookingDate'] .$row['orderID'].'</td><td>'.$row['lastname'].', '.$row['firstname'].'</td>';
      echo     '<td><a href="viewOrder.php?id='.$id.'">[view]</a>';
	  echo     '<a href="editOrder.php?id='.$id.'">[edit]</a>';
	  echo     '<a href="deleteOrder.php?id='.$id.'">[delete]</a></td>';
      echo '</tr>'.PHP_EOL;
   }
} else echo "<h2>No orders found!</h2>"; //suitable feedback
 
mysqli_free_result($result); 
mysqli_close($DBC);
?>
</table>
</body>
</html>