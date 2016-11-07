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
class UploadData extends Secure_area
{
    function __construct() 
    {
        parent::__construct();
        
        $this->load->model('Mod_mutasibahanbaku');
        $this->load->model('Mod_mutasihasilproduksi');
        $this->load->model('Mod_pemakaianbahanbaku');
        $this->load->model('Mod_pemakaiansubkontrak');
        $this->load->model('Mod_pemasukanbahanbaku');
        $this->load->model('Mod_pemasukanhasilproduksi');
        $this->load->model('Mod_pengeluaranhasilproduksi');
        $this->load->model('Mod_penyelesaianwaste');
        
        $this->load->model('Mod_materialmaster');
        $this->load->model('Mod_logupload');
    }
    
    function index()
    {
        
        $this->_viewloader("UploadData/FormUpload", '',"UploadData");
    }
    
    
    private function proc_mutation()
    {
        $this->Mod_pemasukanbahanbaku->prepare_mutation();
        $this->Mod_pemakaianbahanbaku->prepare_mutation();
        $this->Mod_pemakaiansubkontrak->prepare_mutation();
        
        $row = $this->Mod_mutasibahanbaku->get_temp();
        
        $batch = "";
        $in     = 0;
        $inLbs  = 0;
        $out    = 0;
        $outLbs = 0;
        $saldoAwal  = 0;
        $saldoAwalLbs  = 0;
        $saldoAkhir = 0;
        $saldoAkhirLbs = 0;
        
        $hsl    = array();
        
        if($row->num_rows()>0)
        {
            $res = $row->result();
//            print_r($res);
            foreach($res as $r)
            {
                if($batch!=$r->batch)
                {
                    $batch = $r->batch;
                    $in     = round((float)$r->jml_in,3);
                    $inLbs  = round((float)$r->jmllbs_in,3);
                    $saldoAwal  = 0;
                    $saldoAwalLbs  = 0;
                    $saldoAkhir = 0;
                    $saldoAkhirLbs = 0;
                }
                
                $saldoAwal = round((float)$saldoAkhir,3);
                $saldoAwalLbs = round((float)$saldoAkhirLbs,3);
                $out    = round((float)$r->jml_out,3);
                $outLbs = round((float)$r->jmlabs_out,3);
                if($saldoAwal == 0)
                {
                    $saldoAkhir = $in-$out;
                    $saldoAkhirLbs = $inLbs-$outLbs;
                }
                else
                {
                    $saldoAkhir = $saldoAwal-$out;
                    $saldoAkhirLbs = $saldoAwalLbs-$outLbs;
                }
                
                $hsl[]  = array('tanggal' => $r->tgl_pakai,
                                'material_id' => $r->material_id,
                                'batch' => $batch,
                                'satuan' => $r->satuan,
                                'saldo_awal' => $saldoAwal,
                                'saldo_awal_lbs' => $saldoAwalLbs,
                                'pemasukan' => $in,
                                'pemasukan_lbs' => $inLbs,
                                'pengeluaran' =>$out,
                                'pengeluaran_lbs' => $outLbs,
                                'saldo_akhir' => round((float)$saldoAkhir,3),
                                'saldo_akhir_lbs' => round((float)$saldoAkhirLbs,3),
                                'gudang' => $r->gudang);
                
                if($in > 0)
                {
                    $in = 0;
                }
                if($inLbs > 0)
                {
                    $inLbs = 0;
                }
            }
        }
        if(count($hsl)>0)
        {
            $this->Mod_mutasibahanbaku->freeup_table();
            $this->Mod_mutasibahanbaku->create_master_batch($hsl);
        }
    }
    
    function table()
    {
        
        $sel['a.hapus'] = 1;
        
        $id = $this->input->post_get('id');
        
        if(!empty($id))
        {
            $sel['a.upload_id'] = $id;
        }
        
        
        $ret = array();
                
        $data = array();        
        
        $dMa = $this->Mod_logupload->select_master($sel,
                                            0,
                                            0,
                                            array('a.created_at','desc'));
        
        
        $dMaRes = $dMa->result();
        
        foreach($dMaRes as $res)
        {
            $data[] = array(
                'kode'              => $res->upload_code,
                'filename'          => $res->upload_filename,                
                'size'              => $res->upload_size,
                'row'               => $res->upload_row,
                'ins'               => $res->upload_insert,
                'upd'               => $res->upload_update,
                'mut'               => $res->upload_mutation,
                'id'                => $res->upload_id,
                'createdAt'         => $this->fungsi->convertDate($res->created_at,"d-m-Y H:i:s")
            );
        }
        
        $ret['data'] = $data;
        
        echo json_encode($ret);
    }
    
