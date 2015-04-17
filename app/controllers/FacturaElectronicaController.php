<?php

class FacturaElectronicaController extends \BaseController {

/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		if(isset($_GET['search'])) {
			$documentos = Documento::where('secuencial', 'like', '%' . Input::get('search') . '%')
								   ->orderBy('num_factura', 'desc')
   							       ->paginate(7);			
		}
		else {
			$documentos = Documento::orderBy('num_factura', 'desc')->paginate(7);
		}

		return View::make('pages.factura-electronica.index')->with('datos', $documentos);			
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
		$secuencia = SecuenciaDocumento::where('sec_tipo_documento','=','FA')->where('sec_estado','=','A')->get();
		$producto = Producto::all();
		return View::make('pages.factura-electronica.create')->with('secuencia',$secuencia)->with('producto',$producto);
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
	    $rules = array(
	       'doc_num' => 'required|min:1',
	       'doc_fecha' => 'required|date',
	       'cli_cedularuc' => 'required|numeric',
	       'idcliente' => 'required|numeric',
	       'id' => 'required',
	       'cantidad' => 'required|min:1'
	    );

	    $errors = array(
		  'required' => 'El campo :attribute es obligatorio',
          'min' => 'El campo :attribute no puede tener menos de :min carácteres',
	      'date' => 'El formato de la fecha es incorrecto',
	      'numeric' => 'El campo :attribute debe ser numérico',
	    );

