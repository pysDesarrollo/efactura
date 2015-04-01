@extends('layouts.default')

@section('content')

<div class="section">
    <div class="container">
       {{ Form::model($cliente, array('route' => array('cliente.update', $cliente->id), 'class' => 'form-horizontal',  'method' => 'PUT')) }}

		<div class="form-group">
			{{ Form::label('cli_nombres_apellidos', 'Apellidos y Nombres - Razón Social:', array('class' => 'col-sm-3 control-label')) }}
			<div class="col-sm-9">	
				{{ Form::text('cli_nombres_apellidos', null, array('class' => 'form-control')) }}
			</div>
		</div>

		<div class="form-group">
			{{ Form::label('cli_tipo_identificacion', 'Identificación:', array('class' => 'col-sm-3 control-label')) }}
			<div class="col-sm-4">	
				{{ Form::select('cli_tipo_identificacion', $identificacion, Input::old('cli_tipo_identificacion'), array('class' => 'form-control')); }}
			</div>
			<div class="col-sm-5">
				{{ Form::text('cli_identificacion', null, array('class' => 'form-control')) }}
			</div>
		</div>

		<div class="form-group">
			{{ Form::label('cli_tipo_cliente', 'Tipo Cliente:', array('class' => 'col-sm-3 control-label')) }}
			<div class="col-sm-4">	
				{{ Form::select('cli_tipo_cliente', $catalogo, Input::old('cli_tipo_cliente'), array('class' => 'form-control')); }}
			</div>
			{{ Form::label('cli_estado', 'Estado:', array('class' => 'col-sm-2 control-label')) }}
			<div class="col-sm-3">
				{{ Form::radio('cli_estado', 'A') }} Activo &nbsp; &nbsp;
				{{ Form::radio('cli_estado', 'I') }} Inactivo
			</div>
		</div>

		<div class="form-group">
			{{ Form::label('cli_direccion', 'Dirección:', array('class' => 'col-sm-3 control-label')) }}
			<div class="col-sm-9">	
				{{ Form::textarea('cli_direccion', null, array('rows' => 3, 'class' => 'form-control')) }}
			</div>
		</div>

		<div class="form-group">
			{{ Form::label('cli_tel_convencional', 'Teléfono Convencional:', array('class' => 'col-sm-3 control-label')) }}
			<div class="col-sm-4">	
				{{ Form::text('cli_tel_convencional', null, array('class' => 'form-control')) }}
			</div>
			{{ Form::label('cli_tel_celular', 'Teléfono Celular:', array('class' => 'col-sm-2 control-label')) }}
			<div class="col-sm-3">	
				{{ Form::text('cli_tel_celular', null, array('class' => 'form-control')) }}
			</div>
		</div>

		<div class="form-group">
			{{ Form::label('cli_email', 'Correo:', array('class' => 'col-sm-3 control-label')) }}
			<div class="col-sm-9">	
				{{ Form::text('cli_email', null, array('class' => 'form-control')) }}
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-8">
				{{ Form::submit('Actualizar', array('class' => 'btn btn-primary')) }}
			</div>
			<div class="col-sm-2 pull-right">
				<a href="{{URL::to('cliente')}}">Ver Listado</a>
			</div>
		</div>
		{{ Form::close() }}
    </div>
</div>

@stop