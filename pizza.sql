DROP DATABASE IF EXISTS pizza;
CREATE DATABASE IF NOT EXISTS pizza;
USE pizza;

-- The items/products for the Pizzeria
DROP TABLE IF EXISTS fooditems;
CREATE TABLE IF NOT EXISTS fooditems (
  itemID int unsigned NOT NULL auto_increment,
  pizza varchar(15) NOT NULL, -- name of pizza
  description text default NULL, -- description of the ingredients
  pizzatype character default 'S', -- S - standard, V - vegetarian 
  price float NOT NULL, -- what does the pizza cost
  PRIMARY KEY (itemID)
) AUTO_INCREMENT=1;

INSERT INTO `fooditems` (`itemID`,`pizza`,`description`,`pizzatype`,`price`) VALUES (1,"Margherita","Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer aliquam adipiscing","S",10);
INSERT INTO `fooditems` (`itemID`,`pizza`,`description`,`pizzatype`,`price`) VALUES (2,"Chorizo","Lorem ipsum dolor sit amet, consectetuer","S",10);
INSERT INTO `fooditems` (`itemID`,`pizza`,`description`,`pizzatype`,`price`) VALUES (3,"Pepperoni","Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur","S",6);
INSERT INTO `fooditems` (`itemID`,`pizza`,`description`,`pizzatype`,`price`) VALUES (4,"Carne","Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer aliquam","S",6);
INSERT INTO `fooditems` (`itemID`,`pizza`,`description`,`pizzatype`,`price`) VALUES (5,"Salsiccia","Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer aliquam adipiscing lacus.","S",8);
INSERT INTO `fooditems` (`itemID`,`pizza`,`description`,`pizzatype`,`price`) VALUES (6,"Calabrese","Lorem ipsum dolor sit amet, consectetuer adipiscing","S",7);
INSERT INTO `fooditems` (`itemID`,`pizza`,`description`,`pizzatype`,`price`) VALUES (7,"Patate","Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer aliquam adipiscing lacus.","S",8);
INSERT INTO `fooditems` (`itemID`,`pizza`,`description`,`pizzatype`,`price`) VALUES (8,"Salmon","Lorem ipsum dolor sit amet,","S",9);
INSERT INTO `fooditems` (`itemID`,`pizza`,`description`,`pizzatype`,`price`) VALUES (9,"Pancetta","Lorem ipsum dolor sit","V",9);
INSERT INTO `fooditems` (`itemID`,`pizza`,`description`,`pizzatype`,`price`) VALUES (10,"Capricciosa","Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer","V",6);
INSERT INTO `fooditems` (`itemID`,`pizza`,`description`,`pizzatype`,`price`) VALUES (11,"Meatlovers","Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur","V",6);
INSERT INTO `fooditems` (`itemID`,`pizza`,`description`,`pizzatype`,`price`) VALUES (12,"Hawaiian","Lorem","V",7);

-- Bookings for the resturaunt
DROP TABLE IF EXISTS booking;
CREATE TABLE IF NOT EXISTS booking (
  bookingID int unsigned NOT NULL auto_increment,
  customerID int unsigned NOT NULL,
  telephone varchar(14) NOT NULL,
  bookingdate datetime,
  people int default 1,
  PRIMARY KEY (bookingID)
) AUTO_INCREMENT=1;

insert into booking (bookingID, customerID, telephone, bookingdate, people) values (1, 1, '592-232-0521', '2021-12-18 17:29:36', 2);
insert into booking (bookingID, customerID, telephone, bookingdate, people) values (2, 8, '775-120-6785', '2021-05-18 06:13:06', 4);
insert into booking (bookingID, customerID, telephone, bookingdate, people) values (3, 4, '393-916-0672', '2021-02-11 08:39:29', 1);
insert into booking (bookingID, customerID, telephone, bookingdate, people) values (4, 9, '114-541-0005', '2021-11-28 12:20:58', 1);
insert into booking (bookingID, customerID, telephone, bookingdate, people) values (5, 2, '561-687-0825', '2021-06-10 03:52:37', 4);
insert into booking (bookingID, customerID, telephone, bookingdate, people) values (6, 9, '959-512-2639', '2021-03-24 17:06:28', 3);
insert into booking (bookingID, customerID, telephone, bookingdate, people) values (7, 2, '593-781-9360', '2021-03-01 04:11:27', 4);
insert into booking (bookingID, customerID, telephone, bookingdate, people) values (8, 7, '473-595-2768', '2021-11-04 08:16:06', 5);
insert into booking (bookingID, customerID, telephone, bookingdate, people) values (9, 4, '673-132-5499', '2021-01-29 16:57:48', 3);
insert into booking (bookingID, customerID, telephone, bookingdate, people) values (10, 1, '151-149-9447', '2021-05-20 14:13:23', 2);
insert into booking (bookingID, customerID, telephone, bookingdate, people) values (11, 4, '382-125-5641', '2021-01-16 17:42:15', 5);
insert into booking (bookingID, customerID, telephone, bookingdate, people) values (12, 7, '507-644-2363', '2021-03-24 05:13:46', 3);

