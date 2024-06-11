<?php
	// dati relativi al db e alle tabelle da usare in uno script che includa questo file
	// in ogni script in cui dobbiamo usare il db, si include questo file, con require_once()
	$db_name = "OceanLovers";
	$OLuser_table_name = "OLuser";
	$OLwhaleWatch_table_name = "OLwhaleWatch";
	$OLdolphinSwim_table_name = "OLdolphinSwim";
	$OLsharkDive_table_name = "OLsharkDive";
	$OLacquistiUser_table_name = "OLacquistiUser";
	
	
	// Connessione al database OceanLovers
	$mysqliConnection = new mysqli("localhost", "archer", "archer", $db_name);

	// controllo della connessione
	if (mysqli_connect_errno()) {
		printf("Abbiamo problemi con la connessione al db: %s\n", mysqli_connect_error($mysqliConnection));
		exit();
	}
?>