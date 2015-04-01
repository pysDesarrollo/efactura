 @extends('layouts.default')
 @section('content')

 <nav class="navbar navbar-inverse">
    <ul class="nav navbar-nav">
    <li><a href="{{ URL::to('productos') }}">Producto</a></li>
        <li><a href="{{ URL::to('productos/create') }}">Nueva</a></li>
    </ul>
</nav>

<br/>
<div class="section">
    <div class="container">

        <table class="table table-striped table-bordered">
        <caption><h3>Listado de Productos</h3></caption>
            <thead>
                <tr>
                    <th>Cod. Principal</th>
                    <th>Cod. Auxiliar</th>
                    <th>Nombre</th>
                    <th>Valor Unitario</th>
                    <th>Tiene IVA</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($datos as $key => $value)
                <tr>
                    <td>{{ $value->pro_cod_principal }}</td>
                    <td>{{ $value->pro_cod_aux }}</td>
                    <td>{{ $value->pro_nombre }}</td>
                    <td>{{ $value->pro_valor_unitario }}</td>
                    <td>{{ $value->pro_incluyeiva }}</td>

                    <!-- we will also add show, edit, and delete buttons -->
                    <td>

                        <!-- edit this nerd  -->
                        <a class="pull-left btn btn-small btn-success" href="{{ URL::to('productos/' . $value->id . '/edit') }}">Actualizar</a>

                        <!-- delete this nerd  -->
                        {{ Form::open(array('url' => 'productos/' . $value->id, 'class' => 'pull-right')) }}
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
