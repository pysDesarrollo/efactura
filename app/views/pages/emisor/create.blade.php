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

dialog = $( "#dialog-form" ).dialog({
  autoOpen: false,
  height: 300,
  width: 350,
  modal: true,
  buttons: {
    "Create an account": addUser,
    Cancel: function() {
      dialog.dialog( "close" );
  }
},
close: function() {
    form[ 0 ].reset();
    allFields.removeClass( "ui-state-error" );
}
});

function addUser() {
    $.ajax({
        type: 'POST',
        url: '{{URL::to("createCliente")}}',
        data: {nombre: $("#nombreNuevoCliente").val(), direccion: $("#direccionNuevoCliente").val(), telefono: $("#telefonoNuevoCliente").val(), ruc: $("#cedulaNuevoCliente").val() },
        dataType: 'json'
    }).done(function(data){
        if (data.result === "ok") {
            dialog.dialog( "close" );
        }

    });

}

form = dialog.find( "form" ).on( "submit", function( event ) {
  event.preventDefault();
  addUser();
});

});

$(function() {




});
</script>
{{ HTML::ul($errors->all()) }}
<div class="section">

<div class="container">
       {{ Form::open(array('url' => 'emisor', 'class' => 'form-horizontal')) }}

       <div class="form-group">
        <label class="col-sm-2 control-label">
            RUC: 
        </label>
        <div class="col-sm-3">
            <input id="ruc" class="form-control" type="text" name="ruc" value="" placeholder="RUC" required>
        </div>
        <label class="col-sm-2 control-label">
            Apellidos y Nombres - Razon Solcial:
        </label>
        <div class="col-sm-5">
            <input class="form-control" type="text" name="nombres_apellidos_razon" value="" placeholder="Apellidos y Nombres - Razón Social" >
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">
            Nombre Comercial:
        </label>
        <div class="col-sm-6">
            <input class="form-control" type="text" name="nombre_comercial" value="" placeholder="Nombre Comercial">
        </div>

        <label class="col-sm-2">
            Empresa por defecto
        </label>
        <div class="col-sm-2">

            <input type="checkbox" name="empresa_defecto" value="S">

        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">
            Dirección Matriz: 
        </label>
        <div class="col-sm-10">
            <textarea class="form-control" rows="1" name="direccion_matriz" id="direccion_matriz" placeholder="Dirección Matriz" required></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">
            Obligado a llevar contabilidad
        </label>
        <div class="col-sm-1">

            <input type="checkbox" name="obligado_cotablilidad" value="S" checked>

        </div>

        <label class="col-sm-2 control-label">
            Contribuyente Especial Nro de resolución: 
        </label>
        <div class="col-sm-2">
            <input type="text" class="form-control" name="nro_resolucion" value="" placeholder="Nro. de Resolución">
        </div>

        <label class="col-sm-2 control-label">
            Tipo Emisión:
        </label>
        <div class="col-sm-3">
            <select class="form-control" name="tipo_emision" id="tipo_emision" onchange="location.href = '#">                        
            @foreach($catalogo as $key => $value)
                <option value='{{$value->cat_codigo}}'>{{$value->cat_descripcion}}</option>;
            @endforeach;

            </select>
        </div>

    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">
            Tiempo máximo de espera(segundos):
        </label>
        <div class="col-sm-1">
            <input class="form-control" type="number" name="max_time" id="max_time" min="3" max="9999" value="3" required>
        </div>

        <label class="col-sm-2 control-label">
            Tipo de Ambiente:
        </label>
        <div class="col-sm-2">
            <select class="form-control" name="tipo_ambiente" required>

                <option value="1">PRODUCCION</option>
                <option value="2">PRUEBAS</option>

            </select>
        </div>

    </div>
    <div class="form-group">
        <div class="col-sm-8">
         {{ Form::submit('Crear', array('class' => 'btn btn-primary')) }}


         <input class="btn btn-default" type="reset" name="borrar" value="Borrar">
     </div>
     <div class="col-sm-2 pull-right">
        <a href="{{ URL::to('facturacion/emisor')}}">Ver Listado</a>
    </div>
</div>



{{ Form::close() }}


</div>
</div>

@stop