    function doUpload()
    {
        $data   = array();
        
        $data   = $this->prepareFile();
        
        $ret    = array();
        
        if($data['status']==1)
        {
            $logData    = array("upload_filename"  => $data['original_filename'],
                             "upload_code"         => $data['file'],
                             "upload_size"          => $data['size'],
                             "created_by"          => $this->session->userdata('user_id'),
                             "modified_by"         => $this->session->userdata('user_id'),
                             "created_at"          => date("Y-m-d H:i:s"),
                             "modified_at"         => date("Y-m-d H:i:s"));
            
            $fileLogId  = $this->Mod_logupload->create_master($logData);
            
            $cnt        = $this->saveDB($data,$fileLogId);
            
            $updData    = array("upload_row"          => $cnt['upload_row'],
                                "upload_insert"       => $cnt['upload_insert'],
                                "upload_update"       => $cnt['upload_update'],
                                "upload_mutation"     => $cnt['upload_mutation'],                                 
                                "modified_by"         => $this->session->userdata('user_id'),
                                "modified_at"         => date("Y-m-d H:i:s"));
            
            
            $this->Mod_logupload->update_master($updData,array('field'=>'upload_id','value'=>$fileLogId));
            
            /*
             * proc mutation
             */
//            $this->proc_mutation();
            
            $ret    = array("status" => 1, "msg" => "Data berhasil diupload.");
        }
        else
        {
            $ret    = array("status" => 0, "msg" => $data['error']);
        }
        
        echo json_encode($ret);
    }
    
    private function prepareFile()
    {
        $contentFile = file_get_contents($_FILES['txtFile']['tmp_name']);
        
        $crc= crc32($contentFile);
        
        $fileName = sprintf("%x", $crc);
        
        $config                     = array();
        $config['upload_path']      = './uploads/';
        $config['max_size']         = '102400';
        $config['overwrite']        = 'true';
        $config['allowed_types']    = '*';
        $config['file_name']        = $fileName.".xls";

        $this->load->library('upload', $config);
        
        if($this->upload->do_upload('txtFile'))
        {
        
            $arr = array("status"=>1,
                         "file"=>$fileName,
                         "original_filename"=>$_FILES['txtFile']['name'],
                         "size"=>$_FILES['txtFile']['size']);
        }
        else
        {
            $arr = array("status"=>0,
                         "error"=>$this->upload->display_errors());
        }
                
        return $arr;
    }
    
    private function saveDB($data,$uploadId)
    {
        $ret =null;
        
        if(is_array($data))
        {
            $dataParsing = $this->parsingXls($data,$uploadId);
        }
        
        return $dataParsing;
    }
    
