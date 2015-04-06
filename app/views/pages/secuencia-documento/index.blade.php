@extends('layouts.default')
@section('header')
@stop
@section('content')
<nav class="navbar navbar-inverse">
    <ul class="nav navbar-nav">
        <li>
            <a href="{{ URL::to('secuencia-documento/create') }}"  class="a-header" >
                <i class="fa fa-file-text-o"></i> Nuevo
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
                <div class="titulo-pagina"> <i class="fa fa-list-ul"></i> Listado de Secuenciales</div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <!--                <td>ID</td>-->
                        <th>Tipo de documento</th>
                        <th>Establecimiento</th>
                        <th>Emisión</th>
                        <th>Inicial</th>
                        <th>Final</th>
                        <th>Estado</th>
                        <th style="width: 90px">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($datos as $key => $value)
                    <tr>
                        <td>{{ $value->sec_tipo_documento }}</td>
                        <td>{{ $value->sec_estab }}</td>
                        <td>{{ $value->sec_ptoemi }}</td>
                        <td class="numero">{{ $value->sec_inicial }}</td>
                        <td class="numero">{{ $value->sec_final }}</td>
                        <td style="">
                            {{ ($value->sec_estado=="A")?"Activo":"Inactivo" }}
                        </td>

                        <!-- we will also add show, edit, and delete buttons -->
                        <td>
                            <!-- edit this nerd  -->
                            <a class="btn btn-sm btn-success" href="{{ URL::to('secuencia-documento/' . $value->id . '/edit') }}" title="Editar">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <!-- delete this nerd  -->
                            {{ Form::open(array('url' => 'secuencia-documento/' . $value->id, 'style' => 'display: inline-table')) }}
                            {{ Form::hidden('_method', 'DELETE') }}
                            <a href="#"  class="btn bg-danger btn-sm delete" title="Eliminar"><i class="fa fa-trash"></i></a>
                            <!--                    {{ Form::submit('', array('class' => 'btn btn-small btn-warning','title'=>'Eliminar','html'=>'<i class="fa fa-trash"></i>')) }}-->
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
