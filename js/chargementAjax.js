var boolClick = 0;
	
	function afficherNombreNotifs(){
		 $.ajax({
			    type: "POST",
			    url: "./PHP/afficherNombreNotifs.php"
			  })
			    .done(function( msg ) {
			      $( "#nbNotifs" ).empty();
			      if (msg == 0) {
			      	   $( "#nbNotifs" ).removeClass( "aNotif" );
			      }else{
				      $( "#nbNotifs" ).addClass( "aNotif" );
				      $( "#nbNotifs" ).append(msg);
				  }
			    });
	}

	afficherNombreNotifs();
	setInterval(function() {
    	afficherNombreNotifs();
	}, 2000);

	$('#profil-picture').on('click', function(){

		if (boolClick == 0) {
			  $.ajax({
			    type: "POST",
			    url: "./PHP/afficherNotifs.php"
			  })
			    .done(function( msg ) {
			      $( "#notifs" ).empty();

			      if($(window).width()>=740){
			      	$( "#notifs" ).show();
			      	$( "#notifs" ).css('right',0+'px');
			      }
			      if($(window).width()<740){
			      	$( "#notifs" ).show();
			      }


			      $( "#notifs" ).append(msg);

	   			  $('.newNotif').on('click', function(){
				    $.ajax({
					    type: "POST",
					    url: "./PHP/updateNotifs.php"
					 })
					    .done(function( msg ) {});

					 $.ajax({
					    type: "POST",
					    url: "./PHP/afficherNotifs.php"
					  })
					    .done(function( msg ) {
					      $( "#notifs" ).empty();
					      $( "#notifs" ).show();
					      $( "#notifs" ).append(msg);
					  })
			 		  $.ajax({
					    type: "POST",
					    url: "./PHP/afficherNombreNotifs.php"
					  })
					    .done(function( msg ) {
					      $( "#nbNotifs" ).empty();
					      if (msg == 0) {
					      	   $( "#nbNotifs" ).removeClass( "aNotif" );
					      }else{
						      $( "#nbNotifs" ).addClass( "aNotif" );
						      $( "#nbNotifs" ).append(msg);
						  }
					    });
				  })

			    });	
			   	boolClick = 1;
		}else{

			if($(window).width()>=740){
	      	  $( "#notifs" ).css('right',-370+'px');
	        }
	        if($(window).width()<740){
				$( "#notifs" ).empty();
				$( "#notifs" ).hide();
	        }

			$.ajax({
					    type: "POST",
					    url: "./PHP/updateNotifs.php"
					 })
					    .done(function( msg ) {});
 		  	$.ajax({
			    type: "POST",
			    url: "./PHP/afficherNombreNotifs.php"
			  })
			    .done(function( msg ) {
			      $( "#nbNotifs" ).empty();
			      if (msg == 0) {
			      	   $( "#nbNotifs" ).removeClass( "aNotif" );
			      }else{
				      $( "#nbNotifs" ).addClass( "aNotif" );
				      $( "#nbNotifs" ).append(msg);
				  }
			    });

			boolClick = 0;
			afficherNombreNotifs();
		}
	})


