@extends('layouts.default')

@section('content')


{{ HTML::ul($errors->all()) }}
<div class="section">

    <div class="container">
        {{ Form::open(array('url' => 'secuenciaDocumento', 'class' => 'form-horizontal',  'method' => 'post')) }}


        <div class="form-group">
            <label class="col-sm-2 label-control">Apellidos y Nombres - Razón Solcial: </label>
            <div class="col-sm-10">
            <input class="form-control" type="text" name="nombres_apellidos_razon" value="" placeholder="Apellidos y Nombres - Razón Social" required>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 label-control">
                Identificación: 
            </label>
            <div class="col-sm-4">
                <select class="form-control" name="identificacion_option" required>
                    <option value="01">RUC</option>
                    <option value="02">CEDULA</option>
                    <option value="03">PASAPORTE</option>
                </select>
            </div>
            <div class="col-sm-4">

                <input type="text" class="form-control" name="identificacion" value="" placeholder="Ingrese Identificación" required>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 label-control">
                Tipo Cliente:
            </label>
            <div class="col-sm-4">
                <select class="form-control" name="tipo_cliente">
                @foreach($catalogo as $key => $value)
                    <option value='{{$value->cat_codigo}}'>{{$value->cat_descripcion}}</option>
                @endforeach
                </select>
            </div>
            <label class="col-sm-1 control-label">
                Estado:
            </label>
            <div class="col-sm-4">
                <input type="radio" name="estado" value="A" checked>Activo <input type="radio" name="estado" value="I">Inactivo
            </div>                                  
        </div>
        <div class="form-group">
            <label class="col-sm-2 label-control">
                Dirección:
            </label>
            <div class="col-sm-10">
                <textarea class="form-control" rows="3" name="direccion" placeholder="Dirección" required></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 label-control">
                Teléfono Convencional: 
            </label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="tel" value="" placeholder="Teléfono">
            </div>

            <label class="col-sm-2 control-label">
                Teléfono Celular: 
            </label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="cel" value="" placeholder="Celular">
            </div>

        </div>
        <div class="form-group">
            <label class="col-sm-2 label-control">
                Correo:
            </label>
            <div class="col-sm-10">
                <input type="email" class="form-control" name="email" value="" placeholder="Correo Electrónico" required>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-8">
                 {{ Form::submit('Crear', array('class' => 'btn btn-primary')) }}
                <input class="btn btn-default" type="reset" name="borrar" value="Borrar">
            </div>
            <div class="col-sm-2 pull-right">
                <a href="{{URL::to('cliente')}}">Ver Listado</a>
            </div>
        </div>

        {{ Form::close() }}


    </div>
</div>

@stop