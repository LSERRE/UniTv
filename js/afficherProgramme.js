afficherProgrammeFooter();

//Affiche le programme télé dans le footer

function afficherProgrammeFooter() {
    $.getJSON("../JSON/programme.json", function (json) {

        var objDate = new Date();
        var dateJour = objDate.getDate() + '/' + (objDate.getMonth() + 1);

        //Parcour les différentes catégories dans le json
        for (var j = 0; j < 3; j++) {
            var objectProgramme;
            if (j == 0) {
                objectProgramme = json.SPORT;
            } else if (j == 1) {
                objectProgramme = json.FILM;
            } else {
                objectProgramme = json.SERIE;
            }

            //parcour tout les programmes de la catégorie
            for (var i = 0; i < objectProgramme.length; i++) {
                // getTimeProg, 1 pour l'heure de départ, 0 pour l'heure de fin

                var start = getTimeProg(objectProgramme[i], 1);
                var stop = getTimeProg(objectProgramme[i], 0);
                var date = getDateProg(objectProgramme[i]);

                var programme = cutProgramme(objectProgramme[i].chaine[0] + ' - ' + objectProgramme[i].titre[0]);

                //Si le programme passe aujourd'hui, il est ajouté dans le slider
                if (dateJour == date) {
                    $('#programme').append('<li class="span3 als-item"><h4>' + programme + '</h4><p>Diffusion à ' + start + '</p></li>');

                };
            };
        };

        //Pour gérer le programme TV

        calculerProgrammes();
        responsiveProgrammes();
        $(window).resize(function () {
            responsiveProgrammes();
        });


    });

}

function responsiveProgrammes() {
    var width_screen = $(window).width();
    var width_viewport = (width_screen - 120);
    var combien_de_prog = Math.floor(width_viewport / 230);
    var test_marge = (combien_de_prog - 1) * 30;
    var width_besoin_viewport = test_marge + combien_de_prog * 230;
    if (width_besoin_viewport < width_viewport) {
        $('.als-viewport').css("width", (test_marge + combien_de_prog * 230));
    } else {
        $('.als-viewport').css("width", ((test_marge - 1) + (combien_de_prog - 1) * 230));
    }
}

function calculerProgrammes() {
    var nb_prog = document.querySelectorAll('.als-item').length;
    var width_prog = (nb_prog * 230);
    var position_marge = ((Math.floor((nb_prog / 2))) + 1);
    var width_container = ((width_prog + ((nb_prog - 1) * 30)) / 2);
    var ajouter_position_marge = ".als-wrapper li:nth-child(" + position_marge + ")";
    $(ajouter_position_marge).css("margin-left", 0);
    $('.als-wrapper').css("width", width_container);
}

// Autocomplete dans le formulaire CreerSalon.php la liste des programmes suivant la qualtégorie et la date
function chargerProgramme() {

    var dateSelect = $('#datepicker').val();

    if (dateSelect != "") {
        $('#programme').empty();
        var tabDate = dateSelect.split('-');
        dateSelect = tabDate[2] + '/' + tabDate[1];
        $('#programme').append('<option value="">Selectionnez votre programme</option>');

        $.getJSON("JSON/programme.json", function (json) {

            var cat = $('#categorie').val();

            var objectProgramme;
            if (cat == "sport") {
                objectProgramme = json.SPORT;
            } else if (cat == "film") {
                objectProgramme = json.FILM;
            } else {
                objectProgramme = json.SERIE;
            }

            // console.log(objectProgramme);

            for (var i = 0; i < objectProgramme.length; i++) {
                // getTimeProg, 1 pour l'heure de départ, 0 pour l'heure de fin
                var start = getTimeProg(objectProgramme[i], 1);
                var stop = getTimeProg(objectProgramme[i], 0);
                var date = getDateProg(objectProgramme[i]);

                var programme = cutProgramme(objectProgramme[i].chaine[0] + ' - ' + objectProgramme[i].titre[0]);

                if (dateSelect == date) {
                    $('#programme').append('<option value="' + objectProgramme[i].titre[0] + '" ><h4>' + programme + '</h4><p> -- ' + start + '</p></option>');
                }
            }

        });
    }
}

// Quand le programme est selectionné dans CreerSalon.php, ajoute toutes les données concernant ce programme 
// dans des inputs hidden
function selectProgramme() {
    $.getJSON("JSON/programme.json", function (json) {
        $('#hidden').empty();
        var cat = $('#categorie').val();
        var objectProgramme;
        if (cat == "sport") {
            objectProgramme = json.SPORT;
        } else if (cat == "film") {
            objectProgramme = json.FILM;
        } else {
            objectProgramme = json.SERIE;
        }

        // console.log(objectProgramme);

        var dateSelect = $('#datepicker').val();
        var tabDate = dateSelect.split('-');
        dateSelect = tabDate[2] + '/' + tabDate[1];

        for (var i = 0; i < objectProgramme.length; i++) {
            // getTimeProg, 1 pour l'heure de départ, 0 pour l'heure de fin
            var date = getDateProg(objectProgramme[i]);

            if (objectProgramme[i].titre[0] == $('#programme').val() && dateSelect == date) {
                var start1 = objectProgramme[i].start[0].substr(8, 2);
                var start2 = objectProgramme[i].start[0].substr(10, 2);

                var heure = start1 + ':' + start2;

                var jour = objectProgramme[i].start[0].substr(6, 2);
                var mois = objectProgramme[i].start[0].substr(4, 2);
                var annee = objectProgramme[i].start[0].substr(0, 4);
                var date = annee + '-' + mois + '-' + jour;

                var dateComplete = date + ' ' + heure;

                $('#hidden').append('<input type="hidden" name="dateProgramme" value="' + dateComplete + '" />');
                $('#hidden').append('<input type="hidden" name="chaineProgramme" value="' + objectProgramme[i].chaine[0] + '" />');
                $('#hidden').append('<input type="hidden" name="descriptionProgramme" value="' + objectProgramme[i].description[0] + '" />');
                $('#hidden').append('<input type="hidden" name="nomProgramme" value="' + objectProgramme[i].titre[0] + '" />');
                $('#hidden').append('<input type="hidden" name="image" value="' + objectProgramme[i].image[0] + '" />');
            }
        }
    });
}


//Coupe l'heure de début et de fin du programme
function getTimeProg(time, bool) {

    if (bool) {
        var start1 = time.start[0].substr(8, 2);
        var start2 = time.start[0].substr(10, 2);

        return start1 + 'h' + start2;

    } else {
        var stop1 = time.stop[0].substr(8, 2);
        var stop2 = time.stop[0].substr(10, 2);

        return stop1 + 'h' + stop2;
    }

}

//Coupe le jour et le mois du programme
function getDateProg(time) {

    var jour = time.start[0].substr(6, 2);
    var mois = time.start[0].substr(4, 2);

    return jour + '/' + mois;

}

//Coupe le nom du programme tv, si il est trop grand pour etre affiché dans le slider

function cutProgramme(textProgramme) {

    longeurTotale = textProgramme.length;
    if (longeurTotale > 41) {
        return textProgramme.substring(0, 38) + '...';
    } else {
        return textProgramme;
    }

}



// Les eveneements qui permettent l'autocompletion des programmes dans la liste 
$('#programme').change(function () {
    selectProgramme();
});

$("#datepicker").datepicker({
    dateFormat: "yy-mm-dd",
    minDate: "+0D",
    maxDate: "+7D",
    onSelect: function () {
        chargerProgramme();
    }
});


$('#categorie').change(function () {
    chargerProgramme();
});
// }