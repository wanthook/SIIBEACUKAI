<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function pdf_create($html, $filename='', $stream=TRUE)
{
    require_once 'dompdf/dompdf_config.inc.php';
    
    $dompdf = new DOMPDF();
    $dompdf->load_html($html);
    $dompdf->set_paper('A4', 'landscape');
    $dompdf->render();
    if($stream)
    {
        $dompdf->stream($filename.".pdf");
    }
    else
    {
        return $dompdf->output();
    }
}