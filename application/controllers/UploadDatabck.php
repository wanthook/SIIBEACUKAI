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
        $this->load->model('Mod_pemasukkanbahanbaku');
        $this->load->model('Mod_pengeluaranhasilproduksi');
        $this->load->model('Mod_penyelesaianwaste');
        
        $this->load->model('Mod_materialmaster');
        $this->load->model('Mod_logupload');
    }
    
    function index()
    {
        
        $this->_viewloader("UploadData/FormUpload", '',"UploadData");
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
                'id'         => $res->upload_id,
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
        
        if(count($data)>0)
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
            
            $updData    = array("upload_row"          => $cnt,
                                "modified_by"         => $this->session->userdata('user_id'),
                                "modified_at"         => date("Y-m-d H:i:s"));
            
            
            $this->Mod_logupload->update_master($updData,array('field'=>upload_id,'value'=>$fileLogId));
            
        }
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
        
        $this->upload->do_upload('txtFile');
        
        $arr = array("file"=>$fileName,
                     "original_filename"=>$_FILES['txtFile']['name'],
                     "size"=>$_FILES['txtFile']['size']);
                
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
        $total_data = 0;
        
        if(!empty($data['file']))
        {
            $this->load->library('excel');
            
            $objR = PHPExcel_IOFactory::createReader('Excel5');
            
            $objPHPExcel = $objR->load('./uploads/'.$data['file'].".xls");
            
            foreach ($objPHPExcel->getWorksheetIterator() as $sheet) 
            {
                $sheetData = $sheet->toArray(null,true,true,true);
                
                $judul = strtoupper(trim($sheetData[1]["B"]));
                
                if($judul=="LAPORAN PEMASUKAN BAHAN BAKU")
                {
                    $total_data += $this->insert_pemasukan_bahan_baku($sheetData,$uploadId);
                }
                else if($judul=="LAPORAN PEMAKAIAN BAHAN BAKU")
                {
                    $total_data += $this->insert_pemakaian_bahan_baku($sheetData,$uploadId);
                }
                else if($judul=="LAPORAN PEMAKAIAN BARANG DALAM PROSES DALAM RANGKA KEGIATAN SUBKONTRAK")
                {
                    $total_data += $this->insert_pemakaian_subkontrak($sheetData,$uploadId);
                }
                else if($judul=="LAPORAN PEMASUKAN HASIL PRODUKSI")
                {
                    $total_data += $this->insert_pemasukan_hasil_produksi($sheetData,$uploadId);
                }
                else if($judul=="LAPORAN PENGELUARAN HASIL PRODUKSI")
                {
                    $total_data += $this->insert_pengeluaran_hasil_produksi($sheetData,$uploadId);
                }
                else if($judul=="LAPORAN MUTASI BAHAN BAKU")
                {
                    $total_data += $this->insert_mutasi_bahan_baku($sheetData,$uploadId);
                }
                else if($judul=="LAPORAN MUTASI HASIL PRODUKSI")
                {
                    $total_data += $this->insert_mutasi_hasil_produksi($sheetData,$uploadId);
                }
                else if($judul=="LAPORAN PENYELESAIAN WASTE/SCRAP")
                {
                    $total_data += $this->insert_penyelesaian_waste($sheetData,$uploadId);
                }
            }
            
            return $total_data;
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
            $this->Mod_pemasukkanbahanbaku->deletehard_master($where);
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
                
                $arrD['jenis_dokumen']          = trim($arrS['C']);
                $arrD['nomor']                  = trim($arrS['D']);
                $arrD['tanggal']                = $this->sapDate(trim($arrS['E']));
                $arrD['nomor_seri']             = trim($arrS['F']);
                $arrD['nomor_bukti']            = trim($arrS['G']);
                $arrD['tanggal_bukti']          = $this->sapDate(trim($arrS['H']));
                $arrD['material_id']            = $this->getMaterial(trim($arrS['I']),trim($arrS['J']));
                $arrD['batch']                  = trim($arrS['K']);
                $arrD['satuan']                 = trim($arrS['L']);
                $arrD['jumlah']                 = $this->fungsi->toFloat(trim($arrS['M']));
                $arrD['mata_uang']              = trim($arrS['N']);
                $arrD['nilai_barang']           = $this->fungsi->toFloat(trim($arrS['O']));
                $arrD['gudang']                 = trim($arrS['P']);
                $arrD['penerima']               = trim($arrS['Q']);
                $arrD['negara']                 = trim($arrS['R']);
//                print_r($arrD);
                $crc32                          = sprintf("%x", crc32(implode('', $arrD)));
                
                $rowCrc                         = $this->Mod_pemasukanbahanbaku->select_master(array('crc'=>$crc32));
                
                $arrD['mark']                   = trim($arrS['S']);
                
                if($rowCrc->num_rows()>0)
                {
                    if(empty($arrD['mark']))
                    {
                        continue;
                    }
                    else
                    {
                        $res = $rowCrc->row();
                        
                        if(empty($res->mark))
                        {
                            $arrUpd = array(
                                'mark'          => $arrD['mark'],
                                'modified_by'   => $this->session->userdata('user_id'),
                                'modified_at'   => date("Y-m-d H:i:s")
                            );
                            
                            $this->Mod_pemasukanbahanbaku->update_master($arrUpd,array('field'=>'pemasukanbahanbaku_id','value'=>$res->pemasukanbahanbaku_id));
                        }
                        
                        continue;
                    }
                }
                
//                if($rowCrc>0)
//                {
//                    continue;
//                }
                
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
            $this->Mod_pemasukanbahanbaku->create_master_batch($arrData);
        }
        
        return count($arrData);
    }
    
    private function insert_pemakaian_bahan_baku($arr,$uploadId)
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
                
                $arrD['nomor']                  = trim($arrS['C']);
                $arrD['tanggal']                = $this->sapDate(trim($arrS['D']));
                $arrD['material_id']            = $this->getMaterial(trim($arrS['E']),trim($arrS['F']));
                $arrD['batch']                  = trim($arrS['G']);
                $arrD['satuan']                 = trim($arrS['H']);
                $arrD['digunakan']              = $this->fungsi->toFloat(trim($arrS['I']));
                $arrD['disubkontrak']           = $this->fungsi->toFloat(trim($arrS['J']));
                $arrD['penerima']               = trim($arrS['K']);
                
                $crc32                          = sprintf("%x", crc32(implode('', $arrD)));
                
                $rowCrc                         = $this->Mod_pemakaianbahanbaku->select_master(array('crc'=>$crc32));
                
                $arrD['mark']                   = trim($arrS['L']);
                
                if($rowCrc->num_rows()>0)
                {
                    if(empty($arrD['mark']))
                    {
                        continue;
                    }
                    else
                    {
                        $res = $rowCrc->row();
                        
                        if(empty($res->mark))
                        {
                            $arrUpd = array(
                                'mark'          => $arrD['mark'],
                                'modified_by'   => $this->session->userdata('user_id'),
                                'modified_at'   => date("Y-m-d H:i:s")
                            );
                            
                            $this->Mod_pemakaianbahanbaku->update_master($arrUpd,array('field'=>'pemakaianbahanbaku_id','value'=>$res->pemakaianbahanbaku_id));
                        }
                        
                        continue;
                    }
                }
