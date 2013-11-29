<?php
        set_time_limit(0);
        telechargerXML();
        parserXML();

        function parserXML(){

            $myTvProgram = simplexml_load_file('./XML/tnt.xml');
            $arrSport = array();
            $arrFilm = array();
            $arrSerie = array();

            $resultFull = $myTvProgram->xpath('/tv/programme[category = "sport" ]');
            while(list( , $result) = each($resultFull)) {
                $channel = findChannel($result->xpath('/programme[@channel]'), $myTvProgram);
                $array = array('titre' => $result->xpath('/programme/titre'), 'sousTitre' =>$result->xpath('/programme/sub-title'), 'description' => $result->xpath('/programme/desc'), 'image' => $result->xpath('/programme/icon[@src]'), 'start' => $result->xpath('/programme[@start]'), 'stop' => $result->xpath('/programme[@stop]'), 'chaine' => $channel);
                array_push($arrSport, $array);
            }

            $resultFull = $myTvProgram->xpath('/tv/programme[category = "série" ]');
            while(list( , $result) = each($resultFull)) {
                $channel = findChannel($result->xpath('/programme[@channel]'), $myTvProgram);
                $array = array('titre' => $result->xpath('/programme/titre'), 'sousTitre' =>$result->xpath('/programme/sub-title'), 'description' => $result->xpath('/programme/desc'), 'image' => $result->xpath('/programme/icon[@src]'), 'start' => $result->xpath('/programme[@start]'), 'stop' => $result->xpath('/programme[@stop]'), 'chaine' => $channel);
                array_push($arrSerie, $array);
            }   

            $resultFull = $myTvProgram->xpath('/tv/programme[category = "film" ]');
            while(list( , $result) = each($resultFull)) {
                $channel = findChannel($result->xpath('/programme[@channel]'), $myTvProgram);
                $array = array('titre' => $result->xpath('/programme/titre'), 'sousTitre' =>$result->xpath('/programme/sub-title'), 'description' => $result->xpath('/programme/desc'), 'image' => $result->xpath('/programme/icon[@src]'), 'start' => $result->xpath('/programme[@start]'), 'stop' => $result->xpath('/programme[@stop]'), 'chaine' => $channel);
                array_push($arrFilm, $array);
            }


/*            while(list( , $programmeSport) = each($resultSport)) {
                $image =  $programmeSport->icon->attributes();
                $attributesProgram = $programmeSport->attributes();
                $idChannel = $attributesProgram['channel'];
                
                $channel = findChannel($idChannel, $myTvProgram);
                $array = array('titre' => $programmeSport->title, 'sousTitre' => $programmeSport->{'sub-title'}, 'description' => $programmeSport->desc, 'image' => $image['src'], 'start' => $attributesProgram['start'], 'stop' => $attributesProgram['stop'], 'chaine' => $channel);
                array_push($arrSport, $array);
            }*/

/*            $resultSerie = $myTvProgram->xpath('/tv/programme[category = "série" ]');
            while(list( , $programmeSerie) = each($resultSerie)) {
                $image =  $programmeSerie->icon->attributes();
                $attributesProgram = $programmeSerie->attributes();
                $idChannel = $attributesProgram['channel'];
                
                $channel = findChannel($idChannel, $myTvProgram);
                $array = array('titre' => $programmeSerie->title, 'sousTitre' => $programmeSerie->{'sub-title'}, 'description' => $programmeSerie->desc, 'image' => $image['src'], 'start' => $attributesProgram['start'], 'stop' => $attributesProgram['stop'], 'chaine' => $channel);
                array_push($arrSerie, $array);
            }

            $resultFilm = $myTvProgram->xpath('/tv/programme[category = "film" ]');
            while(list( , $programmeFilm) = each($resultFilm)) {
                $image =  $programmeFilm->icon->attributes();
                $attributesProgram = $programmeFilm->attributes();
                $idChannel = $attributesProgram['channel'];
                
                $channel = findChannel($idChannel, $myTvProgram);
                $array = array('titre' => $programmeFilm->title, 'sousTitre' => $programmeFilm->{'sub-title'}, 'description' => $programmeFilm->desc, 'image' => $image['src'], 'start' => $attributesProgram['start'], 'stop' => $attributesProgram['stop'], 'chaine' => $channel);
                array_push($arrFilm, $array);
            }*/




            $arrGlobal = array('SPORT' => $arrSport, 'FILM' => $arrFilm, 'SERIE' => $arrSerie,);

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
                if($chaine != "")
                return $chaine;
            }

        }

        function telechargerXML(){
            $url = 'http://xmltv.dyndns.org/download/tnt.zip';
            $fh = fopen('tnt.zip', 'w');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_FILE, $fh); 
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // this will follow redirects
            curl_exec($ch);
            curl_close($ch);
            fclose($fh);

            dezipperXML();

            unlink('tnt.zip');

        }

        function dezipperXML(){
                $file = 'tnt.zip';
                $initialPath = './XML/';
                $initialFile = $initialPath.'tnt.xml';
                $OldPath = $initialPath.'OLD/';
                $OldFile = $OldPath.'tnt_old.xml';

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
