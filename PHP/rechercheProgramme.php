<?php
 
// if the 'term' variable is not sent with the request, exit
if( isset($_POST['programme'])){
	
		require 'exception.inc';
		require 'connexion_bdd.inc';

		$Mysql = new Mysql();

		$dataTotal = array();
		$rs = $Mysql->TabResSQL('SELECT * FROM Salon WHERE nomProgramme like "'.$_POST['programme'] .'%"');
	
		for ($i=0; $i < count($rs); $i++) {
			$time = (strtotime($rs[$i]['dateProgramme']));
			if(  $time > time() ){
				$data = array( 'coordonnees' => $rs[$i]['coordonnees'], 'idSalon' => $rs[$i]['idSalon'], 'categorie' => $rs[$i]['categorieProgramme']);
				array_push($dataTotal, $data );
			}
		}
		// var_dump($dataTotal);
		echo json_encode($dataTotal);
		flush();
}


?>