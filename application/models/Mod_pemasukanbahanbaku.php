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
        
		//					. "sum('qty_pib') as sQtyPib,"
		//					. "sum('amount_pib') as sAmtPib,"
		//					. "sum('qty_dn') as sQtyDn,"
		$this->db->select_sum('jumlah');
		$this->db->select_sum('jumlah_lbs');
		$this->db->select_sum('qty_pib');
		$this->db->select_sum('amount_pib');
		$this->db->select_sum('qty_dn');
		$this->db->select_sum('nilai_barang');
        $this->db->from($this->table_master." a");
        $this->db->join($this->table_user." b","a.created_by=b.user_id",'LEFT');
        $this->db->join($this->table_material." c","a.material_id=c.material_id",'LEFT');
		$this->db->group_by("a.nomor");
		$this->db->group_by("a.tanggal_bukti");
		$this->db->group_by("a.material_id");
		$this->db->group_by("a.batch");
		$this->db->order_by("a.tanggal_bukti","ASC");
		$this->db->order_by("a.nomor","ASC");
		
		//$this->db->order_by("a.material_id","ASC");
		//$this->db->order_by("a.batch","ASC");
		//print_r($this->db);
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
	
	public function prepare_mutation()
	{
		$this->db->select('a.*',false);
                $this->db->select_sum('jumlah');                
		$this->db->select_sum('jumlah_lbs');
		$this->db->select_sum('qty_pib');
		$this->db->select_sum('amount_pib');
		$this->db->select_sum('qty_dn');
                $this->db->from($this->table_master." a");
		$this->db->group_by("a.nomor");
		$this->db->group_by("a.tanggal_bukti");
		$this->db->group_by("a.material_id");
		$this->db->group_by("a.batch");
//		print_r($this->db);
		$row = $this->db->get()->result();
		
		$arrins = array();
		
		foreach($row as $res)
		{
			$arrins[] = array(
				"no_pabean"	=> $res->nomor,
				"no_bukti" => $res->nomor_bukti,
				"tgl_bukti" => $res->tanggal_bukti,
				"material_id" => $res->material_id,
				"batch" => $res->batch,
				"jumlah" => $res->jumlah,
				"satuan" => $res->satuan,
				"jumlah_lbs" => $res->jumlah_lbs,
				"gudang" => $res->gudang
			);
		}
		
        $this->db->from('tempmutasiin');
		$this->db->truncate();
		//print_r($arrins);
		$this->db->insert_batch('tempmutasiin',$arrins);
		
		
	}
}
