<!DOCTYPE HTML>
<html><head><title>View Order</title> </head>
 <body>
 
<?php
include "checksession.php";
// Check if user is logged in; if not, redirect to login page.
checkUser(); 

echo "Logged in as ".$_SESSION['username'];

include "config.php"; //load in any variables
$DBC = mysqli_connect("localhost", DBUSER, DBPASSWORD, DBDATABASE);
 
//insert DB code from here onwards
//check if the connection was good
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
    exit; //stop processing the page further
}
 
//do some simple validation to check if id exists
$id = $_GET['id'];
if (empty($id) or !is_numeric($id)) {
 echo "<h2>Invalid orderID</h2>"; //simple error feedback
 exit;
} 
 
//prepare a query and send it to the server
//NOTE for simplicity purposes ONLY we are not using prepared queries
//make sure you ALWAYS use prepared queries when creating custom SQL like below
$query = 'SELECT customer.customerID, orders.orderID, booking.bookingDate, customer.lastname, customer.firstname, fooditems.pizza, orderlines.orderlinesID, orderlines.pizzaQuantity, orderlines.extras
FROM orders, customer, booking, fooditems, orderlines
WHERE orders.bookingID = booking.bookingID
AND booking.customerID = customer.customerID 
AND orders.orderID = orderlines.orderID
AND fooditems.itemID = orderlines.itemID 
AND orders.orderID='.$id;

$result = mysqli_query($DBC,$query);
$rowcount = mysqli_num_rows($result); 
?>
<h1>Order details view</h1>
<h2><a href='currentOrders.php'>[Return to the Orders listing]</a><a href='index.php'>[Return to the main page]</a></h2>
<?php
 
//makes sure we have the order
if ($rowcount > 0) {  
   echo "<fieldset><legend>Pizza order detail for order #$id</legend><dl>"; 
   $row = mysqli_fetch_assoc($result);
   echo "<dt>Date & time ordered for:</dt><dd>".$row['bookingDate']."</dd>".PHP_EOL;
   echo "<dt>Customer name:</dt><dd>".$row['lastname'].', '.$row['firstname']."</dd>".PHP_EOL;
   echo "<dt>Extras:</dt><dd>".$row['extras']."</dd>".PHP_EOL;
   echo "<dt>Pizzas:</dt>";
   do {
       echo "<dd>".$row['pizza'].' x '.$row['pizzaQuantity']."</dd>".PHP_EOL; 
   } while ($row = mysqli_fetch_assoc($result));
   echo "</fieldset>";
   //what is this? ---- echo '</dl></fieldset>'.PHP_EOL;  
} else echo "<h2>No member found!</h2>"; //suitable feedback
 
mysqli_free_result($result); //free any memory used by the query
mysqli_close($DBC); //close the connection once done
?>
</table>
</body>
</html>