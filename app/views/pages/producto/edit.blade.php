@extends('layouts.default')

@section('content')

<div class="section">
    <div class="container">
       {{ Form::model($producto, array('route' => array('productos.update', $producto->id), 'class' => 'form-horizontal',  'method' => 'PUT')) }}

		<div class="form-group">
			{{ Form::label('pro_cod_principal', 'Código Principal:', array('class' => 'col-sm-3 control-label')) }}
			<div class="col-sm-4">	
				{{ Form::text('pro_cod_principal', null, array('class' => 'form-control')) }}
			</div>
			{{ Form::label('pro_cod_aux', 'Código Auxiliar:', array('class' => 'col-sm-2 control-label')) }}
			<div class="col-sm-3">	
				{{ Form::text('pro_cod_aux', null, array('class' => 'form-control')) }}
			</div>
		</div>

		<div class="form-group">
			{{ Form::label('pro_tipo_producto', 'Tipo Producto:', array('class' => 'col-sm-3 control-label')) }}
			<div class="col-sm-4">	
				{{ Form::select('pro_tipo_producto', $catalogo, Input::old('pro_tipo_producto'), array('class' => 'form-control')); }}
			</div>
		</div>

		<div class="form-group">
			{{ Form::label('pro_nombre', 'Nombre:', array('class' => 'col-sm-3 control-label')) }}
			<div class="col-sm-9">	
				{{ Form::text('pro_nombre', null, array('class' => 'form-control')) }}
			</div>
		</div>

		<div class="form-group">
			{{ Form::label('pro_valor_unitario', 'Precio Unitario:', array('class' => 'col-sm-3 control-label')) }}
			<div class="col-sm-4">	
				{{ Form::text('pro_valor_unitario', null, array('class' => 'form-control')) }}
			</div>
			{{ Form::label('pro_incluyeiva', 'Producto tiene IVA:', array('class' => 'col-sm-2 control-label')) }}
			<div class="col-sm-3">
				&nbsp; 
				<input type="checkbox" id="pro_incluyeiva" name="pro_incluyeiva" value="S" <?php if ($producto->pro_incluyeiva == 'S') echo 'checked'; ?>>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-8">
				{{ Form::submit('Actualizar', array('class' => 'btn btn-primary')) }}
			</div>
			<div class="col-sm-2 pull-right">
				<a href="{{URL::to('productos')}}">Ver Listado</a>
			</div>
		</div>
		{{ Form::close() }}
    </div>
</div>

@stop