    private function parsingXls($data,$uploadId)
    {
        $total_data     = 0;
        $total_insert   = 0;
        $total_update   = 0;
        $total_mutasi   = 0;
        
        if(!empty($data['file']))
        {
            $this->load->library('excel');
            
            $objR = PHPExcel_IOFactory::createReader('Excel5');
            
            $objPHPExcel = $objR->load('./uploads/'.$data['file'].".xls");
            unset($objR);
            foreach ($objPHPExcel->getWorksheetIterator() as $sheet) 
            {
                $sheetData = $sheet->toArray(null,true,true,true);
                
                $judul = strtoupper(trim($sheetData[1]["B"]));
                
                if($judul=="LAPORAN PEMASUKAN BAHAN BAKU")
                {
                    $arrProc        = array();                    
                    $arrProc        = $this->insert_pemasukan_bahan_baku($sheetData,$uploadId);
                    
                    $total_data     += $arrProc['t_sheet'];
                    $total_insert   += $arrProc['t_insert'];
                    $total_update   += $arrProc['t_update'];
                }
                else if($judul=="LAPORAN PEMAKAIAN BAHAN BAKU")
                {
                    $arrProc        = array();                    
                    $arrProc        = $this->insert_pemakaian_bahan_baku($sheetData,$uploadId);
                    
                    $total_data     += $arrProc['t_sheet'];
                    $total_insert   += $arrProc['t_insert'];
                    $total_update   += $arrProc['t_update'];
                }
                else if($judul=="LAPORAN PEMAKAIAN BARANG DALAM PROSES DALAM RANGKA KEGIATAN SUBKONTRAK")
                {
                    $arrProc        = array();                    
                    $arrProc        = $this->insert_pemakaian_subkontrak($sheetData,$uploadId);
                    
                    $total_data     += $arrProc['t_sheet'];
                    $total_insert   += $arrProc['t_insert'];
                    $total_update   += $arrProc['t_update'];
                }
                else if($judul=="LAPORAN PEMASUKAN HASIL PRODUKSI")
                {
                    $arrProc        = array();                    
                    $arrProc        = $this->insert_pemasukan_hasil_produksi($sheetData,$uploadId);
                    
                    $total_data     += $arrProc['t_sheet'];
                    $total_insert   += $arrProc['t_insert'];
                    $total_update   += $arrProc['t_update'];
                }
                else if($judul=="LAPORAN PENGELUARAN HASIL PRODUKSI")
                {
                    $arrProc        = array();                    
                    $arrProc        = $this->insert_pengeluaran_hasil_produksi($sheetData,$uploadId);
                    
                    $total_data     += $arrProc['t_sheet'];
                    $total_insert   += $arrProc['t_insert'];
                    $total_update   += $arrProc['t_update'];
                }
//                else if($judul=="LAPORAN MUTASI BAHAN BAKU")
//                {
//                    $tot            = $this->insert_mutasi_bahan_baku($sheetData,$uploadId);
//                    
//                    $total_data     += $tot;
//                    $total_mutasi   += $tot;
//                }
//                else if($judul=="LAPORAN MUTASI HASIL PRODUKSI")
//                {
//                    $tot            = $this->insert_mutasi_hasil_produksi($sheetData,$uploadId);
//                    
//                    $total_data     += $tot;
//                    $total_mutasi   += $tot;
//                }
                else if($judul=="LAPORAN PENYELESAIAN WASTE/SCRAP")
                {
                    $arrProc        = array();                    
                    $arrProc        = $this->insert_penyelesaian_waste($sheetData,$uploadId);
                    
                    $total_data     += $arrProc['t_sheet'];
                    $total_insert   += $arrProc['t_insert'];
                    $total_update   += $arrProc['t_update'];
                }
				
				unset($sheetData);
            }
            $objPHPExcel->disconnectWorksheets();
			unset($objPHPExcel);
            return array('upload_row' => $total_data, 'upload_insert'=> $total_insert, 'upload_update' => $total_update,'upload_mutation' => $total_mutasi);
        }
    }
    
    private function getMaterial($material_code,$material_desc)
    {
        $row = $this->Mod_materialmaster->select_master(array('material_code'=>$material_code));
        
        $id = 0;
        
        if($row->num_rows()>0)
        {
            $res = $row->result();
            
            foreach($res as $k => $v)
            {
                $id = $v->material_id;
            }
        }
        else
        {
            $obj['material_code']       = $material_code;
            $obj['material_desc']       = $material_desc;
            $obj['created_by']          = $this->session->userdata('user_id');
            $obj['modified_by']         = $this->session->userdata('user_id');
            $obj['created_at']          = date("Y-m-d H:i:s");
            $obj['modified_at']         = date("Y-m-d H:i:s");
            
            $id = $this->Mod_materialmaster->create_master($obj);
        }
        
        return $id;
    }
    
    private function crc($param)
    {
        $crc = crc32($param);
        return sprintf("%x", $crc);
    }
    
    private function sapDate($date)
    {
        if(empty($date))
        {
            return "";
        }
        
        $date = str_replace("'", "", $date);
        
        $date = explode(".", $date);
        
        return $date[2]."-".$date[1]."-".$date[0];
    }
    
    function procRemove()
    {
        if($this->session->userdata('type')!='ADMIN')
        {
            return json_encode(array('msg'=>'Anda tidak diperkenankan untuk menghapus.','status'=>'0'));
        }
        
        $id     = $this->input->post_get('id');
        
        $ret = array();
        
        if(!empty($id))
        {
            $where  = "upload_id='$id'";
            
            $this->Mod_mutasibahanbaku->deletehard_master($where);
            $this->Mod_mutasihasilproduksi->deletehard_master($where);
            $this->Mod_pemakaianbahanbaku->deletehard_master($where);
            $this->Mod_pemakaiansubkontrak->deletehard_master($where);
            $this->Mod_pemasukanbahanbaku->deletehard_master($where);
            $this->Mod_pemasukanhasilproduksi->deletehard_master($where);
            $this->Mod_pengeluaranhasilproduksi->deletehard_master($where);
            $this->Mod_penyelesaianwaste->deletehard_master($where);
            
            $this->Mod_logupload->update_master(array('hapus'=>'0'),array('field'=>'upload_id','value'=>$id));
            
            $ret    = array('msg'=>'File dan data terkait sudah dihapus.','status'=>'1');
        }
        else
        {
            $ret    = array('msg'=>'File dan data terkait gagal dihapus.','status'=>'0');
        }
        
        echo json_encode($ret);
    }
    
