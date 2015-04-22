<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th colspan="11">
            Reporte de notas de crédito del {{$desde}} al {{$hasta}}
        </th>
    </tr>
    <tr>
        <th>#</th>
        <th>Fecha emisión</th>
        <th>Fecha autorización</th>
        <th>Estado</th>
        <th>Ruc</th>
        <th>Número</th>
        <th># Factura</th>
        <th>Valor</th>
        <th>IVA</th>
        <th>Total</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php $total=0; ?>
    <?php $i=1; ?>
    @foreach($notas as $nota)
    <?php $total+=$nota["valorModificacion"]; ?>
    <tr>
        <td style="width: 30px;"><?php echo $i?></td>
        <?php $i++; ?>
        <td style="text-align: center">{{ date('Y-m-d', strtotime($nota["fechaEmision"]))}}</td>
        <td style="text-align: center">{{ $nota["fechaAutorizacion"]?date('Y-m-d', strtotime($nota["fechaAutorizacion"])):''}}</td>
        <td>{{ $nota["estado"] }}</td>
        <td>{{ $nota["identificacionComprador"] }}</td>
        <td>{{ $nota["estab"] . $nota["ptoEmi"] . $nota["secuencial"]}}</td>
        <td>{{ $nota["numDocModificado"]}}</td>
        <td style="text-align: right">{{ number_format ( $nota["totalSinImpuestos"] ,2 ) }}</td>
        <td style="text-align: right">{{ number_format ( $nota["valor"] ,2 )   }}</td>
        <td style="text-align: right">{{ number_format ( $nota["valorModificacion"] ,2 )   }}</td>
        <td style="width: 40px">
            <a href="{{ URL::to('getNC/' .  $nota['id']) }}" class="btn btn-primary btn-sm" title="ver">
                <i class="fa fa-search"></i>
            </a>
        </td>
    </tr>
    @endforeach
    <tr>
        <td colspan="9" style="text-align: right;font-weight: bold">TOTAL</td>
        <td style="text-align: right;font-weight: bold">{{ number_format ( $total ,2 ) }}</td>
        <td></td>
    </tr>
    </tbody>
</table>