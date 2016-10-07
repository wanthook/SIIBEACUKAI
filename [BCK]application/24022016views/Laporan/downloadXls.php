<?php 
phpexcel();

$phpExcel = new PHPExcel();

$phpExcel->getProperties()->setCreator("Spinmill Indah Industry, PT.")
         ->setLastModifiedBy("Spinmill Indah Industry, PT.")
         ->setDescription($laporan." XLSX")
         ->setKeywords("office 2007 openxml php")
         ->setCategory($laporan." XLSX");

$col = 0;
$row = 1;

$sheet = 0;

$ws = new PHPExcel_Worksheet($phpExcel, $laporan);
$phpExcel->addSheet($ws, $sheet);

/*
 * pilih sheet aktif
 */
$phpExcel->setActiveSheetIndex($sheet);

foreach($head as $kHead => $vHead)
{
    $phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow($col, $row, $vHead, PHPExcel_Cell_DataType::TYPE_STRING);
    $col++;
}
$row +=1;
$col = 0;

$totPPN = 0;
$totDpp = 0;

if(isset($table))
{
    
    if(count($table)>0)
    {
        foreach($table as $kTable => $vTable)
        {
            for($i=0 ; $i<count($vTable) ; $i++)
            {
                $phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow($col, $row, $vTable[$i], PHPExcel_Cell_DataType::TYPE_STRING);
                $col++;
            }
            $row +=1;
            $col = 0;
        }
    }
}


$phpExcel->setActiveSheetIndex(0);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="$laporan.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel2007');
$objWriter->save('php://output');

$phpExcel->disconnectWorksheets();
unset($phpExcel);