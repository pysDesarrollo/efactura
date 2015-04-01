@extends('layouts.default')

@section('content')

{{ HTML::ul($errors->all()) }}
<div class="section">

	<div class="container">
		{{ Form::open(array('url' => 'productos', 'class' => 'form-horizontal',  'method' => 'post')) }}

		<div class="form-group">
			<label class="col-sm-2 label-control">
				Código Principal: 
			</label>
			<div class="col-sm-4">

				<input class="form-control" type="text" name="cod_principal" value="" placeholder="Código Principal" required>

			</div>

			<label class="col-sm-2 control-label">
				Código Auxiliar: 
			</label>
			<div class="col-sm-4">
				<input class="form-control" type="text" name="cod_auxiliar" value="" placeholder="Código Auxiliar">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 label-control">
				Tipo de Producto: 
			</label>
			<div class="col-sm-4">
				<select class="form-control" name="tipo_producto">
					@foreach($catalogo as $key => $value)
						<option value="{{$value->id}}">{{$value->cat_descripcion}}</option>
					@endforeach

				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 label-control">
				Nombre: 
			</label>
			<div class="col-sm-10">
				<input class="form-control" type="text" name="nombre" value="" placeholder="Nombre" required>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 label-control">
				Valor Unitario: 
			</label>
			<div class="col-sm-4">
				<input class="form-control" type="text" name="valor_unitario" value="" placeholder="Valor Unitario" required>
			</div>

			<div class="col-sm-4">
				<b>Producto tiene Iva:</b> 
				
				<input type="checkbox" name="precio_incluye_iva" value="S" placeholder="Producto tiene Iva">

			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-8">
				{{ Form::submit('Crear', array('class' => 'btn btn-primary')) }}
				<input class="btn btn-default" type="reset" name="borrar" value="Borrar">
			</div>
			<div class="col-sm-2 pull-right">
				<a href="{{URL::to('productos')}}">Ver Listado</a>
			</div>
		</div>	

		{{ Form::close() }}


	</div>
</div>

@stop