<?php

class ProductoController extends \BaseController {

	public function index()
	{
		//
		$productos = Producto::all();
		return View::make('pages.producto.index')->with('datos', $productos);
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$catalogo = Catalogo::where('cat_codigo_padre','=','03')->get();
		//$emisores = Emisor::all()->lists('emi_nombre', 'id');
		return View::make('pages.producto.create')->with('catalogo',$catalogo); //->with('datos',$emisores);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//var_dump(Input::all());

		$producto = new Producto;
						$producto->pro_cod_principal = Input::get('cod_principal');
						$producto->pro_cod_aux = Input::get('cod_auxiliar');
						$producto->pro_nombre = Input::get('nombre');
						$producto->pro_valor_unitario = Input::get('valor_unitario');
						$producto->pro_incluyeiva = Input::has('precio_incluye_iva') ? 'S' : 'N';
						$producto->pro_tipo_producto = Input::get('tipo_producto');
						$producto->pro_fecha_crea = Input::get('max_time');
						
						$producto->save();
		
		$productos = Producto::all();
		return View::make('pages.producto.index')->with('datos', $productos);
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
		$producto = Producto::find($id);
		$catalogo = Catalogo::where('cat_codigo_padre', '=', '03')->lists('cat_descripcion', 'id');
		return View::make('pages.producto.edit')->with('producto',$producto)->with('catalogo',$catalogo);
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
		$producto = Producto::find($id);

		$producto->pro_cod_principal  = Input::get('pro_cod_principal');
		$producto->pro_cod_aux  = Input::get('pro_cod_aux');
		$producto->pro_nombre  = Input::get('pro_nombre');
		$producto->pro_valor_unitario  = Input::get('pro_valor_unitario');
		$producto->pro_incluyeiva  = Input::has('pro_incluyeiva') ? Input::get('pro_incluyeiva') : 'N';
		$producto->pro_tipo_producto  = Input::get('pro_tipo_producto');

		$producto->save();
				
		return Redirect::to('productos');

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
