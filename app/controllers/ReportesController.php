<?php

class ReportesController extends \BaseController {


    public function facturas(){
//        phpinfo();
        return View::make('pages.reportes.facturas');
    }

    public function tablaFacturas(){
        $desde = date('Y-m-d', strtotime(Input::get("desde")));
        $hasta = date('Y-m-d', strtotime(Input::get("hasta")));
        $facturas = Documento::where("fechaEmision",">",$desde)->where("fechaEmision","<",$hasta)->orderBy('fechaEmision')->get()->toArray() ;
//        dd($facturas);
        return View::make('pages.reportes.tablaFacturas')->with("desde",$desde)->with("hasta",$hasta)->with("facturas",$facturas);
    }

    public function facturasPdf(){
//        phpinfo();
        $desde = date('Y-m-d', strtotime(Input::get("desde")));
        $hasta = date('Y-m-d', strtotime(Input::get("hasta")));
        $emisor = Emisor::find(1);
        $facturas = Documento::where("fechaEmision",">",$desde)->where("fechaEmision","<",$hasta)->orderBy('fechaEmision')->get()->toArray() ;

        $columnas = ["#","Fecha emisión","Fecha autorización","Estado","Ruc","Número","Valor","IVA","Total"];
        $pdf = new \App\library\ReportesPdf();
        $pdf->setTitulo("Reporte de facturas del ".$desde." al ".$hasta);
        $pdf->setEmisor($emisor);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->setColumnas($columnas,[8,20,25,22,25,30,20,15,20]);
        $i=0;
        $total=0;
        foreach($facturas as $factura){
            $i++;
            $datos = [];
            array_push($datos,"".$i);
            array_push($datos,"".date('Y-m-d', strtotime($factura["fechaEmision"])));
            array_push($datos,$factura["fechaAutorizacion"]?date('Y-m-d', strtotime($factura["fechaAutorizacion"])):'');
            array_push($datos,$factura["estado"]);
            array_push($datos,$factura["identificacionComprador"]);
            array_push($datos,$factura["estab"] . $factura["ptoEmi"] . $factura["secuencial"]);
            array_push($datos,number_format ($factura["totalSinImpuestos"] ,2 ));
            array_push($datos, number_format ($factura["valor"] ,2 ));
            array_push($datos,number_format ($factura["importeTotal"] ,2 ));
            $total+=$factura["importeTotal"];
            if($i%2==0)
                $pdf->Row($datos,$pdf->anchos,8,[0,0,0,0,0,0,1,1,1],true);
            else
                $pdf->Row($datos,$pdf->anchos,8,[0,0,0,0,0,0,1,1,1]);
        }

        $datos=["","","","","","","","Total",number_format ($total ,2 )];
        $i++;
        $pdf->SetFont('Arial','B',8);
        if($i%2==0)
            $pdf->Row($datos,$pdf->anchos,8,[0,0,0,0,0,0,1,1,1],true);
        else
            $pdf->Row($datos,$pdf->anchos,8,[0,0,0,0,0,0,1,1,1]);
        $pdf->Output();


        exit;
    }


    public function retenciones(){
        return View::make('pages.reportes.retenciones');
    }

    public function tablaRetenciones(){
        $desde = date('Y-m-d', strtotime(Input::get("desde")));
        $hasta = date('Y-m-d', strtotime(Input::get("hasta")));
        $retenciones = Retencion::where("fechaEmision",">",$desde)->where("fechaEmision","<",$hasta)->orderBy('fechaEmision')->get()->toArray() ;
        $datos = [];

        foreach($retenciones as $retencion){
            $total=0;
            $detalles=DetalleRetencion::where("id","=",$retencion["id"])->get()->toArray();
            foreach($detalles as $d){
                $total+=$d["valorRetenido"];
                $factura = $d["numDocSustento"];
            }
            $tmp=[date('Y-m-d',strtotime($retencion["fechaEmision"])),
                $retencion["estado"],
                $retencion["identificacionSujetoRetenido"],
                $retencion["estab"] . $retencion["ptoEmi"] . $retencion["secuencial"],
                number_format ( $total ,2 ),
                $retencion["id"],
                $total,
                $factura
            ];
            array_push($datos,$tmp);

        }

        return View::make('pages.reportes.tablaRetenciones')->with("desde",$desde)->with("hasta",$hasta)->with("datos",$datos);

    }

