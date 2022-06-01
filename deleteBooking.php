<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Waipukurau Corner Pizzeria</title>
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="css/mobile.css" type="text/css">
    <script src="js/mobile.js" type="text/javascript"></script>
</head>

<?php
include "checksession.php";
// Check if user is logged in; if not, redirect to login page.
checkUser(); 
loginStatus(); 

include "config.php"; //load in any variables
$DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
 
//insert DB code from here onwards
//check if the connection was good
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
    exit; //stop processing the page further
}
 
//retrieve the booking id from the URL
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $bookingId = $_GET['bookingId'];
    if (empty($bookingId) or !is_numeric($bookingId)) {
        echo "<h2>Invalid Booking ID</h2>"; //simple error feedback
        exit;
    } 
}

//function to clean input but not validate type and content
function cleanInput($data) {  
    return htmlspecialchars(stripslashes(trim($data)));
}

if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Delete')) {     

    $error = 0; //clear error flag
    $msg = 'Error: '; 

    if (isset($_POST['bookingId']) and !empty($_POST['bookingId']) and is_integer(intval($_POST['bookingId']))) {
        $bookingId = cleanInput($_POST['bookingId']);
    } else {
        $error++; //bump the error flag
        $msg .= 'Invalid booking ID '; //append error message
        $bookingId = 0;
    }

    if ($error == 0 and $bookingId > 0) {
        //now delete the booking
        $query = "DELETE FROM booking WHERE booking.bookingID=?";
        $stmt = mysqli_prepare($DBC,$query); //prepare the query
        mysqli_stmt_bind_param($stmt,'i',$bookingId); 
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);  

        echo "<h2>Booking deleted.</h2>";   
    } else { 
        echo "<h2>$msg</h2>".PHP_EOL;
    }  
}
 
//prepare a query and send it to the server
$query = 'SELECT customer.lastname, customer.firstname, booking.telephone, booking.bookingdate, booking.people
FROM customer, booking  
WHERE booking.customerID = customer.customerID
AND booking.bookingID='.$bookingId;

$result = mysqli_query($DBC,$query);
$rowcount = mysqli_num_rows($result); 
?>
<body>
    <div id="pageHeader">
        <h1>Booking Preview Before Deletion</h1>
    </div>
    <div>
        <ul id="navigation">
            <li>
                <a href="currentBookings.php">[Return to the bookings listing]</a>
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
        } else echo "<h2>No booking found! Possibly deleted.</h2>"; //suitable feedback

        mysqli_free_result($result); //free any memory used by the query
        mysqli_close($DBC); //close the connection once done
        ?>
    </div>
    <form method="POST" action="deleteBooking.php">
        <div id="deleteBooking">
            <h2>Are you sure you want to delete this booking?</h2>
            <input type="hidden" name="bookingId" value="<?php echo $bookingId; ?>">
            <input type="submit" name="submit" value="Delete">
            <a href="currentBookings.php">[Cancel]</a>
        </div>
    </form>
<?php   
    mysqli_free_result($result); //free any memory used by the query
    mysqli_close($DBC); //close the connection once done
?>
</body>

</html>