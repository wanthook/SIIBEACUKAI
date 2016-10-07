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
class Barang extends Secure_area
{
    function __construct() 
    {
        parent::__construct();
        
        $this->load->model('Mod_barang');
                        
        //$p = $this->input->get_post('p', TRUE);
        
        
//        var_dump($this->perusahaan);
    }
    
    function index()
    {
        //$data['perusahaan'] = $this->perusahaan[0];
        $this->_viewloader("Barang/Proses_barang", '',"Barang");
    }
    
    function table()
    {
        
        $sel['a.hapus'] = 1;
        
        $id = $this->input->post_get('id');
        
        if(!empty($id))
        {
            $sel['a.barangId'] = $id;
        }
        
        
        $ret = array();
                
        $data = array();        
        
        $dMa = $this->Mod_barang->select_master($sel,
                                            0,
                                            0,
                                            array('a.created_at','desc'));
        
        
        $dMaRes = $dMa->result();
        
        foreach($dMaRes as $res)
        {
            $data[] = array(
                'kodeBarang' => $res->barangKode,
                'namaBarang' => $res->barangNama,
                'nama2Barang' => $res->barangNama2,
                'hargaBarang' => $res->barangHarga,
                'lebarBarang' => $res->barangLebar,
                'panjangBarang' => $res->barangPanjang,
                'BeratBarang' => $res->barangBerat,
                'warnaBarang' => $res->barangWarna,
                'gradeBarang' => $res->barangGrade,
                'unitBarang' => $res->barangUnit,
                'satuanBarang' => $res->barangSatuan,
                'kategoriBarang' => $res->barangKategori,
                'tanggalmasukBarang' => $this->fungsi->convertDate($res->barangTanggalmasuk,"d-m-Y"),
                'groupBarang' => $res->barangGroup,
                'kategoriukuranBarang' => $res->barangKategoriukuran,
                'kategorihargaBarang' => $res->barangKategoriharga,
                'id' => $res->barangId,
                'createdAt' => $this->fungsi->convertDate($res->created_at,"d-m-Y H:i:s")
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
        
        $tanggalExp = explode("-", $this->input->post('txtTanggalmasukBarang'));
        
        $var['barangKode'] = $this->input->post('txtKodeBarang');
        $var['barangNama'] = $this->input->post('txtNamaBarang');
        $var['barangNama2'] = $this->input->post('txtNamaBarang2');
        $var['barangHarga'] = $this->input->post('txtHargaBarang');
        $var['barangLebar'] = $this->input->post('txtLebarBarang');
        $var['barangPanjang'] = $this->input->post('txtPanjangBarang');
        $var['barangBerat'] = $this->input->post('txtBeratBarang');
        $var['barangWarna'] = $this->input->post('txtWarnaBarang');
        $var['barangGrade'] = $this->input->post('txtGradeBarang');
        $var['barangUnit'] = $this->input->post('txtUnitBarang');
        $var['barangSatuan'] = $this->input->post('txtSatuanBarang');
        $var['barangKategori'] = $this->input->post('txtKategoriBarang');
        $var['barangTanggalmasuk'] = $tanggalExp[2]."-".$tanggalExp[1]."-".$tanggalExp[0];
        $var['barangGroup'] = $this->input->post('txtGroupBarang');
        $var['barangKategoriukuran'] = $this->input->post('txtKategoriukuranBarang');
        $var['barangKategoriharga'] = $this->input->post('txtKategorihargaBarang');
        
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
            
            $search = array('barangKode'=>$var['barangKode'],
                            'a.hapus'=>'1');


            $mstr = $this->Mod_barang->select_master($search);

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
    
    private function save($obj)
    {
        $ret = 0;
        
        if(!empty($obj))
        {            
            $obj['created_by']            = $this->session->userdata('user_id');
            $obj['modified_by']           = $this->session->userdata('user_id');
            $obj['created_at']            = date("Y-m-d H:i:s");
            $obj['modified_at']           = date("Y-m-d H:i:s");
            
            $ret = $this->Mod_barang->create_master($obj);
            
            
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
            
            $this->Mod_barang->update_master($master,array('field'=>'barangId','value'=>$obj['id']));
            
            
        }
    }
    

    function sBarang()
    {
        $aRet = array();
        
        //$p = $this->input->get('p');
        $res = "";
        if($this->input->get("id")!="")
        {
            $q = $this->input->get("id");
            
            $res = $this->Mod_barang->select_master(
                                    "barangId = '".$q."'",
                                    0,
                                    0,
                                    array('barangNama','asc'),
                                    array('barangNama'))->result();
            
//            $res = $this->Mod_perusahaan->perusahaan_mssql($search)->result();
        }
        else
        {
            $q = $this->input->get("q");
            
            $res = $this->Mod_barang->select_master(
                                    "(barangNama like '%".$q."%' or barangKode like '%".$q."%')",
                                    0,
                                    0,
                                    array('barangNama','asc'),
                                    array('barangNama'))->result();
            
//            $res = $this->Mod_perusahaan->perusahaan_mssql($search)->result();
        }
        
        foreach($res as $ress)
        {
            $aRet[] = array("id"=>$ress->barangId,"text"=>$ress->barangNama,"harga"=>$ress->barangHarga);
        }
        
        echo json_encode($aRet);
    }
   
}
