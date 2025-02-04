<?php
// Start the session
//The session_start() function must be the very first thing in your document. Before any HTML tags.
session_start();
header("Location: login.php");
session_destroy();
?>
