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
class MutasiHasilProduksi extends Secure_area
{
    function __construct() 
    {
        parent::__construct();
        
        $this->load->model('Mod_mutasihasilproduksi');
        $this->load->model('Mod_pemasukanhasilproduksi');
        $this->load->model('Mod_pengeluaranhasilproduksi');
    }
    
    function index()
    {
        $hsl = $this->proc_mutation();
        $this->_viewloader("Laporan/MutasiHasilProduksi", '',"MutasiHasilProduksi");
    }
    
    private function proc_mutation()
    {
        $this->Mod_pemasukanhasilproduksi->prepare_mutation();
        $this->Mod_pengeluaranhasilproduksi->prepare_mutation();
        
        $row = $this->Mod_mutasihasilproduksi->get_temp();
        
        $batch = "";
        $in     = 0;
        $inLbs  = 0;
        $out    = 0;
        $saldoAwal  = 0;
        $saldoAkhir = 0;
        
        $hsl    = array();
        
        if($row->num_rows()>0)
        {
            $res = $row->result();
//            print_r($res);
            foreach($res as $r)
            {
                $tanggal = $r->tgl_pengeluaran;
                
                if($batch!=$r->batch)
                {
                    $tanggal = $r->tgl_bukti;
                    $batch = $r->batch;
                    $in     = round((float)$r->jumlah,3);
                    $out    = 0;
                    $saldoAwal  = 0;
                    $saldoAkhir = $in;
                }
                else
                {
                    if($r->tipe == 'IN')
                    {
                        $tanggal = $r->tgl_bukti;
                        $in     = round((float)$r->jumlah,3);
                        $out    = 0;
                        $saldoAwal  = $saldoAkhir;
                        $saldoAkhir = $in+$saldoAwal;
                    }
                    else
                    {
                        $in     = 0;
                        $out    = round((float)$r->jumlah,3);
                        $saldoAwal  = $saldoAkhir;
                        $saldoAkhir = $saldoAwal-$out;
                    }
                }
                
                $hsl[]  = array('tanggal' => $tanggal,
                                'material_id' => $r->material_id,
                                'batch' => $batch,
                                'satuan' => $r->satuan,
                                'saldo_awal' => round((float)$saldoAwal,3),
                                'pemasukan' => $in,
                                'pengeluaran' =>$out,
                                'saldo_akhir' => round((float)$saldoAkhir,3),
                                'gudang' => $r->gudang);
                
                if($in > 0)
                {
                    $in = 0;
                }
            }
        }
        if(count($hsl)>0)
        {
            $this->Mod_mutasihasilproduksi->freeup_table();
            $this->Mod_mutasihasilproduksi->create_master_batch($hsl);
        }
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
            
            $sel .= "a.mutasihasilproduksi_id = '$id'";
        }
        /*
         * order
         */
        $arrCol = array('material_code',
                        'material_desc',
                        'batch',
                        'satuan',
                        'saldo_awal',
                        'pemasukan',
                        'pengeluaran',
                        'saldo_akhir',
                        'gudang',
                        'mark',
                        'mutasihasilproduksi_id');
        
        $oBy    = $arrCol[$order[0]['column']];
        $oTy    = $order[0]['dir'];
        
        $ret = array();
                
        $data = array();      
        
        $q      = $this->Mod_mutasihasilproduksi->select_master();
        
        $totData = $q->num_rows();        
        $q->free_result();
        
        $q      = $this->Mod_mutasihasilproduksi->select_master($sel);
        
        $filData = $q->num_rows();
        $q->free_result();
        
