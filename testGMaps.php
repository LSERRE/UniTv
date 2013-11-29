<!DOCTYPE html>
<html>
  <head>
    <title>Projet Pumir -- Test GMaps</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
      html, body, #map-canvas {
        margin: 0;
        padding: 0;
        height: 98%;
      }

      #geolocation{
        position : absolute;
        width: 160px;
        height: 50px;
        margin-left: 20px;
        margin-top: 20px;
        z-index: 1000;
        background-color: white;
      }

      .gm-style-mtc{
        visibility: hidden;
      }

      .gmnoprint{
        margin-top:50px;
      } 

      .gmnoprint>div{
        visibility: hidden;
      }
      
    </style>
    <script src="js/jquery.js" type="text/javascript"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    
    <script src="js/localisation.js" type="text/javascript"></script>

  </head>
  <body>
      <a href="index.html">INDEX</a>
      <A href="javascript:window.location.reload()">Recharger la page</A>
      <?php

          require './PHP/exception.inc';
          require './PHP/connexion_bdd.inc';
          require './facebookSdk/src/facebook.php';

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
            $Mysql = new Mysql();
            $result = $Mysql->TabResSQL("SELECT * FROM Utilisateur WHERE idFacebook='".$user_info['id']."'");
            $resultEvent = $Mysql->TabResSQL("SELECT * FROM Salon");

            if(empty($result)){
                  $insert = $Mysql->ExecuteSQL("INSERT INTO Utilisateur VALUES ('','".$user_info['id']."','".$user_info['last_name']."','".$user_info['first_name']."','".$age."','".$user_info['email']."', 'https://graph.facebook.com/".$user."/picture','','','".$user_info['gender']."')");
                  $result = $Mysql->TabResSQL("SELECT * FROM Utilisateur WHERE idFacebook='".$user_info['id']."'");
            }

            for ($i=0; $i < count($resultEvent) ; $i++) { 
                 setcookie('event_'.$i,$resultEvent[$i]['adresse'].'_'.$resultEvent[$i]['ville'].'_'.$resultEvent[$i]['categorieProgramme'].'_'.$resultEvent[$i]['idSalon']);
            }

            $_SESSION['id'] = $result[0]['idUtilisateur'];

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
      <h3>You</h3>
      <img src="https://graph.facebook.com/<?php echo $user; ?>/picture">

      <h3>Your User Object (/me)</h3>
      <pre><?php  //print htmlspecialchars(print_r($user_info, true)); ?></pre>
    <?php else: ?>
      <strong><em>You are not Connected.</em></strong>
    <?php endif ?>



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

    <div id='images'></div>
    <script src="js/afficherProgramme.js" type="text/javascript"></script>
  </body>
</html>