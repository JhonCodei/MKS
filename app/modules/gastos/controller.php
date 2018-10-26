<?php

Class GastosController
{
    public function __construct()
    {
        is_session_true(); 
        
        $__file__ = "gastos";

        __MODELS__($__file__);
        __SQL__($__file__);
        __FUNCTIONS__($__file__);

        $this->model = new GastosModel();
    }
    public function render($vista)
    {
        $css_js_1 = array();
        $css_js_2 = array();
        
        #$css_js_2[] = __css_js__('highchart_JS');

        return render_view($vista, $css_js_1, $css_js_2);
    }
    #__functions__
    public function _insertar_gasto()
    {
        $gastos = $this->model;

        $vendedor = info_usuario('codigo');
        $periodo = $_POST['periodo'];
        $quincena = $_POST['quincena'];
        $fecha = formatfecha($_POST['fecha']);
        $motivo = $_POST['motivo'];
        $tipo = $_POST['tipo'];
        $documentos = $_POST['documentos'];
        $ruc = $_POST['ruc'];
        $importe = $_POST['importe'];
        $ventas = $_POST['ventas'];
        $ruc_cliente = $_POST['ruc_cliente'];
        $observaciones = $_POST['observaciones'];

        if(strlen($ruc) == 0){$ruc = 0;}
        if(strlen($importe) == 0){$importe = 0;}
        if(strlen($ventas) == 0){$ventas = 0;}
        if(strlen($ruc_cliente) == 0){$ruc_cliente = 0;}
        
        $gastos->vendedor = $vendedor;
        $gastos->periodo = $periodo;
        $gastos->quincena = $quincena;
        $gastos->fecha = $fecha;
        $gastos->motivo = $motivo;
        $gastos->tipo = $tipo;
        $gastos->documentos = $documentos;
        $gastos->ruc = $ruc;
        $gastos->importe = $importe;
        $gastos->ventas = $ventas;
        $gastos->ruc_cliente = $ruc_cliente;
        $gastos->observaciones = $observaciones;

        print $this->model->_insertar_gasto($gastos);
    }
    public function listado_gastos()
    {
        $vendedor = info_usuario('codigo');
        $periodo = $_POST['periodo'];
        $quincena = $_POST['quincena'];

        $data = $this->model;
        $data->vendedor = $vendedor;
        $data->periodo = $periodo;
        $data->quincena = $quincena;

        print $this->model->listado_gastos($data);
    }
    public function editar_gasto()
    {
        $data = $this->model;

        $id = $_POST['id'];
        
        $data->id = $id;

        print $this->model->editar_gasto($data);
    }
    public function update_gasto()
    {
        $gastos = $this->model;

        $vendedor = info_usuario('codigo');
        $id = $_POST['id'];
        $periodo = $_POST['periodo'];
        $quincena = $_POST['quincena'];
        $fecha = formatfecha($_POST['fecha']);
        $motivo = $_POST['motivo'];
        $tipo = $_POST['tipo'];
        $documentos = $_POST['documentos'];
        $ruc = $_POST['ruc'];
        $importe = $_POST['importe'];
        $ventas = $_POST['ventas'];
        $ruc_cliente = $_POST['ruc_cliente'];
        $observaciones = $_POST['observaciones'];

        if(strlen($ruc) == 0){$ruc = 0;}
        if(strlen($importe) == 0){$importe = 0;}
        if(strlen($ventas) == 0){$ventas = 0;}
        if(strlen($ruc_cliente) == 0){$ruc_cliente = 0;}
        
        $gastos->vendedor = $vendedor;
        $gastos->id = $id;
        $gastos->periodo = $periodo;
        $gastos->quincena = $quincena;
        $gastos->fecha = $fecha;
        $gastos->motivo = $motivo;
        $gastos->tipo = $tipo;
        $gastos->documentos = $documentos;
        $gastos->ruc = $ruc;
        $gastos->importe = $importe;
        $gastos->ventas = $ventas;
        $gastos->ruc_cliente = $ruc_cliente;
        $gastos->observaciones = $observaciones;

        print $this->model->update_gasto($gastos);
    }
    public function eliminar_gasto()
    {
        $data = $this->model;

        $id = $_POST['id'];
        
        $data->id = $id;

        print $this->model->eliminar_gasto($data);
    }

}