    private function insert_pemasukan_bahan_baku($arr,$uploadId)
    {
        $i       = 1;
        
        $ret     = array();
        
        $arrData = array();
        
        $t_insert   = 0;
        $t_update   = 0;
        $t_sheet    = 0;
        
        foreach($arr as $arrS)
        {
            if($i>=6)
            {
                //kosong
                if(empty($arrS["C"]))
                {
                    break;
                }
                
                //count row in sheet
                $t_sheet                        += 1;
                
                $arrD                           = array();
                
                $arrD['jenis_dokumen']          = trim($arrS['C']);
                $arrD['nomor']                  = trim($arrS['D']);
                $arrD['tanggal']                = $this->sapDate(trim($arrS['E']));
                $arrD['nomor_seri']             = trim($arrS['F']);
                $arrD['nomor_bukti']            = trim($arrS['G']);
                $arrD['tanggal_bukti']          = $this->sapDate(trim($arrS['H']));
                $arrD['material_id']            = $this->getMaterial(trim($arrS['I']),trim($arrS['J']));
                $arrD['batch']                  = trim($arrS['K']);
                $arrD['satuan']                 = trim($arrS['M']);
                $arrD['jumlah']                 = $this->fungsi->toFloat(trim($arrS['L']));								
		$arrD['jumlah_lbs']             = $this->fungsi->toFloat(trim($arrS['N']));
                
                $arrD['qty_pib']                = $this->fungsi->toFloat(trim($arrS['P']));
                $arrD['amount_pib']             = $this->fungsi->toFloat(trim($arrS['Q']));
                $arrD['qty_dn']                 = $this->fungsi->toFloat(trim($arrS['R']));
                $arrD['amount_dn']              = $this->fungsi->toFloat(trim($arrS['S']));
                
                $arrD['mata_uang']              = trim($arrS['U']);
                $arrD['nilai_barang']           = $this->fungsi->toFloat(trim($arrS['V']));
                $arrD['gudang']                 = trim($arrS['W']);
                $arrD['penerima']               = trim($arrS['X']);    
                
                $crc32                          = sprintf("%x", crc32(implode('', $arrD)));
                
                $rowCrc                         = $this->Mod_pemasukanbahanbaku->select_master(array('crc'=>$crc32));
                
                $arrD['no_doc_dn']              = trim($arrS['T']);
                $arrD['negara']                 = trim($arrS['Y']);
                
                if($rowCrc->num_rows()>0)
                {
                    $res = $rowCrc->row();
					
                    if($arrD['negara']==$res->negara && $arrD['no_doc_dn']==$res->no_doc_dn)
                    {
                        continue;
                    }
                    else
                    {
                        $arrUpd     = array();
                        
                        if($arrD['negara']!=$res->negara)
                        {
                            $arrUpd['negara']     = $arrD['negara'];                        
                        }
                        
                        if($arrD['no_doc_dn']!=$res->no_doc_dn)
                        {
                            $arrUpd['no_doc_dn']     = $arrD['no_doc_dn'];
                        }
                        
                        $arrUpd ['upload_id']    = $uploadId;
                        $arrUpd ['modified_by']    = $this->session->userdata('user_id');
                        $arrUpd ['modified_at']    = date("Y-m-d H:i:s");
                        
                        $this->Mod_pemasukanbahanbaku->update_master($arrUpd,array('field'=>'pemasukanbahanbaku_id','value'=>$res->pemasukanbahanbaku_id));
                        
                        $t_update   += 1;
                        
                        continue;
                    }
                }
                
                $arrD['crc']                 = $crc32;
                
                $arrD['upload_id']           = $uploadId;
                
                $arrD['created_by']          = $this->session->userdata('user_id');
                $arrD['modified_by']         = $this->session->userdata('user_id');
                $arrD['created_at']          = date("Y-m-d H:i:s");
                $arrD['modified_at']         = date("Y-m-d H:i:s");
                
                $arrData[]                   = $arrD;
                
            }
            
            $i++;
        }
        
        if(count($arrData)>0)
        {
            $t_insert   = count($arrData);
            $this->Mod_pemasukanbahanbaku->create_master_batch($arrData);
        }
        
        return array('t_sheet'=>$t_sheet,'t_insert'=>$t_insert,'t_update'=>$t_update);
    }
    
