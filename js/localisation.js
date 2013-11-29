var marker = null;
var markerN = [];
var idSalonR = [];
var tabLatLngR = [];
var coor = null;
var directionsDisplay;
var categorie = "sport";
var directionsService = new google.maps.DirectionsService();
var selectedMode = "DRIVING";


google.maps.event.addDomListener(window, 'load', initialize);



window.onload = function () {

    var getLocated = document.getElementById("go-locate");
    getLocated.onclick = function () {
        navigator.geolocation.getCurrentPosition(geolocationCenter, geolocationError);
    }
    navigator.geolocation.getCurrentPosition(geolocationCenter, geolocationError);

    // A l'init, recherche tout les programmes
    $.ajax({
        type: "POST",
        url: "./PHP/rechercheProgramme.php",
        data: {
            'programme': '',
        },
        dataType: "json",
        success: function (msg) {
            ajouterMarker(msg);
        }
    });

    ajouterMarkerRecherche();

    $("#form").submit(centerWithAdress);

}

// Evenement 

function ajouterMarkerRecherche() {


    //Les différents evenements pour la recherche de salon mobile et desktop

    $("#search-input-desktop").on({
        keyup : function (event) {
                    event.preventDefault();
                    $.ajax({
                        type: "POST",
                        url: "./PHP/rechercheProgramme.php",
                        data: {
                            'programme': $('#search-input-desktop').val()
                        },
                        dataType: "json",
                        success: function (msg) {

                            ajouterMarker(msg);
                            zoomMap(11);
                        }
                    });
                },
    });

    $("#search-input-mobile").on({
        keyup : function (event) {
                    event.preventDefault();
                    $.ajax({
                        type: "POST",
                        url: "./PHP/rechercheProgramme.php",
                        data: {
                            'programme': $('#search-input-mobile').val()
                        },
                        dataType: "json",
                        success: function (msg) {

                            ajouterMarker(msg);
                        }
                    });
                },
    });
    
    $(".ui-corner-all").on('click', function(event){
        var value = $('#search-input-desktop').val();
        if(value != ""){
            $.ajax({
                type: "POST",
                url: "./PHP/rechercheProgramme.php",
                data: {
                    'programme': $('#search-input-desktop').val()
                },
                dataType: "json",
                success: function (msg) {

                    ajouterMarker(msg);
                }
            });
        }
    });
    $("#search-mobile").on('submit', function (event) {
        event.preventDefault();
        var value = $('#search-input-mobile').val();
        var msg = $.ajax({
            type: "POST",
            url: "./PHP/rechercheProgramme.php",
            data: {
                'programme': $('#search-input-mobile').val()
            },
            dataType: "json",
            success: function (msg) {

                ajouterMarker(msg);
            }
        });

    });
}

