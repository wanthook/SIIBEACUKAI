<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mod_log
 *
 * @author taufiq
 */
class Mod_log extends CI_Model
{
    //put your code here
    function __construct() 
    {
        parent::__construct();
    }
    
    function createLog($type, $memo, $user)
    {
        $this->db->insert('log',array('type'=>$type,
                                      'memo'=>$memo,
                                      'created_by'=>$this->session->userdata('user_id'),
                                      'created_at'=>date("Y-m-d H:i:s")
                                      ));
    }
}

?>
