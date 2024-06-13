<?php
	error_reporting (E_ALL &~E_NOTICE);

	session_start();

	if (!isset($_SESSION['accessoPermesso']) || $_SESSION['accessoPermesso']!="utente") 
		header('Location: mysql.OL.login.php');
	
	require_once("./connessione.php");

	// costruzione parte dell'output con la visualizzazione del contenuto del carrello
	$outputTable="<p> Gentile cliente {$_SESSION['username']}";

	if (!isset($_SESSION['carrello']) || empty($_SESSION['carrello'])) {
	   $outputTable.= ", che ci fai qui con il carrello vuoto? <br />\n";
	   $outputTable.="\t\t\t\t\tRecati nella sezione \"Aggiungi servizi al carrello\" per usufruire dei nostri servizi.";
	   $outputTable.=" Non te ne pentirai! </p>\n";
	} else {
		$outputTable.=", stai per acquistare i seguenti servizi: </p>\n";
		
		$outputTable.="<table border=\"2px\" cellspacing=\"2px\" cellpadding=\"3px\" style=\"border-color: fuchsia\">\n";
		$outputTable.="<thead>\n <tr>\n <th> Service Id </th>\n <th> Nome Servizio </th>\n <th> Data </th>\n <th> Costo </th>\n </tr>\n</thead>\n\n<tbody>\n";

		foreach ($_SESSION['carrello'] as $k=>$v) {
			
				$sql1 = "SELECT *
					  FROM $OLwhaleWatch_table_name
					  WHERE serviceId = \"$v\"
					";
					
			   $sql2 = "SELECT *
					  FROM $OLdolphinSwim_table_name
					  WHERE serviceId  = \"$v\"
					";
					
				$sql3 = "SELECT *
					  FROM $OLsharkDive_table_name
					  WHERE serviceId  = \"$v\"
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
						$outputTable.="<tr>\n<td> {$row3['serviceId']} </td>\n <td> {$row3['nomeServizio']} </td>\n <td> {$row3['data']} </td>\n <td> {$row3['costo']}&euro; </td>\n</tr>\n\n";
						
					}
					else {
						//e' un servizio dolphinSwim
						$outputTable.="<tr>\n <td> {$row2['serviceId']} </td>\n <td> {$row2['nomeServizio']} </td>\n <td> {$row2['data']} </td>\n <td> {$row2['costo']}&euro; </td>\n</tr>\n\n";
					}
				}
				else {
					//e' un servizio whaleWatch
						$outputTable.="<tr>\n <td> {$row1['serviceId']} </td>\n <td> {$row1['nomeServizio']} </td>\n <td> {$row1['data']} </td>\n <td> {$row1['costo']}&euro; </td>\n</tr>\n\n";
				}
			}	
			
			$outputTable.="</tbody>\n</table>\n";
			$outputTable.="<p> Costo totale del carrello: {$_SESSION['costoCarrello']}&euro; </p>\n\n";		  
	}
	
	//chiusura connessione con il db OceanLovers
	$mysqliConnection->close();
	
	$stampaForm=<<<'SFM'
				<form action="zona.pagamenti.php"  method="post" >
					<p><input type="submit" name="invioPagamento" value="Procedi con il pagamento" /></p>
				</form>
SFM
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>
		<title> Effettua Pagamento </title>
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
				<h2> Effettua Pagamento </h2>
				<?php 
					echo $outputTable; 
					if (isset($_SESSION['costoCarrello']) && $_SESSION['costoCarrello']!=0)
						echo $stampaForm. "\n";
				?>
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