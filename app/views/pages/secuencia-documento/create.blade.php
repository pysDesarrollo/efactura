@extends('layouts.default')

@section('content')


{{ HTML::ul($errors->all()) }}

<nav class="navbar navbar-inverse">
    <ul class="nav navbar-nav">
        <li>
            <a href="{{  URL::to('secuencia-documento') }}" class="a-header">
                <i class="fa fa-list-ul"></i> Ver Listado
            </a>
        </li>

    </ul>
</nav>
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="titulo-pagina"> <i class="fa fa-file-text-o"></i> Registrar un nuevo secuencial</div>
            </div>
        </div>
        {{ Form::open(array('url' => 'secuencia-documento', 'class' => 'form-horizontal frmSecuencia',  'method' => 'post')) }}
        <input type="hidden" name="id" value="{{$objeto->id}}">
        <div class="form-group">
            <label class="col-md-2 label-control">
                Tipo de documento
            </label>
            <div class="col-md-2">
                <select class="form-control input-sm tipo" name="sec_tipo_documento" >
                    <option value="FA">Factura</option>
                    <option value="NC">Nota de crédito</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 label-control">
                Establecimiento
            </label>
            <div class="col-md-2">
                <input type="text" class="form-control required number" name="sec_estab" maxlength="3" value="{{$objeto->sec_estab}}">
            </div>
            <label class="col-md-1 label-control">
                Emisión
            </label>
            <div class="col-md-2">
                <input type="text" class="form-control required number" name="sec_ptoemi" maxlength="3" value="{{$objeto->sec_ptoemi}}">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 label-control">
                Inicial
            </label>
            <div class="col-md-2">
                <input type="text" class="form-control required number" name="sec_inicial" value="{{$objeto->sec_inicial}}">
            </div>
            <label class="col-md-1 label-control">
                Final
            </label>
            <div class="col-md-2">
                <input type="text" class="form-control required number" name="sec_final" value="{{$objeto->sec_final}}">
            </div>
        </div>


        <div class="form-group">
            <div class="col-sm-8">
                <!--                {{ Form::submit('Guardar', array('class' => 'btn btn-primary')) }}-->
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Guardar</button>
                @if($objeto->id=="")
                <input class="btn btn-default btn-sm bg-danger" type="reset" name="borrar" value="Borrar">
                @endif
            </div>
            <!--            <div class="col-sm-2 pull-right">-->
            <!--                <a href="{{URL::to('secuencia-documento')}}">Ver Listado</a>-->
            <!--            </div>-->
        </div>

        {{ Form::close() }}


    </div>
</div>

<script type="text/javascript">
    var validator = $(".frmSecuencia").validate({
        errorClass     : "help-block",
        errorPlacement : function (error, element) {
            if (element.parent().hasClass("input-group")) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
            element.parents(".grupo").addClass('has-error');
        },
        success        : function (label) {
            label.parents(".grupo").removeClass('has-error');
            label.remove();
        }

    });
    $(function () {
        $(".tipo").val("{{$objeto->sec_tipo_documento}}");

    });

</script>
<script src="{{ URL::to('/') }}/js/custom/ui.js"></script>
@stop