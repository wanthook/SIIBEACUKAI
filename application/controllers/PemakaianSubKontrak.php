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
class PemakaianSubKontrak extends Secure_area
{
    function __construct() 
    {
        parent::__construct();
        
        $this->load->model('Mod_pemakaiansubkontrak');
    }
    
    function index()
    {
        $this->_viewloader("Laporan/PemakaianSubKontrak", '',"PemakaianSubKontrak");
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
            
            $sel .= "a.pemakaiansubkontrak_id = '$id'";
        }
        /*
         * order
         */
        $arrCol = array('nomor_pemakaian','nomor',
                        'tanggal',
                        'material_code',
                        'material_desc',
                        'batch',
                        'satuan',
                        'disubkontrak',
                        'disubkontrak_lbs',
                        'penerima',
                        'pemakaiansubkontrak_id');
        
        $oBy    = $arrCol[$order[0]['column']];
        $oTy    = $order[0]['dir'];
        
        $ret = array();
                
        $data = array();      
        
        $q      = $this->Mod_pemakaiansubkontrak->select_master();
        
        $totData = $q->num_rows();        
        $q->free_result();
        
        $q      = $this->Mod_pemakaiansubkontrak->select_master($sel);
        
        $filData = $q->num_rows();
        $q->free_result();
        
        $dMa = $this->Mod_pemakaiansubkontrak->select_master($sel,
                                            $length,
                                            $start,
                                            array($oBy=>$oTy));
        
        
        $dMaRes = $dMa->result();
        
        foreach($dMaRes as $res)
        {
            $data[] = array(
                'nomor_pemakaian' => $res->nomor_pemakaian,
                'no'                    => $res->nomor,
                'tgl'                   => $this->fungsi->convertDate($res->tanggal,"d-m-Y"),
                'matCode'               => $res->material_code,
                'matDes'                => $res->material_desc,
                'batch'                 => $res->batch,
                'satuan'                => $res->satuan,
                'disubkontrak'          => $res->disubkontrak,
                'disubkontrakLbs'          => $res->disubkontrak_lbs,
                'penerima'              => $res->penerima,
                'mark'                  => $res->mark,
                'pemakaiansubkontrak_id' => $res->pemakaiansubkontrak_id,
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
        $data    = $this->Mod_pemakaiansubkontrak->select_master($sel,0,0,array('nomor','asc'));
//        echo $data->num_rows();
        $tabel = '';
        
        $tabel .= '<table border="1">';
        
        $tabel .= '<thead>';
        
        $tabel .= '<tr>      
                   <th rowspan="2">No.</th>
				   <th rowspan="2">No. Pabean</th>
                   <th colspan="2">Bukti Pengeluaran Barang</th>
                   <th rowspan="2">Kode Barang</th>
                   <th rowspan="2">Nama Barang</th>
                   <th rowspan="2">Batch</th>
                   <th rowspan="2">Satuan</th>
                   <th>Jumlah</th>
                   <th>Jumlah</th>
                   <th>Penerima</th>
                   <th rowspan="2">mark</th>
                   </tr>
                   <tr>
                   <th>Nomor</th>
                   <th>Tanggal</th>
                   <th>Disubkontrakkan</th>
                   <th>Disubkontrakkan (LBS)</th>
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
				$tabel .= '<td>'.$dataValue->nomor_pemakaian.'</td>';
                $tabel .= '<td>'.$dataValue->nomor.'</td>';
                $tabel .= '<td>'.$this->fungsi->convertDate($dataValue->tanggal,"d-m-Y").'</td>';
                $tabel .= '<td>'.$dataValue->material_code.'</td>';
                $tabel .= '<td>'.$dataValue->material_desc.'</td>';
                $tabel .= '<td>'.$dataValue->batch.'</td>';
                $tabel .= '<td>'.$dataValue->satuan.'</td>';
                $tabel .= '<td>'.$dataValue->disubkontrak.'</td>';
                $tabel .= '<td>'.$dataValue->disubkontrak_lbs.'</td>';
                $tabel .= '<td>'.$dataValue->penerima.'</td>';
                $tabel .= '<td>'.$dataValue->mark.'</td>';
                $tabel .= '</tr>';
                
                $no++;
            }
        }
        else 
        {
            $tabel .= '<tr><td colspan="9">Data Kosong</td></tr>';
        }
        
