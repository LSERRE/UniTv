<?php
        set_time_limit(0);
        telechargerXML();
        parserXML();

        function parserXML(){

            $myTvProgram = simplexml_load_file('./XML/tnt_lite.xml');
            $arrSport = array();
            $arrFilm = array();
            $arrSerie = array();
            $heureDebut = 180000;

                for ($i=0; $i < count($myTvProgram->programme) ; $i++) { 
                    for ($j=0; $j < count($myTvProgram->programme[$i]->category) ; $j++) { 

                        $image =  $myTvProgram->programme[$i]->icon->attributes();
                        $attributesProgram = $myTvProgram->programme[$i]->attributes();
                        $idChannel = $attributesProgram['channel'];


                        if ($myTvProgram->programme[$i]->category[$j] == 'Emission sportive') {
                            $channel = findChannel($idChannel, $myTvProgram);
                            if( $channel ){
                                if(substr($attributesProgram['start'], -6) > $heureDebut){
                                    $array = array('titre' => $myTvProgram->programme[$i]->title, 'sousTitre' => $myTvProgram->programme[$i]->{'sub-title'}, 'description' => $myTvProgram->programme[$i]->desc, 'image' => $image['src'], 'start' => $attributesProgram['start'], 'stop' => $attributesProgram['stop'], 'chaine' => $channel);
                                    array_push($arrSport, $array);
                                 }
                            }

                        }elseif ($myTvProgram->programme[$i]->category[$j] == 'Série') {
                            $channel = findChannel($idChannel, $myTvProgram);
                            if( $channel ){
                                if(substr($attributesProgram['start'], -6) > $heureDebut){
                                     $array = array('titre' => $myTvProgram->programme[$i]->title, 'sousTitre' => $myTvProgram->programme[$i]->{'sub-title'}, 'description' => $myTvProgram->programme[$i]->desc, 'start' => $attributesProgram['start'], 'stop' => $attributesProgram['stop'], 'chaine' => $channel);
                                    array_push($arrSerie, $array);
                                }
                            }
                        }elseif ($myTvProgram->programme[$i]->category[$j] == 'Film') {
                            $channel = findChannel($idChannel, $myTvProgram);
                            if( $channel ){
                                if(substr($attributesProgram['start'], -6) > $heureDebut){
                                    $array = array('titre' => $myTvProgram->programme[$i]->title, 'sousTitre' => $myTvProgram->programme[$i]->{'sub-title'},'description' => $myTvProgram->programme[$i]->desc, 'image' => $image['src'], 'start' => $attributesProgram['start'], 'stop' => $attributesProgram['stop'], 'chaine' => $channel);
                                    array_push($arrFilm, $array);
                                }
                            }
                        }
                    }
                }


            $arrGlobal = array('SPORT' => $arrSport, 'FILM' => $arrFilm, 'SERIE' => $arrSerie);

            $initialPath = './JSON/';
            $initialFile = $initialPath.'programme.json';
            $OldPath = $initialPath.'OLD/';
            $OldFile = $OldPath.'programme_old.json';

            makeSauvegarde($initialPath, $initialFile, $OldPath, $OldFile);

            $fp = fopen($initialPath.'programme.json', 'w');
            fwrite($fp, json_encode($arrGlobal));
            fclose($fp);
        }


        function findChannel($idChannel, $myTvProgramBis){

            $result = $myTvProgramBis->xpath('/tv/channel[@id="'.$idChannel.'"]/display-name');

            while(list( , $chaine) = each($result)) {
                if( $chaine == "BFM TV" || $chaine == "i Télé" || $chaine == "France Ô" || $chaine == "D17" || $chaine == "La Chaîne parlementaire" ){
                    return false;
                }else{
                    return $chaine;
                }
            }
        }

        function telechargerXML(){
            $url = 'http://xmltv.dyndns.org/download/tnt_lite.zip';
            $fh = fopen('tnt_lite.zip', 'w');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_FILE, $fh); 
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // this will follow redirects
            curl_exec($ch);
            curl_close($ch);
            fclose($fh);

            dezipperXML();

            unlink('tnt_lite.zip');


        }

        function dezipperXML(){
                $file = 'tnt_lite.zip';
                $initialPath = './XML/';
                $initialFile = $initialPath.'tnt_lite.xml';
                $OldPath = $initialPath.'OLD/';
                $OldFile = $OldPath.'tnt_lite_old.xml';

                makeSauvegarde($initialPath, $initialFile, $OldPath, $OldFile);

                $zip = new ZipArchive;
                $res = $zip->open($file);
                if ($res === TRUE) {
                    $zip->extractTo($initialPath);
                    $zip->close();
                    return TRUE;
                } else {
                    return FALSE;
                }
        }

        function makeSauvegarde($initialPath, $initialFile, $OldPath, $OldFile){

                if (is_dir($initialPath) == false ){
                    mkdir($initialPath);
                }

                if( file_exists($initialFile)){
                    if(is_dir($OldPath)){
                        rename($initialFile, $OldFile);
                    }else{
                        mkdir($OldPath);
                        rename($initialFile, $OldFile );
                    }

                }

        }
?>
