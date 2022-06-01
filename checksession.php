<?php
session_start();

//commented out to check login function
function isAdmin() {
 if (($_SESSION['loggedin'] == 1) and ($_SESSION['userid'] == 1)) 
     return TRUE;
 else 
     return FALSE;
}

function checkUser() {
    $_SESSION['URI'] = '';    
    if ($_SESSION['loggedin'] == 1)
       return TRUE;
    else {
       $_SESSION['URI'] = 'http://waipukuraupizzeria.unaux.com'.$_SERVER['REQUEST_URI']; //save current url for redirect      http://localhost
       header('Location: http://waipukuraupizzeria.unaux.com/pizza/login.php', true, 303);    // http://localhost/pizza/login.php

       //header mitigaton
       header("Access-Control-Allow-Origin: *");   
    }       
}

//just to show we are are logged in
function loginStatus() {
    $un = $_SESSION['username'];
    if ($_SESSION['loggedin'] == 1)     
        echo "<h1>Logged in as $un</h1>";
    else
        if ($un != '') {
            echo "<h1>Logged out</h1>";            
            $_SESSION['username'] = '';
        }    
}

//log a user in
function login($id, $username) {
   //simple redirect if a user tries to access a page they have not logged in to
   if ($_SESSION['loggedin'] == 0 and !empty($_SESSION['URI']))        
        $uri = $_SESSION['URI'];          
   else { 
     $_SESSION['URI'] =  'http://waipukuraupizzeria.unaux.com/pizza/index.php';       //'http://localhost/pizza/index.php';   
     $uri = $_SESSION['URI'];           
   }  

   $_SESSION['loggedin'] = 1;        
   $_SESSION['userid'] = $id;   
   $_SESSION['username'] = $username; 
   $_SESSION['URI'] = ''; 
   header('Location: '.$uri, true, 303);        
}

//simple logout function
function logout(){
  $_SESSION['loggedin'] = 0;
  $_SESSION['userid'] = -1;        
  $_SESSION['username'] = '';
  $_SESSION['URI'] = '';
  header('Location: http://waipukuraupizzeria.unaux.com/pizza/login.php', true, 303);    //http://localhost/pizza/login.php'
}




?>


