<?php

require('tfpdf.php');
require("mysql.php");

session_start();
$tip_cont = $_SESSION['tipcont'];
$idcont = $_SESSION['idcont'];

if($tip_cont == 4){
    die();
}

$pdf=new tFPDF();
$pdf -> SetAutoPageBreak(true,10);
$pdf->AddPage("L","A4");
$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$pdf->SetFont('DejaVu','',12);
if($tip_cont <= 2){
    $queryClase = $connection->prepare("select * from clase order by clasa, litera");
    $queryClase->execute();
} else {
    $queryClase = $connection->prepare("select distinct profesori_clase.clasa as id, clase.clasa, clase.litera from profesori_clase
    join clase on profesori_clase.clasa = clase.id
    where profesori_clase.profesor = ? 
    order by clasa, litera");
    $queryClase->bind_param("i",$idcont);
    $queryClase->execute();
}
$resultClase=$queryClase->get_result();
while($rowClasa = mysqli_fetch_assoc($resultClase)){
    $queryDiriginte = $connection->prepare("select diriginte from clase where id = ?");
    $queryDiriginte->bind_param("i",$rowClasa['id']);
    $queryDiriginte->execute();
    $resultDiriginte = $queryDiriginte->get_result();
    $rowDiriginte = mysqli_fetch_assoc($resultDiriginte);
    if($idcont == $rowDiriginte['diriginte']){
        $diriginte = true;
    } else $diriginte = false;
    if($tip_cont <= 2 || $diriginte == true){
        $queryMaterii = $connection->prepare("select materie as id from profesori_clase where clasa = ?");
        $queryMaterii->bind_param("i",$rowClasa['id']);
        $queryMaterii->execute();
    } else {
        $queryMaterii = $connection->prepare("select materie as id from profesori_clase where clasa = ? and profesor = ?");
        $queryMaterii->bind_param("ii",$rowClasa['id'],$idcont);
        $queryMaterii->execute();
    }
    $resultMaterii = $queryMaterii->get_result();
    while($rowMaterii = mysqli_fetch_assoc($resultMaterii)){
        $materie = $rowMaterii['id'];
        $queryMaterieNume = $connection->prepare("select * from materii where id = ?");
        $queryMaterieNume->bind_param("i",$materie);
        $queryMaterieNume->execute();
        $resultMaterieNume = $queryMaterieNume->get_result();
        $rowMaterieNume = mysqli_fetch_assoc($resultMaterieNume);
        $materieNume = $rowMaterieNume['materie'];
        $pdf->SetFont('DejaVu','',15);
        $clasaId = $rowClasa['id'];
        $clasaN = $rowClasa['clasa'];
        $litera = $rowClasa['litera'];
        $pdf->cell(5,15,"Clasa ".$clasaN.$litera." - ".$materieNume);
        $pdf->Ln(15);
        $pdf->SetFont('DejaVu','',12);
        $queryElevi = $connection->prepare("select * from elevi where clasa = ? order by nume,prenume,prenume2");
        $queryElevi->bind_param("i",$clasaId);
        $queryElevi->execute();
        $resultElevi = $queryElevi->get_result();
        $counter = 0;
        $pdf->cell(10,15,"#",1);
        $pdf->cell(80,15,"Nume si prenume",1);
        $pdf->SetFont('DejaVu','',10);
        $pdf->cell(15,15,"Nr. abs.",1,"C");
        $pdf->Ln(0);
        while($rowElevi = mysqli_fetch_assoc($resultElevi)){
            $x=$pdf->GetX();
            $y=$pdf->GetY();
            $pdf->SetFont('DejaVu','',12);
            $elevId = $rowElevi['id'];
            $counter++;
            $fullname = $rowElevi['nume']." ".$rowElevi['prenume'];
            $pdf->MultiCell(10,15,$counter,1);
            $pdf->SetXY($x+10,$y);
            $pdf->SetFont('DejaVu','',10);
            $pdf->MultiCell(80,15,$fullname,1);
            $pdf->SetXY($x+90,$y);
            $queryNrAbs = "select count(id) as nrabs from absente where elev = ".$elevId." and materie = ".$materie." and motivata = 0";
            $queryNrAbs = $connection->prepare("select count(id) as nrabs from absente where elev = ? and materie = ? and motivata = 0");
            $queryNrAbs->bind_param("ii",$elevId,$materie);
            $queryNrAbs->execute();
            $resultNrAbs = $queryNrAbs->get_result();
            $rowMedia = mysqli_fetch_assoc($resultNrAbs);
            $nrabs = $rowMedia['nrabs'];
            $x=$pdf->GetX();
            $y=$pdf->GetY();
            $pdf->SetFont('DejaVu','',9);	
            $pdf->MultiCell(15,15,$nrabs,1,"C");
            $pdf->SetXY($x+15,$y);
            $queryAbs = "select * from absente where elev = ".$elevId." and materie = ".$materie;
            $queryAbs = $connection->prepare("select * from absente where elev = ? and materie = ?");
            $queryAbs->bind_param("ii",$elevId,$materie);
            $queryAbs->execute();
            $resultAbs = $queryAbs->get_result();
            while($rowNote = mysqli_fetch_assoc($resultAbs)){
                $x=$pdf->GetX();
                $y=$pdf->GetY();
                $motivata = $rowNote['motivata'];
                $data = $rowNote['data'];
                $data = date("d.m Y",strtotime($data));
                if($motivata == 0){
                    $motivata = "N";
                } else {
                    $motivata = "M";
                }
                $cell_content = $motivata."\n".$data;
                $pdf->SetFont('DejaVu','',9);	
                $pdf->MultiCell(12,5,$cell_content,1,"C");
                $pdf->SetXY($x+12,$y);
            }
            $pdf->Ln(15);
            if($counter % 10 == 0){
                $pdf->AddPage("L","A4");
            }
        }
        $pdf->AddPage("L","A4");
    } 
}
$pdf->Output("I","situatieabsente.pdf");
?>