<?php include('header.php'); ?>
   		
	<div id="main">
		<section id="content"> 	  
			<div id="search-mobile" class="mobile search-bar-mobile">
				<form class="form_search form_search_mobile" action="">
			        <input type="search" name="search-desktop" id='search-input-mobile' class='search-input' placeholder="Rechercher un programme"/>
			        <input type="submit" hidden=""/>
				</form>
			</div>          
		    <div id="map-canvas"></div>
		    
		    <div id="geolocation">
				<a href="#" id="go-locate"><img src="../img/geoloc.svg" alt="Me géolocaliser"></a>
				<form id="form" method="post" action="#">
				<input type="search" name="search" id="geolocation-adresse" value="" placeholder="Ou entrez votre adresse" />
				</form>
				<ul>
					<li><a href="" data="DRIVING"><img src="../img/envoiture.svg"></a></li>
					<li><a href="" data="WALKING"><img src="../img/apieds"></a></li>
					<li><a href="" data="BICYCLING"><img src="../img/avelo.svg"></a></li>
					<li><a href="" data="TRANSIT"><img src="../img/enmetro.svg"></a></li>
				</ul>
		    </div>

		    <div id="salon">
		    	<img class="loader_ajax" src="img/ajax-loader.gif" alt="Patientez" />
		    	<section id="containersalon"></section>
			</div>
			<div id="video_yt"><a href="#" id="close-youtube">fermer</a></div>
	        <div id="infoUtilisateur"></div>
       	</section>
     </div>
     

<?php include('footer.php') ?>