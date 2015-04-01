 @extends('layouts.default')
 @section('content')

 <nav class="navbar navbar-inverse">
    <ul class="nav navbar-nav">
    <li><a href="{{ URL::to('emisor') }}">Emisores</a></li>
        <li><a href="{{ URL::to('emisor/create') }}">Nueva</a></li>


    </ul>
</nav>




<br/>
<div class="section">
    <div class="container">
        Listado Emisores
        {{--var_dump ($usuarios)--}}

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <td>ID</td>
                    <td>Ruc</td>
                    <td>Nombre</td>
                    <td>Nombre Comercial</td>
                    <td>Direccion</td>
                    <td>Obligado a llevar C.</td>
                    <td>Nro. Resolución</td>
                    <td>Acciones</td>
                </tr>
            </thead>
            <tbody>
                @foreach($datos as $key => $value)
                <tr>
                    <td>{{ $value->id }}</td>
                    <td>{{ $value->emi_ruc }}</td>
                    <td>{{ $value->emi_nombre }}</td>
                    <td>{{ $value->emi_nombre_comercial }}</td>
                    <td>{{ $value->emi_direccion_matriz }}</td>
                    <td>{{ $value->emi_obligado_llevar_contabilidad }}</td>
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
