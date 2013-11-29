<!DOCTYPE html>
<html>
  <head>
    <title>Projet Pumir -- Test GMaps</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <script src="js/jquery.js" type="text/javascript"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    
    <script src="js/localisation1.js" type="text/javascript"></script>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/bootstrap.css" />

  </head>
  <body>
  	  <ul id="menu_top_mobile" class="mobile">
	  	  <li>About</li>
	  	  <li>Mentions légales</li>
  	  </ul>
  	  
  	  <div id="notifs">
  	  
  	  </div>
  	  
  	  <div id="new-salon-form">
	  	  <form action="creerSalon.php">
		  	  
	  	  </form>
  	  </div>
	  	  
  	  <header>
<!--   	  		<a href="#"><img src="img/icone-menu" alt="Menu" /></a> -->
  	  		<a href="index.php" id="logo"><img src="img/logo.png" alt="Logo UniTv" /></a>
  	  		<ul id="menu_top_desktop" class="desktop">
	  	  		<li>About</li>
	  	  		<li>Mentions légales</li>
	  	  	</ul>
  	  		<div id="header-right"> 
  	  			<a class="desktop new-salon-desktop" href="#"> Creer un salon </a>
  	  		<?php

          require './PHP/exception.inc';
          require './PHP/connexion_bdd.inc';
          require './facebookSdk/src/facebook.php';

          $Mysql = new Mysql();
          $resultEvent = $Mysql->TabResSQL("SELECT * FROM Salon");

          for ($i=0; $i < count($resultEvent) ; $i++) { 
             setcookie('event_'.$i,$resultEvent[$i]['coordonnees'].'_'.$resultEvent[$i]['categorieProgramme'].'_'.$resultEvent[$i]['idSalon']);
          }

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
        }
        if($user){

          $queries = array(
            array('method' => 'GET', 'relative_url' => '/'.$user),
            // array('method' => 'GET', 'relative_url' => '/'.$user.'/home?limit=50'),
            );
         
          try{
            $batchResponse = $facebook->api('?batch='.json_encode($queries), 'POST');
          }catch(Exception $o){
            error_log($o);
          }
         
          $user_info    = json_decode($batchResponse[0]['body'], TRUE);
          // $feed     = json_decode($batchResponse[1]['body'], TRUE);
          $age = explode('/', $user_info['birthday']);
          $age = date("Y")-$age[2];

         
          try
          {

            $result = $Mysql->TabResSQL("SELECT * FROM Utilisateur WHERE idFacebook='".$user_info['id']."'");

            if(empty($result)){
                  $insert = $Mysql->ExecuteSQL("INSERT INTO Utilisateur VALUES ('','".$user_info['id']."','".$user_info['last_name']."','".$user_info['first_name']."','".$age."','".$user_info['email']."', 'https://graph.facebook.com/".$user."/picture','','','".$user_info['gender']."')");
                  $result = $Mysql->TabResSQL("SELECT * FROM Utilisateur WHERE idFacebook='".$user_info['id']."'");
            }

            $_SESSION['id'] = $result[0]['idUtilisateur'];

            $resultNotif = $Mysql->TabResSQL("SELECT * FROM MembreSalon WHERE idCreateur='".$_SESSION['id']."'");

            for ($i=0; $i < count($resultNotif) ; $i++) { 
              if ($resultNotif[0]['boolNotificationCreateur'] == 0 ) {
                $resultInvite = $Mysql->TabResSQL("SELECT * FROM Utilisateur WHERE idUtilisateur='".$resultNotif[0]['idInvite']."'");
                $resultSalon = $Mysql->TabResSQL("SELECT * FROM Salon WHERE idSalon='".$resultNotif[0]['idSalon']."'");
                $afficherHtml = '<h1><a href="pageUtilisateur.php?id='.$resultInvite[0]['idUtilisateur'].'">'.$resultInvite[0]['prenomUtilisateur'].' '.$resultInvite[0]['nomUtilisateur'].'</a> veut rejoindre votre salon '.$resultSalon[0]['nomProgramme'].' le '.$resultSalon[0]['dateProgramme'].' </h1>';  
                $afficherHtml .= '<form id="formValidation" action="./PHP/validationSalon.php" method="post">';
                $afficherHtml .= '<input type="radio" name="boolValidation" value="1"> oui';
                $afficherHtml .= '<input type="radio" name="boolValidation" value="0"> non';
                $afficherHtml .= '<input type="hidden" name="idCreateur" value="'.$resultEvent[0]['idCreateur'].'" />';
                $afficherHtml .= '<input type="hidden" name="idSalon" value="'.$resultNotif[0]['idSalon'].'" />';
                $afficherHtml .= '<input type="hidden" name="idInvite" value="'.$resultNotif[0]['idInvite'].'" />';
                $afficherHtml .= '<input name="submit" id="submit" type="submit" value="Valider" />';
                $afficherHtml .= '</form>';

                echo $afficherHtml ;
              }
            }

          }
          catch (MySQLExeption $e)
          {
            echo $e -> RetourneErreur();
          }

       }
      ?>
     <?php if ($user): ?>
      <a href="<?php echo $logoutUrl; ?>">Logout</a>
    <?php else: ?>
      <div>
        <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
      </div>
    <?php endif ?>

    <?php if ($user): ?>    

    <div id="profil-picture">
      	<a href="#"><img src="https://graph.facebook.com/<?php echo $user; ?>/picture"> </a>
      	<span> 1 </span>
	</div>
    <?php else: ?>
      <strong><em>You are not Connected.</em></strong>
    <?php endif ?>


  	  			
  	  		</div>
  	  </header>
  	  <div id="main" class="container"> 
	 	  <section id="content"> 
		  	  <a  id="new-salon-mobile" class="mobile" href="#">Créer un salon</a>	  
		  	  <div class="mobile search-bar-mobile">
			  	    <input type="text" />
			  	    <input type="submit" />
		  	  </div>
	
		    <div id="geolocation">
		      <form id="form" method="post" action="#">
		        <input type="search" name="search" id="geolocation-adresse" value="" placeholder="Votre adresse" />
		      </form>
		      <a href="#" id="go-locate">Géoloc</a>
		    
		    <select id="mode" data-mini="true" data-icon="plus" onchange="calcRoute();" data-theme="c">
		      <option value="WALKING">à pied</option>
		      <option value="BICYCLING">Vélo</option>
		      <option value="TRANSIT">Métro</option>
		      <option value="DRIVING">Voiture</option>
		    </select>
		    </div>
		    
		    <div id="map-canvas"></div>
		    
		    <div id="salon"></div>
		
		    <div id='images'></div>
		    <!-- <script src="js/afficherProgramme.js" type="text/javascript"></script> -->
	 	  </section>
 	  </div>
  </body>
</html>