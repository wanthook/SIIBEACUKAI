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
class PemasukkanBahanBaku extends Secure_area
{
    function __construct() 
    {
        parent::__construct();
        
        $this->load->model('Mod_pemasukanbahanbaku');
    }
    
    function index()
    {
        $this->_viewloader("Laporan/PemasukkanBahanBaku", '',"PemasukkanBahanBaku");
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
            
            $sel .= "a.pemasukanbahanbaku_id = '$id'";
        }
        /*
         * order
         */
        $arrCol = array('jenis_dokumen',
                        'nomor',
                        'tanggal',
                        'nomor_seri',
                        'nomor_bukti',
                        'tanggal_bukti',
                        'material_code',
                        'material_desc',
                        'batch',
                        'satuan',
                        'jumlah',
                        'qty_pib',
                        'amount_pib',
                        'qty_dn',
                        'amount_dn',
                        'no_doc_dn',
                        'mataUang',
                        'nilaiBarang',
                        'gudang',
                        'penerima',
                        'negara',
                        'mark',
                        'pemasukanbahanbaku_id');
        
        $oBy    = $arrCol[$order[0]['column']];
        $oTy    = $order[0]['dir'];
        
        $ret = array();
                
        $data = array();      
        
        $q      = $this->Mod_pemasukanbahanbaku->select_master();
        
        $totData = $q->num_rows();        
        $q->free_result();
        
        $q      = $this->Mod_pemasukanbahanbaku->select_master($sel);
        
        $filData = $q->num_rows();
        $q->free_result();
        
        $dMa = $this->Mod_pemasukanbahanbaku->select_master($sel,
                                            $length,
                                            $start,
                                            array($oBy=>$oTy));
        
        
        $dMaRes = $dMa->result();
        
