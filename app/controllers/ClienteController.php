<?php

class ClienteController extends \BaseController {

	public function index()
	{

		if (isset($_GET['search'])) {
			$clientes = Cliente::where('cli_identificacion', 'like', Input::get('search') . '%')
						       ->orWhere('cli_nombres_apellidos', 'like', '%' . Input::get('search') . '%')
							   ->paginate(10);
		}
		else {
			$clientes = Cliente::paginate(10);
		}

		return View::make('pages.cliente.index')->with('datos', $clientes);
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$catalogo = Catalogo::where('cat_codigo_padre','=','02')->get();
		$identificacion = Catalogo::where('cat_codigo_padre','=','04')->get();

		return View::make('pages.cliente.create')->with('catalogo', $catalogo)->with('identificacion', $identificacion);

	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{

	    $rules = array(
	       'nombres_apellidos_razon' => 'required',
	       'identificacion' => 'required|min:10',
	       'direccion' => 'required',
	       'tel' => 'required|numeric',
	       'email' => 'required|email',
	    );

	    $errors = array(
		  'required' => 'El campo :attribute es obligatorio',
          'min' => 'El campo :attribute no puede tener menos de :min carácteres',
	      'date' => 'El formato de la fecha es incorrecto',
	      'numeric' => 'El campo :attribute debe ser numérico',
	      'email' => 'El campo :attribute no es correcto',
	    );

		$validator = Validator::make(Input::all(), $rules, $errors); 		
		if ($validator->fails()){
			$catalogo = Catalogo::where('cat_codigo_padre','=','02')->get();
			$identificacion = Catalogo::where('cat_codigo_padre','=','04')->get();

			return Redirect::to('cliente/create')->withErrors($validator)->withInput()
				           ->with('catalogo',$catalogo)->with('identificacion',$identificacion);

		}
		else {		
			$cliente = new Cliente;
							$cliente->cli_nombres_apellidos = Input::get('nombres_apellidos_razon');
							$cliente->cli_tipo_identificacion = Input::get('cli_tipo_identificacion');
							$cliente->cli_identificacion = Input::get('identificacion');
							$cliente->cli_direccion = Input::get('direccion');
							$cliente->cli_tel_convencional = Input::get('tel');
							$cliente->cli_tel_celular = Input::get('cel');
							$cliente->cli_email = Input::get('email');
							$cliente->cli_estado = Input::get('estado');
							$cliente->cli_tipo_cliente = Input::get('tipo_cliente');
							$cliente->id_emi = 1;

							$cliente->save();
			
			$clientes = Cliente::paginate(10);
			return View::make('pages.cliente.index')->with('datos', $clientes);
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
		$cliente = Cliente::find($id);
		$catalogo = Catalogo::where('cat_codigo_padre','=','02')->lists('cat_descripcion','id');
		$identificacion = Catalogo::where('cat_codigo_padre','=','04')->lists('cat_descripcion','id');
		return View::make('pages.cliente.edit')->with('cliente', $cliente)->with('catalogo', $catalogo)->with('identificacion', $identificacion);
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
		$cliente = Cliente::find($id);
		
		$cliente->cli_nombres_apellidos  = Input::get('cli_nombres_apellidos');
		$cliente->cli_tipo_identificacion = Input::get('cli_tipo_identificacion');
		$cliente->cli_identificacion = Input::get('cli_identificacion');
		$cliente->cli_direccion = Input::get('cli_direccion');
		$cliente->cli_tel_convencional = Input::get('cli_tel_convencional');
		$cliente->cli_tel_celular = Input::get('cli_tel_celular');
		$cliente->cli_email =Input::get('cli_email');
		$cliente->cli_tipo_cliente =Input::get('cli_tipo_cliente');
		$cliente->cli_estado =Input::get('cli_estado');

		$cliente->save();
				
		return Redirect::to('cliente');
	}

}
