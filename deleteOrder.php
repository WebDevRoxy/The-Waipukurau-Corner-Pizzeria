<!DOCTYPE HTML>
<html><head><title>Delete Order</title> </head>
 <body>

<?php
include "config.php"; //load in any variables
$DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE);

//insert DB code from here onwards
//check if the connection was good
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
    exit; //stop processing the page further
}

//function to clean input but not validate type and content
function cleanInput($data) {  
  return htmlspecialchars(stripslashes(trim($data)));
}

//retrieve the itemid from the URL
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];
    if (empty($id) or !is_numeric($id)) {
        echo "<h2>Invalid Order ID</h2>"; //simple error feedback
        exit;
    } 
}

if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Delete')) {     
    if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
        $id = cleanInput($_POST['id']);
    } else {
        $error++; //bump the error flag
        $msg .= 'Invalid order ID '; //append error message
        $id = 0;
    }
    $query = "DELETE FROM orderlines WHERE orderID=?";
    $stmt = mysqli_prepare($DBC,$query); //prepare the query
    mysqli_stmt_bind_param($stmt,'i', $id); 
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $query = "DELETE FROM orders WHERE orderID=?";
    $stmt = mysqli_prepare($DBC,$query); //prepare the query
    mysqli_stmt_bind_param($stmt,'i', $id); 
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);  
    echo "<h2>Order details deleted.</h2>";        
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
<h1>Food item details preview before deletion</h1>
<h2><a href='currentOrders.php'>[Return to the Orders listing]</a><a href='index.php'>[Return to the main page]</a></h2>
<?php

//makes sure we have the food item
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
   ?><form method="POST" action="deleteOrder.php">
     <h2>Are you sure you want to delete this Order?</h2>
     <input type="hidden" name="id" value="<?php echo $id; ?>">
     <input type="submit" name="submit" value="Delete">
     <a href="currentOrders.php">[Cancel]</a>
     </form>
<?php    
} else echo "<h2>No Order found, possbily deleted!</h2>"; //suitable feedback

mysqli_free_result($result); //free any memory used by the query
mysqli_close($DBC); //close the connection once done
?>
</table>
</body>
</html>
