<?php

	session_start();


	if (isset($_POST['idCreateur']) || isset($_POST['idInvite']) || isset($_POST['idSalon']) || isset($_POST['boolValidation'])) {
		require 'exception.inc';
	    require 'connexion_bdd.inc';

	    $Mysql = new Mysql();

	    $validation = 0;

	    if ($_POST['boolValidation'] == 'OUI') {
	    	$validation = 1;
	    }  	
	  
	    $insert = $Mysql->ExecuteSQL("UPDATE  MembreSalon SET boolNotificationCreateur = 1, boolValidation = ".$validation.", heureNotification=".time()." WHERE idSalon='".$_POST['idSalon']."' AND idInvite='".$_POST['idInvite']."' AND idCreateur='".$_POST['idCreateur']."'");
	    $resultSalon = $Mysql->TabResSQL("SELECT * FROM Salon WHERE idSalon='".$_POST['idSalon']."'");
	    $resultInvite =  $Mysql->TabResSQL("SELECT * FROM Utilisateur WHERE idUtilisateur='".$_POST['idInvite']."'");
	    $resultCreateur = $Mysql->TabResSQL("SELECT * FROM Utilisateur WHERE idUtilisateur='".$_POST['idCreateur']."'");

	    $date = explode(" ", $resultSalon[0]['dateProgramme']);


	    if(!empty($resultInvite[0]['mailUtilisateur'])){
	    	    // Plusieurs destinataires
			     $to  = $resultInvite[0]['mailUtilisateur'] ; 

			     // Sujet
			     $subject = 'Unitv : Salon '.$resultSalon[0]['nomProgramme'];

			     $message='';

			     if($validation){
			     	// message
				     $message .= '
				     <html>
				      <head>
				       <title>Actualité Unitv</title>
				      </head>
				      <body>
				       <p>Vous avez été accepté au salon '.$resultSalon[0]['nomProgramme'].' de'.$resultCreateur[0]['prenomUtilisateur'].' le '.date("d/m", strtotime($date[0])).' à '.$date[1].'</p>
				       <p>L\'adresse de l\'ôte est : '.$resultSalon[0]['adresse'].' à '.$resultSalon[0]['ville'].'</p>
				       <p>Pour avoir plus de détail, son numéro de téléphone est le '.$resultInvite[0]['telephoneUtilisateur'].'</p>
				       <br/>
					   <br/>
				       <p>UniTv.fr</p>
				      </body>
				     </html>
				     ';
			     }else{
			     	$message .= '
				     <html>
				      <head>
				       <title>Actualité Unitv</title>
				      </head>
				      <body>
				       <p>Malheureusement, vous avez été refusé au salon '.$resultSalon[0]['nomProgramme'].' de'.$resultCreateur[0]['prenomUtilisateur'].' le '.date("d/m", strtotime($date[0])).' à '.$date[1].'</p>
				       <p>A bientôt sur <a href:"http://unitv.fr">Unitv.fr</a></p>
				       <br/>
					   <br/>
				       <p>UniTv.fr</p>
				       </body>
				     </html>
				     ';
			     }
			     

			     // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
			     $headers  = 'MIME-Version: 1.0' . "\r\n";
			     $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

			     // En-têtes additionnels
			     // $headers .= 'To:'.$resultInvite[0]['prenomUtilisateur'].' <'.$resultInvite[0]['mailUtilisateur']. ">\r\n";
			     $headers .= 'From: Unitv <no-reply@unitv.fr>' . "\r\n";

			     // Envoi
			     mail($to, $subject, $message, $headers);
			 }

	    header('Location: ../index.php');     
	}

?>