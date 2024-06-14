<?php
	session_start();
	
	require_once("./connessione.php");
	
	if (!isset($_SESSION['accessoPermesso']) || $_SESSION['accessoPermesso']!="admin")
		header ('Location: mysql.OL.login.php');
	
	$sql="SELECT *
		FROM $OLuser_table_name
		WHERE tipologia!=\"admin\"
		";
		
	if (!$resultQ=mysqli_query($mysqliConnection, $sql)) {
		printf("Dammit! Can't execute user select query.\n");
		exit();
	}
	
	$outputTable="<table border=\"2px\" cellspacing=\"2px\" cellpadding=\"3px\" style=\"border-color: fuchsia; font-size: 70% \">\n";
	$outputTable.="<thead>\n <tr>\n <th></th>\n <th> Username </th>\n <th> Password </th>\n <th> Genere </th>\n <th> Nazione </th>\n";
	$outputTable.=" <th> Tipologia Utente </th>\n <th> Somme Spese </th>\n <th> Ban </th>\n </tr>\n</thead>\n\n<tbody>\n";
	while ($row=mysqli_fetch_array ($resultQ)) {
		$outputTable.="<tr>\n <td> <input type=\"radio\" name=\"selection\" value=\"{$row['username']}\" /> </td>\n";
		$outputTable.=" <td> {$row['username']} </td>\n <td> {$row['password']} </td>\n <td> {$row['genere']} </td>\n <td> {$row['nazione']} </td>\n";
		$outputTable.=" <td> {$row['tipologia']} </td>\n <td> {$row['sommeSpese']} </td>\n <td> {$row['ban']} </td>\n</tr>\n\n";
	}
	$outputTable.="</tbody>\n </table>\n";
	
	if (isset ($_POST['invio1'])) {
		if (isset($_POST['selection'])) {
			//controllo che l'utente selezionato non sia gia' un gestore
			$sql="SELECT *
				FROM $OLuser_table_name
				WHERE username=\"{$_POST['selection']}\"
				;";
			if (!$resultQ=mysqli_query($mysqliConnection, $sql)) {
				print ("Dammit! Can't execute check-tipologia query.\n");
				exit();
			}
			
			$row=mysqli_fetch_array($resultQ);
			if ($row['tipologia']=="utente") {
				//l'utente selezionato non e' un gestore quindi va reso gestore
				$sql="UPDATE $OLuser_table_name
					SET tipologia=\"gestore\"
					WHERE username=\"{$_POST['selection']}\" 
					";
			 
				if (!$resultQ=mysqli_query($mysqliConnection, $sql)) {
					print ("Dammit! Can't execute user-tipologia update query.\n");
					exit();
				}
				header ('Location: mysql.OL.gestioneGestori.php');
			}
			else {
				//l'utente selezionato e' gia' gestore quindi preparo un messaggio da mostrare
				$msg="<p style=\"color: red\"> Operazione fallita, l'utente selezionato &egrave; gi&agrave; un gestore del sito. </p>";
			}
		}
		else 
			$msg="<p style=\"color: red\"> Operazione fallita, utente non selezionato. </p>";
	}
	
	if (isset ($_POST['invio2'])) {
		if (isset($_POST['selection'])) {
			//controllo che l'utente selezionato non sia gia' un utente base
			$sql="SELECT *
				FROM $OLuser_table_name
				WHERE username=\"{$_POST['selection']}\"
				;";
			if (!$resultQ=mysqli_query($mysqliConnection, $sql)) {
				print ("Dammit! Can't execute check-tipologia query.\n");
				exit();
			}
			
			$row=mysqli_fetch_array($resultQ);
			if ($row['tipologia']=="gestore") {
				//l'utente selezionato e' un gestore  quindi va reso utente base 
				//(revoca possibilita' di gestione)
				
				$sql="UPDATE $OLuser_table_name
					SET tipologia=\"utente\"
					WHERE username=\"{$_POST['selection']}\" 
					";
			 
				if (!$resultQ=mysqli_query($mysqliConnection, $sql)) {
					print ("Dammit! Can't execute user-tipologia update query.\n");
					exit();
				}
				header ('Location: mysql.OL.gestioneGestori.php');
			}
			else {
				//l'utente selezionato e' gia' utente base quindi preparo un messaggio da mostrare
				$msg="<p style=\"color: red\"> Operazione fallita, l'utente selezionato &egrave; gi&agrave; un utente base. </p>";
			}	
		}
		else 
			$msg="<p style=\"color: red\"> Operazione fallita, utente non selezionato. </p>";
	}
	
	$mysqliConnection->close();
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>
		<title> Gestione Gestori </title>
		<link rel="stylesheet" href="stile.pag.iniziali.css" type="text/css" />
	</head>

	<body id="container">
		<div id="header">
			<div id="firstRowHeader">
				<div><a href="mysql.OL.logout.php"><img src="img/loghi/logoBalena.png" width="200" height="175" alt="Logo del sito: una balena"/></a></div>
				<div id="login"><a href="mysql.OL.logout.php" >Logout</a></div>
			</div>
			
			<div id="secondRowHeader">
				<span class="social"><a href=""><img src="img/loghi/logoFacebook.png" width="50" height="50" alt="Logo facebook"/></a></span>
				<span class="social"><a href=""><img src="img/loghi/logoInsta.png" width="50" height="50" alt="Logo instagram"/></a></span>
				<span class="social"><a href=""><img src="img/loghi/logoYoutube.png" width="50" height="50" alt="Logo youtube"/></a></span>
				<span class="social"><a href=""><img src="img/loghi/logoTrip.png" width="50" height="50" alt="Logo tripadvisor"/></a></span>
			</div>
		</div>
			
		<div id="content">
			<div id="centralBox">
				<h2> Gestione gestori</h2>
				<p> In questa sezione, puoi scegliere quali utenti nominare gestori o togliere loro questo incarico
					rendendoli di nuovo utenti base. </p>
				<p> Ecco l'elenco degli utenti registrati al sito: </p>
				<form action="<?php $_SERVER['PHP_SELF']?>"  method="post" >
					<?php echo $outputTable; ?>
					<p>
						<input type="submit" name="invio1" value="Rendi gestore" />
						<input type="reset" value="Annulla selezionato" />
						<input type="submit" name="invio2" value="Rendi utente base" />
					</p>
				</form>
				<hr />
				<?php if (isset ($msg)) echo $msg. "<hr />";?>
				
				<!-- per controllare il contenuto di $_POST -->
				<p> $_POST: <?php print_r ($_POST); ?> </p>	
			</div>
			
			<div id="leftBox">
				<?php require("menu.php"); ?>
			</div>	
		</div>
		
		<div id="footer">
			<div id="firstRowFooter"> 
				<div>
					<img src="img/loghi/logoBalena.png" width="100" height="75" alt="Logo del sito: una balena"/>
					<ul>
						<li id="posizione"> Al Fanar, Sharm Al Sheikh, <br />South Sinai, Egitto </li>
						<li id="telefono">+39 342 598 6245</li>
						<li id="mail">info@oceanlovers.com</li>	
					</ul>
				</div>
				
				<div>
					<h2> Centro Sub </h2><br />
					<p>Immersioni</p>
					<p>Corsi</p>
					<p>Snorkeling</p>
				</div>
				
				<div>
					<h2> Crociere </h2><br />
					<p> Crociere sub </p>
					<p> Crociere barche a vela </p>
				</div>
				
				<div>
					<h2> Desclaimer </h2><br />
					<p> Job Opportunities </p>
					<p> Privacy Policy </p>
					<p> Cookie Policy </p>
					<p> Termini e condizioni </p>
				</div>
			</div>
			
			<div id="secondRowFooter">Copyright &copy; 2024 Ocean Lovers a Sharm El Sheikh - 
				All Rights Reserved - Sviluppo by Realizzazione siti L-web Roma
			</div>
		</div>
	</body>
</html>