<table class="table table-striped table-bordered">
    <thead>
    <tr>
<!--        <th>ID</th>-->
        <th>Categoria</th>
        <th>Código</th>
        <th>Descripcion</th>
        <th>Referencia</th>
        <th>Valor</th>
        <th>Acciones</th>
    </tr>
    </thead>
    <tbody>
    @foreach($datos as $key => $value)
    <tr>
<!--        <td>{{ $value->id }}</td>-->
        <td>{{ $value->cat_codigo_padre }}</td>
        <td>{{ $value->cat_codigo }}</td>
        <td>{{ $value->cat_descripcion }}</td>
        <td>{{ $value->cat_referencia }}</td>
        <td>{{ $value->cat_valor }}</td>
        <td style="width: 90px">
            <a class="pull-left btn btn-sm btn-success" href="{{ URL::to('catalogo/' . $value->id . '/edit') }}" title="Editar">
                <i class="fa fa-pencil"></i>
            </a>
            {{ Form::open(array('url' => 'catalogo/' . $value->id, 'class' => 'pull-right', 'style' => 'display: inline-table')) }}
            {{ Form::hidden('_method', 'DELETE') }}
            <a href="#"  class="btn bg-danger btn-sm delete" title="Eliminar"><i class="fa fa-trash"></i></a>
            <!--                            {{ Form::submit('Eliminar', array('class' => 'btn btn-small btn-warning')) }}-->
            {{ Form::close() }}

        </td>
    </tr>
    @endforeach
    </tbody>
</table>
<script type="text/javascript">
    $(".delete").click(function(){
        var boton  = $(this)
        bootbox.confirm("Está seguro? Está acción no puede revertirse" ,function(result) {
            if(result){
                boton.parent().submit()
            }
        });
//            bootbox.alert("Hello world!");
        return false
    });
</script>