// Ajoute les différents marker de salon sur la map
function ajouterMarker(msg) {
    // lors de la recherche, efface les markers déjà placés sur la map
    for (var i = 0; i < markerN.length; i++) {
        if (markerN[i]) {
            markerN[i].setMap(null);
        };
    }
    // Efface l'itinéraire, si il y en a un 

    directionsDisplay.setDirections({
        routes: []
    });

    // Parcour tout le json de tout les programmes 

    for (var i = 0; i < msg.length; i++) {
        idSalonR[i] = msg[i]['idSalon'];
        var categorie = msg[i]['categorie'];

        var latLng = msg[i]['coordonnees'].replace("(", "");
        latLng = latLng.replace(")", "");

        tabLatLngR[i] = latLng;
        latLng = latLng.split(',');

        latLng = new google.maps.LatLng(latLng[0], latLng[1]);

        if (categorie == 'sport') {
            var optionsMarqueur = {
                position: latLng,
                map: map,
                icon: './img/p_sport.svg'
            }
        } else if (categorie == 'serie') {
            var optionsMarqueur = {
                position: latLng,
                map: map,
                icon: './img/p_serie.svg'
            }

        } else if (categorie == 'film') {
            var optionsMarqueur = {
                position: latLng,
                map: map,
                icon: './img/p_film.svg'
            }
        }

        markerN[i] = new google.maps.Marker(optionsMarqueur);
        markerN[i].setMap(map);

        google.maps.event.addListener(markerN[i], 'click', function () {
            var i = markerN.indexOf($(this)[0]);
           
            $('#geolocation > ul > li > a').on('click', function() {
				selectedMode = $(this).attr("data");
				calcRoute(tabLatLngR[i]);
				return false;
			});

            calcRoute(tabLatLngR[i]);
            var zoomMap = map.getZoom();
            var newZoomMap = map.getZoom() - 4;
            map.setZoom(newZoomMap);
			
			//Mise en place du chargement gif
			//$("#salon").empty();
            $("#infoUtilisateur").empty();
			 
             if (width <= 740){
	             $('#salon').addClass('active');
	         }else{
				//Si le footer est déjà baissé, pas besoin de le re-calculer
				if($('footer').css("bottom")!="-180px"){
					salon.baisserFooter();
				} 
				//$("#containersalon").height("0px");
				//$(".loader_ajax").height("100px");
				$(".loader_ajax").slideDown(400);
				$("#containersalon").slideUp(400);
				$('#salon').addClass('active');
				//setTimeout("salon.centrerSalon();",500);
			 }
			
            $.ajax({
                type: "POST",
                url: "afficherSalon.php",
                data: {
                    idSalon: idSalonR[i]
                }
            }).done(function (msg) {

                $("#containersalon").empty();
                $("#infoUtilisateur").empty();
                //$("#salon").show();
                $("#containersalon").append(msg);
				if (width > 740){
					setTimeout("salon.centrerSalon();",500);
					setTimeout("salon.centrerSalon();",700);
					setTimeout("salon.centrerSalon();",1000);
				}

                

				$(".loader_ajax").slideUp(400);
				$("#containersalon").slideDown(400);
				//$(".loader_ajax").height("0px");
				//$("#containersalon").height("400px");

				
				if($("#salon").css("left")!=0){
                    arriverSalon();
                }
                if ($(window).width() <= 740){
                    $('body').animate({scrollTop:$('#salon').position().top}, 'slow');
                }
                $("#extraitVideo").on( 'click', function(){

                    var apiKey = 'AIzaSyAX52rQEjgNSb6v0lP0jizbr9wc400s5vY';
                    var keyWord = $("#salon h2").text()+' bande annonce';

                    var url = 'https://www.googleapis.com/youtube/v3/search?part=snippet&q='+keyWord+'&key='+apiKey;

                    $.getJSON( url, function( json ) {

                        var videoId = json.items[0].id['videoId'];

                        $("#video_yt").show();
                        $("#video_yt").html('<iframe src="http://www.youtube.com/embed/'+videoId+'" width="640" height="385"></iframe>');  


                    });      

                    $( "#salon" ).after('<span id="fermerVideo">fermer la vidéo<span>');
                    var widthDeBaseDivise = (parseInt($(window).width()))/2;
                    var widthSpanDivise = (parseInt($('#fermerVideo').width()))/2
                    $( "#fermerVideo" ).css('left', widthDeBaseDivise - widthSpanDivise +'px');
                    
                    $("#fermerVideo").on( 'click', function(){
                        $("#video_yt").hide();
                        $("#video_yt iframe").remove();
                        $("#fermerVideo").hide();
                    });

                });            
				
                $('.close-salon').on('click', function () {
                	if (width >= 740){

	                    $("#salon").css("left","-400px");
						/*
	                    setTimeout(function() {
	                    	$('#salon').removeClass('heightauto');
          				},1000);
						*/
	                    // TEMPORAIRE
	                    $("#video_yt").empty();
	                    $("#video_yt").hide();
	                    
	                    //$("#salon").hide();
						salon.afficherFooter();
						$('#salon').removeClass('active');
					}else{
						$('#salon').removeClass('active');
					}
                });
            });

        });
    }
}
function arriverSalon(){
	$("#salon").css("left","0px");
}

// Centre la map sur la géolocation de l'utilisateur 

function geolocationCenter(pos) {
    var lat = parseFloat(pos.coords.latitude);
    var lng = parseFloat(pos.coords.longitude);
    latLng = new google.maps.LatLng(lat, lng);

    coor = latLng;

    map.setCenter(latLng);

    var optionsMarqueur = {
        position: latLng,
        map: map,
        icon: './img/p.svg'
    }


    if (marker != null) {

        marker.setMap(null);

    }

    marker = new google.maps.Marker(optionsMarqueur);
    marker.setMap(map);
}

function zoomMap(zoom){
	map.setZoom(zoom);
}

function geolocationError(err) {

    //createFormAddress();
    alert("Vous devez activer la géolocalisation.");

}

// initialise la map avec les options et le style

