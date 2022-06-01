<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>blog - Frozen Yogurt Shop</title>
	<link rel="stylesheet" type="text/css" href="converted_template\css\style.css">
	<link rel="stylesheet" type="text/css" href="converted_template\css\mobile.css">
	<script src="js/mobile.js" type="text/javascript"></script>
</head>
<?php
include "header.php";
include "checksession.php";

include "menu.php";
//----------- page content starts here

?>
		<div id="body" class="home">			
            <div class="header">
				<img src="images/bg-home.jpg" alt="">
				<div>
					<a href="product.html">Seafood Delight</a>
				</div>
			</div>
			<div class="body">
				<div>
					<div>
						<h1>NEW PRODUCT</h1>
						<h2>The Tasty Calzone Pizza</h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean eget lacinia sem. Quisque a libero semper, efficitur ante quis, molestie erat. Phasellus ut turpis libero. Nulla ex est, tristique et nunc id, interdum dignissim nunc. Proin eget ipsum ipsum. Nunc a lectus et neque scelerisque consectetur. Nulla facilisi. </p>
					</div>
					<img src="images/calzone.jpg" alt="">
				</div>
			</div>
			<div class="footer">
				<div>
					<ul>
						<li>
							<a href="product.html" class="product"></a>
							<h1>Pizzas</h1>
						</li>
						<li>
							<a href="about.html" class="about"></a>
							<h1>ABOUT US</h1>
						</li>
						<li>
							<a href="product.html" class="flavor"></a>
							<h1>RESERVATIONS</h1>
						</li>
						<li>
							<a href="contact.html" class="contact"></a>
							<h1>CONTACT</h1>
						</li>
					</ul>
				</div>
			</div>
        </div>            
<?php
//----------- page content ends here
include "footer.php";
?>
