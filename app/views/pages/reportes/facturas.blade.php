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
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="titulo-pagina"> <i class="fa fa-bar-chart-o"></i> Reporte de facturas</div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-1">
                <label>Desde</label>
            </div>
            <div class="col-md-2">
                <input type="text" class="datepicker form-control input-sm required" name="desde" id="desde">
            </div>
            <div class="col-md-1">
                <label>Hasta</label>
            </div>
            <div class="col-md-2">
                <input type="text" class="datepicker form-control input-sm required" name="hasta" id="hasta">
            </div>
            <div class="col-md-1">
                <a href="#" id="ver" class="btn btn-primary btn-sm">
                    <i class="fa fa-search"></i> Ver
                </a>
            </div>
            <div class="col-md-1">
                <a href="#" id="imprimir" class="btn btn-primary btn-sm">
                    <i class="fa fa-file-pdf-o"></i> Imprimir
                </a>
            </div>
        </div>
        <div class="row" style="margin-top: 20px">
            <div class="col-md-12" id="tabla" style="position: relative;min-height: 250px">

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    $(".datepicker").datepicker({
        format: "yyyy-mm-dd",
        todayHighlight:true,
        startDate : "-10y",
        endDate:new Date(),
        language:"es",
        autoclose:true
    });

    $("#ver").click(function(){
        if($("#desde").val()!="" && $("#hasta").val()!=""){
            $("#tabla").html()
            openLoader();
            $.ajax({
                type: 'POST',
                url: '{{URL::to("reportes/tablaFacturas")}}',
                data: {

                    desde:$("#desde").val(),
                    hasta:$("#hasta").val()
                }
            }).success(function(data){
                closeLoader();
                $("#tabla").show("slide").html(data)
            });
        }else{
            bootbox.alert("Por favor, seleccione las fechas Desde y Hasta para generar el reporte")
        }

    })
    $("#imprimir").click(function(){
        if($("#desde").val()!="" && $("#hasta").val()!=""){
            window.open('{{URL::to("reportes/facturasPdf")}}?desde='+$("#desde").val()+"&hasta="+$("#hasta").val())
        }else{
            bootbox.alert("Por favor, seleccione las fechas Desde y Hasta para generar el reporte")
        }

    })
</script>
@stop