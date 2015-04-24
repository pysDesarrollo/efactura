<?php
namespace App\library;


use Anouar\Fpdf\Fpdf;

class ReportesPdf extends Fpdf {

    var $titulo;
    var $orientacion;
    var $emisor;
    var $datos;
    var $columnas;
    var $anchos;

    function setTitulo($titulo){
        $this->titulo=utf8_decode($titulo);
    }
    function setEmisor($emisor){
        $this->emisor=$emisor;
    }

    function setDatos($datos){
        $this->datos=$datos;
    }
    function setColumnas($columnas,$anchos){
        $this->columnas=$columnas;
        $this->anchos=$anchos;
        $this->SetFillColor(0, 110, 183);
        $this->SetTextColor(255);
        $this->SetDrawColor(0,0,0);
        $this->SetLineWidth(.3);
        $this->SetFont('Arial','B',10);
        $this->Row($columnas,$anchos);
        $this->SetFillColor(224,235,255);
        $this->SetTextColor(0);
        $this->SetFont('');
    }
    function fillTable($datos){
        $this->setDatos($datos);
        $this->SetFont('Arial','',8);
        $this->Row($this->datos,$this->anchos);
    }
    function Row($data,$anchos,$font=null,$numeros=null,$fill=null){
        if($font){
            $this->SetFontSize($font);
        }
        //Calculate the height of the row
        $nb=0;
        for($i=0;$i<count($data);$i++)
            $nb=max($nb, $this->NbLines($anchos[$i], $data[$i]));
        $h=5*$nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row

        for($i=0;$i<count($data);$i++)
        {
            $esNumero = false;
            if($numeros){
                if($numeros[$i]==1)
                    $esNumero=true;
            }
            $w=$anchos[$i];
            $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x=$this->GetX();
            $y=$this->GetY();
            //Draw the border
            if(!$fill)
                $this->Rect($x, $y, $w, $h,"DF");
            else
                $this->Rect($x, $y, $w, $h,"D");
            //Print the text
            if($esNumero){
                $this->MultiCell($w, 5,utf8_decode( $data[$i]), 0, "R",false);
            }else{
                $this->MultiCell($w, 5,utf8_decode( $data[$i]), 0, "C",false);
            }
            //Put the position to the right of the cell
            $this->SetXY($x+$w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }


    function Header()
    {
        // Logo
        $this->Image($_SERVER['DOCUMENT_ROOT'].'/efactura/img/logo-pdf-header.png',10,13,50);
        // Arial bold 15
        $this->SetFont('Arial','',10);
        $this->Cell(75);
        $this->Cell(40,10,$this->emisor->emi_nombre,0,0,'C');
        $this->Ln(5);
        $this->Cell(75);
        $this->Cell(40,10,$this->emisor->emi_ruc,0,0,'C');
        $this->Ln(5);
        $this->Cell(75);
        $this->Cell(40,10,$this->emisor->emi_direccion_matriz,0,0,'C');
        $this->Ln(10);
        // Title
        $this->SetFont('Arial','B',10);
        /*Width heigth texto borde ln align*/
        $this->Cell(0,10,$this->titulo,0,0,'C');
        // Line break
        $this->Ln(20);
    }

// Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,utf8_decode('Impreso el '.date("y-m-d h:i:s").' - Página '.$this->PageNo().'/{nb}'),0,0,'C');
    }

    function CheckPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        if($this->GetY()+$h>$this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w, $txt)
    {
        //Computes the number of lines a MultiCell of width w will take
        $cw=&$this->CurrentFont['cw'];
        if($w==0)
            $w=$this->w-$this->rMargin-$this->x;
        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
        $s=str_replace("\r", '', $txt);
        $nb=strlen($s);
        if($nb>0 and $s[$nb-1]=="\n")
            $nb--;
        $sep=-1;
        $i=0;
        $j=0;
        $l=0;
        $nl=1;
        while($i<$nb)
        {
            $c=$s[$i];
            if($c=="\n")
            {
                $i++;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
                continue;
            }
            if($c==' ')
                $sep=$i;
            $l+=$cw[$c];
            if($l>$wmax)
            {
                if($sep==-1)
                {
                    if($i==$j)
                        $i++;
                }
                else
                    $i=$sep+1;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
            }
            else
                $i++;
        }
        return $nl;
    }

}


?>