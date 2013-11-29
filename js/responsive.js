/* Responsive */



//Objet salon publique / Sert essentiellement à redimensionner la gmaps en fonction de la taille de l'écran et des éléments affichés.
this.salon = {
	resizeMap:function(){
		//Fonction de calcul de la map en fonction de la taille du header et du footer
		var height = $(window).height();
		
		var map_height = height - 70 - $("header").height();
		$('#map-canvas').height(map_height);
	},
	//Toggle le footer des qu'on affiche un salon
	baisserFooter:function(){
		
		$('footer').css("bottom","-180px");
		$('#geolocation').css("bottom","-70px");
		var height = $(window).height();
		$('#notifs').css('height', height -70-70);
		$('#notifs').css('transition', 'all 0.3s ease');
	},
	afficherFooter:function(){
		$('footer').css("bottom","0");
		$('#geolocation').css("bottom","110px");
		var height = $(window).height();
		$('#notifs').css('height', height -70-250);
		$('#notifs').css('transition', 'all 1s ease');
	},
	resizeMobileMap:function(){
		var map_width = $('#map-canvas').width();
		$('#map-canvas').height(map_width);
	},
	centrerSalon:function(){
		// Centrage vertical du panneau Salon.
		/*
		var heightSalon = $("#salon").height()+60; //Les 30 c'est le padding top + le padding bottom de la div salon
		var calculTop = ((($('#map-canvas').height()-heightSalon)/2)+ $("header").height());
		calculTop = Math.floor(calculTop);
		$('#salon').css("top",calculTop+"px");
		*/
		

		var heightSalon = ($("#containersalon").height()+60)/2;
		$('#salon').css("margin-top","-"+heightSalon+"px");
	}
}

//Initialisation
var width = $(window).width();
if (width <= 740){
	$('#main').addClass('row-fluid');
	$('#content').addClass('span11');
	salon.resizeMobileMap();
	$('#formCreerSalon > div.formSalonInputs').removeClass('span7');
	$('#formCreerSalon > div.formSalonInputs').addClass('span11');
}else{
	salon.resizeMap();
}

//On window resize
$( window ).resize(function() {
	var width = $(window).width();
		//console.log(width);
		if (width <= 740){
			$('#main').addClass('row-fluid');
			$('#content').addClass('span11');
			salon.resizeMobileMap();
			$('#formCreerSalon > div.formSalonInputs').removeClass('span7');
			$('#formCreerSalon > div.formSalonInputs').addClass('span11');
			if($('#salon').hasClass('active')){
				$('footer').css("bottom","0px");
			}
			$('#salon').addClass('row-fluid');
			$('.flachPop').hide();
		}else{
			if($('#document').hasClass('active')){
				$('#document').removeClass('active');
				$('#menu_top_mobile').removeClass('mobile_menu_active');
			}
			$('#main').removeClass('row-fluid');
			$('#content').removeClass('span11');
			//on remet le tout en place
			salon.resizeMap();
			salon.centrerSalon();
			$('#formCreerSalon > div.formSalonInputs').removeClass('span11');
			$('#formCreerSalon > div.formSalonInputs').addClass('span7');
			if($('#salon').hasClass('active')){
				$('footer').css("bottom","-180px");
			}
		}
});


//Menu option mobile
$('#boutton-menu-mobile').on('click', function(){
	$('#menu_top_mobile').toggleClass('active');
	$('#document').toggleClass('active');
});


/*
//Positionement de la div Geolocation
if (width >= 740){
	var footer_top = $("footer" ).offset();
	var geoloc_top = footer_top.top - 175;
	$('#geolocation').offset({top:geoloc_top});
}

$(window).resize(function() {
	if (width >= 740){
		var footer_top = $("footer" ).offset();
		var geoloc_top = footer_top.top - 175;
		$('#geolocation').offset({top:geoloc_top});
	}
});
*/










