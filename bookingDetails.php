<!DOCTYPE HTML>
<html>
    <head><title>View Booking</title></head>
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
$bookingId = $_GET['bookingId'];
if (empty($bookingId) or !is_numeric($bookingId)) {
    echo "<h2>Invalid booking id</h2>"; //simple error feedback
    exit;
} 
 
//prepare a query and send it to the server
$query = 'SELECT customer.lastname, customer.firstname, booking.telephone, booking.bookingdate, booking.people
FROM customer, booking  
WHERE booking.customerID = customer.customerID
AND booking.bookingID='.$bookingId;

$result = mysqli_query($DBC,$query);
$rowcount = mysqli_num_rows($result); 
?>
    <div id="pageHeader">
        <h1>Booking Details View</h1>
    </div>
    <div>
        <ul id="navigation">
            <li>
                <a href="bookingsListing.php">[Return to the bookings listing]</a>
            </li>
            <li>
                <a href="index.php">[Return to the main page]</a>
            </li>
        </ul>
    </div>
    <div id="bookingDetails">
        <?php
        //make sure we have the booking
        if ($rowcount > 0) {  
        echo "<fieldset><legend>Booking detail: #$bookingId</legend><dl>"; 
        $row = mysqli_fetch_assoc($result);
        echo "<dt>Booking date and time:</dt><dd>".$row['bookingdate']."</dd>".PHP_EOL;
        echo "<dt>Customer name:</dt><dd>".$row['lastname'].', '.$row['firstname']."</dd>".PHP_EOL;
        echo "<dt>Party size:</dt><dd>".$row['people']."</dd>".PHP_EOL;
        echo "<dt>Contact number</dt><dd>".$row['telephone']."</dd>".PHP_EOL;
        echo "</fieldset>";
        //what is this? ---- echo '</dl></fieldset>'.PHP_EOL;  
        } else echo "<h2>No booking found!</h2>"; //suitable feedback

        mysqli_free_result($result); //free any memory used by the query
        mysqli_close($DBC); //close the connection once done
        ?>
    </div>
</table>
</body>
</html>