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
class MutasiBahanBaku extends Secure_area
{
    function __construct() 
    {
        parent::__construct();
        
        $this->load->model('Mod_mutasibahanbaku');
//		$this->load->model('Mod_pemasukanbahanbaku');
//		$this->load->model('Mod_pemakaianbahanbaku');
    }
    
    function index()
    {
//        $hsl = $this->proc_mutation();
//        print_r($hsl);
        $this->_viewloader("Laporan/MutasiBahanBaku", '',"MutasiBahanBaku");
    }
    
    
//    function table()
//    {
//        /* $this->Mod_pemasukanbahanbaku->prepare_mutation(); */
//        $sel = 'a.hapus=1 ';
//        
//        $id = $this->input->post_get('id');
//        
//        $start  = $this->input->post_get('start');
//        $length = $this->input->post_get('length');
//        
//        $draw   = $this->input->post_get('draw');
//        
//        //$order  = $this->input->post_get('order');
//        $oBy    = "";
//        $oTy    = "";
//        
//        $sD     = $this->input->post_get('sD');
//        $eD     = $this->input->post_get('eD');
//        
//        
//        $ret = array();
//        
//        if(!empty($sD) && !empty($eD))
//        {
//            if(!empty($sel)) $sel .= "and ";
//            
//            $sel .= "(tanggal between '".$this->fungsi->convertDate($sD,"Y-m-d")."' and '".$this->fungsi->convertDate($eD,"Y-m-d")."')";
//        }
//        else
//        {
//            $ret['draw']            = 0;
//            $ret['recordsTotal']    = 0;
//            $ret['recordsFiltered'] = 0;
//            $ret['data']            = array();
//            echo json_encode($ret);
//            return;
//        }
//        
//        if(!empty($id))
//        {
//            if(!empty($sel)) $sel .= "and ";
//            
//            $sel .= "a.mutasibahanbaku_id = '$id'";
//        }
//        /*
//         * order
//         */
//        $arrCol = array('material_code',
//                        'material_desc',
//                        'batch',
//                        'satuan',
//                        'saldo_awal',												'saldo_awal_lbs',
//                        'pemasukan',												'pemasukan_lbs',
//                        'pengeluaran',												'pengeluaran_lbs',
//                        'saldo_akhir',												'saldo_akhir_lbs',
//                        'gudang',
//                        'mark',
//                        'mutasibahanbaku_id');
//        
//        //$oBy    = $arrCol[$order[0]['column']];
//        //$oTy    = $order[0]['dir'];
//        
//        $data = array();      
//        
//        $q      = $this->Mod_mutasibahanbaku->select_master();
//        
//        $totData = $q->num_rows();        
//        $q->free_result();
//        
//        $q      = $this->Mod_mutasibahanbaku->select_master($sel);
//        
//        $filData = $q->num_rows();
//        $q->free_result();
//        
//        $dMa = $this->Mod_mutasibahanbaku->select_master($sel,
//                                            $length,
//                                            $start,
//                                            array($oBy=>$oTy));
//        
//        
//        $dMaRes = $dMa->result();
//        
//        foreach($dMaRes as $res)
//        {            
//            if($batch != $res->batch)
//            {
//                $batch = $res->batch;
//                $lblbatch = $res->batch;
//                $lblmatcode = $res->material_code;
//                $lblmatdesc = $res->material_desc;
//            }
//            else
//            {
////                $lblbatch = "";
//                $lblmatcode = "";
//                $lblmatdesc = "";
//            }
//            
//            $data[] = array(
//                'matCode'               => $lblmatcode,
//                'matDes'                => $lblmatdesc,
//                'batch'                 => $lblbatch,
//                'satuan'                => $res->satuan,
//                'saldo_awal'            => $res->saldo_awal,								
//                'lbsSaldo_awal'         => $res->saldo_awal_lbs,
//                'pemasukan'             => $res->pemasukan,								
//                'lbsPemasukan'          => $res->pemasukan_lbs,
//                'pengeluaran'           => $res->pengeluaran,								
//                'lbsPengeluaran'        => $res->pengeluaran_lbs,
//                'saldo_akhir'           => $res->saldo_akhir,								
//                'lbsSaldo_akhir'        => $res->saldo_akhir_lbs,
//                'gudang'                => $res->gudang,
//                'mark'                  => $res->mark,
//                'mutasibahanbaku_id'    => $res->mutasibahanbaku_id,
//                'createdAt'             => $this->fungsi->convertDate($res->created_at,"d-m-Y H:i:s")
//            );
////            );
//        }
//        
//        $ret['draw']            = $draw;
//        $ret['recordsTotal']    = $totData;
//        $ret['recordsFiltered'] = $filData;
//        $ret['data']            = $data;
//        
//        echo json_encode($ret);
//    }
    
