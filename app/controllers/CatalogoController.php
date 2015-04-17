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
        $categoria = Catalogo::where("cat_codigo_padre","=","")->get()->toArray();
        $categoria =array_merge($categoria,Catalogo::whereNull("cat_codigo_padre")->get()->toArray());
//       dd($categoria);
        $catalogos = Catalogo::all();
        return View::make('pages.catalogo.index')->with('datos', $catalogos)->with('categorias', $categoria);
    }

    public function getCatalogoByPadre($id){
        $catalogos = Catalogo::where("cat_codigo_padre","=",$id)->get();
        // dd($id);
        return View::make('pages.catalogo.lista')->with('datos', $catalogos);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        $categoria = Catalogo::where("cat_codigo_padre","=","")->get()->toArray();
        $categoria =array_merge($categoria,Catalogo::whereNull("cat_codigo_padre")->get()->toArray());
//        dd($categoria);
        $catalogo = new Catalogo();
        return View::make('pages.catalogo.create')->with('categorias', $categoria)->with("objeto",$catalogo);
    }

    public function createCategoria()
    {

        $catalogo = new Catalogo();
        return View::make('pages.catalogo.createCategoria')->with("objeto",$catalogo);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $ip = Request::getClientIp();
        if(Input::get('id')==""){
            // echo "nuevo";
            $catalogo = new Catalogo();
        }else{
            //  echo "update";
            $catalogo = Catalogo::find(Input::get('id'));
        }

        $catalogo->cat_codigo = Input::get('cat_codigo');
        $catalogo->cat_codigo_padre = Input::get('codigo_padre');
        $catalogo->cat_descripcion = Input::get('descripcion');
        $catalogo->cat_referencia = Input::get('cat_referencia');
        $catalogo->cat_valor = Input::get('cat_valor');

        $catalogo->save();
        Session::flash('message', 'Datos guardados');
        return Redirect::to('catalogo');
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
        $catalogo = Catalogo::find($id);
        $categoria = Catalogo::where("cat_codigo_padre","=","")->get()->toArray();
        $categoria =array_merge($categoria,Catalogo::whereNull("cat_codigo_padre")->get()->toArray());

        return View::make('pages.catalogo.create')->with("objeto",$catalogo)->with('categorias',$categoria);
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
        $catologo = Catalogo::find($id);
        $catologo->delete();
        Session::flash('message', 'registro eliminado');
        return Redirect::to('catalogo');
    }


}
