<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once ("Secure_area.php");

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Dashboard
 *
 * @author wanthook
 */
class PengeluaranHasilProduksi extends Secure_area
{
    function __construct() 
    {
        parent::__construct();
        
        $this->load->model('Mod_pengeluaranhasilproduksi');
    }
    
    function index()
    {
        $this->_viewloader("Laporan/PengeluaranHasilProduksi", '',"PengeluaranHasilProduksi");
    }
    
    function table()
    {
        
        $sel = 'a.hapus=1 ';
        
        $id = $this->input->post_get('id');
        
        $start  = $this->input->post_get('start');
        $length = $this->input->post_get('length');
        
        $draw   = $this->input->post_get('draw');
        
        $order  = $this->input->post_get('order');
        $oBy    = "";
        $oTy    = "";
        
        $sD     = $this->input->post_get('sD');
        $eD     = $this->input->post_get('eD');
        
        if(!empty($sD) && !empty($eD))
        {
            if(!empty($sel)) $sel .= "and ";
            
            $sel .= "(tanggal between '".$this->fungsi->convertDate($sD,"Y-m-d")."' and '".$this->fungsi->convertDate($eD,"Y-m-d")."')";
        }
        
        if(!empty($id))
        {
            if(!empty($sel)) $sel .= "and ";
            
            $sel .= "a.pengeluaranhasilproduksi_id = '$id'";
        }
        /*
         * order
         */
        $arrCol = array('nomorpib',
                        'nomorpeb',
                        'tanggalpeb',
                        'nomor',
                        'tanggal',
                        'pembeli',
                        'negara_tujuan',
                        'material_code',
                        'material_desc',
                        'batch',
                        'satuan',
                        'jumlah',
                        'jumlah_subkontrak',
                        'mata_uang',
                        'nilai_barang',
                        'gudang',                        
                        'mat_doc',     
                        'mark',
                        'pengeluaranhasilproduksi_id');
        
        $oBy    = $arrCol[$order[0]['column']];
        $oTy    = $order[0]['dir'];
        
        $ret = array();
                
        $data = array();      
        
        $q      = $this->Mod_pengeluaranhasilproduksi->select_master();
        
        $totData = $q->num_rows();        
        $q->free_result();
        
        $q      = $this->Mod_pengeluaranhasilproduksi->select_master($sel);
        
        $filData = $q->num_rows();
        $q->free_result();
        
        $dMa = $this->Mod_pengeluaranhasilproduksi->select_master($sel,
                                            $length,
                                            $start,
                                            array($oBy=>$oTy));
        
        
        $dMaRes = $dMa->result();
        
        foreach($dMaRes as $res)
        {
            $data[] = array(
                'nopib'                 => $res->nomorpib,
                'nopeb'                 => $res->nomorpeb,
                'tglpeb'                => $this->fungsi->convertDate($res->tanggalpeb,"d-m-Y"),
                'no'                    => $res->nomor,
                'tgl'                   => $this->fungsi->convertDate($res->tanggal,"d-m-Y"),
                'pembeli'               => $res->pembeli,
                'negara_tujuan'         => $res->negara_tujuan,
                'matCode'               => $res->material_code,
                'matDes'                => $res->material_desc,
                'batch'                 => $res->batch,
                'satuan'                => $res->satuan,
                'dariproduksi'          => $res->jumlah,
                'darisubkontrak'        => $res->jumlah_subkontrak,
                'matauang'              => $res->mata_uang,
                'nilaibarang'           => $res->nilai_barang,
                'gudang'                => $res->gudang,
                'matdoc'                => $res->mat_doc,
                'mark'                  => $res->mark,
                'pengeluaranhasilproduksi_id' => $res->pengeluaranhasilproduksi_id,
                'createdAt'             => $this->fungsi->convertDate($res->created_at,"d-m-Y H:i:s")
            );
//            );
        }
        
        $ret['draw']            = $draw;
        $ret['recordsTotal']    = $totData;
        $ret['recordsFiltered'] = $filData;
        $ret['data']            = $data;
        
        echo json_encode($ret);
    }
    
