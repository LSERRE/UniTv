<?php
session_start();
?>


<?php
if (!isset($_SESSION[id])) {
	header('Location: index.php');
exit;
}
?>



	<?php 
  include('header.php');

    $resultUtilisateur = $Mysql->TabResSQL("SELECT * FROM Utilisateur WHERE idUtilisateur='".$_SESSION['id']."'");

    if(isset($_POST['nomProgramme']) || isset($_POST['nbInvites']) || isset($_POST['adresse']) || isset($_POST['ville']) || isset($_POST['coordonnees'])){

      $insert = $Mysql->ExecuteSQL("INSERT INTO Salon VALUES ('','".$_SESSION['id']."','".$_POST['nomProgramme']."','".$_POST['dateProgramme']."','".$_POST['chaineProgramme']."','".$_POST['descriptionProgramme']."', '".$_POST['categorieProgramme']."',".$_POST['nbInvites'].",'".$_POST['coordonnees']."','".$_POST['adresse']."','".$_POST['ville']."', '".$_POST['image']."','".$_POST['accepte']."','".$_POST['refuse']."')");

      if(isset($_POST['telephone'])){
        $insert = $Mysql->ExecuteSQL("UPDATE  Utilisateur SET telephoneUtilisateur = '".$_POST['telephone']."' WHERE idUtilisateur='".$_SESSION['id']."'");
      }
      if (isset($insert)) {
        header('Location: index.php');
        exit;
      }

    }else if($resultUtilisateur){
?>    
	


<div id="main" class="formSalonbackground">
	<section id="content">
		<div id="formSalonContainer"> 
			<form id="formCreerSalon" class="container row-fluid" action="" method="post" autocomplete="on">
		        <div id="hidden"> 
		        </div>
		        
		        <div class="span7 formSalonInputs">
		        <h2>Créez votre salon:</h2>
		        <label for="datepicker" ><span>Date:</span> </label><input type="text" name = "date" id="datepicker" value="" required="required"="" >
		        <label for="categorie"class='categories'><span>Catégorie:</span></label> 
		        <select name="categorieProgramme" id="categorie" required="required" > 
		           <option value="film">Film</option> 
		           <option value="sport">Sport</option> 
		           <option value="serie">Série</option> 
		        </select>
		
		        <label for="programme"><span>Programme</span></label>
		        <select name="nomProgramme" id="programme" required="required">
		          <option value="">Selectionnez un programme:</option>
		        </select></p>
		        <label for="invite"><span>Nombre d'invités: </span></label>
		          <select name="nbInvites" id="invite">
		           <option value="1">1</option> 
		           <option value="2">2</option> 
		           <option value="3">3</option>            
		           <option value="4">4</option> 
		           <option value="5">5</option> 
		           <option value="6">6</option>            
		           <option value="7">7</option> 
		           <option value="8">8</option> 
		           <option value="9">9</option> 
		          </select></p>
		        <label for="inputAdresse"><span>Adresse:</span> </label><input id='inputAdresse' name="adresse" type="text" required="required" />
		        <label for="inputVille"><span>Ville: </span></label><input  id='inputVille' name="ville" type="text" required="required" />
		        <label for="acccepte"><span>L'hôte accepte: </span></label><input name="accepte" id="accepte" type="text"/>
		        <label for="refuse"><span>L'hôte refuse:</span></label><input name="refuse" id="refuse" type="text" />
		<?php
		        if($resultUtilisateur[0]['telephoneUtilisateur'] == ''){
		?>
		        <label for="tel">Numéro de téléphone:</label><input name="telephone" id="tel" pattern="[0-9]{10}" type="tel" required="required" />      
		<?php
		        }
		?>
		        <input type="hidden" id='inputCoordonnees' name="coordonnees" value="" />
		         <input id="submit" type="submit" value="Valider"/>
             		        </div>
		        <div class="clear"></div>
		       	        <!-- <p><input name="submit" id='submit' type="button" value='submit' /></p> -->
		    </form>
<?php
    }
?>
	    <script src="js/afficherProgramme.js"></script>
	    <script>
	      $( "#formCreerSalon" ).on('submit', function(e) {
	          var adresse = $('#inputAdresse').val();
	          var ville = $('#inputVille').val();
	          var bool = 0;
	          var adress = adresse+' , '+ville;
	          var geocoder = new google.maps.Geocoder();
	          geocoder.geocode( { 'address' : adress }, function(results, status) {
	            if(status == google.maps.GeocoderStatus.OK) { 
	            	var LatLng = '('+parseFloat(results[0].geometry.location.lat().toFixed(6))+','+parseFloat(results[0].geometry.location.lng().toFixed(6))+')';
	                $('#inputCoordonnees').val(LatLng);
	                $( "#formCreerSalon" ).unbind('submit').submit();
	                $('#submit').trigger('click');
	                
	               }else{
	               alert('Erreur de localisation de votre adresse.');
	              
	            }       
	          });
	          return false;
	        });
	    </script>
	    </div>
       	</section>
     </div>
    
    <?php 
	    include('footer.php');
     ?> 
    
  </body>
</html>