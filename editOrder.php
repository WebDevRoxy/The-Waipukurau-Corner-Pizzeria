<!DOCTYPE HTML>
<html>

<head>
    <title>Edit an order</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>

<body>

    <?php
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
        //validate incoming data - only the first field is done for you in this example - rest is up to you do

        //refer to additems for extend validation examples
        //itemID (sent via a form it is a string not a number so we try a type conversion!)    
        if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
            $id = cleanInput($_POST['id']);
        } else {
            $error++; //bump the error flag
            $msg .= 'Invalid order ID '; //append error message
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
            $ex = cleanInput($_POST['extras']);
            $extras = (strlen($ex) > 200) ? substr($ex, 0, 200) : $ex; //check length and clip if too big   
            //we would also do context checking here for contents, etc  
        } else {
            $error++; //bump the error flag
            $msg .= 'Invalid extras  '; //append eror message
            $extras = '';
        }

        //dropdowns
        if (isset($_POST['pizzaList']) and !empty($_POST['pizzaList']) ) {
            $pizza = cleanInput($_POST['pizzaList']);
        } else {
            $error++; //bump the error flag
            $msg .= 'Invalid pizza  '; //append eror message
            $pizza = '';
        }

        //pizza quantity
        if (isset($_POST['pizzaQuantity']) and !empty($_POST['pizzaQuantity']) and is_integer(intval($_POST['pizzaQuantity']))) {
            $pizzaQuantity = cleanInput($_POST['pizzaQuantity']);
            //make it so you can only enter between 1 and 10
        } else {
            $error++; //bump the error flag
            $msg .= 'Invalid pizza quantity '; //append eror message
            $quantity = 0;
        }

        //save the item data if the error flag is still clear and item id is > 0
        if ($error == 0 and $id > 0) {
            $query = "UPDATE orderlines, fooditems
                      SET orderlines.itemID = fooditems.itemID,
                          orderlines.pizzaQuantity = ?
                      WHERE orderlines.orderID = ? and fooditems.pizza = ?";
            $stmt = mysqli_prepare($DBC, $query); //prepare the query
            mysqli_stmt_bind_param($stmt, 'iis', $pizzaQuantity, $id, $pizza);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            echo "<h2>Order details updated.</h2>";
            //        header('Location: http://localhost/bit608/listitems.php', true, 303);      
        } else {
            echo "<h2>$msg</h2>" . PHP_EOL;
        }
    }
    //locate the food item to edit by using the itemID
    //we also include the item ID in our form for sending it back for saving the data

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

        <form method="POST" action="editOrder.php">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <p>
                <label for="orderDate">Order for (date & time):</label>
                <input id="orderDate" name="orderDate" value="<?php echo $row['bookingdate']; ?>" required>
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
                            <label for="pizzaList">Pizza: </label>
                            <input list="pizzaList" id="pizzalist_<?php $i ?>" name="pizzaList_<?php $i ?>" placeholder="Pizza" required onclick="javascript: this.value = ''" value="<?php echo $row['pizza']; ?>">
                            <label for="pizzaQuantity_<?php $i ?>">Number: </label>
                            <input type="number" name="pizzaQuantity_<?php $i ?>" id="pizzaQuantity_<?php $i ?>" required min="0" max="10" value="<?php echo $row['pizzaQuantity']; ?>">
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
            <input type="submit" name="submit" value="Update">
            <a href="currentOrders.php">[Cancel]</a>
        </form>

        <script>
            config = {
                enableTime: true,
                dateFormat: "Y-m-d H:1"
            }
            flatpickr("#orderDate", {
                config
            });
            console.log(config)
        </script>
    <?php
    } else {
        echo "<h2>Order not found with that ID</h2>"; //simple error feedback
    }
    mysqli_close($DBC); //close the connection once done
    ?>
</body>

</html>