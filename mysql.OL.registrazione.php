<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	require_once("./connessione.php");

	//variabile che quando vale 1 indica che non e' possibile usare lo username inserito
	// poiche' gia' usato da un altro utente del sito
	$errore=0;
	
	if (isset($_POST['invio'])){
		if ($_POST['invio']=="Reset"){
			$_POST['username']="";
			$_POST['genere']="";
			$_POST['nazione']="";
		}
		else {
			$msg=""; //contiene concatenazione di messaggi di errore
			
			//controllo che sia stato inserito lo username
			if (empty($_POST['username'])){
				$msg="<div style=\"color: red; font-size: 1.1em\"> Errore: username non inserito! </div>\n";
			}
			else {
				//controllo che lo username inserito sia univoco 
				//(non ci siano due utenti con lo stesso username)
				$sql="SELECT *
					FROM $OLuser_table_name
					WHERE username=\"{$_POST['username']}\"
					;";
				
				if (!$resultQ=mysqli_query($mysqliConnection, $sql)) {
					print ("Dammit! Can't execute check-username query.\n");
					exit();
				}
				
				$row=mysqli_fetch_array($resultQ);
				
				if (!empty($row)){
					$msg="<div style=\"color: red; font-size: 1.1em\"> Errore: username non disponibile! </div>\n";
					$errore=1;
				}
			}
			
			
			
			//controllo che sia stata inserita la password
			if (empty($_POST['password'])){
				$msg.="<div style=\"color: red; font-size: 1.1em\"> Errore: password non inserita! </div>\n";
			}
			else {
				//controllo che le password coincidano
				if ($_POST['password']!=$_POST['confPassword'])
					$msg.="<div style=\"color: red; font-size: 1.1em\"> Errore: le password non coincidono! </div>\n";
				}
			
			
			//controllo che sia stato scelto il genere
			if (!isset ($_POST['genere'])){
				$msg.="<div style=\"color: red; font-size: 1.1em\"> Errore: genere non specificato! </div>\n";
			}
			
			//controllo che sia stato scelta la nazione
			if ( $_POST['nazione']=="Scegli Nazione"){
				$msg.="<div style=\"color: red; font-size: 1.1em\"> Errore: nazione non specificata! </div>\n";
			}
			
			
			//se i controlli precedenti sono andati, si procede con l'inserimento del prodotto nel db
			if (empty($msg)){
				
				//ricavo il numero di righe della tabella OLuser necessario
				//per l'assegnazione dello userId all'utente che si sta registrando
				$sql="SELECT *
					FROM $OLuser_table_name
				;";
				
				if (!$resultQ = mysqli_query($mysqliConnection, $sql)) {
					printf("Dammit! Can't execute count-row query.\n");
					exit();
				}
				
				$numRow=0;
				while ($row=mysqli_fetch_array ($resultQ)) {
					$numRow++;
				}
				
				/*echo $numRow;*/
				$numRow++;
				$userId="us".$numRow;
				
				//query inserire il nuovo utente nel db
				$sql = "INSERT INTO $OLuser_table_name
					(userId, username, password, genere, nazione, tipologia, sommeSpese, ban)
					VALUES
					('$userId', '{$_POST['username']}', '{$_POST['password']}', '{$_POST['genere']}', '{$_POST['nazione']}', 'utente', '0', 'non attivo')
				";

				// il risultato della query va in $resultQ
				if (!$resultQ = mysqli_query($mysqliConnection, $sql)) {
					printf("Dammit! Can't execute create-row query.\n");
					exit();
				}
				$msg="<p style=\"color: green; font-size: 1.1em\"> Registrazione Completata! <br />Torna al login per accedere. </p>\n";
				
				$_POST['username']="";
				$_POST['genere']="";
				$_POST['nazione']="";
			}
		}	
	}
	
	$mysqliConnection->close();
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
						<input type="text" name="username" value="<?php if (isset($_POST['username'])&& $errore!=1) echo $_POST['username'];?>" size="30" />
					</p>
					
					<p> Password <br /> 
						<input type="password" name="password" size="30" />
					</p>
					
					<p> Conferma Password <br /> 
						<input type="password" name="confPassword" size="30" />
					</p>
					
					<p> Genere: <br /> 
						<input type="radio" name="genere" value="femmina" 
							<?php if (isset($_POST['genere']) && $_POST['genere']=="femmina") echo "checked=\"checked\"";?> /> Femmina
						<input type="radio" name="genere" value="maschio" 
							<?php if (isset($_POST['genere']) && $_POST['genere']=="maschio") echo "checked=\"checked\"";?>/> Maschio	
					</p>
					
					<p> Nazione <br />
						<select  name="nazione" size="1">
							<option value="Scegli Nazione">Scegli Nazione</option>
							<option value="Belgio" <?php if (isset($_POST['nazione']) && $_POST['nazione']=="Belgio") echo "selected=\"selected\"";?>>Belgio</option>
							<option value="Italia" <?php if (isset($_POST['nazione']) && $_POST['nazione']=="Italia") echo "selected=\"selected\"";?>>Italia</option>
							<option value="Francia" <?php if (isset($_POST['nazione']) && $_POST['nazione']=="Francia") echo "selected=\"selected\"";?>>Francia</option>
							<option value="Spagna" <?php if (isset($_POST['nazione']) && $_POST['nazione']=="Spagna") echo "selected=\"selected\"";?>>Spagna</option>
							<option value="Germania" <?php if (isset($_POST['nazione']) && $_POST['nazione']=="Germania") echo "selected=\"selected\"";?>>Germania</option>
							<option value="Regno Unito" <?php if (isset($_POST['nazione']) && $_POST['nazione']=="Regno Unito") echo "selected=\"selected\"";?>>Regno Unito</option>
						</select>
					</p>
					
					<p id="signUp"><a href="mysql.OL.login.php"> Signin </a></p>
					<?php if (isset($msg))echo $msg; ?>
					
					<div id="buttons">
						<input type="submit" name="invio" value="Registrati" />
						<input type="submit" name="invio" value="Reset" />
					</div>
				</form>
			</div>
		<hr />
		<!-- stampa di $_POST per controllo 
		<p> $_POST: <?php print_r($_POST); ?> </p> -->
	</body>
</html>
