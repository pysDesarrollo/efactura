<?php

class RetencionesController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        if(isset($_GET['search'])) {
            $documentos = Retencion::where('secuencial', 'like', '%' . Input::get('search') . '%')
                ->orderBy('num_compra', 'desc')
                ->paginate(7);
        }
        else {
            $documentos = Retencion::orderBy('num_compra', 'desc')->paginate(7);
        }

        return View::make('pages.retencion.index')->with('datos', $documentos);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $total = -1;
        $errores = "";

        //Genera proceso para ingresar facturas
        if (Input::all()) {

            $total = 0;

            //Coge los valores de intereses para generar facturas
            $retenciones = DatoRetencion::where('mes_codigo', '=', Input::get('fecha_proceso'))
                ->where('estado', '=', 'POR FACTURAR')->get();

            foreach ($retenciones as $key => $value) {
                //Busca si existe el cliente/proveedor
                $searchcliente = Cliente::where('cli_identificacion', '=', $value->ruc_beneficiario)->get();

                if (sizeof($searchcliente) <= 0) {
                    //Inserta cliente en la bdd
                    $newcliente = new Cliente;
                    $newcliente->id_emi = 1;
                    $newcliente->cli_nombres_apellidos = $value->nombre_beneficiario;
                    $newcliente->cli_tipo_identificacion = strlen($value->ruc_beneficiario) == 10 ? 12 : 13;
                    $newcliente->cli_identificacion = $value->ruc_beneficiario;
                    $newcliente->cli_direccion = $value->direccion_beneficiario;
                    $newcliente->cli_tel_convencional = $value->fono_beneficiario;
                    $newcliente->cli_email = $value->mail_beneficiario;
                    $newcliente->cli_estado = 'A';
                    $newcliente->cli_usuario_crea = Auth::user()->id;
                    $newcliente->cli_fecha_crea = date('Y-m-d h:i:s');
                    $newcliente->cli_tipo_cliente = '6';

                    $newcliente->save();


                }

                //Busca datos del cliente
                $cliente = Cliente::join('emisor', 'cliente.id_emi', '=', 'emisor.id')
                    ->join('catalogo', 'cliente.cli_tipo_identificacion', '=', 'catalogo.id')
                    ->where('cliente.cli_identificacion', "=", $value->ruc_beneficiario)
                    ->select('cliente.id as idcliente', 'cliente.*', 'emisor.*', 'catalogo.*')
                    ->where('catalogo.cat_codigo_padre', "=", "04")
                    ->get();

              //Busca secuencia de documentos
                $documento = SecuenciaDocumento::where("sec_tipo_documento", "=", "RT")
                    ->where("sec_estado", "=", "A")->get();

                if(sizeof($documento) > 0) {

                    $datos = array("doc_fecha" => $value->fecha_crea, "doc_estab" => $documento[0]->sec_estab,
                        "doc_ptoemi" => $documento[0]->sec_ptoemi, "doc_num" => $documento[0]->sec_final);

                    $token = $this->generarToken($datos, $cliente, "07");

                    $retencion = new Retencion;
                    $retencion->ambiente = $cliente[0]->emi_tipo_ambiente;
                    $retencion->tipoEmision = $cliente[0]->emi_tipo_emision;
                    $retencion->razonSocial = $cliente[0]->emi_nombre;
                    $retencion->nombreComercial = $cliente[0]->emi_nombre_comercial;
                    $retencion->ruc = $cliente[0]->emi_ruc;
                    $retencion->claveAcceso = $token;
                    $retencion->codDoc = "07"; //Retención
                    $retencion->estab = $datos['doc_estab'];
                    $retencion->ptoEmi = $datos['doc_ptoemi'];
                    $retencion->secuencial = str_pad($datos['doc_num'], 9, '0', STR_PAD_LEFT);
                    $retencion->dirMatriz = $cliente[0]->emi_direccion_matriz;
                    $retencion->fechaEmision = date('Y-m-d', strtotime($value->fecha_crea));
                    $retencion->dirEstablecimiento = $cliente[0]->emi_direccion_matriz;
                    $retencion->contribuyenteEspecial = $cliente[0]->emi_nro_resolucion;
                    $retencion->obligadoContabilidad = $cliente[0]->emi_obligado_llevar_contabilidad;
                    $retencion->tipoIdentificacionSujetoRetenido = $cliente[0]->cat_codigo_padre;
                    $retencion->razonSocialSujetoRetenido = $cliente[0]->cli_nombres_apellidos;
                    $retencion->identificacionSujetoRetenido = $value->ruc_beneficiario;
                    $retencion->periodoFiscal = date('Y-m-d', strtotime($value->fecha_crea));
                    $retencion->totalDescuento = 0;
                    $retencion->campoAdicional_emailCliente = is_null($cliente[0]->cli_email) ? $cliente[0]->emi_email : $cliente[0]->cli_email;
                    $retencion->campoAdicional_numeroCliente = $cliente[0]->idcliente;
                    $retencion->estado = $cliente[0]->emi_estado_documento;
                    $retencion->num_compra = $datos['doc_num'];
                    $retencion->save();

                    //Ingresa retención en la fuente
                    $detalleretencion = new DetalleRetencion;
                    $detalleretencion->id = $retencion->id;
                    $detalleretencion->ruc = $cliente[0]->emi_ruc;
                    $detalleretencion->estab = $datos['doc_estab'];
                    $detalleretencion->ptoEmi = $datos['doc_ptoemi'];
                    $detalleretencion->secuencial = str_pad($datos['doc_num'], 9, '0', STR_PAD_LEFT);
                    $detalleretencion->codigo = 1;
                    $detalleretencion->codigoRetencion = $value->codigo_retencion;
                    $detalleretencion->baseImponible = $value->valor_factura;
                    $detalleretencion->porcentajeRetener = $value->porcentaje_retencion;
                    $detalleretencion->valorRetenido = $value->valor_retencion;
                    $detalleretencion->codDocSustento = "01"; //Factura
                    $detalleretencion->numDocSustento = $value->num_factura;
                    $detalleretencion->fechaEmisionDocSustento = date('Y-m-d', strtotime($value->fecha_documento));
                    $detalleretencion->num_compra = $datos['doc_num'];
                    $detalleretencion->save();

                    //Ingresa retención IVA
                    if (!is_null($value->codigo_iva)) {
                        $detalleretencion = new DetalleRetencion;
                        $detalleretencion->id = $retencion->id;
                        $detalleretencion->ruc = $cliente[0]->emi_ruc;
                        $detalleretencion->estab = $datos['doc_estab'];
                        $detalleretencion->ptoEmi = $datos['doc_ptoemi'];
                        $detalleretencion->secuencial = str_pad($datos['doc_num'], 9, '0', STR_PAD_LEFT);
                        $detalleretencion->codigo = 2;
                        $detalleretencion->codigoRetencion = $value->codigo_iva;
                        $detalleretencion->baseImponible = $value->valor_factura * 0.12;
                        $detalleretencion->porcentajeRetener = $value->porcentaje_iva;
                        $detalleretencion->valorRetenido = $value->valor_iva;
                        $detalleretencion->codDocSustento = "01"; //Factura
                        $detalleretencion->numDocSustento = $value->num_factura;
                        $detalleretencion->fechaEmisionDocSustento = date('Y-m-d', strtotime($value->fecha_documento));
                        $detalleretencion->num_compra = $datos['doc_num'];
                        $detalleretencion->save();
                    }

                    //Actualiza en 1 el secuencial del documento
                    $secuencia = SecuenciaDocumento::where('sec_tipo_documento','=','RT')->where('sec_estado','=','A')->get();
                    $numero = $secuencia[0]->sec_final + 1;
                    $id = $secuencia[0]->id;
                    DB::update('update secuencia_documento set sec_final = ? where id = ?', array($numero, $id));

                    //Actualiza estado de retenciones
                    DB::update('update retenciones set estado = ? where id = ?', array('FACTURADO', $value->id));

                    $total++;
                }
                else {
                    $errores = "No existe secuenciales para retenciones";
                }
            }
        }

        //Buscar facturas
        if(isset($_GET['search'])) {
            $documentos = Retencion::where('secuencial', 'like', '%' . Input::get('search') . '%')
                ->orderBy('num_compra', 'desc')
                ->paginate(7);
        }
        else {
            $documentos = Retencion::orderBy('num_compra', 'desc')->paginate(7);
        }

        return View::make('pages.retencion.create')->with('datos', $documentos)->with('registros',$total)->with('errores',$errores);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function generarToken($datos, $cliente, $tipoDocumento) {

        $token = date('d',strtotime($datos['doc_fecha'])) . date('m',strtotime($datos['doc_fecha'])) . date('Y',strtotime($datos['doc_fecha']))
            . $tipoDocumento . $cliente[0]->emi_ruc . $cliente[0]->emi_tipo_ambiente . $datos['doc_estab'] . $datos['doc_ptoemi']
            . str_pad($datos['doc_num'], 9, '0', STR_PAD_LEFT) . "00000990" . "1";

        //Algoritmo Módulo 11 SRI
        $posiciones = array(0,7,6,5,4,3,2);

        $pos = 0;
        $suma = 0;

        for($i=1; $i<48; $i++) {
            $suma += substr($token, $i, 1) . $posiciones[$i - $pos];
            if ($i % 6 == 0) {
                $pos = $pos + 6;
            }
        }

        $verificador = 11 - ($suma % 11);

        if($verificador == 11) {
            $verificador = 0;
        }
        else if($verificador == 10) {
            $verificador = 1;
        }

        $token .= $verificador;

        return $token;
    }

    public function generaPDF($retId){
        // dd("aqui");
        $retencion = Retencion::find($retId);
        $detalle = DetalleRetencion::where("id","=",$retId)->get();
        $emision = Catalogo::where("cat_referencia","=",$retencion['tipoEmision'])->get();
        $emision = $emision[0]->cat_descripcion;
        $cliente = Cliente::find($retencion->campoAdicional_numeroCliente);

	     $html = "<html>
	         <head>
	        <style type='text/css'>

            table.detalle {
             border-collapse: collapse;
             border-spacing: 0px;
            }
            table.detalle td,th{
            border: 1px solid #000000 !important;

            padding:5px;
            }
            .informacion-adicional{
                border:1px solid black;
                padding:5px;
                width:50%;
                font:Verdana;
                font-size:11px
            }
            </style>
	         </head>

	         <body>" .
             "<table border='0' width='100%'>" .
             "<tr>" .
             "<td width='45%' align='center'>
             <img src='img/pys-logo.gif'><p><font face='Verdana' size='2'><b>" . utf8_decode($retencion['razonSocial']) .
             "</b></font></p><p><font face='Verdana' size='1'><b>Matriz: </b>" . utf8_decode($retencion['dirMatriz']) .
             "</font></p>
             </td>" .
             "<td width='10%'>&nbsp;</td>" .
             "<td width='45%' align='left'>
             <p><font face='Verdana' size='2'><b>R.U.C.</b> " . $retencion['ruc'] . "</font></p>
	     		 	<p><font face='Verdana' size='2'>COMPROBANTE DE RETENCI&Oacute;N</font></p>
	     		 	<p><font face='Verdana' size='2'>No. " . $retencion['estab']  . "-" . $retencion['ptoEmi'] . "-" . $retencion['secuencial'] ."</font>

             <p><font face='Verdana' size='1'>AMBIENTE: ".($retencion['ambiente']==2?"PRODUCCI&Oacute;N":"PRUEBAS")."</font></p>
                   <p><font face='Verdana' size='1'>EMISI&Oacute;N: ".$emision."</font></p>
	     		 	<p><font face='Verdana' size='1'>CLAVE DE ACCESO<br>". $retencion['claveAcceso'] . "</font></td>" .
             "</tr>" .
             "</table><br>" .
             "<table border='0' width='100%'>" .
             "<tr><td colspan='2'><font face='Verdana' size='2'><b>Raz&oacute;n Social/Nombres Apellidos: </b>" . utf8_decode($retencion['razonSocialSujetoRetenido']) . "</font></td></tr>" .
             "<tr><td><font face='Verdana' size='2'><b>RUC: </b>" . $retencion['identificacionSujetoRetenido'] . "</font>" .
             "<td><font face='Verdana' size='2'><b>Fecha de Emisi&oacute;n: </b>" . date('d-m-Y', strtotime($retencion['fechaEmision'])) . "</font></td></tr>" .
             "</table><br>" .
             "<table border='0' width='100%' class='detalle'>" .
             "<tr><th><font face='Verdana' size='1'><b>Comprobante</b></th>" .
             "<th ><font face='Verdana' size='1'><b>N&uacute;mero</b></font></th>" .
             "<th ><font face='Verdana' size='1'><b>Fecha emisi&oacute;n</b></font></th>" .
             "<th ><font face='Verdana' size='1'><b>Ejercicio Fiscal</b></font></th>".
             "<th ><font face='Verdana' size='1'><b>Base imponible</b></font></th>".
             "<th ><font face='Verdana' size='1'><b>Impuesto</b></font></th>".
             "<th ><font face='Verdana' size='1'><b>Porcentaje retenci&oacute;n</b></font></th>".
             "<th ><font face='Verdana' size='1'><b>Valor retenido</b></font></th></tr>";
             $total = 0;
				 foreach ($detalle as $key => $value) {

                     $html .= "<tr><td align='left'><font face='Verdana' size='2'>" . utf8_decode($value->codDocSustento=='01'?'Factura':'') . "</font></td>" .
                         "<td align='right'><font face='Verdana' size='2'>" . $value->numDocSustento . "</font></td>" .
                         "<td align='right'><font face='Verdana' size='2'>" . date('d/m/Y', strtotime($value->fechaEmisionDocSustento)) . "</font></td>" .
                         "<td align='right'><font face='Verdana' size='2'>" . date('m/Y', strtotime($value->fechaEmisionDocSustento)) . "</font></td>" .
                         "<td align='right'><font face='Verdana' size='2'>" . number_format($value->baseImponible, "2") . "</font></td>" .
                         "<td align='right'><font face='Verdana' size='2'>" . ($value->codigo==2?'Iva':'Renta') . "</font></td>" .
                         "<td align='right'><font face='Verdana' size='2'>" . number_format($value->porcentajeRetener, "2") . "</font></td>" .
                         "<td align='right'><font face='Verdana' size='2'>" . number_format($value->valorRetenido, "2") . "</font></td></tr>";
                         $total+=$value->valorRetenido;
                 }
        $html.="<tr><td colspan='7' align='right'><font face='Verdana' size='2'><b>TOTAL</b></font></td><td align='right'><font face='Verdana' size='2'><b>".number_format($total, "2")."</b></font></td></tr>";
	     $html.= "</table>";

        $html.="<br/><br/><div class='informacion-adicional'>
            <table style='width:100%'>
            <tr><td style='width: 30%'></td><td style='width: 70%'>Informaci&oacute;n adicional</td></tr>
            <tr><td style='width: 30%'>Direcci&oacute;n</td><td style='width: 70%'>".utf8_decode($cliente['cli_direccion'])."</td></tr>
            <tr><td style='width: 30%'>Email</td><td style='width: 70%'>".utf8_decode($retencion['campoAdicional_emailCliente'])."</td></tr>
            <tr><td style='width: 30%'>Tel&eacute;fono</td><td style='width: 70%'>".utf8_decode($cliente['cli_tel_convencional'])."</td></tr>
            </table>
        </div>";
	     if ($retencion['estado'] != 'NUEVA') {
             $html.= "<p><font face='Verdana' size='4'>" .  $retencion['estado'] . "</font></p>";
         }
	     

	     $html.= '</body></html>';

	     //return PDF::load($html, 'A4', 'portrait')->show();
	     PDF::load($html, 'A4', 'portrait')->download($retencion['claveAcceso']);

	}


}
