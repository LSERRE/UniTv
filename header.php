<!DOCTYPE html>
<html>
  <head>
    <title>UniTv - La télévision ensemble</title>
	<meta http-equiv="Content-Type" content="text/html, charset=utf-8">
	<meta name="viewport" content="initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta charset="utf-8">
    <?php if(empty($_GET['id'])){ ?> 
    <meta name="description" content="Unitv est une plateforme communautaire de co-visionnage de programmes télévisés. Unitv met en relation ces utilisateurs pour voir ensemble des Films, séries et programmes sportifs." />
    <?php }else{ ?>
	<meta name="description" content="Les pages utilisateurs Unitv permettent de visualiser le profil d'un usager avec la possibilité de noter et donner un avis sur chaques les membres du site." />
    <?php  } ?>
    <script src="js/jquery.js" type="text/javascript"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/bootstrap.css" />
    <link rel="stylesheet" href="css/programmetv.css" />
    
    <link rel="shortcut icon" type="image/png" href="img/favicon.png" />
   
  </head>
  <body>
  
    	  		<?php

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

      if(empty($_COOKIE["visited"])){
      ?>
      <script src="js/flachPop.js"></script>
    
      <?php
          
      }
      setcookie("visited", '1', time()+((3600*24)*30)); 
      ?>

      
  	  <ul id="menu_top_mobile" class="mobile">
	  	  <?php if ($user): ?>
	  	  <li><a class="connect-button" href="<?php echo $logoutUrl; ?>"> Logout &#9658; </a></li>
      	  <?php endif ?>
  	  </ul>
  	  
  	  <div id="document">
  	  
  	  <div id="notifs">
  	  		
  	  </div>
  	  
  	  <div id="new-salon-form">
	  	  <form action="creerSalon.php">
		  	  
	  	  </form>
  	  </div>
	  	  
  	  <header id="header">
 	  		<a href="#" id="boutton-menu-mobile"class="mobile"><img src="img/icone-menu" alt="Menu" /></a>
  	  		<h1><a href="index.php" id="logo"><img src="img/logo.png" alt="Logo UniTv" /></a></h1>
  	  		<h2 class="dekstop">Rassemblez vous autour de vos programmes favoris près de chez vous.</h2>
  	  		<div id="header-right"> 
  	  		<?php if ($user): ?>
  	  		<a id="call-popup-new-salon" class="desktop new-salon-desktop" href="creerSalon.php">Creer un salon </a>
  	  		<?php endif ?>
     <?php if ($user): ?>
      <a class="connect-button desktop" href="<?php echo $logoutUrl; ?>">Déconnexion</a>
    <?php else: ?>
        <a class="login connect-button desktop" href="<?php echo $loginUrl; ?>"><img src="./img/login.png" alt="Login With Facebook"></a>
        <a class="login connect-button mobile" href="<?php echo $loginUrl; ?>"><img src="./img/login2.png" alt="Login With Facebook"></a>
    <?php endif ?>

    <?php if ($user): ?>    

      	<a href="#" id="profil-picture"><img src="https://graph.facebook.com/<?php echo $user; ?>/picture"> <span id='nbNotifs'></span></a>
    <?php endif ?>


  	  			
  	  		</div>
  	  </header>
  	  <a  id="new-salon-mobile" class="mobile" href="creerSalon.php" >Créer un salon</a>