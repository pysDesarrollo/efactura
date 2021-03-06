@extends('layouts.default')

@section('content')
<script>
    var precio;
    var total;
    var nombre;
    var elementos = 0;
    var idProducto;

    var dialog, form,
    // From http://www.whatwg.org/specs/web-apps/current-work/multipage/states-of-the-type-attribute.html#e-mail-state-%28type=email%29
        emailRegex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/,
        cedulaNyevoCliente = $( "#cedulaNuevoCliente" ),
        nombreNuevoCliente = $( "#nombreNuevoCliente" ),
        direccionNuevoCliente = $( "#direccionNuevoCliente" ),
        telefonoNuevoCliente = $( "#telefonoNuevoCliente" ),
        allFields = $( [] ).add( cedulaNyevoCliente ).add( nombreNuevoCliente ).add( direccionNuevoCliente ).add( telefonoNuevoCliente ),
        tips = $( ".validateTips" );


    $(document).ready(function(){
        $("#idproducto").change(function(){
            //alert($(this).val());
            $.ajax({
                type: 'GET',
                url: '{{URL::to("getProductById")}}',
                data: {producto: $(this).val()},
                dataType: 'json'
            }).done(function(data){
                $('#detpreciou').val(data.pro_precio_pco);
                precio = data.pro_precio_pco;
                nombre = data.pro_nombre_producto;
                idProducto = data.id;
                //alert(data.pro_precio_pco);
            });
        });

        $("#detcantidad").keyup(function(){
            total = $(this).val() * precio;
            $("#dettotal").val(total);
        });

        $("#asignar").click(function(){
            elementos += 1;
            html = "<div class='row'><div class='col-xs-6 col-md-3' style='float:left;'>" + nombre + "</div><div class='col-xs-6 col-md-3' style='float:left;'>" + $("#detcantidad").val() + "</div><div class='col-xs-6 col-md-3' style='float:left;'>" + precio + "</div><div class='col-xs-6 col-md-3' style='float:left;'>" + total + "</div>";
            html += "<input type='hidden' name='id[" + elementos + "]' value='" + idProducto +"'>";
            html += "<input type='hidden' name='cantidad[" + elementos + "]' value='" + $("#detcantidad").val() +"'>";
            html += "<input type='hidden' name='precio[" + elementos + "]' value='" + precio +"'>";
            html += "<input type='hidden' name='totalFila[" + elementos + "]' value='" + total +"'></div>";
            $("#add").append(html);
        });

        $("#searchCliente").click(function(){
            $('#resultadoSearch').css({'display':'none'});
            $.ajax({
                type: 'GET',
                url: '{{URL::to("getClienteByCedula")}}',
                data: {cedula: $("#idcliente").val()},
                dataType: 'json'
            }).done(function(data){
                if (data.id !== 0000001) {

                    $('#resultadoSearch').css({'display':'block'});

                    $('#codigo_cliente').text(data.codigo_cliente);
                    $('#nombre_cliente').text(data.nombre_cliente);
                    $('#direccion_cliente').text(data.direccion_cliente);
                    $('#telefono_cliente').text(data.telefono_cliente);
                } else {
                    $('#cedulaNuevoCliente').val($("#idcliente").val());
                    dialog.dialog("open");
                }
            });
        });

//        dialog = $( "#dialog-form" ).dialog({
//            autoOpen: false,
//            height: 300,
//            width: 350,
//            modal: true,
//            buttons: {
//                "Create an account": addUser,
//                Cancel: function() {
//                    dialog.dialog( "close" );
//                }
//            },
//            close: function() {
//                form[ 0 ].reset();
//                allFields.removeClass( "ui-state-error" );
//            }
//        });
//
//        function addUser() {
//            $.ajax({
//                type: 'POST',
//                url: '{{URL::to("createCliente")}}',
//                data: {nombre: $("#nombreNuevoCliente").val(), direccion: $("#direccionNuevoCliente").val(), telefono: $("#telefonoNuevoCliente").val(), ruc: $("#cedulaNuevoCliente").val() },
//                dataType: 'json'
//            }).done(function(data){
//                if (data.result === "ok") {
//                    dialog.dialog( "close" );
//                }
//
//            });
//
//        }
//
//        form = dialog.find( "form" ).on( "submit", function( event ) {
//            event.preventDefault();
//            addUser();
//        });

    });

    $(function() {




    });
