@extends('layouts.default')
@section('content')

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
            <div class="col-sm-4">
                <input class="form-control" type="text" id="fecha_proceso" name="fecha_proceso" value="<?php echo date('Ym'); ?>" required >
            </div>

        </div>
        <div class="form-group">
            <div class="col-sm-8">
                {{ Form::submit('Subir Retenciones', array('class' => 'btn btn-primary')) }}
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
                        <a class="pull-left btn btn-small btn-success" href="{{ URL::to('getFC/' . $value->id) }}">Visualizar</a>

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

@stop  