    private function insert_pemakaian_bahan_baku($arr,$uploadId)
    {
        $i       = 1;
        
        $ret     = array();
        
        $arrData = array();
        
        $t_insert   = 0;
        $t_update   = 0;
        $t_sheet    = 0;
        
        foreach($arr as $arrS)
        {
            if($i>=6)
            {
                //kosong
                if(empty($arrS["C"]))
                {
                    break;
                }
                
                $t_sheet                        += 1;
                
                $arrD                           = array();
                
                $arrD['nomor']                  = trim($arrS['C']);
                $arrD['tanggal']                = $this->sapDate(trim($arrS['D']));
                $arrD['material_id']            = $this->getMaterial(trim($arrS['E']),trim($arrS['F']));
                $arrD['batch']                  = trim($arrS['G']);
                $arrD['satuan']                 = trim($arrS['H']);
                $arrD['digunakan']              = $this->fungsi->toFloat(trim($arrS['I']));	
                $arrD['disubkontrak']           = $this->fungsi->toFloat(trim($arrS['J']));				
				$arrD['digunakan_lbs']          = $this->fungsi->toFloat(trim($arrS['K']));
                $arrD['penerima']               = trim($arrS['L']);
                
                $crc32                          = sprintf("%x", crc32(implode('', $arrD)));
                
                $rowCrc                         = $this->Mod_pemakaianbahanbaku->select_master(array('a.crc'=>$crc32));
                
                if($rowCrc->num_rows()>0)
                {
                    $res = $rowCrc->row();
                    $arrUpd = array(
                            'upload_id'     => $uploadId,
                            'modified_by'   => $this->session->userdata('user_id'),
                            'modified_at'   => date("Y-m-d H:i:s")
                    );

                    $this->Mod_pemakaianbahanbaku->update_master($arrUpd,array('field'=>'pemakaianbahanbaku_id','value'=>$res->pemakaianbahanbaku_id));

                    $t_update   += 1;

                    continue;
                }
                
                $arrD['crc']                    = $crc32;
                
                $arrD['upload_id']              = $uploadId;
                
                $arrD['created_by']          = $this->session->userdata('user_id');
                $arrD['modified_by']         = $this->session->userdata('user_id');
                $arrD['created_at']          = date("Y-m-d H:i:s");
                $arrD['modified_at']         = date("Y-m-d H:i:s");
                
                $arrData[]                   = $arrD;
                
            }
            
            $i++;
        }
        
        if(count($arrData)>0)
        {
            $t_insert   = count($arrData);
            
            $this->Mod_pemakaianbahanbaku->create_master_batch($arrData);
        }
        
        return array('t_sheet'=>$t_sheet,'t_insert'=>$t_insert,'t_update'=>$t_update);
    }
    
    private function insert_pemakaian_subkontrak($arr,$uploadId)
    {
        $i       = 1;
        
        $ret     = array();
        
        $arrData = array();
        
        $t_insert   = 0;
        $t_update   = 0;
        $t_sheet    = 0;
        
        foreach($arr as $arrS)
        {
            if($i>=6)
            {
                //kosong
                if(empty($arrS["C"]))
                {
                    break;
                }
                
                $t_sheet                        += 1;
                
                $arrD                           = array();
                
                $arrD['nomor']                  = trim($arrS['C']);
                $arrD['tanggal']                = $this->sapDate(trim($arrS['D']));
                $arrD['material_id']            = $this->getMaterial(trim($arrS['E']),trim($arrS['F']));
                $arrD['batch']                  = trim($arrS['G']);
                $arrD['satuan']                 = trim($arrS['H']);
                $arrD['disubkontrak']           = $this->fungsi->toFloat(trim($arrS['I']));
                $arrD['disubkontrak_lbs']       = $this->fungsi->toFloat(trim($arrS['J']));
                $arrD['penerima']               = trim($arrS['K']);
                
                $crc32                          = sprintf("%x", crc32(implode('', $arrD)));
                
                $rowCrc                         = $this->Mod_pemakaiansubkontrak->select_master(array('a.crc'=>$crc32));
                
                if($rowCrc->num_rows()>0)
                {
		    $res = $rowCrc->row();
                    $arrUpd = array(
                            'upload_id'     => $uploadId,
                            'modified_by'   => $this->session->userdata('user_id'),
                            'modified_at'   => date("Y-m-d H:i:s")
                    );

                    $this->Mod_pemakaiansubkontrak->update_master($arrUpd,array('field'=>'pemakaiansubkontrak_id','value'=>$res->pemakaiansubkontrak_id));
                    $t_update   += 1;
                    continue;
                }
                
                $arrD['crc']                    = $crc32;
                
                $arrD['upload_id']              = $uploadId;
                
                $arrD['created_by']          = $this->session->userdata('user_id');
                $arrD['modified_by']         = $this->session->userdata('user_id');
                $arrD['created_at']          = date("Y-m-d H:i:s");
                $arrD['modified_at']         = date("Y-m-d H:i:s");
                
                $arrData[]                   = $arrD;
                
            }
            
            $i++;
        }
        
        if(count($arrData)>0)
        {
            $t_insert = count($arrData);
            
            $this->Mod_pemakaiansubkontrak->create_master_batch($arrData);
        }
        
        return array('t_sheet'=>$t_sheet,'t_insert'=>$t_insert,'t_update'=>$t_update);
    }
    
