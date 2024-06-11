<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title> Creazione e popolamento OceanLoversDB </title>
	</head>

	<body>
		<h2> Creazione e popolamento OceanLoversDB </h2>

		<?php			
		error_reporting(E_ALL &~E_NOTICE);

		// dati sul database e le tabelle
		$db_name = "OceanLovers";
		$OLuser_table_name = "OLuser";
		$OLwhaleWatch_table_name = "OLwhaleWatch";
		$OLdolphinSwim_table_name = "OLdolphinSwim";
		$OLsharkDive_table_name = "OLsharkDive";
		$OLacquistiUser_table_name = "OLacquistiUser";
		

		// connessione al database MySQL
		$mysqliConnection = new mysqli("localhost", "archer", "archer");


		// controllo della connessione 
		if (mysqli_connect_errno()) {
			printf("<p> Oops, abbiamo problemi con la connessione al db: %s </p>\n", mysqli_connect_error());
			exit();
		}

		// creazione del database OceanLovers
		$queryCreazioneDatabase = "CREATE DATABASE $db_name";
		if ($resultQ = mysqli_query($mysqliConnection, $queryCreazioneDatabase)) {
			printf("<p> Database OceanLovers creato... </p>\n");
		}
		else {
			printf("<p> Whoops! niente creazione del db! Che sara successo?? </p>\n");
			exit();
		}

		// chiusura connessione al database MySQL
		$mysqliConnection->close();
	

		// connessione al database OceanLovers
		$mysqliConnection = new mysqli("localhost", "archer", "archer", $db_name);
		// controllo della connessione 
		if (mysqli_errno($mysqliConnection)) {
			printf("<p> Oops, abbiamo problemi con la connessione al db: %s </p>\n", mysqli_error($mysqliConnection));
			exit();
		}
		
		//creazione tabella OLuser
		$sqlQuery = "CREATE TABLE if not exists $OLuser_table_name (";
		$sqlQuery.= "userId varchar (30) NOT NULL, primary key (userId), ";
		$sqlQuery.= "username varchar (50) NOT NULL, ";
		$sqlQuery.= "password varchar (32) NOT NULL, ";
		$sqlQuery.= "genere varchar (32) NOT NULL, ";
		$sqlQuery.= "nazione varchar (32) NOT NULL, ";
		$sqlQuery.= "tipologia varchar (20) NOT NULL, ";
		$sqlQuery.= "sommeSpese float, ";
		$sqlQuery.= "ban varchar (15) NOT NULL";
		$sqlQuery.= ");";

		echo "<p>$sqlQuery</p>";

		if ($resultQ = mysqli_query($mysqliConnection, $sqlQuery))
			printf("<p> Tabella OLuser creata... </p>\n");
		else {
			printf("<p> Whoops! niente creazione tabella OLuser! Che sara' successo?? </p>\n");
			exit();
		}

		//creazione tabella OLwhaleWatch
		$sqlQuery = "CREATE TABLE if not exists $OLwhaleWatch_table_name (";
		$sqlQuery.= "serviceId varchar (30) NOT NULL, primary key (serviceId), ";
		$sqlQuery.= "nomeServizio varchar (100) NOT NULL, ";
		$sqlQuery.= "data date NOT NULL, ";
		$sqlQuery.= "costo float NOT NULL";
		$sqlQuery.= ");";

		echo "<p>$sqlQuery</p>";

		if ($resultQ = mysqli_query($mysqliConnection, $sqlQuery))
			printf("<p> Tabella OLwhaleWatch creata ...</p>\n");
		else {
			printf("<p> Whoops! niente creazione Tabella OLwhaleWatch! Che sara' successo?? </p>\n");
			exit();
		}

		//creazione tabella OLdolphinSwim
		$sqlQuery = "CREATE TABLE if not exists $OLdolphinSwim_table_name (";
		$sqlQuery.= "serviceId varchar (30) NOT NULL, primary key (serviceId), ";
		$sqlQuery.= "nomeServizio varchar (100) NOT NULL, ";
		$sqlQuery.= "data date NOT NULL, ";
		$sqlQuery.= "costo float ";
		$sqlQuery.= ");";

		echo "<p>$sqlQuery</p>";

		if ($resultQ = mysqli_query($mysqliConnection, $sqlQuery))
			printf("<p> Tabella OLdolphinSwim creata ... </p>\n");
		else {
			printf("<p> Whoops! niente creazione Tabella OLdolphinSwim! Che sara' successo?? </p>\n");
			exit();
		}
		
		//creazione tabella OLsharkDive
		$sqlQuery = "CREATE TABLE if not exists $OLsharkDive_table_name (";
		$sqlQuery.= "serviceId varchar (30) NOT NULL, primary key (serviceId), ";
		$sqlQuery.= "nomeServizio varchar (100) NOT NULL, ";
		$sqlQuery.= "data date NOT NULL, ";
		$sqlQuery.= "costo float ";
		$sqlQuery.= ");";

		echo "<p>$sqlQuery</p>";

		if ($resultQ = mysqli_query($mysqliConnection, $sqlQuery))
			printf("<p> Tabella OLsharkDive creata ... </p>\n");
		else {
			printf("<p> Whoops! niente creazione Tabella OLsharkDive! Che sara' successo?? </p>\n");
			exit();
		}
			
	
		//creazione tabella acquistiUser
		$sqlQuery = "CREATE TABLE if not exists $OLacquistiUser_table_name (";
		$sqlQuery.= "userId varchar (30) NOT NULL, ";
		$sqlQuery.= "serviceId varchar (30) NOT NULL, ";
		$sqlQuery.= "tipoServizio varchar (100) NOT NULL, ";
		$sqlQuery.= "quanteCopie INT NOT NULL, ";
		$sqlQuery.= "PRIMARY KEY (userId, serviceId, tipoServizio), ";
		$sqlQuery.= "FOREIGN KEY (userId) REFERENCES $OLuser_table_name (userId) ";
		$sqlQuery.= ");";

		echo "<p> $sqlQuery </p>";

		if ($resultQ = mysqli_query($mysqliConnection, $sqlQuery))
			printf("<p> Tabella acquistiUser creata... </p>\n");
		else {
			printf("<p> Whoops! niente creazione Tabella acquistiUser! Che sara' successo?? </p>\n");
			exit();
		}
		
	
		// popolamento tabella OLuser
		$sql = "INSERT INTO $OLuser_table_name
			(userId, username, password, genere, nazione, tipologia, sommeSpese, ban )
			VALUES
			(\"us1\", \"Master\", \"dragon\", \"maschio\", \"Italia\", \"admin\", \"0\", \"non attivo\")
			";

		if ($resultQ = mysqli_query($mysqliConnection, $sql))
			printf("<p> Popolamento tabella OLuser eseguito...</p>\n");
		else {
			printf("<p> Whoops! Couldn't populate OLuser table. </p>\n");
			exit();
		}


		$sql = "INSERT INTO $OLuser_table_name
			(userId, username, password, genere, nazione, tipologia, sommeSpese, ban )
			VALUES
			(\"us2\", \"Dario\", \"atomico\", \"maschio\", \"Italia\", \"gestore\", \"0\", \"non attivo\")
			";

		if ($resultQ = mysqli_query($mysqliConnection, $sql))
			printf("<p> Popolamento tabella OLuser eseguito... </p>\n");
		else {
			printf("<p> Whoops! Couldn't populate OLuser table. </p>\n");
			exit();
		}
		
		
		$sql = "INSERT INTO $OLuser_table_name
			(userId, username, password, genere, nazione, tipologia, sommeSpese, ban )
			VALUES
			(\"us3\", \"Thomas\", \"naku\", \"maschio\", \"Italia\", \"utente\", \"0\", \"non attivo\")
			";

		if ($resultQ = mysqli_query($mysqliConnection, $sql))
			printf("<p> Popolamento tabella OLuser eseguito... </p>\n");
		else {
			printf("<p> Whoops! Couldn't populate OLuser table. </p>\n");
			exit();
		}
	
	
		$sql = "INSERT INTO $OLuser_table_name
			(userId, username, password, genere, nazione, tipologia, sommeSpese, ban )
			VALUES
			(\"us4\", \"Enzo\", \"vinzz\", \"maschio\", \"Italia\", \"utente\", \"0\", \"non attivo\")
			";

		if ($resultQ = mysqli_query($mysqliConnection, $sql))
			printf("<p> Popolamento tabella OLuser eseguito... </p>\n");
		else {
			printf("<p> Whoops! Couldn't populate OLuser table. </p>\n");
			exit();
		}
		
		
		$sql = "INSERT INTO $OLuser_table_name
			(userId, username, password, genere, nazione, tipologia, sommeSpese, ban )
			VALUES
			(\"us5\", \"Ignazio\", \"pignuis\", \"maschio\", \"Italia\", \"utente\", \"0\", \"attivo\")
			";

		if ($resultQ = mysqli_query($mysqliConnection, $sql))
			printf("<p> Popolamento tabella OLuser eseguito... </p>\n");
		else {
			printf("<p> Whoops! Couldn't populate OLuser table. </p>\n");
			exit();
		}
		
		
		// popolamento tabella OLwhaleWatch
		$sql = "INSERT INTO $OLwhaleWatch_table_name
			(serviceId, nomeServizio, data, costo)
			VALUES
			(\"ww1\", \"Whale Watching\", \"2024-07-26\", \"75\")
			";
		echo $sql;

		if ($resultQ = mysqli_query($mysqliConnection, $sql))
			printf("<p> Popolamento tabella OLwhaleWatch eseguito... </p>\n");
		else {
			printf("<p> Whoops! Couldn't populate OLwhaleWatch table. </p>\n");
			exit();
		}


		$sql = "INSERT INTO $OLwhaleWatch_table_name
			(serviceId, nomeServizio, data, costo)
			VALUES
			(\"ww2\", \"Whale Watching\", \"2024-07-29\", \"75\")
			";
		echo $sql;

		if ($resultQ = mysqli_query($mysqliConnection, $sql))
			printf("<p> Popolamento tabella OLwhaleWatch eseguito... </p>\n");
		else {
			printf("<p> Whoops! Couldn't populate OLwhaleWatch table. </p>\n");
			exit();
		}


		// popolamento tabella OLdolphinSwim
		$sql = "INSERT INTO $OLdolphinSwim_table_name
			(serviceId, nomeServizio, data, costo)
			VALUES
			(\"ds1\", \"Dolphin Swimming\", \"2024-08-10\", \"89\")
			";
		echo $sql;
		if ($resultQ = mysqli_query($mysqliConnection, $sql))
			printf("<p> Popolamento tabella OLdolphinSwim eseguito... </p>\n");
		else {
			printf("<p> Whoops! Couldn't populate OLdolphinSwim table. </p>\n");
			exit();
		}

		$sql = "INSERT INTO $OLdolphinSwim_table_name
			(serviceId, nomeServizio, data, costo)
			VALUES
			(\"ds2\", \"Dolphin Swimming\", \"2024-08-11\", \"89\")
			";
		echo $sql;
		if ($resultQ = mysqli_query($mysqliConnection, $sql))
			printf("<p> Popolamento tabella OLdolphinSwim eseguito... </p>\n");
		else {
			printf("<p> Whoops! Couldn't populate OLdolphinSwim table. </p>\n");
			exit();
		}



		// popolamento tabella OLsharkDive
		$sql = "INSERT INTO $OLsharkDive_table_name
			(serviceId, nomeServizio, data, costo)
			VALUES
			(\"sh1\", \"Shark Diving\", \"2024-07-25\", \"135\")
			";
		echo $sql;
		if ($resultQ = mysqli_query($mysqliConnection, $sql))
			printf("<p> Popolamento tabella OLsharkDive eseguito... </p>\n");
		else {
			printf("<p> Whoops! Couldn't populate OLsharkDive table. </p>\n");
			exit();
		}

		$sql = "INSERT INTO $OLsharkDive_table_name
			(serviceId, nomeServizio, data, costo)
			VALUES
			(\"sh2\", \"Shark Diving\", \"2024-07-28\", \"135\")
			";
		echo $sql;
		if ($resultQ = mysqli_query($mysqliConnection, $sql))
			printf("<p> Popolamento tabella OLsharkDive eseguito... </p>\n");
		else {
			printf("<p> Whoops! Couldn't populate OLsharkDive table. </p>\n");
			exit();
		}

		
		//chiusura connessione con database OceanLovers
		mysqli_close($mysqliConnection);
		?>
	</body>
</html>