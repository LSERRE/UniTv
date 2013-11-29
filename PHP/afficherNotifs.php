<?php

	session_start();
    header( 'content-type: text/html; charset=utf-8' );

	require '../PHP/exception.inc';
    require '../PHP/connexion_bdd.inc';

    $tabHtml = array();
    $tabVue = array();

    $Mysql = new Mysql();

    $resultNotif = $Mysql->TabResSQL("SELECT * FROM MembreSalon WHERE idCreateur='".$_SESSION['id']."'");

    $resultNotifInvite =  $Mysql->TabResSQL("SELECT * FROM MembreSalon WHERE idInvite='".$_SESSION['id']."'  AND boolNotificationCreateur=1");

    $resultNotifCommentaire = $Mysql->TabResSQL("SELECT * FROM Commentaire WHERE idCommente='".$_SESSION['id']."'");

    for ($i=0; $i < count($resultNotif) ; $i++) { 
        $resultInvite = $Mysql->TabResSQL("SELECT * FROM Utilisateur WHERE idUtilisateur='".$resultNotif[$i]['idInvite']."'");
        $resultSalon = $Mysql->TabResSQL("SELECT * FROM Salon WHERE idSalon='".$resultNotif[$i]['idSalon']."'");

        if ($resultNotif[$i]['boolNotificationCreateur'] == 0 ) {
            $date = explode(" ", $resultSalon[0]['dateProgramme']);
            $afficherHtml = '<div class="newNotif acceptNotif" ><img src="'.$resultInvite[0]['avatarUtilisateur'].'" /><div id="textNotif"><a class="utilisateur" href="pageUtilisateur.php?id='.$resultInvite[0]['idUtilisateur'].'">'.$resultInvite[0]['prenomUtilisateur'].' '.$resultInvite[0]['nomUtilisateur'].'</a><p> veut rejoindre votre salon <span>'.$resultSalon[0]['nomProgramme'].'</span>  le '.date("d/m", strtotime($date[0])).' à '.$date[1].' </p></div>';  
            $afficherHtml .= '<form id="formValidation" action="./PHP/validationSalon.php" method="post">';
            $afficherHtml .= '<input type="hidden" name="idCreateur" value="'.$resultNotif[$i]['idCreateur'].'" />';
            $afficherHtml .= '<input type="hidden" name="idSalon" value="'.$resultNotif[$i]['idSalon'].'" />';
            $afficherHtml .= '<input type="hidden" name="idInvite" value="'.$resultNotif[$i]['idInvite'].'" />';
            $afficherHtml .= '<input type="submit" name="boolValidation" value="OUI">';
            $afficherHtml .= '<input type="submit" name="boolValidation" value="NON">';
            // $afficherHtml .= '<input name="submit" id="submit" type="submit" value="Valider" />';
            $afficherHtml .= '</form></div>';
            $tabHtml[$resultNotif[$i]['heureNotification']] = $afficherHtml ;
        }else{
            $date = explode(" ", $resultSalon[0]['dateProgramme']);
            if($resultNotif[$i]['boolValidation'] == 1){
               $afficherHtml = '<div class="oldNotif" ><img src="'.$resultInvite[0]['avatarUtilisateur'].'" /><div id="textNotif"><p>Vous avez accepté </p><a class="utilisateur" href="pageUtilisateur.php?id='.$resultInvite[0]['idUtilisateur'].'">'.$resultInvite[0]['prenomUtilisateur'].' '.$resultInvite[0]['nomUtilisateur'].'</a><p> à rejoindre votre salon <span>'.$resultSalon[0]['nomProgramme'].'</span>  le '.date("d/m", strtotime($date[0])).' à '.$date[1].' </p></div></div>';
              $tabVue[$resultNotif[$i]['heureNotification']] = $afficherHtml ;
            }else{
                 $afficherHtml = '<div class="oldNotif" ><img src="'.$resultInvite[0]['avatarUtilisateur'].'" /><div id="textNotif"><p>Vous avez refusé </p><a class="utilisateur" href="pageUtilisateur.php?id='.$resultInvite[0]['idUtilisateur'].'">'.$resultInvite[0]['prenomUtilisateur'].' '.$resultInvite[0]['nomUtilisateur'].'</a><p> à rejoindre votre salon <span>'.$resultSalon[0]['nomProgramme'].'</span>  le '.date("d/m", strtotime($date[0])).' à '.$date[1].' </p></div></div>';
                 $tabVue[$resultNotif[$i]['heureNotification']] = $afficherHtml ;
            }
  
        }
    }

    for ($i=0; $i < count($resultNotifInvite) ; $i++) { 
        $resultCreateur = $Mysql->TabResSQL("SELECT * FROM Utilisateur WHERE idUtilisateur='".$resultNotifInvite[$i]['idCreateur']."'");

        $resultSalon = $Mysql->TabResSQL("SELECT * FROM Salon WHERE idSalon='".$resultNotifInvite[$i]['idSalon']."'");

        $date = explode(" ", $resultSalon[0]['dateProgramme']);
        if ($resultNotifInvite[$i]['boolNotificationInvite'] == 0) {
            if($resultNotifInvite[$i]['boolValidation'] != 0){
                $afficherHtml = '<div class="newNotif invite" ><img src="'.$resultCreateur[0]['avatarUtilisateur'].'" /><div id="textNotif"><a class="utilisateur" href="pageUtilisateur.php?id='.$resultCreateur[0]['idUtilisateur'].'">'.$resultCreateur[0]['prenomUtilisateur'].' '.$resultCreateur[0]['nomUtilisateur'].'</a><p> a accepté votre demande pour <span>'.$resultSalon[0]['nomProgramme'].'</span>  le '.date("d/m", strtotime($date[0])).' à '.$date[1].' </p></div></div>';
            }else{
                $afficherHtml = '<div class="newNotif invite" ><img src="'.$resultCreateur[0]['avatarUtilisateur'].'" /><div id="textNotif"><a class="utilisateur" href="pageUtilisateur.php?id='.$resultCreateur[0]['idUtilisateur'].'">'.$resultCreateur[0]['prenomUtilisateur'].' '.$resultCreateur[0]['nomUtilisateur'].'</a><p> n\'a pas accepté votre demande pour <span>'.$resultSalon[0]['nomProgramme'].'</span>  le '.date("d/m", strtotime($date[0])).' à '.$date[1].' </p></div></div>';
            }

            $tabHtml[$resultNotifInvite[$i]['heureNotification']] = $afficherHtml ;
        }else{

            if($resultNotifInvite[$i]['boolValidation'] == 1){
                $afficherHtml = '<div class="oldNotif" ><img src="'.$resultCreateur[0]['avatarUtilisateur'].'" /><div id="textNotif"><a class="utilisateur" href="pageUtilisateur.php?id='.$resultCreateur[0]['idUtilisateur'].'">'.$resultCreateur[0]['prenomUtilisateur'].' '.$resultCreateur[0]['nomUtilisateur'].'</a><p> a accepté votre demande pour <span>'.$resultSalon[0]['nomProgramme'].'</span>  le '.date("d/m", strtotime($date[0])).' à '.$date[1].' </p></div></div>';
            }else{
                $afficherHtml = '<div class="oldNotif" ><img src="'.$resultCreateur[0]['avatarUtilisateur'].'" /><div id="textNotif"><a class="utilisateur" href="pageUtilisateur.php?id='.$resultCreateur[0]['idUtilisateur'].'">'.$resultCreateur[0]['prenomUtilisateur'].' '.$resultCreateur[0]['nomUtilisateur'].'</a><p> n\'a pas accepté votre demande pour <span>'.$resultSalon[0]['nomProgramme'].'</span>  le '.date("d/m", strtotime($date[0])).' à '.$date[1].' </p></div></div>';
            }
            $tabVue[$resultNotifInvite[$i]['heureNotification']] = $afficherHtml;
        }
    }

    for ($i=0; $i < count($resultNotifCommentaire); $i++) { 
        $afficherCommentaire = '';
        $resultCreateur = $Mysql->TabResSQL("SELECT * FROM Utilisateur WHERE idUtilisateur='".$resultNotifCommentaire[$i]['idCommentateur']."'");
        if ($resultNotifCommentaire[$i]['boolCommentaire'] == 0) {

            $afficherCommentaire .= '<div class="newNotif commentaire" ><img src="'.$resultCreateur[0]['avatarUtilisateur'].'" /><div id="textNotif"><a class="utilisateur" href="pageUtilisateur.php?id='.$resultCreateur[0]['idUtilisateur'].'">'.$resultCreateur[0]['prenomUtilisateur'].' '.$resultCreateur[0]['nomUtilisateur'].'</a><p> a posté un commentaire sur</p><a class="utilisateur" href="pageUtilisateur.php?id='.$resultNotifCommentaire[$i]['idCommente'].'">votre page</a></div></div>';
            $tabHtml[$resultNotifCommentaire[$i]['heureCommentaire']] = $afficherCommentaire ;
        }else{
            $afficherCommentaire .= '<div class="oldNotif commentaire" ><img src="'.$resultCreateur[0]['avatarUtilisateur'].'" /><div id="textNotif"><a class="utilisateur" href="pageUtilisateur.php?id='.$resultCreateur[0]['idUtilisateur'].'">'.$resultCreateur[0]['prenomUtilisateur'].' '.$resultCreateur[0]['nomUtilisateur'].'</a><p> a posté un commentaire sur</p><a class="utilisateur" href="pageUtilisateur.php?id='.$resultNotifCommentaire[$i]['idCommente'].'">votre page</a></div></div>';
            $tabVue[$resultNotifCommentaire[$i]['heureCommentaire']] = $afficherCommentaire ;
        }


    }

    ksort($tabHtml);
    ksort($tabVue);

    $reverse_tabHtml = array_reverse($tabHtml);
    $reverse_tabVue = array_reverse($tabVue);

    foreach ($reverse_tabHtml as $key => $val) {
        echo $val;
    }
    foreach ($reverse_tabVue as $key => $val) {
        echo $val;
    }

?>