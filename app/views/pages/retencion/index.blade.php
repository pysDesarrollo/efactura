 @extends('layouts.default')
 @section('content')

 <nav class="navbar navbar-inverse">
    <ul class="nav navbar-nav">
    <li><a href="{{ URL::to('retenciones') }}">Retención</a></li>
        <li><a href="{{ URL::to('retenciones/create') }}">Nueva</a></li>
    </ul>
</nav>

<br/>
<div class="section">
    <div class="container">

        <table class="table table-striped table-bordered">
        <caption><h3>Listado de Retenciones</h3></caption>
            <thead>
                <tr>
                    <th>No. Retención</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Clave Acceso</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($datos as $key => $value)
                <tr>
                    <td>{{ $value->estab . $value->ptoEmi . $value->secuencial  }}</td>
                    <td>{{ date('Y-m-d', strtotime($value->fechaEmision)) }}</td>
                    <td>{{ $value->identificacionSujetoRetenido }}</td>
                    <td>{{ $value->claveAcceso }}</td>

                    <!-- we will also add show, edit, and delete buttons -->
                    <td>

                        <!-- edit this nerd  -->
                        <a class="pull-left btn btn-small btn-success" href="{{ URL::to('getFC/' . $value->id) }}">Visualizar</a>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ Form::open(array('url' => 'retenciones', 'class' => 'form-inline', 'role' => 'form', 'method' => 'get')) }}
            {{ Form::text('search', Input::get("search"), array('class' => 'form-control')) }}
            {{ Form::submit('Buscar', array('class' => 'btn btn-primary')) }}
        {{ Form::close()}}

        <div class="pagination">
            {{ $datos->appends(array("search" => Input::get("search")))->links() }}
        </div>

    </div>
</div>

@stop
