
$('#datepicker').datepicker({  
    inline: true,  
    showOtherMonths: true,  
    dayNamesMin: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],  
});  

    
$("#datepicker").on('focusin', function(){
	$(this).parent().addClass('active');
	
});

$("#datepicker").on('focusout', function(){
	$(this).parent().removeClass('active');
	
	
});