//                if($rowCrc>0)
//                {
//                    continue;
//                }
                
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
            $this->Mod_pemakaianbahanbaku->create_master_batch($arrData);
        }
        
        return count($arrData);
    }
    
    private function insert_pemakaian_subkontrak($arr,$uploadId)
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
                
                $arrD['nomor']                  = trim($arrS['C']);
                $arrD['tanggal']                = $this->sapDate(trim($arrS['D']));
                $arrD['material_id']            = $this->getMaterial(trim($arrS['E']),trim($arrS['F']));
                $arrD['batch']                  = trim($arrS['G']);
                $arrD['satuan']                 = trim($arrS['H']);
                $arrD['disubkontrak']           = $this->fungsi->toFloat(trim($arrS['I']));
                $arrD['penerima']               = trim($arrS['J']);
                
                $crc32                          = sprintf("%x", crc32(implode('', $arrD)));
                
                $rowCrc                         = $this->Mod_pemakaiansubkontrak->select_master(array('crc'=>$crc32));
                
                $arrD['mark']                   = trim($arrS['K']);
                
                if($rowCrc->num_rows()>0)
                {
                    if(empty($arrD['mark']))
                    {
                        continue;
                    }
                    else
                    {
                        $res = $rowCrc->row();
                        
                        if(empty($res->mark))
                        {
                            $arrUpd = array(
                                'mark'          => $arrD['mark'],
                                'modified_by'   => $this->session->userdata('user_id'),
                                'modified_at'   => date("Y-m-d H:i:s")
                            );
                            
                            $this->Mod_pemakaianbahanbaku->update_master($arrUpd,array('field'=>'pemakaianbahanbaku_id','value'=>$res->pemakaianbahanbaku_id));
                        }
                        
                        continue;
                    }
                }
//                if($rowCrc>0)
//                {
//                    continue;
//                }
                
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
            $this->Mod_pemakaiansubkontrak->create_master_batch($arrData);
        }
        
        return count($arrData);
    }
    
    private function insert_pemasukan_hasil_produksi($arr,$uploadId)
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
                
                $arrD['mark']                   = trim($arrS['L']);
                
                if($rowCrc->num_rows()>0)
                {
                    if(empty($arrD['mark']))
                    {
                        continue;
                    }
                    else
                    {
                        $res = $rowCrc->row();
                        
                        if(empty($res->mark))
                        {
                            $arrUpd = array(
                                'mark'          => $arrD['mark'],
                                'modified_by'   => $this->session->userdata('user_id'),
                                'modified_at'   => date("Y-m-d H:i:s")
                            );
                            
                            $this->Mod_pemakaianbahanbaku->update_master($arrUpd,array('field'=>'pemakaianbahanbaku_id','value'=>$res->pemakaianbahanbaku_id));
                        }
                        
                        continue;
                    }
                }