-- Customers
DROP TABLE IF EXISTS customer;
CREATE TABLE IF NOT EXISTS customer (
  customerID int unsigned NOT NULL auto_increment,
  firstname varchar(50) NOT NULL,
  lastname varchar(50) NOT NULL,
  email varchar(100) NOT NULL,  
  username  varchar(32),
  password  varchar(32) default '.',
  role tinyint(1) default 0,
  PRIMARY KEY (customerID)
) AUTO_INCREMENT=1;

INSERT INTO customer (customerID,firstname,lastname,email,username,password,role) VALUES 
(1,"Admin","Admin","admin@pizza.com","Admin","Admin", 1),
(2,"Desiree","Collier","Maecenas@non.co.uk", 'Collier', 'password', 0),
(3,"Irene","Walker","id.erat.Etiam@id.org", 'Walker', 'password', 0),
(4,"Forrest","Baldwin","eget.nisi.dictum@a.com", 'Baldwin', 'password', 0),
(5,"Beverly","Sellers","ultricies.sem@pharetraQuisqueac.co.uk", 'Sellers', 'password', 0),
(6,"Glenna","Kinney","dolor@orcilobortisaugue.org", 'Kinney', 'password', 0),
(7,"Montana","Gallagher","sapien.cursus@ultriciesdignissimlacus.edu", 'Gallagher', 'password', 0),
(8,"Harlan","Lara","Duis@aliquetodioEtiam.edu", 'Lara', 'password', 0),
(9,"Benjamin","King","mollis@Nullainterdum.org", 'King', 'password', 0),
(10,"Rajah","Olsen","Vestibulum.ut.eros@nequevenenatislacus.ca", 'Olsen', 'password', 0),
(11,"Castor","Kelly","Fusce.feugiat.Lorem@porta.co.uk", 'Kelly', 'password', 0),
(12,"Omar","Oconnor","eu.turpis@auctorvelit.co.uk", 'Oconnor', 'password', 0),
(13,"Porter","Leonard","dui.Fusce@accumsanlaoreet.net", 'Leonard', 'password', 0),
(14,"Buckminster","Gaines","convallis.convallis.dolor@ligula.co.uk", 'Gaines', 'password', 0),
(15,"Hunter","Rodriquez","ridiculus.mus.Donec@est.co.uk", 'Rodriquez', 'password', 0),
(16,"Zahir","Harper","vel@estNunc.com", 'Harper', 'password', 0),
(17,"Sopoline","Warner","vestibulum.nec.euismod@sitamet.co.uk", 'Warner', 'password', 0),
(18,"Burton","Parrish","consequat.nec.mollis@nequenonquam.org", 'Parrish', 'password', 0),
(19,"Abbot","Rose","non@et.ca", 'Rose', 'password', 0),
(20,"Barry","Burks","risus@libero.net", 'Burks', 'password', 0);

-- Current orders
DROP TABLE IF EXISTS orders;
CREATE TABLE IF NOT EXISTS orders (
  orderID int unsigned NOT NULL auto_increment,
  customerID int unsigned NOT NULL,
  orderdate datetime,
  PRIMARY KEY (orderID),
  FOREIGN KEY (customerID) REFERENCES customer(customerID)
) AUTO_INCREMENT=1;

INSERT INTO orders (orderID, customerID, orderdate) VALUES 
  (1, 1,'2021-12-18 17:29:36'),
  (2, 2,'2021-12-19 17:01:36'),
  (3, 3,'2021-12-20 17:00:36'),
  (4, 4,'2021-12-18 17:29:36'),
  (5, 5,'2021-12-15 17:29:36'),
  (6, 6,'2021-11-18 17:01:36'),
  (7, 7,'2021-12-19 17:29:36'),
  (8, 8,'2021-12-20 17:29:36'),
  (9, 9,'2021-12-21 17:29:36'),
  (10, 10,'2021-12-22 17:29:36'),
  (11, 11,'2021-12-23 17:29:36'),
  (12, 12,'2021-12-24 17:29:36');

-- Ability to add multiple food items to same order
DROP TABLE IF EXISTS orderlines;
CREATE TABLE IF NOT EXISTS orderlines (
  orderlinesID int unsigned NOT NULL auto_increment,
  orderID int unsigned NOT NULL,
  itemID int unsigned NOT NULL,
  FOREIGN KEY (orderID) REFERENCES orders(orderID),
  FOREIGN KEY (itemID) REFERENCES fooditems(itemID),
  pizzaQuantity int default 1,
  extras varchar(200),
  PRIMARY KEY (orderlinesID)
) AUTO_INCREMENT=1;

INSERT INTO orderlines (orderlinesID, orderID, itemID, pizzaQuantity, extras) VALUES 
(1, 1, 9, 1, "salad"),
(2, 1, 12, 2, "ice cream"),
(3, 2, 10, 1, NULL),
(4, 3, 7, 4, NULL),
(5, 4, 8, 3, "cake"),
(6, 5, 5, 3, NULL),
(7, 6, 1, 1, NULL),
(8, 7, 6, 1, NULL),
(9, 7, 7, 1, NULL),
(10, 7, 8, 1, NULL),
(11, 8, 8, 1, NULL),
(12, 9, 3, 2, NULL),
(13, 10, 11, 5, "fries"),
(14, 11, 9, 2, NULL),
(15, 12, 3, 1, NULL);
