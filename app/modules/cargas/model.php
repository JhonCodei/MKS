<?php

Class CargasModel
{
    public function __construct()
    {
        $this->db = Database::Connection();#Connection DB#
        #__SQL__();
    }
    #__functions__
    public function load_csv($file_excel, $periodo)
    {      
        try 
        {
            date_default_timezone_set('UTC');

            $DeleteData = $this->db->prepare("DELETE FROM tbl_drenaje_ventas WHERE drenaje_periodo = :periodo");
            $DeleteData->bindParam(":periodo", $periodo, PDO::PARAM_INT);
            
            if ($DeleteData->execute()) 
            {
                $inputFileName = $file_excel;
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
                    $factura_serie = 0;
                    $COD_VENDEDOR       = (int)$DataRow[1]; #COD_VENDEDOR
                    $NAME_VENDEDOR      = $DataRow[2]; #NAME_VENDEDOR
                    $COD_DISTRIBUIDORA  = (int)$DataRow[3]; #COD_DISTRIBUIDORA
                    $NAME_DISTRIBUIDORA = $DataRow[4]; #NAME_DISTRIBUIDORA

                    $FECHA = date($format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP(trim($DataRow[6]))); #FECHA

                    $explo_fecha = explode('-', $FECHA);
                    $f_mes = $explo_fecha[1];
                    $f_day = $explo_fecha[2];

                    $FECHA_ = yearmes_to_year_month($periodo);

                    $FECHA = $FECHA_['year'].'-'.$f_mes.'-'.$f_day;

                    ##CHOCLON##

                    $COD_SUBZONA  = (int)$DataRow[7];
                    $ZONA         = $DataRow[8];
                    ########

                    $RUC_CLIENTE        = trim($DataRow[9]); #RUC_CLIENTE
                    $COD_CLIENTE        = trim($DataRow[10]); #COD_CLIENTE

                    #complementar  = facturas anuladas.

                    $NAME_CLIENTE       = $DataRow[11];
                    
                    if($NAME_CLIENTE == '-') #NAME_CLIENTE
                    {
                        $NAME_CLIENTE = '-';
                    }elseif(empty($NAME_CLIENTE))
                    {
                        $NAME_CLIENTE = '-';
                    }else
                    {
                        $NAME_CLIENTE = $NAME_CLIENTE;
                    }

                    $COD_PRODUCTO       = (int)$DataRow[12]; #COD_PRODUCTO
                    $NAME_PRODUCTO      = $DataRow[13]; #NAME_PRODUCTO
                    $CANTIDAD           = (int)$DataRow[14]; #CANTIDAD
                    $VALOR              = (double)$DataRow[15]; #VALOR
                   
                    $PERIODO = $periodo;
                    $DISTRITOS = 1;#clear_input($DataRow[15]);
                    $ZONA_VISITA = clear_input($DataRow[17]);
                    $COD_ZONA_G  = clear_input($DataRow[0]);
                    #$REGION  = clear_input($DataRow[18]);

                    $ZONA_UBIGEO_XLS = clear_input($DataRow[18]);
                    $ZONA_UBIGEO_DSC_XLS = clear_input($DataRow[19]);
                    $FACTURA = $DataRow[21];


                    if(strpos($FACTURA, '*') !== FALSE)
                    {
                        $explode_factura = explode('*', $FACTURA);

                        if(strtolower($explode_factura[0]) == 'f')
                        {
                            $factura_serie = $explode_factura[1];
                        }
                    }
                    

                    $NO_VENTA = array('001-00005105','001-00005108','001-00005109','001-00005119','001-00005120','001-00005121','001-00005122');
                    $bonificacion =   array(505011288,505011289,505011290,505011291,505011292,505011293,505011294,505011295,
                                            505011296,505011297,505011298,505011299,505011300,505011301,505011302,505011303,
                                            505011304,505011305,505011306,505011307,505011308,505011309,505011310,505011311,
                                            505011312,505011313,505011314,505011315,505011316,505011317,505011318,505011319,
                                            505011320,505011321,505011322,505011323,505011324,505011325,505011326,505011327,
                                            505011328,505011329,505011330,505011331);


                    if(strpos($ZONA_UBIGEO_XLS, '*') !== FALSE)
                    {
                        $explo_ubigeo = explode('*', $ZONA_UBIGEO_XLS);

                        $ubg_departamento = (int)$explo_ubigeo[0];
                        $ubg_provincia = (int)$explo_ubigeo[1];
                        $ubg_distrito = (int)$explo_ubigeo[2];

                        $ubg_loc_desc_db = search_localidad_x_ubigeo($ubg_departamento, $ubg_provincia, $ubg_distrito);

                        if($ubg_loc_desc_db == "empty")
                        {
                            $ZONA_UBIGEO = $ZONA_UBIGEO_XLS;
                            $ZONA_UBIGEO_DSC = $ZONA_UBIGEO_DSC_XLS;
                        }else
                        {
                            $ZONA_UBIGEO = zerofill($ubg_departamento, 2).zerofill($ubg_provincia, 2).zerofill($ubg_distrito, 2);
                            $ZONA_UBIGEO_DSC = strtoupper($ubg_loc_desc_db);
                        }
                    }else
                    {
                        $ZONA_UBIGEO = "ZONA_UBIGEO_XLS";
                        $ZONA_UBIGEO_DSC = "ZONA_UBIGEO_DSC_XLS";
                    }                  

                    if($ZONA_VISITA != null)
                    {
                        $ZONA_VISITA = $ZONA_VISITA;
                    }else
                    {
                        $ZONA_VISITA = 0;
                    }

                    if($COD_VENDEDOR == 810 || $COD_VENDEDOR == 632)
                    {
                        $PORTAFOLIO = "C";
                    }else
                    {
                        $PORTAFOLIO = "A";
                    }
                    #$PORTAFOLIO = "A";
                    $REGION = clear_input($DataRow[20]);#search_reg_x_zona_g($COD_ZONA_G);####CHECK

                    switch ($REGION) {
                        case '1':
                            $REGION_COD = 22;#strtolower('CASTILLO AREQUIPA');
                            break;
                        
                        case '2':
                            $REGION_COD = 2;#strtolower('DIMEXA AREQUIPA');
                            break;
                        
                        case '3':
                            $REGION_COD = 10;#strtolower('NORTE 1');
                            break;
                        
                        case '4':
                            $REGION_COD = 9;#strtolower('NORTE 2');
                            break;
                        
                        case '5':
                            $REGION_COD = 11;#strtolower('SIERRA CENTRAL');
                            break;
                        
                        case '6':
                            $REGION_COD = 3;#strtolower('NORTE SUR CHICO');
                            break;
                        
                        case '7':
                            $REGION_COD = 7;#strtolower('MAYORISTA');
                            break;

                        case '8':
                            $REGION_COD = 1;#strtolower('LIMA');
                            break;

                        case '13':
                            $REGION_COD = 13;#strtolower('LIMA');
                            break;

                        default:
                            # code...
                            break;
                    }              

                    $REGION_COD_2 = $REGION_COD;

                    if($COD_VENDEDOR == 760)
                    {
                        $COD_VENDEDOR = 75;
                        $NAME_VENDEDOR = 'SANCHEZ ARIAS MARCO';
                    }else if($COD_VENDEDOR == 51)
                    {
                        $COD_VENDEDOR = 130;
                        $NAME_VENDEDOR = 'RUBI CISNEROS';
                    }
                    
                    if($COD_VENDEDOR == 810 || $COD_VENDEDOR == 632 || $COD_VENDEDOR == 218)
                    {
                        $REGION_COD = 99;
                    }else
                    {
                        $REGION_COD = $REGION_COD;
                    }
                    if(empty($RUC_CLIENTE))
                    {
                        $RUC_CLIENTE = 10000000001;
                    }else
                    {
                        $RUC_CLIENTE = $RUC_CLIENTE;
                    }

                    if(!in_array($factura_serie, $NO_VENTA))
                    {
                        if(!in_array($COD_PRODUCTO, $bonificacion))
                        {
                            $InsertDetalle = $this->db->prepare("INSERT INTO tbl_drenaje_ventas(drenaje_repre_cod, drenaje_repre_name, drenaje_dist_cod,
                                                                                            drenaje_dist_name, drenaje_fecha, drenaje_portafolio, drenaje_zona_cod, drenaje_zona_desc, drenaje_cliente_cod,
                                                                                            drenaje_cliente_ruc, drenaje_cliente_name, drenaje_prod_cod, drenaje_prod_name, drenaje_cantidad,
                                                                                            drenaje_valor, drenaje_sub_zona, drenaje_zona_g, drenaje_region, drenaje_region_2, drenaje_zona_visita,
                                                                                            drenaje_ubigeo, drenaje_ubigeo_desc, drenaje_periodo)
                                                                                         VALUES(:COD_VENDEDOR, :NAME_VENDEDOR, :COD_DISTRIBUIDORA, :NAME_DISTRIBUIDORA, :FECHA, 
                                                                                         :PORTAFOLIO, :COD_SUBZONA, :ZONA, :COD_CLIENTE, :RUC_CLIENTE, :NAME_CLIENTE, :COD_PRODUCTO, :NAME_PRODUCTO, 
                                                                                         :CANTIDAD, :VALOR, :DISTRITOS, :COD_ZONA_G, :REGION_COD, :REGION_COD_2, :ZONA_VISITA, :ZONA_UBIGEO, 
                                                                                         :ZONA_UBIGEO_DSC, :PERIODO)");
                        
                            $InsertDetalle->bindParam(":COD_VENDEDOR", $COD_VENDEDOR);
                            $InsertDetalle->bindParam(":NAME_VENDEDOR", $NAME_VENDEDOR);
                            $InsertDetalle->bindParam(":COD_DISTRIBUIDORA", $COD_DISTRIBUIDORA);
                            $InsertDetalle->bindParam(":NAME_DISTRIBUIDORA", $NAME_DISTRIBUIDORA);
                            $InsertDetalle->bindParam(":FECHA", $FECHA);
                            $InsertDetalle->bindParam(":PORTAFOLIO", $PORTAFOLIO);
                            $InsertDetalle->bindParam(":COD_SUBZONA", $COD_SUBZONA);
                            $InsertDetalle->bindParam(":ZONA", $ZONA);
                            $InsertDetalle->bindParam(":COD_CLIENTE", $COD_CLIENTE);
                            $InsertDetalle->bindParam(":RUC_CLIENTE", $RUC_CLIENTE);
                            $InsertDetalle->bindParam(":NAME_CLIENTE", $NAME_CLIENTE);
                            $InsertDetalle->bindParam(":COD_PRODUCTO", $COD_PRODUCTO);
                            $InsertDetalle->bindParam(":NAME_PRODUCTO", $NAME_PRODUCTO);
                            $InsertDetalle->bindParam(":CANTIDAD", $CANTIDAD);
                            $InsertDetalle->bindParam(":VALOR", $VALOR);
                            $InsertDetalle->bindParam(":DISTRITOS", $DISTRITOS);
                            $InsertDetalle->bindParam(":COD_ZONA_G", $COD_ZONA_G);
                            $InsertDetalle->bindParam(":REGION_COD", $REGION_COD);
                            $InsertDetalle->bindParam(":REGION_COD_2", $REGION_COD_2);
                            $InsertDetalle->bindParam(":ZONA_VISITA", $ZONA_VISITA);
                            $InsertDetalle->bindParam(":ZONA_UBIGEO", $ZONA_UBIGEO);
                            $InsertDetalle->bindParam(":ZONA_UBIGEO_DSC", $ZONA_UBIGEO_DSC);
                            $InsertDetalle->bindParam(":PERIODO", $PERIODO);

                            if($InsertDetalle->execute())
                            {
                                // print " {insert Data - ok} <br>";
                                print " {insert : ".$FECHA." - NAME_VENDEDOR = ".$NAME_VENDEDOR ." - ". $RUC_CLIENTE ." - ". $COD_PRODUCTO." - " .$REGION_COD. "}<br>";
                            }else
                            {
                                print "Error => " . $COD_VENDEDOR . ' - ' .$RUC_CLIENTE . '- ' .$CANTIDAD;
                                print "<br>".errorPDO($InsertDetalle).'<br>';
                            }
                        }else
                        {
                                print " {NO ENTRA : ".$FECHA." - NAME_VENDEDOR = ".$NAME_VENDEDOR ." - ". $RUC_CLIENTE ." - ". $COD_PRODUCTO." -cant :  " .$CANTIDAD. " - valor: " .$VALOR. "}-COD_PRODUCTO<br>";
                        }
                    }else
                    {
                        print " {NO ENTRA : ".$FECHA." - NAME_VENDEDOR = ".$NAME_VENDEDOR ." - ". $RUC_CLIENTE ." - ". $COD_PRODUCTO." - " .$factura_serie. "}<br>";
                    }
                }
            } else 
            {
                print "{ Debug : Error DELETE => " . errorPDO($DeleteData) . " } <br/>";
            }
        }
        catch (PDOException $e) 
        {
            print $e->getMessage();
        }
    }
    public function load_cuota_producto($file_excel, $periodo)
    {      
        try 
        {
            $DeleteData = $this->db->prepare("TRUNCATE TABLE tbl_cuota_producto");
            // $DeleteData->bindParam(":periodo", $periodo, PDO::PARAM_INT);
            
            if ($DeleteData->execute()) 
            {
                $inputFileName = $file_excel;
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
                    
                    $prod_cod = $DataRow[0];
                    $prod_desc = $DataRow[1];
                    $prod_region = $DataRow[4];
                    $prod_zona = $DataRow[2];
                    $prod_cod_repre = $DataRow[3];                  
                    $prod_categoria = $DataRow[6];
                    $prod_sub_cat = $DataRow[5];
                    $prod_cuota_uni = $DataRow[7];
                    $prod_cuota_val = $DataRow[8];
                    $prod_periodo = $DataRow[9];
                   

                    $InsertDetalle = $this->db->prepare("INSERT INTO tbl_cuota_producto(prod_cod, prod_desc, prod_region, prod_zona,
                                                                prod_cod_repre, prod_categoria, prod_sub_cat, prod_cuota_uni, prod_cuota_val, prod_periodo)
                                                             VALUES(:prod_cod, :prod_desc, :prod_region, :prod_zona,
                                                                :prod_cod_repre, :prod_categoria, :prod_sub_cat, :prod_cuota_uni, :prod_cuota_val, :prod_periodo)");
                    
                    $InsertDetalle->bindParam(":prod_cod", $prod_cod);
                    $InsertDetalle->bindParam(":prod_desc", $prod_desc);
                    $InsertDetalle->bindParam(":prod_region", $prod_region);
                    $InsertDetalle->bindParam(":prod_zona", $prod_zona);
                    $InsertDetalle->bindParam(":prod_cod_repre", $prod_cod_repre);
                    $InsertDetalle->bindParam(":prod_categoria", $prod_categoria);
                    $InsertDetalle->bindParam(":prod_sub_cat", $prod_sub_cat);
                    $InsertDetalle->bindParam(":prod_cuota_uni", $prod_cuota_uni);
                    $InsertDetalle->bindParam(":prod_cuota_val", $prod_cuota_val);
                    $InsertDetalle->bindParam(":prod_periodo", $prod_periodo);

                    if($InsertDetalle->execute())
                    {
                        print " {insert Data - ok} <br>";
                    }else
                    {
                        print "Error => " . $prod_cod . ' - ' .$prod_desc . '- ' .$prod_periodo;
                        print "<br>".errorPDO($InsertDetalle).'<br>';
                    }
                }
            } else 
            {
                print "{ Debug : Error DELETE => " . errorPDO($DeleteData) . " } <br/>";
            }
        }
        catch (PDOException $e) 
        {
            print $e->getMessage();
        }
    }
    public function generar_reporte_venta($periodo)
    {
        $output = null;

        $truncate = $this->db->prepare("DELETE FROM reporte_drenaje_ventas WHERE rv_periodo = :periodo");
        $truncate->bindParam(':periodo', $periodo);
        if($truncate->execute())
        {
            $regiones = $this->db->prepare("SELECT 
                                            region_codigo2
                                        FROM
                                            tbl_maestro_regiones
                                        WHERE
                                            region_codigo2 != 99 ORDER BY 1 ASC;");
            if($regiones->execute())
            {
                if($regiones->rowCount() > 0)
                {
                    while ($regiones_r = $regiones->fetch(PDO::FETCH_ASSOC))
                    {
                        $region_cod = $regiones_r['region_codigo2'];

                        $periodos = $this->db->prepare("SELECT DISTINCT
                                                            drenaje_periodo
                                                        FROM
                                                            tbl_drenaje_ventas
                                                        WHERE
                                                            drenaje_periodo = :periodo");
                        $periodos->bindParam(':periodo', $periodo);
                        if($periodos->execute())
                        {
                            if($periodos->rowCount() > 0)
                            {
                                while ($periodos_r = $periodos->fetch(PDO::FETCH_ASSOC))
                                {
                                    $periodo = $periodos_r['drenaje_periodo'];

                                    $portafolios = $this->db->prepare("SELECT DISTINCT
                                                                            drenaje_portafolio
                                                                        FROM
                                                                            tbl_drenaje_ventas
                                                                        WHERE
                                                                            drenaje_periodo = :periodo
                                                                            AND drenaje_region_2 = :region_cod");
                                                                            
                                    $portafolios->bindParam(":periodo", $periodo, PDO::PARAM_INT);
                                    $portafolios->bindParam(":region_cod", $region_cod, PDO::PARAM_INT);
                                    if($portafolios->execute())
                                    {
                                        if($portafolios->rowCount() > 0)
                                        {
                                            while($portafolios_r = $portafolios->fetch(PDO::FETCH_ASSOC))
                                            {
                                                $portafolio = $portafolios_r['drenaje_portafolio'];

                                                $vendedores =$this->db->prepare("SELECT DISTINCT
                                                                                        drenaje_repre_cod
                                                                                    FROM
                                                                                        tbl_drenaje_ventas
                                                                                    WHERE
                                                                                        drenaje_periodo = :periodo
                                                                                            AND drenaje_region_2 = :region_cod
                                                                                            AND drenaje_portafolio = :portafolio;");
                                                $vendedores->bindParam(":periodo", $periodo, PDO::PARAM_INT);
                                                $vendedores->bindParam(":region_cod", $region_cod, PDO::PARAM_INT);
                                                $vendedores->bindParam(":portafolio", $portafolio, PDO::PARAM_STR);
                                                if($vendedores->execute())
                                                {
                                                    if($vendedores->rowCount() > 0)
                                                    {
                                                        while($vendedores_r = $vendedores->fetch(PDO::FETCH_ASSOC))
                                                        {
                                                            $vendedor_cod = $vendedores_r['drenaje_repre_cod'];

                                                            // if($COD_VENDEDOR == 790)
                                                            // {
                                                            //     $COD_VENDEDOR = 75;
                                                            //     $NAME_VENDEDOR = 'SANCHEZ ARIAS MARCO';
                                                            // }

                                                            $drenaje1 = $this->db->prepare("SELECT 
                                                                                                SUM(drenaje_valor) AS valor_ventas,
                                                                                                COUNT(DISTINCT drenaje_cliente_cod) AS cantidad_clientes,
                                                                                                drenaje_repre_name AS repre_nombre,
                                                                                                drenaje_zona_g AS zona,
                                                                                                drenaje_portafolio AS portafolio
                                                                                            FROM
                                                                                                tbl_drenaje_ventas
                                                                                            WHERE
                                                                                                drenaje_periodo = :periodo
                                                                                                AND drenaje_region_2 = :region_cod
                                                                                                AND drenaje_portafolio = :portafolio
                                                                                                AND drenaje_repre_cod = :vendedor_cod;");
                                                            $drenaje1->bindParam(":periodo", $periodo, PDO::PARAM_INT);
                                                            $drenaje1->bindParam(":region_cod", $region_cod, PDO::PARAM_INT);
                                                            $drenaje1->bindParam(":portafolio", $portafolio, PDO::PARAM_STR);
                                                            $drenaje1->bindParam(":vendedor_cod", $vendedor_cod, PDO::PARAM_INT);
                                                            if($drenaje1->execute())
                                                            {
                                                                if($drenaje1->rowCount() > 0)
                                                                {
                                                                    $drenaje1_r = $drenaje1->fetch(PDO::FETCH_ASSOC);

                                                                    $rv_region = $region_cod;
                                                                    
                                                                    if($vendedor_cod == 810 || $vendedor_cod == 218 || $vendedor_cod == 632)
                                                                    {
                                                                        $rv_region_2 = 99;
                                                                    }else
                                                                    {
                                                                        $rv_region_2 = $rv_region;
                                                                    }
                                                                    $rv_zona = $drenaje1_r['zona'];
                                                                    $rv_vendedor_codigo = $vendedor_cod;
                                                                    $rv_vendedor_nombre = $drenaje1_r['repre_nombre'];
                                                                    if($vendedor_cod == 75)
                                                                    {
                                                                        $crp = 760;
                                                                        $rv_cuota = cuota_representante_zona_region($periodo, $region_cod, $crp);
                                                                        if((int)$rv_cuota == 0)
                                                                        {
                                                                            $crp = 75;
                                                                            $rv_cuota = cuota_representante_zona_region($periodo, $region_cod, $crp);
                                                                        }
                                                                    }else
                                                                    {
                                                                        $rv_cuota = cuota_representante_zona_region($periodo, $region_cod, $vendedor_cod);
                                                                    }
                                                                    
                                                                    $rv_valor = $drenaje1_r['valor_ventas'];
                                                                    $rv_cliente_portafolio = 0;#FUNCION DE CARTERA CLIENTE
                                                                    $rv_cliente_ventas = $drenaje1_r['cantidad_clientes'];
                                                                    $rv_periodo = $periodo;
                                                                    $rv_vendedor_portafolio = $portafolio;

                                                                    
                                                                    $distribuidoras = $this->db->prepare("SELECT DISTINCT
                                                                                                                drenaje_dist_cod AS distribuidora_codigo,
                                                                                                                drenaje_dist_name AS distribuidora_nombre
                                                                                                            FROM
                                                                                                                tbl_drenaje_ventas
                                                                                                            WHERE
                                                                                                            drenaje_periodo = :periodo
                                                                                                            AND drenaje_region_2 = :region_cod
                                                                                                            ORDER BY 1;");
                                                                                                            // AND drenaje_portafolio = :portafolio
                                                                                                            // AND drenaje_repre_cod = :vendedor_cod
                                                                    
                                                                    $distribuidoras->bindParam(":periodo", $periodo, PDO::PARAM_INT);
                                                                    $distribuidoras->bindParam(":region_cod", $region_cod, PDO::PARAM_INT);
                                                                    // $distribuidoras->bindParam(":portafolio", $portafolio, PDO::PARAM_STR);
                                                                    // $distribuidoras->bindParam(":vendedor_cod", $vendedor_cod, PDO::PARAM_INT);
                                                                    if($distribuidoras->execute())
                                                                    {
                                                                        if($distribuidoras->rowCount() > 0)
                                                                        {
                                                                            $distribuidoras_array_mame = null;
                                                                            $distribuidoras_array_valores = null;
                                                                            while ($distribuidoras_r = $distribuidoras->fetch(PDO::FETCH_ASSOC))
                                                                            {
                                                                                $distribuidora_codigo = $distribuidoras_r['distribuidora_codigo'];
                                                                                $distribuidora_nombre = $distribuidoras_r['distribuidora_nombre'];
                                                                                
                                                                                // if($distribuidora_codigo == 8 || $distribuidora_codigo == 9)
                                                                                // {
                                                                                //     $distribuidora_codigo = 4;
                                                                                //     $distribuidora_nombre = 'Dimexa';
                                                                                // }else
                                                                                // {
                                                                                //     $distribuidora_codigo = $distribuidora_codigo;
                                                                                //     $distribuidora_nombre = $distribuidora_nombre;
                                                                                // }
                                                                                

                                                                                $distribuidoras_array_mame .= $distribuidora_codigo.',';

                                                                                $valorxdistribuidora = $this->db->prepare("SELECT 
                                                                                                                                COALESCE(SUM(drenaje_valor),0) AS valor_distribuidora
                                                                                                                            FROM
                                                                                                                                tbl_drenaje_ventas
                                                                                                                            WHERE
                                                                                                                                drenaje_periodo = :periodo
                                                                                                                                AND drenaje_region_2 = :region_cod
                                                                                                                                AND drenaje_portafolio = :portafolio
                                                                                                                                AND drenaje_repre_cod = :vendedor_cod
                                                                                                                                AND drenaje_dist_cod = :distribuidora_codigo;");
                                                                                $valorxdistribuidora->bindParam(":periodo", $periodo, PDO::PARAM_INT);
                                                                                $valorxdistribuidora->bindParam(":region_cod", $region_cod, PDO::PARAM_INT);
                                                                                $valorxdistribuidora->bindParam(":portafolio", $portafolio, PDO::PARAM_STR);
                                                                                $valorxdistribuidora->bindParam(":vendedor_cod", $vendedor_cod, PDO::PARAM_INT);
                                                                                $valorxdistribuidora->bindParam(":distribuidora_codigo", $distribuidora_codigo, PDO::PARAM_INT);
                                                                                if($valorxdistribuidora->execute())
                                                                                {
                                                                                    if($valorxdistribuidora->rowCount() > 0)
                                                                                    {
                                                                                        $valorxdistribuidora_r = $valorxdistribuidora->fetch(PDO::FETCH_ASSOC);

                                                                                        $distribuidoras_array_valores .= $valorxdistribuidora_r['valor_distribuidora'].',';#$distribuidora_codigo.'~'.
                                                                                    }else
                                                                                    {
                                                                                        $output = "ZERO valorxdistribuidora_r";
                                                                                    }
                                                                                }else
                                                                                {
                                                                                    $output = errorPDO($valorxdistribuidora);
                                                                                }
                                                                            }
                                                                        }else
                                                                        {
                                                                            $output = "ZERO distribuidoras";
                                                                        }
                                                                    }else
                                                                    {
                                                                        $output = errorPDO($distribuidoras);
                                                                    }

                                                                    $rv_distribuidoras_nombres = trim($distribuidoras_array_mame, ',');
                                                                    $rv_distribuidoras_valores = trim($distribuidoras_array_valores, ',');


                                                                    // $output .= 
                                                                    // "<br><br><br> - rv_region = " . $rv_region .
                                                                    // "<br> - rv_zona = " . $rv_zona .
                                                                    // "<br> - rv_vendedor_codigo = " . $rv_vendedor_codigo .
                                                                    // "<br> - rv_vendedor_nombre = " . $rv_vendedor_nombre .
                                                                    // "<br> - rv_distribuidoras_nombres = " . $rv_distribuidoras_nombres .
                                                                    // "<br> - rv_distribuidoras_valores = " . $rv_distribuidoras_valores .
                                                                    // "<br> - rv_cuota = " . $rv_cuota .
                                                                    // "<br> - rv_valor = " . $rv_valor .
                                                                    // "<br> - rv_cliente_portafolio = " . $rv_cliente_portafolio .
                                                                    // "<br> - rv_cliente_ventas = " . $rv_cliente_ventas .
                                                                    // "<br> - rv_periodo = " . $rv_periodo;

                                                                    $insert = $this->db->prepare("INSERT INTO reporte_drenaje_ventas(rv_region, rv_region_2, rv_zona, rv_vendedor_codigo, 
                                                                                                    rv_vendedor_nombre, rv_vendedor_portafolio, rv_distribuidoras_nombres, rv_distribuidoras_valores,
                                                                                                    rv_cuota, rv_valor, rv_cliente_portafolio, rv_cliente_ventas, rv_periodo)
                                                                                                    VALUES(:rv_region, :rv_region_2, :rv_zona, :rv_vendedor_codigo, 
                                                                                                    :rv_vendedor_nombre, :rv_vendedor_portafolio, :rv_distribuidoras_nombres, :rv_distribuidoras_valores,
                                                                                                    :rv_cuota, :rv_valor, :rv_cliente_portafolio, :rv_cliente_ventas, :rv_periodo)");
                                                                    $insert->bindParam(":rv_region", $rv_region, PDO::PARAM_INT);
                                                                    $insert->bindParam(":rv_region_2", $rv_region_2, PDO::PARAM_INT);
                                                                    $insert->bindParam(":rv_zona", $rv_zona, PDO::PARAM_INT);
                                                                    $insert->bindParam(":rv_vendedor_codigo", $rv_vendedor_codigo, PDO::PARAM_INT);
                                                                    $insert->bindParam(":rv_vendedor_nombre", $rv_vendedor_nombre, PDO::PARAM_STR);
                                                                    $insert->bindParam(":rv_vendedor_portafolio", $rv_vendedor_portafolio, PDO::PARAM_STR);
                                                                    $insert->bindParam(":rv_distribuidoras_nombres", $rv_distribuidoras_nombres, PDO::PARAM_STR);
                                                                    $insert->bindParam(":rv_distribuidoras_valores", $rv_distribuidoras_valores, PDO::PARAM_STR);
                                                                    $insert->bindParam(":rv_cuota", $rv_cuota, PDO::PARAM_INT);
                                                                    $insert->bindParam(":rv_valor", $rv_valor, PDO::PARAM_INT);
                                                                    $insert->bindParam(":rv_cliente_portafolio", $rv_cliente_portafolio, PDO::PARAM_STR);
                                                                    $insert->bindParam(":rv_cliente_ventas", $rv_cliente_ventas, PDO::PARAM_INT);
                                                                    $insert->bindParam(":rv_periodo", $rv_periodo, PDO::PARAM_INT);
                                                                    if($insert->execute())
                                                                    {
                                                                        $output .= "ingresado...<br>";
                                                                    }else
                                                                    {
                                                                        $output .= errorPDO($insert)."<br>";
                                                                    }
                                                                    
                                                                }else
                                                                {
                                                                    $output = "ZERO drenaje1"; 
                                                                }
                                                            }else
                                                            {
                                                                $output = errorPDO($drenaje1);
                                                            }
                                                        }
                                                    }else
                                                    {
                                                        $output = "ZERO vendedores";
                                                    }
                                                }else
                                                {
                                                    $output = errorPDO($vendedores);
                                                }
                                            }
                                        }else
                                        {
                                            $output = "ZERO portafolios";
                                        }
                                    }else
                                    {
                                        $output = errorPDO($portafolios);
                                    }
                                }
                            }else
                            {
                                $output = "ZERO PERIODOS";
                            }
                        }else
                        {
                            $output = errorPDO($periodos);
                        }
                    }
                }else
                {
                    $output = "ZERO REGIONES";
                }
            }else
            {
                $output = errorPDO($regiones);
            }
        }else
        {
            $output = errorPDO($truncate);
        }
        
        return $output;
    }
    public function sierra_central_proceso($periodo)
    {
        $output = null;

        $periodos = $this->db->prepare("SELECT DISTINCT
                                            drenaje_periodo
                                        FROM
                                            tbl_drenaje_ventas WHERE drenaje_periodo = :periodo");
        $periodos->bindParam(':periodo', $periodo);
        if($periodos->execute())
        {
            if($periodos->rowCount() > 0)
            {
                while ($periodos_r = $periodos->fetch(PDO::FETCH_ASSOC))
                {
                    $periodo = $periodos_r['drenaje_periodo'];

                    $select = $this->db->prepare("SELECT 
                                            rv_region,
                                            rv_region_2,
                                            rv_zona,
                                            rv_vendedor_codigo,
                                            rv_vendedor_nombre,
                                            rv_vendedor_portafolio,
                                            rv_distribuidoras_nombres,
                                            rv_distribuidoras_valores,
                                            rv_cuota,
                                            rv_valor,
                                            rv_cliente_portafolio,
                                            rv_cliente_ventas,
                                            rv_periodo
                                        FROM
                                            reporte_drenaje_ventas
                                            WHERE rv_periodo = :periodo
                                            AND rv_region = 11 
                                            AND rv_zona = 43
                                            AND rv_vendedor_codigo = 771;");
                    $select->bindParam(":periodo", $periodo, PDO::PARAM_INT);
                    if($select->execute())
                    {
                        if($select->rowCount() > 0)
                        {   
                            $arrayValores = null;
                            
                            
                            $select_r = $select->fetch(PDO::FETCH_ASSOC);
                            
                            $distribuidoras__ = $select_r['rv_distribuidoras_nombres'];
                            $rv_vendedor_portafolio = $select_r['rv_vendedor_portafolio'];
                            @$cliente_portafolio = ($select_r['rv_cliente_portafolio']/3);
                            @$cliente_ventas = ($select_r['rv_cliente_ventas']/3);

                            $valor_dis = $select_r['rv_distribuidoras_valores'];

                            @$valor = ($select_r['rv_valor']/3);
                                                        
                            if(strpos($valor_dis , ",") !== FALSE)
                            {
                                $explode_valor = explode(",", $valor_dis);

                                for($i = 0; $i <= count($explode_valor)-1; $i++)
                                {
                                    #$explode_deli = $explode_valor[$i];#explode("~", $explode_valor[$i]);


                                    @$division = ((int)$explode_valor[$i]/3);
                                    #$arrayValores .= 'ENCONTRADO,';
                                    $arrayValores .= round($division, 2).',';
                                }
                            }else
                            {
                                @$division = ($valor_dis/3);
                                $arrayValores = round($division, 2);
                            }

                                $rv_region = 11;
                                $rv_zona = 43;
                                $rv_region_2 = $select_r['rv_region_2'];
                                
                                $rv_distribuidoras_nombres = $distribuidoras__;
                                $rv_distribuidoras_valores = trim($arrayValores, ",");

                                $rv_vendedor_codigo1 = 40;
                                $rv_vendedor_nombre1 = "VACANTE-SIERRA CENTRAL~A";
                                $rv_cuota1 = cuota_representante_zona_region($periodo, $rv_region, $rv_vendedor_codigo1);

                                $rv_vendedor_codigo2 = 758;
                                $rv_vendedor_nombre2 = "GASPAR VENTURA LUIS ALBERTO~B";
                                $rv_cuota2 = cuota_representante_zona_region($periodo, $rv_region, $rv_vendedor_codigo2);
                                
                                $rv_valor = $valor;
                                $rv_cliente_portafolio = $cliente_portafolio;
                                $rv_cliente_ventas = $cliente_ventas;
                                $rv_vendedor_portafolio = $rv_vendedor_portafolio;
                                $rv_periodo = $periodo;
                                $rv_tipo = 1;

                                $insert1 = $this->db->prepare("INSERT INTO reporte_drenaje_ventas(rv_region, rv_region_2, rv_zona, rv_vendedor_codigo, 
                                                                rv_vendedor_nombre, rv_vendedor_portafolio, rv_distribuidoras_nombres, rv_distribuidoras_valores,
                                                                rv_cuota, rv_valor, rv_cliente_portafolio, rv_cliente_ventas, rv_tipo, rv_periodo)
                                                                VALUES(:rv_region, :rv_region_2, :rv_zona, :rv_vendedor_codigo, 
                                                                :rv_vendedor_nombre, :rv_vendedor_portafolio, :rv_distribuidoras_nombres, :rv_distribuidoras_valores,
                                                                :rv_cuota, :rv_valor, :rv_cliente_portafolio, :rv_cliente_ventas, :rv_tipo, :rv_periodo)");

                                $insert1->bindParam(":rv_region", $rv_region, PDO::PARAM_INT);
                                $insert1->bindParam(":rv_region_2", $rv_region_2, PDO::PARAM_INT);
                                $insert1->bindParam(":rv_zona", $rv_zona, PDO::PARAM_INT);
                                $insert1->bindParam(":rv_vendedor_codigo", $rv_vendedor_codigo1, PDO::PARAM_INT);
                                $insert1->bindParam(":rv_vendedor_nombre", $rv_vendedor_nombre1, PDO::PARAM_STR);
                                $insert1->bindParam(":rv_vendedor_portafolio", $rv_vendedor_portafolio, PDO::PARAM_STR);
                                $insert1->bindParam(":rv_distribuidoras_nombres", $rv_distribuidoras_nombres, PDO::PARAM_STR);
                                $insert1->bindParam(":rv_distribuidoras_valores", $rv_distribuidoras_valores, PDO::PARAM_STR);
                                $insert1->bindParam(":rv_cuota", $rv_cuota1, PDO::PARAM_INT);
                                $insert1->bindParam(":rv_valor", $rv_valor, PDO::PARAM_INT);
                                $insert1->bindParam(":rv_cliente_portafolio", $rv_cliente_portafolio, PDO::PARAM_STR);
                                $insert1->bindParam(":rv_cliente_ventas", $rv_cliente_ventas, PDO::PARAM_INT);
                                $insert1->bindParam(":rv_tipo", $rv_tipo, PDO::PARAM_INT);
                                $insert1->bindParam(":rv_periodo", $rv_periodo, PDO::PARAM_INT);
                                if($insert1->execute())
                                {
                                    $output .= "ingresado...1<br>";
                                }else
                                {
                                    $output .= errorPDO($insert)."<br>";
                                }

                                $insert2 = $this->db->prepare("INSERT INTO reporte_drenaje_ventas(rv_region, rv_region_2, rv_zona, rv_vendedor_codigo, 
                                                                rv_vendedor_nombre, rv_vendedor_portafolio, rv_distribuidoras_nombres, rv_distribuidoras_valores,
                                                                rv_cuota, rv_valor, rv_cliente_portafolio, rv_cliente_ventas, rv_periodo)
                                                                VALUES(:rv_region, :rv_region_2, :rv_zona, :rv_vendedor_codigo, 
                                                                :rv_vendedor_nombre, :rv_vendedor_portafolio, :rv_distribuidoras_nombres, :rv_distribuidoras_valores,
                                                                :rv_cuota, :rv_valor, :rv_cliente_portafolio, :rv_cliente_ventas, :rv_periodo)");
                                $insert2->bindParam(":rv_region", $rv_region, PDO::PARAM_INT);
                                $insert2->bindParam(":rv_region_2", $rv_region_2, PDO::PARAM_INT);
                                $insert2->bindParam(":rv_zona", $rv_zona, PDO::PARAM_INT);
                                $insert2->bindParam(":rv_vendedor_codigo", $rv_vendedor_codigo2, PDO::PARAM_INT);
                                $insert2->bindParam(":rv_vendedor_nombre", $rv_vendedor_nombre2, PDO::PARAM_STR);
                                $insert2->bindParam(":rv_vendedor_portafolio", $rv_vendedor_portafolio, PDO::PARAM_STR);
                                $insert2->bindParam(":rv_distribuidoras_nombres", $rv_distribuidoras_nombres, PDO::PARAM_STR);
                                $insert2->bindParam(":rv_distribuidoras_valores", $rv_distribuidoras_valores, PDO::PARAM_STR);
                                $insert2->bindParam(":rv_cuota", $rv_cuota2, PDO::PARAM_INT);
                                $insert2->bindParam(":rv_valor", $rv_valor, PDO::PARAM_INT);
                                $insert2->bindParam(":rv_cliente_portafolio", $rv_cliente_portafolio, PDO::PARAM_STR);
                                $insert2->bindParam(":rv_cliente_ventas", $rv_cliente_ventas, PDO::PARAM_INT);
                                $insert2->bindParam(":rv_periodo", $rv_periodo, PDO::PARAM_INT);
                                if($insert2->execute())
                                {
                                    $output .= "ingresado...2<br>";
                                }else
                                {
                                    $output .= errorPDO($insert)."<br>";
                                }

                                $update = $this->db->prepare("UPDATE reporte_drenaje_ventas SET rv_valor = :rv_valor, 
                                                                rv_distribuidoras_valores = :rv_distribuidoras_valores,
                                                                rv_cliente_ventas = :cliente_ventas
                                                                WHERE rv_periodo = :rv_periodo 
                                                                    AND rv_vendedor_codigo = 771");
                                $update->bindParam(":rv_valor", $rv_valor, PDO::PARAM_INT); 
                                $update->bindParam(":rv_distribuidoras_valores", $rv_distribuidoras_valores, PDO::PARAM_STR);
                                $update->bindParam(":rv_periodo", $rv_periodo, PDO::PARAM_INT);
                                $update->bindParam(":cliente_ventas", $cliente_ventas, PDO::PARAM_INT);
                                $update->execute();

                        }else
                        {
                            $output = "zero select1";
                        }
                    }else
                    {
                        $output = "select = ".errorPDO($select);
                    }
                }
            }
        }
        return $output;
    }
    public function generar_reporte_visitas($periodo)
    {
        $select_reg = $this->db->prepare("SELECT 
                                                region_codigo2, region_descripcion2
                                            FROM
                                                tbl_maestro_regiones
                                            WHERE
                                                -- region_codigo2 != 99
                                                region_codigo2 = 10
                                            ORDER BY 1 ASC;");
        if($select_reg->execute())
        {
            while ($res_reg = $select_reg->fetch(PDO::FETCH_ASSOC))
            {
                $reg_cod = $res_reg['region_codigo2'];
                $reg_dsc = $res_reg['region_descripcion2'];
                $reg_cod_vis = _zona_ventas_visita($reg_cod);

                $select_dist = $this->db->prepare("SELECT DISTINCT
                                                        drenaje_dist_cod AS distribuidora_codigo,
                                                        drenaje_dist_name AS distribuidora_nombre
                                                    FROM
                                                        tbl_drenaje_ventas
                                                    WHERE
                                                        drenaje_periodo = :periodo
                                                        AND drenaje_zona_cod IN (SELECT 
                                                                zona_cod
                                                            FROM
                                                                tbl_maestro_detalle_zonas
                                                            WHERE
                                                                zona_cod_g_zona IN (SELECT 
                                                                        drenaje_zona_g
                                                                    FROM
                                                                        tbl_drenaje_ventas
                                                                    WHERE
                                                                        drenaje_periodo = :periodo
                                                                            AND drenaje_region_2 = :reg_cod
                                                                    GROUP BY 1)) 
                                                        ORDER BY 1;");
                $select_dist->bindParam(":periodo", $periodo);
                $select_dist->bindParam(":reg_cod", $reg_cod);

                if($select_dist->execute())
                {
                    while ($res_dist = $select_dist->fetch(PDO::FETCH_ASSOC))
                    {
                        $distribuidora_codigo = $res_dist['distribuidora_codigo'];
                        $distribuidora_nombre = $res_dist['distribuidora_nombre'];

                        $select_cuota = $this->db->prepare("SELECT 
                                                                cuota_zona AS zona,
                                                                cuota_region AS region,
                                                                cuota_codigo_vendedor AS vendedor,
                                                                cuota_portafolio AS portafolio,
                                                                cuota_monto AS monto
                                                            FROM
                                                                tbl_cuotas
                                                            WHERE
                                                                cuota_periodo = :periodo
                                                                AND cuota_region = :reg_cod_vis");
                        $select_cuota->bindParam(":periodo", $periodo);
                        $select_cuota->bindParam(":reg_cod_vis", $reg_cod_vis);

                        if($select_cuota->execute())
                        {
                            while ($res_cuota = $select_cuota->fetch(PDO::FETCH_ASSOC))
                            {
                                $zona = $res_cuota['zona'];
                                $region = $res_cuota['region'];
                                $vendedor = $res_cuota['vendedor'];
                                $portafolio = $res_cuota['portafolio'];
                                $monto = $res_cuota['monto'];

                                $select_zona_reg = $this->db->prepare("SELECT 
                                                                            drenaje_zona_g
                                                                        FROM
                                                                            tbl_drenaje_ventas
                                                                        WHERE
                                                                            drenaje_periodo = :periodo
                                                                                AND drenaje_region_2 = :reg_cod
                                                                            GROUP BY 1;");
                                $select_zona_reg->bindParam(":periodo", $periodo);
                                $select_zona_reg->bindParam(":reg_cod", $reg_cod);

                                if($select_zona_reg->execute())
                                {
                                    while($res_zona_g = $select_zona_reg->fetch(PDO::FETCH_ASSOC))
                                    {
                                        $drenaje_zona_g = $res_zona_g['drenaje_zona_g'];

                                        print "<br>- zona ".$zona ."- region ".$region."- vendedor ".$vendedor."- portafolio ".$portafolio."- monto ".$monto. ' - ZONA =' .$drenaje_zona_g;

                                       /* if($reg_cod_vis == 26)
                                        {
                                            if($drenaje_zona_g == 33)
                                            {

                                                $qry_norte1= $this->db->prepare("SELECT 
                                                                                    SUM(drenaje_valor) AS total
                                                                                FROM
                                                                                    tbl_drenaje_ventas
                                                                                WHERE
                                                                                    drenaje_periodo = 201805
                                                                                    AND drenaje_zona_cod IN (SELECT 
                                                                                            zona_cod
                                                                                        FROM
                                                                                            tbl_maestro_detalle_zonas
                                                                                        WHERE
                                                                                            zona_cod_g_zona = 33)
                                                                                    AND drenaje_prod_cod IN (SELECT 
                                                                                            prod_vis_cod
                                                                                        FROM
                                                                                            tbl_productos_visita
                                                                                        WHERE
                                                                                            prod_vis_portafolio IN ('A','B'))
                                                                                    AND drenaje_dist_cod = 16;");















                                            }
                                        }*/
                                    }
                                }else
                                {
                                    print "WAZA";
                                }
                            }
                        }else
                        {
                            print "WAZA";
                        }
                    }
                }else
                {
                    print "WAZA";
                }
            }
        }else
        {
            print "WAZA";
        }
    }
    public function generar_reporte_visitas_norte1($periodo)
    {
        // const $array_dist_x_reg = 0;
        $output = null;
        $rv_tipo = 2;
        $reg_cod = 10;
        $reg_cod_vis = _zona_ventas_visita($reg_cod);
        $array_dist_x_reg = _array_dist_x_reg($periodo, $reg_cod);

        /*
        print " - reg_cod_vis = " . $reg_cod_vis.'<br>'.
                                " - reg_cod_vis = " . $reg_cod_vis.'<br>'.
                                " - zona = " . $zona.'<br>'.
                                " - vendedor = " . $vendedor.'<br>'.
                                " - name_vendedor = " . $name_vendedor.'<br>'.
                                " - portafolio = " . $portafolio.'<br>'.
                                " - cod_array_dstb = " . $cod_array_dstb.'<br>'.
                                " - valores_dstb = " . $valores_dstb.'<br>'.
                                " - monto = " . $monto.'<br>'.
                                " - total_ventas = " . $total_ventas.'<br>'.
                                " - rv_cliente_portafolio = " . $rv_cliente_portafolio.'<br>'.
                                " - rv_cliente_ventas = " . $rv_cliente_ventas.'<br>'.
                                " - rv_tipo = " . $rv_tipo." - periodo = " . $periodo.'<br><br>';
        
        */
        
        $delete = $this->db->prepare("DELETE FROM reporte_drenaje_ventas 
                                        WHERE rv_periodo = :periodo AND rv_region_2 = :reg_cod_vis");
        $delete->bindParam(":periodo", $periodo);
        $delete->bindParam(":reg_cod_vis", $reg_cod_vis);
        if($delete->execute())
        {
            $select_zona_reg = $this->db->prepare("SELECT 
                                                    drenaje_zona_g
                                                FROM
                                                    tbl_drenaje_ventas
                                                WHERE
                                                    drenaje_periodo = :periodo
                                                        AND drenaje_region_2 = :reg_cod
                                                    GROUP BY 1;");
            $select_zona_reg->bindParam(":periodo", $periodo);
            $select_zona_reg->bindParam(":reg_cod", $reg_cod);

            if($select_zona_reg->execute())
            {
                while($res_zona_g = $select_zona_reg->fetch(PDO::FETCH_ASSOC))
                {
                    $drenaje_zona_g = $res_zona_g['drenaje_zona_g'];

                    if($drenaje_zona_g == 33)
                    {
                        $src_porafolio = "'A'";
                    }else if($drenaje_zona_g == 31)
                    {
                        $src_porafolio = "'B','C'";
                    }elseif ($drenaje_zona_g == 30) 
                    {
                        $src_porafolio = "'E','F'";
                    }

                    $select_cuota = $this->db->prepare("SELECT 
                                                            cuota_zona AS zona,
                                                            cuota_region AS region,
                                                            cuota_codigo_vendedor AS vendedor,
                                                            cuota_portafolio AS portafolio,
                                                            cuota_monto AS monto
                                                        FROM
                                                            tbl_cuotas
                                                        WHERE
                                                            cuota_periodo = :periodo
                                                        AND cuota_region = :reg_cod_vis
                                                        AND cuota_portafolio IN($src_porafolio)");
                    $select_cuota->bindParam(":periodo", $periodo);
                    $select_cuota->bindParam(":reg_cod_vis", $reg_cod_vis);
                    if($select_cuota->execute())
                    {
                        while ($res_cuota = $select_cuota->fetch(PDO::FETCH_ASSOC))
                        {
                            $total_ventas = 0;                      
                            $count_dis = 0;

                            $verificar_dist = array();
                            $array_dist = array();
                            $ventas_dist = array();
                            $array_dim = array();
                            

                            $zona = $res_cuota['zona'];
                            $region = $res_cuota['region'];
                            $vendedor = $res_cuota['vendedor'];

                            $name_vendedor = search_repre_name($vendedor);

                            if($name_vendedor == 'NoName')
                            {
                                $name_vendedor = 'VACANTE';
                            }else
                            {
                                $name_vendedor = strtoupper($name_vendedor);
                            }

                            $portafolio = $res_cuota['portafolio'];
                            $monto = $res_cuota['monto'];
                            
                            if($drenaje_zona_g == 33)
                            {
                                if($portafolio == 'A')
                                {
                                    $prod_vis = "'A','B'";
                                }
                            }elseif ($drenaje_zona_g == 31)
                            {
                                if($portafolio == 'B')
                                {
                                    $prod_vis = "'A'";
                                }elseif ($portafolio == 'C')
                                {   
                                    $prod_vis = "'B'";
                                }
                            }else if($drenaje_zona_g == 30)
                            {
                                if($portafolio == 'E')
                                {
                                    $prod_vis = "'A'";
                                }elseif ($portafolio == 'F')
                                {   
                                    $prod_vis = "'B'";
                                }
                            }
                            $select_ventas = $this->db->prepare("SELECT 
                                                                        drenaje_dist_cod,    
                                                                        SUM(drenaje_valor) AS total_x_dis
                                                                    FROM
                                                                        tbl_drenaje_ventas
                                                                    WHERE
                                                                        drenaje_periodo = :periodo
                                                                        AND drenaje_zona_cod IN (SELECT 
                                                                                zona_cod
                                                                            FROM
                                                                                tbl_maestro_detalle_zonas
                                                                            WHERE
                                                                                zona_cod_g_zona = :drenaje_zona_g)
                                                                        AND drenaje_prod_cod IN (SELECT 
                                                                                prod_vis_cod
                                                                            FROM
                                                                                tbl_productos_visita
                                                                            WHERE
                                                                                prod_vis_portafolio IN ($prod_vis))
                                                                        AND drenaje_dist_cod IN ($array_dist_x_reg)
                                                                        GROUP BY drenaje_dist_cod;");
                            $select_ventas->bindParam(':periodo', $periodo);
                            $select_ventas->bindParam(':drenaje_zona_g', $drenaje_zona_g);
                            if($select_ventas->execute())
                            {   
                                while($res_ventas = $select_ventas->fetch(PDO::FETCH_ASSOC))
                                {
                                    $venta_x_dis = trim($res_ventas['total_x_dis']);
                                    $distrb_code = trim($res_ventas['drenaje_dist_cod']);

                                    $total_ventas += $venta_x_dis;
                                    
                                    $array_dim[$distrb_code] = $venta_x_dis;

                                    $array_dist[] = $distrb_code;
                                    $ventas_dist[] = $venta_x_dis;
                                }

                                if(strpos($array_dist_x_reg, ',') !== FALSE)
                                {
                                    $all_dist_array = explode(",", $array_dist_x_reg);
                                }else
                                {
                                    $all_dist_array[] = $array_dist_x_reg;
                                }
                                    
                                $verificar_dist = array_diff($all_dist_array, $array_dist);
                                sort($verificar_dist);

                                $count_dis  = count($verificar_dist);
                                    
                                for ($i = 0; $i <= $count_dis -1; $i++)
                                {                                  
                                    $array_dim[$verificar_dist[$i]] = 0; 
                                }

                                ksort($array_dim);
                                $cod_array_dstb = implode($all_dist_array, ',');
                                $valores_dstb = implode($array_dim, ',');

                                $rv_cliente_ventas = 0;
                                $rv_cliente_portafolio = 0;

                                $insert = $this->db->prepare("INSERT INTO reporte_drenaje_ventas(rv_region, rv_region_2, rv_zona, rv_vendedor_codigo, 
                                    rv_vendedor_nombre, rv_vendedor_portafolio, rv_distribuidoras_nombres, rv_distribuidoras_valores,
                                    rv_cuota, rv_valor, rv_cliente_portafolio, rv_cliente_ventas, rv_tipo, rv_zona_view, rv_prod_port, rv_periodo)
                                    VALUES(:rv_region, :rv_region_2, :rv_zona, :rv_vendedor_codigo, 
                                    :rv_vendedor_nombre, :rv_vendedor_portafolio, :rv_distribuidoras_nombres, :rv_distribuidoras_valores,
                                    :rv_cuota, :rv_valor, :rv_cliente_portafolio, :rv_cliente_ventas, :rv_tipo, :rv_zona_view, :rv_prod_port ,:rv_periodo)");
                                $insert->bindParam(":rv_region", $reg_cod_vis);
                                $insert->bindParam(":rv_region_2", $reg_cod_vis);
                                $insert->bindParam(":rv_zona", $zona);
                                $insert->bindParam(":rv_vendedor_codigo", $vendedor);
                                $insert->bindParam(":rv_vendedor_nombre", $name_vendedor);
                                $insert->bindParam(":rv_vendedor_portafolio", $portafolio);
                                $insert->bindParam(":rv_distribuidoras_nombres", $cod_array_dstb);
                                $insert->bindParam(":rv_distribuidoras_valores", $valores_dstb);
                                $insert->bindParam(":rv_cuota", $monto);
                                $insert->bindParam(":rv_valor", $total_ventas);
                                $insert->bindParam(":rv_cliente_portafolio", $rv_cliente_portafolio);
                                $insert->bindParam(":rv_cliente_ventas", $rv_cliente_ventas);
                                $insert->bindParam(":rv_tipo", $rv_tipo);
                                $insert->bindParam(":rv_zona_view", $drenaje_zona_g);
                                $insert->bindParam(":rv_prod_port", $prod_vis);
                                $insert->bindParam(":rv_periodo", $periodo);

                                if($insert->execute())
                                {
                                    $output .= "ingresado...<br>";
                                }else
                                {
                                    $output .= " INSERT_ = ".errorPDO($insert)."<br>";
                                }
                            }else
                            {
                                $output .= " VENTAS = ".errorPDO($select_ventas).'<br>';
                            }
                        }
                    }else
                    {
                        $output .= " CUOTA = ".errorPDO($select_cuota).'<br>';
                    }
                }
            }else
            {
                $output .= " ZONA_REG = ".errorPDO($select_zona_reg).'<br>';
            }
        }else
        {
            $output .= " DELETE_ = ".errorPDO($delete).'<br>';
        }
        return $output;
    }
    public function generar_reporte_visitas_norte2($periodo)
    {
        // const $array_dist_x_reg = 0;
        $output = null;
        $rv_tipo = 2;
        $reg_cod = 9;
        $reg_cod_vis = _zona_ventas_visita($reg_cod);
        $array_dist_x_reg = _array_dist_x_reg($periodo, $reg_cod);
        
        $delete = $this->db->prepare("DELETE FROM reporte_drenaje_ventas 
                                        WHERE rv_periodo = :periodo AND rv_region_2 = :reg_cod_vis");
        $delete->bindParam(":periodo", $periodo);
        $delete->bindParam(":reg_cod_vis", $reg_cod_vis);
        if($delete->execute())
        {
            $select_zona_reg = $this->db->prepare("SELECT 
                                                    drenaje_zona_g
                                                FROM
                                                    tbl_drenaje_ventas
                                                WHERE
                                                    drenaje_periodo = :periodo
                                                        AND drenaje_region_2 = :reg_cod
                                                    GROUP BY 1;");
            $select_zona_reg->bindParam(":periodo", $periodo);
            $select_zona_reg->bindParam(":reg_cod", $reg_cod);

            if($select_zona_reg->execute())
            {
                while($res_zona_g = $select_zona_reg->fetch(PDO::FETCH_ASSOC))
                {
                    $drenaje_zona_g = $res_zona_g['drenaje_zona_g'];

                    if($drenaje_zona_g == 29)
                    {
                        $src_porafolio = "'A'";
                    }else if($drenaje_zona_g == 26)
                    {
                        $src_porafolio = "'B'";
                    }elseif ($drenaje_zona_g == 28) 
                    {
                        $src_porafolio = "'D'";
                    }elseif ($drenaje_zona_g == 32) 
                    {
                        $src_porafolio = "'C'";
                    }

                    $select_cuota = $this->db->prepare("SELECT 
                                                            cuota_zona AS zona,
                                                            cuota_region AS region,
                                                            cuota_codigo_vendedor AS vendedor,
                                                            cuota_portafolio AS portafolio,
                                                            cuota_monto AS monto
                                                        FROM
                                                            tbl_cuotas
                                                        WHERE
                                                            cuota_periodo = :periodo
                                                        AND cuota_region = :reg_cod_vis
                                                        AND cuota_portafolio IN($src_porafolio)");
                    $select_cuota->bindParam(":periodo", $periodo);
                    $select_cuota->bindParam(":reg_cod_vis", $reg_cod_vis);
                    if($select_cuota->execute())
                    {
                        while ($res_cuota = $select_cuota->fetch(PDO::FETCH_ASSOC))
                        {
                            $total_ventas = 0;                      
                            $count_dis = 0;

                            $verificar_dist = array();
                            $array_dist = array();
                            $ventas_dist = array();
                            $array_dim = array();
                            

                            $zona = $res_cuota['zona'];
                            $region = $res_cuota['region'];
                            $vendedor = $res_cuota['vendedor'];

                            $name_vendedor = search_repre_name($vendedor);

                            if($name_vendedor == 'NoName')
                            {
                                $name_vendedor = 'VACANTE';
                            }else
                            {
                                $name_vendedor = strtoupper($name_vendedor);
                            }

                            $portafolio = $res_cuota['portafolio'];
                            $monto = $res_cuota['monto'];
                            
                            if($drenaje_zona_g == 29)
                            {
                                if($portafolio == 'A')
                                {
                                    $prod_vis = "'A','B'";
                                }
                            }elseif ($drenaje_zona_g == 26)
                            {
                                if($portafolio == 'B')
                                {
                                    $prod_vis = "'A','B'";
                                }
                            }else if($drenaje_zona_g == 28)
                            {
                                if($portafolio == 'D')
                                {
                                    $prod_vis = "'A','B'";
                                }
                            }else if($drenaje_zona_g == 32)
                            {
                                if($portafolio == 'C')
                                {
                                    $prod_vis = "'A','B'";
                                }
                            }
                            $select_ventas = $this->db->prepare("SELECT 
                                                                        drenaje_dist_cod,    
                                                                        SUM(drenaje_valor) AS total_x_dis
                                                                    FROM
                                                                        tbl_drenaje_ventas
                                                                    WHERE
                                                                        drenaje_periodo = :periodo
                                                                        AND drenaje_zona_cod IN (SELECT 
                                                                                zona_cod
                                                                            FROM
                                                                                tbl_maestro_detalle_zonas
                                                                            WHERE
                                                                                zona_cod_g_zona = :drenaje_zona_g)
                                                                        AND drenaje_prod_cod IN (SELECT 
                                                                                prod_vis_cod
                                                                            FROM
                                                                                tbl_productos_visita
                                                                            WHERE
                                                                                prod_vis_portafolio IN ($prod_vis))
                                                                        AND drenaje_dist_cod IN ($array_dist_x_reg)
                                                                        GROUP BY drenaje_dist_cod;");
                            $select_ventas->bindParam(':periodo', $periodo);
                            $select_ventas->bindParam(':drenaje_zona_g', $drenaje_zona_g);
                            if($select_ventas->execute())
                            {   
                                while($res_ventas = $select_ventas->fetch(PDO::FETCH_ASSOC))
                                {
                                    $venta_x_dis = trim($res_ventas['total_x_dis']);
                                    $distrb_code = trim($res_ventas['drenaje_dist_cod']);

                                    $total_ventas += $venta_x_dis;
                                    
                                    $array_dim[$distrb_code] = $venta_x_dis;

                                    $array_dist[] = $distrb_code;
                                    $ventas_dist[] = $venta_x_dis;
                                }

                                if(strpos($array_dist_x_reg, ',') !== FALSE)
                                {
                                    $all_dist_array = explode(",", $array_dist_x_reg);
                                }else
                                {
                                    $all_dist_array[] = $array_dist_x_reg;
                                }
                                    
                                $verificar_dist = array_diff($all_dist_array, $array_dist);
                                sort($verificar_dist);

                                $count_dis  = count($verificar_dist);
                                    
                                for ($i = 0; $i <= $count_dis -1; $i++)
                                {                                  
                                    $array_dim[$verificar_dist[$i]] = 0; 
                                }

                                ksort($array_dim);
                                $cod_array_dstb = implode($all_dist_array, ',');
                                $valores_dstb = implode($array_dim, ',');

                                $rv_cliente_ventas = 0;
                                $rv_cliente_portafolio = 0;

                                $insert = $this->db->prepare("INSERT INTO reporte_drenaje_ventas(rv_region, rv_region_2, rv_zona, rv_vendedor_codigo, 
                                    rv_vendedor_nombre, rv_vendedor_portafolio, rv_distribuidoras_nombres, rv_distribuidoras_valores,
                                    rv_cuota, rv_valor, rv_cliente_portafolio, rv_cliente_ventas, rv_tipo, rv_zona_view, rv_prod_port, rv_periodo)
                                    VALUES(:rv_region, :rv_region_2, :rv_zona, :rv_vendedor_codigo, 
                                    :rv_vendedor_nombre, :rv_vendedor_portafolio, :rv_distribuidoras_nombres, :rv_distribuidoras_valores,
                                    :rv_cuota, :rv_valor, :rv_cliente_portafolio, :rv_cliente_ventas, :rv_tipo, :rv_zona_view, :rv_prod_port ,:rv_periodo)");
                                $insert->bindParam(":rv_region", $reg_cod_vis);
                                $insert->bindParam(":rv_region_2", $reg_cod_vis);
                                $insert->bindParam(":rv_zona", $zona);
                                $insert->bindParam(":rv_vendedor_codigo", $vendedor);
                                $insert->bindParam(":rv_vendedor_nombre", $name_vendedor);
                                $insert->bindParam(":rv_vendedor_portafolio", $portafolio);
                                $insert->bindParam(":rv_distribuidoras_nombres", $cod_array_dstb);
                                $insert->bindParam(":rv_distribuidoras_valores", $valores_dstb);
                                $insert->bindParam(":rv_cuota", $monto);
                                $insert->bindParam(":rv_valor", $total_ventas);
                                $insert->bindParam(":rv_cliente_portafolio", $rv_cliente_portafolio);
                                $insert->bindParam(":rv_cliente_ventas", $rv_cliente_ventas);
                                $insert->bindParam(":rv_tipo", $rv_tipo);
                                $insert->bindParam(":rv_zona_view", $drenaje_zona_g);
                                $insert->bindParam(":rv_prod_port", $prod_vis);
                                $insert->bindParam(":rv_periodo", $periodo);

                                if($insert->execute())
                                {
                                    $output .= "ingresado...<br>";
                                }else
                                {
                                    $output .= errorPDO($insert)."<br>";
                                }
                            }else
                            {
                                $output .= errorPDO($select_ventas);
                            }
                        }
                    }else
                    {
                        $output .= errorPDO($select_cuota);
                    }
                }
            }else
            {
                $output .= errorPDO($select_zona_reg);
            }
        }else
        {
            $output .= errorPDO($delete);
        }
        return $output;
    }
    public function generar_reporte_visitas_sc($periodo)
    {
        // const $array_dist_x_reg = 0;
        $output = null;
        $rv_tipo = 2;
        $reg_cod = 11;
        $reg_cod_vis = _zona_ventas_visita($reg_cod);
        $array_dist_x_reg = _array_dist_x_reg($periodo, $reg_cod);
        
        $delete = $this->db->prepare("DELETE FROM reporte_drenaje_ventas 
                                        WHERE rv_periodo = :periodo 
                                        AND rv_region_2 = :reg_cod_vis");

        $delete->bindParam(":periodo", $periodo);
        $delete->bindParam(":reg_cod_vis", $reg_cod_vis);
        if($delete->execute())
        {
            $select_zona_reg = $this->db->prepare("SELECT 
                                                    drenaje_zona_g
                                                FROM
                                                    tbl_drenaje_ventas
                                                WHERE
                                                    drenaje_periodo = :periodo
                                                        AND drenaje_region_2 = :reg_cod
                                                    GROUP BY 1;");
            $select_zona_reg->bindParam(":periodo", $periodo);
            $select_zona_reg->bindParam(":reg_cod", $reg_cod);

            if($select_zona_reg->execute())
            {
                $res_zona_g = $select_zona_reg->fetch(PDO::FETCH_ASSOC);
                #{
                    $drenaje_zona_g = $res_zona_g['drenaje_zona_g'];

                    if($drenaje_zona_g == 43)
                    {
                        $src_porafolio = "'A','B','C'";
                    }
                    if($drenaje_zona_g == 44)
                    {
                        $src_porafolio = "'A','B','C'";
                    }

                    $select_cuota = $this->db->prepare("SELECT 
                                                            cuota_zona AS zona,
                                                            cuota_region AS region,
                                                            cuota_codigo_vendedor AS vendedor,
                                                            cuota_portafolio AS portafolio,
                                                            cuota_monto AS monto
                                                        FROM
                                                            tbl_cuotas
                                                        WHERE
                                                            cuota_periodo = :periodo
                                                        AND cuota_region = :reg_cod_vis
                                                        AND cuota_portafolio IN($src_porafolio)");
                    $select_cuota->bindParam(":periodo", $periodo);
                    $select_cuota->bindParam(":reg_cod_vis", $reg_cod_vis);
                    if($select_cuota->execute())
                    {
                        while ($res_cuota = $select_cuota->fetch(PDO::FETCH_ASSOC))
                        {
                            $total_ventas = 0;                      
                            $count_dis = 0;

                            $verificar_dist = array();
                            $array_dist = array();
                            $ventas_dist = array();
                            $array_dim = array();

                            $zona = $res_cuota['zona'];
                            $region = $res_cuota['region'];
                            $vendedor = $res_cuota['vendedor'];

                            $name_vendedor = search_repre_name($vendedor);

                            if($name_vendedor == 'NoName')
                            {
                                $name_vendedor = 'VACANTE';
                            }else
                            {
                                $name_vendedor = strtoupper($name_vendedor);
                            }

                            $portafolio = $res_cuota['portafolio'];
                            $monto = $res_cuota['monto'];
                            
                            if($drenaje_zona_g == 43 && $portafolio == 'A')
                            {
                                $prod_vis = "'A','B'";
                                // if($portafolio == 'A')
                                // {
                                //     $prod_vis = "'A','B'";
                                // }else if($portafolio == 'B')
                                // {
                                //     $prod_vis = "'A','B'";
                                // }else if($portafolio == 'C')
                                // {
                                //     $prod_vis = "'A','B'";
                                // }
                            }else if($drenaje_zona_g == 43 && $portafolio == 'B')
                            {
                                $prod_vis = "'A','B'";
                            }
                            else if($drenaje_zona_g == 43 && $portafolio == 'C')
                            {
                                $prod_vis = "'A','B'";
                            }
                            $select_ventas = $this->db->prepare("SELECT 
                                                                        drenaje_dist_cod,    
                                                                        round(COALESCE(SUM(drenaje_valor), 0)/3,2) AS total_x_dis
                                                                    FROM
                                                                        tbl_drenaje_ventas
                                                                    WHERE
                                                                        drenaje_periodo = :periodo
                                                                        AND drenaje_zona_cod IN (SELECT 
                                                                                zona_cod
                                                                            FROM
                                                                                tbl_maestro_detalle_zonas
                                                                            WHERE
                                                                                zona_cod_g_zona = :drenaje_zona_g)
                                                                        AND drenaje_prod_cod IN (SELECT 
                                                                                prod_vis_cod
                                                                            FROM
                                                                                tbl_productos_visita
                                                                            WHERE
                                                                                prod_vis_portafolio IN ($prod_vis))
                                                                        AND drenaje_dist_cod IN ($array_dist_x_reg)
                                                                        GROUP BY drenaje_dist_cod;");
                            $select_ventas->bindParam(':periodo', $periodo);
                            $select_ventas->bindParam(':drenaje_zona_g', $drenaje_zona_g);
                            if($select_ventas->execute())
                            {   
                                while($res_ventas = $select_ventas->fetch(PDO::FETCH_ASSOC))
                                {
                                    $venta_x_dis = trim($res_ventas['total_x_dis']);
                                    $distrb_code = trim($res_ventas['drenaje_dist_cod']);

                                    $total_ventas += $venta_x_dis;
                                    
                                    $array_dim[$distrb_code] = $venta_x_dis;

                                    $array_dist[] = $distrb_code;
                                    $ventas_dist[] = $venta_x_dis;
                                }

                                if(strpos($array_dist_x_reg, ',') !== FALSE)
                                {
                                    $all_dist_array = explode(",", $array_dist_x_reg);
                                }else
                                {
                                    $all_dist_array[] = $array_dist_x_reg;
                                }
                                    
                                $verificar_dist = array_diff($all_dist_array, $array_dist);
                                sort($verificar_dist);

                                $count_dis  = count($verificar_dist);
                                    
                                for ($i = 0; $i <= $count_dis -1; $i++)
                                {                                  
                                    $array_dim[$verificar_dist[$i]] = 0; 
                                }

                                ksort($array_dim);
                                $cod_array_dstb = implode($all_dist_array, ',');
                                $valores_dstb = implode($array_dim, ',');

                                $rv_cliente_ventas = 0;
                                $rv_cliente_portafolio = 0;


                                // print "<br><br>reg_cod_vis=" .$reg_cod_vis." - reg_cod_vis=" .$reg_cod_vis." - zona=" .$zona." - vendedor=" .$vendedor." - name_vendedor=" .$name_vendedor." - portafolio=" .$portafolio." - cod_array_dstb=" .$cod_array_dstb." - valores_dstb=" .$valores_dstb." - monto=" .$monto." - total_ventas=" .$total_ventas." - rv_cliente_portafolio=" .$rv_cliente_portafolio." - rv_cliente_ventas=" .$rv_cliente_ventas." - rv_tipo=" .$rv_tipo." - periodo=" .$periodo; 
                                $insert = $this->db->prepare("INSERT INTO reporte_drenaje_ventas(rv_region, rv_region_2, rv_zona, rv_vendedor_codigo, 
                                    rv_vendedor_nombre, rv_vendedor_portafolio, rv_distribuidoras_nombres, rv_distribuidoras_valores,
                                    rv_cuota, rv_valor, rv_cliente_portafolio, rv_cliente_ventas, rv_tipo, rv_zona_view, rv_prod_port, rv_periodo)
                                    VALUES(:rv_region, :rv_region_2, :rv_zona, :rv_vendedor_codigo, 
                                    :rv_vendedor_nombre, :rv_vendedor_portafolio, :rv_distribuidoras_nombres, :rv_distribuidoras_valores,
                                    :rv_cuota, :rv_valor, :rv_cliente_portafolio, :rv_cliente_ventas, :rv_tipo, :rv_zona_view, :rv_prod_port ,:rv_periodo)");
                                $insert->bindParam(":rv_region", $reg_cod_vis);
                                $insert->bindParam(":rv_region_2", $reg_cod_vis);
                                $insert->bindParam(":rv_zona", $zona);
                                $insert->bindParam(":rv_vendedor_codigo", $vendedor);
                                $insert->bindParam(":rv_vendedor_nombre", $name_vendedor);
                                $insert->bindParam(":rv_vendedor_portafolio", $portafolio);
                                $insert->bindParam(":rv_distribuidoras_nombres", $cod_array_dstb);
                                $insert->bindParam(":rv_distribuidoras_valores", $valores_dstb);
                                $insert->bindParam(":rv_cuota", $monto);
                                $insert->bindParam(":rv_valor", $total_ventas);
                                $insert->bindParam(":rv_cliente_portafolio", $rv_cliente_portafolio);
                                $insert->bindParam(":rv_cliente_ventas", $rv_cliente_ventas);
                                $insert->bindParam(":rv_tipo", $rv_tipo);
                                $insert->bindParam(":rv_zona_view", $drenaje_zona_g);
                                $insert->bindParam(":rv_prod_port", $prod_vis);
                                $insert->bindParam(":rv_periodo", $periodo);

                                if($insert->execute())
                                {
                                    $output .= "ingresado...<br>";
                                }else
                                {
                                    $output .= errorPDO($insert)."<br>";
                                }
                            }else
                            {
                                $output .= errorPDO($select_ventas);
                            }
                        }
                    }else
                    {
                        $output .= errorPDO($select_cuota);
                    }
                #}
            }else
            {
                $output .= errorPDO($select_zona_reg);
            }
        }else
        {
            $output .= errorPDO($delete);
        }
        return $output;
    }
    public function generar_reporte_visitas_lima($periodo)##########
    {
        // const $array_dist_x_reg = 0;
        $output = null;
        $rv_tipo = 2;
        $reg_cod = 1;
        $reg_cod_vis = _zona_ventas_visita($reg_cod);
        $array_dist_x_reg = _array_dist_x_reg($periodo, $reg_cod);
        
        $delete = $this->db->prepare("DELETE FROM reporte_drenaje_ventas 
                                        WHERE rv_periodo = :periodo AND rv_region_2 = :reg_cod_vis");
        $delete->bindParam(":periodo", $periodo);
        $delete->bindParam(":reg_cod_vis", $reg_cod_vis);
        if($delete->execute())
        {
            $select_zona_reg = $this->db->prepare("SELECT 
                                                    drenaje_zona_g
                                                FROM
                                                    tbl_drenaje_ventas
                                                WHERE
                                                    drenaje_periodo = :periodo
                                                        AND drenaje_region_2 = :reg_cod
                                                    GROUP BY 1;");
            $select_zona_reg->bindParam(":periodo", $periodo);
            $select_zona_reg->bindParam(":reg_cod", $reg_cod);

            if($select_zona_reg->execute())
            {
                #while($res_zona_g = $select_zona_reg->fetch(PDO::FETCH_ASSOC))
                #{
                    $res_zona_g = $select_zona_reg->fetch(PDO::FETCH_ASSOC);
                    $drenaje_zona_g = $res_zona_g['drenaje_zona_g'];

                    // if($drenaje_zona_g == 29)
                    // {
                    //     $src_porafolio = "'A'";
                    // }else if($drenaje_zona_g == 26)
                    // {
                    //     $src_porafolio = "'B'";
                    // }elseif ($drenaje_zona_g == 28) 
                    // {
                    //     $src_porafolio = "'D'";
                    // }elseif ($drenaje_zona_g == 32) 
                    // {
                    //     $src_porafolio = "'C'";
                    // }

                    $select_cuota = $this->db->prepare("SELECT 
                                                            cuota_zona AS zona,
                                                            cuota_region AS region,
                                                            cuota_codigo_vendedor AS vendedor,
                                                            cuota_portafolio AS portafolio,
                                                            cuota_monto AS monto
                                                        FROM
                                                            tbl_cuotas
                                                        WHERE
                                                            cuota_periodo = :periodo
                                                        AND cuota_region = :reg_cod_vis");
                    $select_cuota->bindParam(":periodo", $periodo);
                    $select_cuota->bindParam(":reg_cod_vis", $reg_cod_vis);
                    if($select_cuota->execute())
                    {
                        while ($res_cuota = $select_cuota->fetch(PDO::FETCH_ASSOC))
                        {
                            $total_ventas = 0;                      
                            $count_dis = 0;

                            $verificar_dist = array();
                            $array_dist = array();
                            $ventas_dist = array();
                            $array_dim = array();
                            
                            $zona = $res_cuota['zona'];
                            $region = $res_cuota['region'];
                            $vendedor = $res_cuota['vendedor'];
                            $portafolio = $res_cuota['portafolio'];
                            $monto = $res_cuota['monto'];               

                            $name_vendedor = search_repre_name($vendedor);

                            if($name_vendedor == 'NoName')
                            {
                                $name_vendedor = 'VACANTE';
                            }else
                            {
                                $name_vendedor = strtoupper($name_vendedor);
                            }

                            
                            $prod_vis = "'$portafolio'";#condicional de mas portafolios;
                            
                            $select_ventas = $this->db->prepare("SELECT 
                                                                        drenaje_dist_cod,    
                                                                        SUM(drenaje_valor) AS total_x_dis
                                                                    FROM
                                                                        tbl_drenaje_ventas
                                                                    WHERE
                                                                        drenaje_periodo = :periodo
                                                                        AND drenaje_zona_visita = :zona
                                                                        AND drenaje_prod_cod IN (SELECT 
                                                                                prod_vis_cod
                                                                            FROM
                                                                                tbl_productos_visita
                                                                            WHERE
                                                                                prod_vis_portafolio IN ($prod_vis))
                                                                        AND drenaje_dist_cod IN ($array_dist_x_reg)
                                                                        GROUP BY drenaje_dist_cod;");
                            $select_ventas->bindParam(':periodo', $periodo);
                            $select_ventas->bindParam(':zona', $zona);
                            // $select_ventas->bindParam(':drenaje_zona_g', $drenaje_zona_g);
                            if($select_ventas->execute())
                            {   
                                while($res_ventas = $select_ventas->fetch(PDO::FETCH_ASSOC))
                                {
                                    $venta_x_dis = trim($res_ventas['total_x_dis']);
                                    $distrb_code = trim($res_ventas['drenaje_dist_cod']);

                                    $total_ventas += $venta_x_dis;
                                    
                                    $array_dim[$distrb_code] = $venta_x_dis;

                                    $array_dist[] = $distrb_code;
                                    $ventas_dist[] = $venta_x_dis;
                                }

                                if(strpos($array_dist_x_reg, ',') !== FALSE)
                                {
                                    $all_dist_array = explode(",", $array_dist_x_reg);
                                }else
                                {
                                    $all_dist_array[] = $array_dist_x_reg;
                                }
                                    
                                $verificar_dist = array_diff($all_dist_array, $array_dist);
                                sort($verificar_dist);

                                $count_dis  = count($verificar_dist);
                                    
                                for ($i = 0; $i <= $count_dis -1; $i++)
                                {                                  
                                    $array_dim[$verificar_dist[$i]] = 0; 
                                }

                                ksort($array_dim);
                                $cod_array_dstb = implode($all_dist_array, ',');
                                $valores_dstb = implode($array_dim, ',');

                                $rv_cliente_ventas = 0;
                                $rv_cliente_portafolio = 0;


                                 
                                /*print " - reg_cod_vis = " . $reg_cod_vis.'<br>'.
                                " - reg_cod_vis = " . $reg_cod_vis.'<br>'.
                                " - zona = " . $zona.'<br>'.
                                " - vendedor = " . $vendedor.'<br>'.
                                " - name_vendedor = " . $name_vendedor.'<br>'.
                                " - portafolio = " . $portafolio.'<br>'.
                                " - cod_array_dstb = " . $cod_array_dstb.'<br>'.
                                " - valores_dstb = " . $valores_dstb.'<br>'.
                                " - monto = " . $monto.'<br>'.
                                " - total_ventas = " . $total_ventas.'<br>'.
                                " - rv_cliente_portafolio = " . $rv_cliente_portafolio.'<br>'.
                                " - rv_cliente_ventas = " . $rv_cliente_ventas.'<br>'.
                                " - rv_tipo = " . $rv_tipo." - periodo = " . $periodo.'<br><br>';*/
        
                                $portafolio_vendedor = str_replace("'", "", $portafolio);

                                $insert = $this->db->prepare("INSERT INTO reporte_drenaje_ventas(rv_region, rv_region_2, rv_zona, rv_vendedor_codigo, 
                                    rv_vendedor_nombre, rv_vendedor_portafolio, rv_distribuidoras_nombres, rv_distribuidoras_valores,
                                    rv_cuota, rv_valor, rv_cliente_portafolio, rv_cliente_ventas, rv_tipo, rv_zona_view, rv_prod_port, rv_periodo)
                                    VALUES(:rv_region, :rv_region_2, :rv_zona, :rv_vendedor_codigo, 
                                    :rv_vendedor_nombre, :rv_vendedor_portafolio, :rv_distribuidoras_nombres, :rv_distribuidoras_valores,
                                    :rv_cuota, :rv_valor, :rv_cliente_portafolio, :rv_cliente_ventas, :rv_tipo, :rv_zona_view, :rv_prod_port, :rv_periodo)");
                                $insert->bindParam(":rv_region", $reg_cod_vis);
                                $insert->bindParam(":rv_region_2", $reg_cod_vis);
                                $insert->bindParam(":rv_zona", $zona);
                                $insert->bindParam(":rv_vendedor_codigo", $vendedor);
                                $insert->bindParam(":rv_vendedor_nombre", $name_vendedor);
                                $insert->bindParam(":rv_vendedor_portafolio", $portafolio_vendedor);
                                $insert->bindParam(":rv_distribuidoras_nombres", $cod_array_dstb);
                                $insert->bindParam(":rv_distribuidoras_valores", $valores_dstb);
                                $insert->bindParam(":rv_cuota", $monto);
                                $insert->bindParam(":rv_valor", $total_ventas);
                                $insert->bindParam(":rv_cliente_portafolio", $rv_cliente_portafolio);
                                $insert->bindParam(":rv_cliente_ventas", $rv_cliente_ventas);
                                $insert->bindParam(":rv_tipo", $rv_tipo);
                                $insert->bindParam(":rv_zona_view", $zona);
                                $insert->bindParam(":rv_prod_port", $prod_vis);
                                $insert->bindParam(":rv_periodo", $periodo);
                                if($insert->execute())
                                {
                                    $output .= "ingresado...<br>";
                                }else
                                {
                                    $output .= errorPDO($insert)."<br>";
                                }
                            }else
                            {
                                $output .= errorPDO($select_ventas);
                            }
                        }
                    }else
                    {
                        $output .= errorPDO($select_cuota);
                    }
                #}
            }else
            {
                $output .= errorPDO($select_zona_reg);
            }
        }else
        {
            $output .= errorPDO($delete);
        }
        return $output;
    }

    
    public function __destruct()
    {
        $this->db = NULL;#Connection Desctruct#
        #__SQL__();
    }


}