        $tabel .= '</tbody>';
        
        $tabel .= '</table>';
        
        $arrPdf['laporan']          = "Laporan Pemakaian Barang Dalam Proses Dalam Rangka Kegiatan Subkontrak";
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
        
        $data    = $this->Mod_pemakaiansubkontrak->select_master($sel,0,0,array('nomor','asc'));
        
        $head    = array('No.',
		'No. Pabean',
                   'Nomor Bukti Pengeluaran Barang',
                   'Tanggal Bukti Pengeluaran Barang',
                   'Kode Barang',
                   'Nama Barang',
                   'Batch',
                   'Satuan',
                   'Jumlah Disubkontrakkan',
                    'Jumlah Disubkontrakkan (LBS)',
                   'Penerima Subkontrak',
                   'mark');
        
        $table  = array();
        
        if($data->num_rows()>0)
        {
            $no = 1;
            
            $res    = $data->result();
            
            foreach ($res as $dataKey => $dataValue)
            {
                
                $table[]    = array(
                    $no,
					$dataValue->nomor_pemakaian,
                    $dataValue->nomor,
                    $this->fungsi->convertDate($dataValue->tanggal,"d-m-Y"),
                    $dataValue->material_code,
                    $dataValue->material_desc,
                    $dataValue->batch,
                    $dataValue->satuan,
                    $dataValue->disubkontrak,
                    $dataValue->disubkontrak_lbs,
                    $dataValue->penerima,
                    $dataValue->mark
                );
                $no++;
            }
        }
        
        $ret    = array(
            'laporan'           => "Laporan Pemakaian Barang Dalam Proses Dalam Rangka Kegiatan Subkontrak",
            'tanggal_awal'      => $sD,
            'tanggal_akhir'     => $eD,
            'head'              => $head,
            'table'             => $table
        );
        
