<?php

class CatalogoController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		$catalogos = Catalogo::all();
		return View::make('pages.catalogo.index')->with('datos', $catalogos);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
		$emisores = Catalogo::all()->lists('cat_codigo', 'id');
		return View::make('pages.catalogo.create')->with('emisores', $emisores);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
		$catalogo = new Catalogo;
						$catalogo->cat_codigo = Input::get('codigo');
						$catalogo->cat_codigo_padre = Input::get('codigo_padre');
						$catalogo->cat_descripcion = Input::get('descripcion');
						
						$catalogo->save();
		
		$catalogos = Catalogo::all();
		return View::make('pages.catalogo.index')->with('datos', $catalogos);
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
