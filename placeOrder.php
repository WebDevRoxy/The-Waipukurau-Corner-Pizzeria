<!DOCTYPE HTML>
<html><head><title>Place Order</title> </head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<body>

<?php

include "checksession.php";
// Check if user is logged in; if not, redirect to login page.
checkUser(); 

echo "Logged in as ".$_SESSION['username'];

include "config.php"; //load in any variables

// Connect to database
$DBC = mysqli_connect("localhost", DBUSER, DBPASSWORD, DBDATABASE);
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
    exit; //stop processing the page further
};

//function to clean input but not validate type and content
function cleanInput($data) {  
  return htmlspecialchars(stripslashes(trim($data)));
}

//the data was sent using a formtherefore we use the $_POST instead of $_GET
//check if we are saving data first by checking if the submit button exists in the array
if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Place Order')) {
  
    $error = 0; //clear our error flag

    if (isset($_POST['bookingId']) and !empty($_POST['bookingId']) and is_integer(intval($_POST['bookingId']))) {
        $bookingId = cleanInput($_POST['bookingId']);
    } else {
        $error++; //bump the error flag
        $msg .= 'Invalid booking ID '; //append error message
        $id = 0;
    }

    //extras
    if (isset($_POST['extras']) and is_string($_POST['extras'])) {
        $fn = cleanInput($_POST['extras']);        
        $extras = (strlen($fn)>200)?substr($fn,0,200):$fn; //check length and clip if too big   
        //we would also do context checking here for contents, etc  
    } else {
        $error++; //bump the error flag
        $msg .= 'Invalid extras  '; //append eror message
        $extras = '';  
    }       

    // pizza dropdowns
    if (isset($_POST['pizza']) and !empty($_POST['pizza'])) {

        $pizzas = $_POST['pizza'];

        foreach($pizzas as $pizza) {
            $fn = cleanInput($pizza); 
            $pizza = (strlen($fn)>15)?substr($fn,1,15):$fn; //check length and clip if too big

            //we would also do context checking here for contents, etc     
            // if (!strpos($pizza, 'Margheritta' or 'Chorizo' or 'Pepperoni' or 'Carne'
            // or 'Salsiccia' or 'Calabrese' or 'Patate' or 'Salmon' or 'Pancetta' or 'Capricciosa')) {
            //     $error++; //bump the error flag
            //     $msg .=  ': Invalid pizza  '; //append eror message
            // } 
        }
    }

    //pizza quantity dropdowns
    if (isset($_POST['quantity']) and !empty($_POST['quantity'])) { 
        $quantities = $_POST['quantity'];

        foreach($quantities as $quantity) {
            if ($quantity < 1 || $quantity > 12) {
                $error++; //bump the error flag
                $msg .= ' Invalid pizza quantity  '; //append eror message
            }
        }       
    }

    if ($error == 0) {
        //Create the new order
        $query = "INSERT INTO orders (bookingID) VALUES (?)";
        $stmt = mysqli_prepare($DBC,$query); //prepare the query
        mysqli_stmt_bind_param($stmt,'i', $bookingId); 
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        //Get the newly created orderId
        $query = 'SELECT orderID
                  FROM orders
                  WHERE bookingID='.$bookingId;
        $result = mysqli_query($DBC, $query);
        $row = mysqli_fetch_assoc($result);
        $id = $row['orderID'];

        //Create the orderlines
        for($i = 0; $i < count($pizzas); $i++) {
            $pizza = $pizzas[$i];
            $quantity = $quantities[$i];

            $query = "INSERT INTO orderlines (orderID, itemId, pizzaQuantity, extras) 
                      SELECT ?, itemID, ?, ?
                      FROM fooditems
                      WHERE pizza = ?";
            $stmt = mysqli_prepare($DBC,$query); //prepare the query
            mysqli_stmt_bind_param($stmt,'iiss', $id, $quantity, $extras, $pizza); 
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }    
        echo "<h2>New order placed!</h2>";    

    } else { 
        echo "<h2>$msg</h2>".PHP_EOL;
    } 
}

/* Presumably the bookingId will need to be passed into the page later when the rest of the pages 
are completed. For now, just select the bookingId's from the booking table and choose the first one. */
$customerId = $_SESSION['userid'];
$query = "SELECT booking.bookingID, booking.bookingdate, customer.lastname, customer.firstname
          FROM customer, booking
          WHERE booking.customerID = customer.customerID 
          AND customer.customerID=".$customerId;
$result = mysqli_query($DBC, $query);
$rowcount = mysqli_num_rows($result);

if ($rowcount > 0) {
    $row = mysqli_fetch_assoc($result);
?>
    <h1>Place an Order</h1>
    <h2><a href='currentOrders.php'>[Return to the orders listing]</a><a href='index.php'>[Return to the main page]</a></h2>
    <div id="placeOrder">
        <div id="orderHeader">
            <h2>Pizza order for customer: <?php echo $row['lastname']; ?>, <?php echo $row['firstname']; ?></h2>
        </div>
        <form id="pizzaOrderForm" method="POST" action="placeOrder.php">
            <input type="hidden" name="bookingId" value="<?php echo $row['bookingID']; ?>">

            <label for="orderDate">Order for (date & time):</label>
            <input id="orderDate" readonly="true" name="orderDate" value="<?php echo $row['bookingdate']; ?>">

            <label for="extras">Extras:</label>
            <input type="text" name="extras" id="extras" maxlength="100">

            <hr>
            <h3>Pizzas for this order:</h3>
            <ol id="olContainer">
                <li class="pizzaSelection">
                    Pizza: <input list="pizzaList" name="pizza[]" placeholder="Pizza" required onclick="javascript: this.value = ''" >
                    Number: <input type="number" name="quantity[]" required min="0" max="12">
                    Delete Item: <input type="checkbox" name="delete" onclick='DeletePizza(this)'>
                </li>
            </ol>
            <datalist id="pizzaList">
                <option value="Margherita"></option>
                <option value="Chorizo"></option>
                <option value="Pepperoni"></option>
                <option value="Carne"></option>
                <option value="Salsiccia"></option>
                <option value="Calabrese"></option>
                <option value="Patate"></option>
                <option value="Salmon"></option>
                <option value="Pancetta"></option>
                <option value="Capricciosa"></option>
                <option value="Meatlovers"></option>
                <option value="Hawaiian"></option>
            </datalist>

            <button id="addPizzaBtn" disabled="true">Add Pizza</button>

            <input type="submit" name="submit" value="Place Order">
            <a href="currentOrders.php">[Cancel]</a>
        </form>
    </div>
    <script src="js/placeOrder.js"></script>
<?php
} else {
    echo "<h2>No booking has been made</h2>"; //simple error feedback
}
mysqli_close($DBC); //close the connection once done
?>
</body>
</html>