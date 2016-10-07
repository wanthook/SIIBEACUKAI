<?php 
phpexcel();

$phpExcel = new PHPExcel();

$phpExcel->getProperties()->setCreator("Indah Jaya Textile Industry, PT.")
         ->setLastModifiedBy("Indah Jaya Textile Industry, PT.")
         ->setDescription("Laporan E-faktur XLSX")
         ->setKeywords("office 2007 openxml php")
         ->setCategory("Laporan E-faktur XLSX");

$col = 0;
$row = 1;

$sheet = 0;

$ws = new PHPExcel_Worksheet($phpExcel, "Faktur PM");
$phpExcel->addSheet($ws, $sheet);

/*
 * pilih sheet aktif
 */
$phpExcel->setActiveSheetIndex($sheet);

$phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow($col, $row, "FM", PHPExcel_Cell_DataType::TYPE_STRING);
$phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(++$col, $row, "KD_JENIS_TRANSAKSI", PHPExcel_Cell_DataType::TYPE_STRING);
$phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(++$col, $row, "FG_PENGGANTI", PHPExcel_Cell_DataType::TYPE_STRING);
$phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(++$col, $row, "NOMOR_FAKTUR", PHPExcel_Cell_DataType::TYPE_STRING);
$phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(++$col, $row, "MASA_PAJAK", PHPExcel_Cell_DataType::TYPE_STRING);
$phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(++$col, $row, "TAHUN_PAJAK", PHPExcel_Cell_DataType::TYPE_STRING);
$phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(++$col, $row, "TANGGAL_FAKTUR", PHPExcel_Cell_DataType::TYPE_STRING);
$phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(++$col, $row, "NPWP", PHPExcel_Cell_DataType::TYPE_STRING);
$phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(++$col, $row, "NAMA", PHPExcel_Cell_DataType::TYPE_STRING);
$phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(++$col, $row, "ALAMAT_LENGKAP", PHPExcel_Cell_DataType::TYPE_STRING);
$phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(++$col, $row, "JUMLAH_DPP", PHPExcel_Cell_DataType::TYPE_STRING);
$phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(++$col, $row, "JUMLAH_PPN", PHPExcel_Cell_DataType::TYPE_STRING);
$phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(++$col, $row, "JUMLAH_PPNBM", PHPExcel_Cell_DataType::TYPE_STRING);
$phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(++$col, $row, "IS_CREDITABLE", PHPExcel_Cell_DataType::TYPE_STRING);

$row +=1;
$col = 0;

if(isset($table))
{
    foreach ($table as $key => $value)
    {
        $phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow($col, $row, 'FM', PHPExcel_Cell_DataType::TYPE_STRING);
        $phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(++$col, $row, $value->kdJenisTransaksi, PHPExcel_Cell_DataType::TYPE_STRING);
        $phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(++$col, $row, $value->fgPengganti, PHPExcel_Cell_DataType::TYPE_STRING);
        $phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(++$col, $row, $value->nomorFaktur, PHPExcel_Cell_DataType::TYPE_STRING);
        $phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(++$col, $row, $value->masaPajak, PHPExcel_Cell_DataType::TYPE_STRING);
        $phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(++$col, $row, $value->tahunPajak, PHPExcel_Cell_DataType::TYPE_STRING);
        $phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(++$col, $row, $this->fungsi->convertDate($value->tanggalFaktur,'d/m/Y'), PHPExcel_Cell_DataType::TYPE_STRING);
        $phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(++$col, $row, $value->npwpPenjual, PHPExcel_Cell_DataType::TYPE_STRING);
        $phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(++$col, $row, $value->namaPenjual, PHPExcel_Cell_DataType::TYPE_STRING);
        $phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(++$col, $row, $value->alamatPenjual, PHPExcel_Cell_DataType::TYPE_STRING);
        $phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(++$col, $row, number_format($value->jumlahDpp,0,'',''), PHPExcel_Cell_DataType::TYPE_STRING);
        $phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(++$col, $row, number_format($value->jumlahPpn,0,'',''), PHPExcel_Cell_DataType::TYPE_STRING);
        $phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(++$col, $row, number_format($value->jumlahPpnBm,0,'',''), PHPExcel_Cell_DataType::TYPE_STRING);
        $phpExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(++$col, $row, '', PHPExcel_Cell_DataType::TYPE_STRING);
    
        $row +=1;
        $col = 0;
    }
}


$phpExcel->setActiveSheetIndex(0);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Laporan E-faktur.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel2007');
$objWriter->save('php://output');

$phpExcel->disconnectWorksheets();
unset($phpExcel);