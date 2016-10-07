<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

tcpdf();
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('wanthook');
$pdf->SetTitle($laporan.' Periode '.$tanggal_awal.' s/d '.$tanggal_akhir);
$pdf->SetSubject('Laporang Beacukai PT. Spinmill Indah Industry');
$pdf->SetKeywords('Laporan, Beacukai, Spinmill');

// set default header data
$pdf->SetHeaderData("LogoSpinmill.jpg", 40, "$laporan \nPeriode ".$tanggal_awal." s/d ".$tanggal_awal, "PT. Spinmill Indah Industry"."\n"."Jl. Aria Jaya Sentika No. 55, Desa Pasir Nangka RT.006/002 Kec. Tigaraksa, Kab. Tangerang 15720 Banten");
// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$pdf->SetFont('helvetica', '', 5);
$pdf->setFontSubsetting(false);

// add a page
$pdf->AddPage();



$pdf->writeHTML($tabel, true, 0, true, 0);
        
// set document information
$pdf->Output(str_replace(" ", "_", $laporan).date('d_m_Y').'.pdf', 'I');