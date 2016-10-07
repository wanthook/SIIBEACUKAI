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
class Langganan extends Secure_area
{
    function __construct() 
    {
        parent::__construct();
        
        $this->load->model('Mod_langganan');
                        
        //$p = $this->input->get_post('p', TRUE);
        
        
//        var_dump($this->perusahaan);
    }
    
    function index()
    {
        //$data['perusahaan'] = $this->perusahaan[0];
        $this->_viewloader("Langganan/Proses_langganan", '',"Langganan");
    }
    
    function table()
    {
        
        $sel['a.hapus'] = 1;
        
        $id = $this->input->post_get('id');
        
        if(!empty($id))
        {
            $sel['a.langgananId'] = $id;
        }
        
        
        $ret = array();
                
        $data = array();        
        
        $dMa = $this->Mod_langganan->select_master($sel,
                                            0,
                                            0,
                                            array('a.created_at','desc'));
        
        
        $dMaRes = $dMa->result();
        
        foreach($dMaRes as $res)
        {
            $data[] = array(
                'kodeLangganan'         => $res->langgananKode,
                'namadepanLanggangan'   => $res->langgananNamadepan,
                'namaptLangganan'       => $res->langgananNamapt,
                'alamatLangganan'       => $res->langgananAlamat,
                'kotaLangganan'         => $res->langgananKota,
                'kodeposLangganan'      => $res->langgananKodepos,
                'telponLangganan'       => $res->langgananTelpon,
                'hpLangganan'           => $res->langgananHp,
                'faxLangganan'          => $res->langgananFax,
                'namaorangLangganan'    => $res->langgananNamaorang,
                'noidentitasLangganan'  => $res->langgananNoidentitas,
                'pkpLangganan'          => $res->langgananPkp,
                'npwpLangganan'         => $res->langgananNpwp,
                'id'                    => $res->langgananId,
                'createdAt'             => $this->fungsi->convertDate($res->created_at,"d-m-Y H:i:s")
            );
        }
        
        $ret['data'] = $data;
        
        echo json_encode($ret);
    }
    
     private function validationForm()
    {
//        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|max_length[12]|xss_clean');
//        $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[passconf]|md5');
//        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim|required');
//        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        
        
        
        if($this->form_validation->run() == FALSE)
        {
            echo validation_errors();
        }
    }
    
    function proc()
    {
        $id = $this->input->post('txtId');
        
//        $tanggalExp = explode("-", $this->input->post('txtTanggalmasukBarang'));
        
        $var['langgananKode']           = $this->input->post('txtKodeLangganan');
        $var['langgananNamadepan']      = $this->input->post('txtNamaDepanLangganan');
        $var['langgananNamapt']         = $this->input->post('txtNamaPtLangganan');
        $var['langgananAlamat']         = $this->input->post('txtAlamatLangganan');
        $var['langgananKota']           = $this->input->post('txtKotaLangganan');
        $var['langgananKodepos']        = $this->input->post('txtKodePosLangganan');
        $var['langgananTelpon']         = $this->input->post('txtTelponLangganan');
        $var['langgananHp']             = $this->input->post('txtHpLangganan');
        $var['langgananFax']            = $this->input->post('txtFaxLangganan');
        $var['langgananNamaorang']      = $this->input->post('txtNamaOrangLangganan');
        $var['langgananNoidentitas']    = $this->input->post('txtNoIdentitasLangganan');
        $var['langgananPkp']            = $this->input->post('txtPkpLangganan');
        $var['langgananNpwp']           = $this->input->post('txtNpwpLangganan');
        
//        $this->validationForm();
        
        $ret = array();
        
        if(!empty($id))
        {
            $this->edit($var,$id);
            
            $ret = array('status'=>1,
                         'msg'=>'Berhasil dirubah!!!');
        }
        else
        {
            
            $search = array('langgananKode'=>$var['langgananKode'],
                            'a.hapus'=>'1');


            $mstr = $this->Mod_langganan->select_master($search);

            if($mstr->num_rows()<1)
            {

                if($this->save($var)>0)
                {
                    $ret = array('status'=>1,
                                 'msg'=>'Berhasil disimpan!!!');
                }
                else
                {
                    $ret = array('status'=>0,
                                 'msg'   =>'Data gagal disimpan!');
                }
            }
            else
            {
                $ret = array('status'=>0,
                     'msg'=>'Data sudah pernah disimpan!!!');
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
        
        echo json_encode($ret);
    }
    
//    function download()
//    {
//        $this->load->helper("xls_helper");
//        
//        $periode = $this->input->get('txtPeriodeDownload');
//        $perusahaan = $this->input->get('txtPerusahaanDownload');
//        
//        $search = array('npwpLawanTransaksi'=>$this->perusahaan[0]->npwp);
//        
//        if(!empty($periode))
//        {
//            $arrPer = explode("-", $periode);
//            $bulan = intval($arrPer[0]);
//            $tahun = intval($arrPer[1]);
//            
//            $search['masaPajak'] = $bulan;
//            $search['tahunPajak'] = $tahun;
//        }
//        
//        if(!empty($perusahaan))
//        {
//            $search['namaPenjual'] = $perusahaan;
//        }
//        
//        $res = $this->Mod_efaktur->select_master($search,
//                                            0,
//                                            0,
//                                            array('a.created_at','desc'))->result();
//        
//        $data['table'] = $res;
//        
//        $this->load->view('Efaktur/Dokumen_download_xls',$data);
//        
//    }
    
    private function save($obj)
    {
        $ret = 0;
        
        if(!empty($obj))
        {            
            $obj['created_by']            = $this->session->userdata('user_id');
            $obj['modified_by']           = $this->session->userdata('user_id');
            $obj['created_at']            = date("Y-m-d H:i:s");
            $obj['modified_at']           = date("Y-m-d H:i:s");
            
            $ret = $this->Mod_langganan->create_master($obj);
            
            
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
            
            $this->Mod_langganan->update_master($obj,array('field'=>'langgananId','value'=>$id));
            
            
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
            
            $this->Mod_langganan->update_master($master,array('field'=>'langgananId','value'=>$obj['id']));
            
            
        }
    }
    
   
    function sLangganan()
    {
        $aRet = array();
        
        //$p = $this->input->get('p');
        $res = "";
        if($this->input->get("id")!="")
        {
            $q = $this->input->get("id");
            
            $res = $this->Mod_langganan->select_master(
                                    "langgananId = '".$q."'",
                                    0,
                                    0,
                                    array('langgananNamadepan','asc'),
                                    array('langgananNamadepan'))->result();
            
//            $res = $this->Mod_perusahaan->perusahaan_mssql($search)->result();
        }
        else
        {
            $q = $this->input->get("q");
            
            $res = $this->Mod_langganan->select_master(
                                    "(langgananNamadepan like '%".$q."%' or langgananKode like '%".$q."%')",
                                    0,
                                    0,
                                    array('langgananNamadepan','asc'),
                                    array('langgananNamadepan'))->result();
            
//            $res = $this->Mod_perusahaan->perusahaan_mssql($search)->result();
        }
        
        foreach($res as $ress)
        {
            $aRet[] = array("id"=>$ress->langgananId,"text"=>$ress->langgananNamadepan);
        }
        
        echo json_encode($aRet);
    }
}
