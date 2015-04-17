@extends('layouts.default')
@section('header')
<script src="{{ URL::to('/') }}/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="{{ URL::to('/') }}/js/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.es.js"></script>
<link rel="stylesheet" href="{{ URL::to('/') }}/js/plugins/bootstrap-datepicker/css/datepicker.css" />
@stop
@section('content')
<style type="text/css">
    #mensaje{
        padding: 5px;
        color: red;
        font-weight: bold;
        font-size: 12px;
    }
</style>

<nav class="navbar navbar-inverse">
    <ul class="nav navbar-nav">
        <li>
            <a href="{{  URL::to('retenciones') }}" class="a-header">
                <i class="fa fa-list-ul"></i> Ver Listado
            </a>
        </li>

    </ul>
</nav>
<div class="section">
    <div class="container">
        {{ Form::open(array('url' => 'retenciones', 'class' => 'form-horizontal frmRetencion',  'method' => 'post')) }}
        @if($errors->has())
        @foreach ($errors->all() as $error)
        <div class="alert alert-danger"> {{ $error }} </div>
        @endforeach
        @endif
        @if(Session::has('message'))
        <div class="row">
            <div class="col-md-12">
                <div class="mensajes">
                    {{ Session::get('message') }}
                </div>
            </div>
        </div>
        @endif
        <input type="hidden" name="data" id="data">
        <div class="row">
            <div class="col-md-12">
                <div class="titulo-pagina"> <i class="fa fa-file-archive-o"></i> Nueva retención</div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 label-control">
                No. Retención:
            </label>
            <div class="col-sm-2">
                <div class="input-group">
                    <span class="input-group-addon bg-info" id="num">
                        {{$secuencia->sec_estab}} - {{$secuencia->sec_ptoemi}}
                     </span>
                    <input type="text" class="form-control input-sm required" name="secuecial" aria-describedby="num" value="{{$secuencia->sec_final+1}}" readonly>
                </div>
            </div>

            <label class="col-sm-2 label-control">
                Fecha de emisión:
            </label>
            <div class="col-sm-2">
                <input class="form-control input-sm required datepicker" type="text" id="fechaEmision" name="fechaEmision" value="<?php echo date('Y-m-d'); ?>" placeholder="Fecha Factura">
            </div>

        </div>
        <div class="form-group">

            <label class="col-sm-2 label-control">
                No. Documento de sustento:
            </label>
            <div class="col-sm-2">
                <input class="form-control input-sm required" type="text" id="" name="numDocSustento" value="" placeholder="Número de la factura">
            </div>
            <label class="col-sm-2 label-control">
                Fecha emisión doc. de sustento:
            </label>
            <div class="col-sm-2">
                <input class="form-control input-sm required datepicker"  type="text" id="" name="fechaEmisionDocSustento" value="<?php echo date('Y-m-d'); ?>" placeholder="Fecha Factura">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="titulo-pagina"></div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 label-control">
                CI/RUC - Cliente:
            </label>
            <div class="col-sm-3">
                <div class="input-group">
                    <input class="form-control input-sm required" type="text"  id="cli_cedularuc" name="cli_cedularuc" value="" placeholder="CI/RUC Cliente" required>
                     <span class="input-group-btn">
                           <a href="#" type="button" class="btn btn-info btn-sm " id="searchCliente" style="border-bottom-left-radius: 0px !important;border-top-left-radius: 0px !important;"><i class="fa fa-search"></i> Buscar</a>
                     </span>
                </div>
            </div>
        </div>

        <div id='resultadoSearch' style='display:none'>
            <div class="form-group">
                <input class="form-control" type="hidden"  id="idcliente" name="idcliente" value="">
                <label class="col-sm-2 label-control">Nombre:</label>
                <div class="col-sm-3" id='nombre_cliente' style="font-size: 12px">
                </div>
                <label class="col-sm-1 label-control">Dirección:</label>
                <div class="col-sm-5" id='direccion_cliente' style="font-size: 12px">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 label-control">Teléfono:</label>
                <div class="col-sm-3" id='telefono_cliente' style="font-size: 12px" >
                </div>
                <label class="col-sm-1 label-control">Email:</label>
                <div class="col-sm-2" id='mail_cliente' style="font-size: 12px">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="titulo-pagina"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-md-3" style="float:left;">
                <label>Retención</label>
                <select class="form-control input-sm" name="tipo" id="tipo">
                    <option value="-1" data-valor="0">Seleccione</option>
                    @foreach($tipos as $value)
                    <option value="{{$value['id']}}" data-valor="{{$value['cat_valor']}}">{{$value['cat_descripcion']}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-xs-6 col-md-2" style="float:left;">
                <label>Base imponible</label>
                <input type="text" name="base"  id="base" placeholder="0.00"  class="form-control input-sm number" style="text-align: right">
            </div>
            <div class="col-xs-6 col-md-2" style="float:left;">
                <label>Porcentaje</label>
                <input type="text" name="dettotal" id="porcentaje" disabled class="form-control  input-sm"  style="text-align: right">
            </div>

            <div class="col-xs-6 col-md-2" style="float:left;">
                <label>Total</label>
                <input type="text" name="total_ingreso" style="text-align: right" id="total_ingreso" disabled class="form-control  input-sm">
            </div>
            <div class="col-xs-6 col-md-1" style="float:left;">
                <label>Acción</label>
                <a href="#" class="btn btn-sm btn-primary"  id="asignar"><i class="fa fa-plus"></i> Añadir</a>

            </div>
        </div>
        <div class="row" style="margin-top: 25px">
            <div class="col-md-12">
                <table class="table table-condensed table-hover table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Item</th>
                        <th>Base imponible</th>
                        <th>Porcentaje</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody id="detalle">

                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right;font-weight: bold">TOTAL</td>
                        <td style="text-align: right;font-weight: bold" id="total">0.00</td>
                        <td style="width: 50px"></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm" id="guardar"><i class="fa fa-save"></i> Guardar</button>
            </div>
        </div>





        {{ Form::close() }}


    </div>
</div>
<script type="text/javascript">
    var validator = $(".frmRetencion").validate({
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
    function calculaValores(){
        var valor = $("#base").val()
        if(isNaN(valor) || valor=="")
            valor=0
        else
            valor=valor*1
        var porcentaje = $("#porcentaje").val()
        if(isNaN(porcentaje) || porcentaje=="")
            porcentaje=0
        else
            porcentaje=porcentaje*1
        $("#total_ingreso").val((valor*porcentaje/100).toFixed(2))
    }
    function calcularTotal(){
        var total = 0
        $(".total_row").each(function(){
            total+=$(this).html()*1
        })
        $("#total").html(total.toFixed(2))
    }
    function addRow(item,valor,porcentaje){
        var tr =$("<tr class='tr-info'></tr>")
        tr.attr("item",item)
        tr.attr("valor",valor)
        tr.attr("porcentaje",porcentaje)
        tr.addClass(""+item)
        tr.append("<td>"+$("#tipo option:selected").text()+"</td>")
        tr.append("<td style='text-align: right'>"+valor+"</td>")
        tr.append("<td style='text-align: right'>"+porcentaje+"</td>")
        tr.append("<td style='text-align: right;' class='total_row'>"+(valor*porcentaje/100).toFixed(2)+"</td>")
        tr.append("<td style='text-align: center;'><a href='#' class='btn bg-danger borrar btn-sm'><i class='fa fa-trash'></i></a></td>")
        $("#detalle").append(tr)
        $(".borrar").unbind("click")
        $(".borrar").bind("click",borrar)
        calcularTotal();
    }
    function borrar(){
        $(this).parent().parent().remove()
        calcularTotal()
    }
    $("#asignar").click(function(){
        var item = $("#tipo").val()
        var valor = $("#base").val()
        var porcentaje = $("#porcentaje").val()
        var mensaje =""
        if(item=="-1"){
            mensaje+="Por favor, seleccione un tipo de retención<br/>"
        }
        if(isNaN(valor) || valor==""){
            mensaje+="Por favor, ingrese la base imponible<br/>"
        }
        if(mensaje==""){
            if($("."+item).size()<1){
                calculaValores()
                addRow(item*1,valor*1,porcentaje*1);
                $("#base").val("")
            }else{
                mensaje+="Ya añadio ese tipo de retención en la sección de detalles<br/>"
                bootbox.alert(mensaje);
            }

        }else{
            bootbox.alert(mensaje);
        }

        return false

    });

    $("#searchCliente").click(function(){
        $('#resultadoSearch').css({'display':'none'});
        $.ajax({
            type: 'GET',
            url: '{{URL::to("getClienteByCedula")}}',
            data: {cedula: $("#cli_cedularuc").val()},
            dataType: 'json'
        }).done(function(data){
            if (data.id !== 0) {
                $('#resultadoSearch').css({'display':'block'});
                $('#codigo_cliente').text(data.id);
                $('#idcliente').val(data.id);
                $('#nombre_cliente').text(data.cli_nombres_apellidos);
                $('#direccion_cliente').text(data.cli_direccion);
                $('#telefono_cliente').text(data.cli_tel_convencional);
                $('#mail_cliente').text(data.cli_email);
            } else {
                alert("Cliente no encontrado");
                $("#cli_cedularuc").val("");
                //$('#cedulaNuevoCliente').val($("#idcliente").val());
                //dialog.dialog("open");
            }
        });
    });

    $("#tipo").change(function(){
        var porcentaje = $("#tipo option:selected").data("valor");
//        console.log($("#tipo option:selected"),$("#tipo option:selected").data("valor"))
        $("#porcentaje").val(porcentaje)
        calculaValores();
    })
    $("#base").blur(function(){
        calculaValores();
    })

    $("#guardar").click(function(){
        var msg = "Por favor, ingrese al menos un tipo de retención en la sección de detalle"
        if($("#idcliente").val()==""){
            msg = "Por favor, ingrese el cliente sujeto de la retención"
            bootbox.alert(msg);
            return false
        }
        if($(".tr-info").size()<1){
            bootbox.alert(msg);
            return false
        }else{
            var data = ""
            $(".tr-info").each(function(){
                data+=$(this).attr("item")+";"+$(this).attr("valor")+";"+$(this).attr("porcentaje")+"|"
            });
            $("#data").val(data)
            return true
        }


    })
    $(".datepicker").datepicker({
        format: "yyyy-mm-dd",
        todayHighlight:true,
        startDate : "-1y",
        endDate:new Date(),
        language:"es",
        autoclose:true
    });

</script>
<script src="{{ URL::to('/') }}/js/custom/ui.js"></script>
@stop  