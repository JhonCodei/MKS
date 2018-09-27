<?php

Class KushkaModel
{
    public function __construct()
    {
        $this->db = Database::Connection();#Connection DB#
        #__SQL__();
    }
    #__functions__
    public function load_locales($file_excel)
    {
        date_default_timezone_set('UTC');
        
        $delete = $this->db->prepare("TRUNCATE TABLE tbl_kushka_maestro_locales");
        if($delete->execute())
        {
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
                
                $f_local_codigo = $DataRow[0];
                $f_local_cod_proveedor = $DataRow[1];
                $f_local_desc = $DataRow[2];
                $f_local_formato = $DataRow[3];
                $f_local_tipo = $DataRow[4];
                $f_local_direccion = $DataRow[5];
                $f_local_estado = $DataRow[6];
    
                if(trim($f_local_estado) == 'ACTIVO')
                {
                    $f_local_estado = 1;
                }else
                {
                    $f_local_estado = 0;
                }
    
                $f_local_departamento = $DataRow[7];
                $f_local_provincia = $DataRow[8];
                $f_local_distrito = $DataRow[9];
                $f_local_f_apertura = $DataRow[10];#date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($DataRow[11])); #FECHA$DataRow[11];
                $f_local_cierre = $DataRow[11];#date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($DataRow[12])); #FECHA$DataRow[12];
    
                #departamento,provincia,distrito
                $f_local_ubigeo = _search_ubigeo_locales($f_local_departamento, $f_local_provincia, $f_local_distrito);
                $f_local_zona = _search_zona_locales($f_local_departamento, $f_local_provincia, $f_local_distrito);
    
                $insert = $this->db->prepare("INSERT INTO tbl_kushka_maestro_locales(f_local_codigo, f_local_cod_proveedor,
                                            f_local_desc, f_local_formato, f_local_tipo, f_local_direccion,
                                            f_local_estado, f_local_departamento, f_local_provincia, f_local_distrito,
                                            f_local_ubigeo, f_local_f_apertura, f_local_cierre, f_local_zona)
                                            VALUES(:f_local_codigo, :f_local_cod_proveedor,
                                            :f_local_desc, :f_local_formato, :f_local_tipo, :f_local_direccion,
                                            :f_local_estado, :f_local_departamento, :f_local_provincia, :f_local_distrito,
                                            :f_local_ubigeo, :f_local_f_apertura, :f_local_cierre, :f_local_zona)");
    
                $insert->bindParam(":f_local_codigo", $f_local_codigo, PDO::PARAM_STR);
                $insert->bindParam(":f_local_cod_proveedor", $f_local_cod_proveedor, PDO::PARAM_STR);
                $insert->bindParam(":f_local_desc", $f_local_desc, PDO::PARAM_STR);
                $insert->bindParam(":f_local_formato", $f_local_formato, PDO::PARAM_STR);
                $insert->bindParam(":f_local_tipo", $f_local_tipo, PDO::PARAM_STR);
                $insert->bindParam(":f_local_direccion", $f_local_direccion, PDO::PARAM_STR);
                $insert->bindParam(":f_local_estado", $f_local_estado, PDO::PARAM_STR);
                $insert->bindParam(":f_local_departamento", $f_local_departamento, PDO::PARAM_STR);
                $insert->bindParam(":f_local_provincia", $f_local_provincia, PDO::PARAM_STR);
                $insert->bindParam(":f_local_distrito", $f_local_distrito, PDO::PARAM_STR);
                $insert->bindParam(":f_local_ubigeo", $f_local_ubigeo, PDO::PARAM_STR);
                $insert->bindParam(":f_local_f_apertura", $f_local_f_apertura, PDO::PARAM_STR);
                $insert->bindParam(":f_local_cierre", $f_local_cierre, PDO::PARAM_STR);
                $insert->bindParam(":f_local_zona", $f_local_zona, PDO::PARAM_INT);
                if($insert->execute())
                {
                    print 'OK'.$f_local_codigo.' - '.$f_local_zona .' - '.$f_local_ubigeo .'<br>';
                }else
                {
                    print "error => " .errorPDO($insert) .'<br>';
                }
            }
        } 
    }
    public function load_drenaje($file_excel, $empresa)
    {
        $file_row = 0;
        $out = null;

        $csv = new LibCSV();
        $csv->auto($file_excel);
            
        foreach ($csv->data as $key => $row)
        {
            $drenaje_fecha = trim($row['PERIODO']);
            $drenaje_cod_lab = trim($row['COD_LAB']);
            $drenaje_lab = trim($row['LABORATORIO']);
            $drenaje_cod_prod = trim($row['COD_PRODUCTO']);
            $drenaje_cod_prod_proveedor = trim($row['COD_PRODUCTO_PROVEEDOR']);
            $drenaje_cod_inkaventa = trim($row['COD_INKAVENTA']);
            $drenaje_descripcion = trim($row['DESCRIPCION']);
            $drenaje_estado_prod = trim($row['ESTADO_PROD.']);
            $drenaje_umb = trim($row['UMB']);
            $drenaje_cod_local = trim($row['COD_LOCAL']);
            $empty = trim($row['COD_LOCAL_PROVEEDOR']);
            $drenaje_cod_local_proveedor = trim($row['DESCRIPCION_LOCAL']);
            $drenaje_estado_local = trim($row['ESTADO_LOCAL']);
            $drenaje_formato = trim($row['FORMATO']);
            $drenaje_tipo = trim($row['TIPO']);
            $drenaje_distrito = trim($row['DISTRITO']);
            $drenaje_venta_periodo_unidad = trim($row['VTA_PERIODO_UNID']);
            $drenaje_costo_venta_periodo = trim($row['COSTO_DE_VENTA_PERIODO_S']);

            // print '<pre>';
            // print_r($row);
																	
            $drenaje_fecha_explo = explode('/', $drenaje_fecha);#dia/mes/año

            $drenaje_fecha = $drenaje_fecha_explo[2].'-'.$drenaje_fecha_explo[1].'-'.$drenaje_fecha_explo[0];
            $drenaje_fecha_mes = $drenaje_fecha_explo[1];
            $drenaje_fecha_year = $drenaje_fecha_explo[2];

            $drenaje_periodo = $drenaje_fecha_year.$drenaje_fecha_mes;

            $drenaje_rangos_fecha = $drenaje_periodo.'_'.$drenaje_fecha_explo[0];


            $insert = $this->db->prepare("INSERT INTO tbl_kushka_drenaje(drenaje_fecha, drenaje_cod_lab, 
                                                    drenaje_lab, drenaje_cod_prod, drenaje_cod_prod_proveedor,
                                                    drenaje_cod_inkaventa, drenaje_descripcion, drenaje_estado_prod,
                                                    drenaje_umb, drenaje_cod_local, drenaje_cod_local_proveedor, 
                                                    drenaje_estado_local, drenaje_formato, drenaje_tipo, drenaje_distrito, 
                                                    drenaje_venta_periodo_unidad, drenaje_costo_venta_periodo, drenaje_x_empresa, 
                                                    drenaje_periodo, drenaje_rangos_fecha)
                                                VALUES(:drenaje_fecha, :drenaje_cod_lab, :drenaje_lab, :drenaje_cod_prod, 
                                                        :drenaje_cod_prod_proveedor, :drenaje_cod_inkaventa, :drenaje_descripcion, 
                                                        :drenaje_estado_prod, :drenaje_umb, :drenaje_cod_local, :drenaje_cod_local_proveedor, 
                                                        :drenaje_estado_local, :drenaje_formato, :drenaje_tipo, :drenaje_distrito, :drenaje_venta_periodo_unidad, 
                                                        :drenaje_costo_venta_periodo, :drenaje_x_empresa, :drenaje_periodo, :drenaje_rangos_fecha)");

            $insert->bindParam(":drenaje_fecha", $drenaje_fecha);
            $insert->bindParam(":drenaje_cod_lab", $drenaje_cod_lab);
            $insert->bindParam(":drenaje_lab", $drenaje_lab);
            $insert->bindParam(":drenaje_cod_prod", $drenaje_cod_prod);
            $insert->bindParam(":drenaje_cod_prod_proveedor", $drenaje_cod_prod_proveedor);
            $insert->bindParam(":drenaje_cod_inkaventa", $drenaje_cod_inkaventa);
            $insert->bindParam(":drenaje_descripcion", $drenaje_descripcion);
            $insert->bindParam(":drenaje_estado_prod", $drenaje_estado_prod);
            $insert->bindParam(":drenaje_umb", $drenaje_umb);
            $insert->bindParam(":drenaje_cod_local", $drenaje_cod_local);
            $insert->bindParam(":drenaje_cod_local_proveedor", $drenaje_cod_local_proveedor);
            $insert->bindParam(":drenaje_estado_local", $drenaje_estado_local);
            $insert->bindParam(":drenaje_formato", $drenaje_formato);
            $insert->bindParam(":drenaje_tipo", $drenaje_tipo);
            $insert->bindParam(":drenaje_distrito", $drenaje_distrito);
            $insert->bindParam(":drenaje_venta_periodo_unidad", $drenaje_venta_periodo_unidad);
            $insert->bindParam(":drenaje_costo_venta_periodo", $drenaje_costo_venta_periodo);
            $insert->bindParam(":drenaje_x_empresa", $empresa);
            $insert->bindParam(":drenaje_periodo", $drenaje_periodo);
            $insert->bindParam(":drenaje_rangos_fecha", $drenaje_rangos_fecha);

            if($insert->execute())
            {
                print '1<br>';
                #$out = $file_row++;
            }else
            {
                print " - error => " .errorPDO($insert) .'<br>';
            }
        }  
        // print $out;
    }
    public function generar_data_kushka()
    {
        try
        {
            $drop_table_tmp = $this->db->prepare("TRUNCATE TABLE tmp_reportes_kushka");
            if($drop_table_tmp->execute())
            {
                $region = $this->db->prepare("SELECT DISTINCT regiones_cod_2 FROM regiones");

                if($region->execute())
                {
                    if($region->rowCount() > 0)
                    {
                        $n = 0;
    
                        while ($region_r = $region->fetch(PDO::FETCH_ASSOC))
                        {
    
                            $region_cod = $region_r['regiones_cod_2'];
    
                            $kushka_data = $this->db->prepare("SELECT 
                                                                    drenaje_fecha,
                                                                    drenaje_cod_lab,
                                                                    drenaje_cod_prod_proveedor,
                                                                    drenaje_descripcion,
                                                                    drenaje_cod_local,
                                                                    drenaje_distrito,
                                                                    drenaje_venta_periodo_unidad,
                                                                    drenaje_costo_venta_periodo,
                                                                    drenaje_x_empresa,
                                                                    kushka_fichero_local_b.f_local_zona AS zona
                                                                FROM
                                                                    kushka_drenaje,
                                                                    zonas,
                                                                    kushka_fichero_local_b
                                                                WHERE
                                                                    kushka_fichero_local_b.f_local_codigo = kushka_drenaje.drenaje_cod_local
                                                                        AND kushka_fichero_local_b.f_local_zona = zonas.zona_codigo
                                                                        AND drenaje_cod_local IN (SELECT DISTINCT
                                                                            f_local_codigo
                                                                        FROM
                                                                            kushka_fichero_local_b
                                                                        WHERE
                                                                            f_local_zona IN (SELECT DISTINCT
                                                                                    zona_codigo
                                                                                FROM
                                                                                    zonas
                                                                                WHERE
                                                                                    zonas.zona_zgrupos_cod IN (SELECT DISTINCT
                                                                                            zgrupos_codigo
                                                                                        FROM
                                                                                            zona_general
                                                                                        WHERE
                                                                                            zgrupos_region = :region_cod))
                                                                        GROUP BY f_local_codigo);");
                            $kushka_data->bindParam(":region_cod", $region_cod, PDO::PARAM_STR);
                            if($kushka_data->execute())
                            {
                                if($kushka_data->rowCount() > 0)
                                {
                                    while ($kushka_data_r = $kushka_data->fetch(PDO::FETCH_ASSOC))
                                    {
                                        $dre_fecha = $kushka_data_r['drenaje_fecha'];
                                        $dre_cod_lab = $kushka_data_r['drenaje_cod_lab'];
                                        $dre_cod_prod = $kushka_data_r['drenaje_cod_prod_proveedor'];
                                        $dre_descripcion = $kushka_data_r['drenaje_descripcion'];
                                        $dre_cod_local = $kushka_data_r['drenaje_cod_local'];
                                        $dre_distrito = $kushka_data_r['drenaje_distrito'];
                                        $dre_venta_periodo_unidad = $kushka_data_r['drenaje_venta_periodo_unidad'];
                                        $dre_costo_venta_periodo = $kushka_data_r['drenaje_costo_venta_periodo'];
                                        $dre_empresa = $kushka_data_r['drenaje_x_empresa'];
                                        $dre_zona = $kushka_data_r['zona'];
                                        
                                        $insert = $this->db->prepare("INSERT INTO tmp_reportes_kushka(kushka_cod_prod, kushka_nam_prod,
                                                                    kushka_venta_periodo_u, kushka_costo_venta_periodo, kushka_empresa,
                                                                    kushka_cod_lab, kushka_cod_local, kushka_local_distrito,
                                                                    kushka_fecha, kushka_zona, kushka_region)
                                                                    VALUES(:dre_cod_prod, :dre_descripcion, :dre_venta_periodo_unidad,
                                                                    :dre_costo_venta_periodo, :dre_empresa, :dre_cod_lab, 
                                                                    :dre_cod_local, :dre_distrito, :dre_fecha, :dre_zona, :region_cod)");
                                        
                                        $insert->bindParam(":dre_fecha", $dre_fecha, PDO::PARAM_STR);
                                        $insert->bindParam(":dre_cod_lab", $dre_cod_lab, PDO::PARAM_STR);
                                        $insert->bindParam(":dre_cod_prod", $dre_cod_prod, PDO::PARAM_STR);
                                        $insert->bindParam(":dre_descripcion", $dre_descripcion, PDO::PARAM_STR);
                                        $insert->bindParam(":dre_cod_local", $dre_cod_local, PDO::PARAM_STR);
                                        $insert->bindParam(":dre_distrito", $dre_distrito, PDO::PARAM_STR);
                                        $insert->bindParam(":dre_venta_periodo_unidad", $dre_venta_periodo_unidad, PDO::PARAM_INT);
                                        $insert->bindParam(":dre_costo_venta_periodo", $dre_costo_venta_periodo, PDO::PARAM_INT);
                                        $insert->bindParam(":dre_empresa", $dre_empresa, PDO::PARAM_INT);
                                        $insert->bindParam(":dre_zona", $dre_zona, PDO::PARAM_INT);
                                        $insert->bindParam(":region_cod", $region_cod, PDO::PARAM_INT);
    
                                        if($insert->execute())
                                        {
                                            print $n++.'<br>';
                                        }else
                                        {
                                            print errorPDO($insert);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }else
            {
                print "Error truncate, =>" . errorPDO($drop_table_tmp);
            }
        }catch(PDOException $e)
        {
            print $e->getMessage();
        }
        // return $output;
    }
    public function generar_data($year, int $mes)
    {
        $n = 0;
        
        try
        {
            $drop_table_tmp = $this->db->prepare("DELETE FROM reporte_drenaje_kushka 
                                                                            WHERE YEAR(kushka_fecha) = :year 
                                                                            AND MONTH(kushka_fecha) = :mes ");
            $drop_table_tmp->bindParam(":year", $year);
            $drop_table_tmp->bindParam(":mes", $mes);
            if($drop_table_tmp->execute())
            {
                if($mes < 10)
                {
                    $mes = '0'.$mes;
                }

                $periodo__ = $year.$mes;

                $kushka_data = $this->db->prepare("SELECT 
                                                        drenaje_fecha,
                                                        drenaje_cod_lab,
                                                        drenaje_cod_prod_proveedor,
                                                        drenaje_descripcion,
                                                        drenaje_cod_local,
                                                        drenaje_distrito,
                                                        drenaje_venta_periodo_unidad,
                                                        drenaje_costo_venta_periodo,
                                                        drenaje_x_empresa
                                                    FROM
                                                        tbl_kushka_drenaje
                                                    WHERE
                                                        drenaje_periodo = :periodo;");
                                                    
                            $kushka_data->bindParam(":periodo", $periodo__);
                            if($kushka_data->execute())
                            {
                                if($kushka_data->rowCount() > 0)
                                {
                                    while ($kushka_data_r = $kushka_data->fetch(PDO::FETCH_ASSOC))
                                    {
                                        $dre_fecha = $kushka_data_r['drenaje_fecha'];
                                        $dre_cod_lab = $kushka_data_r['drenaje_cod_lab'];
                                        $dre_cod_prod = (int)$kushka_data_r['drenaje_cod_prod_proveedor'];
                                        $dre_descripcion = $kushka_data_r['drenaje_descripcion'];
                                        $dre_cod_local = $kushka_data_r['drenaje_cod_local'];
                                        $dre_distrito = $kushka_data_r['drenaje_distrito'];
                                        $dre_venta_periodo_unidad = $kushka_data_r['drenaje_venta_periodo_unidad'];
                                        $dre_costo_venta_periodo = $kushka_data_r['drenaje_costo_venta_periodo'];
                                        $dre_empresa = $kushka_data_r['drenaje_x_empresa'];
                                        $dre_data_name_zona = explode("~~", kushka_data($kushka_data_r['drenaje_cod_local']));
                                        $dre_zona = $dre_data_name_zona[1];

                                        $dre_zona_g = (int)kushka_zonag($dre_zona);
                                        $region_cod = (int)kushka_region_($dre_zona_g);

                                        $drenaje_local_name = $dre_data_name_zona[0];
                                        
                                        $insert = $this->db->prepare("INSERT INTO reporte_drenaje_kushka(kushka_cod_prod, kushka_nam_prod,
                                                                    kushka_venta_periodo_u, kushka_costo_venta_periodo, kushka_empresa,
                                                                    kushka_cod_lab, kushka_cod_local, drenaje_local_name, kushka_local_distrito,
                                                                    kushka_fecha, kushka_zona, drenaje_zona_g, kushka_region)
                                                                    VALUES(:dre_cod_prod, :dre_descripcion, :dre_venta_periodo_unidad,
                                                                    :dre_costo_venta_periodo, :dre_empresa, :dre_cod_lab, 
                                                                    :dre_cod_local, :drenaje_local_name, :dre_distrito, :dre_fecha, 
                                                                    :dre_zona, :dre_zona_g, :region_cod)");
                                        
                                        $insert->bindParam(":dre_fecha", $dre_fecha, PDO::PARAM_STR);
                                        $insert->bindParam(":dre_cod_lab", $dre_cod_lab, PDO::PARAM_STR);
                                        $insert->bindParam(":dre_cod_prod", $dre_cod_prod, PDO::PARAM_STR);
                                        $insert->bindParam(":dre_descripcion", $dre_descripcion, PDO::PARAM_STR);
                                        $insert->bindParam(":dre_cod_local", $dre_cod_local, PDO::PARAM_STR);
                                        $insert->bindParam(":drenaje_local_name", $drenaje_local_name, PDO::PARAM_STR);
                                        $insert->bindParam(":dre_distrito", $dre_distrito, PDO::PARAM_STR);
                                        $insert->bindParam(":dre_venta_periodo_unidad", $dre_venta_periodo_unidad);
                                        $insert->bindParam(":dre_costo_venta_periodo", $dre_costo_venta_periodo);
                                        $insert->bindParam(":dre_empresa", $dre_empresa, PDO::PARAM_INT);
                                        $insert->bindParam(":dre_zona", $dre_zona, PDO::PARAM_INT);
                                        $insert->bindParam(":dre_zona_g", $dre_zona_g, PDO::PARAM_INT);
                                        $insert->bindParam(":region_cod", $region_cod, PDO::PARAM_INT);
    
                                        if($insert->execute())
                                        {
                                            print $n++.'<br>';
                                        }else
                                        {
                                            print errorPDO($insert);
                                        }
                                    }
                                }
                            }else
                            {
                                print errorPDO($kushka_data);
                            }
            }else
            {
                print "Error truncate, =>" . errorPDO($drop_table_tmp);
            }
        }catch(PDOException $e)
        {
            print $e->getMessage();
        }
        // return $output;
    }
    public function listar_reporte($year, $mes)
    {
        $data = null;
        $output = null;
            $select = $this->db->prepare("SELECT kushka_fecha,
                                                kushka_cod_local,
                                                kushka_local_distrito,
                                                drenaje_local_name,
                                                kushka_region,
                                                drenaje_zona_g,
                                                kushka_zona,
                                                kushka_local_distrito,
                                                kushka_cod_prod,
                                                kushka_nam_prod,
                                                kushka_venta_periodo_u,
                                                kushka_costo_venta_periodo
                                                FROM reporte_drenaje_kushka
                                            WHERE YEAR(kushka_fecha) = :year AND MONTH(kushka_fecha)= :mes");
            $select->bindParam(":year", $year);
            $select->bindParam(":mes", $mes);
            if($select->execute())
            {
                if($select->rowCount() > 0)
                {#style="font-size:0.6em;" 
                    $output = '<table class="table table-bordered table-condensed table-striped table-hover table-sm" id="table-kushka_all" style="width: auto !important;">
                                <thead style="background-color:#528078;font-size:0.65em;" class="text-white">
                                    <th class="text-center">CD</th>
                                    <th class="text-center">DISTRIBUIDORA</th>
                                    <th class="text-center">ANO</th>
                                    <th class="text-center">RUC</th>
                                    <th class="text-center">DISTRITO</th>
                                    <th class="text-center">CLIENT</th>
                                    <th class="text-center">DESCRIP</th>
                                    <th class="text-center">RE</th>
                                    <th class="text-center">ZG</th>
                                    <th class="text-center">ZON</th>
                                    <th class="text-center">DESC DISTRITO</th>
                                    <th class="text-center">PRODUCTO</th>
                                    <th class="text-center">DESCRIPCION</th>
                                    <th class="text-center">CANT</th>
                                    <th class="text-center">IMPORTE</th>
                                </thead></table>';
                                
                    while ($rquery1 = $select->fetch(PDO::FETCH_ASSOC))
                    {
                        
                        $fecha_exp = explode("-", $rquery1['kushka_fecha']);
            
                        $periodo = $fecha_exp[0].$fecha_exp[1];

                        $cd = "xx";
                        $distribuidora = "INKAFARMA";

                        $cod_local = $rquery1['kushka_cod_local'];
                        $distrito = $rquery1['kushka_local_distrito'];
                        $cliente = "xx";
                        $descripcion = kushka_data_names($cod_local);

                        $region = $rquery1['kushka_region'];
                        $zonag = $rquery1['drenaje_zona_g'];
                        $zona = $rquery1['kushka_zona'];
                        $z_descripcion = $rquery1['kushka_local_distrito'];
                        
                        $producto_cod  = kushka_codprod_markos($rquery1['kushka_cod_prod']);
                        $producto_name  = $rquery1['kushka_nam_prod'];

                        $cantidad  = $rquery1['kushka_venta_periodo_u'];
                        $valor  = $rquery1['kushka_costo_venta_periodo'];

                        $result['cd'] = $cd;
                        $result['distribuidora'] = $distribuidora;
                        $result['periodo'] = $periodo;
                        $result['cod_local'] = $cod_local;
                        $result['distrito'] = $distrito;
                        $result['cliente'] = $cliente;
                        $result['descripcion'] = $descripcion;
                        $result['region'] = $region;
                        $result['zonag'] = $zonag;
                        $result['zona'] = $zona;
                        $result['z_descripcion'] = $z_descripcion;
                        $result['producto_cod'] = $producto_cod;
                        $result['producto_name'] = $producto_name;
                        $result['cantidad'] = $cantidad;
                        $result['valor'] = $valor;

                        $data['data'][] = $result;
                    }
                }else
                {
                    $output = "0";
                    $data['error'][] = "-";
                }
            }else
            {
                $output = "0".errorPDO($select);
                $data['error'][] = "error Query";
            }
        return $output.'|~|'.json_encode($data, JSON_UNESCAPED_UNICODE);
    }   
    public function __destruct()
    {
        $this->db = NULL;#Connection Desctruct#
        #__SQL__();
    }


}