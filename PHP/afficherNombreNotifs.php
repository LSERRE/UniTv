<?php

	session_start();

	require '../PHP/exception.inc';
    require '../PHP/connexion_bdd.inc';

    $Mysql = new Mysql();

	$resultNotif = $Mysql->TabResSQL("SELECT * FROM MembreSalon WHERE idCreateur='".$_SESSION['id']."' AND boolNotificationCreateur=0 ");
    $resultNotifBis = $Mysql->TabResSQL("SELECT * FROM MembreSalon WHERE idInvite='".$_SESSION['id']."' AND boolNotificationInvite=0  AND boolNotificationCreateur=1");
    $resultNotifTer = $Mysql->TabResSQL("SELECT * FROM Commentaire WHERE idCommente='".$_SESSION['id']."' AND boolCommentaire=0");

    $nb = count($resultNotif)+count($resultNotifBis)+count($resultNotifTer);
    echo $nb;
?>