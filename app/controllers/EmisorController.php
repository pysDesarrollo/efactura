<?php
class EmisorController extends \BaseController {
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
        $emisor = new Emisor();
        return View::make('pages.emisor.create')->with('catalogo',$catalogo)->with('datos',$emisores)->with('objeto',$emisor);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //var_dump(Input::all());
        $ip = Request::getClientIp();
        if(Input::get('id')==""){
            // echo "nuevo";
            $emisor = new Emisor();
        }else{
            //  echo "update";
            $emisor = Emisor::find(Input::get('id'));
        }
//		$emisor = new Emisor;
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
        Session::flash('message', 'Datos guardados');
//        $emisores = Emisor::all();
//        return View::make('pages.emisor.index')->with('datos', $emisores);
        return Redirect::to('emisor');
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
        $catalogo = Catalogo::where('cat_codigo_padre','=','01')->get();
        $emisor = Emisor::find($id);
//        var_dump($emisor);
        return View::make('pages.emisor.create')->with("objeto",$emisor)->with('catalogo',$catalogo);
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
        $emisor = Emisor::find($id);
        $emisor->delete();
        Session::flash('message', 'Emisor eliminado');
        return Redirect::to('emisor');
    }

    public function getProductById()
    {
        $producto = Producto::find(Input::get('producto'))->toArray();

        return json_encode($producto);
    }

    public function getClienteByCedula()
    {
        $cliente = Cliente::select('id', 'codigo_cliente', 'nombre_cliente', 'direccion_cliente', 'telefono_cliente')->where('ruc_cliente', '=', Input::get('cedula'))->get();
        if (count($cliente) == 1){
            return json_encode($cliente[0]->toArray());
        } else {
            return json_encode(array('id' => 0000001, 'respuesta' => 'Cliente no registrado'));
        }

    }

    public function createCliente() {
        $cliente = new Cliente;
        $cliente->codigo_cliente = '0000325';
        $cliente->nombre_cliente = Input::get('nombre');
        $cliente->direccion_cliente = Input::get('direccion');
        $cliente->telefono_cliente = Input::get('telefono');
        $cliente->ruc_cliente = Input::get('ruc');

        $cliente->save();

        return json_encode(array('result' => 'ok'));
    }
}