@extends('layouts.default')
@section('content')
<style type="text/css">
    #mensaje{
        padding: 5px;
        color: red;
        font-weight: bold;
        font-size: 12px;
    }
</style>

<nav class="navbar navbar-inverse">
    <ul class="nav navbar-nav">
        <li>
            <a href="{{  URL::to('retenciones') }}" class="a-header">
                <i class="fa fa-list-ul"></i> Ver Listado
            </a>
        </li>

    </ul>
</nav>

<br/>

<div class="section">

    <div class="container">
        {{ Form::open(array('url' => 'genera-retenciones', 'class' => 'form-horizontal',  'method' => 'post')) }}

        @if($errors->has())
        @foreach ($errors->all() as $error)
        <div class="alert alert-danger"> {{ $error }} </div>
        @endforeach
        @endif

        <div class="form-group">
            <label class="col-sm-2 label-control">
                Fecha:
            </label>
            <div class="col-sm-3">
                <div class="input-group">
                    <input class="form-control input-sm" type="text" id="fecha_proceso" name="fecha_proceso" value="<?php echo date('Ym'); ?>" required >
                    <span class="input-group-addon" style="background: #F0AD4E !important;font-weight: bold">
                        Ej: 201501
                    </span>
                </div>
            </div>
            <div class="col-sm-7" id="mensaje">

            </div>

        </div>
        <div class="form-group">
            <div class="col-sm-8">
                {{ Form::submit('Subir Retenciones', array('class' => 'btn btn-primary btn-subir')) }}
            </div>
        </div>

        @if(isset($registros))
        @if($registros >= 0)
        <div class="alert alert-success"> {{ "Se ha(n) procesado " . $registros . " registro(s)" }} </div>
        @endif
        @endif

        @if(isset($errores))
        @if($errores != "")
        <div class="alert alert-danger"> {{ "Clientes con error: " . $errores; }} </div>
        @endif
        @endif

        {{ Form::close() }}

        @if(isset($registros))
        @if($registros > 0)
        <table class="table table-striped table-bordered">
            <caption><h3>Listado de Retenciones Procesadas</h3></caption>
            <thead>
            <tr>
                <th>No. Retención</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Clave Acceso</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($datos as $key => $value)
            <tr>
                <td>{{ $value->estab . $value->ptoEmi . $value->secuencial  }}</td>
                <td>{{ date('Y-m-d', strtotime($value->fechaEmision)) }}</td>
                <td>{{ $value->identificacionSujetoRetenido }}</td>
                <td>{{ $value->claveAcceso }}</td>

                <!-- we will also add show, edit, and delete buttons -->
                <td>{{ $value->estado }}</td>
                <td>

                    <!-- edit this nerd  -->
                    <a class="pull-left btn btn-small btn-success" href="{{ URL::to('getRT/' . $value->id) }}">Visualizar</a>

                </td>
            </tr>
            @endforeach
            </tbody>
        </table>

        {{ Form::open(array('url' => 'genera-retenciones', 'class' => 'form-inline', 'role' => 'form', 'method' => 'get')) }}
        {{ Form::text('search', Input::get("search"), array('class' => 'form-control')) }}
        {{ Form::submit('Buscar', array('class' => 'btn btn-primary')) }}
        {{ Form::close()}}

        <div class="pagination">
            {{ $datos->appends(array("search" => Input::get("search")))->links() }}
        </div>

        @endif
        @endif

    </div>

</div>
<script type="text/javascript">
    $(".btn-subir").click(function(){
        var valor = $("#fecha_proceso").val();
        var mes;
        var anio;
        var actual = new Date().getFullYear()*1;
        var mensaje = "";
        if(valor.length!=6){
            mensaje="Por favor, ingrese una fecha correcta con el formato 'añomes' (yyyymm) ejemplo: 201501 (6 números) ";
        }else{
            if(isNaN(valor)){
                mensaje="Por favor, ingrese solo números con el formato 'añomes' (yyyymm) ejemplo: 201501 ";
            }else{
                mes = valor.substr(4);
                anio = valor.substr(0,4);
//            console.log(anio,mes,actual)
                if(mes*1>12){
                    mensaje="Por favor, ingrese un mes valido ";
                }
                if(anio*1<2015 || anio*1>actual){
                    mensaje="Por favor, ingrese un año valido, mínimo 2015 ";
                }

            }
        }

        if(mensaje!=""){
            $("#mensaje").html(mensaje).show("slide")
            return false
        }else{
            return true
        }



    })
</script>
@stop