    private function insert_pemasukan_hasil_produksi($arr,$uploadId)
    {
        $i       = 1;
        
        $ret     = array();
        
        $arrData = array();
        
        $t_insert   = 0;
        $t_update   = 0;
        $t_sheet    = 0;
        
        foreach($arr as $arrS)
        {
            if($i>=6)
            {
                //kosong
                if(empty($arrS["C"]))
                {
                    break;
                }
                
                $t_sheet                        += 1;
                
                $arrD                           = array();
                
                $arrD['nomorpib']               = trim($arrS['L']);
                $arrD['nomor']                  = trim($arrS['C']);
                $arrD['tanggal']                = $this->sapDate(trim($arrS['D']));
                $arrD['material_id']            = $this->getMaterial(trim($arrS['E']),trim($arrS['F']));
                $arrD['batch']                  = trim($arrS['G']);
                $arrD['satuan']                 = trim($arrS['H']);
                $arrD['digunakan']              = $this->fungsi->toFloat(trim($arrS['I']));
                $arrD['disubkontrak']           = $this->fungsi->toFloat(trim($arrS['J']));
                $arrD['gudang']                 = trim($arrS['K']);
                
                $crc32                          = sprintf("%x", crc32(implode('', $arrD)));
                
                $rowCrc                         = $this->Mod_pemasukanhasilproduksi->select_master(array('crc'=>$crc32));
                
                if($rowCrc->num_rows()>0)
                {
                    $res = $rowCrc->row();
                    
                    $arrUpd = array(
                            'upload_id'     => $uploadId,
                            'modified_by'   => $this->session->userdata('user_id'),
                            'modified_at'   => date("Y-m-d H:i:s")
                    );

                    $this->Mod_pemasukanhasilproduksi->update_master($arrUpd,array('field'=>'pemasukanhasilproduksi_id','value'=>$res->pemasukanhasilproduksi_id));
                    $t_update   += 1;
                    continue;
                }
                
                $arrD['crc']                    = $crc32;
                
                $arrD['upload_id']              = $uploadId;
                
                $arrD['created_by']          = $this->session->userdata('user_id');
                $arrD['modified_by']         = $this->session->userdata('user_id');
                $arrD['created_at']          = date("Y-m-d H:i:s");
                $arrD['modified_at']         = date("Y-m-d H:i:s");
                
                $arrData[]                   = $arrD;
                
            }
            
            $i++;
        }
        
        if(count($arrData)>0)
        {
            $t_insert   = count($arrData);
            
            $this->Mod_pemasukanhasilproduksi->create_master_batch($arrData);
        }
        
        return array('t_sheet'=>$t_sheet,'t_insert'=>$t_insert,'t_update'=>$t_update);
    }

