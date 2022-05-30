<!DOCTYPE HTML>
<html><head><title>Browse bookings</title> </head>
<body>
<?php
include "checksession.php";
// Check if user is logged in; if not, redirect to login page.
checkUser(); 
loginStatus(); 

include "config.php"; //load in any variables
$DBC = mysqli_connect("localhost", DBUSER, DBPASSWORD, DBDATABASE);
 
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
    exit;
}

$customerId = $_SESSION['userid'];
$query = 'SELECT booking.bookingID, customer.lastname, customer.firstname, booking.telephone, booking.bookingdate, booking.people
FROM customer, booking  
WHERE booking.customerID = customer.customerID';

// If not an admin, then can only view bookings for the logged in customer
if (!isAdmin()) {
    $query .= " AND customer.customerID=".$customerId;
}

$result = mysqli_query($DBC, $query);
$rowcount = mysqli_num_rows($result); 

/* turnoff PHP to use some HTML - this quicker to do than php echos,
   we have an example of embedding php in small parts – see member count below
*/
?>
    <div id="pageHeader">
        <h1>Current Bookings</h1>
    </div>
    <div>
        <ul id="navigation">
            <li>
                <?php
                    echo '<a href="makeBooking.php?customerId='.$customerId.'">[Make a booking]</a>'
                ?>
            </li>
            <li>
                <a href="index.php">[Return to the main page]</a>
            </li>
        </ul>
    </div>
    <table id="bookingTable" border="1">
        <input type="text" name="search" id="search" placeholder="Search...">
        <thead>
            <tr>
                <th col-index = 1 rowspan="2">Booking (date & time, people)
                    <select class="table-filter">
                        <option value="all"></option>
                    </select>
                </th>
                <th col-index = 2 rowspan="2">Customer (Telephone)
                    <select class="table-filter">
                        <option value="all"></option>
                    </select>
                </th>

                <th rowspan="2">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
         //makes sure we have members
        if ($rowcount > 0) {          
            while ($row = mysqli_fetch_assoc($result)) {
                $bookingId = $row['bookingID'];
                echo '<tr><td>'.$row['bookingdate'].' ('.$row['people'].')</td><td>'.$row['lastname'].', '.$row['firstname'].' [T: '.$row['telephone'].']</td>';
                echo     '<td><a href="bookingDetails.php?bookingId='.$bookingId.'">[view]</a>';
                echo     '<a href="editBooking.php?bookingId='.$bookingId.'">[edit]</a>';
                echo     '<a href="deleteBooking.php?bookingId='.$bookingId.'">[delete]</a></td>';
                echo '</tr>'.PHP_EOL;
             }
          } else echo "<h2>No bookings found!</h2>"; //suitable feedback

          mysqli_free_result($result); 
          mysqli_close($DBC);
        ?>
        </tbody>
    </table>
</body>
</html>
