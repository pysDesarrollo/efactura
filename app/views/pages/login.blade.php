@extends('layouts.default')

@section('content')

<div class="section">
	    	<div class="container">
	    		<div class="row">
		    		<div class="col-sm-12 social-login">
		    			<p>Ingreso al sistema</p>
		    		</div>
	    		</div>
				<div class="row">
					<div class="col-sm-offset-3 col-sm-5">
						{{ Form::open(array('url' => 'login', 'method' => 'post')) }}	
						<div class="basic-login">
							<form role="form" role="form">
								<div class="form-group">
		        				 	<label for="username"><i class="icon-user"></i> <b>Usuario</b></label>
									{{ Form::text('username', Input::old('username'), array('class' => 'inputbox form-control', 'placeholder' => 'Usuario')) }}
								</div>
								<div class="form-group">
		        				 	<label for="password"><i class="icon-lock"></i> <b>Contrase&ntilde;a</b></label>
									{{ Form::password('password', array('class' => 'inputbox form-control', 'placeholder' => 'Password')) }}
								</div>
								<div class="form-group">
									<a href="page-password-reset.html" class="forgot-password">Forgot password?</a>
									<button type="submit" class="btn pull-right">Login</button>
									<div class="clearfix"></div>
								</div>
							</form>
						</div>
						{{ Form::close() }}	
					</div>
				</div>
				<br>
				<div class="container">
					@if ($errors->any())
			       		@foreach ($errors->all() as $error)
				        	<div class="alert alert-danger">{{ $error }}</div>
						@endforeach
					@endif
				</div>
			</div>
</div>
@stop