function initialize() {

    var styles = [{
        "featureType": "administrative.country",
        "stylers": [{
            "visibility": "on"
        }, {
            "saturation": -100
        }, {
            "lightness": 10
        }]
    }, {
        "featureType": "administrative.province",
        "stylers": [{
            "visibility": "simplified"
        }, {
            "saturation": -100
        }]
    }, {
        "featureType": "administrative.locality",
        "stylers": [{
            "visibility": "on"
        }, {
            "saturation": -100
        }, {
            "lightness": 21
        }]
    }, {
        "featureType": "administrative.neighborhood",
        "stylers": [{
            "visibility": "simplified"
        }, {
            "saturation": -100
        }]
    }, {
        "featureType": "administrative.land_parcel",
        "stylers": [{
            "visibility": "simplified"
        }, {
            "saturation": -100
        }]
    }, {
        "featureType": "landscape.man_made",
        "stylers": [{
            "visibility": "off"
        }]
    }, {
        "featureType": "landscape.natural",
        "stylers": [{
            "visibility": "simplified"
        }, {
            "saturation": -60
        }]
    }, {
        "featureType": "poi.attraction",
        "stylers": [{
            "visibility": "off"
        }]
    }, {
        "featureType": "poi.business",
        "stylers": [{
            "visibility": "off"
        }]
    }, {
        "featureType": "poi.government",
        "stylers": [{
            "visibility": "off"
        }]
    }, {
        "featureType": "poi.medical",
        "stylers": [{
            "visibility": "off"
        }]
    }, {
        "featureType": "poi.park",
        "stylers": [{
            "gamma": 2
        }, {
            "saturation": -75
        }, {
            "visibility": "simplified"
        }]
    }, {
        "featureType": "poi.place_of_worship",
        "stylers": [{
            "visibility": "off"
        }]
    }, {
        "featureType": "poi.school",
        "stylers": [{
            "saturation": -100
        }, {
            "visibility": "off"
        }]
    }, {
        "featureType": "poi.sports_complex",
        "stylers": [{
            "visibility": "off"
        }]
    }, {
        "featureType": "road.highway",
        "stylers": [{
            "saturation": -100
        }, {
            "visibility": "simplified"
        }, {
            "gamma": 1.18
        }]
    }, {
        "featureType": "road.arterial",
        "stylers": [{
            "visibility": "on"
        }, {
            "saturation": -100
        }, {
            "gamma": 1.87
        }, {
            "lightness": 10
        }]
    }, {
        "featureType": "road.local",
        "stylers": [{
            "visibility": "simplified"
        }, {
            "saturation": -100
        }, {
            "gamma": 2.49
        }]
    }, {
        "featureType": "transit.line",
        "stylers": [{
            "visibility": "off"
        }]
    }, {
        "featureType": "transit.station.airport",
        "stylers": [{
            "visibility": "simplified"
        }, {
            "saturation": -100
        }]
    }, {
        "featureType": "transit.station.bus",
        "stylers": [{
            "saturation": -100
        }, {
            "visibility": "simplified"
        }]
    }, {
        "featureType": "transit.station.rail",
        "stylers": [{
            "visibility": "simplified"
        }, {
            "hue": "#ff0000"
        }, {
            "gamma": 1.56
        }, {
            "lightness": 11
        }, {
            "saturation": -30
        }]
    }, {
        "featureType": "water",
        "stylers": [{
            "lightness": 100
        }, {
            "saturation": -100
        }, {
            "gamma": 9.99
        }]
    }, {
        "featureType": "administrative.country",
        "stylers": [{
            "visibility": "simplified"
        }]
    }, {
        "featureType": "road",
        "stylers": [{
            "saturation": -100
        }, {
            "gamma": 0.6
        }]
    }, {
        "featureType": "road.arterial",
        "stylers": [{
            "gamma": 1.69
        }]
    }, {
        "featureType": "road.highway",
        "stylers": [{
            "gamma": 1.77
        }]
    }, {
        "featureType": "administrative.locality",
        "stylers": [{
            "gamma": 0.66
        }]
    }, {
        "featureType": "administrative.locality",
        "stylers": [{
            "visibility": "on"
        }]
    }];

    var styledMap = new google.maps.StyledMapType(styles, {
        name: "Styled Map"
    });

    directionsDisplay = new google.maps.DirectionsRenderer();
    map = new google.maps.Map(document.getElementById('map-canvas'), {
        scrollwheel: true,
        panControl: false,
        zoomControl: false,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.LARGE
        },
        mapTypeControl: false,
        scaleControl: false,
        streetViewControl: false,
        overviewMapControl: false,
        center: new google.maps.LatLng(48.8705678032146, 2.3518518965656643),
        zoom: 14,
        mapTypeId: google.maps.MapTypeId.TERRAIN
    });
    directionsDisplay.setMap(map);
    map.mapTypes.set('map_style', styledMap);
    map.setMapTypeId('map_style');

    //calcRoute();
}


// centre la map si on fait une recherche de lieu

function centerWithAdress(e) {
    var geocoder = new google.maps.Geocoder();
    var adress = document.getElementById("geolocation-adresse").value;

    directionsDisplay.setDirections({
        routes: []
    });

    geocoder.geocode({
        'address': adress
    }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            latLng = new google.maps.LatLng(parseFloat(results[0].geometry.location.lat().toFixed(6)), parseFloat(results[0].geometry.location.lng().toFixed(6)));
            coor = latLng;
            map.setCenter(latLng);


            var optionsMarqueur = {
                position: latLng,
                map: map,
                icon: './img/p.svg'
            }

            //ajoute le marker à la map

            if (marker != null) {
                marker.setMap(null);
            }

            marker = new google.maps.Marker(optionsMarqueur);
            marker.setMap(map);

        } else {
            alert("Oups, nous n'arrivons pas à localiser cette adresse.");
        }
    });
    return false;
}

// Calcule l'itinéraire entre la position du pin et la géloc
function calcRoute(latLng) {

	
	/* var selectedMode = document.getElementById('mode').value; */

    var dest = latLng.split(',');



    if (dest == "") {
        alert("Vous devez sélectionner une destination.");
    } else {

        var request = {
            origin: coor,
            durationInTraffic: true,
            destination: new google.maps.LatLng(dest[0], dest[1]),
            travelMode: google.maps.TravelMode[selectedMode]
        };
        directionsService.route(request, function (response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(response);
                // , preserveViewport: true,
                directionsDisplay.setOptions({
                    suppressMarkers: true
                });
            }
        });
    }

    return false;
}