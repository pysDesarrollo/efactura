 @extends('layouts.default')
 @section('content')

 <nav class="navbar navbar-inverse">
    <ul class="nav navbar-nav">
    <li><a href="{{ URL::to('nota-credito') }}">Notas de Crédito</a></li>
        <li><a href="{{ URL::to('nota-credito/create') }}">Nueva</a></li>
    </ul>
</nav>

<br/>
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="titulo-pagina"> <i class="fa fa-list-ul"></i> Listado de Notas de Crédito</div>
            </div>
        </div>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Clave Acceso</th>
                    <th>Doc. Modificado</th>
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
                    <td>{{ $value->numDocModificado }}</td>

                    <!-- we will also add show, edit, and delete buttons -->
                    <td>

                        <!-- edit this nerd  -->
                        <a class="pull-left btn btn-small btn-success" href="{{ URL::to('getNC/' . $value->id) }}">Visualizar</a>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <?php echo $datos->links(); ?>
    </div>
</div>

@stop
