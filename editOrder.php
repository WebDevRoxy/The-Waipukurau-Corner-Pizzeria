<!DOCTYPE HTML>
<html>

<head>
    <title>Edit an order</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>

<body>
    <?php

    include "checksession.php";
    // Check if user is logged in; if not, redirect to login page.
    checkUser(); 

    echo "Logged in as ".$_SESSION['username'];

    include "config.php"; //load in any variables
    $DBC = mysqli_connect("localhost", DBUSER, DBPASSWORD, DBDATABASE);

    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL. ".mysqli_connect_error();
        exit; //stop processing the page further
    };

    //function to clean input but not validate type and content
    function cleanInput($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    //retrieve the itemid from the URL
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $id = $_GET['id'];
        if (empty($id) or !is_numeric($id)) {
            echo "<h2>Invalid order ID</h2>"; //simple error feedback
            exit;
        }
    }

    //check if we are saving data first by checking if the submit button exists in the array
    if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Update')) {

        $error = 0; //clear our error flag

        if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
            $id = cleanInput($_POST['id']);
        } else {
            $error++; //bump the error flag
            $msg .= 'Invalid order ID '; //append error message
            $id = 0;
        }

        //extras
        if (isset($_POST['extras']) and is_string($_POST['extras'])) {
            $ex = cleanInput($_POST['extras']);
            $extras = (strlen($ex) > 200) ? substr($ex, 0, 200) : $ex; //check length and clip if too big   
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

        //save the item data if the error flag is still clear 
        if ($error == 0) {
            // First delete the existing orderlines corresponding with the order
            $query = "DELETE FROM orderlines WHERE orderID=?";
            $stmt = mysqli_prepare($DBC, $query); //prepare the query
            mysqli_stmt_bind_param($stmt,'i', $id); 
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // Now insert the new/changed orderlines
            for($i = 0; $i < count($pizzas); $i++) {
                $pizza = $pizzas[$i];
                $quantity = $quantities[$i];
    
                $query = "INSERT INTO orderlines (orderID, itemId, pizzaQuantity, extras) 
                          SELECT ?, itemID, ?, ?
                          FROM fooditems
                          WHERE pizza = ?";
                $stmt = mysqli_prepare($DBC, $query); //prepare the query
                mysqli_stmt_bind_param($stmt,'iiss', $id, $quantity, $extras, $pizza); 
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }    
            echo "<h2>Order details updated.</h2>";
        } else {
            echo "<h2>$msg</h2>" . PHP_EOL;
        }
    }

    $query = 'SELECT orders.orderID, booking.bookingdate, customer.lastname, customer.firstname, orderlines.extras,
        fooditems.pizza, orderlines.pizzaQuantity
    FROM orders, customer, booking, orderlines, fooditems
    WHERE orders.bookingID = booking.bookingID
    AND booking.customerID = customer.customerID 
    AND orders.orderID = orderlines.orderID
    AND orderlines.itemID = fooditems.itemID 
    AND orders.orderID='.$id;

    $result = mysqli_query($DBC, $query);
    $rowcount = mysqli_num_rows($result);
    if ($rowcount > 0) {
        $row = mysqli_fetch_assoc($result);
    ?>
        <h1>Pizza Orders Details Update</h1>
        <h2><a href='currentOrders.php'>[Return to the orders listing]</a><a href='index.php'>[Return to the main page]</a></h2>

        <form id="pizzaOrderForm" method="POST" action="editOrder.php">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <p>
                <label for="orderDate">Order for (date & time):</label>
                <input id="orderDate" readonly="true" name="orderDate" value="<?php echo $row['bookingdate']; ?>">
            </p>
            <p>
                Pizza order for customer: <?php echo $row['lastname']; ?>, <?php echo $row['firstname']; ?>
            </p>
            <p>
                <label for="extras">Extras:</label>
                <input type="text" name="extras" id="extras" maxlength="100" value="<?php echo $row['extras']; ?>">
            </p>
            <p>
            <ol id="olContainer">
                <?php
                    // fetch all the pizzas associated with the order
                    for ($i = 1; $i <= $rowcount; $i++) {
                ?>  
                    <li class="pizzaSelection">
                        Pizza: <input list="pizzaList" name="pizza[]" placeholder="Pizza" required onclick="javascript: this.value = ''" value="<?php echo $row['pizza']; ?>">
                        Number: <input type="number" name="quantity[]" required min="0" max="12" value="<?php echo $row['pizzaQuantity']; ?>">
                        Delete Item: <input type="checkbox" name="delete" onclick='DeletePizza(this)'>
                    </li>
                <?php
                        $row = mysqli_fetch_assoc($result);
                    }
                ?>
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
            </p>

            <button id="addPizzaBtn">Add Pizza</button>

            <input type="submit" name="submit" value="Update">
            <a href="currentOrders.php">[Cancel]</a>
        </form>

        <script src="js/placeOrder.js"></script>
    <?php
    } else {
        echo "<h2>Order not found with that ID</h2>"; //simple error feedback
    }
    mysqli_close($DBC); //close the connection once done
    ?>
</body>

</html>