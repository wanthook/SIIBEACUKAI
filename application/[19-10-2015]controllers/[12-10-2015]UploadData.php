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
                'filename'          => $res->upload_filename,
                'size'              => $res->upload_size,
                'row'               => $res->upload_row,
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
            $cnt = $this->saveDB($data);
            
            $logData = array("upload_filename"     => $data['original_filename'],
                             "upload_size"         => $data['size'],
                             "upload_row"          => $cnt,
                             "created_by"          => $this->session->userdata('user_id'),
                             "modified_by"         => $this->session->userdata('user_id'),
                             "created_at"          => date("Y-m-d H:i:s"),
                             "modified_at"         => date("Y-m-d H:i:s"));
            
            $this->Mod_logupload->create_master($logData);
        }
//        print_r($data);
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
        
//        delete_files('./uploads/');
        
        return $arr;
    }
    
    private function saveDB($data)
    {
        $ret =null;
        
        if(is_array($data))
        {
            $dataParsing = $this->parsingXls($data);
        }
        
        return $dataParsing;
    }
    
    private function parsingXls($data)
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
                    $total_data += $this->insert_pemasukan_bahan_baku($sheetData);
                }
                else if($judul=="LAPORAN PEMAKAIAN BAHAN BAKU")
                {
                    $total_data += $this->insert_pemakaian_bahan_baku($sheetData);
                }
                else if($judul=="LAPORAN PEMAKAIAN BARANG DALAM PROSES DALAM RANGKA KEGIATAN SUBKONTRAK")
                {
                    $total_data += $this->insert_pemakaian_subkontrak($sheetData);
                }
                else if($judul=="LAPORAN PEMASUKAN HASIL PRODUKSI")
                {
                    $total_data += $this->insert_pemasukan_hasil_produksi($sheetData);
                }
                else if($judul=="LAPORAN PENGELUARAN HASIL PRODUKSI")
                {
                    $total_data += $this->insert_pengeluaran_hasil_produksi($sheetData);
                }
                else if($judul=="LAPORAN MUTASI BAHAN BAKU")
                {
                    $total_data += $this->insert_mutasi_bahan_baku($sheetData);
                }
                else if($judul=="LAPORAN MUTASI HASIL PRODUKSI")
                {
                    $total_data += $this->insert_mutasi_hasil_produksi($sheetData);
                }
                else if($judul=="LAPORAN PENYELESAIAN WASTE/SCRAP")
                {
                    $total_data += $this->insert_penyelesaian_waste($sheetData);
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
    
    private function insert_pemasukan_bahan_baku($arr)
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
                $arrD['jumlah']                 = trim($arrS['M']);
                $arrD['mata_uang']              = trim($arrS['N']);
                $arrD['nilai_barang']           = trim($arrS['O']);
                $arrD['gudang']                 = trim($arrS['P']);
                $arrD['penerima']               = trim($arrS['Q']);
                $arrD['negara']                 = trim($arrS['R']);
                
                $crc32                          = sprintf("%x", crc32(implode('', $arrD)));
                
                $rowCrc                         = $this->Mod_pemasukanbahanbaku->select_master(array('crc'=>$crc32))->num_rows();
                
                if($rowCrc>0)
                {
                    continue;
                }
                
                $arrD['crc']                    = $crc32;
                
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
    
    private function insert_pemakaian_bahan_baku($arr)
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
                $arrD['digunakan']              = trim($arrS['I']);
                $arrD['disubkontrak']           = trim($arrS['J']);
                $arrD['penerima']               = trim($arrS['K']);
                
                $crc32                          = sprintf("%x", crc32(implode('', $arrD)));
                
                $rowCrc                         = $this->Mod_pemakaianbahanbaku->select_master(array('crc'=>$crc32))->num_rows();
                
                if($rowCrc>0)
                {
                    continue;
                }
                
                $arrD['crc']                    = $crc32;
                
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
    
    private function insert_pemakaian_subkontrak($arr)
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
                $arrD['disubkontrak']           = trim($arrS['I']);
                $arrD['penerima']               = trim($arrS['J']);
                
                $crc32                          = sprintf("%x", crc32(implode('', $arrD)));
                
                $rowCrc                         = $this->Mod_pemakaiansubkontrak->select_master(array('crc'=>$crc32))->num_rows();
                
                if($rowCrc>0)
                {
                    continue;
                }
                
                $arrD['crc']                    = $crc32;
                
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
    
    private function insert_pemasukan_hasil_produksi($arr)
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
                $arrD['digunakan']              = trim($arrS['I']);
                $arrD['disubkontrak']           = trim($arrS['J']);
                $arrD['gudang']                 = trim($arrS['K']);
                
                $crc32                          = sprintf("%x", crc32(implode('', $arrD)));
                
                $rowCrc                         = $this->Mod_pemasukanhasilproduksi->select_master(array('crc'=>$crc32))->num_rows();
                
                if($rowCrc>0)
                {
                    continue;
                }
                
                $arrD['crc']                    = $crc32;
                
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

    private function insert_pengeluaran_hasil_produksi($arr)
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
                $arrD['jumlah']                 = trim($arrS['M']);
                $arrD['gudang']                 = trim($arrS['Q']);
                $arrD['jumlah_subkontrak']      = trim($arrS['N']);
                $arrD['nilai_barang']           = trim($arrS['P']);
                $arrD['mata_uang']              = trim($arrS['O']);
                $arrD['mat_doc']                = trim($arrS['R']);
                
                $crc32                          = sprintf("%x", crc32(implode('', $arrD)));
                
                $rowCrc                         = $this->Mod_pengeluaranhasilproduksi->select_master(array('crc'=>$crc32))->num_rows();
                
                if($rowCrc>0)
                {
                    continue;
                }
                
                $arrD['crc']                    = $crc32;
                
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
    
    private function insert_mutasi_bahan_baku($arr)
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
                $arrD['saldo_awal']             = trim($arrS['G']);
                $arrD['pemasukan']              = trim($arrS['H']);
                $arrD['pengeluaran']            = trim($arrS['I']);
                $arrD['saldo_akhir']            = trim($arrS['J']);
                $arrD['gudang']                 = trim($arrS['K']);
                
//                $crc32                          = sprintf("%x", crc32(implode('', $arrD)));
//                
//                $rowCrc                         = $this->Mod_pengeluaranhasilproduksi->select_master(array('crc'=>$crc32))->num_rows();
//                
//                if($rowCrc>0)
//                {
//                    continue;
//                }
//                
//                $arrD['crc']                    = $crc32;
                
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
    
    private function insert_mutasi_hasil_produksi($arr)
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
                $arrD['saldo_awal']             = trim($arrS['G']);
                $arrD['pemasukan']              = trim($arrS['H']);
                $arrD['pengeluaran']            = trim($arrS['I']);
                $arrD['saldo_akhir']            = trim($arrS['J']);
                $arrD['gudang']                 = trim($arrS['K']);
                
//                $crc32                          = sprintf("%x", crc32(implode('', $arrD)));
//                
//                $rowCrc                         = $this->Mod_pengeluaranhasilproduksi->select_master(array('crc'=>$crc32))->num_rows();
//                
//                if($rowCrc>0)
//                {
//                    continue;
//                }
//                
//                $arrD['crc']                    = $crc32;
                
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
    
    private function insert_penyelesaian_waste($arr)
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
                $arrD['jumlah']             = trim($arrS['H']);
                $arrD['gudang']             = trim($arrS['J']);
                $arrD['nilai']              = trim($arrS['I']);
                $arrD['material_document']  = trim($arrS['K']);
                
                $crc32                      = sprintf("%x", crc32(implode('', $arrD)));
                
                $rowCrc                     = $this->Mod_penyelesaianwaste->select_master(array('crc'=>$crc32));
                
                $arrD['nomor']              = trim($arrS['C']);
                
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
                }
                
                $arrD['crc']                    = $crc32;
                
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

