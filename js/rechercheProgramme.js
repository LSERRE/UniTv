$('#search-input-desktop').autocomplete({
	source:'./PHP/autocomplete.php', 
	minLength:1
});

$('#search-input-mobile').autocomplete({
	source:'./PHP/autocomplete.php', 
	minLength:1
});


$('.form_search').on('submit', function(e){
	e.preventDefault();
});