    function table()
    {
        /* $this->Mod_pemasukanbahanbaku->prepare_mutation(); */
        $sel = 'a.hapus=1 ';
        
        $id = $this->input->post_get('id');
        
        $start  = $this->input->post_get('start');
        $length = $this->input->post_get('length');
        
        $draw   = $this->input->post_get('draw');
        
        //$order  = $this->input->post_get('order');
        $oBy    = "";
        $oTy    = "";
        
        $sD     = $this->input->post_get('sD');
        $eD     = $this->input->post_get('eD');
        
        
        $ret = array();
        
        if(!empty($sD) && !empty($eD))
        {
            if(!empty($sel)) $sel .= "and ";
            
            $sel .= "(tanggal between '".$this->fungsi->convertDate($sD,"Y-m-d")."' and '".$this->fungsi->convertDate($eD,"Y-m-d")."')";
        }
        else
        {
            $ret['draw']            = 0;
            $ret['recordsTotal']    = 0;
            $ret['recordsFiltered'] = 0;
            $ret['data']            = array();
            echo json_encode($ret);
            return;
        }
        
        if(!empty($id))
        {
            if(!empty($sel)) $sel .= "and ";
            
            $sel .= "a.mutasibahanbaku_id = '$id'";
        }
        /*
         * order
         */
        $arrCol = array('material_code',
                        'material_desc',
                        'batch',
                        'satuan',
                        'saldo_awal',												'saldo_awal_lbs',
                        'pemasukan',												'pemasukan_lbs',
                        'pengeluaran',												'pengeluaran_lbs',
                        'saldo_akhir',												'saldo_akhir_lbs',
                        'gudang',
                        'mark',
                        'mutasibahanbaku_id');
        
        //$oBy    = $arrCol[$order[0]['column']];
        //$oTy    = $order[0]['dir'];
        
        $data = array();      
        
        $q      = $this->Mod_mutasibahanbaku->select_master();
        
        $totData = $q->num_rows();        
        $q->free_result();
        
        $q      = $this->Mod_mutasibahanbaku->select_master($sel);
        
        $filData = $q->num_rows();
        $q->free_result();
        
        $dMa = $this->Mod_mutasibahanbaku->select_master($sel,
                                            $length,
                                            $start,
                                            array($oBy=>$oTy));
        
        
        $dMaRes = $dMa->result();
        $matcode = "";
        $matdesc = "";
        $batch      = "";
        $lblmatcode = "";
        $lblmatdesc = "";
        $lblbatch      = "";
        foreach($dMaRes as $res)
        {            
            if($batch != $res->batch)
            {
                $batch = $res->batch;
                $lblbatch = $res->batch;
                $lblmatcode = $res->material_code;
                $lblmatdesc = $res->material_desc;
            }
            else
            {
//                $lblbatch = "";
                $lblmatcode = "";
                $lblmatdesc = "";
            }
            
            $data[] = array(
                'matCode'               => $lblmatcode,
                'matDes'                => $lblmatdesc,
                'batch'                 => $lblbatch,
                'satuan'                => $res->satuan,
                'saldo_awal'            => number_format($res->saldo_awal,3),								
                'lbsSaldo_awal'         => number_format($res->saldo_awal_lbs,3),
                'pemasukan'             => number_format($res->pemasukan,3),								
                'lbsPemasukan'          => number_format($res->pemasukan_lbs,3),
                'pengeluaran'           => number_format($res->pengeluaran,3),								
                'lbsPengeluaran'        => number_format($res->pengeluaran_lbs,3),
                'saldo_akhir'           => number_format((float)($res->saldo_awal+$res->pemasukan-$res->pengeluaran),3),								
                'lbsSaldo_akhir'        => number_format((float)($res->saldo_awal_lbs+$res->pemasukan_lbs-$res->pengeluaran_lbs),3),
                'gudang'                => $res->gudang,
                'mutasibahanbaku_id'    => $res->mutasibahanbaku_id,
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
        $data    = $this->Mod_mutasibahanbaku->select_master($sel,0,0);
//        echo $data->num_rows();
        $tabel = '';
        
        $tabel .= '<table border="1">';
        
        $tabel .= '<thead>';
        
        $tabel .= '<tr>      
                   <th>No.</th>
                   <th>Kode Barang</th>
                   <th>Nama Barang</th>
                   <th>Batch</th>
                   <th>Satuan</th>
                   <th>Saldo Awal</th>				   				   
                   <th>Saldo Awal (LBS)</th>
                   <th>Pemasukkan</th>				   				   
                   <th>Pemasukkan (LBS)</th>
                   <th>Pengeluaran</th>				   				   
                   <th>Pengeluaran (LBS)</th>
                   <th>Saldo Akhir</th>				   				   
                   <th>Saldo Akhir (LBS)</th>
                   <th>Gudang</th>
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
                $tabel .= '<td>'.$dataValue->material_code.'</td>';
                $tabel .= '<td>'.$dataValue->material_desc.'</td>';
                $tabel .= '<td>'.$dataValue->batch.'</td>';
                $tabel .= '<td>'.$dataValue->satuan.'</td>';
                $tabel .= '<td>'.$dataValue->saldo_awal.'</td>';								
                $tabel .= '<td>'.$dataValue->saldo_awal_lbs.'</td>';
                $tabel .= '<td>'.$dataValue->pemasukan.'</td>';								
                $tabel .= '<td>'.$dataValue->pemasukan_lbs.'</td>';
                $tabel .= '<td>'.$dataValue->pengeluaran.'</td>';								
                $tabel .= '<td>'.$dataValue->pengeluaran_lbs.'</td>';
                $tabel .= '<td>'.number_format((float)($dataValue->saldo_awal+$dataValue->pemasukan-$dataValue->pengeluaran),3).'</td>';								
                $tabel .= '<td>'.number_format((float)($dataValue->saldo_awal_lbs+$dataValue->pemasukan_lbs-$dataValue->pengeluaran_lbs),3).'</td>';
                $tabel .= '<td>'.$dataValue->gudang.'</td>';
                $tabel .= '</tr>';
                
                $no++;
            }
        }
        else 
        {
            $tabel .= '<tr><td colspan="11">Data Kosong</td></tr>';
        }
        
        $tabel .= '</tbody>';
        
        $tabel .= '</table>';
        
        $arrPdf['laporan']          = "Laporan Mutasi Bahan Baku";
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
        
        $data    = $this->Mod_mutasibahanbaku->select_master($sel,0,0);
        
        $head    = array('No.',
                   'Kode Barang',
                   'Nama Barang',
                   'Batch',
                   'Satuan',
                   'Saldo Awal',				   				   'Saldo Awal (LBS)',
                   'Pemasukkan',				   				   'Pemasukkan (LBS)',
                   'Pengeluaran',				   				   'Pengeluaran (LBS)',
                   'Saldo Akhir',				   				   'Saldo Akhir (LBS)',
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
                    $dataValue->material_code,
                    $dataValue->material_desc,
                    $dataValue->batch,
                    $dataValue->satuan,
                    $dataValue->saldo_awal,										
                    $dataValue->saldo_awal_lbs,
                    $dataValue->pemasukan,										
                    $dataValue->pemasukan_lbs,
                    $dataValue->pengeluaran,										
                    $dataValue->pengeluaran_lbs,
                    number_format((float)($dataValue->saldo_awal+$dataValue->pemasukan-$dataValue->pengeluaran),3),										
                    number_format((float)($dataValue->saldo_awal_lbs+$dataValue->pemasukan_lbs-$dataValue->pengeluaran_lbs),3),
                    $dataValue->gudang
                );
                $no++;
            }
        }
        
        $ret    = array(
            'laporan'           => "Laporan Mutasi Bahan Baku",
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
        $this->_viewloader("Laporan/MutasiBahanBaku", $data,"MutasiBahanBaku");
    }
    
     private function validationForm()
    {
        if($this->form_validation->run() == FALSE)
        {
            echo validation_errors();
        }
    }
}
