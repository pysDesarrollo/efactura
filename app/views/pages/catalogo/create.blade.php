@extends('layouts.default')

@section('content')

{{ HTML::ul($errors->all()) }}
<nav class="navbar navbar-inverse">
    <ul class="nav navbar-nav">
        <li>
            <a href="{{  URL::to('catalogo') }}" class="a-header">
                <i class="fa fa-list-ul"></i> Ver Listado
            </a>
        </li>

    </ul>
</nav>
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if($objeto->id)
                <div class="titulo-pagina"> <i class="fa fa-list-ol"></i> Editar registro</div>
                @else
                <div class="titulo-pagina"> <i class="fa fa-list-ol"></i> Nuevo registro</div>
                @endif
            </div>
        </div>
        {{ Form::open(array('url' => 'catalogo', 'class' => 'form-horizontal frmCatalogo',  'method' => 'post')) }}
        <input type="hidden" name="id" value="{{$objeto->id}}">
        <div class="form-group">
            <label class="col-sm-2 control-label">
                Categoría
            </label>
            <div class="col-sm-2">
                <select id="categoria" name="codigo_padre" class="form-control input-sm required">
                    @foreach($categorias as  $value)
                    <option value="{{$value['cat_codigo']}}">{{$value['cat_descripcion']}}</option>
                    @endforeach
                </select>

            </div>
            <label class="col-sm-2 control-label">
                Codigo:
            </label>
            <div class="col-sm-2">
                <input id="codigo" class="form-control input-sm  required" type="text" name="cat_codigo" value="{{$objeto->cat_codigo}}" placeholder="Código" maxlength="4">
            </div>

        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">
                Referencia:
            </label>
            <div class="col-sm-2">
                <input id="referencia" class="form-control input-sm" type="text" name="cat_referencia" value="{{$objeto->cat_referencia}}" placeholder="Código de referencia" maxlength="3">
            </div>
            <label class="col-sm-2 control-label">
                Valor
            </label>
            <div class="col-sm-2">
                <input id="valor" class="form-control input-sm number " type="text" name="cat_valor" value="{{$objeto->cat_valor}}"  placeholder="Valor">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">
                Descripción:
            </label>
            <div class="col-sm-6">
                <input class="form-control input-sm required" type="text" name="descripcion" id="descripcion" value="{{$objeto->cat_descripcion}}" placeholder="Descripción" maxlength="80">
            </div>
        </div>
        <div class="form-group" style="margin-top: 25px">
            <div class="col-sm-8">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Guardar</button>
                @if($objeto->id=="")
                <input class="btn btn-default btn-sm bg-danger" type="reset" name="borrar" value="Borrar">
                @endif
            </div>
        </div>

        {{ Form::close() }}


    </div>
</div>
<script type="text/javascript">
    var validator = $(".frmCatalogo").validate({
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
    $("#categoria").val("{{$objeto->cat_codigo_padre}}")
    $("#categoria").change(function(){
        $("#codigo").val($("#categoria").val())
    });
    $("#categoria").change()
</script>
<script src="{{ URL::to('/') }}/js/custom/ui.js"></script>
@stop


