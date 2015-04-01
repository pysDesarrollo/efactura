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
        
        <table class="table table-striped table-bordered">
        <caption><h3>Listado de Clientes</h3></caption>
            <thead>
                <tr>
                    <th>RUC/Cédula</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($datos as $key => $value)
                <tr>
                    <td>{{ $value->cli_identificacion }}</td>
                    <td>{{ $value->cli_nombres_apellidos }}</td>
                    <td>{{ $value->cli_tel_convencional }}</td>
                    <td>{{ $value->cli_email }}</td>
                    <td>{{ $value->cli_estado }}</td>

                    <!-- we will also add show, edit, and delete buttons -->
                    <td>

                        <!-- edit this nerd  -->
                        <a class="pull-left btn btn-small btn-success" href="{{ URL::to('cliente/' . $value->id . '/edit') }}">Actualizar</a>

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
        <div class="form-group">
        {{ Form::open(array('url' => 'cliente', 'class' => 'form-inline', 'role' => 'form', 'method' => 'get')) }}
            {{ Form::text('search', Input::get("search"), array('class' => 'form-control')) }}
            {{ Form::submit('Buscar', array('class' => 'btn btn-primary')) }}
        {{ Form::close()}}
        </div>

        <div class="pagination">
            {{ $datos->appends(array("search" => Input::get("search")))->links() }}
        </div>
    </div>
</div>

@stop
