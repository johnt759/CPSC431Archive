<?php
session_start();

session_unset(); // Unset all the session variables.

session_destroy(); // Destroy the current session.

header("location: index.html"); // Now redirect the user back to the login screen.
?>