        $dMa = $this->Mod_mutasihasilproduksi->select_master($sel,
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
                'batch'                 => $res->batch,
                'satuan'                => $res->satuan,
                'saldo_awal'            => number_format($res->saldo_awal,3),
                'pemasukan'             => number_format($res->pemasukan,3),
                'pengeluaran'           => number_format($res->pengeluaran,3),
                'saldo_akhir'           => number_format((float)($res->saldo_awal+$res->pemasukan-$res->pengeluaran),3),
                'gudang'                => $res->gudang,
                'mark'                  => $res->mark,
                'mutasihasilproduksi_id'    => $res->mutasihasilproduksi_id,
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
        $data    = $this->Mod_mutasihasilproduksi->select_master($sel,0,0,array('nomor','asc'));
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
                   <th>Pemasukkan</th>
                   <th>Pengeluaran</th>
                   <th>Saldo Akhir</th>
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
                $tabel .= '<td>'.$dataValue->pemasukan.'</td>';
                $tabel .= '<td>'.$dataValue->pengeluaran.'</td>';
                $tabel .= '<td>'.$dataValue->saldo_akhir.'</td>';
                $tabel .= '<td>'.$dataValue->gudang.'</td>';
                $tabel .= '<td>'.$dataValue->penerima.'</td>';
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
        
        $arrPdf['laporan']          = "Laporan Mutasi Hasil Produksi";
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
        
        $data    = $this->Mod_mutasihasilproduksi->select_master($sel,0,0,array('nomor','asc'));
        
        $head    = array('No.',
                   'Kode Barang',
                   'Nama Barang',
                   'Batch',
                   'Satuan',
                   'Saldo Awal',
                   'Pemasukkan',
                   'Pengeluaran',
                   'Saldo Akhir',
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
                    $dataValue->pemasukan,
                    $dataValue->pengeluaran,
                    $dataValue->saldo_akhir,
                    $dataValue->gudang,
                    $dataValue->penerima
                );
                $no++;
            }
        }
        
        $ret    = array(
            'laporan'           => "Laporan Mutasi Hasil Produksi",
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
        $this->_viewloader("Laporan/MutasiHasilProduksi", $data,"MutasiHasilProduksi");
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
    
    function proc()
    {
        $id = $this->input->post('txtId');
        
        $tanggalExp = explode("-", $this->input->post('txtTanggalBpb'));
//        echo "kesini";
        /*
         * master variable
         */
        $val['noFaktur']        = $this->input->post('txtNoFaktur');
        $val['noBukti']         = $this->input->post('txtNoBukti');
        $val['noPo']            = $this->input->post('txtNoPo');
        $val['sales']           = $this->input->post('txtSales');
        $val['langgananId']     = $this->input->post('txtLangganan');
        $val['kriteria']        = $this->input->post('txtKriteria');
        $val['syaratPenjualan'] = $this->input->post('txtSyarat');
        $val['tanggalBpb']      = $tanggalExp[2]."-".$tanggalExp[1]."-".$tanggalExp[0];
        
        /*
         * detail variable
         */
        $det['barangId']        = $this->input->post('txtKodeBarang');
        $det['unit']            = $this->input->post('txtUnit');
        $det['hargaSatuan']     = $this->input->post('txtHargaSatuan');
        $det['diskon']          = $this->input->post('txtDiskon');
        $det['jumlah']          = $this->input->post('txtJumlah');
        
//        $this->validationForm();
        
        $ret = array();
        
        $masterId = 0;
        /*
         * Check Save or Edit
         * empty true = new
         * empty false = edit
         */
        if(empty($id))
        {
            $mstr = $this->Mod_bpb->select_master(array(
                'bpbMasterId'   => $id,
                'a.hapus'       => '1'
            ));
//            echo "kesini";
            //jika tidak terdapat isinya nilainya < 0
            if($mstr->num_rows()<1)
            {
                $det = $this->processDetail($det);
                
                $val['jumlah']  = $det['jumlah'];
                
                if(count($det['data'])>0)
                {
                    $masterId = $this->save($val);

                    //ketika save berhasil
                    if($masterId>0)
                    {
                        //cek detail ada isinya atau tidak
                        $this->saveDetail($det['data'], $masterId);

                        foreach($det['updateBarang'] as $ubK => $ubV)
                        {
                            $this->editBarang($ubV, $ubV['barangId']);
                        }

                        $this->saveLog($det['log']);   

                        $ret = array('status'=>1,
                         'msg'=>'Berhasil disimpan!!!');
                    }
                    else
                    {
                        $ret = array('status'=>0,
                         'msg'=>'Data gagal disimpan!!!');
                    }
                }
                else
                {
                    $ret = array('status'=>0,
                         'msg'=>'Transaksi detail masih kosong!!!');
                }
            }  
        }
        echo json_encode($ret);
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
