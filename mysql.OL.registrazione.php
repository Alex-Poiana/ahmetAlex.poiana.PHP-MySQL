<?php
/*	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	require_once("./connessione.php");

	// una volta che siamo nel db, verichiamo cosa e` stato passato come username
	// e pwd e facciamo una quesry per controllare
	//
	if (isset($_POST['invio'])){       // abbiamo appena inviato dati attraverso la form di login
	  if (empty($_POST['userName']) || empty($_POST['password']))
		echo "<p> dati mancanti!!! </p>";
	  else {                             
			 // controllo dati
			 // username e password ricevuti corrispondono a  quel che c'e' nella tabella STuser?
			 // questa e' la query di controllo
			$sql = "SELECT *
					FROM $STuser_table_name
					WHERE userName = \"{$_POST['userName']}\" AND password =\"{$_POST['password']}\"
				";
			// il risultato ("result set") della query va in $resultQ
			if (!$resultQ = mysqli_query($mysqliConnection, $sql)) {
				printf("Oops, la query non ha risultato !!\n");
				exit();
			}
		
			// prendiamo una riga dal risultato (in questo caso il risultato ha una sola
			// riga perche' c'e` un solo utente, se c'e`, corrispondente ai dati, ma in
			// altre occasioni il risultato potrebbe essere un insieme di righe distinte
			// della tabella ...
			// la funzione restituisce un array (anche associativo per default)
			// con i valori della riga selezionata, oppure NULL - se non c'e` la riga
			$row = mysqli_fetch_array($resultQ);

			if ($row) { 		// utente esiste valido
				if ($row['ban']!="attivo") {
				  session_start();
				  $_SESSION['userName']=$_POST['userName'];
				  $_SESSION['dataLogin']=time();
				  $_SESSION['numeroUtente']=$row['userId'];
				  $_SESSION['accessoPermesso']=$row['tipologia'];
				  $_SESSION['ban']=$row['ban'];
				  if ($_SESSION['accessoPermesso']=="utente")
					$_SESSION['spesaFinora']=$row['sommeSpese'];
				  header('Location: mysql.ST.inizio.php');    // accesso alla pagina iniziale
				  exit();
				}
				else 
					echo "<p> Accesso negato!!! Causa: Ban. </p>";	
			}
			else 
				echo "<p> Accesso negato!!! Causa: mancata registrazione. </p>";	
		}
	}*/
?>


<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title> Registrazione </title>
		<link rel="stylesheet" href="stile.login-registrazione.css" type="text/css" />
	</head>

	<body>
		<div><a href="home.html"><img src="img/loghi/logoBalena.png" width="200" height="175" alt="Logo del sito: una balena"/></a></div>
		<hr />
		<div id="formContainer">
				<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
					<h2> Sign Up </h2>
					
					<p> Username <br />
						<input type="text" name="username" size="30" />
					</p>
					<p> Password <br /> 
						<input type="text" name="password" size="30" />
					</p>
					
					<p> Conferma Password <br /> 
						<input type="text" name="confPassword" size="30" />
					</p>
					
					<p> Genere: <br /> 
						<input type="radio" name="genere" value="femmina" /> Femmina
						<input type="radio" name="genere" value="maschio" /> Maschio	
					</p>
					
					<p> Nazione <br />
						<select  name="argomenti" size="1" >
							<option value="Belgio">Belgio</option>
							<option value="Italia">Italia</option>
							<option value="Francia">Francia</option>
							<option value="Array">Spagna</option>
							<option value="Spagna)">Germania</option>
							<option value="Regno Unito">Regno Unito</option>
						</select>
					</p>
					
					<p id="signUp"><a href="mysql.OL.login.php"> Signin </a></p>
					
					<div id="buttons">
						<input type="submit" name="invio" value="Registrati" />
						<input type="submit" name="reset" value="Reset" />
					</div>
				</form>
			</div>
		<hr />
	</body>
</html>
