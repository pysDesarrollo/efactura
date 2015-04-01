@extends('layouts.default')
@section('content')

<script>
	var precio;
	var total;
	var nombre;
	var elementos = 0;
	var idProducto;
	var subtotal0 = 0;
	var subtotal12 = 0;
	var iva = 0;
	var total =0;
	var tieneIva;

	$(document).ready(function(){
			$("#idproducto").change(function(){
				//alert($(this).val());
				$("#detcantidad").val("");

				$.ajax({
					type: 'GET',
					url: '{{URL::to("getProductById")}}',
					data: {producto: $(this).val()},
					dataType: 'json'
				}).done(function(data){
					$('#detpreciou').val(data.pro_valor_unitario);
					precio = data.pro_valor_unitario;
					nombre = data.pro_nombre;
					idProducto = data.id;
					tieneIva = data.pro_incluyeiva;

				    total = $("#detcantidad").val() * $("#detpreciou").val();
				    $("#dettotal").val(total);
				});
			});

			$("#detcantidad").keyup(function(){
				total = $(this).val() * $("#detpreciou").val();
				$("#dettotal").val(total);
			});

			$("#asignar").click(function(){
				if (total <= 0) {
					alert("Valor incorrecto para total del producto");
					return;
				}
				elementos += 1;
				html = "<div class='row'><div class='col-xs-6 col-md-3' style='float:left;'>" + nombre + "</div><div class='col-xs-6 col-md-3' style='float:left;'>" + $("#detcantidad").val() + "</div><div class='col-xs-6 col-md-3' style='float:left;'>" + precio + "</div><div class='col-xs-6 col-md-3' style='float:left;'>" + total + "</div>";
				html += "<input type='hidden' name='id[" + elementos + "]' value='" + idProducto +"'>";
				html += "<input type='hidden' name='cantidad[" + elementos + "]' value='" + $("#detcantidad").val() +"'>";
				html += "<input type='hidden' name='precio[" + elementos + "]' value='" + precio +"'>";
				html += "<input type='hidden' name='totalFila[" + elementos + "]' value='" + total +"'></div>"; 
				$("#add").append(html);
				//Calcula totales de factura
				if (tieneIva == 'S') {
					subtotal12 += total;
					iva = subtotal12 * 0.12;
				}
				else {
					subtotal0 += total;	
				}				
				
				total = subtotal0 + subtotal12 + iva;

				$("#doc_subtotal0").val(subtotal0);
				$("#doc_subtotal12").val(subtotal12);
				$("#doc_iva").val(iva);
				$("#doc_total").val(total);
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
	});	

</script>

<div class="section">

	<?php
		$sec_final = sizeof($secuencia) > 0 ? $secuencia[0]->sec_final : ''; 
		$estab = sizeof($secuencia) > 0 ? $secuencia[0]->sec_estab : '';  
		$ptoemi = sizeof($secuencia) > 0 ? $secuencia[0]->sec_ptoemi : '';  

		
	?>

	<div class="container">
		{{ Form::open(array('url' => 'factura-electronica', 'class' => 'form-horizontal',  'method' => 'post')) }}

		 @if($errors->has())
		 	  @foreach ($errors->all() as $error)
		 	  	<div class="alert alert-danger"> {{ $error }} </div>
		      @endforeach
		 @endif

		<div class="form-group">
			<label class="col-sm-2 label-control">
				No. Factura: &nbsp;{{$estab . "-" . $ptoemi . "-"}}
			</label>
			<div class="col-sm-4">
				<input class="form-control" type="text" id="doc_num" name="doc_num" value="<?php echo $sec_final; ?>" readonly required >
				<input class="form-control" type="hidden" id="doc_estab" name="doc_estab" value="<?php echo $estab; ?>" placeholder="No. Factura">
				<input class="form-control" type="hidden" id="doc_ptoemi" name="doc_ptoemi" value="<?php echo $ptoemi; ?>" placeholder="No. Factura">
			</div>

			<label class="col-sm-2 label-control">
				Fecha: 
			</label>
			<div class="col-sm-4">
				<input class="form-control" type="text" id="doc_fecha" name="doc_fecha" value="<?php echo date('Y-m-d'); ?>" placeholder="Fecha Factura">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 label-control">
				CI/RUC: 
			</label>
			<div class="col-sm-4">
				<input class="form-control" type="text"  id="cli_cedularuc" name="cli_cedularuc" value="" placeholder="CI/RUC Cliente" required>
			</div>
			<div class="col-sm-4">
				<input type="button" id="searchCliente" value="Buscar">
			</div>
		</div>	

		<div id='resultadoSearch' style='display:none'>
			<div class="form-group">
				<label class="col-sm-2 label-control">Código:</label>
				<div class="col-sm-4">
					<label id='codigo_cliente'></label>
					<input class="form-control" type="hidden"  id="idcliente" name="idcliente" value="">	
				</div>
				<label class="col-sm-2 label-control">Nombre:</label>
				<div class="col-sm-4">
					<label id='nombre_cliente'></label>
				</div>
			</div>			
			<div class="form-group">
				<label class="col-sm-2 label-control">Dirección:</label>
				<div class="col-sm-4">
					<label id='direccion_cliente'></label> 
				</div>
				<label class="col-sm-2 label-control">Teléfono:</label>
				<div class="col-sm-4">
					<label id='telefono_cliente'></label>
				</div>
			</div>		
			<div class="form-group">
				<label class="col-sm-2 label-control">Correo Electrónico:</label>
				<div class="col-sm-2">
					<label id='mail_cliente'></label> 
				</div>
			</div>		
		</div>

		<div class="form-group">
			<label class="col-sm-2 label-control">
			</label>
			<div class="col-sm-4">
			</div>
			<label class="col-sm-2 label-control">
				Subtotal 0%: 
			</label>
			<div class="col-sm-4">
				<input class="form-control" type="text" id="doc_subtotal0" name="doc_subtotal0" value="" placeholder="0.00" readonly required>
			</div>
		</div>
			
		<div class="form-group">
			<label class="col-sm-2 label-control">
			</label>
			<div class="col-sm-4">
			</div>
			<label class="col-sm-2 label-control">
				Subtotal 12%: 
			</label>
			<div class="col-sm-4">
				<input class="form-control" type="text" id="doc_subtotal12" name="doc_subtotal12" value="" placeholder="0.00" readonly required>
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 label-control">
			</label>
			<div class="col-sm-4">
			</div>
			<label class="col-sm-2 label-control">
				I.V.A.: 
			</label>
			<div class="col-sm-4">
				<input class="form-control" type="text" id="doc_iva" name="doc_iva" value="" placeholder="0.00" readonly required>
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 label-control">
			</label>
			<div class="col-sm-4">
			</div>
			<label class="col-sm-2 label-control">
				Total: 
			</label>
			<div class="col-sm-4">
				<input class="form-control" type="text" id="doc_total" name="doc_total" value="" placeholder="0.00" readonly required>
			</div>
		</div>

		<div>
			<div class="row">
					<div class="col-xs-6 col-md-3" style="float:left;">
						<label>Producto</label>
						<select class="form-control" name="idproducto" id="idproducto">
							@foreach($producto as $key => $value)
								<option value="{{$value->id}}">{{$value->pro_nombre}}</option>
							@endforeach
						</select>

					</div>
					<div class="col-xs-6 col-md-2" style="float:left;">
						<label>Cantidad</label>
						<input type="text" name="detcantidad" id="detcantidad" class="form-control">
					</div>
					<div class="col-xs-6 col-md-2" style="float:left;">
						<label>Precio Unitario</label>
						<input type="text" name="detpreciou" id="detpreciou" placeholder="0.00" disabled class="form-control">
					</div>
					<div class="col-xs-6 col-md-2" style="float:left;">
						<label>Total</label>
						<input type="text" name="dettotal" id="dettotal" disabled class="form-control">
					</div>
					<div class="col-xs-6 col-md-2" style="float:left;">
						<label>Acción</label>
						<input type="button" name="asignar" id="asignar" value="Add" class="btn btn-success form-control ">
					</div>
				</div>
			<div>
			
			<div>
				<br>
				<br>
			</div>

			<div>
				<div class="row">
					<div class="col-xs-6 col-md-3" style="float:left;">
						Producto
					</div>
					<div class="col-xs-6 col-md-3" style="float:left;">
						Cantidad
					</div>
					<div class="col-xs-6 col-md-3" style="float:left;">
						Precio unitario
					</div>
					<div class="col-xs-6 col-md-3" style="float:left;">
						Total
					</div>
				</div>
				<div id="add">
				</div>
			</div>
		</div>

			<div>
				<br>
			</div>

		<div class="form-group">
			<div class="col-sm-8">
				{{ Form::submit('Imprimir', array('class' => 'btn btn-primary')) }}
			</div>
			<div class="col-sm-2 pull-right">
				<a href="{{URL::to('factura-electronica/create')}}">Nueva</a>
			</div>
			<div class="col-sm-2 pull-right">
				<a href="{{URL::to('factura-electronica')}}">Ver Listado</a>
			</div>

		</div>	
		
		@if(isset($errors))
		   
		      @foreach($errors as $item)
		         <div class="alert alert-danger"> {{ $item }} </div>
		      @endforeach
		   
		@endif

		{{ Form::close() }}
	</div>
</div>

@stop	