    private function insert_pengeluaran_hasil_produksi($arr,$uploadId)
    {
        $i       = 1;
        
        $ret     = array();
        
        $arrData = array();
        
        $t_insert   = 0;
        $t_update   = 0;
        $t_sheet    = 0;
        
        foreach($arr as $arrS)
        {
            if($i>=6)
            {
                //kosong
                if(empty($arrS["E"]))
                {
                    break;
                }
                
                $t_sheet                        += 1;
                
                $arrD                           = array();
                
                $arrD['nomorpeb']               = trim($arrS['C']);
                $arrD['tanggalpeb']             = $this->sapDate(trim($arrS['D']));
                $arrD['nomor']                  = trim($arrS['E']);
                $arrD['tanggal']                = $this->sapDate(trim($arrS['F']));
                $arrD['pembeli']                = trim($arrS['G']);
                $arrD['negara_tujuan']          = trim($arrS['H']);
                $arrD['material_id']            = $this->getMaterial(trim($arrS['I']),trim($arrS['J']));
                $arrD['batch']                  = trim($arrS['K']);
                $arrD['satuan']                 = trim($arrS['L']);
                $arrD['jumlah']                 = $this->fungsi->toFloat(trim($arrS['M']));
                $arrD['gudang']                 = trim($arrS['Q']);
                $arrD['jumlah_subkontrak']      = $this->fungsi->toFloat(trim($arrS['N']));
                $arrD['nilai_barang']           = $this->fungsi->toFloat(trim($arrS['P']));
                $arrD['mata_uang']              = trim($arrS['O']);
                $arrD['mat_doc']                = trim($arrS['R']);
                
                $crc32                          = sprintf("%x", crc32(implode('', $arrD)));
                
                $rowCrc                         = $this->Mod_pengeluaranhasilproduksi->select_master(array('a.crc'=>$crc32));
                
                if($rowCrc->num_rows()>0)
                {
                    $res = $rowCrc->row();
                    
                    $arrUpd = array(
                            'upload_id'     => $uploadId,
                            'modified_by'   => $this->session->userdata('user_id'),
                            'modified_at'   => date("Y-m-d H:i:s")
                    );

                    $this->Mod_pengeluaranhasilproduksi->update_master($arrUpd,array('field'=>'pengeluaranhasilproduksi_id','value'=>$res->pengeluaranhasilproduksi_id));
                    $t_update   += 1;
                    continue;
                }
                
                $arrD['crc']                    = $crc32;
                
                $arrD['upload_id']              = $uploadId;
                
                $arrD['created_by']          = $this->session->userdata('user_id');
                $arrD['modified_by']         = $this->session->userdata('user_id');
                $arrD['created_at']          = date("Y-m-d H:i:s");
                $arrD['modified_at']         = date("Y-m-d H:i:s");
                
                $arrData[]                   = $arrD;
                
            }
            
            $i++;
        }
        
        if(count($arrData)>0)
        {
            $t_insert   = count ($arrData);
            $this->Mod_pengeluaranhasilproduksi->create_master_batch($arrData);
        }
        
        return array('t_sheet'=>$t_sheet,'t_insert'=>$t_insert,'t_update'=>$t_update);
    }
    
    private function insert_mutasi_bahan_baku($arr,$uploadId)
    {
        $i       = 1;
        
        $ret     = array();
        
        $arrData = array();
        
        foreach($arr as $arrS)
        {
            if($i>=6)
            {
                //kosong
                if(empty($arrS["C"]))
                {
                    break;
                }
                
                $arrD                           = array();
                
                $arrD['tanggal']                = $this->sapDate(trim($arrS['L']));
                $arrD['tanggal_akhir']          = $this->sapDate(trim($arrS['M']));
                $arrD['material_id']            = $this->getMaterial(trim($arrS['C']),trim($arrS['D']));
                $arrD['batch']                  = trim($arrS['E']);
                $arrD['satuan']                 = trim($arrS['F']);
                $arrD['saldo_awal']             = $this->fungsi->toFloat(trim($arrS['G']));								
				$arrD['saldo_awal_lbs']         = $this->fungsi->toFloat(trim($arrS['H']));
                $arrD['pemasukan']              = $this->fungsi->toFloat(trim($arrS['I']));								
				$arrD['pemasukan_lbs']              = $this->fungsi->toFloat(trim($arrS['J']));
                $arrD['pengeluaran']            = $this->fungsi->toFloat(trim($arrS['K']));								
				$arrD['pengeluaran_lbs']            = $this->fungsi->toFloat(trim($arrS['L']));
                $arrD['saldo_akhir']            = $this->fungsi->toFloat(trim($arrS['M']));								
				$arrD['saldo_akhir_lbs']            = $this->fungsi->toFloat(trim($arrS['N']));
                $arrD['gudang']                 = trim($arrS['O']);
                
                $arrD['upload_id']              = $uploadId;
                
                $arrD['created_by']          = $this->session->userdata('user_id');
                $arrD['modified_by']         = $this->session->userdata('user_id');
                $arrD['created_at']          = date("Y-m-d H:i:s");
                $arrD['modified_at']         = date("Y-m-d H:i:s");
                
                $arrData[]                   = $arrD;
                
            }
            
            $i++;
        }
        
        if(count($arrData)>0)
        {
            $this->Mod_mutasibahanbaku->freeup_table();
            
            $this->Mod_mutasibahanbaku->create_master_batch($arrData);
        }
        
        return count($arrData);
    }
    
