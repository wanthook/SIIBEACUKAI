<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Mod_asset_ij
 *
 * @author wanthook
 */
class Mod_pemasukanbahanbaku extends CI_Model
{
    private $table_user     = "user";
    
    private $table_master   = "pemasukanbahanbaku";
    private $table_material = "materialmaster";
//    private $table_detail   = "bpbdetail";
    
    function __construct() 
    {
        parent::__construct();
    }
    
    public function select_master($search=array(),
                                  $limit=0,
                                  $offset=0,
                                  $order_by=array(),
                                  $group_by=array(),
                                  $select="")
    {
        if(is_array($search) && count($search)>0)
        {
            foreach($search as $kSearch => $vSearch)
            {
                $this->db->where($kSearch,$vSearch);
            }
        }
        else if(is_string($search) && !empty ($search))
        {
            $this->db->where($search, NULL, FALSE);
        }
        
        if($limit>0)
        {
            if($offset>0)
            {
                $this->db->limit($limit,$offset);
            }
            else
            {
                $this->db->limit($limit);
            }
        }
        
        if(count($order_by)>0)
        {
            foreach($order_by as $kOrderBy => $vOrderBy)
            {
                $this->db->order_by($kOrderBy,$vOrderBy);
            }
        }
        
        if(count($group_by)>0)
        {
            $this->db->group_by($group_by);
        }
        
        if(empty($select))
        {
            $this->db->select("a.*,"
                            . "b.nama,"
                            . "c.material_code,"
                            . "c.material_desc",false);
        }
        else
        {
            $this->db->select($select,false);
        }
        
        $this->db->from($this->table_master." a");
        $this->db->join($this->table_user." b","a.created_by=b.user_id",'LEFT');
        $this->db->join($this->table_material." c","a.material_id=c.material_id",'LEFT');
        return $this->db->get();
    }
    public function create_master($data)
    {
        $ret = "";
        if(count($data)>0)
        {
            $this->db->insert($this->table_master,$data);
            $ret = $this->db->insert_id();
        }
        
        return $ret;
    }
    
    public function create_master_batch($data)
    {
        $ret = "";
        if(count($data)>0)
        {
            $this->db->insert_batch($this->table_master,$data);
        }
    }
    
    public function update_master($data,$key)
    {
        if(count($data)>0)
        {
            $this->db->where($key['field'],$key['value']);
            $this->db->update($this->table_master,$data);
        }
    }
    
    
    public function create_detail($data)
    {
        $ret = "";
        if(count($data)>0)
        {
            $this->db->insert_batch($this->table_detail,$data);
//            $ret = $this->db->insert_id();
        }
        
        return $ret;
    }
    
    public function deletehard_master($search)
    {
        if(is_array($search) && count($search)>0)
        {
            foreach($search as $kSearch => $vSearch)
            {
                $this->db->where($kSearch,$vSearch);
            }
        }
        else if(is_string($search) && !empty ($search))
        {
            $this->db->where($search, NULL, FALSE);
        }
        
        $this->db->delete($this->table_master);
    }
    
    public function update_detail($data,$key)
    {
        if(count($data)>0)
        {
            $this->db->where($key['field'],$key['value']);
            $this->db->update($this->table_detil,$data);
        }
    }
}