//    function Add()
//    {
//        $noFaktur = $this->generateFaktur();
//        
//        $this->Form(array("faktur"=>$noFaktur));
//    }
//    
//    private function Form($data="")
//    {
//        $this->_viewloader("Laporan/PemasukkanBahanBaku", $data,"PemasukkanBahanbaku");
//    }
//    
//     private function validationForm()
//    {
//        if($this->form_validation->run() == FALSE)
//        {
//            echo validation_errors();
//        }
//    }
//    
//    function procSS()
//    {
//        $txtKodeBarang           = $this->input->post('txtKodeBarang');
//        
//        print_r($txtKodeBarang);
//    }
//    
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
////            if($mstr->num_rows()<1)
////            {
////                $det = $this->processDetail($det);
////                
////                $val['jumlah']  = $det['jumlah'];
////                
////                $masterId = $this->save($val);
////                
////                //ketika save berhasil
////                if($masterId>0)
////                {
////                    //cek detail ada isinya atau tidak
////                    if(count($det['data'])>0)
////                    {
////                        $this->saveDetail($det['data'], $masterId);
////                    
////                        foreach($det['updateBarang'] as $ubK => $ubV)
////                        {
////                            $this->editBarang($ubV, $ubV['barangId']);
////                        }
////
////                        $this->saveLog($det['log']);   
////                        
////                        $ret = array('status'=>1,
////                         'msg'=>'Berhasil disimpan!!!');
////                    }
////                }
////            }
//        }
//        echo json_encode($ret);
//    }
//    
//    function procRemove()
//    {
//        $ret = array();
//        
//        $var['id'] = $this->input->post('id');
//        
//        if(!empty($var['id']))
//        {
//            $this->delete($var);
//            
//            $ret = array('status'=>1,
//                         'msg'=>'Berhasil dihapus!!!');
//        }
//        else
//        {
//            $ret = array('status'=>0,
//                         'msg'=>'Gagal dihapus!!!');
//        }
//        
//        echo json_encode($ret);
//    }
//    
//    private function processDetail($var)
//    {
////        $arr = array();
//        
//        $data = array();
//        
//        $jumlah = 0;
//        
//        $logTransaksi = array();
//        
//        $updateBarang = array();
//        
//        if(isset($var['barangId']))
//        {
//        
//            if(is_array($var['barangId']) && count($var['barangId'])>0)
//            {
//
//                foreach($var['barangId'] as $key => $val)
//                {
//                    if(!empty($val))
//                    {
//                        $data[] = array('barangId'   => $val,
//                                        'unit'       => $var['unit'][$key],
//                                        'hargaSatuan'=> $var['hargaSatuan'][$key],
//                                        'diskon'     => $var['diskon'][$key],
//                                        'jumlah'     => $var['jumlah'][$key]);
//
//                        $barangData = $this->Mod_barang->select_id($val);
//
//                        $logTransaksi[] = array(
//                                                'tanggal'   => date('Y-m-d H:i:s'),
//                                                'barangId'  => $val,
//                                                'stokAwal'  => $barangData->barangUnit,
//                                                'stokAkhir' => ($barangData->barangUnit-$var['unit'][$key])
//                                                );
//
//                        $updateBarang[] = array(
//                                                'barangId'=>$val,
//                                                'barangUnit'=>($barangData->barangUnit-$var['unit'][$key])
//                                                );
//
//                        $jumlah += $var['jumlah'][$key];
//                    }
//                }
//            }
//        }
//        
//        return array('jumlah'=>$jumlah,'data'=>$data,'updateBarang'=>$updateBarang,'log'=>$logTransaksi);
//    }
//    
//    private function save($obj)
//    {
//        $ret = 0;
//        
//        if(!empty($obj))
//        {            
//            $obj['created_by']            = $this->session->userdata('user_id');
//            $obj['modified_by']           = $this->session->userdata('user_id');
//            $obj['created_at']            = date("Y-m-d H:i:s");
//            $obj['modified_at']           = date("Y-m-d H:i:s");
//            
//            $ret = $this->Mod_bpb->create_master($obj);
//            
//            
//        }
//        
//        return $ret;
//    }
//    
//    private function saveDetail($obj,$master)
//    {
//        $ret    = 0;
//        $o      = array();
//        
//        if(is_array($obj) && count($obj)>0 && !empty($master))
//        {
//            foreach($obj as $k => $v)
//            {
//                $v['bpbMasterId']           = $master;
//                $v['created_by']            = $this->session->userdata('user_id');
//                $v['modified_by']           = $this->session->userdata('user_id');
//                $v['created_at']            = date("Y-m-d H:i:s");
//                $v['modified_at']           = date("Y-m-d H:i:s");
//                
//                $o[] = $v;
//            }
//            
//            $ret = $this->Mod_bpb->create_detail($o);
//        }
//        
//        return $ret;
//    }
//    
//    private function saveLog($obj)
//    {
//        $ret    = 0;
//        $o      = array();
//        
//        if(is_array($obj) && count($obj)>0)
//        {
//            foreach($obj as $k => $v)
//            {
//                //$v['bpbMasterId']           = $master;
//                $v['created_by']            = $this->session->userdata('user_id');
//                $v['created_at']            = date("Y-m-d H:i:s");
//                
//                $o[] = $v;
//            }
//            
//            $ret = $this->Mod_logtransaksi->create_master($o);
//        }
//        
//        return $ret;
//    }
//    
//    private function edit($obj,$id)
//    {
//        $ret = 0;
//        
//        if(!empty($obj))
//        {            
//            $obj['modified_by']           = $this->session->userdata('user_id');
//            $obj['modified_at']           = date("Y-m-d H:i:s");
//            
//            $this->Mod_bpb->update_master($obj,array('field'=>'langgananId','value'=>$id));
//            
//            
//        }
//    }
//    
//     private function editBarang($obj,$id)
//    {
//        $ret = 0;
//        
//        if(!empty($obj))
//        {            
//            $obj['modified_by']           = $this->session->userdata('user_id');
//            $obj['modified_at']           = date("Y-m-d H:i:s");
//            
//            $this->Mod_barang->update_master($obj,array('field'=>'barangId','value'=>$id));
//            
//            
//        }
//    }
//    
//    private function delete($obj)
//    {
//        $ret = 0;
//        
//        if(!empty($obj))
//        {            
//            $master = array('hapus'               => '0',
//                            'modified_by'           => $this->session->userdata('user_id'),
//                            'modified_at'           => date("Y-m-d H:i:s")
//                    );
//            
//            $this->Mod_bpb->update_master($master,array('field'=>'langgananId','value'=>$obj['id']));
//            
//            
//        }
//    }
//    
//    private function generateFaktur()
//    {
//        $q      = $this->Mod_bpb->select_master("",0,0,array(),array(),"max(a.bpbMasterId) mx");
//        
//        $hasil  = $q->result();
//        $val    = $hasil[0]->mx+1;
//        return "7".sprintf("%07d",$val);
//    }
}
