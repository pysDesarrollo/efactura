@extends('layouts.default')
@section('content')

<nav class="navbar navbar-inverse">
    <ul class="nav navbar-nav">
        <li>
            <a href="{{ URL::to('factura-electronica/create') }}" class="a-header">
                <i class="fa fa-file-text-o"></i> Nueva 
            </a>
        </li>
    </ul>
</nav>

<div class="section">
    <div class="container">
        @if(Session::has('message'))
        <div class="row">
            <div class="col-md-12">
                <div class="mensajes">
                    {{ Session::get('message') }}
                </div>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="titulo-pagina"> <i class="fa fa-list-ul"></i> Listado de Facturas</div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>No. Factura</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Clave Acceso</th>
                        <th>Valor Total</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($datos as $key => $value)
                    <tr>
                        <td>{{ $value->estab . $value->ptoEmi . $value->secuencial  }}</td>
                        <td>{{ date('Y-m-d', strtotime($value->fechaEmision)) }}</td>
                        <td>{{ $value->identificacionComprador }}</td>
                        <td>{{ $value->claveAcceso }}</td>
                        <td align="right">{{ number_format($value->importeTotal, 2) }}</td>
                        <td>
                            <a class="pull-left btn btn-small btn-success" href="{{ URL::to('getFC/' . $value->id) }}">Visualizar</a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>

                {{ Form::open(array('url' => 'factura-electronica', 'class' => 'form-inline', 'role' => 'form', 'method' => 'get')) }}
                    {{ Form::text('search', Input::get("search"), array('class' => 'form-control')) }}
                    {{ Form::submit('Buscar', array('class' => 'btn btn-primary')) }}
                {{ Form::close()}}

                <div class="pagination">
                    {{ $datos->appends(array("search" => Input::get("search")))->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@stop

