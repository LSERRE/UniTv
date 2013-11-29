

var passerIntro=false;

//Objet de création de pop-up publique
this.flachPop = {

	init:function(options){
		//x = position x en px ou %
		//y = position y en px ou %
		//dir = direction de la pop-up (left,right,up,down,none)
		//contenu = contenu de la pop-up (possibilité de mettre de l'html)
		this.params=options;
		
		return this;
	},
	createPop:function(){
		var arrowClass;
		switch(this.params.dir){
		
			case "left":	arrowClass="flachPopLeft";
			break;
			case "right":	arrowClass="flachPopRight";
			break;
			case "up":	arrowClass="flachPopUp";
			break;
			case "down":	arrowClass="flachPopDown";
			break;
			case "none":		arrowClass="";
			break;
		}
		
		this.params.passer = "<a href='#'>Passer le tutoriel</a>";
		this.params.popup = $("<div class='flachPop "+arrowClass+"'><h3>"+this.params.title+"</h3><p>"+this.params.text+"</p>"+this.params.passer+"</div>");
		this.params.popup.css("top",this.params.posX);
		this.params.popup.css("left",this.params.posY);
		this.params.popup.css("opacity","0");
		
		var it = this;
		
		this.params.popup.on("click", function(){it.animOut(it)});
		this.params.popup.find('a').on("click", function(){passerIntro=true;});
		
		$("body").append(this.params.popup);
		//On l'affiche
		setTimeout(function(){ it.params.popup.css('opacity','1'); },500);
		
		//return this; //Pour le chainage
	},
	animOut:function(ut){
		if ( $('.flachPop').hasClass("flachPopLeft") ||  $('.flachPop').hasClass("flachPopRight") )
		{
			var left = parseInt($(this.params.popup).css("left"))+$(this.params.popup).width();
			$(this.params.popup).css("left",left);
		} else {
			var up = parseInt($(this.params.popup).css("top"))+$(this.params.popup).height();
			$(this.params.popup).css("top",up);
		}
		$(this.params.popup).css("opacity","0");
		var it = $(this.params.popup);
		//On le supprime !
		setTimeout(function(){ it.remove(); },1000);
		ut.params.killed.call(this);
	}

}

if($(window).width()>740)
{
	//Tutoriel pour la première visite
	//Création des popup
	var pop1 = flachPop.init({
		posX:'35%',
		posY:'70%',
		dir:"down",
		title:"Bienvenue sur UniTV",
		text:"Pour le bon fonctionnement du site, vous devez activer votre géolocalisation. Sinon vous devrez vous géolocaliser manuellement.",
		killed:function(){
			pop2();
		}
	});
	if (!passerIntro)
		pop1.createPop();

	function pop2(){

		var pop2 = flachPop.init({
			posX:'40%',
			posY:'55%',
			dir:"left",
			title:"Les Salons",
			text:"Vous avez été géolocalisé ! choisissez maintenant un salon en cliquant sur un pin. Ces derniers sont les différents salon existant.",
			killed:function(){
				pop3();
			}
		});
		if (!passerIntro)
			pop2.createPop();
	}
		
	function pop3(){

		var pop3 = flachPop.init({
			posX:'12%',
			posY:'80%',
			dir:"up",
			title:"Connectez-vous",
			text:"Pour accéder à toutes les fonctionnalités et participer aux salons, il est nécessaire de se connecter via votre compte Facebook.",
			killed:function(){
				pop4();
			}
		});
		if (!passerIntro)
			pop3.createPop();
	}

	function pop4(){

		var pop4 = flachPop.init({
			posX:'40%',
			posY:'10%',
			dir:"down",
			title:"Recherche",
			text:"Vous pouvez rechercher un programme, les salons qui diffuse ce dernier s'afficheront sur la map.",
			killed:function(){
				//pop5();
			}
		});
		if (!passerIntro)
			pop4.createPop();
	}
}





