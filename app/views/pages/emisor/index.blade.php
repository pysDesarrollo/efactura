@extends('layouts.default')
@section('content')

<nav class="navbar navbar-inverse">
    <ul class="nav navbar-nav">
        <li>
            <a href="{{ URL::to('emisor/create') }}" class="a-header">
                <i class="fa fa-bank"></i> Nuevo emisor
            </a>
        </li>
    </ul>
</nav>
<div class="section">
    <div class="container">
        @if(Session::has('message'))
        <div class="row">
            <div class="col-md-12">
                <div class="mensajes">
                    {{ Session::get('message') }}
                </div>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="titulo-pagina"> <i class="fa fa-list-ul"></i> Listado de emisores</div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
<!--                        <th>ID</th>-->
                        <th>Ruc</th>
                        <th>Nombre</th>
                        <th>Nombre Comercial</th>
                        <th>Direccion</th>
                        <th>Obligado a <br>llevar C.</th>
                        <th>Nro. Resolución</th>
                        <th style="width: 90px">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($datos as $key => $value)
                    <tr>
<!--                        <td>{{ $value->id }}</td>-->
                        <td>{{ $value->emi_ruc }}</td>
                        <td>{{ $value->emi_nombre }}</td>
                        <td>{{ $value->emi_nombre_comercial }}</td>
                        <td>{{ $value->emi_direccion_matriz }}</td>
                        <td>{{ ($value->emi_obligado_llevar_contabilidad=='S')?'Si':'No' }}</td>
                        <td style="text-align: right">{{ $value->emi_nro_resolucion }}</td>
                        <td style="width: 90px">
                            <a class="pull-left btn btn-sm btn-success" href="{{ URL::to('emisor/' . $value->id . '/edit') }}" title="Editar">
                                <i class="fa fa-pencil"></i>
                            </a>

                            {{ Form::open(array('url' => 'emisor/' . $value->id, 'class' => 'pull-right', 'style' => 'display: inline-table')) }}
                            {{ Form::hidden('_method', 'DELETE') }}
                            <a href="#"  class="btn bg-danger btn-sm delete" title="Eliminar"><i class="fa fa-trash"></i></a>
<!--                            {{ Form::submit('Eliminar', array('class' => 'btn btn-small btn-warning')) }}-->
                            {{ Form::close() }}

                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
    $(function () {

        $(".delete").click(function(){
            var boton  = $(this)
            bootbox.confirm("Está seguro? Está acción no puede revertirse" ,function(result) {
                if(result){
                    boton.parent().submit()
                }
            });
//            bootbox.alert("Hello world!");
            return false
        });
    });
</script>
@stop
