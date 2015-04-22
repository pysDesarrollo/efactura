<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th colspan="10">
            Reporte de facturas del {{$desde}} al {{$hasta}}
        </th>
    </tr>
    <tr>
        <th>#</th>
        <th>Fecha emisión</th>
        <th>Fecha autorización</th>
        <th>Estado</th>
        <th>Ruc</th>
        <th>Número</th>
        <th>Valor</th>
        <th>IVA</th>
        <th>Total</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php $total=0; ?>
    <?php $i=1; ?>
    @foreach($facturas as $factura)
    <?php $total+=$factura["importeTotal"]; ?>
    <tr>
        <td style="width: 30px;"><?php echo $i?></td>
        <?php $i++; ?>
        <td style="text-align: center">{{ date('Y-m-d', strtotime($factura["fechaEmision"]))}}</td>
        <td style="text-align: center">{{ $factura["fechaAutorizacion"]?date('Y-m-d', strtotime($factura["fechaAutorizacion"])):''}}</td>
        <td>{{ $factura["estado"] }}</td>
        <td>{{ $factura["identificacionComprador"] }}</td>
        <td>{{ $factura["estab"] . $factura["ptoEmi"] . $factura["secuencial"]}}</td>
        <td style="text-align: right">{{ number_format ( $factura["totalSinImpuestos"] ,2 ) }}</td>
        <td style="text-align: right">{{ number_format ( $factura["valor"] ,2 )   }}</td>
        <td style="text-align: right">{{ number_format ( $factura["importeTotal"] ,2 )   }}</td>
        <td style="width: 40px">
            <a href="{{ URL::to('getFC/' .  $factura['id']) }}" class="btn btn-primary btn-sm" title="ver">
                <i class="fa fa-search"></i>
            </a>
        </td>
    </tr>
    @endforeach
    <tr>
        <td colspan="8" style="text-align: right;font-weight: bold">TOTAL</td>
        <td style="text-align: right;font-weight: bold">{{ number_format ( $total ,2 ) }}</td>
        <td></td>
    </tr>
    </tbody>
</table>