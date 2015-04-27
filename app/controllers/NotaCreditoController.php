<?php

class NotaCreditoController extends \BaseController {

/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		$notacredito = NotaCredito::paginate(7);
		return View::make('pages.nota-credito.index')->with('datos', $notacredito);
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
		$secuencia = SecuenciaDocumento::where('sec_tipo_documento','=','NC')->where('sec_estado','=','A')->get();
		$producto = Producto::all();
		return View::make('pages.nota-credito.create')->with('secuencia',$secuencia)->with('producto',$producto);
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$estadoAnula = 'CANJEADA';

	    $rules = array(
	       'doc_num' => 'required|min:1',
	       'doc_fecha' => 'required|date',
	       'fac_estab' => 'required|min:1',
	       'fac_ptoEmi' => 'required|min:1',
	       'fac_numero' => 'required|min:1',
	       'idFacturaMod' => 'required|min:1',
	    );

	    $errors = array(
		  'required' => 'El campo :attribute es obligatorio',
          'min' => 'El campo :attribute no puede tener menos de :min carácteres',
	      'date' => 'El formato de la fecha es incorrecto',
	      'numeric' => 'El campo :attribute debe ser numérico',
	    );

		$validator = Validator::make(Input::all(), $rules, $errors); 		
		if ($validator->fails()){
		    $secuencia = SecuenciaDocumento::where('sec_tipo_documento','=','NC')->where('sec_estado','=','A')->get();

			return Redirect::to('nota-credito/create')->withErrors($validator)->withInput()
				           ->with('secuencia',$secuencia); 
		}	
		else {
			//Busca la factura original
			$cliente = Cliente::join('emisor', 'cliente.id_emi', '=', 'emisor.id')
						      ->join('catalogo', 'cliente.cli_tipo_identificacion', '=', 'catalogo.id')
						      ->where('cliente.id', "=", Input::get('idcliente'))
						      ->where('catalogo.cat_codigo_padre', "=", "04")
							  ->get();		

			$factura = Documento::find(Input::get('idFacturaMod'));

			$token = $this->generarToken(Input::all(), $cliente, "04");


			//echo $factura['ambiente'];
			//exit;

			//Ingresa datos en la tabla nota de crédito
			$notacredito = new NotaCredito;
			$notacredito->ambiente = $factura['ambiente'];
			$notacredito->tipoEmision = $factura['tipoEmision'];
			$notacredito->razonSocial = $factura['razonSocial'];
			$notacredito->nombreComercial = $factura['nombreComercial'];
			$notacredito->ruc = $factura['ruc'];
			$notacredito->claveAcceso = $token;
			$notacredito->codDoc = "04"; //Nota de Crédito
			$notacredito->estab = Input::get('doc_estab');
			$notacredito->ptoEmi = Input::get('doc_ptoemi');
			$notacredito->secuencial = str_pad(Input::get('doc_num'), 9, '0', STR_PAD_LEFT);
			$notacredito->dirMatriz = $factura['dirMatriz'];
			$notacredito->fechaEmision = Input::get('doc_fecha');
			$notacredito->dirEstablecimiento = $factura['dirEstablecimiento'];
			$notacredito->contribuyenteEspecial = $factura['contribuyenteEspecial'];
			$notacredito->obligadoContabilidad = $factura['obligadoContabilidad'];
			$notacredito->tipoIdentificacionComprador = $factura['tipoIdentificacionComprador'];
			$notacredito->razonSocialComprador = $factura['razonSocialComprador'];
			$notacredito->identificacionComprador = $factura['identificacionComprador'];
			$notacredito->totalSinImpuestos = $factura['totalSinImpuestos'];
			$notacredito->codigo = $factura['codigo'];
			$notacredito->codigoPorcentaje = $factura['codigoPorcentaje'];
			$notacredito->baseImponible = $factura['baseImponible'];
			$notacredito->valor = $factura['valor'];
			$notacredito->moneda = $factura['moneda'];
			$notacredito->campoAdicional_emailCliente = $factura['campoAdicional_emailCliente'];
			$notacredito->campoAdicional_numeroCliente = $factura['campoAdicional_numeroCliente'];
			$notacredito->estado = $cliente[0]->emi_estado_documento;
			$notacredito->num_factura = Input::get('doc_num');
			$notacredito->totalsiniva = $factura['totalsiniva'];
			$notacredito->totalconiva = $factura['totalconiva'];
			$notacredito->fechaEmisionDocSustento = $factura['fechaEmision'];
			$notacredito->codDocModificado = "01";
			$notacredito->numDocModificado = $factura['estab'] . '-' . $factura['ptoEmi'] . '-' . $factura['secuencial'];
			$notacredito->valorModificacion = $factura['importeTotal'];
			$notacredito->motivo = 'ANULACION';

			$notacredito->save();

			$detallesFactura = DetalleDocumento::where('id', '=', Input::get('idFacturaMod'))->get();

			foreach ($detallesFactura as $detalleFactura) {
				//Ingresa los ítems de la factura a la nota de crédito
				$detalle = new DetalleNotaCredito;
				$detalle->id = $notacredito->id;
				$detalle->ruc = $detalleFactura['ruc'];
				$detalle->estab = Input::get('doc_estab');
				$detalle->ptoEmi =  Input::get('doc_ptoemi');
				$detalle->secuencial = str_pad(Input::get('doc_num'), 9, '0', STR_PAD_LEFT);
				$detalle->codigoInterno = $detalleFactura['codigoPrincipal'];
				$detalle->codigoAdicional = $detalleFactura['codigoPrincipal'];
				$detalle->descripcion = $detalleFactura['descripcion'];
				$detalle->cantidad = $detalleFactura['cantidad'];
				$detalle->precioUnitario = $detalleFactura['precioUnitario'];
				$detalle->descuento = $detalleFactura['descuento'];
				$detalle->precioTotalSinImpuesto = $detalleFactura['precioTotalSinImpuesto'];
				$detalle->codigo = $detalleFactura['codigo'];
				$detalle->codigoPorcentaje = $detalleFactura['codigoPorcentaje'];
				$detalle->tarifa = $detalleFactura['tarifa'];
				$detalle->baseImponible = $detalleFactura['baseImponible'];
				$detalle->valor = $detalleFactura['valor'];
				$detalle->num_factura = $detalleFactura['num_factura'];
				
				$detalle->save();
			}

			//Actualiza en 1 el secuencial del documento
			$secuencia = SecuenciaDocumento::where('sec_tipo_documento','=','NC')->where('sec_estado','=','A')->get();
			$numero = $secuencia[0]->sec_final + 1;
			$id = $secuencia[0]->id;
			DB::update('update secuencia_documento set sec_final = ? where id = ?', array($numero, $id));

			//Actualiza el estado de la factura
			DB::update('update fe_cabecera_factura set estado = ? where id = ?', array($estadoAnula, $factura['id']));			

			$notacredito = NotaCredito::paginate(7);
			return View::make('pages.nota-credito.index')->with('datos', $notacredito);
		}
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