        $this->load->helper("xls_helper");
        $this->load->view("Laporan/downloadXls",$ret);
        
    }
    
    function Add()
    {
        $noFaktur = $this->generateFaktur();
        
        $this->Form(array("faktur"=>$noFaktur));
    }
    
    private function Form($data="")
    {
        $this->_viewloader("Laporan/PemakaianSubKontrak", $data,"PemakaianSubKontrak");
    }
    
     private function validationForm()
    {
        if($this->form_validation->run() == FALSE)
        {
            echo validation_errors();
        }
    }
    
    function procRemove()
    {
        $ret = array();
        
        $var['id'] = $this->input->post('id');
        
        if(!empty($var['id']))
        {
            $this->delete($var);
            
            $ret = array('status'=>1,
                         'msg'=>'Berhasil dihapus!!!');
        }
        else
        {
            $ret = array('status'=>0,
                         'msg'=>'Gagal dihapus!!!');
        }
        
        echo json_encode($ret);
    }
    
    private function processDetail($var)
    {
//        $arr = array();
        
        $data = array();
        
        $jumlah = 0;
        
        $logTransaksi = array();
        
        $updateBarang = array();
        
        if(isset($var['barangId']))
        {
        
            if(is_array($var['barangId']) && count($var['barangId'])>0)
            {

                foreach($var['barangId'] as $key => $val)
                {
                    if(!empty($val))
                    {
                        $data[] = array('barangId'   => $val,
                                        'unit'       => $var['unit'][$key],
                                        'hargaSatuan'=> $var['hargaSatuan'][$key],
                                        'diskon'     => $var['diskon'][$key],
                                        'jumlah'     => $var['jumlah'][$key]);

                        $barangData = $this->Mod_barang->select_id($val);

                        $logTransaksi[] = array(
                                                'tanggal'   => date('Y-m-d H:i:s'),
                                                'barangId'  => $val,
                                                'stokAwal'  => $barangData->barangUnit,
                                                'stokAkhir' => ($barangData->barangUnit-$var['unit'][$key])
                                                );

                        $updateBarang[] = array(
                                                'barangId'=>$val,
                                                'barangUnit'=>($barangData->barangUnit-$var['unit'][$key])
                                                );

                        $jumlah += $var['jumlah'][$key];
                    }
                }
            }
        }
        
        return array('jumlah'=>$jumlah,'data'=>$data,'updateBarang'=>$updateBarang,'log'=>$logTransaksi);
    }
    
    private function save($obj)
    {
        $ret = 0;
        
        if(!empty($obj))
        {            
            $obj['created_by']            = $this->session->userdata('user_id');
            $obj['modified_by']           = $this->session->userdata('user_id');
            $obj['created_at']            = date("Y-m-d H:i:s");
            $obj['modified_at']           = date("Y-m-d H:i:s");
            
            $ret = $this->Mod_bpb->create_master($obj);
            
            
        }
        
        return $ret;
    }
    
    private function saveDetail($obj,$master)
    {
        $ret    = 0;
        $o      = array();
        
        if(is_array($obj) && count($obj)>0 && !empty($master))
        {
            foreach($obj as $k => $v)
            {
                $v['bpbMasterId']           = $master;
                $v['created_by']            = $this->session->userdata('user_id');
                $v['modified_by']           = $this->session->userdata('user_id');
                $v['created_at']            = date("Y-m-d H:i:s");
                $v['modified_at']           = date("Y-m-d H:i:s");
                
                $o[] = $v;
            }
            
            $ret = $this->Mod_bpb->create_detail($o);
        }
        
        return $ret;
    }
    
    private function saveLog($obj)
    {
        $ret    = 0;
        $o      = array();
        
        if(is_array($obj) && count($obj)>0)
        {
            foreach($obj as $k => $v)
            {
                //$v['bpbMasterId']           = $master;
                $v['created_by']            = $this->session->userdata('user_id');
                $v['created_at']            = date("Y-m-d H:i:s");
                
                $o[] = $v;
            }
            
            $ret = $this->Mod_logtransaksi->create_master($o);
        }
        
        return $ret;
    }
    
    private function edit($obj,$id)
    {
        $ret = 0;
        
        if(!empty($obj))
        {            
            $obj['modified_by']           = $this->session->userdata('user_id');
            $obj['modified_at']           = date("Y-m-d H:i:s");
            
            $this->Mod_bpb->update_master($obj,array('field'=>'langgananId','value'=>$id));
            
            
        }
    }
    
     private function editBarang($obj,$id)
    {
        $ret = 0;
        
        if(!empty($obj))
        {            
            $obj['modified_by']           = $this->session->userdata('user_id');
            $obj['modified_at']           = date("Y-m-d H:i:s");
            
            $this->Mod_barang->update_master($obj,array('field'=>'barangId','value'=>$id));
            
            
        }
    }
    
    private function delete($obj)
    {
        $ret = 0;
        
        if(!empty($obj))
        {            
            $master = array('hapus'               => '0',
                            'modified_by'           => $this->session->userdata('user_id'),
                            'modified_at'           => date("Y-m-d H:i:s")
                    );
            
            $this->Mod_bpb->update_master($master,array('field'=>'langgananId','value'=>$obj['id']));
            
            
        }
    }
    
    private function generateFaktur()
    {
        $q      = $this->Mod_bpb->select_master("",0,0,array(),array(),"max(a.bpbMasterId) mx");
        
        $hasil  = $q->result();
        $val    = $hasil[0]->mx+1;
        return "7".sprintf("%07d",$val);
    }
}
