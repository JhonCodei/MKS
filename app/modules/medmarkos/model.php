<?php

Class MedmarkosModel
{
    public function __construct()
    {
        $this->db = Database::Connection();#Connection DB#
        #__SQL__();
    }
    #__functions__
    public function __cantidad_padron_medicos__($repre_cod)
    {
        $output = null;

        $Exec_Pronostico = null;
        $user_existe_medico = user_existe_medico($repre_cod);

            $Query_PRONOSTICO1 = "SELECT 
                                                            COUNT(medico_id) AS cantidad
                                                        FROM
                                                            mks_unidos_db.tbl_maestro_medicos
                                                        WHERE
                                                            medico_representante = :repre_cod;";
            $Query_PRONOSTICO2 = "SELECT 
                                                            COUNT(medico_id) AS cantidad
                                                        FROM
                                                            mks_unidos_db.tbl_maestro_medicos
                                                        WHERE
                                                            medico_zona = (SELECT 
                                                                    representante_zonag
                                                                FROM
                                                                    tbl_representantes
                                                                WHERE
                                                                    representante_codigo = :repre_cod)
                                                                AND medico_representante IS NULL;";

            if($user_existe_medico == 1)
            {
                $Exec_Pronostico = $Query_PRONOSTICO1;
            }else
            {
                $Exec_Pronostico = $Query_PRONOSTICO2;
            }

        $query = $this->db->prepare($Exec_Pronostico);
        $query->bindParam(":repre_cod", $repre_cod);
        if($query->execute())
        {
            if($query->rowCount() > 0)
            {
                $res_query = $query->fetch(PDO::FETCH_ASSOC);

                $cantidad = $res_query['cantidad'];
                $output = $cantidad;

            }else
            {
                $output = 0;
            }
        }                                           
        return $output;
    }
    public function __cantidad_medico_visitados__($repre_cod, $year, $mes)
    {
        $output = null;

        $sql = "SELECT 
                        COUNT(DISTINCT (medpro_medico_cmp)) AS cantidad
                    FROM
                        mks_unidos_db.tbl_medpro_detalle
                    WHERE
                        medpro_vendedor = :repre_cod
                            AND YEAR(medpro_fecha) = :year
                            AND MONTH(medpro_fecha) = :mes;";

        $query = $this->db->prepare($sql);
        $query->bindParam(":repre_cod", $repre_cod);
        $query->bindParam(":year", $year);
        $query->bindParam(":mes", $mes);
        if($query->execute())
        {
            if($query->rowCount() > 0)
            {
                $res_query = $query->fetch(PDO::FETCH_ASSOC);

                $cantidad = $res_query['cantidad'];
                $output = $cantidad;

            }else
            {
                $output = 0;
            }
        }                                           
        return $output;
    }
    public function load_movimiento($periodo, $file)
    {
        date_default_timezone_set('UTC');

        $parse_periodo = explode("-", nl2br(htmlentities(wordwrap($periodo, 4, "-", true))));

        $year = (int)$parse_periodo[0];
        $mes = (int)$parse_periodo[1];

        $delete = $this->db->prepare("DELETE FROM tbl_medpro_detalle WHERE YEAR(medpro_fecha) = :year AND MONTH(medpro_fecha) = :mes");
        
        $delete->bindparam(":year", $year, PDO::PARAM_INT);
        $delete->bindparam(":mes", $mes, PDO::PARAM_INT);

        if($delete->execute())
        {
            $inputFileName = $file;
            $objPHPExcel   = PHPExcel_IOFactory::load($inputFileName);
                
            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) 
            {
                $worksheetTitle     = $worksheet->getTitle();
                $highestRow         = $worksheet->getHighestRow();
                $highestColumn      = $worksheet->getHighestColumn();
                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                $nrColumns          = ord($highestColumn) - 64;
            }
                
            for ($row = 1; $row <= $highestRow; ++$row) 
            {
                $DataRow = array();
                    
                for ($col = 0; $col < $highestColumnIndex; ++$col) 
                {
                    $cell      = $worksheet->getCellByColumnAndRow($col, $row);
                    $DataRow[] = $cell->getValue();
                }

                
                $medpro_vendedor = (int)$DataRow[0]; 
                $medpro_medico_cmp = trim($DataRow[2]); #FECHA


                if(strpos($medpro_medico_cmp, '*') !== FALSE)
                {
                    $medpro_medico_cmp_ex = explode("*", $medpro_medico_cmp);
                    (int)$medpro_medico_cmp = $medpro_medico_cmp_ex[0];
                }else
                {
                    (int)$medpro_medico_cmp = $medpro_medico_cmp;
                }

                $medpro_producto = (int)$DataRow[5]; 
                $medpro_descripcion_producto = $DataRow[6];
                $medpro_cantidad = (int)$DataRow[7];
                $medpro_fecha = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($DataRow[4])); #FECHA

                $insert = $this->db->prepare("INSERT INTO tbl_medpro_detalle(medpro_vendedor, medpro_medico_cmp, 
                                            medpro_producto, medpro_descripcion_producto, medpro_cantidad, medpro_fecha)
                                            VALUES(:medpro_vendedor, :medpro_medico_cmp, :medpro_producto, 
                                            :medpro_descripcion_producto, :medpro_cantidad, :medpro_fecha)");
                $insert->bindparam(':medpro_vendedor', $medpro_vendedor, PDO::PARAM_INT);
                $insert->bindparam(':medpro_medico_cmp', $medpro_medico_cmp, PDO::PARAM_INT);
                $insert->bindparam(':medpro_producto', $medpro_producto, PDO::PARAM_INT);
                $insert->bindparam(':medpro_descripcion_producto', $medpro_descripcion_producto, PDO::PARAM_STR);
                $insert->bindparam(':medpro_cantidad', $medpro_cantidad, PDO::PARAM_INT);
                $insert->bindparam(':medpro_fecha', $medpro_fecha, PDO::PARAM_INT);
                if($insert->execute())
                {
                    print "insertado . . .<br>";
                }else
                {
                    print "insert, event =>".errorPDO($insert)."<br>";
                }
            }
        }else
        {
            print errorPDO($delete)."<br>";
        }
    }
    public function load_medicos($file)
    {
        $s = 0;

        $file_excel = $file;
                   
        $delete = $this->db->prepare("TRUNCATE TABLE tbl_maestro_medicos");
        
        if($delete->execute())
        {
            $inputFileName = $file_excel;
        
            $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
            $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
            $cacheSettings = array('memoryCacheSize' => '4096MB');

            PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings); 

            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) 
            {
                $worksheetTitle     = $worksheet->getTitle();
                $highestRow         = $worksheet->getHighestRow();
                $highestColumn      = $worksheet->getHighestColumn();
                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                $nrColumns          = ord($highestColumn) - 64;
            }

            for ($row = 1; $row <= $highestRow; ++$row) 
            {
                $DataRow = array();
                
                for ($col = 0; $col < $highestColumnIndex; ++$col) 
                {
                    $cell      = $worksheet->getCellByColumnAndRow($col, $row);
                    $DataRow[] = $cell->getValue();
                }
                
                $med_cod = null;
                $med_cor = null;
                $med_turno = null;

                if(strpos($DataRow[0], '*') !== FALSE)
                {
                    $parse = explode("*", $DataRow[0]);

                    $med_cod = (int)str_replace('77700', '', $parse[0]);
                    $med_cor = @((int)search_correlativo($med_cod))+1;
                    
                }else
                {
                    $med_cod = (int)str_replace('77700', '', $DataRow[0]);
                    $med_cor = @((int)search_correlativo($med_cod))+1;
                }
                if($med_cor == 1)
                {
                    $med_turno = 'AM';
                }else
                {
                    $med_turno = 'PM';
                }

                $med_nya = (string)$DataRow[1];
                $med_esp = (string)$DataRow[2];

                if(strpos($med_esp, ' ') !== FALSE)
                {
                        $MEDICO_EXPLODE = explode(" ", $med_esp);
                        $med_esp = $MEDICO_EXPLODE[0];
                }else
                {
                        $med_esp = $med_esp;
                } 
                
                $med_cat = (string)$DataRow[3];
                $med_inst = (string)$DataRow[4];
                $med_dir = (string)$DataRow[5];
                $med_loc = (string)$DataRow[6];
                $med_zona = (int)$DataRow[7];
                $med_altbaja = (string)$DataRow[8];
                $med_ubigeo = (int)$DataRow[9];

                if(strlen($med_ubigeo) == 0 || $med_ubigeo == null)
                {
                    $med_ubigeo = (int)_search_ubigeo($med_loc);
                }

                $insert = $this->db->prepare("INSERT INTO tbl_maestro_medicos(medico_cmp,medico_correlativo,
                                                            medico_nombre, medico_especialidad, 
                                                            medico_categoria, medico_institucion, medico_direccion,
                                                            medico_localidad, medico_turno, medico_zona, medico_alta_baja, medico_ubigeo)
                                                VALUES(:med_cod, :med_cor, :med_nya, :med_esp, :med_cat, :med_inst,
                                                        :med_dir, :med_loc, :med_turno, :med_zona, :med_altbaja, :med_ubigeo)");

                $insert->bindParam(":med_cod", $med_cod);
                $insert->bindParam(":med_cor", $med_cor);
                $insert->bindParam(":med_nya", $med_nya);
                $insert->bindParam(":med_esp", $med_esp);
                $insert->bindParam(":med_cat", $med_cat);
                $insert->bindParam(":med_inst", $med_inst);
                $insert->bindParam(":med_dir", $med_dir);
                $insert->bindParam(":med_loc", $med_loc);
                $insert->bindParam(":med_turno", $med_turno);
                $insert->bindParam(":med_zona", $med_zona);
                $insert->bindParam(":med_altbaja", $med_altbaja);
                $insert->bindParam(":med_ubigeo", $med_ubigeo);
                
                if($insert->execute())
                {
                    print $med_cod .' - '. $s++.'<br>';
                }else
                {
                    print $med_cod. " - error => " .errorPDO($insert) .'<br>';
                    return false;
                }   
            }
        }else
        {
            print errorPDO($delete);
        }
    }
    public function load_medicos_x_repre_bk($file)
    {
        $s = 0;
        $x = 0;

        $file_excel = $file;
                   
        $inputFileName = $file_excel;
        
        $objPHPExcel   = PHPExcel_IOFactory::load($inputFileName);
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        $cacheSettings = array('memoryCacheSize' => '4096MB');

        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings); 

        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) 
        {
            $worksheetTitle     = $worksheet->getTitle();
            $highestRow         = $worksheet->getHighestRow();
            $highestColumn      = $worksheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $nrColumns          = ord($highestColumn) - 64;
        }

        for ($row = 1; $row <= $highestRow; ++$row) 
        {
            $DataRow = array();
            
            for ($col = 0; $col < $highestColumnIndex; ++$col) 
            {
                $cell      = $worksheet->getCellByColumnAndRow($col, $row);
                $DataRow[] = $cell->getValue();
            }
            
            $med_cod = null;
            $med_cor = null;
            $med_turno = null;

            if(strpos($DataRow[0], '*') !== FALSE)
            {
                $parse = explode("*", $DataRow[0]);

                $med_cod = (int)str_replace('77700', '', $parse[0]);
                #$med_cor = @((int)search_correlativo($med_cod))+1;
                $med_cor = $parse[1];
            }else
            {
                $med_cod = (int)str_replace('77700', '', $DataRow[0]);
                $med_cor = 1;
            }
            
            if($med_cor == 1)
            {
                $med_turno = 'AM';
            }else
            {
                $med_turno = 'PM';
            }

            $med_nya = (string)$DataRow[1];
            $med_esp = (string)$DataRow[2];

            if(strpos($med_esp, ' ') !== FALSE)
            {
                $MEDICO_EXPLODE = explode(" ", $med_esp);
                $med_esp = $MEDICO_EXPLODE[0];
            }else
            {
                $med_esp = $med_esp;
            } 
            
            $med_cat = (string)trim(strtoupper($DataRow[3]));
            $med_inst = (string)trim($DataRow[4]);
            $med_dir = (string)trim($DataRow[5]);
            $med_loc = (string)trim($DataRow[6]);
            $med_zona = (int)trim($DataRow[7]);
            $med_altbaja = (string)trim($DataRow[8]);
            $medico_representante = (int)trim($DataRow[9]);
            $medico_supervisor = (int)trim($DataRow[10]);
            $med_ubigeo = (int)trim($DataRow[11]);
            
            if(strlen($med_ubigeo) == 0 || $med_ubigeo == null || $med_ubigeo == 0)
            {
                $med_ubigeo = (int)_search_ubigeo($med_loc);
            }
            
            // print $med_cod."||".$med_cor."||".$med_nya."||".$med_esp."||".$med_cat."||".$med_inst."||".$med_dir."||".$med_loc."||".$med_turno."||".$med_zona."||".$med_altbaja."||".$medico_representante."||".$medico_supervisor."||".$med_ubigeo.'##########<br>';
            
            $data_medico = search_data_med($med_cod, $medico_representante);

            $id_r = $data_medico['id'];
            $correlativo_r = $data_medico['correlativo'];
            $zona_r = $data_medico['zona'];
            $categoria_r = $data_medico['categoria'];
            $localidad_r = $data_medico['localidad'];
            $alta_baja_r = $data_medico['alta_baja'];
            $turno_r = $data_medico['turno'];
            $ubigeo_r = $data_medico['ubigeo'];
            $representante_r = $data_medico['representante'];
            $existe = $data_medico['existe'];


            if($existe == 1)
            {
                if($zona_r == $med_zona && $representante_r == $medico_representante)
                {
                    #$correlativo_r != $med_cor
                    if($alta_baja_r != $med_altbaja || $localidad_r != $med_loc || $categoria_r != $med_cat)
                    {

                        $new_correlativo = @((int)search_correlativo($med_cod))+1;
                        // print  'del sql A/B = '.$alta_baja_r . ' - del excel = ' . $med_altbaja . ' - del sql LOC = '. $localidad_r . ' - del excel = '. $med_loc .' -  del SQL UBI = '.$ubigeo_r . ' del excel = '. $med_ubigeo.'<br><br>';
                        
                        // print 'new corre = ' . $new_correlativo.'<br>';

                        $insert = $this->db->prepare("INSERT INTO tbl_maestro_medicos(medico_cmp, medico_correlativo,
                                                        medico_nombre, medico_especialidad, 
                                                        medico_categoria, medico_institucion, medico_direccion,
                                                        medico_localidad, medico_turno, medico_zona, medico_alta_baja, medico_ubigeo,
                                                        medico_representante, medico_supervisor)
                                                    VALUES(:med_cod, :med_cor, :med_nya, :med_esp, :med_cat, :med_inst,
                                                            :med_dir, :med_loc, :med_turno, :med_zona, :med_altbaja, :med_ubigeo,
                                                            :medico_representante, :medico_supervisor)");

                        $insert->bindParam(":med_cod", $med_cod);
                        $insert->bindParam(":med_cor", $new_correlativo);
                        $insert->bindParam(":med_nya", $med_nya);
                        $insert->bindParam(":med_esp", $med_esp);
                        $insert->bindParam(":med_cat", $med_cat);
                        $insert->bindParam(":med_inst", $med_inst);
                        $insert->bindParam(":med_dir", $med_dir);
                        $insert->bindParam(":med_loc", $med_loc);
                        $insert->bindParam(":med_turno", $med_turno);
                        $insert->bindParam(":med_zona", $med_zona);
                        $insert->bindParam(":med_altbaja", $med_altbaja);
                        $insert->bindParam(":med_ubigeo", $med_ubigeo);
                        $insert->bindParam(":medico_representante", $medico_representante);
                        $insert->bindParam(":medico_supervisor", $medico_supervisor);

                        if($insert->execute())
                        {
                            print $med_cod.' - lenght: '.strlen($med_cod) .' DIFERENTE 3 OPCIONES - '. $s++.'<br>';
                        }else
                        {
                            print $med_cod. " DIFERENTE 3 OPCIONES - error => " .errorPDO($insert) .'<br>';
                        }
                        $update = $this->db->prepare("UPDATE tbl_maestro_medicos SET medico_alta_baja = 'B' WHERE medico_id = :id_r");
                        $update->bindParam(':id_r', $id_r);

                        if($update->execute())
                        {
                            print "ACTUALIZADO;<br>";
                            print  'del sql A/B = '.$alta_baja_r . ' - del excel = ' . $med_altbaja . ' - del sql LOC = '. $localidad_r . ' - del excel = '. $med_loc .'- del SQL Cat= '. $categoria_r . ' - del excel - = '.$med_cat.'<br><br>';
                        }else
                        {
                            print "NO ACTUALIZADO;<br>";
                            print  'del sql A/B = '.$alta_baja_r . ' - del excel = ' . $med_altbaja . ' - del sql LOC = '. $localidad_r . ' - del excel = '. $med_loc .'- del SQL Cat= '. $categoria_r . ' - del excel - = '.$med_cat.'<br><br>';
                        }
                    }else
                    {
                        // print "nadaaaa<br><br>";
                    }
                }else
                {
                    $new_correlativo = @((int)search_correlativo($med_cod))+1;

                    $insert = $this->db->prepare("INSERT INTO tbl_maestro_medicos(medico_cmp, medico_correlativo,
                                                        medico_nombre, medico_especialidad, 
                                                        medico_categoria, medico_institucion, medico_direccion,
                                                        medico_localidad, medico_turno, medico_zona, medico_alta_baja, medico_ubigeo,
                                                        medico_representante, medico_supervisor)
                                                    VALUES(:med_cod, :med_cor, :med_nya, :med_esp, :med_cat, :med_inst,
                                                            :med_dir, :med_loc, :med_turno, :med_zona, :med_altbaja, :med_ubigeo,
                                                            :medico_representante, :medico_supervisor)");

                        $insert->bindParam(":med_cod", $med_cod);
                        $insert->bindParam(":med_cor", $new_correlativo);
                        $insert->bindParam(":med_nya", $med_nya);
                        $insert->bindParam(":med_esp", $med_esp);
                        $insert->bindParam(":med_cat", $med_cat);
                        $insert->bindParam(":med_inst", $med_inst);
                        $insert->bindParam(":med_dir", $med_dir);
                        $insert->bindParam(":med_loc", $med_loc);
                        $insert->bindParam(":med_turno", $med_turno);
                        $insert->bindParam(":med_zona", $med_zona);
                        $insert->bindParam(":med_altbaja", $med_altbaja);
                        $insert->bindParam(":med_ubigeo", $med_ubigeo);
                        $insert->bindParam(":medico_representante", $medico_representante);
                        $insert->bindParam(":medico_supervisor", $medico_supervisor);

                    if($insert->execute())
                    {
                        print $med_cod .'NO en zona-repre - '. $s++.'<br>';
                    }else
                    {
                        print $med_cod. "NO en zona-repre - error => " .errorPDO($insert) .'<br>';
                    } 
                }
            }else
            {
                $new_correlativo = @((int)search_correlativo($med_cod))+1;

                $insert = $this->db->prepare("INSERT INTO tbl_maestro_medicos(medico_cmp, medico_correlativo,
                                                        medico_nombre, medico_especialidad, 
                                                        medico_categoria, medico_institucion, medico_direccion,
                                                        medico_localidad, medico_turno, medico_zona, medico_alta_baja, medico_ubigeo,
                                                        medico_representante, medico_supervisor)
                                                    VALUES(:med_cod, :med_cor, :med_nya, :med_esp, :med_cat, :med_inst,
                                                            :med_dir, :med_loc, :med_turno, :med_zona, :med_altbaja, :med_ubigeo,
                                                            :medico_representante, :medico_supervisor)");

                        $insert->bindParam(":med_cod", $med_cod);
                        $insert->bindParam(":med_cor", $new_correlativo);
                        $insert->bindParam(":med_nya", $med_nya);
                        $insert->bindParam(":med_esp", $med_esp);
                        $insert->bindParam(":med_cat", $med_cat);
                        $insert->bindParam(":med_inst", $med_inst);
                        $insert->bindParam(":med_dir", $med_dir);
                        $insert->bindParam(":med_loc", $med_loc);
                        $insert->bindParam(":med_turno", $med_turno);
                        $insert->bindParam(":med_zona", $med_zona);
                        $insert->bindParam(":med_altbaja", $med_altbaja);
                        $insert->bindParam(":med_ubigeo", $med_ubigeo);
                        $insert->bindParam(":medico_representante", $medico_representante);
                        $insert->bindParam(":medico_supervisor", $medico_supervisor);

                if($insert->execute())
                {
                    print $med_cod .'no existe - '. $s++.'<br>';
                }else
                {
                    print $med_cod. "no existe - error => " .errorPDO($insert) .'<br>';
                } 
            }
        }        
    }
    public function load_medicos_x_repre($file)
    {
        $s = 0;
        $x = 0;

        $file_excel = $file;
                   
        $inputFileName = $file_excel;
        
        $objPHPExcel   = PHPExcel_IOFactory::load($inputFileName);
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        $cacheSettings = array('memoryCacheSize' => '4096MB');

        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings); 

        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) 
        {
            $worksheetTitle     = $worksheet->getTitle();
            $highestRow         = $worksheet->getHighestRow();
            $highestColumn      = $worksheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $nrColumns          = ord($highestColumn) - 64;
        }

        for ($row = 1; $row <= $highestRow; ++$row) 
        {
            $DataRow = array();
            
            for ($col = 0; $col < $highestColumnIndex; ++$col) 
            {
                $cell      = $worksheet->getCellByColumnAndRow($col, $row);
                $DataRow[] = $cell->getValue();
            }
            
            $med_cod = null;
            $med_cor = null;
            $med_turno = null;
            #$med_cod = (int)str_replace('77700', '', $DataRow[0]);
            $med_cod = (int)$DataRow[0];
            $med_cor = $DataRow[1];

            if($med_cor == null || strlen($med_cor) == 0)
            {
                $med_cor = 1;
            }
           
            if($med_cor == 1)
            {
                $med_turno = 'AM';
            }else
            {
                $med_turno = 'PM';
            }

            $med_nya = (string)$DataRow[2];
            $med_esp = (string)$DataRow[3];

            if(strpos($med_esp, ' ') !== FALSE)
            {
                $MEDICO_EXPLODE = explode(" ", $med_esp);
                $med_esp = $MEDICO_EXPLODE[0];
            }else
            {
                $med_esp = $med_esp;
            }
            
            $med_cat = (string)trim(strtoupper($DataRow[4]));
            $med_inst = (string)trim($DataRow[5]);
            $med_dir = (string)trim($DataRow[6]);
            $med_loc = (string)trim($DataRow[7]);
            $med_zona = (int)trim($DataRow[11]);
            $med_altbaja = (string)trim($DataRow[8]);
            $medico_representante = (int)trim($DataRow[9]);
            $medico_supervisor = (int)trim($DataRow[10]);
            $med_ubigeo = (int)trim($DataRow[12]);
            
            if(strlen($med_ubigeo) == 0 || $med_ubigeo == null || $med_ubigeo == 0)
            {
                $med_ubigeo = (int)_search_ubigeo($med_loc);
            }
            
            // print $med_cod."||".$med_cor."||".$med_nya."||".$med_esp."||".$med_cat."||".$med_inst."||".$med_dir."||".$med_loc."||".$med_turno."||".$med_zona."||".$med_altbaja."||".$medico_representante."||".$medico_supervisor."||".$med_ubigeo.'##########<br>';
            
            $data_medico = search_data_med($med_cod, $medico_representante);

            $id_r = $data_medico['id'];
            $correlativo_r = $data_medico['correlativo'];
            $zona_r = $data_medico['zona'];
            $categoria_r = $data_medico['categoria'];
            $localidad_r = $data_medico['localidad'];
            $alta_baja_r = $data_medico['alta_baja'];
            $turno_r = $data_medico['turno'];
            $ubigeo_r = $data_medico['ubigeo'];
            $representante_r = $data_medico['representante'];
            $existe = $data_medico['existe'];

            $new_correlativo = $med_cor;
            // $new_correlativo = @((int)search_correlativo($med_cod))+1;
            // print  'del sql A/B = '.$alta_baja_r . ' - del excel = ' . $med_altbaja . ' - del sql LOC = '. $localidad_r . ' - del excel = '. $med_loc .' -  del SQL UBI = '.$ubigeo_r . ' del excel = '. $med_ubigeo.'<br><br>';
            
            // print 'new corre = ' . $new_correlativo.'<br>';

            $insert = $this->db->prepare("INSERT INTO tbl_maestro_medicos(medico_cmp, medico_correlativo,
                                            medico_nombre, medico_especialidad, 
                                            medico_categoria, medico_institucion, medico_direccion,
                                            medico_localidad, medico_turno, medico_zona, medico_alta_baja, medico_ubigeo,
                                            medico_representante, medico_supervisor)
                                        VALUES(:med_cod, :med_cor, :med_nya, :med_esp, :med_cat, :med_inst,
                                                :med_dir, :med_loc, :med_turno, :med_zona, :med_altbaja, :med_ubigeo,
                                                :medico_representante, :medico_supervisor)");

            $insert->bindParam(":med_cod", $med_cod);
            $insert->bindParam(":med_cor", $new_correlativo);
            $insert->bindParam(":med_nya", $med_nya);
            $insert->bindParam(":med_esp", $med_esp);
            $insert->bindParam(":med_cat", $med_cat);
            $insert->bindParam(":med_inst", $med_inst);
            $insert->bindParam(":med_dir", $med_dir);
            $insert->bindParam(":med_loc", $med_loc);
            $insert->bindParam(":med_turno", $med_turno);
            $insert->bindParam(":med_zona", $med_zona);
            $insert->bindParam(":med_altbaja", $med_altbaja);
            $insert->bindParam(":med_ubigeo", $med_ubigeo);
            $insert->bindParam(":medico_representante", $medico_representante);
            $insert->bindParam(":medico_supervisor", $medico_supervisor);

            if($insert->execute())
            {
                print $med_cod.' - lenght: '.strlen($med_cod) .' DIFERENTE 3 OPCIONES - '. $s++.'<br>';
            }else
            {
                print errorPDO($insert).'<br>';
            }
        }        
    }
    public function listar_representantes($year, $month, $region)
    {
        $WHERE = null;
        
        if($region == 'T')
        {
            $WHERE = null;
        }else
        {
            $WHERE = " AND representante_region IN($region) ";
        }
        $WHERE = $WHERE;

        $output = null;

        try
        {
            $consulta = $this->db->prepare("SELECT 
                                                medpro_vendedor AS movimiento_vendedor, representante_nombre
                                            FROM
                                                tbl_medpro_detalle
                                                    INNER JOIN
                                                tbl_representantes ON representante_codigo = medpro_vendedor
                                            WHERE
                                                YEAR(medpro_fecha) = :year
                                                    AND MONTH(medpro_fecha) = :month
                                                    AND representante_codigo != 999
                                                    $WHERE
                                            GROUP BY 1
                                            ORDER BY 2 ASC;");
            $consulta->bindParam(":year", $year, PDO::PARAM_INT);
            $consulta->bindParam(":month", $month, PDO::PARAM_INT);
            if($consulta->execute())
            {
                if($consulta->rowCount() > 0)
                {
                    while ($rconsulta = $consulta->fetch(PDO::FETCH_ASSOC))
                    {
                        $output .= '<option value="'.$rconsulta['movimiento_vendedor'].'">'.$rconsulta['representante_nombre'].'</option>';
                    }
                }else
                {
                    $output = 0;
                }
            }else
            {
                $output = errorPDO($consulta);
            }
        }catch(PDOException $e)
        {
            $output = $e->getMessage();
        }
        return $output;
    }
    public function table_x_localidad($representantes)
    {
        $output = null;
        
        $ExecQuery = null;
        $ExecQuery2 = null;

        $Query_SQL1 = "SELECT DISTINCT
                            (medico_localidad) AS localidades
                        FROM
                            tbl_maestro_medicos
                        WHERE
                            medico_zona = (SELECT 
                                    representante_zonag
                                FROM
                                    tbl_representantes
                                WHERE
                                    representante_codigo = :representantes)
                            AND medico_representante = :representantes
                            AND medico_alta_baja != 'B'
                        GROUP BY medico_localidad
                        ORDER BY 1 ASC;";

        $Query_SQL2 = "SELECT DISTINCT
                            (medico_localidad) AS localidades
                        FROM
                            tbl_maestro_medicos
                        WHERE
                            medico_zona = (SELECT 
                                    representante_zonag
                                FROM
                                    tbl_representantes
                                WHERE
                                    representante_codigo = :representantes)
                            AND medico_alta_baja != 'B'
                            AND medico_representante IS NULL
                        GROUP BY medico_localidad
                        ORDER BY 1 ASC;";
                                
        $Query_SQL21 = "SELECT 
                            COUNT(medico_cmp) AS cantidad
                        FROM
                            tbl_maestro_medicos
                        WHERE
                            medico_categoria = :categorias
                                AND medico_localidad = :localidades
                                AND medico_zona = (SELECT 
                                    representante_zonag
                                FROM
                                    tbl_representantes
                                WHERE
                                    representante_codigo = :representantes)
                                AND medico_representante = :representantes
                                AND medico_alta_baja != 'B';";
                                
        $Query_SQL22 = "SELECT 
                            COUNT(medico_cmp) AS cantidad
                        FROM
                            tbl_maestro_medicos
                        WHERE
                            medico_categoria = :categorias
                                AND medico_localidad = :localidades
                                AND medico_zona = (SELECT 
                                    representante_zonag
                                FROM
                                    tbl_representantes
                                WHERE
                                    representante_codigo = :representantes)
                                AND medico_representante IS NULL
                                AND medico_alta_baja != 'B';";

        $user_existe_medico = user_existe_medico($representantes);

        if($user_existe_medico == 1)
        {
            $ExecQuery = $Query_SQL1;
            $ExecQuery2 = $Query_SQL21;
        }else
        {
            $ExecQuery = $Query_SQL2;
            $ExecQuery2 = $Query_SQL22;
        }

        try
        {
            $localidad_q = $this->db->prepare($ExecQuery);
            $localidad_q->bindParam(":representantes", $representantes, PDO::PARAM_INT);
            if($localidad_q->execute())
            {
                if($localidad_q->rowCount() > 0)
                {
                    $output .= '<table class="table table-striped table-sm table-bordered" id="table-x-localidad" style="width:100% !important;font-size:0.8em !important;">
                                                <thead style="background-color:#56A7A7;">
                                                    <th class="text-white text-center" style="border-radius: 10px 0px 0px 0px">Localidad</th>
                                                    <th class="text-white text-center">AA</th>
                                                    <th class="text-white text-center">A</th>
                                                    <th class="text-white text-center">B</th>
                                                    <th class="text-white text-center">C</th>
                                                    <th class="text-white text-center">Total</th>
                                                    <th class="text-white text-center" style="border-radius: 0px 10px 0px 0px">Contactos</th>
                                                </thead>
                                                <tbody class="text-center font-weight-bold">';
                    $categarray = array('AA','A','B','C');

                    $countcat = count($categarray);

                    while($localidad_r = $localidad_q->fetch(PDO::FETCH_ASSOC)) 
                    {
                        $total = 0;
                        $contactos = 0;

                        $localidades = $localidad_r['localidades'];
                        
                        $output .= '<tr><td class="text-left" style="font-size:0.8em;">'.$localidades.'</td>';
                        
                        for ($c = 0; $c <= $countcat-1; $c++)
                        { 
                            $categorias = $categarray[$c];
                            
                            $cantidad = $this->db->prepare($ExecQuery2);

                            $cantidad->bindParam(":categorias", $categorias, PDO::PARAM_STR);
                            $cantidad->bindParam(":localidades", $localidades, PDO::PARAM_STR);
                            $cantidad->bindParam(":representantes", $representantes, PDO::PARAM_INT);

                            if($cantidad->execute())
                            {
                                if($cantidad->rowCount() > 0)
                                {
                                    $cantidad_r = $cantidad->fetch(PDO::FETCH_ASSOC);
                                    
                                    $cant_loc_x_cat = $cantidad_r['cantidad'];
                                    
                                    $total += $cant_loc_x_cat;

                                    if($categorias == 'AA')
                                    {
                                        $contactos += ($cant_loc_x_cat*4);
                                    }else if($categorias == 'A')
                                    {
                                        $contactos += ($cant_loc_x_cat*3);
                                    }else if($categorias == 'B')
                                    {
                                        $contactos += ($cant_loc_x_cat*2);
                                    }else
                                    {
                                        $contactos += ($cant_loc_x_cat*1);
                                    }

                                    if($cant_loc_x_cat != 0)
                                    {
                                        $output .= '<td style="color:#21ABA5;cursor:pointer;" onclick="return listar_padron_medicos('."'".$representantes."'".','."'".$categorias."'".','."'".$localidades."'".','."'loc'".');">'.$cant_loc_x_cat.'</td>';
                                    }else
                                    {
                                        $output .= '<td>'.$cant_loc_x_cat.'</td>';
                                    }
                                    
                                }else
                                {
                                    $output .= '<td>0</td>';
                                }
                            }else
                            {
                                $output = errorPDO($cantidad);
                            }
                        }
                        // $output .= '<td>'.$total.'</td><td>'.$contactos.'</td></tr>';
                        if($total > 0)
                        {
                            $output .= '<td style="color:#21ABA5;cursor:pointer;" onclick="return listar_padron_medicos('."'".$representantes."'".','."'T'".','."'".$localidades."'".','."'loc'".');">'.$total.'</td><td>'.$contactos.'</td></tr>';
                        }else
                        {
                            $output .= '<td>'.$total.'</td><td>'.$contactos.'</td></tr>';
                        }
                    }
                    
                    $output .= '</tbody>
                                    <tfoot class="text-center">
                                        <th class="text-white text-center" style="background-color:#5782BB;">Total</th>
                                        <th class="text-dark text-center" style="background-color:#FFDF5A;" id="tfaa_l"></th>
                                        <th class="text-dark text-center" style="background-color:#FFDF5A;" id="tfa_l"></th>
                                        <th class="text-dark text-center" style="background-color:#FFDF5A;" id="tfb_l"></th>
                                        <th class="text-dark text-center" style="background-color:#FFDF5A;" id="tfc_l"></th>
                                        <th class="text-dark text-center" style="background-color:#FFDF5A;" id="tftt_l"></th>
                                        <th class="text-dark text-center" style="background-color:#FFDF5A;" id="tftc_l"></th>
                                    </tfoot>
                                </table>';

                    
                }else
                {
                    $output = 0;
                }
            }else
            {
                $output = errorPDO($localidad_q);
            }
        }catch(PDOException $e)
        {
            $output = $e->getMessage();
        }
        return $output;
    }
    public function table_x_especialidad($representantes)
    {
        $output = null;
        $ExecQuery = null;
        $ExecQuery2 = null;

        $Query_SQL1 = "SELECT DISTINCT
                            (medico_especialidad) AS especialidades
                        FROM
                            tbl_maestro_medicos
                        WHERE
                            medico_zona = (SELECT 
                                    representante_zonag
                                FROM
                                    tbl_representantes
                                WHERE
                                    representante_codigo = :representantes)
                            AND medico_representante = :representantes
                            AND medico_alta_baja != 'B'
                        GROUP BY medico_especialidad
                        ORDER BY 1 ASC;";

        $Query_SQL2 = "SELECT DISTINCT
                            (medico_especialidad) AS especialidades
                        FROM
                            tbl_maestro_medicos
                        WHERE
                            medico_zona = (SELECT 
                                    representante_zonag
                                FROM
                                    tbl_representantes
                                WHERE
                                    representante_codigo = :representantes)
                            AND medico_representante IS NULL
                            AND medico_alta_baja != 'B'
                        GROUP BY medico_especialidad
                        ORDER BY 1 ASC;";
                                
        $Query_SQL21 = "SELECT 
                            COUNT(medico_cmp) AS cantidad
                        FROM
                            tbl_maestro_medicos
                        WHERE
                            medico_categoria = :categorias
                                AND medico_especialidad = :especialidades
                                AND medico_zona = (SELECT 
                                    representante_zonag
                                FROM
                                    tbl_representantes
                                WHERE
                                    representante_codigo = :representantes)
                                AND medico_representante = :representantes
                                AND medico_alta_baja != 'B';";

        $Query_SQL22 = "SELECT 
                            COUNT(medico_cmp) AS cantidad
                        FROM
                            tbl_maestro_medicos
                        WHERE
                            medico_categoria = :categorias
                                AND medico_especialidad = :especialidades
                                AND medico_zona = (SELECT 
                                    representante_zonag
                                FROM
                                    tbl_representantes
                                WHERE
                                    representante_codigo = :representantes)
                                AND medico_representante IS NULL
                                AND medico_alta_baja != 'B';";

        $user_existe_medico = user_existe_medico($representantes);

        if($user_existe_medico == 1)
        {
            $ExecQuery = $Query_SQL1;
            $ExecQuery2 = $Query_SQL21;
        }else
        {
            $ExecQuery = $Query_SQL2;
            $ExecQuery2 = $Query_SQL22;
        }
        
        try
        {
            $especialidad_q = $this->db->prepare($ExecQuery);
            $especialidad_q->bindParam(":representantes", $representantes, PDO::PARAM_INT);
            if($especialidad_q->execute())
            {
                if($especialidad_q->rowCount() > 0)
                {
                    $output .= '<table class="table table-striped table-condensed table-bordered table-sm" id="table-x-especialidad" style="width:100% !important;font-size:0.85em !important;">
                                                <thead style="background-color:#56A7A7;font-size:0.9em;">
                                                    <th class="text-white text-center" style="border-radius: 10px 0px 0px 0px">Especialidad</th>
                                                    <th class="text-white text-center">AA</th>
                                                    <th class="text-white text-center">A</th>
                                                    <th class="text-white text-center">B</th>
                                                    <th class="text-white text-center">C</th>
                                                    <th class="text-white text-center">Total</th>
                                                    <th class="text-white text-center" style="border-radius: 0px 10px 0px 0px">Contactos</th>
                                                </thead>
                                                <tbody class="text-center font-weight-bold">';
                    $categarray = array(0 => 'AA', 1 => 'A', 2 => 'B', 3 => 'C');

                    $countcat = count($categarray);

                    while($especialidad_r = $especialidad_q->fetch(PDO::FETCH_ASSOC)) 
                    {
                        $total = 0;
                        $contactos = 0;

                        $especialidades = $especialidad_r['especialidades'];
                        
                        $output .= '<tr><td class="td_n_esp">'.$especialidades.'</td>';
                        
                        for ($c = 0; $c <= $countcat-1; $c++)
                        { 
                            $categorias = $categarray[$c];
                            
                            $cantidad = $this->db->prepare($ExecQuery2);

                            $cantidad->bindParam(":categorias", $categorias, PDO::PARAM_STR);
                            $cantidad->bindParam(":especialidades", $especialidades, PDO::PARAM_STR);
                            $cantidad->bindParam(":representantes", $representantes, PDO::PARAM_INT);

                            if($cantidad->execute())
                            {
                                if($cantidad->rowCount() > 0)
                                {
                                    $cantidad_r = $cantidad->fetch(PDO::FETCH_ASSOC);
                                    
                                    $cant_esp_x_cat = $cantidad_r['cantidad'];
                                    
                                    $total += $cant_esp_x_cat;

                                    if($categorias == 'AA')
                                    {
                                        $contactos += ($cant_esp_x_cat*4);
                                    }else if($categorias == 'A')
                                    {
                                        $contactos += ($cant_esp_x_cat*3);
                                    }else if($categorias == 'B')
                                    {
                                        $contactos += ($cant_esp_x_cat*2);
                                    }else
                                    {
                                        $contactos += ($cant_esp_x_cat*1);
                                    }

                                    if($cant_esp_x_cat != 0)
                                    {
                                        $output .= '<td style="color:#21ABA5;cursor:pointer;" onclick="return listar_padron_medicos('."'".$representantes."'".','."'".$categorias."'".','."'".$especialidades."'".','."'esp'".');">'.$cant_esp_x_cat.'</td>';
                                        #"onclick="cobertura_visitados('."'".$especialidades."'".','."'".$categorias."'".','."'".$representantes."'".','."'".$mes."'".','."'".$year."'".');"
                                    }else
                                    {
                                        $output .= '<td>'.$cant_esp_x_cat.'</td>';
                                    }                             
                                }else
                                {
                                    $output .= '<td>0</td>';
                                }
                            }else
                            {
                                $output = errorPDO($cantidad);
                            }
                        }
                        if($total > 0)
                        {
                            $output .= '<td style="color:#21ABA5;cursor:pointer;" onclick="return listar_padron_medicos('."'".$representantes."'".','."'T'".','."'".$especialidades."'".','."'esp'".');">'.$total.'</td><td>'.$contactos.'</td></tr>';
                        }else
                        {
                            $output .= '<td>'.$total.'</td><td>'.$contactos.'</td></tr>';
                        }
                    }
                    
                    $output .= '</tbody>
                                    <tfoot class="text-center">
                                        <th class="text-white text-center" style="background-color:#5782BB;">Total</th>
                                        <th class="text-dark text-center" style="background-color:#FFDF5A;" id="tfaa"></th>
                                        <th class="text-dark text-center" style="background-color:#FFDF5A;" id="tfa"></th>
                                        <th class="text-dark text-center" style="background-color:#FFDF5A;" id="tfb"></th>
                                        <th class="text-dark text-center" style="background-color:#FFDF5A;" id="tfc"></th>
                                        <th class="text-dark text-center" style="background-color:#FFDF5A;" id="tftt"></th>
                                        <th class="text-dark text-center" style="background-color:#FFDF5A;" id="tftc"></th>
                                    </tfoot>
                                </table>';
                }else
                {
                    $output = 0;
                }
            }else
            {
                $output = errorPDO($espcialidad_q);
            }
        }catch(PDOException $e)
        {
            $output = $e->getMessage();
        }
        return $output;
    }
    public function table_visitas_realizada($representantes, $mes, $year)#cobertura
    {
        $output = null;

        $Exec_ESP = null;
        $Exec_Realizado = null;
        $Exec_Pronostico = null;

        $user_existe_medico = user_existe_medico($representantes);

        $Query_ESP1 = "SELECT DISTINCT
                            (medico_especialidad) AS especialidades
                        FROM
                            tbl_maestro_medicos
                        WHERE
                            medico_zona = (SELECT 
                                    representante_zonag
                                FROM
                                    tbl_representantes
                                WHERE
                                    representante_codigo = :representantes)
                                AND medico_alta_baja != 'B'
                                AND medico_representante = :representantes
                        GROUP BY medico_especialidad
                        ORDER BY 1 ASC;";

        $Query_ESP2 = "SELECT DISTINCT
                            (medico_especialidad) AS especialidades
                        FROM
                            tbl_maestro_medicos
                        WHERE
                            medico_zona = (SELECT 
                                    representante_zonag
                                FROM
                                    tbl_representantes
                                WHERE
                                    representante_codigo = :representantes)
                                AND medico_alta_baja != 'B'
                                AND medico_representante IS NULL
                        GROUP BY medico_especialidad
                        ORDER BY 1 ASC;";

        $Query_REALIZADO0 = "SELECT 
                                COUNT(DISTINCT medpro_medico_cmp, medpro_fecha) AS realizado
                            FROM
                                tbl_medpro_detalle
                            WHERE
                                medpro_vendedor = :representantes
                                    AND YEAR(medpro_fecha) = :year
                                    AND MONTH(medpro_fecha) = :mes
                                    AND medpro_medico_esp = :especialidades
                                    AND medpro_medico_categoria = :categorias
                                    AND medpro_estado IN(1,2)";

        $Query_PRONOSTICO1 = "SELECT 
                                    COUNT(medico_cmp) AS pronostico
                                FROM
                                    tbl_maestro_medicos
                                WHERE
                                    medico_categoria = :categorias
                                        AND medico_especialidad = :especialidades
                                        AND medico_representante = :representantes;";
        $Query_PRONOSTICO2 = "SELECT 
                                    COUNT(medico_cmp) AS pronostico
                                FROM
                                    tbl_maestro_medicos
                                WHERE
                                    medico_categoria = :categorias
                                        AND medico_especialidad = :especialidades
                                        AND medico_zona = (SELECT 
                                                representante_zonag
                                            FROM
                                                tbl_representantes
                                            WHERE
                                                representante_codigo = :representantes
                                                AND medico_alta_baja = 'A')
                                        AND medico_representante IS NULL;";


        if($user_existe_medico == 1)
        {
            $Exec_ESP = $Query_ESP1;
            $Exec_Realizado = $Query_REALIZADO0;
            $Exec_Pronostico = $Query_PRONOSTICO1;
        }else
        {
            $Exec_ESP = $Query_ESP2;
            $Exec_Realizado = $Query_REALIZADO0;
            $Exec_Pronostico = $Query_PRONOSTICO2;
        }


        try
        {
            $especialidad_q = $this->db->prepare($Exec_ESP);
            $especialidad_q->bindParam(":representantes", $representantes, PDO::PARAM_INT);
            if($especialidad_q->execute())
            {
                if($especialidad_q->rowCount() > 0)
                {
                    $output .= '<div class="col-md-2" >
                        <div class="input-group form-group" >
                            <span class="input-group-addon bg-primary text-white">
                                <span class="">Cobertura: </span>
                            </span>
                            <span class="form-control text-center" style="border-color:#78BBE6;" id="avance-cobertura"></span>
                        </div>
                    </div>
                    <div class="col-md-12" >
                    <table id="tablecobertura" class="table table-bordered table-condensed table-striped" style="width:auto;">
                        <thead>
                            <th style="background-color:#95A792;" class="tablesmall2 text-center text-white">Especialidad</th>
                            <th style="background-color:#F1E290;" class="tablesmall2 text-center">AA</th>
                            <th style="background-color:#415B90;" class="tablesmall2 text-center text-white">AA</th>
                            <th style="background-color:#6088BB;" class="tablesmall2 text-center text-white">Cob(%)</th>
                            <th style="background-color:#F1E290;" class="tablesmall2 text-center">A</th>
                            <th style="background-color:#415B90;" class="tablesmall2 text-center text-white">A</th>
                            <th style="background-color:#6088BB;" class="tablesmall2 text-center text-white">Cob(%)</th>
                            <th style="background-color:#F1E290;" class="tablesmall2 text-center">B</th>
                            <th style="background-color:#415B90;" class="tablesmall2 text-center text-white">B</th>
                            <th style="background-color:#6088BB;" class="tablesmall2 text-center text-white">Cob(%)</th>
                            <th style="background-color:#F1E290;" class="tablesmall2 text-center">C</th>
                            <th style="background-color:#415B90;" class="tablesmall2 text-center text-white">C</th>
                            <th style="background-color:#6088BB;" class="tablesmall2 text-center text-white">Cob(%)</th>
                            <th style="background-color:#F1E290;" class="tablesmall2 text-center">Total</th>
                            <th style="background-color:#415B90;" class="tablesmall2 text-center text-white">Total</th>
                            <th style="background-color:#6088BB;" class="tablesmall2 text-center text-white">Cob(%)</th>
                            <th style="background-color:#F1E290;" class="tablesmall2 text-center">Pendiente</th>
                        </thead><tbody class="text-center" style="font-size:1em !important;">';

                    while($especialidad_r = $especialidad_q->fetch(PDO::FETCH_ASSOC))
                    {
                        $especialidades = $especialidad_r['especialidades'];

                        $categoriasArray = array(0 => 'AA', 1 => 'A', 2 => 'B', 3 => 'C');
                        
                        $mp = 0;
                        $tota1 = 0;
                        $totalpro = 0;
                        $totalvisi = 0;

                        $circle = null;

                        if($especialidades == '')
                        {
                            $output .= '<tr class=""><td>   </td>';
                        }else
                        {
                            $output .= '<tr class=""><td>'.$especialidades.'</td>';
                        }

                        for($i = 0; $i <= count($categoriasArray) - 1; $i++)
                        {
                            $categorias = $categoriasArray[$i];

                            $realizado_q = $this->db->prepare($Exec_Realizado);

                            $realizado_q->bindParam(":representantes", $representantes, PDO::PARAM_INT);
                            $realizado_q->bindParam(":mes", $mes, PDO::PARAM_INT);
                            $realizado_q->bindParam(":year", $year, PDO::PARAM_INT);
                            $realizado_q->bindParam(":categorias", $categorias, PDO::PARAM_STR);
                            $realizado_q->bindParam(":especialidades", $especialidades, PDO::PARAM_STR);
                        
                            $pronostico_q = $this->db->prepare($Exec_Pronostico);
                            $pronostico_q->bindParam(":categorias", $categorias, PDO::PARAM_STR);
                            $pronostico_q->bindParam(":especialidades", $especialidades, PDO::PARAM_STR);
                            $pronostico_q->bindParam(":representantes", $representantes, PDO::PARAM_INT);

                            if($realizado_q->execute() && $pronostico_q->execute())
                            {
                                if($categorias == 'AA'){$mp = 4;}elseif($categorias == 'A'){$mp = 3;}elseif($categorias == 'B'){$mp = 2;}elseif($categorias == 'C'){$mp = 1;}else{$mp = 0;}

                                $realizado_r = $realizado_q->fetch(PDO::FETCH_ASSOC);
                                $pronostico_r = $pronostico_q->fetch(PDO::FETCH_ASSOC);

                                $Realizado = $realizado_r['realizado'];
                                $Pronosticado = ($pronostico_r['pronostico'] * $mp);

                                if($Pronosticado == 0)
                                {
                                    $output .= '<td>'.$Pronosticado.'</td>';
                                }else
                                {
                                    $output .= '<td style="cursor:pointer;color:#2980B9 !important;" class="tablesmall2 font-weight" onclick="cobertura_no_visitados('."'".$especialidades."'".','."'".$categorias."'".','."'".$representantes."'".','."'".$mes."'".','."'".$year."'".');"><b>'.$Pronosticado.'</b></td>';
                                }
                                if($Realizado == 0)
                                {
                                    $output .= '<td>'.$Realizado.'</td>';
                                }else
                                {
                                    $output .= '<td style="cursor:pointer;color:#2980B9 !important;" class="tablesmall2 font-weight" onclick="cobertura_visitados('."'".$especialidades."'".','."'".$categorias."'".','."'".$representantes."'".','."'".$mes."'".','."'".$year."'".');"><b>'.$Realizado.'</b></td>';
                                }

                                @$total2 = round(($Realizado * 100)/$Pronosticado);

                                if($total2 >= 75)
                                {
                                    $circle = '<span style="color:#6088BB;"><i class="fa fa-circle"></i></span>';
                                }elseif($total2 >= 60)
                                {
                                    $circle = '<span style="color:#F1E290;"><i class="fa fa-circle"></i></span>';
                                }
                                else
                                {
                                    $circle = '<span style="color:#C70039;"><i class="fa fa-circle"></i></span>';
                                }

                                if($total2 == 0)
                                {
                                    $output .= '<td>'.$circle.'&nbsp;'.$total2.'%</td>';
                                }else
                                {
                                    if(is_nan($total2))
                                    {
                                        $total2 = 0;
                                    }else
                                    {
                                        $total2 = $total2;
                                    }
                                    $output .= '<td>'.$circle.'&nbsp;'.$total2.'%</td>';
                                }

                                $totalpro += $Pronosticado;
                                $totalvisi += $Realizado;
                            }
                        }
                            @$totalcob = round(($totalvisi * 100)/$totalpro);
                            if($totalcob >= 75)
                            {
                                $circle = '<span style="color:#6088BB;"><i class="fa fa-circle"></i></span>';
                            }elseif($totalcob >= 60)
                            {
                                $circle = '<span style="color:#F1E290;"><i class="fa fa-circle"></i></span>';
                            }else
                            {
                                $circle = '<span style="color:#C70039;"><i class="fa fa-circle"></i></span>';}

                            $output .= '<td class="tablesmall2">'.$totalpro.'</td>';
                            $output .= '<td class="tablesmall2">'.$totalvisi.'</td>';
                            $output .= '<td class="tablesmall2">'.$circle.'&nbsp;'.$totalcob.'%</td>';
                            $output .= '<td id="" class="tablesmall2">'.($totalpro - $totalvisi).'</td>';

                            $output .= '</tr>';
                    }
                }
                $output .= '</tbody>
                                <tfoot style="color:black;" class="text-center">
                                    <tr>
                                        <td style="background-color:#6088BB;" class="text-white tablesmall2">Total</td>
                                        <td id="tfooter1" style="background-color:#C2DBC1;"></td>
                                        <td id="tfooter2" style="background-color:#C2DBC1;"></td>
                                        <td id="tfooter3" style="background-color:#C2DBC1;"></td>
                                        <td id="tfooter4" style="background-color:#C2DBC1;"></td>
                                        <td id="tfooter5" style="background-color:#C2DBC1;"></td>
                                        <td id="tfooter6" style="background-color:#C2DBC1;"></td>
                                        <td id="tfooter7" style="background-color:#C2DBC1;"></td>
                                        <td id="tfooter8" style="background-color:#C2DBC1;"></td>
                                        <td id="tfooter9" style="background-color:#C2DBC1;"></td>
                                        <td id="tfooter10" style="background-color:#C2DBC1;"></td>
                                        <td id="tfooter11" style="background-color:#C2DBC1;"></td>
                                        <td id="tfooter12" style="background-color:#C2DBC1;"></td>
                                        <td id="tfooter13" class="text-white" style="background-color:#73B1C1;"></td>
                                        <td id="tfooter14" class="text-white" style="background-color:#DD3E3E;"></td>
                                        <td id="tfooter15" style="background-color:#C2DBC1;"></td>
                                        <td id="tfooter16" style="background-color:#C2DBC1;"></td>
                                    </tr>
                                </tfoot>
                            </table></div><div class="col-md-2"></div>';
            }else
            {
                $output = errorPDO($espcialidad_q);
            }   
        }catch(PDOException $e)
        {
            $output = $e->getMessage();
        }
        return $output;
    }
    public function cobertura_no_visitados($especialidades, $categorias, $representantes, $mes, $year)
    {
        $output = null;

        $Exec_Query1 = null;
        $Exec_Realizado = null;
        $Exec_Pronostico = null;

        $user_existe_medico = user_existe_medico($representantes);

        $qqq = "SELECT 
                medico_cmp AS med_cod,
                medico_nombre AS med_name,
                medico_direccion AS med_dir,
                medico_especialidad AS med_esp,
                medico_categoria AS med_cat,
                medico_localidad AS med_local
            FROM
                tbl_maestro_medicos
            WHERE
                medico_zona = (SELECT 
                        representante_zonag
                    FROM
                        tbl_representantes
                    WHERE
                        representante_codigo = :representantes)

                    AND medico_representante = :representantes
                    
                    AND medico_categoria = :categorias
                    AND medico_especialidad = :especialidades
                    AND medico_cmp NOT IN (SELECT DISTINCT
                        medpro_medico_cmp
                    FROM
                        tbl_medpro_detalle
                    WHERE
                        medpro_vendedor = :representantes
                            AND YEAR(medpro_fecha) = :year
                            AND MONTH(medpro_fecha) = :mes)
                    AND medico_alta_baja != 'B'
            GROUP BY 1
            ORDER BY 6 ASC;";
            
        $SQL_Query1 = "SELECT 
                            medico_cmp AS med_cod,
                            medico_nombre AS med_name,
                            medico_direccion AS med_dir,
                            medico_especialidad AS med_esp,
                            medico_categoria AS med_cat,
                            medico_localidad AS med_local
                        FROM
                            tbl_maestro_medicos
                        WHERE
                            medico_zona = (SELECT 
                                    representante_zonag
                                FROM
                                    tbl_representantes
                                WHERE
                                    representante_codigo = :representantes)
                            AND medico_representante = :representantes
                            AND medico_categoria = :categorias
                            AND medico_especialidad = :especialidades
                            AND medico_alta_baja != 'B'
                        GROUP BY 1
                        ORDER BY 6 ASC;";
        
        $SQL_Query2 = "SELECT 
                            medico_cmp AS med_cod,
                            medico_nombre AS med_name,
                            medico_direccion AS med_dir,
                            medico_especialidad AS med_esp,
                            medico_categoria AS med_cat,
                            medico_localidad AS med_local
                        FROM
                            tbl_maestro_medicos
                        WHERE
                            medico_zona = (SELECT 
                                    representante_zonag
                                FROM
                                    tbl_representantes
                                WHERE
                                    representante_codigo = :representantes)
                            AND medico_representante IS NULL
                            AND medico_categoria = :categorias
                            AND medico_especialidad = :especialidades
                            AND medico_alta_baja != 'B'
                        GROUP BY 1
                        ORDER BY 6 ASC;";

        if($user_existe_medico == 1)
        {
            $Exec_Query1 = $SQL_Query1;
        }else
        {
            $Exec_Query1 = $SQL_Query2;
        }
        
        try
        {
            $consulta = $this->db->prepare($Exec_Query1);

            $consulta->bindParam(":categorias", $categorias, PDO::PARAM_STR);
            $consulta->bindParam(":especialidades", $especialidades, PDO::PARAM_STR);
            $consulta->bindParam(":representantes", $representantes, PDO::PARAM_INT);
            // $consulta->bindParam(":mes", $mes, PDO::PARAM_INT);
            // $consulta->bindParam(":year", $year, PDO::PARAM_INT);
            if($consulta->execute())
            {
                if($consulta->rowCount() > 0)
                {
                    while ($consulta_r = $consulta->fetch(PDO::FETCH_ASSOC))
                    {
                        $med_cod = $consulta_r['med_cod'];
                        $med_name = $consulta_r['med_name'];
                        $med_dir = $consulta_r['med_dir'];
                        $med_esp = $consulta_r['med_esp'];
                        $med_cat = $consulta_r['med_cat'];
                        $med_local = $consulta_r['med_local'];

                        $cant_visitado =  _validate_medico_visitado($med_cod, $representantes, $mes, $year);
                        $visitado_ = null;

                        if($cant_visitado > 0)
                        {
                            $visitado_ = 'Visitado';
                        }else
                        {
                            $visitado_ = 'No visitado';
                        }
                        $output .= '<tr style="border-color:blue;">
                                    
                                    <td style="font-size:0.75em;" class="text-left">'.$med_name.'</td>
                                    <td style="font-size:0.75em;" class="text-center">'.$med_local.'</td>
                                    <td style="font-size:0.75em;" class="text-left">'.$med_dir.'</td>
                                    <td style="font-size:0.75em;" class="text-center">'.$visitado_.'</td>
                                  </tr>';
                                //   <td style="font-size:0.75em;" class="text-left">'.$med_cod.'</td>
                        /*<td style="font-size:0.75em;" class="text-center">'.$med_esp.'</td>
                        <td style="font-size:0.75em;" class="text-center">'.$med_cat.'</td>*/
                    }
                }else
                {
                    $output = '<tr>
                                    <td style="font-size:0.75em;" class="text-center">-</td>
                                    <td style="font-size:0.75em;" class="text-center">-</td>
                                    <td style="font-size:0.75em;" class="text-center">-</td>
                                    <td style="font-size:0.75em;" class="text-center">-</td>
                                </tr>';
                }
            }else
            {
                $output = errorPDO($consulta);
            }
        }catch(PDOException $e)
        {
            $output = $e->getMessage();
        }
        return $output;
    } 
    public function cobertura_visitados($especialidades, $categorias, $representantes, $mes, $year)
    {
        $output = null;

        try
        {
            $consulta = $this->db->prepare("SELECT 
                                                medpro_medico_cmp AS codigo_medico,
                                                medpro_medico_nombre AS nombre_medico,
                                                COUNT(DISTINCT medpro_medico_cmp, medpro_fecha) AS cantidad,
                                                GROUP_CONCAT(DISTINCT DAY(medpro_fecha) ORDER BY medpro_fecha ASC) AS fecha
                                            FROM
                                                tbl_medpro_detalle
                                            WHERE
                                                medpro_vendedor = :representantes
													AND medpro_medico_altabaja != 'B'
                                                    AND medpro_medico_esp = :especialidades
                                                    AND medpro_medico_categoria = :categorias
                                                    AND medpro_estado IN(1,2)
                                                    AND MONTH(medpro_fecha) = :mes
                                                    AND YEAR(medpro_fecha) = :year
                                            GROUP BY medpro_medico_nombre , medpro_medico_cmp
                                            ORDER BY 3 DESC");
            $consulta->bindParam(":especialidades", $especialidades, PDO::PARAM_STR);
            $consulta->bindParam(":categorias", $categorias, PDO::PARAM_STR);
            $consulta->bindParam(":representantes", $representantes, PDO::PARAM_INT);
            $consulta->bindParam(":mes", $mes, PDO::PARAM_INT);
            $consulta->bindParam(":year", $year, PDO::PARAM_INT);
            if($consulta->execute())
            {
                if($consulta->rowCount() > 0)
                {
                    while($consulta_r = $consulta->fetch(PDO::FETCH_ASSOC))
                    {
                        $codigo_medico__ = $consulta_r['codigo_medico'];
                        $medico = $consulta_r['nombre_medico'];
                        $visitas = $consulta_r['cantidad'];
                        $fechas_visitadas = explode(',', $consulta_r['fecha']);

                        $fechashref = '';

                        if(count($fechas_visitadas) > 0)
                        {
                            $dias = '';
                            for ($f = 0; $f <= count($fechas_visitadas) - 1; $f++)
                            {
                                $fechashref .= ' - <a href="javascript:void(0);" onclick="cobertura_fecha_productos('."'".$consulta_r['codigo_medico']."'".','."'".$fechas_visitadas[$f]."'".','."'".$mes."'".','."'".$year."'".','."'".$representantes."'".','."'".$consulta_r['nombre_medico']."'".');">'.$fechas_visitadas[$f].'</a>';
                            }
                        }else
                        {
                            $fechashref = '<a href="javascript:void(0);" onclick="cobertura_fecha_productos('."'".$consulta_r['codigo_medico']."'".','."'".$consulta_r['fecha']."'".','."'".$mes."'".','."'".$year."'".','."'".$representantes."'".','."'".$consulta_r['nombre_medico']."'".');">D&iacute;a'.$consulta_r['fecha'].'</a>';
                        }

                        $output .= '<tr>
                                        
                                        <td style="font-size:0.75em;" class="text-center">'.$medico.'</td>
                                        <td style="font-size:0.75em;" class="text-center">'.$visitas.'</td>
                                        <td style="font-size:0.75em;" class="text-center">Dias '.trim($fechashref, '-').'</td>';
                                        // <td style="font-size:0.75em;" class="text-center">'.$codigo_medico__.'</td>
                    }
                }else
                {
                    $output = errorPDO($consulta);
                }
            }else
            {
                $output = errorPDO($consulta);
            }
        }catch(PDOException $e)
        {
            $output = $e->getMessage();
        }
        return $output;
    } 
    public function cobertura_fecha_productos($codigo_medico, $dia, $mes, $year, $representantes)
    {
        $output = null;
         
        $f_core = $year.'-'.$mes.'-'.$dia;
        $f_complete = date('Y-m-d', strtotime($f_core));

        try
        {
            $consulta = $this->db->prepare("SELECT 
                                                medpro_descripcion_producto AS producto_nombre,
                                                medpro_cantidad AS cantidad
                                            FROM
                                                tbl_medpro_detalle
                                            WHERE
                                                medpro_fecha = :f_complete
                                                    AND medpro_medico_cmp = :codigo_medico
                                                    AND medpro_vendedor = :representantes
                                            ORDER BY 2 DESC;");
                                                    #AND movimiento_cantidad != 0                                            

            $consulta->bindParam(":f_complete", $f_complete, PDO::PARAM_STR);
            $consulta->bindParam(":codigo_medico", $codigo_medico, PDO::PARAM_STR);
            $consulta->bindParam(":representantes", $representantes, PDO::PARAM_INT);
            if($consulta->execute())
            {
                if($consulta->rowCount() > 0)
                {
                    while ($consulta_r = $consulta->fetch(PDO::FETCH_ASSOC))
                    {
                        $output .= '<tr>
                                        <td style="font-size:0.8em;" class="text-left">'.$consulta_r['producto_nombre'].'</td>
                                        <td style="font-size:0.8em;" class="text-center">'.$consulta_r['cantidad'].'</td>
                                    </tr>';
                    }
                }else
                {
                    $output .= '<tr>
                                    <td style="font-size:0.8em;" class="text-left"> - </td>
                                    <td style="font-size:0.8em;" class="text-center"> - </td>
                                </tr>';
                }
            }else
            {
                $output = errorPDO($consulta);
            }
        }catch(PDOException $e)
        {
            $output = $e->getMessage();
        }
        return $output;
    }
    public function table_realizadas_detalle($representantes, $mes, $year)
    {
        $sumadias = 0;
        $cob = 0;
        $Pendiente = 0;

        $days_x_month = cal_days_in_month(CAL_GREGORIAN, $mes , $year);

        try
        {
            $consulta = $this->db->prepare("SELECT 
                                                medico_cmp AS med_cod,
                                                medico_nombre AS med_name,
                                                medico_especialidad AS med_esp,
                                                medico_categoria AS med_cat,
                                                medico_localidad AS med_loc,
                                                medico_direccion AS med_dir
                                            FROM
                                                tbl_maestro_medicos
                                            WHERE
                                                medico_zona = (SELECT 
                                                        representante_zonag
                                                    FROM
                                                        tbl_representantes
                                                    WHERE
                                                        representante_codigo = :representantes)
                                                    AND medico_alta_baja != 'B';");

            $consulta->bindParam(":representantes", $representantes, PDO::PARAM_INT);
            if($consulta->execute())
            {
                if($consulta->rowCount() > 0)
                {
                    $output = '<table id="table-detalle-realizadas-data" class="table table-striped table-bordered table-condensed table-responsive" style="width:auto !important">
                                <thead style="color: white;background-color: #36404A;font-size:1em;">
                                <tr>
                                    <th style="color: white !important;border-radius: 5px 0px 0px 0px !important;font-size:0.7em;" class="text-center">Medico</th>
                                    <th style="color: white !important;font-size:0.7em;" class="text-center">Esp</th>
                                    <th style="color: white !important;font-size:0.7em;" class="text-center">Cat</th>
                                    <th style="color: white !important;font-size:0.7em;" class="text-center">Distrito</th>
                                    <th style="color: white !important;font-size:0.7em;" class="text-center">Direcci&oacute;n</th>
                                    <th style="color: white !important;font-size:0.7em;" class="text-center">Cont</th>';

                    for ($i=1; $i <= $days_x_month ; $i++)
                    {
                        $output .= '<th style="color: white !important;font-size:0.7em;" class="text-center">'.$i.'</th>';
                    }
                        $output .=' <th style="color: white !important;font-size:0.7em;" class="text-center">Total</th>
                                    <th style="color: white !important;font-size:0.7em;" class="text-center">Cob(%)</th>
                                    <th style="color: white !important;border-radius: 0px 5px 0px 0px !important;font-size:0.7em;" class="text-center">Pendiente</th>
                                    </tr>
                                    </thead><tbody style=" color:black;font-weight:bold;">';

                    while ($consulta_r = $consulta->fetch(PDO::FETCH_ASSOC))
                    {
                        $contactos = 0;
                        $sumadias = 0;

                        $med_cod = $consulta_r['med_cod'];
                        $med_name = $consulta_r['med_name'];
                        $med_esp = $consulta_r['med_esp'];
                        $med_cat = $consulta_r['med_cat'];
                        $med_loc = $consulta_r['med_loc'];
                        $med_dir = $consulta_r['med_dir'];
    
                        if($med_cat == 'AA' || $med_cat == ' ')
                        {
                            $contactos = 4;
                        }elseif($med_cat == 'A')
                        {
                            $contactos = 3;
                        }elseif($med_cat == 'B')
                        {
                            $contactos = 2;
                        }else
                        {
                            $contactos = 1;
                        }
    
                        $output .= '<tr>
                                    <td style="font-size:0.65em;color:black;" class="text-left">'.$med_name.'</td>
                                    <td style="font-size:0.65em;color:black;" class="text-left">'.$med_esp.'</td>
                                    <td style="font-size:0.65em;color:black;" class="text-left">'.$med_cat.'</td>
                                    <td style="font-size:0.65em;color:black;" class="text-left">'.$med_loc.'</td>
                                    <td style="font-size:0.65em;color:black;" class="text-left">'.$med_dir.'</td>
                                    <td style="font-size:0.65em;color:black;" class="text-left">'.$contactos.'</td>';
                        
                        for ($f=1; $f <= $days_x_month ; $f++)
                        {
                            $f_core = $year.'-'.$mes.'-'.$f;

                            $f_complete = date('Y-m-d', strtotime($f_core));
                            
                            $visitasxdia = $this->db->prepare("SELECT 
                                                                    COUNT(DISTINCT medpro_medico_cmp) AS cantidad
                                                                FROM
                                                                    tbl_medpro_detalle
                                                                WHERE
                                                                    medpro_vendedor = :representantes
                                                                        AND medpro_medico_cmp = :med_cod
                                                                        AND medpro_fecha = :f_complete;");
                            $visitasxdia->bindParam(":representantes", $representantes, PDO::PARAM_INT);
                            $visitasxdia->bindParam(":med_cod", $med_cod, PDO::PARAM_INT);
                            $visitasxdia->bindParam(":f_complete", $f_complete, PDO::PARAM_STR);

                            if($visitasxdia->execute())
                            {
                                while ( $visitasxdia_r = $visitasxdia->fetch(PDO::FETCH_ASSOC)  )
                                {
                                    if($visitasxdia_r['cantidad'] == 0)
                                    {
                                        $output .= '<td style="font-size:0.75em;color:black;">'.$visitasxdia_r['cantidad'].'</td>';
                                    }else
                                    {
                                        $output .= '<td  class="text-white" style="font-size:0.65em;background-color:#5EA3A6;font-weight:bold;cursor:pointer;" onclick="cobertura_fecha_productos('."'".$med_cod."'".','."'".$f."'".','."'".$mes."'".','."'".$year."'".','."'".$representantes."'".','."'".$med_name."'".');">'.$visitasxdia_r['cantidad'].'</td>';
                                        #cobertura_fecha_productos('."'".$med_cod."'".','."'".$f."'".','."'".$mes."'".','."'".$year."'".','."'".$representantes."'".','."'".$consulta_r['nombre_medico']."'".');
                                    }

                                    $sumadias += (int)$visitasxdia_r['cantidad'];
                                }
                            }else
                            {
                                $output = errorPDO($visitasxdia);
                            }
                        }

                        $cob = round(($sumadias * 100)/($contactos));
                        $Pendiente = $contactos - $sumadias;

                        if($cob >= 75)
                        {
                            $circle = '<span style="color:#6088BB;"><i class="fa fa-circle"></i></span>';
                        }elseif($cob == 0)
                        {
                            $circle = '';
                        }elseif($cob >= 60)
                        {
                            $circle = '<span style="color:#F1E290;"><i class="fa fa-circle"></i></span>';
                        }else
                        {
                            $circle = '<span style="color:#C70039;"><i class="fa fa-circle"></i></span>';
                        }

                        $output .= '<td style="font-size:0.75em;color:black;">'.$sumadias.'</td>
                                    <td><span style="font-size:0.6em;" >'.$circle.'</span>&nbsp;<span style="font-size:0.65em;font-wieght:bold;color:black;">'.$cob.'</span><span style="font-wieght:bold;font-size:0.55em;color:black;">%</span></td>
                                    <td style="font-size:0.75em;color:black;">'.$Pendiente.'</td>
                                </tr>';
                    }
                    $output .= '</tbody></table>';
                }else
                {
                    $output = 0;
                }
            }else
            {
                $output = errorPDO($consulta);
            }
        }catch(PDOException $e)
        {
            $output = $e->getMessage();
        }
        return $output;
    }
    public function table_medicos_x_dia($representantes, $mes, $year)
    {
        $sumadias = 0;
        $cob = 0;
        $Pendiente = 0;

        $days_x_month = cal_days_in_month(CAL_GREGORIAN, $mes , $year);

        try
        {
            $output = '<table id="table_medicos_x_dia" class="table table-striped table-bordered table-condensed table-responsive" style="width:auto !important">
                                <thead style="color: white;background-color: #36404A;font-size:1em;">
                                <tr>
                                    <th style="color: white !important;border-radius: 5px 0px 0px 0px !important;font-size:0.7em;" class="text-center">Medico</th>';

            for ($i=1; $i <= $days_x_month ; $i++)
            {
                $output .= '<th style="color: white !important;font-size:0.7em;" class="text-center">'.$i.'</th>';
            }
                $output .=' <th style="color: white !important;border-radius: 0px 5px 0px 0px !important;font-size:0.7em;" class="text-center">total</th>
                            </tr>
                            </thead><tbody style=" color:black;font-weight:bold;">';

                            $output .= '<td style="font-size:0.75em;color:black;">Medicos</td>';
            for ($f=1; $f <= $days_x_month ; $f++)
            {
                $f_core = $year.'-'.$mes.'-'.$f;
    
                $f_complete = date('Y-m-d', strtotime($f_core));
                                
                $visitasxdia = $this->db->prepare("SELECT 
                                                        COUNT(DISTINCT medpro_medico_cmp) AS cantidad
                                                    FROM
                                                        tbl_medpro_detalle
                                                    WHERE
                                                        medpro_vendedor = :representantes
                                                            AND medpro_fecha = :f_complete ");
                                                            #AND movimiento_cantidad != 0
                $visitasxdia->bindParam(":representantes", $representantes, PDO::PARAM_INT);
                $visitasxdia->bindParam(":f_complete", $f_complete, PDO::PARAM_STR);
                
                if($visitasxdia->execute())
                {
                    
                    while ( $visitasxdia_r = $visitasxdia->fetch(PDO::FETCH_ASSOC)  )
                    {
                        
                        if($visitasxdia_r['cantidad'] == 0)
                        {
                            $output .= '<td style="font-size:0.75em;color:black;">'.$visitasxdia_r['cantidad'].'</td>';
                        }else
                        {
                            $output .= '<td  class="text-white" style="font-size:0.65em;background-color:#5EA3A6;font-weight:bold;cursor:pointer;" onclick="cobertura_visitados_detalle('."'".$representantes."'".','."'".$f_complete."'".');">'.$visitasxdia_r['cantidad'].'</td>';
                                            #cobertura_fecha_productos('."'".$med_cod."'".','."'".$f."'".','."'".$mes."'".','."'".$year."'".','."'".$representantes."'".','."'".$consulta_r['nombre_medico']."'".');
                        }
    
                        $sumadias += (int)$visitasxdia_r['cantidad'];
                    }
                }else
                {
                    $output = errorPDO($visitasxdia);
                }
            }
    
                $cob = '-';

                $output .= '<td style="font-size:0.75em;color:black;">'.$sumadias.'</td></tr>';


                $output .= '</tbody></table>';
                
        }catch(PDOException $e)
        {
            $output = $e->getMessage();
        }
        return $output;
    }
    public function table_medicos_x_dia_2($region, $mes, $year)
    {
        $sumadias = 0;
        $cob = 0;
        $Pendiente = 0;

        $where = null;

        if($region != 'T' && $region != 8)
        {
            $where = " representante_region = $region AND ";
        }else if($region == 8)
        {
            $where = " representante_region IN(8,28) AND ";
        }

        $days_x_month = cal_days_in_month(CAL_GREGORIAN, $mes , $year);

        

        $qry_repre = $this->db->prepare("SELECT 
                                            representante_codigo AS codigo,
                                            representante_nombre AS nombre,
                                            representante_portafolio AS portafolio,
                                            representante_zonag AS zonag
                                        FROM
                                            tbl_representantes
                                        WHERE
                                        $where 
                                            representante_codigo != 999
                                                AND representante_cargo IN (6)
                                                AND representante_estado = 'A'
                                        ORDER BY representante_portafolio , representante_zonag;");
        $qry_repre->bindParam(":region", $region);
        if($qry_repre->execute())
        {
            if($qry_repre->rowCount() > 0)
            {
                $output = '<table id="table_medicos_x_dia" class="table table-striped table-bordered table-condensed table-responsive table-sm" style="width:auto !important;font-size:0.9em;font-weight: regular;font-family: Montserrat, sans-serif;">
                            <thead style="color: white;background-color: #36404A;font-size:1em;">
                            <tr>
                                <th style="color: white !important;border-radius: 5px 0px 0px 0px !important;font-size:0.9em;" class="text-center">Repre.</th>';

                for ($i=1; $i <= $days_x_month ; $i++)
                {
                    $create_date = $year.'-'.$mes.'-'.$i;
                    // $output .= '<th style="color: white !important;font-size:0.7em;" class="text-center">'.$i.'</th>';
                    if(date('w',strtotime($create_date)) == 0 )
                    {
                        $output .= '<th style="color: white !important;font-size:0.7em;background-color:red !important;" class="text-center">'.$i.'</th>';
                    }else
                    {
                        $output .= '<th style="color: white !important;font-size:0.7em;" class="text-center">'.$i.'</th>';
                    } 
                }
                    $output .=' 
                    <th style="color: white !important;font-size:0.7em;" class="text-center">N° Cont.</th>
                    <th style="color: white !important;font-size:0.7em;" class="text-center">Cont. Realiz.</th>
                    <th style="color: white !important;font-size:0.7em;" class="text-center">Cont. Restante</th>
                    <th style="color: white !important;font-size:0.7em;" class="text-center">N° Med.</th>
                    <th style="color: white !important;font-size:0.7em;" class="text-center">Med. Visita.</th>
                    <th style="color: white !important;font-size:0.7em;" class="text-center">Med. Rest.s</th>
                                </tr>
                                </thead>
                                <tbody style="color:black;font-weight:bold;font-size:1.2em !important;">';
                while($res_rep = $qry_repre->fetch(PDO::FETCH_ASSOC))
                {
                    $sumadias = 0;
                    $total_restante_client = 0;
                    $codigo = $res_rep['codigo'];
                    $nombre = $res_rep['nombre'];
                    $portafolio = $res_rep['portafolio'];
                    $zonag = $res_rep['zonag'];

                    $pronosticoAA = pronostico_categoria($codigo, 'AA');
                    $pronosticoA = pronostico_categoria($codigo, 'A');
                    $pronosticoB = pronostico_categoria($codigo, 'B');
                    $pronosticoC = pronostico_categoria($codigo, 'C');

                    $total_pro = ($pronosticoAA+$pronosticoA+$pronosticoB+$pronosticoC);

                    $total_cart_med = $this->__cantidad_padron_medicos__($codigo);
                    $total_med_vis = $this->__cantidad_medico_visitados__($codigo, $year, $mes);
                    $total_restante_client = $total_cart_med-$total_med_vis;

                    #$nombre_ex = explode(" ", $nombre);

                    #@$new_name = $nombre_ex[0].' '.$nombre_ex[1];
                    $new_name = nombre_corto($codigo);

                    $output .= '<tr>
                                <td style="font-size:0.7em !important;color:black;" class="text-left">'.$new_name.'</td>';

                    for ($f=1; $f <= $days_x_month ; $f++)
                    {
                        $f_core = $year.'-'.$mes.'-'.$f;
            
                        $f_complete = date('Y-m-d', strtotime($f_core));
                                        
                        $visitasxdia = $this->db->prepare("SELECT 
                                                                COUNT(DISTINCT medpro_medico_cmp) AS cantidad
                                                            FROM
                                                                tbl_medpro_detalle
                                                            WHERE
                                                                medpro_vendedor = :codigo
                                                                    AND medpro_fecha = :f_complete ");
                                                                    #AND movimiento_cantidad != 0
                        $visitasxdia->bindParam(":codigo", $codigo);
                        $visitasxdia->bindParam(":f_complete", $f_complete);
                        
                        if($visitasxdia->execute())
                        {
                            while ( $visitasxdia_r = $visitasxdia->fetch(PDO::FETCH_ASSOC)  )
                            {
                                
                                if($visitasxdia_r['cantidad'] == 0)
                                {
                                    $output .= '<td style="font-size:0.75em;color:black;">'.$visitasxdia_r['cantidad'].'</td>';
                                }else
                                {
                                    $output .= '<td  class="text-white" style="font-size:0.65em;background-color:#5EA3A6;font-weight:bold;cursor:pointer;" onclick="cobertura_visitados_detalle('."'".$codigo."'".','."'".$f_complete."'".');">'.$visitasxdia_r['cantidad'].'</td>';
                                                    #cobertura_fecha_productos('."'".$med_cod."'".','."'".$f."'".','."'".$mes."'".','."'".$year."'".','."'".$representantes."'".','."'".$consulta_r['nombre_medico']."'".');
                                }
                                $sumadias += (int)$visitasxdia_r['cantidad'];
                            }
                        }else
                        {
                            $output = errorPDO($visitasxdia);
                        }
                    }
                    
                    
                    


                    $total_res = @($total_pro - $sumadias);

                    $output .= '<td style="font-size:0.7em;color:black;" class="text-left">'.$total_pro.'</td>
                                        <td style="font-size:0.7em;color:black;">'.$sumadias.'</td>
                                        <td style="font-size:0.7em;color:black;" class="text-left">'.$total_res.'</td>
                                        <td style="font-size:0.7em;color:black;" class="text-left">'.$total_cart_med.'</td>
                                        <td style="font-size:0.7em;color:black;">'.$total_med_vis.'</td>
                                        <td style="font-size:0.7em;color:black;" class="text-left">'.$total_restante_client.'</td>
                                    </tr>';

                    
                }
                $output .= '</tbody></table>';
            }
        }else
        {

        }


        return $output;
    }
    public function table_medicos_x_dia_total($mes, $year)
    {
        $sumadias = 0;
        $cob = 0;
        $Pendiente = 0;

        $days_x_month = cal_days_in_month(CAL_GREGORIAN, $mes , $year);

        try
        {
            $output = '<table id="table_medicos_x_dia" class="table table-striped table-bordered table-condensed table-responsive" style="width:auto !important">
                                <thead style="color: white;background-color: #36404A;font-size:1em;">
                                <tr>
                                    <th style="color: white !important;border-radius: 5px 0px 0px 0px !important;font-size:0.7em;" class="text-center">Medico</th>';

            for ($i=1; $i <= $days_x_month ; $i++)
            {
                $output .= '<th style="color: white !important;font-size:0.7em;" class="text-center">'.$i.'</th>';
            }
                $output .=' <th style="color: white !important;border-radius: 0px 5px 0px 0px !important;font-size:0.7em;" class="text-center">total</th>
                            </tr>
                            </thead><tbody style=" color:black;font-weight:bold;">';

                            $output .= '<td style="font-size:0.75em;color:black;">Medicos</td>';
            for ($f=1; $f <= $days_x_month ; $f++)
            {
                $f_core = $year.'-'.$mes.'-'.$f;
    
                $f_complete = date('Y-m-d', strtotime($f_core));
                
                
                $visitasxdia = $this->db->prepare("SELECT 
                                                        COUNT(DISTINCT medpro_medico_cmp) AS cantidad
                                                    FROM
                                                        tbl_medpro_detalle
                                                    WHERE
                                                        medpro_vendedor = :representantes
                                                            AND medpro_fecha = :f_complete");
                                                            #AND movimiento_cantidad != 0
                $visitasxdia->bindParam(":representantes", $representantes, PDO::PARAM_INT);
                $visitasxdia->bindParam(":f_complete", $f_complete, PDO::PARAM_STR);
                
                if($visitasxdia->execute())
                {
                    
                    while ( $visitasxdia_r = $visitasxdia->fetch(PDO::FETCH_ASSOC)  )
                    {
                        
                        if($visitasxdia_r['cantidad'] == 0)
                        {
                            $output .= '<td style="font-size:0.75em;color:black;">'.$visitasxdia_r['cantidad'].'</td>';
                        }else
                        {
                            $output .= '<td  class="text-white" style="font-size:0.65em;background-color:#5EA3A6;font-weight:bold;cursor:pointer;" onclick="cobertura_visitados_detalle('."'".$representantes."'".','."'".$f_complete."'".');">'.$visitasxdia_r['cantidad'].'</td>';
                                            #cobertura_fecha_productos('."'".$med_cod."'".','."'".$f."'".','."'".$mes."'".','."'".$year."'".','."'".$representantes."'".','."'".$consulta_r['nombre_medico']."'".');
                        }
    
                        $sumadias += (int)$visitasxdia_r['cantidad'];
                    }
                }else
                {
                    $output = errorPDO($visitasxdia);
                }
            }
    
                $cob = '{data}';

                $output .= '<td style="font-size:0.75em;color:black;">'.$sumadias.'</td></tr>';


                $output .= '</tbody></table>';
                
        }catch(PDOException $e)
        {
            $output = $e->getMessage();
        }
        return $output;
    }
    public function cobertura_visitados_detalle($representantes, $fecha)
    {
        $output = null;

        $explode_fecha = explode("-", $fecha);

        $dia = $explode_fecha[2];
        $mes = $explode_fecha[1];
        $year = $explode_fecha[0];

        try
        {
            $consulta = $this->db->prepare("SELECT 
                                                medpro_medico_cmp AS med_cod,
                                                medpro_medico_nombre AS med_nom,
                                                medpro_medico_esp AS med_esp,
                                                medpro_medico_categoria AS med_cat,
                                                medpro_medico_direccion AS med_dir,
                                                medpro_medico_localidad AS med_local
                                            FROM
                                                tbl_medpro_detalle
                                            WHERE
                                                medpro_vendedor = :representantes
                                                    AND medpro_fecha = :fecha
                                            GROUP BY 1
                                            ORDER BY 6 ASC;");
            $consulta->bindParam(":representantes", $representantes);
            $consulta->bindParam(":fecha", $fecha);
            if($consulta->execute())
            {
                if($consulta->rowCount() > 0)
                {
                    $output .= '<table class="table table-striped table-condensed table-sm table-bordered">
                                <thead style="background-color:#3E615B;" class="text-white ">
                                    <th class="text-center">Medico</th>
                                    <th class="text-center">Especialidad</th>
                                    <th class="text-center">Categoria</th>
                                    <th class="text-center">Direccion</th>
                                    <th class="text-center">Distrito</th>
                                    <th class="text-center">Detalle</th>
                                </thead>
                                <tbody>';
                    while($consulta_r = $consulta->fetch(PDO::FETCH_ASSOC))
                    {
                        $med_cod = $consulta_r['med_cod'];
                        $med_nom = $consulta_r['med_nom'];
                        $med_esp = $consulta_r['med_esp'];
                        $med_cat = $consulta_r['med_cat'];
                        $med_dir = $consulta_r['med_dir'];
                        $med_local = $consulta_r['med_local'];

                        $output .= '<tr>
                                        <td style="font-size:0.75em;" class="text-left">'.$med_nom.'</td>
                                        <td style="font-size:0.75em;" class="text-center">'.$med_esp.'</td>
                                        <td style="font-size:0.75em;" class="text-center">'.$med_cat.'</td>
                                        <td style="font-size:0.75em;" class="text-left">'.$med_dir.'</td>
                                        <td style="font-size:0.75em;" class="text-center">'.$med_local.'</td>
                                        <td>
                                            <button type="button" class="btn btn-primary waves-effect waves-light" onclick="cobertura_fecha_productos('."'".$med_cod."'".','."'".$dia."'".','."'".$mes."'".','."'".$year."'".','."'".$representantes."'".','."'".$med_nom."'".');"><span class="fa fa-list-alt"></span>&nbsp;Ver</button>
                                        </td>
                                    </tr>';
                                    
                        // if(count($fechas_visitadas) > 0)
                        // {
                        //     $dias = '';
                        //     for ($f = 0; $f <= count($fechas_visitadas) - 1; $f++)
                        //     {
                        //         $fechashref .= ' - <a href="javascript:void(0);" onclick="cobertura_fecha_productos('."'".$consulta_r['codigo_medico']."'".','."'".$fechas_visitadas[$f]."'".','."'".$mes."'".','."'".$year."'".','."'".$representantes."'".','."'".$consulta_r['nombre_medico']."'".');">'.$fechas_visitadas[$f].'</a>';
                        //     }
                        // }else
                        // {
                        //     $fechashref = '<a href="javascript:void(0);" onclick="cobertura_fecha_productos('."'".$consulta_r['codigo_medico']."'".','."'".$consulta_r['fecha']."'".','."'".$mes."'".','."'".$year."'".','."'".$representantes."'".','."'".$consulta_r['nombre_medico']."'".');">D&iacute;a'.$consulta_r['fecha'].'</a>';
                        // }

                        // $output .= '<tr><td style="font-size:0.75em;" class="text-center">'.$medico.'</td>
                        //                 <td style="font-size:0.75em;" class="text-center">'.$visitas.'</td>
                        //                 <td style="font-size:0.75em;" class="text-center">Dias '.trim($fechashref, '-').'</td>';
                    }
                    $output .= '</tbody></table>';
                }else
                {
                    $output = errorPDO($consulta);
                }
            }else
            {
                $output = errorPDO($consulta);
            }
        }catch(PDOException $e)
        {
            $output = $e->getMessage();
        }
        return $output;
    }
    public function table_stock_count($representantes, $mes, $year)
    {
        $sumadias = 0;
        $periodo = (int)$year.$mes;
        #$periodo = $periodo+1;
        $stock_actual = 0;

        $output = null;

        $days_x_month = cal_days_in_month(CAL_GREGORIAN, $mes , $year);

        try
        {
            $productos = $this->db->prepare("SELECT 
                                                stock_codigo_producto AS prod_cod,
                                                stock_descripcion_producto AS prod_desc,
                                                stock_cantidad AS stock_cantidad
                                            FROM
                                                tbl_stock
                                            WHERE
                                                stock_codigo_vendedor = :representantes
                                                    AND stock_periodo = :periodo");
            $productos->bindParam(":representantes",$representantes, PDO::PARAM_INT); 
            $productos->bindParam(":periodo",$periodo, PDO::PARAM_INT);
            if($productos->execute())
            {
                if($productos->rowCount() > 0)
                {
                    $output = '<table id="table-stock-productos" class="table table-striped table-bordered table-condensed table-responsive display table-sm" style="width:auto !important;font-family:verdana !important;">
                                <thead style="color: white;background-color: #36404A;font-size:1em;">
                                <tr>
                                    <th style="color: white !important;border-radius: 5px 0px 0px 0px !important;font-size:0.7em;" class="text-center">Codigo producto</th>
                                    <th style="color: white !important;font-size:0.7em;" class="text-center">Descripción producto</th>
                                    <th style="color: white !important;font-size:0.7em;" class="text-center">Stock inicial</th>';

                    for ($i=1; $i <= $days_x_month ; $i++)
                    {
                        $output .= '<th style="color: white !important;font-size:0.7em;" class="text-center">'.$i.'</th>';
                    }
                        $output .=' <th style="color: white !important;font-size:0.7em;" class="text-center">Stock actual</th>
                                    <th style="color: white !important;border-radius: 0px 5px 0px 0px !important;font-size:0.7em;" class="text-center this_order">Entregados</th>
                                    </tr>
                                    </thead><tbody style=" color:black;font-weight:bold;">';

                    while ($productos_r = $productos->fetch(PDO::FETCH_ASSOC))
                    {
                        
                        $prod_cod = $productos_r['prod_cod'];
                        $prod_desc = $productos_r['prod_desc'];
                        $stock_cantidad = $productos_r['stock_cantidad'];

                        $output .= '<tr>
                                    <td style="font-size:0.65em;color:black;" class="text-left">'.$prod_cod.'</td>
                                    <td style="font-size:0.65em;color:black;" class="text-left">'.$prod_desc.'</td>
                                    <td style="font-size:0.65em;color:black;" class="text-left">'.$stock_cantidad.'</td>';
                        
                        $sumadias = 0;

                        for ($f=1; $f <= $days_x_month ; $f++)
                        {
                            $f_core = $year.'-'.$mes.'-'.$f;

                            $f_complete = date('Y-m-d', strtotime($f_core));
                            
                            $prod_x_dia = $this->db->prepare("SELECT 
                                                                    SUM(medpro_cantidad) AS cantidad
                                                                FROM
                                                                    tbl_medpro_detalle
                                                                WHERE
                                                                    medpro_vendedor = :representantes
                                                                    AND medpro_producto = :prod_cod
                                                                        AND medpro_fecha = :f_complete;");
                            $prod_x_dia->bindParam(":representantes", $representantes, PDO::PARAM_INT);
                            $prod_x_dia->bindParam(":prod_cod", $prod_cod, PDO::PARAM_INT);
                            $prod_x_dia->bindParam(":f_complete", $f_complete, PDO::PARAM_STR);

                            if($prod_x_dia->execute())
                            {
                                while ( $prod_x_dia_r = $prod_x_dia->fetch(PDO::FETCH_ASSOC)  )
                                {
                                    if($prod_x_dia_r['cantidad'] == 0)
                                    {
                                        $output .= '<td style="font-size:0.75em;color:black;">0</td>';
                                    }else
                                    {
                                        $output .= '<td class="text-white" style="font-size:0.65em;background-color:#5EA3A6;font-weight:bold;cursor:pointer;" onclick="return view_medicos_x_producto('."'".$representantes."'".','."'".$f_complete."'".','."'".$prod_cod."'".','."'".$prod_desc."'".');">'.$prod_x_dia_r['cantidad'].'</td>';
                                        #cobertura_fecha_productos('."'".$med_cod."'".','."'".$f."'".','."'".$mes."'".','."'".$year."'".','."'".$representantes."'".','."'".$consulta_r['nombre_medico']."'".');
                                    }

                                    $sumadias += (int)$prod_x_dia_r['cantidad'];
                                }
                            }else
                            {
                                $output = errorPDO($prod_x_dia);
                            }
                        }

                        #$Entregados = $sumadias;
                        $Entregados = _stock_actual_x_prod($representantes, $prod_cod, $periodo);
                        $stock_actual = $stock_cantidad - $Entregados;
                        

                        $output .= '<td style="font-size:0.75em;color:black;">'.$stock_actual.'</td>
                                    <td style="font-size:0.75em;color:black;">'.$Entregados.'</td>
                                </tr>';
                    }
                    $output .= '</tbody></table>';
                }else
                {
                    $output = $representantes.' - productos-vacios'.$periodo;
                }
            }else
            {
                $output = errorPDO($productos);
            }
        }catch(PDOException $e)
        {
            $output = $e->getMessahe();
        }

        return $output;
    }
    public function stock_entregado_resumen($representantes, $mes, $year)
    {
        $output = null;
        $periodo = 0;
        $periodo = $year.$mes;

        $query1 = $this->db->prepare("SELECT 
                                            stock_codigo_producto AS cod_prod,
                                            stock_descripcion_producto AS nom_prod,
                                            stock_cantidad AS cantidad,
                                            stock_cantidad_tmp AS cantidad_actual
                                        FROM
                                            tbl_stock
                                        WHERE
                                            stock_periodo = :periodo
                                                AND stock_codigo_vendedor = :representantes
                                        GROUP BY stock_codigo_producto;");
        $query1->bindParam(":periodo", $periodo, PDO::PARAM_INT);
        $query1->bindParam(":representantes", $representantes, PDO::PARAM_INT);

        if($query1->execute())
        {
            if($query1->rowCount() > 0)
            {
                $output .= '<table class="table table-condensed table-bordered table-striped table-sm" id="table_stock_entregado_resumen_" style="font-size:1em;color:black;font-family:calibri;width:auto;">
                                <thead class="text-white" style="background-color:#588D9C;">
                                    <th class="text-center">Representante</th>
                                    <th class="text-center">Codigo Producto</th>
                                    <th class="text-center">Nombre Producto</th>
                                    <th class="text-center this_order">Sctock Inicial</th>
                                    <th class="text-center">Entregados</th>
                                    <th class="text-center">Stock actual</th>
                                </thead>
                                <tfoot class="text-white" style="background-color:#588D9C;font-size:0.9em;">
                                    <td class="text-center" colspan="3"><b class="h6">Total</b></td>
                                    <td class="text-center" style="color:black;background-color:#F9A828;">Sctock Inicial</td>
                                    <td class="text-center" style="color:black;background-color:#F9A828;">Entregados</td>
                                    <td class="text-center" style="color:black;background-color:#F9A828;">Stock actual</td>
                                </tfoot>
                         </table>';
                         
                while ($r_query1 = $query1->fetch(PDO::FETCH_ASSOC))
                {
                    $stock_actual_bd = $r_query1['cantidad_actual'];
                    $entregados = _sum_prod_entegados($representantes, $r_query1['cod_prod'], $mes, $year);

                    // $stock_actual = (int)($r_query1['cantidad'] - $entregados);

                    $entregados = (int)($r_query1['cantidad'] - $stock_actual_bd);

                    $result['cod_repre'] = search_repre_name($representantes);
                    $result['cod_prod'] = $r_query1['cod_prod'];
                    $result['nom_prod'] = $r_query1['nom_prod'];
                    $result['cantidad'] = $r_query1['cantidad'];
                    $result['entregados'] = $entregados;
                    $result['stock_actual'] = $stock_actual_bd;

                    $data['data'][] = $result;
                }
            }else
            {
                $output = 'No hay datos';
                $data['data']["Error"] = 'No hay datos';
            }
        }else
        {
            $output = 'No hay datos';
            $data['data']["Error"] = 'No hay datos';
        }
        return $output.'||'.json_encode($data, JSON_UNESCAPED_UNICODE);   
    }
    public function table_stock_count_all($mes, $year)
    {
        if($mes < 10)
        {
            $mes = '0'.$mes;
        }else
        {
            $mes = $mes;
        }
        $sumadias = 0;
        $periodo = (int)$year.$mes;
        #$periodo = $periodo+1;
        $stock_actual = 0;

        $output = null;

        $days_x_month = cal_days_in_month(CAL_GREGORIAN, $mes , $year);

        try
        {
            $productos = $this->db->prepare("SELECT 
                                                    stock_codigo_vendedor AS vend_cod,
                                                    representante_nombre AS vend_name,
                                                    stock_codigo_producto AS prod_cod,
                                                    stock_descripcion_producto AS prod_desc,
                                                    stock_cantidad AS stock_cantidad
                                                FROM
                                                    tbl_stock
                                                        INNER JOIN
                                                    tbl_representantes ON representante_codigo = stock_codigo_vendedor
                                                WHERE
                                                    stock_periodo = :periodo
                                                ORDER BY 1;");
            $productos->bindParam(":periodo",$periodo, PDO::PARAM_INT);
            if($productos->execute())
            {
                if($productos->rowCount() > 0)
                {
                    $output = '<table id="table_stock_count_all" class="table table-striped table-bordered table-condensed table-responsive display table-sm" style="width:auto !important;font-family:verdana !important;">
                                <thead style="color: white;background-color: #36404A;font-size:1em;">
                                    <th style="color: white !important;border-radius: 5px 0px 0px 0px !important;font-size:0.7em;" class="text-center">Codigo vendedor</th>
                                    <th style="color: white !important;font-size:0.7em;" class="text-center">Descripción vendedor</th>
                                    <th style="color: white !important;font-size:0.7em;" class="text-center">Codigo producto</th>
                                    <th style="color: white !important;font-size:0.7em;" class="text-center">Descripción producto</th>
                                    <th style="color: white !important;font-size:0.7em;" class="text-center">Stock inicial</th>';

                    for ($i=1; $i <= $days_x_month ; $i++)
                    {
                        $output .= '<th style="color: white !important;font-size:0.7em;" class="text-center">'.$i.'</th>';
                    }
                        $output .=' <th style="color: white !important;font-size:0.7em;" class="text-center">Stock actual</th>
                                    <th style="color: white !important;border-radius: 0px 5px 0px 0px !important;font-size:0.7em;" class="text-center this_order">Entregados</th>
                                    </thead>
                                    <tbody style=" color:black;font-weight:bold;">';

                    while ($productos_r = $productos->fetch(PDO::FETCH_ASSOC))
                    {

                        $vend_cod = $productos_r['vend_cod'];
                        $vend_name = $productos_r['vend_name'];
                        $prod_cod = $productos_r['prod_cod'];
                        $prod_desc = $productos_r['prod_desc'];

                        $stock_cantidad = $productos_r['stock_cantidad'];
                        
                        $output .= '<tr>
                                    <td style="font-size:0.65em;color:black;" class="text-left">'.$vend_cod.'</td>
                                    <td style="font-size:0.65em;color:black;" class="text-left">'.$vend_name.'</td>
                                    <td style="font-size:0.65em;color:black;" class="text-left">'.$prod_cod.'</td>
                                    <td style="font-size:0.65em;color:black;" class="text-left">'.$prod_desc.'</td>
                                    <td style="font-size:0.65em;color:black;" class="text-left">'.$stock_cantidad.'</td>';
                        
                        $sumadias = 0;

                        for ($f=1; $f <= $days_x_month ; $f++)
                        {
                            $f_core = $year.'-'.$mes.'-'.$f;

                            $f_complete = date('Y-m-d', strtotime($f_core));
                            
                            $prod_x_dia = $this->db->prepare("SELECT 
                                                                    SUM(medpro_cantidad) AS cantidad
                                                                FROM
                                                                    tbl_medpro_detalle
                                                                WHERE
                                                                    medpro_vendedor = :representantes
                                                                    AND medpro_producto = :prod_cod
                                                                        AND medpro_fecha = :f_complete;");
                            $prod_x_dia->bindParam(":representantes", $vend_cod, PDO::PARAM_INT);
                            $prod_x_dia->bindParam(":prod_cod", $prod_cod, PDO::PARAM_INT);
                            $prod_x_dia->bindParam(":f_complete", $f_complete, PDO::PARAM_STR);

                            if($prod_x_dia->execute())
                            {
                                while ( $prod_x_dia_r = $prod_x_dia->fetch(PDO::FETCH_ASSOC)  )
                                {
                                    if($prod_x_dia_r['cantidad'] == 0)
                                    {
                                        $output .= '<td style="font-size:0.75em;color:black;">0</td>';
                                        // $output .= '<td style="font-size:0.75em;color:black;">'.$prod_x_dia_r['cantidad'].'</td>';
                                    }else
                                    {
                                        // $output .= '<td  class="text-white" style="font-size:0.65em;background-color:#5EA3A6;font-weight:bold;cursor:pointer;">'.$prod_x_dia_r['cantidad'].'</td>';
                                        $output .= '<td class="text-white" style="font-size:0.65em;background-color:#5EA3A6;font-weight:bold;cursor:pointer;" onclick="return view_medicos_x_producto('."'".$vend_cod."'".','."'".$f_complete."'".','."'".$prod_cod."'".','."'".$prod_desc."'".');">'.$prod_x_dia_r['cantidad'].'</td>';
                                        #cobertura_fecha_productos('."'".$med_cod."'".','."'".$f."'".','."'".$mes."'".','."'".$year."'".','."'".$representantes."'".','."'".$consulta_r['nombre_medico']."'".');
                                    }

                                    $sumadias += (int)$prod_x_dia_r['cantidad'];
                                }
                            }else
                            {
                                $output = errorPDO($prod_x_dia);
                            }
                        }

                        $stock_actual = $stock_cantidad - $sumadias;
                        $Entregados = $sumadias;

                        $output .= '<td style="font-size:0.75em;color:black;">'.$stock_actual.'</td>
                                    <td style="font-size:0.75em;color:black;">'.$Entregados.'</td>
                                </tr>';
                    }
                    $output .= '</tbody></table>';
                }else
                {
                    $output = ' - productos-vacios'.$periodo;
                }
            }else
            {
                $output = errorPDO($productos);
            }
        }catch(PDOException $e)
        {
            $output = $e->getMessahe();
        }

        return $output;
    }
    public function view_medicos_x_producto($representantes, $fecha, $prod_cod)
    {
        try
        {
            $query1 = $this->db->prepare("SELECT 
                                                medpro_medico_cmp AS med_cod,
                                                medpro_cantidad AS prod_cant
                                            FROM
                                                tbl_medpro_detalle
                                            WHERE
                                                medpro_fecha = :fecha
                                                AND medpro_producto = :prod_cod
                                                AND medpro_vendedor = :representantes");
            $query1->bindParam(":representantes", $representantes, PDO::PARAM_INT);
            $query1->bindParam(":fecha", $fecha, PDO::PARAM_STR);
            $query1->bindParam(":prod_cod", $prod_cod, PDO::PARAM_INT);
            if($query1->execute())
            {
                if($query1->rowCount() > 0)
                {
                    $output = '<table class="table table-bordered table-striped table-condensed table-sm" style="font-size:0.8em;">
                                    <thead class="text-white" style="background-color:#476269;">
                                        <th class="text-center">CMP</th>
                                        <th class="text-center">MEDICO</th>
                                        <th class="text-center">CANT</th>
                                        <th class="text-center">ESP</th>
                                        <th class="text-center">CAT</th>
                                        <th class="text-center">INST</th>
                                        <th class="text-center">DIST</th>
                                    </thead>
                                    <tbody style="color:black;font-size:1em;font-family:verdana;">';
                    while ($query1_r = $query1->fetch(PDO::FETCH_ASSOC)) 
                    {
                        $data_med = medico_array($query1_r['med_cod']);

                        $output .= '<tr>
                                        <td>'.$data_med[0].'</td>
                                        <td>'.$data_med[1].'</td>
                                        <td>'.$query1_r['prod_cant'].'</td>
                                        <td>'.$data_med[2].'</td>
                                        <td>'.$data_med[3].'</td>
                                        <td>'.$data_med[4].'</td>
                                        <td>'.$data_med[5].'</td>
                                    </tr>';
                    }
                        $output .= '</tbody></table>';

                }else
                {
                    $output = 'No hay resultados . . .';
                }
            }else
            {
                $output = errorPDO($query1);
            }
        }catch(PDOException $e)
        {
            $output = $e->getMessage();
        }
        return $output;
    }
    public function propagandistas_muestras($mes, $year, $region)
    {
        $WHERE = null;
        
        if($region == 'T')
        {
            $WHERE = null;
        }else
        {
            $WHERE = " AND representante_region IN($region) ";
        }
        $WHERE = $WHERE;

        $output = null;

        $representantes = $this->db->prepare("SELECT 
                                                    representante_codigo as repre_cod,
                                                    representante_nombre as repre_name,
                                                    representante_zonag as zonag
                                                FROM
                                                    tbl_representantes
                                                WHERE
                                                    representante_cargo = 6
                                                        AND representante_estado IN('A',1)
                                                        AND representante_codigo != 999
                                                        $WHERE;");
        if($representantes->execute())    
        {
            if($representantes->rowCount() > 0)
            {#<th class="text-white text-center">Cant. Med.</th>
                $output .= '<table class="table table-condensed table-bordered table-sm" id="table-propagandistas_muestras" 
                            style="font-family:calibri;width:auto !important;">
                            <thead class="text-center" style="background-color:#4D7CAE; font-size:0.75em;">
                                <th class="text-white text-center">Zona</th>
                                <th class="text-white text-center">Representante</th>
                                <th class="text-white text-center this_order">Registro</th>
                                
                                <th class="text-white text-center">Med. vist.</th>
                                <th class="text-white text-center">Stock</th>
                                <th class="text-left" style="background-color:#F1E290;">Pron. AA</th>
                                <th class="text-white text-left" style="background-color:#415B90;">Realz. AA</th>
                                <th class="text-left text-white" style="background-color:#6088BB;">(%)</th>
                                <th class="text-left" style="background-color:#F1E290;">Pron. A</th>
                                <th class="text-white text-left" style="background-color:#415B90;">Realz. A</th>
                                <th class="text-left text-white" style="background-color:#6088BB;">(%)</th>
                                <th class="text-left" style="background-color:#F1E290;">Pron. B</th>
                                <th class="text-white text-left" style="background-color:#415B90;">Realz. B</th>
                                <th class="text-left text-white" style="background-color:#6088BB;">(%)</th>
                                <th class="text-left" style="background-color:#F1E290;">Pron. C</th>
                                <th class="text-white text-left" style="background-color:#415B90;">Realz. C</th>
                                <th class="text-left text-white" style="background-color:#6088BB;">(%)</th>
                                <th class="text-left" style="background-color:#F1E290;">Tot. Pron.</th>
                                <th class="text-white text-left" style="background-color:#415B90;">Tot. Realz.</th>
                                <th class="text-left text-white" style="background-color:#6088BB;">(%)</th>
                            </thead><tbody style="font-size:0.85em;color:black;">';# <br/>Al dia <b style="color:#00204A;">'.date('d-M').'</b>
                while ($representantes_r = $representantes->fetch(PDO::FETCH_ASSOC))
                {
                    $repre_cod = $representantes_r['repre_cod'];
                    $repre_name = $representantes_r['repre_name'];
                    $zonag = $representantes_r['zonag'];


                    $pronosticoAA = pronostico_categoria($repre_cod, 'AA');
                    $pronosticoA = pronostico_categoria($repre_cod, 'A');
                    $pronosticoB = pronostico_categoria($repre_cod, 'B');
                    $pronosticoC = pronostico_categoria($repre_cod, 'C');
                    #$total_pro = ($pronosticoAA+$pronosticoA+$pronosticoB+$pronosticoC);

                    $realizadoAA = realizado_categoria($repre_cod, $mes, $year, 'AA');
                    $realizadoA = realizado_categoria($repre_cod, $mes, $year, 'A');
                    $realizadoB = realizado_categoria($repre_cod, $mes, $year, 'B');
                    $realizadoC = realizado_categoria($repre_cod, $mes, $year, 'C');

                    @$cobAA = color_x_circulo(NAN_to_cero(round(($realizadoAA * 100)/$pronosticoAA)));
                    @$cobA = color_x_circulo(NAN_to_cero(round(($realizadoA * 100)/$pronosticoA)));
                    @$cobB = color_x_circulo(NAN_to_cero(round(($realizadoB * 100)/$pronosticoB)));
                    @$cobC = color_x_circulo(NAN_to_cero(round(($realizadoC * 100)/$pronosticoC)));

                    $total_pro = ($pronosticoAA+$pronosticoA+$pronosticoB+$pronosticoC);
                    $total_rea = ($realizadoAA+$realizadoA+$realizadoB+$realizadoC);
                    $cob_total = color_x_circulo(NAN_to_cero(round(($total_rea * 100)/$total_pro)));
                   
                    if($mes < 10)
                    {
                        $mes_nw = '0'.$mes;
                    }

                    $periodo_ = $year.$mes_nw;

                    $cantidad_stock = cantidad_sctock($repre_cod, $periodo_);

                    $ultima_visita = $this->db->prepare("SELECT 
                                                                MAX(medpro_fecha) AS ultimodia,
                                                                COALESCE(COUNT(DISTINCT medpro_medico_cmp), 0) AS cantidad_medicos
                                                            FROM
                                                                tbl_medpro_detalle
                                                            WHERE
                                                                medpro_vendedor = :repre_cod
                                                                    AND YEAR(medpro_fecha) = :year
                                                                    AND MONTH(medpro_fecha) = :mes;");
                    $ultima_visita->bindParam(":repre_cod", $repre_cod, PDO::PARAM_INT);
                    $ultima_visita->bindParam(":year", $year, PDO::PARAM_INT);
                    $ultima_visita->bindParam(":mes", $mes, PDO::PARAM_INT);
                    if($ultima_visita->execute())
                    {
                        if($ultima_visita->rowCount() > 0)
                        {
                            $ultima_visita_r = $ultima_visita->fetch(PDO::FETCH_ASSOC);

                            if(strpos($ultima_visita_r['ultimodia'], "-") !== FALSE)
                            {
                                $ultimodia_ex = explode("-", $ultima_visita_r['ultimodia']);
                                $ultimodia = (int)$ultimodia_ex[2];
                            }else
                            {
                                $ultimodia = $ultima_visita_r['ultimodia'];
                                if($ultimodia == null)
                                {
                                    $ultimodia = 0;
                                }
                            }
                            
                            $cantidad_medicos = $ultima_visita_r['cantidad_medicos'];
                            //<td>'.pronostico_medicos_new($repre_cod).'</td>
                            $output .= '<tr>
                                            <td class="text-white" style="background-color:#5ba19b;cursor:pointer;" onclick="padron_medico_x_represesntante('."'".$repre_cod."'".','."'".$repre_name."'".');" >'.$zonag.'</td>
                                            <td class="text-left">'.nombre_corto($repre_cod).'</td>
                                            <td>'.$ultimodia.'</td>
                                            <td class="text-white" style="background-color:#5ba19b;cursor:pointer;" onclick="medicos_visitados_x_dia('."'".$zonag."'".','."'".$repre_cod."'".','."'".$year."'".','."'".$mes."'".');" >'.$cantidad_medicos.'</td>
                                            
                                            <td>'.$cantidad_stock.'</td>
                                            <td style="background-color:#c8d6f8;">'.$pronosticoAA.'</td>
                                            <td style="background-color:#c8d6f8;">'.$realizadoAA.'</td>
                                            <td style="background-color:#c8d6f8;" class="text-left">'.$cobAA.'</td>
                                            <td style="background-color:#c8f8e0;">'.$pronosticoA.'</td>
                                            <td style="background-color:#c8f8e0;">'.$realizadoA.'</td>
                                            <td style="background-color:#c8f8e0;" class="text-left">'.$cobA.'</td>
                                            <td>'.$pronosticoB.'</td>
                                            <td>'.$realizadoB.'</td>
                                            <td class="text-left">'.$cobB.'</td>
                                            <td>'.$pronosticoC.'</td>
                                            <td>'.$realizadoC.'</td>
                                            <td class="text-left">'.$cobC.'</td>
                                            <td>'.$total_pro.'</td>
                                            <td>'.$total_rea.'</td>
                                            <td class="text-left">'.$cob_total.'</td>
                                        </tr>';
                        }else
                        {
                            $output .= '<tr>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                        </tr>';
                        }
                    }else
                    {
                        $output = errorPDO($ultima_visita);
                    }                  
                }

                $output .= '</tbody></table>';
            }
        }else
        {
            $output = errorPDO($representantes);
        }
        return $output;
    }
    public function medicos_productos_cantidad_periodo($representantes, $mes, $year)#CHECK
    {
        $output = null;

        $query1 = $this->db->prepare("SELECT 
                                        medpro_medico_cmp AS med_cod,
                                        medico_nombre AS med_name,
                                        medico_especialidad AS med_esp,
                                        medico_categoria AS med_cat,
                                        medico_localidad AS med_loc,
                                        medico_direccion AS med_dir,
                                        medpro_producto AS prod_cod,
                                        medpro_descripcion_producto AS prod_name,
                                        SUM(medpro_cantidad) AS cantidad
                                    FROM
                                        tbl_medpro_detalle
                                            INNER JOIN
                                        tbl_maestro_medicos ON medico_cmp = medpro_medico_cmp
                                    WHERE
                                        medpro_cantidad != 0
                                            AND YEAR(medpro_fecha) = :year
                                            AND MONTH(medpro_fecha) = :mes
                                            AND medpro_vendedor = :representantes
                                    GROUP BY 1 , 7");    
                                
        $query1->bindParam(":year", $year, PDO::PARAM_INT);
        $query1->bindParam(":mes", $mes, PDO::PARAM_INT);
        $query1->bindParam(":representantes", $representantes, PDO::PARAM_INT);
        if($query1->execute())
        {
            if($query1->rowCount() > 0)
            {
                $output .= '<table class="table table-condensed table-bordered table-striped table-hover table-sm" style="font-family:calibri;width:auto;" id="table_medicos_productos_cantidad_periodo">
                                <thead class="text-white" style="background-color:#528078;">
                                    <th class="text-center">CMP</th>
                                    <th class="text-center this_order">Medico</th>
                                    <th class="text-center">Esp</th>
                                    <th class="text-center">Cat</th>
                                    <th class="text-center">Distrito</th>
                                    <th class="text-center">Direccion</th>
                                    <th class="text-center">Cod Prod</th>
                                    <th class="text-center">Producto</th>
                                    <th class="text-center">Cant</th>
                                </thead>
                            <tfoot class="text-white" style="background-color:#588D9C;font-size:0.95em;">
                                <th class="text-center">CMP</th>
                                <th class="text-center">Medico</th>
                                <th class="text-center">Esp</th>
                                <th class="text-center">Cat</th>
                                <th class="text-center">Distrito</th>
                                <th class="text-center">Direccion</th>
                                <th class="text-center">Cod Prod</th>
                                <th class="text-center">Producto</th>
                                <th class="text-center">Cant</th>
                            </tfoot>
                         </table>';

                while($query1_r = $query1->fetch(PDO::FETCH_ASSOC))
                {
                    $result['med_cod'] = $query1_r['med_cod'];
                    $result['med_name'] = $query1_r['med_name'];
                    $result['med_esp'] = $query1_r['med_esp'];
                    $result['med_cat'] = $query1_r['med_cat'];
                    $result['med_loc'] = $query1_r['med_loc'];
                    $result['med_dir'] = str_replace("+Ã¦", "Ñ",$query1_r['med_dir']);
                    $result['prod_cod'] = $query1_r['prod_cod'];
                    $result['prod_name'] = $query1_r['prod_name'];
                    $result['cantidad'] = $query1_r['cantidad'];

                    $data['data'][] = array_map("utf8_encode", $result);
                }
            }else
            {
                $output = 'No hay datos';
                $data['data']["Error"] = 'No hay datos';
            }
        }else
        {
            $output = 'No hay datos';
            $data['data']["Error"] = 'No hay datos';
        }
        
        return $output.'||'.json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function padron_medico_x_represesntante($representantes)
    {
        $ExecQuery = null;

        $user_existe_medico = user_existe_medico($representantes);

        $Query_SQL1 = "SELECT 
                            medico_cmp AS cmp,
                            medico_nombre AS nombre,
                            medico_especialidad AS especialidad,
                            medico_categoria AS categoria,
                            medico_institucion AS institucion,
                            medico_localidad AS localidad,
                            medico_direccion AS direccion,
                            medico_alta_baja AS alta_baja,
                            medico_representante AS repre,
                            medico_supervisor AS superv,
                            medico_zona AS zona,
                            medico_ubigeo AS ubigeo
                        FROM
                            tbl_maestro_medicos
                        WHERE
                            medico_zona = (SELECT 
                                                representante_zonag
                                                FROM
                                                    tbl_representantes
                                                WHERE
                                                representante_codigo = :representantes)
                            AND medico_representante = :representantes;";

        $Query_SQL2 = "SELECT 
                            medico_cmp AS cmp,
                            medico_nombre AS nombre,
                            medico_especialidad AS especialidad,
                            medico_categoria AS categoria,
                            medico_institucion AS institucion,
                            medico_localidad AS localidad,
                            medico_direccion AS direccion,
                            medico_alta_baja AS alta_baja,
                            medico_representante AS repre,
                            medico_supervisor AS superv,
                            medico_zona AS zona,
                            medico_ubigeo AS ubigeo
                            FROM
                            tbl_maestro_medicos
                            WHERE
                            medico_zona = (SELECT 
                                                representante_zonag
                                                FROM
                                                    tbl_representantes
                                                WHERE
                                                representante_codigo = :representantes)
                            AND medico_representante IS NULL;";


        if($user_existe_medico == 1)
        {
            $ExecQuery = $Query_SQL1;
        }else
        {
            $ExecQuery = $Query_SQL2;
        }

        $select = $this->db->prepare($ExecQuery);
        $select->bindParam(":representantes", $representantes);
        if($select->execute())
        {
            if($select->rowCount() > 0)
            {
                $output = '<table class="table table-striped table-condensed table-bordered table-sm" style="width:auto !important;font-size:0.75em;color:black;" id="padron-medicos_med">
                                <thead class="text-white text-center" style="background-color:#56A7A7;">
                                    <th class="text-center">CMP</th>
                                    <th class="text-center">Medico</th>
                                    <th class="text-center">Esp.</th>
                                    <th class="text-center">Cat.</th>
                                    <th class="text-center">Institución</th>
                                    <th class="text-center">Dirección</th>
                                    <th class="text-center">Localidad</th>
                                    <th class="text-center">A/B</th>
                                    <th class="text-center">Rep.</th>
                                    <th class="text-center">Sup.</th>
                                    <th class="text-center">Zona</th>
                                    <th class="text-center">Ubigeo</th>

                                </thead>
                            </table>';
                while($select_r = $select->fetch(PDO::FETCH_ASSOC))
                {
                    $result['cmp'] = $select_r['cmp'];
                    $result['nombre'] = $select_r['nombre'];
                    $result['especialidad'] = $select_r['especialidad'];
                    $result['categoria'] = $select_r['categoria'];
                    $result['institucion'] = $select_r['institucion'];
                    $result['localidad'] = $select_r['localidad'];
                    $result['direccion'] = $select_r['direccion'];
                    $result['alta_baja'] = $select_r['alta_baja'];
                    $result['repre'] = $select_r['repre'];
                    $result['superv'] = $select_r['superv'];
                    $result['zona'] = $select_r['zona'];
                    $result['ubigeo'] = $select_r['ubigeo'];

                    $data['data'][] = $result;
                }
            }else
            {
                $output = 0;
                $data = 0;
            }
        }else
        {
            $output = errorPDO($select);
            $data = 0;
        }

        if($data != 0)
        {
           $json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
        }else
        {
            $json_data = 0;
        }
        return $output.'|~|'.$json_data;
    }
    public function medicos_visitados_x_dia($zonag, $representantes, $year, $mes)
    {
        $user_existe_medico = user_existe_medico($representantes);

        $Query_SQL1 = "SELECT 
                            medico_cmp AS cmp,
                            medico_nombre AS nombre,
                            medico_especialidad AS especialidad,
                            medico_categoria AS categoria,
                            medico_institucion AS institucion,
                            medico_localidad AS localidad,
                            medico_direccion AS direccion,
                            medico_alta_baja AS alta_baja,
                            medico_representante AS repre,
                            medico_supervisor AS superv,
                            medico_zona AS zona,
                            medico_ubigeo AS ubigeo
                        FROM
                            tbl_maestro_medicos
                        WHERE
                            medico_zona = (SELECT 
                                                representante_zonag
                                                FROM
                                                    tbl_representantes
                                                WHERE
                                                representante_codigo = :representantes)
                            AND medico_representante = :representantes;";

        $Query_SQL2 = "SELECT 
                            medico_cmp AS cmp,
                            medico_nombre AS nombre,
                            medico_especialidad AS especialidad,
                            medico_categoria AS categoria,
                            medico_institucion AS institucion,
                            medico_localidad AS localidad,
                            medico_direccion AS direccion,
                            medico_alta_baja AS alta_baja,
                            medico_representante AS repre,
                            medico_supervisor AS superv,
                            medico_zona AS zona,
                            medico_ubigeo AS ubigeo
                            FROM
                            tbl_maestro_medicos
                            WHERE
                            medico_zona = (SELECT 
                                                representante_zonag
                                                FROM
                                                    tbl_representantes
                                                WHERE
                                                representante_codigo = :representantes)
                            AND medico_representante IS NULL;";


        if($user_existe_medico == 1)
        {
            $ExecQuery = $Query_SQL1;
        }else
        {
            $ExecQuery = $Query_SQL2;
        }

        $sumadias = 0;
        $cob = 0;
        $Pendiente = 0;

        $days_x_month = cal_days_in_month(CAL_GREGORIAN, $mes , $year);


        $output = '<table id="table_medicos_vistados_x_dia" class="table table-striped table-bordered table-condensed table-responsive table-sm" style="width:auto !important;font-size:0.9em;font-weight: regular;font-family: Montserrat, sans-serif;">
                            <thead style="color: white;background-color: #36404A;font-size:0.75em;">
                                  <th style="color: white !important;border-radius: 5px 0px 0px 0px !important;font-size:0.7em;" class="text-center">CMP</th>
                                    <th style="color: white !important;" class="text-center">Medico</th>
                                    <th style="color: white !important;" class="text-center">Esp</th>
                                    <th style="color: white !important;" class="text-center">Cat</th>';
                                    #<th style="color: white !important;" class="text-center">Distrito</th>';

            for ($i=1; $i <= $days_x_month ; $i++)
            {
                    $create_date = $year.'-'.$mes.'-'.$i;
                    // $output .= '<th style="color: white !important;font-size:0.7em;" class="text-center">'.$i.'</th>';
                    if(date('w',strtotime($create_date)) == 0 )
                    {
                        $output .= '<th style="color: white !important;font-size:0.78em;background-color:red !important;" class="text-center">'.$i.'</th>';
                    }else
                    {
                        $output .= '<th style="color: white !important;font-size:0.78em;" class="text-center">'.$i.'</th>';
                    } 
                #$output .= '<th style="color: white !important;" class="text-center">'.$i.'</th>';
            }
                $output .=' <th class="order_this" style="color: white !important;border-radius: 0px 5px 0px 0px !important;font-size:0.75em;">total</th>
                            
                            </thead>
                            <tbody style=" color:black;font-weight:bold;">';

            $select_medico = $this->db->prepare($ExecQuery);
            $select_medico->bindParam(":representantes", $representantes);
            if($select_medico->execute())
            {
                while ($r_medico = $select_medico->fetch(PDO::FETCH_ASSOC)) 
                {
                    $sumadias = 0;
                    $cmp = $r_medico['cmp'];
                    $medico = $r_medico['nombre'];
                    $especialidad = $r_medico['especialidad'];
                    $categoria = $r_medico['categoria'];
                    $localidad = $r_medico['localidad'];

                    $output .= '<tr><td style="font-size:0.8em;color:black;">'.$cmp.'</td>
                                    <td style="font-size:0.65em;color:black;">'.$medico.'</td>
                                    <td style="font-size:0.8em;color:black;">'.$especialidad.'</td>
                                    <td style="font-size:0.8em;color:black;">'.$categoria.'</td>';
                                    #<td style="font-size:0.8em;color:black;">'.$localidad.'</td>';

                     for ($f=1; $f <= $days_x_month ; $f++)
                    {
                        $f_core = $year.'-'.$mes.'-'.$f;
            
                        $f_complete = date('Y-m-d', strtotime($f_core));
                                        
                        $visitasxdia = $this->db->prepare("SELECT 
                                                                medpro_medico_cmp
                                                            FROM
                                                                tbl_medpro_detalle
                                                            WHERE
                                                                medpro_vendedor = :representantes
                                                                    AND medpro_medico_cmp = :cmp
                                                                    AND medpro_fecha = :f_complete 
                                                                    GROUP BY medpro_medico_cmp;");
                        $visitasxdia->bindParam(":representantes", $representantes);
                        $visitasxdia->bindParam(":cmp", $cmp);
                        $visitasxdia->bindParam(":f_complete", $f_complete);
                        
                        if($visitasxdia->execute())
                        {
                            if($visitasxdia->rowCount() > 0)
                            {
                                $output .= '<td style="font-size:0.75em;color:white;background-color:#5ba19b;cursor:pointer;" 
                                onclick="cobertura_fecha_productos('."'".$cmp."'".','."'".$f."'".','."'".$mes."'".','."'".$year."'".','."'".$representantes."'".','."'".$medico."'".');">v</td>';

                                $sumadias += 1;
                            }else
                            {
                                $output .= '<td style="font-size:0.75em;color:black;"><span class="fa  fa-close"></span></td>';
                            }
                        }else
                        {
                            $output .= '<td style="font-size:0.75em;color:black;">'.errorPDO($visitasxdia).'</td>';
                        }
                    }
                    $output .= '<td style="font-size:0.75em;color:black;">'.$sumadias.'</td></tr>';
                }
            }
            $output .= '</tbody></table>';

        return $output;
    }
    public function __destruct()
    {
        $this->db = NULL;#Connection Desctruct#
    }


}