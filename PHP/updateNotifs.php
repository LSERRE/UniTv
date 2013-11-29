<?php

	session_start();

	require '../PHP/exception.inc';
    require '../PHP/connexion_bdd.inc';

    $Mysql = new Mysql();

    $insert = $Mysql->ExecuteSQL("UPDATE  MembreSalon SET boolNotificationInvite = 1 WHERE idInvite='".$_SESSION['id']."' AND boolNotificationCreateur=1 ");
    $insert = $Mysql->ExecuteSQL("UPDATE  Commentaire SET boolCommentaire = 1 WHERE idCommente='".$_SESSION['id']."'");
?>