//                if($rowCrc>0)
//                {
//                    continue;
//                }
                
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
            $this->Mod_pemasukanhasilproduksi->create_master_batch($arrData);
        }
        
        return count($arrData);
    }

    private function insert_pengeluaran_hasil_produksi($arr,$uploadId)
    {
        $i       = 1;
        
        $ret     = array();
        
        $arrData = array();
        
        foreach($arr as $arrS)
        {
            if($i>=6)
            {
                //kosong
                if(empty($arrS["E"]))
                {
                    break;
                }
                
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
                
                $rowCrc                         = $this->Mod_pengeluaranhasilproduksi->select_master(array('crc'=>$crc32));
                
                $arrD['mark']                   = trim($arrS['T']);
                
                if($rowCrc->num_rows()>0)
                {
                    if(empty($arrD['mark']))
                    {
                        continue;
                    }
                    else
                    {
                        $res = $rowCrc->row();
                        
                        if(empty($res->mark))
                        {
                            $arrUpd = array(
                                'mark'          => $arrD['mark'],
                                'modified_by'   => $this->session->userdata('user_id'),
                                'modified_at'   => date("Y-m-d H:i:s")
                            );
                            
                            $this->Mod_pemakaianbahanbaku->update_master($arrUpd,array('field'=>'pemakaianbahanbaku_id','value'=>$res->pemakaianbahanbaku_id));
                        }
                        
                        continue;
                    }
                }
//                if($rowCrc>0)
//                {
//                    continue;
//                }
                
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
            $this->Mod_pengeluaranhasilproduksi->create_master_batch($arrData);
        }
        
        return count($arrData);
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
                $arrD['pemasukan']              = $this->fungsi->toFloat(trim($arrS['H']));
                $arrD['pengeluaran']            = $this->fungsi->toFloat(trim($arrS['I']));
                $arrD['saldo_akhir']            = $this->fungsi->toFloat(trim($arrS['J']));
                $arrD['gudang']                 = trim($arrS['K']);
                $arrD['mark']                   = trim($arrS['N']);
//                $crc32                          = sprintf("%x", crc32(implode('', $arrD)));
//                
//                $rowCrc                         = $this->Mod_pengeluaranhasilproduksi->select_master(array('crc'=>$crc32));
//                
//                if($rowCrc>0)
//                {
//                    continue;
//                }
//                
//                $arrD['crc']                    = $crc32;
                
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
                $arrD['mark']                   = trim($arrS['N']);
//                $crc32                          = sprintf("%x", crc32(implode('', $arrD)));
//                
//                $rowCrc                         = $this->Mod_pengeluaranhasilproduksi->select_master(array('crc'=>$crc32));
//                
//                if($rowCrc>0)
//                {
//                    continue;
//                }
//                
//                $arrD['crc']                    = $crc32;
                
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
                
                $arrD                           = array();
                
                
                $arrD['tanggal']            = $this->sapDate(trim($arrS['D']));
                $arrD['material_id']        = $this->getMaterial(trim($arrS['E']),trim($arrS['F']));
                $arrD['satuan']             = trim($arrS['G']);
                $arrD['jumlah']             = $this->fungsi->toFloat(trim($arrS['H']));
                $arrD['gudang']             = trim($arrS['J']);
                $arrD['nilai']              = $this->fungsi->toFloat(trim($arrS['I']));
                $arrD['material_document']  = trim($arrS['K']);
                
                $crc32                      = sprintf("%x", crc32(implode('', $arrD)));
                
                $rowCrc                     = $this->Mod_penyelesaianwaste->select_master(array('crc'=>$crc32));
                
                $arrD['nomor']              = trim($arrS['C']);
                $arrD['mark']               = trim($arrS['L']);
                
                if($rowCrc->num_rows()>0)
                {
                    if(empty($arrD['nomor']))
                    {
                        continue;
                    }
                    else
                    {
                        $res = $rowCrc->row();
                        
                        if(empty($res->nomor))
                        {
                            $arrUpd = array(
                                'nomor'         => $arrD['nomor'],
                                'modified_by'   => $this->session->userdata('user_id'),
                                'modified_at'   => date("Y-m-d H:i:s")
                            );
                            
                            $this->Mod_penyelesaianwaste->update_master($arrUpd,array('field'=>'penyelesaianwaste_id','value'=>$res->penyelesaianwaste_id));
                        }
                        
                        continue;
                    }
                    
                    if(empty($arrD['mark']))
                    {
                        continue;
                    }
                    else
                    {
                        $res = $rowCrc->row();
                        
                        if(empty($res->mark))
                        {
                            $arrUpd = array(
                                'mark'         => $arrD['mark'],
                                'modified_by'   => $this->session->userdata('user_id'),
                                'modified_at'   => date("Y-m-d H:i:s")
                            );
                            
                            $this->Mod_penyelesaianwaste->update_master($arrUpd,array('field'=>'penyelesaianwaste_id','value'=>$res->penyelesaianwaste_id));
                        }
                        
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
            
            $this->Mod_penyelesaianwaste->create_master_batch($arrData);
        }
        
        return count($arrData);
    }
}
