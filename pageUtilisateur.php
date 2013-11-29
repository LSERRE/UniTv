<?php
	include('header.php');
?>
  	  <div id="main"> 
	 	  <section id="content"> 
	
<?php

	if (isset($_GET['id'])) {

		if(isset($_POST['commentaire']) && isset($_POST['titre']) && isset($_POST['note'])){
			$heure = time();
			$insert = $Mysql->ExecuteSQL("INSERT INTO Commentaire VALUES ('', '".$_SESSION['id']."', '".$_GET['id']."', '".$_POST['titre']."', '".$_POST['commentaire']."', '".$_POST['note']."', 0, ".$heure." )");
			
			header('Location: pageUtilisateur.php?id='.$_GET['id']);

		}else{

	   	$resultUtilisateur = $Mysql->TabResSQL("SELECT * FROM Utilisateur WHERE idUtilisateur='".$_GET['id']."'");
	    $resultCommentaire = $Mysql->TabResSQL("SELECT * FROM Commentaire WHERE idCommente='".$_GET['id']."'");
	   	$resultSalon = $Mysql->TabResSQL("SELECT * FROM Salon WHERE idCreateur='".$_GET['id']."'"); 
	   	if(!empty($resultUtilisateur[0])){
?>
	
	<h1></h1>
	<aside id="ficheMembre">
				<header ="carteIdentite">
					<p id="nomMembre"><?php echo  $resultUtilisateur[0]['prenomUtilisateur'].' <span>'.$resultUtilisateur[0]['nomUtilisateur'].'</span></p>'; ?>
					<!-- <p id="nomMembre">Tanguy <span>Géréec</span></p> -->
					<img src="<?php echo  $resultUtilisateur[0]['avatarUtilisateur']; ?>" id="photoMembre">
				</header>

				<div id="lesSalons">
					<ul id="listeSalons">
	<?php
		$tabAnciensSalon = array();
		$tabFuturSalon = array();

		if (count($resultSalon) == 0) {
			$salon .= '<li class="prochainSalon ancienSalon">';
			$salon .= '<img src="img/pasDeSalon.jpg" />';
			$salon .= '<h3>'.$resultUtilisateur[0]['prenomUtilisateur'].' n\'a pas encore créé de salon.</h3>';
			$salon .= '</li>';
			$tabAnciensSalon[$time] = $salon;
		}

		for ($i=count($resultSalon)-1; $i >= 0  ; $i--) { 
			$salon = '';
			$date = explode(" ", $resultSalon[$i]['dateProgramme']);
			$time = (strtotime($resultSalon[$i]['dateProgramme']));
			if(  $time < time() ){
				$salon .= '<li class="prochainSalon ancienSalon">';
				if($resultSalon[$i]['image'] != ""){
					$salon .= '<img src="'.$resultSalon[$i]['image'].'" />';
				}else{
					$salon .= '<img src="img/close.png" />';
				}
				$salon .= '<h3>'.$resultSalon[$i]['nomProgramme'].'</h3>';
				$salon .= '<p> le '.date("d/m", strtotime($date[0])).' à '.$date[1].' <i>(passé)</i> </p>';
				$salon .= '</li>';
				$tabAnciensSalon[$time] = $salon;
			}else{
				$salon .= '<li class="prochainSalon" >';
				if($resultSalon[$i]['image'] != ""){
					$salon .= '<img src="'.$resultSalon[$i]['image'].'" />';
				}else{
					$salon .= '<img src="img/close.png" />';
				}
				$salon .= '<h3>'.$resultSalon[$i]['nomProgramme'].'</h3>';
				$salon .= '<p> le '.date("d/m", strtotime($date[0])).' à '.$date[1].' <i>(en cours)</i> </p>';
				$salon .= '</li>';
				$tabFuturSalon[$time] = $salon;
			}
			
		}
		    ksort($tabFuturSalon);
		    ksort($tabAnciensSalon);

		    $reverse_tabFuturSalon = array_reverse($tabFuturSalon);
		    $reverse_tabAnciensSalon = array_reverse($tabAnciensSalon);

		    foreach ($reverse_tabFuturSalon as $key => $val) {
			        echo $val;
		    }
		    foreach ($reverse_tabAnciensSalon as $key => $val) {
		        	echo $val;
		    }
	?>
					</ul>
				</div>
			</aside>

			<section id="note">
				<div id="resume">
					<h2>Notes et commentaires à propos de <?php echo  $resultUtilisateur[0]['prenomUtilisateur']; ?></h2>
					
<?php
$spanNote = '';
if($resultCommentaire){

	$spanNote .='<p>Note moyenne :</p>';

	$totalNotes = 0;
	for ($i=0; $i < count($resultCommentaire) ; $i++) { 
	 	$totalNotes = $totalNotes + $resultCommentaire[$i]['noteCommentaire'];
	}

	 $moyenne =  round($totalNotes/count($resultCommentaire));

	for ($i=0; $i < $moyenne ; $i++) { 
	 	$spanNote .='<span class="noteRouge"></span>';
	}

	for ($i=0; $i < 5-$moyenne ; $i++) { 
		$spanNote .='<span class="noteGris"></span>';
	}

	echo $spanNote;
}
?>
			
				</div>
				<div id="commentaires">
					<ul>
<?php 
	
	$commentaire = "";
	for ($i=0; $i < count($resultCommentaire) ; $i++) { 

		$resultCommentateur =  $Mysql->TabResSQL("SELECT * FROM Utilisateur WHERE idUtilisateur='".$resultCommentaire[$i]['idCommentateur']."'");

		$commentaire .= '<li class="commentaire">';
		$commentaire .= '<p>'.$resultCommentaire[$i]['titreCommentaire'].'</p>';
		$commentaire .= '<p>'.$resultCommentaire[$i]['texteCommentaire'].'</p>';
		$commentaire .= '<div class="auteurCommentaire">';
		$commentaire .= '<img src="'.$resultCommentateur[0]['avatarUtilisateur'].'"/>';
		$commentaire .= '<a href="pageUtilisateur.php?id='.$resultCommentateur[0]['idUtilisateur'].'">'.utf8_encode($resultCommentateur[0]['prenomUtilisateur']).'</a>';
		$commentaire .= '</div>';
	}

	echo $commentaire;

	if($_SESSION['id'] != $_GET['id']){
?>

					</ul>	
				</div>

				<div id="laisserUnCommentaire">
					<h2>Noter et laisser un commentaire :</h2>
					<form id="champCommentaire" action="" method="POST">
						<input required="required" type="text" name="titre" placeholder="Titre du commentaire..."/>
						<textarea required="required" name="commentaire" placeholder="Votre commentaire..."></textarea>
						<div id="buttonNote">
							<input type= "radio" name="note"  checked="checked"  value="1"><label>1/5</label>
							<input type= "radio" name="note" value="2"><label>2/5</label>
							<input type= "radio" name="note" value="3"><label>3/5</label>
							<input type= "radio" name="note" value="4"><label>4/5</label>
							<input type= "radio" name="note" value="5"><label>5/5</label>
						</div>					
						<input type="submit" value="Envoyer"/>
					</form>
				</div>
		    <div id="salon">
		    	<img class="loader_ajax" src="img/ajax-loader.gif" alt="Patientez" />
		    	<section id="containersalon"></section>
			</div>
			</section>


<?php
		}
	   	}

	   }
		
	}
?>

      	</section>
     	</div>
        </div>
        <script src="js/responsive.js" type="text/javascript"></script>
		<script src="js/chargementAjax.js" type="text/javascript"></script>
  </body>
</html>