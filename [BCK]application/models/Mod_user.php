<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of karyawan
 *
 * @author taufiq
 */
class Mod_user extends CI_Model 
{
    private $tbl = 'user';
    
    private $fieldId = 'user_id';
    
    private $fieldHapus = 'hapus';
    
    function __construct() 
    {
        parent::__construct();
    }
    
    function isLoggin()
    {
        return $this->session->userdata('user_id')!=false;
    }
    
    function getSessionUserId()
    {
        if($this->isLoggin())
        {
            return $this->session->userdata('user_id');
        }
        return false;
    }
    
    function login($username,$password)
    {
        $sql = $this->db->get_where($this->tbl,
                array('username'=>$username,'password'=>md5($password),'hapus'=>'1'),
                1);
        if($sql->num_rows()==1)
        {
            $row = $sql->row();
            $this->session->set_userdata('user_id',$row->user_id);
            $this->session->set_userdata('username',$username);
            $this->session->set_userdata('name',$row->nama);
            $this->session->set_userdata('type',$row->type);
            $this->session->set_userdata('photo',$row->photo);
            return true;
        }
        
        return false;        
    }
    
    function getInfoKaryawanLogin()
    {
        if($this->isLoggin())
        {
            return $this->getUserInfo($this->session->userdata('user_id'));
        }
        
        return false;
    }
    
    function logout()
    {
        $this->Mod_log->createLog('LOGOUT',
                'UserId='.$this->session->userdata('user_id').';UserName='.$this->session->userdata('username').';',
                $this->session->userdata('user_id'));
        $this->session->sess_destroy();
    }
    
    function getUserInfo($person_id)
    {
        $sql = $this->db->get_where($this->tbl,array($this->fieldId=>$person_id,  $this->fieldHapus=>'1'),1);
        
        if($sql->num_rows()==1)
        {
            return $sql->row();
        }
        else
        {
            //create object with empty properties.
            $fields = $this->db->list_fields('user');
            $person_obj = new stdClass;

            foreach ($fields as $field)
            {
                    $person_obj->$field='';
            }

            return $person_obj;
        }
    }
    
    public function update_user($data,$key)
    {
        if(count($data)>0)
        {
            $this->db->where("user_id",$key);
            $this->db->update('user',$data);
        }
    }
}
