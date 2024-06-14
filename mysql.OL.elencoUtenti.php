<?php
	session_start();
	
	require_once("./connessione.php");
	
	if (!isset($_SESSION['accessoPermesso']) || $_SESSION['accessoPermesso']!="admin")
		header ('Location: mysql.OL.login.php');
	
	$sql="SELECT *
		FROM $OLuser_table_name
		";
		
	if (!$resultQ=mysqli_query($mysqliConnection, $sql)) {
		printf("Dammit! Can't execute user select query.\n");
		exit();
	}
	
	$outputTable="<table border=\"2px\" cellspacing=\"2px\" cellpadding=\"3px\" style=\"border-color: fuchsia; font-size: 70%\">\n";
	$outputTable.="<thead>\n <tr>\n <th> Username </th>\n <th> Password </th>\n <th> Genere </th>\n <th> Nazione </th>\n";
	$outputTable.=" <th> Tipologia Utente </th>\n <th> Somme Spese </th>\n <th> Ban </th>\n </tr>\n</thead>\n\n<tbody>\n";
	while ($row=mysqli_fetch_array ($resultQ)) {
		$outputTable.="<tr>\n <td> {$row['username']} </td>\n <td> {$row['password']} </td>\n <td> {$row['genere']} </td>\n <td> {$row['nazione']} </td>\n";
		$outputTable.=" <td> {$row['tipologia']} </td>\n <td> {$row['sommeSpese']} </td>\n <td> {$row['ban']} </td>\n</tr>\n\n";
	}
	$outputTable.="</tbody>\n</table>\n";
	
	$mysqliConnection->close();
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>
		<title> Visualizzazione utenti </title>
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
				<h2> Visualizzazione elenco utenti del sito </h2>
				<p> Caro 
					<?php 
						echo "{$_SESSION['accessoPermesso']} {$_SESSION['username']}, <br />";
					?>
					ecco l'elenco degli utenti registrati al sito: 
				</p>
			
				<?php echo $outputTable; ?>
				
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