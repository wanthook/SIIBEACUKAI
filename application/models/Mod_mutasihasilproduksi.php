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
class Mod_mutasihasilproduksi extends CI_Model
{
    private $table_user     = "user";
    
    private $table_master   = "mutasihasilproduksi";
    private $table_material = "materialmaster";
//    private $table_detail   = "bpbdetail";
    
    function __construct() 
    {
        parent::__construct();
    }
    
    function get_temp()
    {
        $this->db->select("a.tipe,
                            a.material_id,
                            a.batch,
                            a.tgl_bukti,
                            a.tgl_pengeluaran,
                            a.satuan,
                            a.jumlah,
                            a.gudang",false);
        $this->db->from("tempmutasihasilproduksi a");
//        $this->db->group_by('a.batch');
//        $this->db->group_by('a.tgl_bukti');
//        $this->db->group_by('a.tipe');
        $this->db->order_by("batch,material_id,tgl_bukti,tipe","ASC");
        
        return $this->db->get();
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
            $this->db->select("a.tanggal,
                        a.tanggal_akhir,
                        a.material_id,
                        a.batch,
                        a.satuan,
                        a.saldo_awal,
                        sum(a.pemasukan) as pemasukan,
                        sum(a.pengeluaran) as pengeluaran,
                        a.saldo_akhir,
                        a.gudang,
                        a.mutasihasilproduksi_id,
                        a.created_by,
                        a.created_at,a.mark,"
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
        $this->db->group_by('a.batch');
        $this->db->group_by('a.material_id');
        $this->db->order_by('a.batch, a.tanggal, a.material_id','ASC');
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
    
    public function update_detail($data,$key)
    {
        if(count($data)>0)
        {
            $this->db->where($key['field'],$key['value']);
            $this->db->update($this->table_detil,$data);
        }
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
    
    public function freeup_table()
    {
        $this->db->from($this->table_master);
        $this->db->truncate();
    }
}
