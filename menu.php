<!-- menu start -->
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>blog - Frozen Yogurt Shop</title>
	<link rel="stylesheet" type="text/css" href="converted_template\css\style.css">
	<link rel="stylesheet" type="text/css" href="converted_template\css\mobile.css">
	<script src="js/mobile.js" type="text/javascript"></script>
</head>
<?php
$customerId = $_SESSION['userid'];
?>
<div id="header">
	<div>
		<a href="index.html" class="logo"><img src="images/logo.png" alt=""></a>
		<ul id="navigation">
			<li class="menu selected">
				<a href="index.php">Home</a>
			</li>
			<li class="menu">
				<!-- there currently doesn't exist a pizza list page, so this is a place holder -->
				<a href="pizzalist.php">Pizzas</a>
				<ul class="primary">
					<li><a href="pizzalist.php">Pizza Menu</a></li>
					<?php
						echo '<a href="placeOrder.php?customerId='.$customerId.'">Place Order</a>';
					?>
				</ul>

			</li>
			<li class="menu">
				<a href="about.php">About</a>
				<ul class="primary">
					<li><a href="about.php">About Us</a></li>
					<li><a href="contact.php">Contact Us</a></li>
					<li><a href="http://www.freewebsitetemplates.com/about/terms/">Terms of Use</a></li>
					<li><a href="privacy.php">Privacy Policy</a></li>
				</ul>
			</li>
			<li class="menu">
				<a href="login.php">Login</a>
				<ul class="secondary">
					<li>
						<a href="login.php">Sign in to Order</a>
						<a href="registermember.php">Register</a>
					</li>
				</ul>
			</li>

			<li class="menu">
				<a href="login.php">Admin</a>
				<ul class="secondary">
					<li><a href="currentBookings.php">Reservations</a></li>
					<li><a href="currentOrders.php">Orders</a></li>
					<li><a href="listitems.php">Products</a></li>
					<li><a href="listcustomers.php">Customers</a></li>
				</ul>
			</li>
		</ul>
	</div>
</div>
<!-- menu end -->