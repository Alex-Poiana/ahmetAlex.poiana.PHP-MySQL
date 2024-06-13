<?php
	error_reporting (E_ALL &~E_NOTICE);

	session_start();               

	if (!isset($_SESSION['accessoPermesso']) || $_SESSION['accessoPermesso']!="utente")
	header('Location: mysql.OL.login.php');

	require_once("./connessione.php");

	// variabile contenente la stringa di output che verra` inclusa nella pagina di risposta
	$msg="";
	
	if ($_SESSION['costoCarrello']!=0) {
		$msg.="<p> Gentile cliente {$_SESSION['username']}";
	
		$tot=$_SESSION['costoCarrello']+$_SESSION['spesaFinora'];

		$sql1 = "UPDATE $OLuser_table_name
				SET sommeSpese=\"$tot\"
				WHERE username = \"{$_SESSION['username']}\"
		   ";
		// eseguiamo la query, e la controlliamo
		if (!mysqli_query($mysqliConnection, $sql1)) {
			printf("Oops, errore nella gestione della query: %s\n", mysqli_error($mysqliConnection));
			exit();
		}

		$msg.=" hai proprio speso {$_SESSION['costoCarrello']} &euro;.";	 
		$msg.="<p> La ringraziamo per l'acquisto e la informiamo che la spesa totale presso di noi: {$tot} &euro; </p>\n";
			
		//aggiornamento database con i servizi acquistati dall'utente
		foreach ($_SESSION['carrello'] as $chiave=>$valore) {
			$sql1 = "SELECT *
					FROM $OLwhaleWatch_table_name
					WHERE serviceId = \"$valore\"
				";
					
			$sql2 = "SELECT *
					FROM $OLdolphinSwim_table_name
					WHERE serviceId  = \"$valore\"
				";
					
			$sql3 = "SELECT *
					FROM $OLsharkDive_table_name
					WHERE serviceId  = \"$valore\"
				";
					
			if (!$resultQ = mysqli_query($mysqliConnection, $sql1)) {
				printf("Dammit! Can't execute whaleWatch select query.\n");
				exit();
			}
			   
			$row1= mysqli_fetch_array($resultQ); // se e' un servizio whaleWatch, sta qua - senno' vuota

			if (!$resultQ = mysqli_query($mysqliConnection, $sql2)) {
				printf("Dammit! Can't execute dolphinSwim select query.\n");
				exit();
			}
			   
			$row2= mysqli_fetch_array($resultQ); // se e' un un servizio dolphinSwim, sta qua - senno' vuota
			   
			   
			if (!$resultQ = mysqli_query($mysqliConnection, $sql3)) {
				printf("Dammit! Can't execute sharkDive select query.\n");
				exit();
			}
			   
			$row3= mysqli_fetch_array($resultQ); // se e' un un servizio sharkDive, sta qua - senno' vuota
			   
			if (empty ($row1)){
				if(empty($row2)){
					//e' un servizio sharkDive
					//query per verificare questo servizio sharkDive (con questo serviceId) e' gia' stato acquistato dall'utente
					$sqlQuery="SELECT *
							FROM $OLacquistiUser_table_name
							WHERE userId=\"{$_SESSION['numeroUtente']}\" AND serviceId=\"{$row3['serviceId']}\" AND tipoServizio=\"$OLsharkDive_table_name\"
						";
					
					if (!$resultQ=mysqli_query($mysqliConnection, $sqlQuery)){
						printf("Dammit! Can't execute check number query.\n");
						exit();
					}
							
					$row4=mysqli_fetch_array($resultQ);
							
					if (empty($row4)) {
						//questo servizio sharkDive (con questo serviceId) non e' gia' stato acquistato dall'utente, quindi 
						//lo inseriamo nella tabella acquistiUser
						$sqlQuery="INSERT INTO $OLacquistiUser_table_name
								(userId, serviceId, tipoServizio, quanteCopie)
								VALUES
								('{$_SESSION['numeroUtente']}', '{$row3['serviceId']}', '$OLsharkDive_table_name', '1')
							";
								
						if (!$resultQ=mysqli_query($mysqliConnection, $sqlQuery)){
							printf("Dammit! Can't execute user-sharkDive insert query.\n");
							exit();
						}
					}
					else {
						//questo servizio sharkDive (con questo serviceId) e' gia' stato acquistato dall'utente, quindi 
						//incrementiamo semplicemente il numero di copie comprate dall'utente
						$row4['quanteCopie']++;
						$sqlQuery="UPDATE $OLacquistiUser_table_name
								SET quanteCopie=\"{$row4['quanteCopie']}\"
								WHERE userId=\"{$row4['userId']}\" AND serviceId=\"{$row4['serviceId']}\" AND tipoServizio=\"{$row4['tipoServizio']}\"
							";
								
						if (!$resultQ=mysqli_query($mysqliConnection, $sqlQuery)){
							printf("Dammit! Can't execute user-sharkDive update query.\n");
							exit();
						}
					}
				}
				else {
					//e' un servizio dolphinSwim
					//query per verificare se questo servizio dolphinSwim (con questo serviceId) e' gia' stato acquistato dall'utente
					$sqlQuery="SELECT *
							FROM $OLacquistiUser_table_name
							WHERE userId=\"{$_SESSION['numeroUtente']}\" AND serviceId=\"{$row2['serviceId']}\" AND tipoServizio=\"$OLdolphinSwim_table_name\"
						";
					
					if (!$resultQ=mysqli_query($mysqliConnection, $sqlQuery)){
						printf("Dammit! Can't execute check number query.\n");
						exit();
					}
					
					$row4=mysqli_fetch_array($resultQ);
					if (empty($row4)) {
						//questo servizio dolphinSwim (con questo serviceId) non e' gia' stato acquistato dall'utente, quindi 
						//lo inseriamo nella tabella acquistiUser
						$sqlQuery="INSERT INTO $OLacquistiUser_table_name
								(userId, serviceId, tipoServizio, quanteCopie)
								VALUES
								('{$_SESSION['numeroUtente']}', '{$row2['serviceId']}', '$OLdolphinSwim_table_name', '1')
							";
							
						if (!$resultQ=mysqli_query($mysqliConnection, $sqlQuery)){
							printf("Dammit! Can't execute user-dolphinSwim insert query.\n");
							exit();
						}
					}
					else {
						//questo servizio dolphinSwim (con questo serviceId) e' gia' stato acquistato dall'utente, quindi 
						//incrementiamo semplicemente il numero di copie comprate dall'utente
						$row4['quanteCopie']++;
						$sqlQuery="UPDATE $OLacquistiUser_table_name
								SET quanteCopie=\"{$row4['quanteCopie']}\"
								WHERE userId=\"{$row4['userId']}\" AND serviceId=\"{$row4['serviceId']}\" AND tipoServizio=\"{$row4['tipoServizio']}\"
							";
							
						if (!$resultQ=mysqli_query($mysqliConnection, $sqlQuery)){
							printf("Dammit! Can't execute user-dolphinSwim update query.\n");
							exit();
						}
					}
				}
			}
			else {
				//e' un servizio whaleWatch
				//query per verificare se questo servizio whaleWatch (con questo serviceId) e' gia' stato acquistato dall'utente
					$sqlQuery="SELECT *
							FROM $OLacquistiUser_table_name
							WHERE userId=\"{$_SESSION['numeroUtente']}\" AND serviceId=\"{$row1['serviceId']}\" AND tipoServizio=\"$OLwhaleWatch_table_name\"
						";
					
					if (!$resultQ=mysqli_query($mysqliConnection, $sqlQuery)){
						printf("Dammit! Can't execute check number query.\n");
						exit();
					}
					
					$row4=mysqli_fetch_array($resultQ);
					if (empty($row4)) {
						//questo servizio whaleWatch (con questo serviceId) non e' gia' stato acquistato dall'utente, quindi 
						//lo inseriamo nella tabella acquistiUser
						$sqlQuery="INSERT INTO $OLacquistiUser_table_name
								(userId, serviceId, tipoServizio, quanteCopie)
								VALUES
								('{$_SESSION['numeroUtente']}', '{$row1['serviceId']}', '$OLwhaleWatch_table_name', '1')
							";
							
						if (!$resultQ=mysqli_query($mysqliConnection, $sqlQuery)){
							printf("Dammit! Can't execute user-whaleWatch insert query.\n");
							exit();
						}
					}
					else {
						//questo servizio whaleWatch (con questo serviceId) e' gia' stato acquistato dall'utente, quindi 
						//incrementiamo semplicemente il numero di copie comprate dall'utente
						$row4['quanteCopie']++;
						$sqlQuery="UPDATE $OLacquistiUser_table_name
								SET quanteCopie=\"{$row4['quanteCopie']}\"
								WHERE userId=\"{$row4['userId']}\" AND serviceId=\"{$row4['serviceId']}\" AND tipoServizio=\"{$row4['tipoServizio']}\"
							";
							
						if (!$resultQ=mysqli_query($mysqliConnection, $sqlQuery)){
							printf("Dammit! Can't execute user-whaleWatch update query.\n");
							exit();
						}
					}
			}
		}
			
		//nel caso si rientri senza logout
		$_SESSION['carrello']=array();  // il carrello pagato va svuotato
		$_SESSION['costoCarrello']=0;   
		$_SESSION['spesaFinora']=$tot;		
	}	
			
	//chiusura connessione con il db OceanLovers
	$mysqliConnection->close();			
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>
		<title> Zona Pagamenti </title>
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
				<h2> Zona pagamenti </h2>
				<?php echo $msg; ?>
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