<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	require_once("./connessione.php");

	$msg="";
	if (isset($_POST['invio'])){       // abbiamo appena inviato dati attraverso la form di login
	  if (empty($_POST['username']) || empty($_POST['password']))
		$msg="<p style=\"color: red; font-size: 1.3em\"> Dati mancanti!!! </p>\n";
	  else {                             
			// query per controllare che i dati inseriti nella form sia presenti nella 
			//tabella OLuser
			$sql = "SELECT *
					FROM $OLuser_table_name
					WHERE username = \"{$_POST['username']}\" AND password =\"{$_POST['password']}\"
				";
			// il risultato della query va in $resultQ
			if (!$resultQ = mysqli_query($mysqliConnection, $sql)) {
				printf("Oops, la query non ha risultato !!\n");
				exit();
			}
		
			$row = mysqli_fetch_array($resultQ);

			if ($row) { 		// utente esiste valido
				if ($row['ban']!="attivo") {
				  session_start();
				  $_SESSION['username']=$_POST['username'];
				  $_SESSION['dataLogin']=time();
				  $_SESSION['numeroUtente']=$row['userId'];
				  $_SESSION['accessoPermesso']=$row['tipologia'];
				  $_SESSION['ban']=$row['ban'];
				  if ($_SESSION['accessoPermesso']=="utente")
					$_SESSION['spesaFinora']=$row['sommeSpese'];
				  header('Location: mysql.OL.inizio.php');    // accesso alla pagina iniziale
				  exit();
				}
				else 
					$msg="<p style=\"color: red; font-size: 1.3em\"> Accesso negato!!! <br /> Causa: Ban. </p>\n";	
			}
			else 
				$msg= "<p style=\"color: red; font-size: 1.3em\"> Accesso negato!!! <br /> Causa: mancata registrazione. </p>\n";	
		}
	}
?>
<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title> Login </title>
		<link rel="stylesheet" href="stile.login-registrazione.css" type="text/css" />
	</head>

	<body>
		<div><a href="home.html"><img src="img/loghi/logoBalena.png" width="200" height="175" alt="Logo del sito: una balena"/></a></div>
		<hr />
		<div id="formContainer">
				<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
					<h2> Sign In </h2>
					
					<p>Username<br />
						<input type="text" name="username" size="30" />
					</p>
					
					<p>Password<br /> 
						<input type="text" name="password" size="30" />
					</p>
					
					<p id="signUp"><a href="mysql.OL.registrazione.php"> Signup </a></p>
					
					<?php 
						echo $msg;
					?>
					
					<div id="buttons">
						<input type="submit" name="invio" value="Accedi" />
						<input type="reset" name="reset" value="Reset" />
					</div>
				</form>
			</div>
		<hr />
	</body>
</html>