        foreach($dMaRes as $res)
        {
            $data[] = array(
                'jenisDokumen'          => $res->jenis_dokumen,
                'no'                    => $res->nomor,
                'tgl'                   => $this->fungsi->convertDate($res->tanggal,"d-m-Y"),
                'noSeri'                => $res->nomor_seri,
                'noBukti'               => $res->nomor_bukti,
                'tglBukti'              => $this->fungsi->convertDate($res->tanggal_bukti,"d-m-Y"),
                'matCode'               => $res->material_code,
                'matDes'                => $res->material_desc,
                'batch'                 => $res->batch,
                'satuan'                => $res->satuan,
                'jumlah'                => $res->jumlah,
                
                'pibqty'                => $res->qty_pib,
                'pibamount'             => $res->amount_pib,
                'dnqty'                 => $res->qty_dn,
                'dnamount'              => $res->amount_dn,
                'dndoc'                => $res->no_doc_dn,
                
                'mataUang'              => $res->mata_uang,
                'nilaiBarang'           => $res->nilai_barang,
                'gudang'                => $res->gudang,
                'penerima'              => $res->penerima,
                'negara'                => $res->negara,
                'mark'                  => $res->mark,
                'pemasukanbahanbaku_id' => $res->pemasukanbahanbaku_id,
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
        $data    = $this->Mod_pemasukanbahanbaku->select_master($sel,0,0,array('nomor_bukti','asc'));
//        echo $data->num_rows();
        $tabel = '';
        
        $tabel .= '<table border="1">';
        
        $tabel .= '<thead>';
        
        $tabel .= '<tr>      
                   <th rowspan="2">No.</th>
                   <th rowspan="2">Jenis Dokumen</th>
                   <th colspan="3">Dokumen Pabeanan</th>
                   <th colspan="2">Bukti Penerimaan</th>
                   <th rowspan="2">Kode Barang</th>
                   <th rowspan="2">Nama Barang</th>
                   <th rowspan="2">Batch</th>
                   <th rowspan="2">Satuan</th>
                   <th rowspan="2">Jumlah</th>
                   <th rowspan="2">Qty PIB</th>
                   <th rowspan="2">Amount PIB</th>
                   <th rowspan="2">Qty DN</th>
                   <th rowspan="2">Amount DN</th>
                   <th rowspan="2">No.Doc DN</th>
                   <th rowspan="2">Mata Uang</th>
                   <th rowspan="2">Nilai Barang</th>
                   <th rowspan="2">Gudang</th>
                   <th>Penerima</th>
                   <th>Negara</th>
                   <th rowspan="2">mark</th>
                   </tr>
                   <tr>
                   <th>Nomor</th>
                   <th>Tanggal</th>
                   <th>No. Seri Barang</th>
                   <th>Nomor</th>
                   <th>Tanggal</th>
                   <th>Subkontrak</th>
                   <th>Asal Barang</th>
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
                $tabel .= '<td>'.$dataValue->jenis_dokumen.'</td>';
                $tabel .= '<td>'.$dataValue->nomor.'</td>';
                $tabel .= '<td>'.$this->fungsi->convertDate($dataValue->tanggal,"d-m-Y").'</td>';
                $tabel .= '<td>'.$dataValue->nomor_seri.'</td>';
                $tabel .= '<td>'.$dataValue->nomor_bukti.'</td>';
                $tabel .= '<td>'. $this->fungsi->convertDate($dataValue->tanggal_bukti,"d-m-Y").'</td>';
                $tabel .= '<td>'.$dataValue->material_code.'</td>';
                $tabel .= '<td>'.$dataValue->material_desc.'</td>';
                $tabel .= '<td>'.$dataValue->batch.'</td>';
                $tabel .= '<td>'.$dataValue->satuan.'</td>';
                $tabel .= '<td>'.$dataValue->jumlah.'</td>';
                
                $tabel .= '<td>'.$dataValue->qty_pib.'</td>';
                $tabel .= '<td>'.$dataValue->amount_pib.'</td>';
                $tabel .= '<td>'.$dataValue->qty_dn.'</td>';
                $tabel .= '<td>'.$dataValue->amount_dn.'</td>';
                $tabel .= '<td>'.$dataValue->no_doc_dn.'</td>';
                
                $tabel .= '<td>'.$dataValue->mata_uang.'</td>';
                $tabel .= '<td>'.$dataValue->nilai_barang.'</td>';
                $tabel .= '<td>'.$dataValue->gudang.'</td>';
                $tabel .= '<td>'.$dataValue->penerima.'</td>';
                $tabel .= '<td>'.$dataValue->negara.'</td>';
                $tabel .= '<td>'.$dataValue->mark.'</td>';
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
        
        $arrPdf['laporan']          = "Laporan Pemasukkan Bahan Baku";
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
    
    function Add()
    {
        $noFaktur = $this->generateFaktur();
        
        $this->Form(array("faktur"=>$noFaktur));
    }
    
    private function Form($data="")
    {
        $this->_viewloader("Laporan/PemasukkanBahanBaku", $data,"PemasukkanBahanbaku");
    }
    
     private function validationForm()
    {
        if($this->form_validation->run() == FALSE)
        {
            echo validation_errors();
        }
    }
    
    function procSS()
    {
        $txtKodeBarang           = $this->input->post('txtKodeBarang');
        
        print_r($txtKodeBarang);
    }
    
//    function proc()
//    {
//        $id = $this->input->post('txtId');
//        
//        $tanggalExp = explode("-", $this->input->post('txtTanggalBpb'));
////        echo "kesini";
//        /*
//         * master variable
//         */
//        $val['noFaktur']        = $this->input->post('txtNoFaktur');
//        $val['noBukti']         = $this->input->post('txtNoBukti');
//        $val['noPo']            = $this->input->post('txtNoPo');
//        $val['sales']           = $this->input->post('txtSales');
//        $val['langgananId']     = $this->input->post('txtLangganan');
//        $val['kriteria']        = $this->input->post('txtKriteria');
//        $val['syaratPenjualan'] = $this->input->post('txtSyarat');
//        $val['tanggalBpb']      = $tanggalExp[2]."-".$tanggalExp[1]."-".$tanggalExp[0];
//        
//        /*
//         * detail variable
//         */
//        $det['barangId']        = $this->input->post('txtKodeBarang');
//        $det['unit']            = $this->input->post('txtUnit');
//        $det['hargaSatuan']     = $this->input->post('txtHargaSatuan');
//        $det['diskon']          = $this->input->post('txtDiskon');
//        $det['jumlah']          = $this->input->post('txtJumlah');
//        
////        $this->validationForm();
//        
//        $ret = array();
//        
//        $masterId = 0;
//        /*
//         * Check Save or Edit
//         * empty true = new
//         * empty false = edit
//         */
//        if(empty($id))
//        {
//            $mstr = $this->Mod_bpb->select_master(array(
//                'bpbMasterId'   => $id,
//                'a.hapus'       => '1'
//            ));
////            echo "kesini";
//            //jika tidak terdapat isinya nilainya < 0
//            if($mstr->num_rows()<1)
//            {
//                $det = $this->processDetail($det);
//                
//                $val['jumlah']  = $det['jumlah'];
//                
//                if(count($det['data'])>0)
//                {
//                    $masterId = $this->save($val);
//
//                    //ketika save berhasil
//                    if($masterId>0)
//                    {
//                        //cek detail ada isinya atau tidak
//                        $this->saveDetail($det['data'], $masterId);
//
//                        foreach($det['updateBarang'] as $ubK => $ubV)
//                        {
//                            $this->editBarang($ubV, $ubV['barangId']);
//                        }
//
//                        $this->saveLog($det['log']);   
//
//                        $ret = array('status'=>1,
//                         'msg'=>'Berhasil disimpan!!!');
//                    }
//                    else
//                    {
//                        $ret = array('status'=>0,
//                         'msg'=>'Data gagal disimpan!!!');
//                    }
//                }
//                else
//                {
//                    $ret = array('status'=>0,
//                         'msg'=>'Transaksi detail masih kosong!!!');
//                }
//            }  
//        }
//        echo json_encode($ret);
//    }
//    
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
