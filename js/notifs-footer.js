$( document ).ready(function(){

      var vue = parseInt($('.als-viewport').width());
      var widthTot = parseInt($('.als-wrapper').width());
      var positionLeft = 0;
      var posFinale = -widthTot+vue; 

      $('.als-prev>img').hide();

      $('.als-next>img').on('click',function(){
        checkValeurs();
        positionLeft = positionLeft-vue;
        animation(positionLeft);
      });

      $('.als-prev>img').on('click',function(){
        checkValeurs();
        positionLeft = positionLeft+vue;
        if(positionLeft > 0){
         positionLeft = 0; 
        }
        animation(positionLeft);
      });

      function animation(positionLeft){
        $('#programme').css('transition', 'margin-left 1s ease');
        $('#programme').css('margin-left', positionLeft + 'px');
        checkPosition();
      }

      function checkPosition(){
        if(positionLeft == 0){
          $('.als-prev>img').hide();          
        }else{
          $('.als-prev>img').show();
       } 

        if(positionLeft <= posFinale+150){
          $('.als-next>img').hide();          
        }else{
          $('.als-next>img').show();
        } 
      }

      function checkValeurs(){
        vue = parseInt($('.als-viewport').width());
        widthTot = parseInt($('.als-wrapper').width());

        if((vue+1)%260 != 0 ){
          vue = parseInt($('.als-viewport').width())+30;
        }
        posFinale = -widthTot+vue;
      }

       var screenHeight = $(window).height();
       $('#ficheMembre').css('height', screenHeight-70);
       $('#lesSalons').css('height', screenHeight-320);

       $( window ).resize(function() {
          var screenHeight = $(window).height();
          $('#ficheMembre').css('height', screenHeight-70);
          $('#lesSalons').css('height', screenHeight-320);
       });

       tailleNotifs();
       function tailleNotifs(){
          if( parseInt($(window).width()) > 740){
              var height_screen = $(window).height();
              var height_notifs = height_screen - 70 - 250;
              $('#notifs').css("height", height_notifs);
          }

          if(!($('footer').length)){
              var height_screen = $(window).height();
              var height_notifs = height_screen - 70;
              $('#notifs').css("height", height_notifs);
          }
       }

       $( window ).resize(function() {
          tailleNotifs();
       });






});