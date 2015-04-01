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
       {{ Form::open(array('url' => 'catalogo', 'class' => 'form-horizontal',  'method' => 'post')) }}

       <div class="form-group">
        <label class="col-sm-2 control-label">
            Codigo: 
        </label>
        <div class="col-sm-2">
            <input id="codigo" class="form-control" type="text" name="codigo" value="" placeholder="Código">
        </div>
        <label class="col-sm-2 control-label">
            Codigo Padre:
        </label>
        <div class="col-sm-2">
            <input class="form-control" type="text" id="codigo_padre" name="codigo_padre" value="" placeholder="Codigo padre" >
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">
            Descripción:
        </label>
        <div class="col-sm-6">
            <input class="form-control" type="text" name="descripcion" id="descripcion" value="" placeholder="Descripción">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-8">
         {{ Form::submit('Crear', array('class' => 'btn btn-primary')) }}


         <input class="btn btn-default" type="reset" name="borrar" value="Borrar">
     </div>
     <div class="col-sm-2 pull-right">
        <a href="{{ URL::to('catalogo')}}">Ver Listado</a>
    </div>
</div>

{{ Form::close() }}


</div>
</div>

@stop


