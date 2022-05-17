<!DOCTYPE HTML>
<html><head><title>Place Order</title> </head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
 <body>

<?php
//function to clean input but not validate type and content
function cleanInput($data) {  
  return htmlspecialchars(stripslashes(trim($data)));
}

//the data was sent using a formtherefore we use the $_POST instead of $_GET
//check if we are saving data first by checking if the submit button exists in the array
if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Add')) {
//if ($_SERVER["REQUEST_METHOD"] == "POST") { //alternative simpler POST test    
    include "config.php"; //load in any variables
    $DBC = mysqli_connect("localhost", DBUSER, DBPASSWORD, DBDATABASE);

    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
        exit; //stop processing the page further
    };

//validate incoming data - only the first field is done for you in this example - rest is up to you do
//food item name
    $error = 0; //clear our error flag


//date and time
if (isset($_POST['orderDate']) and !empty($_POST['orderDate']) and is_int($_POST['orderDate'])) { //must have decimal
    $date = cleanInput($_POST['orderDate']);  
 } else {
    $error++; //bump the error flag
    $msg .= 'Invalid order date and time '; //append eror message
    $date = '';  
 }       


//extras
if (isset($_POST['extras']) and !empty($_POST['extras']) and is_string($_POST['extras'])) {
    $fn = cleanInput($_POST['extras']);        
    $extras = (strlen($fn)>200)?substr($fn,0,200):$fn; //check length and clip if too big   
    //we would also do context checking here for contents, etc  
 } else {
    $error++; //bump the error flag
    $msg .= 'Invalid extras  '; //append eror message
    $extras = '';  
 }       


//dropdowns
if (isset($_POST['pizzaList']) and !empty($_POST['pizzaList']) and is_string($_POST['pizzaList'])) {
    $fn = cleanInput($_POST['pizzaList']); 
    $pizza = (strlen($fn)>15)?substr($fn,1,15):$fn; //check length and clip if too big

    //we would also do context checking here for contents, etc     
    $pizza = (strpos($pizza, 'Margheritta' or 'Chorizo' or 'Pepperoni' or 'Carne'
    or 'Salsiccia' or 'Calabrese' or 'Patate' or 'Salmon' or 'Pancetta' or 'Capricciosa'));$fn;
    
 } else {
    $error++; //bump the error flag
    $msg .= 'Invalid pizza  '; //append eror message
    $pizza = '';  
 } 

//pizza quantity
if (isset($_POST['pizza']) and !empty($_POST['pizza']) and is_int($_POST['pizza'])) { //must have decimal
    $date = cleanInput($_POST['pizza']);  

    //make it so you can only enter between 1 and 10
 } else {
    $error++; //bump the error flag
    $msg .= 'Invalid pizza quantity '; //append eror message
    $date = '';  
 }       



 // retrieve the orderID that has been created by locating the record with that customerID AND orderdate

$query = 'SELECT customer.customerID, orders.orderID, booking.bookingDate, customer.lastname, customer.firstname, fooditems.pizza, orderlines.orderlinesID
FROM orders, customer, booking, fooditems, orderlines
WHERE orders.bookingID = booking.bookingID
AND booking.customerID = customer.customerID 
AND orders.orderID = orderlines.orderID
AND fooditems.itemID = orderlines.itemID 
AND orders.orderID='.$id;


 //save the item data if the error flag is still clear
 //FOR EACH PIZZA INSERT NEW ORDERLINES ID

 /*echo "<pre>";
 foreach ($numbers as $key => $number) {//include the array key
  echo "Foreach $key - $number\n"; //$number will contain an element of the array 
 };
 echo "</pre>";ression as $key => $value)
 statement*/

/* alternative
<?php 
    $projects = array();
    while ($project =  mysql_fetch_assoc($records))
    {
        $projects[] = $project;
    }
    foreach ($projects as $project)
    {

*/



 if ($error == 0) {
    $query = "INSERT INTO orderlines (orderID) VALUES (?,?)";
    $stmt = mysqli_prepare($DBC,$query); //prepare the query
    mysqli_stmt_bind_param($stmt,'sssd', $date, $extras, $pizza); 
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);    
    echo "<h2>New order placed!</h2>";        
} else { 
  echo "<h2>$msg</h2>".PHP_EOL;
}      
mysqli_close($DBC); //close the connection once done
}

?>
<!--html-->
<div id="pageHeader">
        <h1>Place an Order</h1>
    </div>
    <div>
        <ul id="navigation">
            <li>
                <a href="ordersListing.html">[Return to the orders listing]</a>
            </li>
            <li>
                <a href="index.php">[Return to the main page]</a>
            </li>
        </ul>
    </div>
    <div id="placeOrder">
        <div id="orderHeader">
            <h2>Pizza order for customer Test</h2>
        </div>
        <form id="pizzaOrderForm" action="/order">
            <label for="orderDate">Order for (date & time):</label>
            <input id="orderDate" required>
            <label for="extras">Extras:</label>
            <input type="text" name="extras" id="extras" maxlength="100">
            <hr>
            <h3>Pizzas for this order:</h3>
            <ol id="olContainer">
                <li class="pizzaSelection">
                    Pizza: <input list="pizzaList" name="pizzaList" placeholder="Pizza" required onclick="javascript: this.value = ''" >
                    Number: <input type="number" name="pizza" id="pizza" required min="0" max="12">
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
</body>
</html>
  