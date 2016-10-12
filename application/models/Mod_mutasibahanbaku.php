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
class Mod_mutasibahanbaku extends CI_Model
{
    private $table_user     = "user";
    
    private $table_master   = "mutasibahanbaku";
    private $table_material = "materialmaster";
//    private $table_detail   = "bpbdetail";
    
    function __construct() 
    {
        parent::__construct();
    }
    
    function get_temp()
    {
        $this->db->select("a.material_id,
                            a.batch,
                            a.tgl_bukti,
                            b.tgl_pakai,
                            a.satuan,
                            a.jumlah jml_in,
                            a.jumlah_lbs jmllbs_in,
                            b.jumlah jml_out,
                            b.jumlah_lbs jmlabs_out,
                            a.gudang");
        $this->db->from("tempmutasiin a");
        //$this->db->join("materialmaster mm","a.material_id = mm.material_id","LEFT");
        $this->db->from("tempmutasiout b");
        
        $this->db->where("a.batch = b.batch");
//        $this->db->where("a.batch = 'F0173AE16X' ");
//        $this->db->order_by("batch, tgl_bukti, tgl_pakai","ASC");
        $this->db->order_by("batch, tgl_bukti, tgl_pakai","ASC");
        
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
                        a.saldo_awal_lbs,
                        sum(a.pemasukan) as pemasukan,
                        sum(a.pemasukan_lbs) as pemasukan_lbs,
                        sum(a.pengeluaran) as pengeluaran,
                        sum(a.pengeluaran_lbs) as pengeluaran_lbs,
                        a.saldo_akhir,
                        a.saldo_akhir_lbs,
                        a.gudang,
                        a.mutasibahanbaku_id,
                        a.created_by,
                        a.created_at,"
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
//        $this->db->order_by('a.batch,a.tanggal','asc');
        $this->db->group_by('a.batch');
        $this->db->order_by('a.batch, a.mutasibahanbaku_id','ASC');
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
    public function update_master($data,$key)
    {
        if(count($data)>0)
        {
            $this->db->where($key['field'],$key['value']);
            $this->db->update($this->table_master,$data);
        }
    }    
    public function create_master_batch($data)
    {
        $ret = "";
        if(count($data)>0)
        {
            $this->db->insert_batch($this->table_master,$data);
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
