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

<body>
<?php

include "checksession.php";
// Check if user is logged in; if not, redirect to login page.
checkUser(); 
loginStatus(); ;

include "config.php"; //load in any variables
$DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. ".mysqli_connect_error();
    exit; //stop processing the page further
};

//function to clean input but not validate type and content
function cleanInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

//retrieve the booking Id from the URL
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $bookingId = $_GET['bookingId'];
    if (empty($bookingId) or !is_numeric($bookingId)) {
        echo "<h2>Invalid booking ID</h2>"; //simple error feedback
        exit;
    }
}

//check if we are saving data first by checking if the submit button exists in the array
if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Update')) {

    $error = 0; //clear our error flag

    if (isset($_POST['bookingId']) and !empty($_POST['bookingId']) and is_integer(intval($_POST['bookingId']))) {
        $bookingId = cleanInput($_POST['bookingId']);
    } else {
        $error++; //bump the error flag
        $msg .= 'Invalid booking ID '; //append error message
    }

    if (isset($_POST['bookingDate']) and !empty($_POST['bookingDate']) and is_string($_POST['bookingDate'])) {
        $bookingdate = cleanInput($_POST['bookingDate']);
    } else {
        $error++; //bump the error flag
        $msg .= 'Invalid booking date '; //append error message
    }

    if (isset($_POST['contactNumber']) and !empty($_POST['contactNumber']) and is_string($_POST['contactNumber'])) {
        $telephone = cleanInput($_POST['contactNumber']);
    } else {
        $error++; //bump the error flag
        $msg .= 'Invalid contact number '; //append error message
    }

    if (isset($_POST['partySize']) and !empty($_POST['partySize']) and is_integer(intval($_POST['partySize']))) {
        $people = cleanInput($_POST['partySize']);
    } else {
        $error++; //bump the error flag
        $msg .= 'Invalid party size '; //append error message
    }
    
    //save the booking data if the error flag is still clear 
    if ($error == 0) {

        $query = "UPDATE booking 
                  SET telephone = ?, 
                      bookingdate = ?, 
                      people = ?
                  WHERE
                      bookingId = ?";

        $stmt = mysqli_prepare($DBC, $query); //prepare the query
        mysqli_stmt_bind_param($stmt,'ssii', $telephone, $bookingdate, $people, $bookingId); 
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        echo "<h2>Booking details updated.</h2>";
    } else {
        echo "<h2>$msg</h2>" . PHP_EOL;
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
    <div id="pageHeader">
        <h1>Edit a booking</h1>
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
    <?php
    if ($rowcount > 0) {
        $row = mysqli_fetch_assoc($result);
    ?>
    <div id="testBooking">
        <div id="bookingHeader">
            <h2>Booking made for <?php echo $row['lastname'].', '.$row['firstname']; ?></h2>
        </div>
        <form id="bookingForm" method="POST" action="editBooking.php">
            <input type="hidden" name="bookingId" value="<?php echo $bookingId; ?>">
            
            <p>
                <label for="bookingDate">Booking date & time:</label>
                <input type="datetime" name="bookingDate" id="bookingDate" placeholder="yyyy-mm-dd HH:MM" value="<?php echo $row['bookingdate']; ?>" required>
            </p>

            <p>
                <label for="contactNumber">Contact number:</label>
                <input type="tel" name="contactNumber" id="contactNumber" placeholder="###-###-####" value="<?php echo $row['telephone']; ?>" required pattern="^{[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*}$">
            </p>

            <p>
                <label for="partySize">Party size (# people, 1-10):</label>
                <input type="number" name="partySize" id="partySize" value="<?php echo $row['people']; ?>" required min="1" max="10">
            </p>

            <input type="submit" name="submit" value="Update">
            <a href="currentBookings.php">[Cancel]</a>
        </form>
    <?php
    } else {
        echo "<h2>No booking found with that ID</h2>"; //simple error feedback
    }
    mysqli_close($DBC); //close the connection once done
    ?>
</body>

</html>
