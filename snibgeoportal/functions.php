<?php
class enciclovida {
    function ligaComentarios($mysqli, $llaveejemplar): string {
        //arreglos de los diferentes catalogos [01-Echinodermata, 02-Crustaceos, 03-Hongos, 04-Invertebrados, 05-Plantas, 06-Algas, 07-Vertebrados, 08-Diptera, 09-Arthropoda]
        $one = array("ECHIN","ANIM");
        $two = array("CRUST");
        $three = array("FUNGI");
        $four = array("ANNEL","BRACH","BRYOZ","CNIDA","CTENO","CHAET","MOLUS","MYXOZ","ONYCH","PHORO","PLACO","PORIF","ROTIF","SIPUN","TARDI","UROCH");
        $five = array("PLANT","ANGIO","BRIOF","GIMNO","PTERI","VASCU");
        $six = array("PROT","PROKA");
        $seven = array("AVES","CONA","REPTI","ANFIB","MAMIF","PECES");
        $eight = array("DIPTE");
        $nine = array("ARACH","ARTHR","COLEO","HEMIP","HYMEN","INSEC","LEPID","MYRIA","ORTHO");
        
        $sql = "SELECT idnombre
                FROM snib.ejemplar_curatorial 
                INNER JOIN snib.nombre_taxonomia USING(llavenombre)
                WHERE llaveejemplar = '{$llaveejemplar}'";
        $result = $mysqli->query($sql);
        $row = $result->fetch_array(MYSQLI_NUM);
        $catalogo = "";
        $idcat = 0;
        $cod = 0;
        $catalogo = preg_replace('/[0-9]+/', '',$row[0]);
        $idcat = intval(preg_replace('/[^0-9]+/', '', $row[0]), 10); 
        		
        
        if (in_array($catalogo, $one)) {
            $cod = 1000000;
        }
        else if (in_array($catalogo, $two)){
            $cod = 2000000;
        }
        else if (in_array($catalogo, $three)){
            $cod = 3000000;
        }
        else if (in_array($catalogo, $four)){
            $cod = 4000000;
        }
        else if (in_array($catalogo, $five)){
            $cod = 6000000;
        }
        else if (in_array($catalogo, $six)){
            $cod = 7000000;
        }
        else if (in_array($catalogo, $seven)){
            $cod = 8000000;
        }
        else if (in_array($catalogo, $eight)){
            $cod = 9000000;
        }
        else if (in_array($catalogo, $nine)){
            $cod = 10000000;
        }
        $codNombre = $cod + $idcat;
        $result->free();       
        return "http://www.enciclovida.mx/especies/{$codNombre}/comentarios/new?proveedor_id={$llaveejemplar}&tipo_proveedor=6";
    }
    
    function obtenResumen($mysqli, $llaveejemplar): array {
        //mysqli_next_result($mysqli);
        $scientificName = $autor = $commonName = $region = $localidad = $procedencia = $col = $ins = $lat = $lon = '';
        try{  
            // Consultamos el nombre cinetifico de la especie 
            $sql = "SELECT especievalida, autorvalido, TRIM(BOTH ',' FROM REGEXP_REPLACE(nombrecomun, '[^,]*\\\\([^)]*\\\\),? ?', '')) as nombrecomun,
                    region, localidad, procedenciaejemplar, coleccion, institucion,
                    latitud, longitud
                    FROM snib.informaciongeoportal_siya WHERE idejemplar = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $llaveejemplar);
            $stmt->execute();
            $stmt->bind_result($scientificName,$autor,$commonName,$region,$localidad,$procedencia,$col,$ins,$lat,$lon);
            if ($stmt->fetch()) {
                $result = array($scientificName,$autor,$commonName,$region,$localidad,$procedencia,$col,$ins,$lat,$lon);
            } else {
                $result = array(); // No se encontró ningún resultado
            }       
            // cerramos el statement y la conexión
            $stmt->close();            
        } catch (Exception $e) {
            // Manejo de excepciones
            $scientificName='';
            echo "Se produjo un error: " . $e->getMessage();
        }
        return $result;
    }

    function obtenProyecto($mysqli, $llaveejemplar): string {
        $urlProyecto = $titulo = $urlProyectoConabio = $urlOrigen = '';
        try{  
            // Consultamos el nombre cinetifico de la especie 
            $sql = "SELECT titulo, urlproyectoconabio, urlorigen
                    FROM snib.ejemplar_curatorial
                    INNER JOIN snib.proyecto USING(llaveproyecto) WHERE llaveejemplar = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $llaveejemplar);
            $stmt->execute();
            $stmt->bind_result($titulo,$urlProyectoConabio,$urlOrigen);
            if ($stmt->fetch()) {
                if ($urlProyectoConabio != '') {
                    $urlProyecto = "<a href='$urlProyectoConabio' target='_blank'>$titulo</a>";
                } else if ($urlOrigen != '') {
                    $urlProyecto = "<a href='$urlOrigen' target='_blank'>$titulo</a>";
                } else $urlProyecto=$titulo;
            }       
            // cerramos el statement y la conexión
            $stmt->close();            
        } catch (Exception $e) {
            // Manejo de excepciones
            $urlProyecto='';
            echo "Se produjo un error: " . $e->getMessage();
        }
        return $urlProyecto;
    }
}    
?>