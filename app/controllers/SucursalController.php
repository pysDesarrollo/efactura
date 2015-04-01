<?php

class SucursalController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		$emisores = Emisor::all();
		return View::make('pages.emisor.index')->with('datos', $emisores);
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
		$catalogo = Catalogo::where('cat_codigo_padre','=','01')->get();
		$emisores = Emisor::all()->lists('emi_nombre', 'id');
		return View::make('pages.emisor.create')->with('catalogo',$catalogo)->with('datos',$emisores);
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//var_dump(Input::all());
		
		$emisor = new Emisor;
						$emisor->emi_ruc = Input::get('ruc');
						$emisor->emi_nombre = Input::get('nombres_apellidos_razon');
						$emisor->emi_nombre_comercial = Input::get('nombre_comercial');
						$emisor->emi_direccion_matriz = Input::get('direccion_matriz');
						$emisor->emi_obligado_llevar_contabilidad = Input::get('obligado_cotablilidad');
						$emisor->emi_nro_resolucion = Input::get('nro_resolucion');
						$emisor->emi_tiempo_max_espera = Input::get('max_time');
						$emisor->emi_tipo_ambiente = Input::get('tipo_ambiente');
						$emisor->emi_tipo_emision = Input::get('tipo_emision');
						$emisor->emi_defecto = Input::get('empresa_defecto');
						
						$emisor->save();
		
		$emisores = Emisor::all();

		return View::make('pages.emisor.index')->with('datos', $emisores);
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
	


}