	public function generaPDF($facId){

		 $factura = NotaCredito::find($facId);
		 $detalle = DetalleNotaCredito::where("id","=",$facId)->get();

	     $html = "<html><body>" .
	     		 "<table border='0' width='100%'>" .
	     		 "<tr>" .
	     		 	"<td width='45%' align='center'><img src='img/pys-logo.gif'><p><font face='Verdana' size='2'><b>" . utf8_decode($factura['razonSocial']) . "</b></font></p><p><font face='Verdana' size='1'><b>Matriz: </b>" . utf8_decode($factura['dirMatriz']) . "</font></p></td>" .
	     		 	"<td width='10%'>&nbsp;</td>" .
	     		 	"<td width='45%' align='center'><font face='Verdana' size='2'>NOTA DE CR&Eacute;DITO<p></font><font face='Verdana' size='1'><b>R.U.C.</b> " . $factura['ruc'] . "</p><p>No. Documento " . $factura['estab']  . "-" . $factura['ptoEmi'] . "-" . $factura['secuencial'] . 
	     		 	"<p><font face='Verdana' size='1'>CONTRIBUYENTE ESPECIAL RESOLUCI&Oacute;N No. ". $factura['contribuyenteEspecial'] . "</font>" .
	     		 	"<p><font face='Verdana' size='1'>Clave de Acceso<br>". $factura['claveAcceso'] . "</font></td>" .
	     		 "</tr>" .
	     		 "</table><br>" .
	     		 "<table border='0' width='100%'>" .
	     		 "<tr><td colspan='2'><font face='Verdana' size='2'><b>Raz&oacute;n Social/Nombres Apellidos: </b>" . utf8_decode($factura['razonSocialComprador']) . "</font></td></tr>" .
	     		 "<tr><td><font face='Verdana' size='2'><b>RUC: </b>" . $factura['identificacionComprador'] . "</font>" .
	     		 "<td><font face='Verdana' size='2'><b>Fecha de Emisi&oacute;n: </b>" . date('d-m-Y', strtotime($factura['fechaEmision'])) . "</font></td></tr>" .
	     		 "</table><br>" .
	     		 "<table border='0' width='100%'>" .
				 "<tr><td align='center' width='40%'><font face='Verdana' size='2'><b>Descripci&oacute;n</b></font></td>" .	     		 
				 "<td align='right' width='20%'><font face='Verdana' size='2'><b>Cantidad</b></font></td>" .	     		 
				 "<td align='right' width='20%'><font face='Verdana' size='2'><b>Precio Unitario</b></font></td>" .	     		 
				 "<td align='right' width='20%'><font face='Verdana' size='2'><b>Valor de Venta</b></font></td></tr>";
				 
				 foreach ($detalle as $key => $value) {
					$html .= "<tr><td align='left'><font face='Verdana' size='2'>" . utf8_decode($value->descripcion) . "</font></td>" .
							 "<td align='right'><font face='Verdana' size='2'>" . number_format($value->cantidad, "2") . "</font></td>" .
							 "<td align='right'><font face='Verdana' size='2'>" . number_format($value->precioUnitario, "2") . "</font></td>" .
							 "<td align='right'><font face='Verdana' size='2'>" . number_format($value->precioTotalSinImpuesto, "2") . "</font></td></tr>";
				 }	     		 
	     $html.= "<tr><td colspan='4'>&nbsp;</td></tr>" .
				 "<tr><td colspan='3' align='right'><font face='Verdana' size='2'>Subtotal 0% </font></td>" .
	     		 "<td align='right'><font face='Verdana' size='2'>" . number_format($factura['totalsiniva'], "2") . "</font></td></tr>" .
	     		 "<tr><td colspan='3' align='right'><font face='Verdana' size='2'>Subtotal 12% </font></td>" .
	     		 "<td align='right'><font face='Verdana' size='2'>" . number_format($factura['totalconiva'], "2") . "</font></td></tr>" .
	     		 "<tr><td colspan='3' align='right'><font face='Verdana' size='2'>I.V.A. 12% </font></td>" .
	     		 "<td align='right'><font face='Verdana' size='2'>" . number_format($factura['valor'], "2") . "</font></td></tr>" .	     		 
	     		 "<tr><td colspan='3' align='right'><font face='Verdana' size='2'><b>TOTAL </b></font></td>" .
	     		 "<td align='right'><font face='Verdana' size='2'><b>" . number_format($factura['importeTotal'], "2") . "</b></font></td></tr>" .	     		 
	     		 "</table>";

	     $html.= '</body></html>';

	     //return PDF::load($html, 'A4', 'portrait')->show();
	     PDF::load($html, 'A4', 'portrait')->download($factura['claveAcceso']);

	}

}
