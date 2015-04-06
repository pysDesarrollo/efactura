<?php

class SecuenciaDocumentoController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $secuenciales = SecuenciaDocumento::all();
        return View::make('pages.secuencia-documento.index')->with('datos', $secuenciales);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
//		$catalogo = Catalogo::where('cat_codigo_padre','=','01')->get();
//		$emisores = Emisor::all()->lists('emi_nombre', 'id');
        $secuencia = new SecuenciaDocumento();
        return View::make('pages.secuencia-documento.create')->with("objeto",$secuencia);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //var_dump(Input::get('id'));
        $ip = Request::getClientIp();
        if(Input::get('id')==""){
           // echo "nuevo";
            $secuencia = new SecuenciaDocumento();
        }else{
          //  echo "update";
            $secuencia = SecuenciaDocumento::find(Input::get('id'));
        }
       // var_dump($secuencia);
        $secuencia->sec_tipo_documento = Input::get('sec_tipo_documento');
        $secuencia->sec_estab = Input::get('sec_estab');
        $secuencia->sec_ptoemi = Input::get('sec_ptoemi');
        $secuencia->sec_inicial = Input::get('sec_inicial');
        $secuencia->sec_final = Input::get('sec_final');
        $secuencia->sec_final = Input::get('sec_final');
        $secuencia->sec_estado = "A";
        $secuencia->sec_ip_crea=$ip;
        $secuencia->sec_fecha_crea=new DateTime();
        $secuencia->sec_usuario_crea=Auth::user()->usu_ruc;

        $secuencia->save();
        Session::flash('message', 'Datos guardados');
        return Redirect::to('secuencia-documento');
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
        $secuencia = SecuenciaDocumento::find($id);
        return View::make('pages.secuencia-documento.create')->with("objeto",$secuencia);

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
        $secuencia = SecuenciaDocumento::find($id);
        $secuencia->delete();
        Session::flash('message', 'Secuencial documento eliminado');
        return Redirect::to('secuencia-documento');

    }



}