		$validator = Validator::make(Input::all(), $rules, $errors); 		
		if ($validator->fails()){
		    $secuencia = SecuenciaDocumento::where('sec_tipo_documento','=','FA')->where('sec_estado','=','A')->get();
		    $producto = Producto::all();

			return Redirect::to('factura-electronica/create')->withErrors($validator)->withInput()
				           ->with('secuencia',$secuencia)->with('producto',$producto); 
		}
		else {
			$cliente = Cliente::join('emisor', 'cliente.id_emi', '=', 'emisor.id')
						      ->join('catalogo', 'cliente.cli_tipo_identificacion', '=', 'catalogo.id')
						      ->where('cliente.id', "=", Input::get('idcliente'))
						      ->where('catalogo.cat_codigo_padre', "=", "04")
							  ->get();		

			$token = $this->generarToken(Input::all(), $cliente, "01");

			$factura = new Documento;
			$factura->ambiente = $cliente[0]->emi_tipo_ambiente;
			$factura->tipoEmision = $cliente[0]->emi_tipo_emision;
			$factura->razonSocial = $cliente[0]->emi_nombre;
			$factura->nombreComercial = $cliente[0]->emi_nombre_comercial;
			$factura->ruc = $cliente[0]->emi_ruc;
			$factura->claveAcceso = $token;
			$factura->codDoc = "01"; //Factura
			$factura->estab = Input::get('doc_estab');
			$factura->ptoEmi = Input::get('doc_ptoemi');
			$factura->secuencial = str_pad(Input::get('doc_num'), 9, '0', STR_PAD_LEFT);
			$factura->dirMatriz = $cliente[0]->emi_direccion_matriz;
			$factura->fechaEmision = Input::get('doc_fecha');
			$factura->dirEstablecimiento = $cliente[0]->emi_direccion_matriz;
			$factura->contribuyenteEspecial = $cliente[0]->emi_nro_resolucion;
			$factura->obligadoContabilidad = $cliente[0]->emi_obligado_llevar_contabilidad;
			$factura->tipoIdentificacionComprador = $cliente[0]->cat_referencia;
			$factura->razonSocialComprador = $cliente[0]->cli_nombres_apellidos;
			$factura->identificacionComprador = Input::get('cli_cedularuc');
			$factura->totalSinImpuestos = Input::get('doc_subtotal0') + Input::get('doc_subtotal12');
			$factura->totalDescuento = 0;
			$factura->codigo = 2; //Input::get('doc_iva') > 0 ? 2 : 0;
			$factura->codigoPorcentaje = Input::get('doc_iva') > 0 ? 2 : 0;
			$factura->descuentoAdicional = 0;
			$factura->baseImponible = Input::get('doc_subtotal12');
			$factura->valor = Input::get('doc_iva');
			$factura->propina = 0; 
			$factura->importeTotal = round(Input::get('doc_total'),2);
			$factura->moneda = "DOLAR";
			$factura->campoAdicional_emailCliente = is_null($cliente[0]->cli_email) ? $cliente[0]->emi_email : $cliente[0]->cli_email;
			$factura->campoAdicional_dirCliente = $cliente[0]->cli_direccion;
			$factura->campoAdicional_telfCliente = $cliente[0]->cli_tel_convencional;
			$factura->campoAdicional_numeroCliente = Input::get('idcliente');
			$factura->estado = $cliente[0]->emi_estado_documento;
			$factura->num_factura = Input::get('doc_num');
			$factura->totalsiniva = Input::get('doc_subtotal0');
			$factura->totalconiva = Input::get('doc_subtotal12');

			$factura->save();
			
			$productos = Input::get('id');
			
			for ($i = 0; $i < count($productos); $i++) {

				$producto = Producto::where('id','=',Input::get('id')[$i+ 1])->get();

				$detalle = new DetalleDocumento;
				$detalle->id = $factura->id;
				$detalle->ruc = $cliente[0]->emi_ruc;
				$detalle->estab = Input::get('doc_estab');
				$detalle->ptoEmi =  Input::get('doc_ptoemi');
				$detalle->secuencial = str_pad(Input::get('doc_num'), 9, '0', STR_PAD_LEFT);
				$detalle->codigoPrincipal = $producto[0]->pro_cod_principal;
				$detalle->descripcion = $producto[0]->pro_nombre;
				$detalle->cantidad = Input::get('cantidad')[$i + 1];
				$detalle->precioUnitario = Input::get('precio')[$i + 1];
				$detalle->descuento = 0;
				$detalle->precioTotalSinImpuesto = Input::get('totalFila')[$i + 1];
				$detalle->codigo = 2; //$producto[0]->pro_incluyeiva == 'S' ? 2 : 0;
				$detalle->codigoPorcentaje = $producto[0]->pro_incluyeiva == 'S' ? 2 : 0;
				$detalle->tarifa = $producto[0]->pro_incluyeiva == 'S' ? 12 : 0;
				$detalle->baseImponible = Input::get('totalFila')[$i + 1];
				$detalle->valor = Input::get('totalFila')[$i + 1] * ($detalle->tarifa / 100);
				$detalle->num_factura = Input::get('doc_num');
				
				$detalle->save();
			}

			//Actualiza en 1 el secuencial del documento
			$secuencia = SecuenciaDocumento::where('sec_tipo_documento','=','FA')->where('sec_estado','=','A')->get();
			$numero = $secuencia[0]->sec_final + 1;
			$id = $secuencia[0]->id;
			DB::update('update secuencia_documento set sec_final = ? where id = ?', array($numero, $id));
			
			$this->generaPDF($factura->id);
			
			$documentos = Documento::paginate(7);

			Session::flash('message', 'Datos guardados');
        	return Redirect::to('factura-electronica');

			//Redirect::to('secuencia-documento');
			//return Redirect::to('pages.factura-electronica.index')->with('datos', $documentos)->with('notice', 'Factura creada correctamente');	
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

	public function getProductById()
	{
		$producto = Producto::find(Input::get('producto'))->toArray();
	
		return json_encode($producto);
	}

	public function getClienteByCedula()
	{
		$cliente = Cliente::select('id', 'cli_identificacion', 'cli_nombres_apellidos', 'cli_direccion', 'cli_tel_convencional', 'cli_email')
					        ->where('cli_identificacion', '=', Input::get('cedula'))
					        ->where('cli_estado', '=', 'A')
					        ->get();

		if (count($cliente) == 1){
			return json_encode($cliente[0]->toArray());
		} else {
			return json_encode(array('id' => 0, 'respuesta' => 'Cliente no registrado'));
		}
		
	}

	public function getFacturaByNumero()
	{
		$factura = Documento::leftJoin('fe_detalle_factura', 'fe_cabecera_factura.id', '=', 'fe_detalle_factura.id')
						  ->where('fe_cabecera_factura.estab', '=', Input::get('fac_estab'))
					      ->where('fe_cabecera_factura.ptoEmi', '=', Input::get('fac_ptoEmi'))
					      ->where('fe_cabecera_factura.num_factura', '=', Input::get('fac_numero'))
					      ->get();

		if (count($factura) >= 1){
			return json_encode($factura[0]->toArray());
		} else {
			return json_encode(array('id' => 0, 'respuesta' => 'Factura no emitida'));
		}
		
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

		 $factura = Documento::find($facId);
		 $detalle = DetalleDocumento::where("id","=",$facId)->get();

	     $html = "<html><body>" .
	     		 "<table border='0' width='100%'>" .
	     		 "<tr>" .
	     		 	"<td width='45%' align='center'><img src='img/pys-logo.gif'><p><font face='Verdana' size='2'><b>" . utf8_decode($factura['razonSocial']) . "</b></font></p><p><font face='Verdana' size='1'><b>Matriz: </b>" . utf8_decode($factura['dirMatriz']) . "</font></p></td>" .
	     		 	"<td width='10%'>&nbsp;</td>" .
	     		 	"<td width='45%' align='center'><font face='Verdana' size='2'>FACTURA ELECTR&Oacute;NICA<p></font><font face='Verdana' size='1'><b>R.U.C.</b> " . $factura['ruc'] . "</p><p>No. Documento " . $factura['estab']  . "-" . $factura['ptoEmi'] . "-" . $factura['secuencial'] . 
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

	     if ($factura['estado'] != 'NUEVA') {
	     	 $html.= "<p><font face='Verdana' size='4'>" .  $factura['estado'] . "</font></p>";
	     }
	     

	     $html.= '</body></html>';

	     //return PDF::load($html, 'A4', 'portrait')->show();
	     PDF::load($html, 'A4', 'portrait')->download($factura['claveAcceso']);

	}

	public function facturaIntereses()
	{
		$total = -1;
		$errores = "";

		//Genera proceso para ingresar facturas
		if (Input::all()) {

			$total = 0;

			//Coge los valores de intereses para generar facturas
			$intereses = Interes::where('int_fecha', '=', Input::get('fecha_proceso'))
							    ->where('int_estado', '=', 'POR FACTURAR')
							    ->orderBy('int_codigocliente', 'desc')
							    ->get();

			foreach ($intereses as $key => $value) {						
				$cliente = Cliente::join('emisor', 'cliente.id_emi', '=', 'emisor.id')
							      ->join('catalogo', 'cliente.cli_tipo_identificacion', '=', 'catalogo.id')
							      ->where('cliente.cli_identificacion', "=", $value->int_ruc)
							      ->where('catalogo.cat_codigo_padre', "=", "04")
								  ->get();		

				//Si cliente existe en la bdd de clientes, procede a facturar
				if (sizeof($cliente) > 0) {
					$total++;

					//Busca secuencia de documentos
					$documento = SecuenciaDocumento::where("sec_tipo_documento", "=", "FA")
												   ->where("sec_estado","=","A")->get();

					$datos = array("doc_fecha" => Input::get('fecha_proceso'), "doc_estab" => $documento[0]->sec_estab, 
								   "doc_ptoemi" => $documento[0]->sec_ptoemi, "doc_num" => $documento[0]->sec_final);

					$token = $this->generarToken($datos, $cliente, "01");
					$factura = new Documento;
					$factura->ambiente = $cliente[0]->emi_tipo_ambiente;
					$factura->tipoEmision = $cliente[0]->emi_tipo_emision;
					$factura->razonSocial = $cliente[0]->emi_nombre;
					$factura->nombreComercial = $cliente[0]->emi_nombre_comercial;
					$factura->ruc = $cliente[0]->emi_ruc;
					$factura->claveAcceso = $token;
					$factura->codDoc = "01"; //Factura
					$factura->estab = $datos['doc_estab'];
					$factura->ptoEmi = $datos['doc_ptoemi'];
					$factura->secuencial = str_pad($datos['doc_num'], 9, '0', STR_PAD_LEFT);
					$factura->dirMatriz = $cliente[0]->emi_direccion_matriz;
					$factura->fechaEmision = date('Y-m-d', strtotime($datos['doc_fecha']));
					$factura->dirEstablecimiento = $cliente[0]->emi_direccion_matriz;
					$factura->contribuyenteEspecial = $cliente[0]->emi_nro_resolucion;
					$factura->obligadoContabilidad = $cliente[0]->emi_obligado_llevar_contabilidad;
					$factura->tipoIdentificacionComprador = $cliente[0]->cat_codigo_padre;
					$factura->razonSocialComprador = $cliente[0]->cli_nombres_apellidos;
					$factura->identificacionComprador = $value->int_ruc;
					$factura->totalSinImpuestos = $value->int_valor;
					$factura->totalDescuento = 0;
					$factura->codigo = 2; //Input::get('doc_iva') > 0 ? 2 : 0;
					$factura->codigoPorcentaje =  0;
					$factura->descuentoAdicional = 0;
					$factura->baseImponible = $value->int_valor;
					$factura->valor = 0;
					$factura->propina = 0; 
					$factura->importeTotal = $value->int_valor;
					$factura->moneda = "DOLAR";
					$factura->campoAdicional_emailCliente = is_null($cliente[0]->cli_email) ? $cliente[0]->emi_email : $cliente[0]->cli_email;
					$factura->campoAdicional_dirCliente = $cliente[0]->cli_direccion;
					$factura->campoAdicional_telfCliente = $cliente[0]->cli_tel_convencional;
					$factura->campoAdicional_numeroCliente = $cliente[0]->id;
					$factura->estado = $cliente[0]->emi_estado_documento;
					$factura->num_factura = $datos['doc_num'];
					$factura->totalsiniva = $value->int_valor;
					$factura->totalconiva = 0;
					$factura->usuario = 2; //Auth::user()->id;

					$factura->save();

					//Busca nombre del producto
					$producto = Producto::where("pro_cod_principal", "=", $value->int_tipo)->get();

					$detalle = new DetalleDocumento;
					$detalle->id = $factura->id;
					$detalle->ruc = $cliente[0]->emi_ruc;
					$detalle->estab = $datos['doc_estab'];
					$detalle->ptoEmi =  $datos['doc_ptoemi'];
					$detalle->secuencial = str_pad($datos['doc_num'], 9, '0', STR_PAD_LEFT);
					$detalle->codigoPrincipal = $value->int_tipo;
					$detalle->descripcion = $producto[0]->pro_nombre;
					$detalle->cantidad = 1;
					$detalle->precioUnitario = $value->int_valor;
					$detalle->descuento = 0;
					$detalle->precioTotalSinImpuesto = $value->int_valor;
					$detalle->codigo = 2; 
					$detalle->codigoPorcentaje = 0;
					$detalle->tarifa = 0;
					$detalle->baseImponible = $value->int_valor;;
					$detalle->valor = $value->int_valor;;
					$detalle->num_factura = $datos['doc_num'];
					
					$detalle->save();

					//Actualiza en 1 el secuencial del documento
					$secuencia = SecuenciaDocumento::where('sec_tipo_documento','=','FA')->where('sec_estado','=','A')->get();
					$numero = $secuencia[0]->sec_final + 1;
					$id = $secuencia[0]->id;
					DB::update('update secuencia_documento set sec_final = ? where id = ?', array($numero, $id));

					//Actualiza estado de intereses para no migrar
					DB::update('update intereses set int_estado = ? where int_codigocliente = ? and int_fecha = ? and int_tipo = ?', array('FACTURADO', $value->int_codigocliente, $value->int_fecha, $value->int_tipo));
				}
				else {
					$errores .= "[" . $value->int_codigocliente . "] ";
				}
			}

		}

		//Buscar facturas
		if(isset($_GET['search'])) {
			$documentos = Documento::where('usuario', '=', '2') 
								   ->where('secuencial', 'like', '%' . Input::get('search') . '%')
								   ->orderBy('num_factura', 'desc')
   							       ->paginate(7);			
		}
		else {
			$documentos = Documento::where('usuario', '=', '2')
								    ->orderBy('num_factura', 'desc')->paginate(7);
		}

		return View::make('pages.factura-electronica.facturaIntereses')->with('datos', $documentos)->with('registros',$total)->with('errores',$errores);			
	}

}
