/*$(document).on('pageinit', '#parc',function() {
	$.mobile.ajaxEnabled = false;
	$("[data-role=header]").fixedtoolbar({ tapToggle: false });
	$("[data-role=footer]").fixedtoolbar({ tapToggle: false });
	$(document).on('pageshow', '#parc',function() {
		//google.maps.event.addDomListener(window, 'load', initialize2);
		initialize2();
	});
});

function initialize2() {
	
	var dest = $('.coordonnees').text().split(",");
	var latLng = null;
	
	console.log(dest);
	
	latLng = new google.maps.LatLng(parseFloat(dest[0]), parseFloat(dest[1]));
	
	if( latLng == null ){
	latLng = new google.maps.LatLng(48.8705678032146, 2.3518518965656643);
	}
	
	
	map2 = new google.maps.Map(document.getElementById('googft-mapCanvas2'), {
	center: latLng,
	zoom: 14,
	mapTypeId: google.maps.MapTypeId.ROADMAP
	});
	layer = new google.maps.FusionTablesLayer({
	map: map2,
	heatmap: { enabled: false },
	query: {
	select: "col0",
	from: "1bETnA4HLfjWfmZRVOuZeU43MU2F3poYHudF_lE8",
	where: ""
	},
	options: {
	styleId: 7,
	templateId: 7
	}
	});
	
	var optionsMarqueur = {
	position: latLng,
	map: map2
	}
	
	marker = new google.maps.Marker(optionsMarqueur);
	marker.setMap(map2);
	
}*/



