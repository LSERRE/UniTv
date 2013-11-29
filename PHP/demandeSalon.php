<?php

	session_start();


	if (isset($_POST['idCreateur']) || isset($_POST['idInvite']) || isset($_POST['idSalon'])) {
		require 'exception.inc';
	    require 'connexion_bdd.inc';

	    $Mysql = new Mysql();

	    $insert = $Mysql->ExecuteSQL("INSERT INTO MembreSalon VALUES ('".$_POST['idSalon']."','".$_POST['idInvite']."', '".$_POST['idCreateur']."', 0, 0, NULL, '".time()."' )");

	    header('Location: ../index.php');     
	}

?>