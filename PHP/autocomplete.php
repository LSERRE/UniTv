<?php

	if(isset($_REQUEST['term'])){
		require 'exception.inc';
		require 'connexion_bdd.inc';

		$Mysql = new Mysql();
		  

		$rs = $Mysql->TabResSQL('SELECT * FROM Salon WHERE nomProgramme like "'.$_REQUEST['term'] .'%"');

		if (empty($rs[0]['nomProgramme'])) {
			$data = array();
			array_push($data,  '___________ Il n\'y a aucun résultat trouvé');	
			echo json_encode($data);	
		}else{	
			$dataReturn = programmeAVenir($rs);
			echo json_encode($dataReturn);
		}
		flush();

	}


	function programmeAVenir($rs){
		for ($i=0; $i < count($rs) ; $i++) { 
			$date =  strtotime($rs[$i]['dateProgramme']);
			if(  $date > time() ){
				$data = array();
				array_push($data, $rs[$i]['nomProgramme']);	
				return $data;
			}

		}
	}
?>