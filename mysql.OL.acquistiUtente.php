<?php
	session_start();
	
	require_once("./connessione.php");
	
	if (!isset($_SESSION['accessoPermesso']) || $_SESSION['accessoPermesso']!="gestore")
		header ('Location: mysql.OL.login.php');
	
	//construzione tabella di selezione utenti
	$sql="SELECT *
		FROM $OLuser_table_name
		WHERE tipologia!=\"admin\" && tipologia!=\"gestore\"
		";
		
	if (!$resultQ=mysqli_query($mysqliConnection, $sql)) {
		printf("Dammit! Can't execute user select query.\n");
		exit();
	}
	
	$tableUsers="<table border=\"2px\" cellspacing=\"2px\" cellpadding=\"3px\" style=\"border-color: fuchsia; font-size: 70% \">\n";
	$tableUsers.="<thead>\n <tr>\n <th></th>\n <th> Username </th>\n <th> Genere </th>\n <th> Nazione </th>\n";
	$tableUsers.=" <th> Tipologia Utente </th>\n <th> Somme Spese </th>\n <th> Ban </th>\n </tr>\n</thead>\n\n<tbody>\n";
	while ($row=mysqli_fetch_array ($resultQ)) {
		$tableUsers.="<tr>\n <td> <input type=\"radio\" name=\"selection\" value=\"{$row['username']}\" /> </td>\n";
		$tableUsers.=" <td> {$row['username']} </td>\n <td> {$row['genere']} </td>\n <td> {$row['nazione']} </td>\n";
		$tableUsers.=" <td> {$row['tipologia']} </td>\n <td> {$row['sommeSpese']} </td>\n <td> {$row['ban']} </td>\n</tr>\n\n";
	}
	$tableUsers.="</tbody>\n </table>\n";
	

	if (isset ($_POST['invio'])) {
		if (isset($_POST['selection'])) {
			
			//query per ricavare id dello user selezionato
			$sql1="SELECT *
				FROM $OLuser_table_name
				WHERE userName=\"{$_POST['selection']}\" 
			";
		 
			if (!$resultQ=mysqli_query($mysqliConnection, $sql1)) {
				print ("Dammit! Can't execute user select query.\n");
				exit();
			}
			
			//row1 contiene le info dell'utente selezionato tra cui l'Id necessario
			//per trovare gli aquisti fatti da tale utente
			$row1=mysqli_fetch_array($resultQ); 
			
			if ($row1['sommeSpese']!=0){
				//query per selezionare i servizi acquistati dall'utente selezionato
				$sql2="SELECT *
					FROM $OLacquistiUser_table_name
					WHERE userId=\"{$row1['userId']}\"
				";
				
				if (!$resultQ=mysqli_query($mysqliConnection, $sql2)) {
					print ("Dammit! Can't execute user-service select query.\n");
					exit();
				}
				
				
				$tableService="\n<table border=\"2px\" cellspacing=\"2px\" cellpadding=\"3px\" style=\"border-color: fuchsia; font-size: 70% \">\n";
				$tableService.="<thead>\n <tr>\n <th> ServiceId </th>\n <th> Nome Servizio </th>\n <th> Data </th>\n <th> Costo </th>\n";
				$tableService.=" <th> Quantit&agrave; </th>\n </tr>\n</thead>\n\n<tbody>\n";
				//$row2 contiene tutti gli acquisti dell'utente selezionato
				while ($row2=mysqli_fetch_array($resultQ)){
					
					switch ($row2['tipoServizio']) {
						case "$OLwhaleWatch_table_name":
							//e' un servizio whaleWatch, quindi cerco (tramite una query) il servizio nell'apposita tabella
							$sql3="SELECT *
								FROM {$row2['tipoServizio']}
								WHERE serviceId=\"{$row2['serviceId']}\"
							";
						break;
						
						case "$OLdolphinSwim_table_name":
							//e' un servizio dolphinSwim, quindi cerco (tramite una query) il servizio nell'apposita tabella
							$sql3="SELECT *
								FROM {$row2['tipoServizio']}
								WHERE serviceId=\"{$row2['serviceId']}\"
							";
						break;
						
						case "$OLsharkDive_table_name":
							//e' un servizio sharkDive, quindi cerco (tramite una query) il servizio nell'apposita tabella
							$sql3="SELECT *
								FROM {$row2['tipoServizio']}
								WHERE serviceId=\"{$row2['serviceId']}\"
							";
						break;		
					}
					
					if (!$resultQuery=mysqli_query($mysqliConnection, $sql3)) {
						print ("Dammit! Can't execute select query.\n");
						exit();
					}
					
					$row3=mysqli_fetch_array ($resultQuery);
					
					$tableService.=" <tr>\n <td> {$row3['serviceId']} </td>\n <td> {$row3['nomeServizio']} </td>\n";
					$tableService.=" <td> {$row3['data']} </td>\n <td> {$row3['costo']} </td>\n <td> {$row2['quanteCopie']} </td>\n </tr>\n\n";
				}
				
				$tableService.="</tbody>\n</table>\n";	
			}
			else 
				$msg="<div style=\"color: green; font-size: 0.8em\"> L'utente {$_POST['selection']} non ha effettuato alcun acquisto. </div>";
		}
		else 
			$msg="<div style=\"color: red; font-size: 0.8em\"> Operazione fallita, utente non selezionato. </div>";
	}

	$mysqliConnection->close();
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>
		<title> Elenco Acquisti </title>
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
				<h2> Elenco acquisti utente </h2>
				<p> In questa sezione puoi visualizzare gli acquisti effettuati degli utenti del sito. </p>
				<p> Elenco utenti del sito: </p>
				<form action="<?php $_SERVER['PHP_SELF']?>"  method="post" >
					<?php echo $tableUsers; ?>
					<p>
						<input type="submit" name="invio" value="Vedi Acquisti"/>
						<input type="reset" value="Annulla selezionato" />
					</p>
				</form>
				<hr />
				<?php 
					if (isset ($msg)) echo $msg. "<hr />";
					
					if (isset ($tableService) ) {
						echo "<h4> Ecco i servizi acquistati dall'utente {$_POST['selection']}: </h4>";
						echo $tableService. "<hr />";
					}
				?>
				
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