</script>
{{ HTML::ul($errors->all()) }}
<nav class="navbar navbar-inverse">
    <ul class="nav navbar-nav">
        <li>
            <a href="{{  URL::to('emisor') }}" class="a-header">
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
                <div class="titulo-pagina"> <i class="fa fa-bank"></i> Editar emisor</div>
                @else
                <div class="titulo-pagina"> <i class="fa fa-bank"></i> Registrar un nuevo emisor</div>
                @endif
            </div>
        </div>

        {{ Form::open(array('url' => 'emisor', 'class' => 'form-horizontal frmEmisor')) }}
        <input type="hidden" name="id" value="{{$objeto->id}}">
        <div class="form-group">
            <label class="col-sm-2 control-label">
                RUC
            </label>
            <div class="col-sm-3">
                <input id="ruc" class="form-control input-sm required number" type="text" name="ruc"  placeholder="RUC" required value="{{$objeto->emi_ruc}}">
            </div>
            <label class="col-sm-2 control-label">
                Razon Solcial
            </label>
            <div class="col-sm-5">
                <input class="form-control input-sm required" type="text" name="nombres_apellidos_razon"  placeholder="Apellidos y Nombres" value="{{$objeto->emi_nombre}}" >
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">
                Nombre Comercial
            </label>
            <div class="col-sm-10">
                <input class="form-control input-sm required" type="text" name="nombre_comercial"  placeholder="Nombre Comercial" value="{{$objeto->emi_nombre_comercial}}">
            </div>


        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">
                Dirección Matriz
            </label>
            <div class="col-sm-10">
                <textarea class="form-control input-sm required" rows="1" name="direccion_matriz" id="direccion_matriz" placeholder="Dirección Matriz" required>{{$objeto->emi_direccion_matriz}}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" style="margin-top: 0px;padding-top: 0px">
                Contribuyente Especial Nro de resolución
            </label>
            <div class="col-sm-2">
                <input type="text" class="form-control input-sm" name="nro_resolucion"  placeholder="Nro. de Resolución" value="{{$objeto->emi_nro_resolucion}}">
            </div>
            <label class="col-sm-2 control-label" style="margin-top: 0px;padding-top: 0px">
                Obligado a llevar contabilidad
            </label>
            <div class="col-sm-1">
                <input type="checkbox" id="contabilidad" name="obligado_cotablilidad"  class="form-control" data-off-color="danger" value="S" >
            </div>
            <label class="col-sm-2 control-label">
                Empresa por defecto
            </label>
            <div class="col-sm-2">
                <input id="defecto" name="empresa_defecto"  type="checkbox" data-off-color="danger" class="form-control" value="S" >
<!--                <input type="checkbox" name="empresa_defecto" value="S" {{($objeto->id)?($objeto->emi_defecto=='S')?'checked':'':'checked'}}>-->
            </div>

        </div>
        <div class="form-group">


            <label class="col-sm-2 control-label">
                Tipo de Ambiente
            </label>
            <div class="col-sm-2">
                <select class="form-control required input-sm" name="tipo_ambiente" id="tipo_ambiente" required>

                    <option value="1">PRODUCCION</option>
                    <option value="2">PRUEBAS</option>

                </select>
            </div>
            <label class="col-sm-2 control-label">
                Tipo Emisión
            </label>
            <div class="col-sm-3">
                <select class="form-control input-sm required" name="tipo_emision" id="tipo_emision">
                    @foreach($catalogo as $key => $value)
                    <option value='{{$value->cat_codigo}}'>{{$value->cat_descripcion}}</option>
                    @endforeach;

                </select>
            </div>
            <label class="col-sm-2 control-label" style="margin-top: 0px;padding-top: 0px">
                Tiempo máximo de espera(segundos)
            </label>
            <div class="col-sm-1">
                <input class="form-control input-sm required number" type="number" name="max_time" id="max_time" min="3" max="9999"  maxlength="4" required value="{{($objeto->id!='')?$objeto->emi_tiempo_max_espera:3}}">
            </div>
        </div>
        <div class="form-group" style="margin-top: 25px">
            <div class="col-sm-8">
                <!--         {{ Form::submit('Crear', array('class' => 'btn btn-primary')) }}-->

                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Guardar</button>
                @if($objeto->id=="")
                <input class="btn btn-default btn-sm bg-danger" type="reset" name="borrar" value="Borrar">
                @endif
            </div>
<!--            <div class="col-sm-2 pull-right">-->
<!--                <a href="{{ URL::to('facturacion/emisor')}}">Ver Listado</a>-->
<!--            </div>-->
        </div>



        {{ Form::close() }}


    </div>
</div>
<script type="text/javascript">
    var validator = $(".frmEmisor").validate({
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
        $("#tipo_emision").val("{{$objeto->emi_tipo_emision}}");
        $("#tipo_ambiente").val("{{$objeto->emi_tipo_ambiente}}");



    });
    $("#defecto").bootstrapSwitch({
        onText:"Si",
        offText:"No",
        handleWidth:40,
        state :{{($objeto->id)?($objeto->emi_defecto=='S')?'true':'false':'true'}}
    });
    $("#contabilidad").bootstrapSwitch({
        onText:"Si",
        offText:"No",
        handleWidth:40,
        state :{{($objeto->id)?($objeto->emi_obligado_llevar_contabilidad=='S')?'true':'false':'true'}}
    });

</script>
<script src="{{ URL::to('/') }}/js/custom/ui.js"></script>
@stop


