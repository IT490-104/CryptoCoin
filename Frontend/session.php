<?php
include ('feRequest.php');
// Starting the session, necessary 
// for using session variables 
session_start();

// Declaring and hoisting the variables 
$username = "";
$errors = array();
$_SESSION['success'] = "";


// Registration code 
if (isset($_POST['reg_user'])) {

    // Ensuring that the user has not left any input field blank 
    // error messages will be displayed for every blank input 
    if (empty($username)) { array_push($errors, "Username is required"); }
    if (empty($email)) { array_push($errors, "Email is required"); }
    if (empty($password_1)) { array_push($errors, "Password is required"); }

    if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match");
        // Checking if the passwords match 
    }

    // If the form is error free, then register the user 
    if (count($errors) == 0) {

        // Password encryption to increase data security 
        $password = md5($password_1);

        // Storing username of the logged in user, 
        // in the session variable 
        $_SESSION['username'] = $username;

        // Welcome message 
        $_SESSION['success'] = "You have logged in";

        // Page on which the user will be  
        // redirected after logging in 
        header('location: index.html');
    }
}

// User login 
if (isset($_POST['login'])) {
    // Error message if the input field is left blank 
    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }
    // Checking for the errors 
    if (count($errors) == 0) {

        // Password matching 
        $password = md5($password);
            If{
            // Storing username in session variable
            $_SESSION['username'] = $username;

            // Welcome message 
            $_SESSION['success'] = "You have logged in!";

            // Page on which the user is sent 
            // to after logging in 
            header('location: index.html');
        }
        else {

            // If the username and password doesn't match 
            array_push($errors, "Username or password incorrect");
        }
    }
}
?>


