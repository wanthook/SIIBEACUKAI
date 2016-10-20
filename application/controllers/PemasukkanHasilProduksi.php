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
class PemasukkanHasilProduksi extends Secure_area
{
    function __construct() 
    {
        parent::__construct();
        
        $this->load->model('Mod_pemasukanhasilproduksi');
    }
    
    function index()
    {
        $this->_viewloader("Laporan/PemasukkanHasilProduksi", '',"PemasukkanHasilProduksi");
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
            
            $sel .= "a.pemasukanhasilproduksi_id = '$id'";
        }
        /*
         * order
         */
        $arrCol = array('nomorpib',
                        'nomor',
                        'tanggal',
                        'material_code',
                        'material_desc',
                        'batch',
                        'satuan',
                        'digunakan',
                        'disubkontrak',
                        'gudang',
                        'mark',
                        'pemasukanhasilproduksi_id');
        
        $oBy    = $arrCol[$order[0]['column']];
        $oTy    = $order[0]['dir'];
        
        $ret = array();
                
        $data = array();      
        
        $q      = $this->Mod_pemasukanhasilproduksi->select_master();
        
        $totData = $q->num_rows();        
        $q->free_result();
        
        $q      = $this->Mod_pemasukanhasilproduksi->select_master($sel);
        
        $filData = $q->num_rows();
        $q->free_result();
        
        $dMa = $this->Mod_pemasukanhasilproduksi->select_master($sel,
                                            $length,
                                            $start,
                                            array($oBy=>$oTy));
        
        
        $dMaRes = $dMa->result();
        
        foreach($dMaRes as $res)
        {
            $data[] = array(
                'nopib'                 => $res->nomorpib,
                'no'                    => $res->nomor,
                'tgl'                   => $this->fungsi->convertDate($res->tanggal,"d-m-Y"),
                'matCode'               => $res->material_code,
                'matDes'                => $res->material_desc,
                'batch'                 => $res->batch,
                'satuan'                => $res->satuan,
                'digunakan'             => $res->digunakan,
                'disubkontrak'          => $res->disubkontrak,
                'gudang'                => $res->gudang,
                'mark'                  => $res->mark,
                'pemasukanhasilproduksi_id' => $res->pemasukanhasilproduksi_id,
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
        $data    = $this->Mod_pemasukanhasilproduksi->select_master($sel,0,0,array('nomor','asc'));
//        echo $data->num_rows();
        $tabel = '';
        
        $tabel .= '<table border="1">';
        
        $tabel .= '<thead>';
        
        $tabel .= '<tr>      
                   <th rowspan="2">No. PIB</th>
                   <th rowspan="2">No.</th>
                   <th colspan="2">Bukti Penerimaan</th>
                   <th rowspan="2">Kode Barang</th>
                   <th rowspan="2">Nama Barang</th>
                   <th rowspan="2">Batch</th>
                   <th rowspan="2">Satuan</th>
                   <th colspan="2">Jumlah</th>
                   <th rowspan="2">Gudang</th>
                   </tr>
                   <tr>
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
                $tabel .= '<td>'.$dataValue->nomor.'</td>';
                $tabel .= '<td>'.$this->fungsi->convertDate($dataValue->tanggal,"d-m-Y").'</td>';
                $tabel .= '<td>'.$dataValue->material_code.'</td>';
                $tabel .= '<td>'.$dataValue->material_desc.'</td>';
                $tabel .= '<td>'.$dataValue->batch.'</td>';
                $tabel .= '<td>'.$dataValue->satuan.'</td>';
                $tabel .= '<td>'.$dataValue->digunakan.'</td>';
                $tabel .= '<td>'.$dataValue->disubkontrak.'</td>';
                $tabel .= '<td>'.$dataValue->gudang.'</td>';
                $tabel .= '</tr>';
                
                $no++;
            }
        }
        else 
        {
            $tabel .= '<tr><td colspan="10">Data Kosong</td></tr>';
        }
        
        $tabel .= '</tbody>';
        
        $tabel .= '</table>';
        
        $arrPdf['laporan']          = "Laporan Pemasukkan Hasil Produksi";
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
        
        $data    = $this->Mod_pemasukanhasilproduksi->select_master($sel,0,0,array('nomor','asc'));
        
        $head    = array('No.',
                        'Nomor PIB',
                        'Nomor Bukti Penerimaan',
                        'Tanggal Bukti Penerimaan',
                        'Kode Barang',
                        'Nama Barang',
                        'Batch',
                        'Satuan',
                        'Jumlah Dari Produksi',
                        'Jumlah Dari Subkontrak',
                        'Gudang');
        
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
                    $dataValue->nomor,
                    $this->fungsi->convertDate($dataValue->tanggal,"d-m-Y"),
                    $dataValue->material_code,
                    $dataValue->material_desc,
                    $dataValue->batch,
                    $dataValue->satuan,
                    $dataValue->digunakan,
                    $dataValue->disubkontrak,
                    $dataValue->gudang
                );
                $no++;
            }
        }
        
        $ret    = array(
            'laporan'           => "Laporan Pemasukkan Hasil Produksi",
            'tanggal_awal'      => $sD,
            'tanggal_akhir'     => $eD,
            'head'              => $head,
            'table'             => $table
        );
        
        $this->load->helper("xls_helper");
        $this->load->view("Laporan/downloadXls",$ret);
        
    }
}
