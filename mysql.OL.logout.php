<?php
	session_start();
	unset($_SESSION);
	session_destroy();
	header('Location: Home.html');
	/*header('Location: mysql.OL.login.php');*/
?>
