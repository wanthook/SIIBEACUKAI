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
class PemakaianBahanBaku extends Secure_area
{
    function __construct() 
    {
        parent::__construct();
        
        $this->load->model('Mod_pemakaianbahanbaku');
    }
    
    function index()
    {
        $this->_viewloader("Laporan/PemakaianBahanBaku", '',"PemakaianBahanBaku");
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
            
            $sel .= "a.pemakaianbahanbaku_id = '$id'";
        }
        /*
         * order
         */
        $arrCol = array('nomor',
                        'tanggal',
                        'material_code',
                        'material_desc',
                        'batch',
                        'satuan',
                        'digunakan',
                        'disubkontrak',
                        'penerima',
                        'pemakaianbahanbaku_id');
        
        $oBy    = $arrCol[$order[0]['column']];
        $oTy    = $order[0]['dir'];
        
        $ret = array();
                
        $data = array();      
        
        $q      = $this->Mod_pemakaianbahanbaku->select_master();
        
        $totData = $q->num_rows();        
        $q->free_result();
        
        $q      = $this->Mod_pemakaianbahanbaku->select_master($sel);
        
        $filData = $q->num_rows();
        $q->free_result();
        
        $dMa = $this->Mod_pemakaianbahanbaku->select_master($sel,
                                            $length,
                                            $start,
                                            array($oBy=>$oTy));
        
        
        $dMaRes = $dMa->result();
        
        foreach($dMaRes as $res)
        {
            $data[] = array(
                'no'                    => $res->nomor,
                'tgl'                   => $this->fungsi->convertDate($res->tanggal,"d-m-Y"),
                'matCode'               => $res->material_code,
                'matDes'                => $res->material_desc,
                'batch'                 => $res->batch,
                'satuan'                => $res->satuan,
                'digunakan'             => $res->digunakan,
                'disubkontrakkan'       => $res->disubkontrak,
                'penerima'              => $res->penerima,
                'pemakaianbahanbaku_id' => $res->pemakaianbahanbaku_id,
                'createdAt'             => $this->fungsi->convertDate($res->created_at,"d-m-Y H:i:s")
            );
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
        $data    = $this->Mod_pemakaianbahanbaku->select_master($sel,0,0,array('nomor'=>'asc'));
        
        $tabel = '';
        
        $tabel .= '<table border="1" style="width: 100%;">';
        
        $tabel .= '<thead>';
        
        $tabel .= '<tr>      
                   <th rowspan="2">No.</th>
                   <th colspan="2">Bukti Pengeluaran</th>
                   <th rowspan="2">Kode Barang</th>
                   <th rowspan="2">Nama Barang</th>
                   <th rowspan="2">Batch</th>
                   <th rowspan="2">Satuan</th>
                   <th colspan="2">Jumlah</th>
                   <th>Penerima</th>
                   </tr>
                   <tr>
                   <th>Nomor</th>
                   <th>Tanggal</th>
                   <th>Digunakan</th>
                   <th>Disubkontrakkan</th>
                   <th>Subkontrak</th>
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
                $tabel .= '<td>'.$dataValue->nomor.'</td>';
                $tabel .= '<td>'.$this->fungsi->convertDate($dataValue->tanggal,"d-m-Y").'</td>';
                $tabel .= '<td>'.$dataValue->material_code.'</td>';
                $tabel .= '<td>'.$dataValue->material_desc.'</td>';
                $tabel .= '<td>'.$dataValue->batch.'</td>';
                $tabel .= '<td>'.$dataValue->satuan.'</td>';
                $tabel .= '<td>'.$dataValue->digunakan.'</td>';
                $tabel .= '<td>'.$dataValue->disubkontrak.'</td>';
                $tabel .= '<td>'.$dataValue->penerima.'</td>';
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
        
        $arrPdf['laporan']          = "Laporan Pemakaian Bahan Baku";
        $arrPdf['tanggal_awal']     = $sD;
        $arrPdf['tanggal_akhir']    = $eD;
        $arrPdf['tabel']            = $tabel;
        
//        $this->load->helper("dompdf");
//        $pdData = $this->load->view("Laporan/pdf",$arrPdf,true);
//        $fl     = pdf_create($pdData,'',FALSE);
//        write_file('./report/output.pdf', $fl);
        
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
}