    public function retencionesPdf(){
//        phpinfo();
        $desde = date('Y-m-d', strtotime(Input::get("desde")));
        $hasta = date('Y-m-d', strtotime(Input::get("hasta")));
        $retenciones = Retencion::where("fechaEmision",">",$desde)->where("fechaEmision","<",$hasta)->orderBy('fechaEmision')->get()->toArray() ;
        $emisor = Emisor::find(1);

        $columnas = ["#","Fecha emisión","Estado","Ruc","Número","# Factura","Total"];
        $pdf = new \App\library\ReportesPdf();
        $pdf->setTitulo("Reporte de retenciones del ".$desde." al ".$hasta);
        $pdf->setEmisor($emisor);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->setColumnas($columnas,[8,40,25,30,30,30,20,15,20]);
        $i=0;
        $total=0;
        foreach($retenciones as $retencion){
            $i++;
            $detalles=DetalleRetencion::where("id","=",$retencion["id"])->get()->toArray();
            foreach($detalles as $d){
                $total+=$d["valorRetenido"];
                $factura = $d["numDocSustento"];
            }
            $tmp=[
                $i,
                date('Y-m-d',strtotime($retencion["fechaEmision"])),
                $retencion["estado"],
                $retencion["identificacionSujetoRetenido"],
                $retencion["estab"] . $retencion["ptoEmi"] . $retencion["secuencial"],
                $factura,
                number_format ( $total ,2 )
            ];
            if($i%2==0)
                $pdf->Row($tmp,$pdf->anchos,8,[0,0,0,0,0,0,1,1,1],true);
            else
                $pdf->Row($tmp,$pdf->anchos,8,[0,0,0,0,0,0,1,1,1]);

        }

        $datos=["","","","","","Total",number_format ($total ,2 )];
        $i++;
        $pdf->SetFont('Arial','B',8);
        if($i%2==0)
            $pdf->Row($datos,$pdf->anchos,8,[0,0,0,0,0,0,1,1,1],true);
        else
            $pdf->Row($datos,$pdf->anchos,8,[0,0,0,0,0,0,1,1,1]);
        $pdf->Output();


        exit;
    }


    public function notasDeCredito(){
        return View::make('pages.reportes.notas');
    }

    public function tablaNotas(){
        $desde = date('Y-m-d', strtotime(Input::get("desde")));
        $hasta = date('Y-m-d', strtotime(Input::get("hasta")));
        $notas = NotaCredito::where("fechaEmision",">",$desde)->where("fechaEmision","<",$hasta)->orderBy('fechaEmision')->get()->toArray() ;


        return View::make('pages.reportes.tablaNotas')->with("desde",$desde)->with("hasta",$hasta)->with("notas",$notas);

    }
    public function notasPdf(){
        $desde = date('Y-m-d', strtotime(Input::get("desde")));
        $hasta = date('Y-m-d', strtotime(Input::get("hasta")));
        $emisor = Emisor::find(1);
        $notas = NotaCredito::where("fechaEmision",">",$desde)->where("fechaEmision","<",$hasta)->orderBy('fechaEmision')->get()->toArray() ;

        $columnas = ["#","Fecha emisión","Fecha autorización","Estado","Ruc","Número","# Factura","Valor","IVA","Total"];
        $pdf = new \App\library\ReportesPdf();
        $pdf->setTitulo("Reporte de notas de crédito del ".$desde." al ".$hasta);
        $pdf->setEmisor($emisor);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->setColumnas($columnas,[8,20,25,22,25,30,20,15,10,15]);
        $i=0;
        $total=0;
        foreach($notas as $nota){
            $i++;
            $datos = [];
            array_push($datos,"".$i);
            array_push($datos,"".date('Y-m-d', strtotime($nota["fechaEmision"])));
            array_push($datos,$nota["fechaAutorizacion"]?date('Y-m-d', strtotime($nota["fechaAutorizacion"])):'');
            array_push($datos,$nota["estado"]);
            array_push($datos,$nota["identificacionComprador"]);
            array_push($datos,$nota["estab"] . $nota["ptoEmi"] . $nota["secuencial"]);
            array_push($datos,$nota["numDocModificado"]);
            array_push($datos,number_format ($nota["totalSinImpuestos"] ,2 ));
            array_push($datos, number_format ($nota["valor"] ,2 ));
            array_push($datos,number_format ($nota["valorModificacion"] ,2 ));
            $total+=$nota["valorModificacion"];
            if($i%2==0)
                $pdf->Row($datos,$pdf->anchos,8,[0,0,0,0,0,0,0,1,1,1],true);
            else
                $pdf->Row($datos,$pdf->anchos,8,[0,0,0,0,0,0,0,1,1,1]);
        }

        $datos=["","","","","","","","","Total",number_format ($total ,2 )];
        $i++;
        $pdf->SetFont('Arial','B',8);
        if($i%2==0)
            $pdf->Row($datos,$pdf->anchos,8,[0,0,0,0,0,0,0,1,1,1],true);
        else
            $pdf->Row($datos,$pdf->anchos,8,[0,0,0,0,0,0,0,1,1,1]);
        $pdf->Output();


        exit;
    }

}