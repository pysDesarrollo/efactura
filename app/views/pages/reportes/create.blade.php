@extends('layouts.default')
@section('content')

<div class="section">

	<div class="container">
		{{ Form::open(array('url' => 'productos', 'class' => 'form-horizontal',  'method' => 'post')) }}

		<div class="form-group">
			<label class="col-sm-2 label-control">
				Fecha Inicial: 
			</label>
			<div class="col-sm-4">
				<input class="form-control" type="text" id="fechaInicial" value="" placeholder="" required>
			</div>

			<label class="col-sm-2 control-label">
				Fecha Final: 
			</label>
			<div class="col-sm-4">
				<input class="form-control" type="text" id="fechaFinal" value="" placeholder="">
			</div>

		<div class="form-group">
			<div class="col-sm-4">
				{{ Form::submit('Crear', array('class' => 'btn btn-primary')) }}
			</div>
		</div>	

		{{ Form::close() }}
		
		</div>
	</div>
</div>

@stop	