    private function insert_mutasi_hasil_produksi($arr,$uploadId)
    {
        $i       = 1;
        
        $ret     = array();
        
        $arrData = array();
        
        foreach($arr as $arrS)
        {
            if($i>=6)
            {
                //kosong
                if(empty($arrS["C"]))
                {
                    break;
                }
                
                $arrD                           = array();
                
                $arrD['tanggal']                = $this->sapDate(trim($arrS['L']));
                $arrD['tanggal_akhir']          = $this->sapDate(trim($arrS['M']));
                $arrD['material_id']            = $this->getMaterial(trim($arrS['C']),trim($arrS['D']));
                $arrD['batch']                  = trim($arrS['E']);
                $arrD['satuan']                 = trim($arrS['F']);
                $arrD['saldo_awal']             = $this->fungsi->toFloat(trim($arrS['G']));
                $arrD['pemasukan']              = $this->fungsi->toFloat(trim($arrS['H']));
                $arrD['pengeluaran']            = $this->fungsi->toFloat(trim($arrS['I']));
                $arrD['saldo_akhir']            = $this->fungsi->toFloat(trim($arrS['J']));
                $arrD['gudang']                 = trim($arrS['K']);
                
                $arrD['upload_id']              = $uploadId;
                
                $arrD['created_by']          = $this->session->userdata('user_id');
                $arrD['modified_by']         = $this->session->userdata('user_id');
                $arrD['created_at']          = date("Y-m-d H:i:s");
                $arrD['modified_at']         = date("Y-m-d H:i:s");
                
                $arrData[]                   = $arrD;
                
            }
            
            $i++;
        }
        
        if(count($arrData)>0)
        {
            $this->Mod_mutasihasilproduksi->freeup_table();
            
            $this->Mod_mutasihasilproduksi->create_master_batch($arrData);
        }
        
        return count($arrData);
    }
    
    private function insert_penyelesaian_waste($arr,$uploadId)
    {
        $i       = 1;
        
        $ret     = array();
        
        $arrData = array();
        
        $t_insert   = 0;
        $t_update   = 0;
        $t_sheet    = 0;
        //$arrUpd  = array();
        
        foreach($arr as $arrS)
        {
            if($i>=6)
            {
                //kosong
                if(empty($arrS["D"]))
                {
                    break;
                }
                
                $t_sheet                    += 1;
                
                $arrD                       = array();
                                
                $arrD['tanggal']            = $this->sapDate(trim($arrS['D']));
                $arrD['material_id']        = $this->getMaterial(trim($arrS['E']),trim($arrS['F']));
                $arrD['satuan']             = trim($arrS['G']);
                $arrD['jumlah']             = $this->fungsi->toFloat(trim($arrS['H']));
                $arrD['gudang']             = trim($arrS['J']);
                $arrD['nilai']              = $this->fungsi->toFloat(trim($arrS['I']));
                $arrD['material_document']  = trim($arrS['K']);
				$arrD['tanggal_document']  =$this->sapDate(trim($arrS['L']));
                
                $crc32                      = sprintf("%x", crc32(implode('', $arrD)));
                
                $rowCrc                     = $this->Mod_penyelesaianwaste->select_master(array('crc'=>$crc32));
                
                $arrD['nomor']              = trim($arrS['C']);
                
                if($rowCrc->num_rows()>0)
                {
                    $res = $rowCrc->row();
					
                    if($arrD['nomor']==$res->nomor)
                    {
                        continue;
                    }
                    else
                    {                                                
                        if($arrD['nomor']!=$res->nomor)
                        {
                            $arrUpd = array(
                                'upload_id'     => $uploadId,
                                'nomor'         => $arrD['nomor'],
                                'modified_by'   => $this->session->userdata('user_id'),
                                'modified_at'   => date("Y-m-d H:i:s")
                            );
                            
                            $this->Mod_penyelesaianwaste->update_master($arrUpd,array('field'=>'penyelesaianwaste_id','value'=>$res->penyelesaianwaste_id));
                        }
                        $t_update   += 1;
                        continue;
                    }
                }
                
                $arrD['crc']                    = $crc32;
                
                $arrD['upload_id']              = $uploadId;
                
                $arrD['created_by']          = $this->session->userdata('user_id');
                $arrD['modified_by']         = $this->session->userdata('user_id');
                $arrD['created_at']          = date("Y-m-d H:i:s");
                $arrD['modified_at']         = date("Y-m-d H:i:s");
                
                $arrData[]                   = $arrD;
                
            }
            
            $i++;
        }
        
        if(count($arrData)>0)
        {
            $t_insert   = count($arrData);
            $this->Mod_penyelesaianwaste->create_master_batch($arrData);
        }
        
        return array('t_sheet'=>$t_sheet,'t_insert'=>$t_insert,'t_update'=>$t_update);
    }
}
