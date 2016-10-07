<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mod_module
 *
 * @author taufiq
 */
class Mod_modul extends CI_Model
{
    private $tbl = "modul";
    private $tbl2 = "user_modul";
    
    private $fieldHapus = "hapus";
    
    function __construct() 
    {
        parent::__construct();
    }
    
    function get_menu()
    {
        $ret = array();
        
        $this->db->select("a.modul_id,
                        a.nama,
                        a.desc,
                        a.route,
                        a.param,
                        a.selected,
                        a.icon",FALSE);
        
        $this->db->from($this->tbl." a");
        $this->db->from($this->tbl2." b");
        
        $this->db->where("a.modul_id = b.modul_id
                          and b.".$this->fieldHapus."='1'
                          and b.user_id = '".$this->Mod_user->getSessionUserId()."'
                          and a.parent='0'
                          and a.".$this->fieldHapus."='1'",NULL,FALSE);
        
        $this->db->order_by("order","asc");
        
        $res = $this->db->get()->result();
        
        foreach($res as $row)
        {
            $child = $this->get_child($row->modul_id);
            
            $arrData = array(
                "modul_id" => $row->modul_id,
                "modul_name" => $row->nama,
                "modul_desc" => $row->desc,
                "modul_route" => $row->route,
                "param" => $row->param,
                "selected" => $row->selected,
                "modul_icon" => $row->icon
            );
            
            if(is_array($child) && count($child)>0)
            {
                $arrData["child"] = $child;
            }
            
            $ret[] = $arrData;
        }
        
        return $ret;
    }
    
    private function get_child($parent_id)
    {
        $ret = array();
        
        $this->db->select("a.modul_id,
                        a.nama,
                        a.desc,
                        a.route,
                        a.param,
                        a.selected,
                        a.icon",FALSE);
        
        $this->db->from($this->tbl." a");
        $this->db->from($this->tbl2." b");
        
        $this->db->where("a.modul_id = b.modul_id
                          and b.".$this->fieldHapus."='1'
                          and b.user_id = '".$this->Mod_user->getSessionUserId()."'
                          and a.parent='$parent_id'
                          and a.".$this->fieldHapus."='1'",NULL,FALSE);
        
        $this->db->order_by("order","asc");
        
        $res = $this->db->get()->result();
        
        foreach($res as $row)
        {
            $child = $this->get_child($row->modul_id);
            
            $arrData = array(
                "modul_id" => $row->modul_id,
                "modul_name" => $row->nama,
                "modul_desc" => $row->desc,
                "modul_route" => $row->route,
                "param" => $row->param,
                "selected" => $row->selected,
                "modul_icon" => $row->icon
            );
            
            if(is_array($child) && count($child)>0)
            {
                $arrData["child"] = $child;
            }
            
            $ret[] = $arrData;
        }
        
        return $ret;
    }
}
