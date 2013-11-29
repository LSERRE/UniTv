<?php

    session_start();
    header( 'content-type: text/html; charset=utf-8' );

    require './PHP/exception.inc';
    require './PHP/connexion_bdd.inc';
    require './facebookSdk/src/facebook.php';



    $Mysql = new Mysql();
    
    $facebook = new Facebook(array(
            'appId'  => '213048858862502',
            'secret' => 'c8a611b2ce186c6c582c347ddcb22aa4'
          ));
        
        $user = $facebook->getUser();

    

        if ($user) {
          try {

            $user_profile = $facebook->api('/me');


          } catch (FacebookApiException $e) {
            error_log($e);
            $user = null;
          }
        }

        // URL de Login ou de logout
        if ($user) {
          $logoutUrl = './PHP/logout.php';
        } else {
          $loginUrl = $facebook->getLoginUrl(array('scope' => 'email,read_stream,user_birthday '));
          $loginUrl = str_replace("afficherSalon.php", " ", $loginUrl);
          
        }
        if($user){

          $queries = array(
            array('method' => 'GET', 'relative_url' => '/'.$user),
            );
         
          try{
            $batchResponse = $facebook->api('?batch='.json_encode($queries), 'POST');
          }catch(Exception $o){
            error_log($o);
          }
         
          $user_info    = json_decode($batchResponse[0]['body'], TRUE);
         
          try
          {

            $result = $Mysql->TabResSQL("SELECT * FROM Utilisateur WHERE idFacebook='".$user_info['id']."'");

            if(empty($result)){

                  $age = explode('/', $user_info['birthday']);
                  $age = date("Y")-$age[2];

                  $insert = $Mysql->ExecuteSQL("INSERT INTO Utilisateur VALUES ('','".$user_info['id']."','".$user_info['last_name']."','".$user_info['first_name']."','".$age."','".$user_info['email']."', 'https://graph.facebook.com/".$user."/picture','','','".$user_info['gender']."')");
                  $result = $Mysql->TabResSQL("SELECT * FROM Utilisateur WHERE idFacebook='".$user_info['id']."'");
            }

            $_SESSION['id'] = $result[0]['idUtilisateur'];

          }
          catch (MySQLExeption $e)
          {
            echo $e -> RetourneErreur();
          }

       }



    $resultEvent = $Mysql->TabResSQL("SELECT * FROM Salon WHERE idSalon='".$_POST['idSalon']."'");
    $resultUtilisateur = $Mysql->TabResSQL("SELECT * FROM Utilisateur WHERE idUtilisateur='".$resultEvent[0]['idCreateur']."'");
    $resultSalon =  $Mysql->TabResSQL("SELECT * FROM MembreSalon WHERE idSalon='".$_POST['idSalon']."' AND idInvite='".$_SESSION['id']."'");

    $resultNbSalon =  $Mysql->TabResSQL("SELECT * FROM MembreSalon WHERE idSalon='".$_POST['idSalon']."' && boolValidation=1");

    $nbPlace = $resultEvent[0]['nbInvites'] - count($resultNbSalon);
	  $nbPersonne = count($resultNbSalon) ;
    $date = explode(" ", $resultEvent[0]['dateProgramme']);

	$image = $resultEvent[0]['image'];
	if(empty($image)){
		$image = "img/close.png";
	}

  $nomProgramme = $resultEvent[0]['nomProgramme'];
  $tailleTotale = strlen($nomProgramme.$resultEvent[0]['chaineProgramme']);
  if ( $tailleTotale >= 52) {
    $tailleCut = 49-strlen($resultEvent[0]['chaineProgramme']);
    $nomProgramme = substr($nomProgramme, 0, $tailleCut).'...';
  }
	
	$html = '
				<header>
					<div><img src="'.$image.'" alt="Image du programme" /></div>
					<h2><span class="rouge">'.$resultEvent[0]['chaineProgramme'].'</span> '.$nomProgramme.'</h2>
					<p>Salon proposé par <a class="utilisateur" href="pageUtilisateur?id='.$resultEvent[0]['idCreateur'].'">'.$resultUtilisateur[0]['prenomUtilisateur'].'</a></p>
				</header>';
   
    if($nbPlace > 1){
        $place = '<p>'.$nbPlace.' places</p>';
    }else if($nbPlace == 1){
        $place = '<p>'.$nbPlace.' place</p>';
    }else{
        $place ='<p><span class="rouge">Complet</span></p>';
    }
   
   $html .='
			   <div id="infosDeBase">
					<time>Le '.date("d/m", strtotime($date[0])).' à '.$date[1].'</time>
					'.$place.'
					<a id="extraitVideo" href="#"><img src="img/video.svg" alt="Icone Video" /></a>
				</div>
				';

  $description = substr($resultEvent[0]['descriptionProgramme'], 0, 347).'...'; 
	
	$html .='
				<div id="descriptionSalon">
					<p>'.$description.'</p>
					<h5>L’hôte accepte</h5>
					<p>'.$resultEvent[0]['accepte'].'</p>
					<h5>L’hôte refuse</h5>
					<p>'.$resultEvent[0]['refuse'].'</p>';
  if(!empty($resultSalon) && $resultSalon[0]['boolValidation'] == 1) {      
        $html .= '<h5>Numéro de l\'hôte</h5><p>'.$resultUtilisateur[0]['telephoneUtilisateur'].'</p>';
	}
  $html .= '</div>';
				
	if (!empty($resultSalon) && $resultSalon[0]['boolValidation'] == 1) {
        $html .= '<p class="bgVert accepteOuNon">Admis</p>';
    }elseif(!empty($resultSalon) && $resultSalon[0]['boolValidation'] == NULL){
        $html .= '<p class="accepteOuNon">En Attente</p>';
    }elseif(!empty($resultSalon) && $resultSalon[0]['boolValidation'] == 0){
        $html .= '<p class="bgRouge accepteOuNon">Refusé</p>';
    }else if(!isset($_SESSION['id'])){
        $html .= '<a href=" '.$loginUrl.' " class="accepteOuNon fbconnect">Connectez-vous</a>';
    }else if ($resultEvent[0]['idCreateur'] != $_SESSION['id'] && $nbPlace > 0){
        $html .= '<form id="formSalon" action="./PHP/demandeSalon.php" method="post">';
        $html .= '<input type="hidden" name="idCreateur" value="'.$resultEvent[0]['idCreateur'].'" />';
        $html .= '<input type="hidden" name="idSalon" value="'.$_POST['idSalon'].'" />';
        $html .= '<input type="hidden" name="idInvite" value="'.$_SESSION['id'].'" />';
        $html .= '<input name="submit" id="submit" type="submit" value="Rejoindre ce Salon" />';
        $html .= '</form>';
    }else if ($nbPlace == 0){
		$html .= '<p class="accepteOuNon">Complet</p>';
    }else{
        $html .= '<p class="accepteOuNon">'.$nbPersonne.' invité(s)</p>';
    }			
		
	$html .= '<a class="close-salon" href="#">Fermer<!--<img src="img/close.png" alt="close" />--></a>';

    echo $html;


?>