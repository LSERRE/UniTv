        <?php $page = $_SERVER['REQUEST_URI']; ?>
        <?php $page1 = substr($page, 0, 15); ?>
        <footer <?php if(!empty($_GET['id']) || $page1 == "/creerSalon.php"){ ?>  class="nothomepage" <? } ?>>
        	<?php if(empty($_GET['id']) && $page1 != "/creerSalon.php"){ ?> 
	        <div id="search-desktop" class="desktop">
	        	<form class="form_search" action="">
			        <input type="search" id="search-input-desktop" name="search-desktop" class='search-input' placeholder="Rechercher un programme"/>
			        <input type="submit" hidden=""/>
	        	</form>
	        </div>
	        <?php } ?>
	        <div id="programmetv" class="als-container">
	        	 <span class="als-prev"><img src="img/arrow-left.png" alt="prev" title="previous" /></span>
		        <h3>Ce soir à la télévision</h3>
		        <div class="als-viewport">
			        <ul class="row als-wrapper" id='programme'>
			        </ul>
			    </div>
			    <span class="als-next"><img src="img/arrow-right.png" alt="next" title="next" /></span>
	        </div>
	        <div class="clear"></div>
        </footer>
        </div>
        
        <script src="js/notifs-footer.js" type="text/javascript"></script>
        <script src="js/responsive.js" type="text/javascript"></script>
		<script src="js/chargementAjax.js" type="text/javascript"></script>
        <script src="js/afficherProgramme.js" type="text/javascript"></script>
        <script src="js/rechercheProgramme.js" type="text/javascript"></script>
        <script src="https://apis.google.com/js/client.js?onload=handleClientLoad"></script>
        <script src="js/localisation.js" type="text/javascript"></script>
        <script src="https://apis.google.com/js/client.js?onload=load"></script>
        <script src="js/others.js" type="text/javascript"></script>
  </body>
</html>