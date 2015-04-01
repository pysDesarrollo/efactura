 @extends('layouts.default')
 @section('content')

 <nav class="navbar navbar-inverse">
    <ul class="nav navbar-nav">
    <li><a href="{{ URL::to('cliente') }}">Cliente</a></li>
        <li><a href="{{ URL::to('cliente/create') }}">Nueva</a></li>


    </ul>
</nav>




<br/>
<div class="section">
    <div class="container">
        Listado Clientes
        {{--var_dump ($usuarios)--}}

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <td>ID</td>
                    <td>Tipo ID</td>
                    <td>Nombre</td>
                    <td>Direacción</td>
                    <td>Teléfono</td>
                    <td>Email</td>
                    <td>Estado</td>
                    <td>Acciones</td>
                </tr>
            </thead>
            <tbody>
                @foreach($datos as $key => $value)
                <tr>
                    <td>{{ $value->cli_identificacion }}</td>
                    <td>{{ $value->cli_tipo_identificacion }}</td>
                    <td>{{ $value->cli_nombres_apellidos }}</td>
                    <td>{{ $value->cli_direccion }}</td>
                    <td>{{ $value->cli_tel_convencional }}</td>
                    <td>{{ $value->cli_email }}</td>
                    <td>{{ $value->emi_nro_resolucion }}</td>

                    <!-- we will also add show, edit, and delete buttons -->
                    <td>

                        <!-- edit this nerd  -->
                        <a class="pull-left btn btn-small btn-success" href="{{ URL::to('usuarios/' . $value->id . '/edit') }}">Actualizar</a>

                        <!-- delete this nerd  -->
                        {{ Form::open(array('url' => 'usuarios/' . $value->id, 'class' => 'pull-right')) }}
                        {{ Form::hidden('_method', 'DELETE') }}
                        {{ Form::submit('Eliminar', array('class' => 'btn btn-small btn-warning')) }}
                        {{ Form::close() }}

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@stop
