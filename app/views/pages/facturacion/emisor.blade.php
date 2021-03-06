@extends('layouts.default')

@section('content')
 <div class="section">
            <div class="container">
              
                            <form class="form-horizontal" action="emisor.php" method="post">
                
                            <div class="form-group">
                                <label class="col-sm-2 control-label">
                                    RUC: 
                                </label>
                                <div class="col-sm-10">
              
                                        <input id="ruc" class="form-control" type="text" name="ruc" value="def_ruc" placeholder="RUC" required>
                       
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">
                                    Apellidos y Nombres - Razon Solcial:
                                </label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="nombres_apellidos_razon" value="def_nombres" placeholder="Apellidos y Nombres - Razón Social" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">
                                    Nombre Comercial:
                                </label>
                                <div class="col-sm-6">
                                    <input class="form-control" type="text" name="nombre_comercial" value="def_nombre" placeholder="Nombre Comercial">
                                </div>

                                <label class="col-sm-2">
                                    Empresa por defecto
                                </label>
                                <div class="col-sm-2">
                        
                                        <input type="checkbox" name="empresa_defecto" value="S">
   
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">
                                    Dirección Matriz: 
                                </label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" rows="1" name="direccion_matriz" placeholder="Dirección Matriz" required>def_direccion></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">
                                    Obligado a llevar contabilidad
                                </label>
                                <div class="col-sm-1">
  
                                        <input type="checkbox" name="obligado_cotablilidad" value="S" checked>
                               
                                </div>

                                <label class="col-sm-2 control-label">
                                    Contribuyente Especial Nro de resolución: 
                                </label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" name="nro_resolucion" value="def_nro_resolucion" placeholder="Nro. de Resolución">
                                </div>

                                <label class="col-sm-2 control-label">
                                    Tipo Emisión:
                                </label>
                                <div class="col-sm-3">
                                    <select class="form-control" name="tipo_emision" id="c_uniforme" onchange="location.href = '#">                        
                       
                                            <option value='codigo' selected>nombre</option>;
                               
                                    </select>
                                </div>

                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">
                                    Tiempo máximo de espera(segundos):
                                </label>
                                <div class="col-sm-1">
                                    <input class="form-control" type="number" name="max_time" min="3" max="9999" value="def_time" required>
                                </div>

                                <label class="col-sm-2 control-label">
                                    Tipo de Ambiente:
                                </label>
                                <div class="col-sm-2">
                                    <select class="form-control" name="tipo_ambiente" required>
                                        
                             
                                        
                                       
                                            <option value="1">PRODUCCION</option>
                                            <option value="2">PRUEBAS</option>
                                      
                                    </select>
                                </div>

                            </div>
                            <div class="form-group">
                                <div class="col-sm-8">
                                    
                                        <button type="submit" id="loading-example-btn" data-loading-text="Actualizando..." class="btn btn-primary">
                                            Actualizar
                                        </button>
    
                                        <button type="submit" id="loading-example-btn" data-loading-text="Guardando..." class="btn btn-primary">
                                            Guardar
                                        </button>

                                    <input class="btn btn-default" type="reset" name="borrar" value="Borrar">
                                </div>
                                <div class="col-sm-2 pull-right">
                                    <a href="emisor_listado.php?pagina=1">Ver Listado</a>
                                </div>
                            </div>
                        </form>

                        </div>
    </div>

@stop