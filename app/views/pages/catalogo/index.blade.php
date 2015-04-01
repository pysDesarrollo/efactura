 @extends('layouts.default')
 @section('content')

 <nav class="navbar navbar-inverse">
    <ul class="nav navbar-nav">
    <li><a href="{{ URL::to('catalogo') }}">Catálogo</a></li>
        <li><a href="{{ URL::to('catalogo/create') }}">Nueva</a></li>


    </ul>
</nav>

<br/>
<div class="section">
    <div class="container">
        Listado Catálogo
        {{--var_dump ($usuarios)--}}

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <td>ID</td>
                    <td>Código</td>
                    <td>Descripcion</td>
                    <td>Código padre</td>
                    <td>Acciones</td>
                </tr>
            </thead>
            <tbody>
                @foreach($datos as $key => $value)
                <tr>
                    <td>{{ $value->id }}</td>
                    <td>{{ $value->cat_codigo }}</td>
                    <td>{{ $value->cat_descripcion }}</td>
                    <td>{{ $value->cat_codigo_padre }}</td>

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
