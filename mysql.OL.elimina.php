<?php
	error_reporting (E_ALL &~E_NOTICE);

	session_start();               

	if (!isset($_SESSION['accessoPermesso']) || $_SESSION['accessoPermesso']!="utente") 
	   header('Location: mysql.OL.login.php');
   
   require_once ("./connessione.php");
   
   $msg="";
   $msgCosto="";
   $tabellaCarrello="";
   
	if (!isset($_SESSION['carrello']) || ($_SESSION['costoCarrello']==0)) {
		$msg.= "<p> - Carrello vuoto - </p>\n";
	} 
	else {
			$msg.="<p> Seleziona quel che vuoi eliminare dal carrello: </p>\n";
			
			//creazione tabella che mostra il contenuto del carrello
			$tabellaCarrello.="<table border=\"2px\" cellspacing=\"2px\" cellpadding=\"3px\" style=\"border-color: fuchsia\">\n";
			$tabellaCarrello.="<thead>\n <tr>\n <th></th>\n <th> Service Id </th>\n <th> Nome Servizio </th>\n <th> Data </th>\n <th> Costo </th>\n </tr>\n</thead>\n\n<tbody>\n";
			
			foreach ($_SESSION['carrello'] as $chiave=>$valore){
				
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
							$tabellaCarrello.="<tr>\n <td> <input type=\"checkbox\" name=\"eliminandi[]\" value=\"$chiave\" /> </td>\n";
							$tabellaCarrello.=" <td> {$row3['serviceId']} </td>\n <td> {$row3['nomeServizio']} </td>\n <td> {$row3['data']} </td>\n <td> {$row3['costo']}&euro; </td>\n</tr>\n\n";
					}
					else {
						//e' un servizio dolphinSwim
							$tabellaCarrello.="<tr>\n <td> <input type=\"checkbox\" name=\"eliminandi[]\" value=\"$chiave\" /> </td>\n";
							$tabellaCarrello.=" <td> {$row2['serviceId']} </td>\n <td> {$row2['nomeServizio']} </td>\n <td> {$row2['data']} </td>\n <td> {$row2['costo']}&euro; </td>\n</tr>\n\n";
					}
				}
				else {
					//e' un servizio whaleWatch
						$tabellaCarrello.="<tr>\n <td> <input type=\"checkbox\" name=\"eliminandi[]\" value=\"$chiave\" /> </td>\n";
						$tabellaCarrello.=" <td> {$row1['serviceId']} </td>\n <td> {$row1['nomeServizio']} </td>\n <td> {$row1['data']} </td>\n <td> {$row1['costo']}&euro; </td>\n</tr>\n\n";
				}
			}

			$tabellaCarrello.="</tbody>\n </table>\n";
				
			if ($_SESSION['costoCarrello']!=0)
					$msgCosto.="<p> Costo totale del carrello: {$_SESSION['costoCarrello']}&euro; </p>\n";
			
			
			
			if (isset($_POST['eliminandi'])) {
				//bisogna eliminare le cose selezionate in eliminandi[] e
				//togliere da $_SESSION['costoCarrello'] il costo degli elementi eliminati
				foreach ($_POST['eliminandi'] as $k=>$indiceDaEliminare){
					$serviceId=$_SESSION['carrello'] [$indiceDaEliminare];
					
					$sql1 = "SELECT *
							FROM $OLwhaleWatch_table_name
							WHERE serviceId = \"$serviceId\"
						";
					
				   $sql2 = "SELECT *
						  FROM $OLdolphinSwim_table_name
						  WHERE serviceId  = \"$serviceId\"
						";
						
					$sql3 = "SELECT *
						  FROM $OLsharkDive_table_name
						  WHERE serviceId  = \"$serviceId\"
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
							$_SESSION['costoCarrello']-=$row3['costo'];	
						}
						else {
							//e' un servizio dolphinSwim
							$_SESSION['costoCarrello']-=$row2['costo'];
						}
					}
					else {
						//e' un servizio whaleWatch
						$_SESSION['costoCarrello']-=$row1['costo'];
					}
				
					unset($_SESSION['carrello'][$indiceDaEliminare]);
				}
				header('Location: mysql.OL.elimina.php');
			}		
	}

	//chiusura connessione con il db OceanLovers
	$mysqliConnection->close();
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>
		<title> Aggiungi Servizi </title>
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
				<h2> Eliminazione servizi </h2>
				<?php echo $msg; ?>

				<form action="<?php $_SERVER['PHP_SELF']?>"  method="post" >
					<table>
						<tr>
							<td  style="width: 30%">
								<p style="margin-bottom: 15%">
									<input type="reset" name="annulla" value="Annulla le selezioni" />
								</p>
								
								<p style="margin-top: 15%">
									<input type="submit" name="cancellaSelezionati" value="Cancella i selezionati" />
								</p>
							</td>

							<td> <?php echo $tabellaCarrello; ?> </td>
						</tr>
					</table>
				</form>
				
				<?php echo $msgCosto; ?>
				
				<hr />
			
				<!-- inizio parte per visualizzare/controllare il contenuto di $_SESSION e $_POST -->
				<table>
					<tr>
						<td style="width: 50%">
							<?php 
								echo "\$_SESSION:<br />";
								foreach ($_SESSION as $k=>$v){
									if ($k!="carrello")	
										echo "[".$k."]  ".$v."\n<br />";
								}
							?>
						</td>
						
						<td style="width: 50%">
							<?php
								echo "\$_POST:<br />";
								foreach ($_POST as $k=>$v)
								  echo "[$k] $v<br />";
							?>
						</td>
					</tr>
				</table>
				<!-- fine parte per visualizzare/controllare il contenuto di $_SESSION e $_POST -->
				<hr />
				
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