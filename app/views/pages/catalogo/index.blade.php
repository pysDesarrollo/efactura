@extends('layouts.default')
@section('content')

<nav class="navbar navbar-inverse">
    <ul class="nav navbar-nav">
        <li>
            <a href="{{ URL::to('catalogo/create') }}">
                <i class="fa fa-list-ol"></i> Nuevo registro
            </a>
        </li>
        <li>
            <a href="{{ URL::to('catalogo/createCategoria') }}">
                <i class="fa fa-indent"></i> Nueva categoria
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
                <div class="titulo-pagina"> <i class="fa fa-list-ul"></i> Catálogo</div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-1">
                <label>Categoría</label>
            </div>
            <div class="col-md-2">
                <select id="categoria" name="categoria" class="form-control input-sm">
                    @foreach($categorias as  $value)
                    <option value="{{$value['cat_codigo']}}">{{$value['cat_descripcion']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row" style="margin-top: 15px">
            <div class="col-md-12" id="detalle">

            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
    $("#categoria").change(function(){
        $.ajax({
            type: 'GET',
            url: '{{URL::to("getCatalogoByPadre")}}/'+ $("#categoria").val(),
            data:""
        }).success(function(msg){
            console.log("msg",msg)
            $("#detalle").html(msg)
        });
    })
    $("#categoria").change()

</script>
@stop
