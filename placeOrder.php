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

    if (isset($_POST['customerId']) and !empty($_POST['customerId']) and is_integer(intval($_POST['customerId']))) {
        $id = cleanInput($_POST['customerId']);
    } else {
        $error++; //bump the error flag
        $msg .= 'Invalid customer ID '; //append error message
        $id = 0;
    }

    //date and time
    if (isset($_POST['orderDate']) and !empty($_POST['orderDate'])) { //must have decimal
        $date = cleanInput($_POST['orderDate']);  
    } else {
        $error++; //bump the error flag
        $msg .= 'Invalid order date and time '; //append eror message
        $date = '';  
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

$customerId = $_SESSION['userid'];
$query = 'SELECT customer.lastname, customer.firstname
          FROM customer
          WHERE customerID='.$id;

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
            <input type="hidden" name="customerId" value="<?php echo $customerId; ?>">
            <label for="orderDate">Order for (date & time):</label>
            <input id="orderDate" name="orderDate" required>
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
            <div id="cancel">
                <a href="placeOrder.html">[Cancel]</a>
            </div>
        </form>
    </div>
    <script src="js/placeOrder.js"></script>
    <script>
        config = {
            enableTime: true,
            dateFormat: "Y-m-d H:1"
        }
        flatpickr("#orderDate", { config });
        console.log(config)
    </script>
<?php
} else {
    echo "<h2>Could not retrieve customer information</h2>"; //simple error feedback
}
mysqli_close($DBC); //close the connection once done
?>
</body>
</html>