    function pdf()
    {
        $sel    = "";
        
        $sD     = $this->input->post_get('sD');
        $eD     = $this->input->post_get('eD');
        
        if(!empty($sD) && !empty($eD))
        {
            if(!empty($sel)) $sel .= "and ";
            
            $sel .= "tanggal between '".$this->fungsi->convertDate($sD,"Y-m-d")."' and '".$this->fungsi->convertDate($eD,"Y-m-d")."'";
        }
        $data    = $this->Mod_pengeluaranhasilproduksi->select_master($sel,0,0,array('nomor','asc'));
//        echo $data->num_rows();
        $tabel = '';
        
        $tabel .= '<table border="1">';
        
        $tabel .= '<thead>';
        
        $tabel .= '<tr>      
                   <th rowspan="2">No.</th>
                   <th colspan="2">PIB</th>
                   <th colspan="2">PEB</th>
                   <th colspan="2">Bukti Pengeluaran</th>
                   <th rowspan="2">Pembeli/Penerima</th>
                   <th rowspan="2">Negara Tujuan</th>
                   <th rowspan="2">Kode Barang</th>
                   <th rowspan="2">Nama Barang</th>
                   <th rowspan="2">Batch</th>
                   <th rowspan="2">Satuan</th>
                   <th colspan="2">Jumlah</th>
                   <th rowspan="2">Mata Uang</th>
                   <th rowspan="2">Nilai Barang</th>
                   <th rowspan="2">Gudang</th>
                   <th rowspan="2">Materia Dokumen</th>
                   </tr>
                   <tr>
                   <th>Nomor</th>
                   <th>Tanggal</th>
                   <th>Nomor</th>
                   <th>Tanggal</th>
                   <th>Dari Produksi</th>
                   <th>Dari Subkontrak</th>
                   </tr>';
        
        $tabel .= '</thead>';
        
        $tabel .= '<tbody>';
        
        if($data->num_rows()>0)
        {
            $no = 1;
            
            $res    = $data->result();
            
            foreach ($res as $dataKey => $dataValue)
            {
                $tabel .= '<tr>';
                $tabel .= '<td>'.$no.'</td>';
                $tabel .= '<td>'.$dataValue->nomorpib.'</td>';
                $tabel .= '<td>'.$dataValue->nomorpeb.'</td>';
                $tabel .= '<td>'.$this->fungsi->convertDate($dataValue->tanggalpeb,"d-m-Y").'</td>';
                $tabel .= '<td>'.$dataValue->nomor.'</td>';
                $tabel .= '<td>'.$this->fungsi->convertDate($dataValue->tanggal,"d-m-Y").'</td>';
                $tabel .= '<td>'.$dataValue->pembeli.'</td>';
                $tabel .= '<td>'.$dataValue->negara_tujuan.'</td>';
                $tabel .= '<td>'.$dataValue->material_code.'</td>';
                $tabel .= '<td>'.$dataValue->material_desc.'</td>';
                $tabel .= '<td>'.$dataValue->batch.'</td>';
                $tabel .= '<td>'.$dataValue->satuan.'</td>';
                $tabel .= '<td>'.$dataValue->jumlah.'</td>';
                $tabel .= '<td>'.$dataValue->jumlah_subkontrak.'</td>';
                $tabel .= '<td>'.$dataValue->mata_uang.'</td>';
                $tabel .= '<td>'.$dataValue->nilai_barang.'</td>';
                $tabel .= '<td>'.$dataValue->gudang.'</td>';
                $tabel .= '<td>'.$dataValue->mat_doc.'</td>';
                $tabel .= '</tr>';
                
                $no++;
            }
        }
        else 
        {
            $tabel .= '<tr><td colspan="17">Data Kosong</td></tr>';
        }
        
        $tabel .= '</tbody>';
        
        $tabel .= '</table>';
        
        $arrPdf['laporan']          = "Laporan Pengeluaran Hasil Produksi";
        $arrPdf['tanggal_awal']     = $sD;
        $arrPdf['tanggal_akhir']    = $eD;
        $arrPdf['tabel']            = $tabel;
        
        if($this->input->post_get('t')=="print")
        {
            $this->load->view("Laporan/Print",$arrPdf);
        }
        else
        {
            $this->load->helper("pdf_helper");
            $this->load->view("Laporan/downloadPdf",$arrPdf);
        }
        return;
    }
    
    function excel()
    {        
        $ret    = array();
        $sel    = "";
        
        $sD     = $this->input->post_get('sD');
        $eD     = $this->input->post_get('eD');
        
        if(!empty($sD) && !empty($eD))
        {
            if(!empty($sel)) $sel .= "and ";
            
            $sel .= "tanggal between '".$this->fungsi->convertDate($sD,"Y-m-d")."' and '".$this->fungsi->convertDate($eD,"Y-m-d")."'";
        }
        
        $data    = $this->Mod_pengeluaranhasilproduksi->select_master($sel,0,0,array('nomor','asc'));
        
        $head    = array('No.',
                        'Nomor PIB',
                        'Nomor PEB',
                        'Tanggal PEB',
                        'Nomor Bukti Pengeluaran',
                        'Tanggal Bukti Pengeluaran',
                        'Pembeli/Penerima',
                        'Negara Tujuan',
                        'Kode Barang',
                        'Nama Barang',
                        'Batch',
                        'Satuan',
                        'Jumlah Dari Produksi',
                        'Jumlah Dari Subkontrak',
                        'Mata Uang',
                        'Nilai Barang',
                        'Gudang',
                        'Materia Dokumen');
        
        $table  = array();
        
        if($data->num_rows()>0)
        {
            $no = 1;
            
            $res    = $data->result();
            
            foreach ($res as $dataKey => $dataValue)
            {
                
                $table[]    = array(
                    $no,
                    $dataValue->nomorpib,
                    $dataValue->nomorpeb,
                    $this->fungsi->convertDate($dataValue->tanggalpeb,"d-m-Y"),
                    $dataValue->nomor,
                    $this->fungsi->convertDate($dataValue->tanggal,"d-m-Y"),
                    $dataValue->pembeli,
                    $dataValue->negara_tujuan,
                    $dataValue->material_code,
                    $dataValue->material_desc,
                    $dataValue->batch,
                    $dataValue->satuan,
                    $dataValue->jumlah,
                    $dataValue->jumlah_subkontrak,
                    $dataValue->mata_uang,
                    $dataValue->nilai_barang,
                    $dataValue->gudang,
                    $dataValue->mat_doc
                );
                $no++;
            }
        }
        
        $ret    = array(
            'laporan'           => "Laporan Pengeluaran Hasil Produksi",
            'tanggal_awal'      => $sD,
            'tanggal_akhir'     => $eD,
            'head'              => $head,
            'table'             => $table
        );
        
        $this->load->helper("xls_helper");
        $this->load->view("Laporan/downloadXls",$ret);
        
    }
    
    private function Form($data="")
    {
        $this->_viewloader("Laporan/PengeluaranHasilProduksi", $data,"PengeluaranHasilProduksi");
    }
}
