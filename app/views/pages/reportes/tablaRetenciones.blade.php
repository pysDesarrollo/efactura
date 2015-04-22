<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th colspan="10">
            Reporte de retenciones del {{$desde}} al {{$hasta}}
        </th>
    </tr>
    <tr>
        <th>#</th>
        <th>Fecha emisión</th>
        <th>Estado</th>
        <th>Ruc</th>
        <th>Número</th>
        <th># Factura</th>
        <th>Total</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php $total=0; ?>
    <?php $i=1; ?>
    @foreach($datos as $d)
    <?php $total+= $d[6]; ?>
    <tr>
        <td style="width: 30px;"><?php echo $i?></td>
        <?php $i++; ?>
        <td style="text-align: center">{{ $d[0] }}</td>
        <td style="text-align: center">{{ $d[1] }}</td>
        <td>{{ $d[2]  }}</td>
        <td>{{$d[3]  }}</td>
        <td>{{$d[7]  }}</td>
        <td style="text-align: right">{{ $d[4] }}</td>
        <td style="width: 40px">
            <a href="{{ URL::to('getRT/'. $d[5])}}" class="btn btn-primary btn-sm" title="ver">
                <i class="fa fa-search"></i>
            </a>
        </td>
    </tr>
    @endforeach
    <tr>
        <td colspan="6" style="text-align: right;font-weight: bold">TOTAL</td>
        <td style="text-align: right;font-weight: bold">{{ number_format ( $total ,2 ) }}</td>
        <td></td>
    </tr>
    </tbody>
</table>