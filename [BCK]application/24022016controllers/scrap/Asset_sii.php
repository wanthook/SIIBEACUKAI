<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once ("Secure_area.php");

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of Asset_sii
 *
 * @author wanthook
 */
class Asset_sii extends Secure_area
{
    
    private $table_tools            = "tblAssetIj";
    private $table_tools_detail     = "tblAssetDetailIj";
    private $con_page      = "Asset_sii";
    private $uri_segment    = 3;
    private $limit          = 50;
    
    function __construct() 
    {
        parent::__construct();
        
        $this->load->model('Mod_asset_ij');
    }
    
    public function index()
    {
        /*
         * get offset for pagination
         */
        $offset = $this->uri->segment($this->uri_segment);
        
        $search['a.hapus'] = '1';
        $search['a.type'] = 'SII';
        $order['a.tanggal'] = 'desc';
        
        $data_count = $this->Mod_asset_ij->select_master($search)->num_rows();
        $data = $this->Mod_asset_ij->select_master($search,  $this->limit, $offset, $order)->result();
        
        $view['table'] = $this->create_table_master($data,$data_count,$offset);
        
        $this->load->view('asset_sii',$view);
    }
    
    public function form_add()
    {
        $data['action'] = site_url($this->con_page.'/form_add_save');
        $data['idForm'] = "formAddMaster";
        
        echo $this->load->view('asset_sii_form', $data, TRUE);
    }
    
    public function form_edit($id)
    {
        $data['action']     = site_url($this->con_page.'/form_add_update');
        $data['idForm']     = "formAddMaster";
        $data['master_id']   = $id;
        
        $res = $this->Mod_asset_ij->select_master(array('master_id'=>$id))->result();
        
        $data['form_data'] = $res[0];
        
        $data['form_data']->tanggal = $this->fungsi->convertDate($data['form_data']->tanggal,'d-m-Y');
        
        echo $this->load->view('asset_sii_form', $data, TRUE);
    }
    
    public function form_add_detail()
    {
        $data['action'] = site_url($this->con_page.'/form_add_detail_save');
        $data['idForm'] = "formAddDetail";
        
        echo $this->load->view('asset_sii_form_detail', $data, TRUE);
    }
    
    public function form_edit_detail($id)
    {
        $data['action']     = site_url($this->con_page.'/form_add_detail_update');
        $data['idForm']     = "formAddDetail";
        $data['idDetail']   = $id;
        
        $search['detail_id']    = $id;
        
        $res = $this->Mod_asset_ij->select_detail($search)->result();
        
        $data['form_data'] = $res[0];
        
//        $data['form_data']->payment_status = $this->fungsi->convertDate($data['form_data']->payment_status,'d-m-Y');
//        $data['form_data']->delivery_status = $this->fungsi->convertDate($data['form_data']->delivery_status,'d-m-Y');
        
        echo $this->load->view('asset_sii_form_detail', $data, TRUE);
    }
    
    public function detail($id)
    {
        $search['a.hapus'] = '1';
        $search['b.type'] = 'SII';
        $search['a.master_id'] = $id;
        $order['a.created_at'] = 'asc';
        
        $data = $this->Mod_asset_ij->select_detail($search, 10000, 0, $order)->result();
        
        $master = $this->Mod_asset_ij->select_master(array('a.master_id'=>$id))->result();
//        echo $id;
        $view['table'] = $this->create_table_detail($data);
        $view['master'] = $master[0];
        
        $this->load->view('asset_sii_detail',$view);
    }
    
    public function form_add_save()
    {
        $arr = array();
        
        $data_ins = array();
        
        $tanggal = explode("-", $this->input->post('txtDate'));
        
        $data_ins['tanggal']     = $tanggal[2]."-".$tanggal[1]."-".$tanggal[0];
        $data_ins['deskripsi']   = $this->input->post('txtDescription');
        $data_ins['status']      = 'NEW';
        $data_ins['type']        = 'SII';
        $data_ins['created_by']  = $this->Mod_user->getSessionUserId();
        $data_ins['modified_by'] = $this->Mod_user->getSessionUserId();
        $data_ins['created_at']  = date('Y-m-d H:i:s');
        $data_ins['updated_at']  = date('Y-m-d H:i:s');
        
        $ins = $this->Mod_asset_ij->create_master($data_ins);
        
        
        
        if($ins>0)
        {
            $arr['status'] = '1';
            $arr['message'] = $this->lang->line('asset_sii_master_success');
        }
        else
        {
            $arr['status'] = '0';
            $arr['message'] = $this->lang->line('asset_sii_master_failed');
        }
        
        echo json_encode($arr);
    }
    
    public function form_add_detail_save()
    {
        $arr = array();
        
        $data_ins = array();
        
        $payment_status = "";
        $delivery_status = "";
        
        
//        if(!empty($this->input->post('txtPaymentStatus')))
//        {
//            $arrPayment = explode("-",$this->input->post('txtPaymentStatus'));
//            $payment_status = $arrPayment[2]."-".$arrPayment[1]."-".$arrPayment[0];
//        }
        
//        if(!empty($this->input->post('txtDeliveryStatus')))
//        {
//            $arrDelivery = explode("-",$this->input->post('txtDeliveryStatus'));
//            $delivery_status = $arrDelivery[2]."-".$arrDelivery[1]."-".$arrDelivery[0];
//        }
        
        $data_ins['master_id']    = $this->input->post('id');
        $detail_id    = $this->input->post('idDetail');
        
//        $data_ins['priority_no']    = $this->input->post('txtPriorityNo');
//        $data_ins['departemen']     = $this->input->post('txtDepartment');
        $data_ins['supplier']       = $this->input->post('txtSupplier');
//        $data_ins['mesin']          = $this->input->post('txtMesin');
//        $data_ins['desc']           = $this->input->post('txtDescription');
        $data_ins['pr_no']          = $this->input->post('txtPrNo');
        $data_ins['po_no']          = $this->input->post('txtPoNo');
        $data_ins['po_currency']    = $this->input->post('txtPoCurrency');
        $data_ins['po_value']       = $this->input->post('txtPoValue');
        $data_ins['per_usd']        = $this->input->post('txtUsd');
        $data_ins['po_value_usd']   = $this->input->post('txtPoValueUsd');
        $data_ins['top']            = $this->input->post('txtTop');
//        $data_ins['payment_status'] = $payment_status;
        $data_ins['budged']         = $this->input->post('txtBudget');
        $data_ins['remarks']        = $this->input->post('txtRemarks');
//        $data_ins['delivery_status']= $delivery_status;
        $data_ins['status']         = "NEW";
        
        $data_ins['created_by']  = $this->Mod_user->getSessionUserId();
        $data_ins['modified_by'] = $this->Mod_user->getSessionUserId();
        $data_ins['created_at']  = date('Y-m-d H:i:s');
        $data_ins['updated_at']  = date('Y-m-d H:i:s');
        
        if(empty($detail_id))
        {
            $ins = $this->Mod_asset_ij->create_detail($data_ins);
            
            $data_upd['status'] = "PROCCESS";
            $data_upd['modified_by'] = $this->Mod_user->getSessionUserId();
            $data_upd['updated_at'] = date('Y-m-d H:i:s');
            
            $this->Mod_asset_ij->update_master($data_upd,$data_ins['master_id']);
        
            if($ins>0)
            {
                $arr['status'] = '1';
                $arr['message'] = $this->lang->line('asset_sii_detail_success');
            }
            else
            {
                $arr['status'] = '0';
                $arr['message'] = $this->lang->line('asset_sii_detail_failed');
            }
        }
        
        
        
        echo json_encode($arr);
    }
    
    public function form_add_update()
    {
        $arr = array();
        
        $data_ins = array();
        
        $tanggal = "";
        
        $master_id  = $this->input->post('id');
        
        if(!empty($this->input->post('txtDate')))
        {
            $arrDate = explode("-",$this->input->post('txtDate'));
            $tanggal = $arrDate[2]."-".$arrDate[1]."-".$arrDate[0];
        }
        
        $data_ins['tanggal'] = $tanggal;
        $data_ins['deskripsi'] = $this->input->post('txtDescription');
        
        $data_ins['modified_by'] = $this->Mod_user->getSessionUserId();
        $data_ins['updated_at']  = date('Y-m-d H:i:s');
        
        if(count($data_ins)>0)
        {
            $this->Mod_asset_ij->update_master($data_ins,$master_id);
            
            $arr['status'] = '1';
            $arr['message'] = $this->lang->line('asset_sii_master_success');
        }
        else
        {
            $arr['status'] = '0';
            $arr['message'] = $this->lang->line('asset_sii_master_noedit');
        }
        
        echo json_encode($arr);
    }
    
    public function form_finish($id)
    {
        
        $data_ins['status'] = 'FINISH';
        
        $data_ins['modified_by'] = $this->Mod_user->getSessionUserId();
        $data_ins['updated_at']  = date('Y-m-d H:i:s');
        
        if(count($data_ins)>0)
        {
            $this->Mod_asset_ij->update_master($data_ins,$id);
            
            $arr['status'] = '1';
            $arr['message'] = $this->lang->line('asset_sii_master_success');
        }
        else
        {
            $arr['status'] = '0';
            $arr['message'] = $this->lang->line('asset_sii_master_noedit');
        }
        
        echo json_encode($arr);
        
    }
    
    public function form_delete($id)
    {
        
        $data_ins['hapus'] = '0';
        
        $data_ins['modified_by'] = $this->Mod_user->getSessionUserId();
        $data_ins['updated_at']  = date('Y-m-d H:i:s');
        
        if(count($data_ins)>0)
        {
            $this->Mod_asset_ij->update_master($data_ins,$id);
            
            $arr['status'] = '1';
            $arr['message'] = $this->lang->line('asset_sii_master_deleted');
        }
        else
        {
            $arr['status'] = '0';
            $arr['message'] = $this->lang->line('asset_sii_detail_noedit');
        }
        
        echo json_encode($arr);
        
    }
    
    public function form_add_detail_update()
    {
        $arr = array();
        
        $data_ins = array();
        
        $payment_status = "";
        $delivery_status = "";
        
        $data_ins['master_id']  = $this->input->post('id');
        $detail_id              = $this->input->post('idDetail');
        
//        if(!empty($this->input->post('txtPaymentStatus')))
//        {
//            $arrPayment = explode("-",$this->input->post('txtPaymentStatus'));
//            $payment_status = $arrPayment[2]."-".$arrPayment[1]."-".$arrPayment[0];
//        }
        
//        if(!empty($this->input->post('txtDeliveryStatus')))
//        {
//            $arrDelivery = explode("-",$this->input->post('txtDeliveryStatus'));
//            $delivery_status = $arrDelivery[2]."-".$arrDelivery[1]."-".$arrDelivery[0];
//        }
        
        /*
         * 
         */
        $data_detail = $this->Mod_asset_ij->select_detail(array('detail_id'=>$detail_id))->result();
        /*
         * 
         */
        
//        if($this->input->post('txtPriorityNo')!=$data_detail[0]->priority_no)
//        {
//            $data_ins['priority_no'] = $this->input->post('txtPriorityNo');
//        }
        
//        if($this->input->post('txtDepartment')!=$data_detail[0]->departemen)
//        {
//            $data_ins['departemen'] = $this->input->post('txtDepartment');
//        }
        
        if($this->input->post('txtSupplier')!=$data_detail[0]->supplier)
        {
            $data_ins['supplier'] = $this->input->post('txtSupplier');
        }
        
//        if($this->input->post('txtMesin')!=$data_detail[0]->mesin)
//        {
//            $data_ins['mesin'] = $this->input->post('txtMesin');
//        }
        
//        if($this->input->post('txtDescription')!=$data_detail[0]->desc)
//        {
//            $data_ins['desc'] = $this->input->post('txtDescription');
//        }
        
        if($this->input->post('txtPrNo')!=$data_detail[0]->pr_no)
        {
            $data_ins['pr_no'] = $this->input->post('txtPrNo');
        }
        
        if($this->input->post('txtPoNo')!=$data_detail[0]->po_no)
        {
            $data_ins['po_no'] = $this->input->post('txtPoNo');
        }
        
        if($this->input->post('txtPoCurrency')!=$data_detail[0]->po_currency)
        {
            $data_ins['po_currency'] = $this->input->post('txtPoCurrency');
        }
        
        if($this->input->post('txtPoValue')!=$data_detail[0]->po_value)
        {
            $data_ins['po_value'] = $this->input->post('txtPoValue');
        }
        
        if($this->input->post('txtUsd')!=$data_detail[0]->per_usd)
        {
            $data_ins['per_usd'] = $this->input->post('txtUsd');
        }
        
        if($this->input->post('txtPoValueUsd')!=$data_detail[0]->po_value_usd)
        {
            $data_ins['po_value_usd'] = $this->input->post('txtPoValueUsd');
        }
        
        if($this->input->post('txtTop')!=$data_detail[0]->top)
        {
            $data_ins['top'] = $this->input->post('txtTop');
        }
        
//        if($payment_status!=$data_detail[0]->payment_status)
//        {
//            $data_ins['payment_status'] = $payment_status;
//        }
        
        if($this->input->post('txtBudget')!=$data_detail[0]->budged)
        {
            $data_ins['budged'] = $this->input->post('txtBudget');
        }
        
        if($this->input->post('txtRemarks')!=$data_detail[0]->remarks)
        {
            $data_ins['remarks'] = $this->input->post('txtRemarks');
        }
        
//        if($delivery_status!=$data_detail[0]->delivery_status)
//        {
//            $data_ins['delivery_status'] = $delivery_status;
//        }        
        
        $data_ins['status'] = "PROCCESS";
        $data_ins['modified_by'] = $this->Mod_user->getSessionUserId();
        $data_ins['updated_at']  = date('Y-m-d H:i:s');
        
        if(count($data_ins)>0)
        {
            $this->Mod_asset_ij->update_detail($data_ins,$detail_id);
            
            $arr['status'] = '1';
            $arr['message'] = $this->lang->line('asset_sii_detail_success');
        }
        else
        {
            $arr['status'] = '0';
            $arr['message'] = $this->lang->line('asset_sii_detail_noedit');
        }
        
        echo json_encode($arr);
    }
    
    public function form_finish_detail($id)
    {
        
        $data_ins['status'] = 'FINISH';
        
        $data_ins['modified_by'] = $this->Mod_user->getSessionUserId();
        $data_ins['updated_at']  = date('Y-m-d H:i:s');
        
        if(count($data_ins)>0)
        {
            $this->Mod_asset_ij->update_detail($data_ins,$id);
            
            $arr['status'] = '1';
            $arr['message'] = $this->lang->line('asset_sii_detail_success');
        }
        else
        {
            $arr['status'] = '0';
            $arr['message'] = $this->lang->line('asset_sii_detail_noedit');
        }
        
        echo json_encode($arr);
        
    }
    
    public function form_delete_detail($id)
    {
        
        $data_ins['hapus'] = '0';
        
        $data_ins['modified_by'] = $this->Mod_user->getSessionUserId();
        $data_ins['updated_at']  = date('Y-m-d H:i:s');
        
        if(count($data_ins)>0)
        {
            $this->Mod_asset_ij->update_detail($data_ins,$id);
            
            $arr['status'] = '1';
            $arr['message'] = $this->lang->line('asset_sii_detail_deleted');
        }
        else
        {
            $arr['status'] = '0';
            $arr['message'] = $this->lang->line('asset_sii_detail_noedit');
        }
        
        echo json_encode($arr);
        
    }
    
    private function create_table_master($data,$total_data,$offset_data)
    {
        $return = array();   
                
        /*
         * create templete table
         */
        $templateTable = $this->fungsi->template_table($this->table_tools);
        
        /*
         * create config paggination table
         */
        $config = $this->fungsi->template_pagging(
                        site_url($this->con_page.'/index/'),
                        $total_data,
                        $this->limit,
                        $this->uri_segment
                    );
        
        /*
         * initializing pagination config
         */
        $this->pagination->initialize($config);
        
        /*
         * insert pagination into return variable
         */
        $return['pagination'] = $this->pagination->create_links();
        
        /*
         * set value table when empty
         */
        $this->table->set_empty("&nbsp;");
        
        /*
         * set header table
         */
        $heading = array(
            $this->lang->line('tbl_master_no'),
            $this->lang->line('tbl_master_date'),
            $this->lang->line('tbl_master_desc'),
            $this->lang->line('tbl_master_status'),
            $this->lang->line('tbl_master_create_date'),
            $this->lang->line('tbl_master_created_by'),
            $this->lang->line('tbl_master_action')
        );
        $this->table->set_heading($heading);
        
        /*
         * set template table
         */
        $this->table->set_template($templateTable);
        
        if(empty($data))
        {
            $this->table->add_row(array('data' => $this->lang->line('tbl_master_empty'), 'colspan' => count($heading), 'align'=> 'center'));
        }
        else
        {
            $i = 1 + $offset_data;
            foreach ($data as $row)
            {            
                $label_status = "";
                
                $button = "";
                
                if(in_array($this->session->userdata('type'), array('ADMIN')))
                {
                    $button = anchor($this->con_page.'/form_edit/'.$row->master_id,'<i class="fa fa-pencil"></i>',array('class'=>'btn btn-warning btn-xs btn-edit','title'=>$this->lang->line('asset_sii_button_edit')))."&nbsp;".
                              anchor($this->con_page.'/detail/'.$row->master_id,'<i class="fa fa-bars"></i>',array('class'=>'btn btn-primary btn-xs','title'=>$this->lang->line('asset_sii_button_detail'))).'&nbsp;'.
                              anchor($this->con_page.'/form_finish/'.$row->master_id,'<i class="fa fa-flag"></i>',array('class'=>'btn btn-success btn-xs btn-finish','title'=>$this->lang->line('asset_sii_button_finish')))."&nbsp;".
                              anchor($this->con_page.'/form_delete/'.$row->master_id,'<i class="fa fa-close"></i>',array('class'=>'btn btn-danger btn-xs btn-del','title'=>$this->lang->line('asset_sii_button_delete')));
                }
                else if(in_array($this->session->userdata('type'), array('IMPORT','FINANCE')))
                {
                    $button = 
//                              anchor($this->con_page.'/form_edit/'.$row->master_id,'<i class="fa fa-pencil"></i>',array('class'=>'btn btn-warning btn-xs btn-edit','title'=>$this->lang->line('asset_sii_button_edit')))."&nbsp;".
                              anchor($this->con_page.'/detail/'.$row->master_id,'<i class="fa fa-bars"></i>',array('class'=>'btn btn-primary btn-xs','title'=>$this->lang->line('asset_sii_button_detail')));
//                        anchor($this->con_page.'/finish/'.$row->master_id,'<i class="fa fa-flag"></i>',array('class'=>'btn btn-success btn-xs','title'=>$this->lang->line('asset_sii_button_finish')))."&nbsp;".
//                    anchor($this->con_page.'/delete/'.$row->master_id,'<i class="fa fa-close"></i>',array('class'=>'btn btn-danger btn-xs','title'=>$this->lang->line('asset_sii_button_delete')));
                }
                else if(in_array($this->session->userdata('type'), array('USER')))
                {
                    $button = anchor($this->con_page.'/form_edit/'.$row->master_id,'<i class="fa fa-pencil"></i>',array('class'=>'btn btn-warning btn-xs btn-edit','title'=>$this->lang->line('asset_sii_button_edit')))."&nbsp;".
                              anchor($this->con_page.'/detail/'.$row->master_id,'<i class="fa fa-bars"></i>',array('class'=>'btn btn-primary btn-xs','title'=>$this->lang->line('asset_sii_button_detail'))).'&nbsp;'.
//                              anchor($this->con_page.'/finish/'.$row->master_id,'<i class="fa fa-flag"></i>',array('class'=>'btn btn-success btn-xs','title'=>$this->lang->line('asset_sii_button_finish')))."&nbsp;".
                              anchor($this->con_page.'/form_delete/'.$row->master_id,'<i class="fa fa-close"></i>',array('class'=>'btn btn-danger btn-xs btn-del','title'=>$this->lang->line('asset_sii_button_delete')));
                    
                    if($row->status=="FINISH")
                    {
                        $button = anchor($this->con_page.'/detail/'.$row->master_id,'<i class="fa fa-bars"></i>',array('class'=>'btn btn-primary btn-xs','title'=>$this->lang->line('asset_sii_button_detail')));
//                              
                    }
                }
                
                if($row->status=="NEW")
                {
                    $label_status = '<span class="label label-primary">'.$this->lang->line('tbl_master_stat_new').'</span>';                    
                }
                else if($row->status=="PROCCESS")
                {
                    $label_status = '<span class="label label-warning">'.$this->lang->line('tbl_master_stat_proc').'</span>';   
                }
                else if($row->status=="FINISH")
                {
                    $label_status = '<span class="label label-success">'.$this->lang->line('tbl_master_stat_finish').'</span>';
                }
                else
                {
                    $label_status = '<span class="label label-danger">'.$this->lang->line('tbl_master_stat_unkn').'</span>';
                }
                
                
                
                $this->table->add_row(
                    $i,
                    $this->fungsi->convertDate($row->tanggal,'d-m-Y'), 
                    $row->deskripsi,
                    $label_status,
                    $this->fungsi->convertDate($row->updated_at,'d-m-Y H:i:s'),
                    $row->nama,
                    $button
                );
                $i++;
            }
        }
        
        /*
         * insert generate table into return variable
         */
        $return['table'] = $this->table->generate();
        
        return $return;
    }
    
    private function create_table_detail($data)
    {
        $return = array();   
                
        /*
         * create templete table
         */
        $templateTable = $this->fungsi->template_table($this->table_tools_detail);
                  
        /*
         * set value table when empty
         */
        $this->table->set_empty("&nbsp;");
        
        /*
         * set header table
         */
        $heading = array(
//            $this->lang->line('tbl_detail_perior'),
            $this->lang->line('tbl_detail_status'),
//            $this->lang->line('tbl_detail_dept'),
            $this->lang->line('tbl_detail_supplier'),
//            $this->lang->line('tbl_detail_machine'),
//            $this->lang->line('tbl_detail_desc'),
            $this->lang->line('tbl_detail_create_prno'),
            $this->lang->line('tbl_detail_created_pono'),
            $this->lang->line('tbl_detail_currency'),
            $this->lang->line('tbl_detail_poval'),
            $this->lang->line('tbl_detail_povalusd'),
            $this->lang->line('tbl_detail_top'),
//            $this->lang->line('tbl_detail_paystat'),
            $this->lang->line('tbl_detail_budget'),
//            $this->lang->line('tbl_detail_delivery'),
            $this->lang->line('tbl_detail_remarks'),            
            $this->lang->line('tbl_detail_create_date'),
            $this->lang->line('tbl_detail_created_by'),
            $this->lang->line('tbl_detail_action')
        );
        $this->table->set_heading($heading);
        
        /*
         * set template table
         */
        $this->table->set_template($templateTable);
        
        if(empty($data))
        {
            $this->table->add_row(array('data' => $this->lang->line('tbl_detail_empty'), 'colspan' => count($heading), 'align'=> 'center'));
        }
        else
        {
//            $i = 1 + $offset_data;
            $total = 0;
            foreach ($data as $row)
            {   
                $status = "";
                $label_status = "";
                $label_delivery = "";
                
//                if(!empty($row->payment_status) && $row->payment_status!='0000-00-00')
//                {
//                    $label_status = $this->fungsi->convertDate($row->payment_status,'d-m-Y');
//                }
//                else
//                {
//                    $label_status = $this->lang->line('tbl_detail_notyet');
//                }
                
//                if(!empty($row->delivery_status)  && $row->delivery_status!='0000-00-00')
//                {
//                    $label_delivery = $this->fungsi->convertDate($row->delivery_status,'d-m-Y');
//                }
//                else
//                {
//                    $label_delivery = $this->lang->line('tbl_detail_notyet');
//                }
                
                $button = "";
                
                if(in_array($this->session->userdata('type'), array('ADMIN')))
                {
                    $button = anchor($this->con_page.'/form_edit_detail/'.$row->detail_id,'<i class="fa fa-pencil"></i>',array('class'=>'btn btn-warning btn-xs btn-edit','title'=>$this->lang->line('asset_sii_button_edit')))."&nbsp;".
//                    anchor($this->con_page.'/detail/'.$row->master_id,'<i class="fa fa-bars"></i>',array('class'=>'btn btn-primary btn-xs','title'=>$this->lang->line('asset_sii_button_detail'))).'&nbsp;'.
                              anchor($this->con_page.'/form_finish_detail/'.$row->detail_id,'<i class="fa fa-flag"></i>',array('class'=>'btn btn-success btn-xs btn-finish','title'=>$this->lang->line('asset_sii_button_finish')))."&nbsp;".
                              anchor($this->con_page.'/form_delete_detail/'.$row->detail_id,'<i class="fa fa-close"></i>',array('class'=>'btn btn-danger btn-xs btn-del','title'=>$this->lang->line('asset_sii_button_delete')));
                }
                else if(in_array($this->session->userdata('type'), array('IMPORT','FINANCE')))
                {
                    $button = 
//                              anchor($this->con_page.'/form_add_update/'.$row->master_id,'<i class="fa fa-pencil"></i>',array('class'=>'btn btn-warning btn-xs','title'=>$this->lang->line('asset_sii_button_edit')))."&nbsp;".
                              anchor($this->con_page.'/form_edit_detail/'.$row->detail_id,'<i class="fa fa-pencil"></i>',array('class'=>'btn btn-warning btn-xs btn-edit','title'=>$this->lang->line('asset_sii_button_edit')));
//                        anchor($this->con_page.'/finish/'.$row->master_id,'<i class="fa fa-flag"></i>',array('class'=>'btn btn-success btn-xs','title'=>$this->lang->line('asset_sii_button_finish')))."&nbsp;".
//                    anchor($this->con_page.'/delete/'.$row->master_id,'<i class="fa fa-close"></i>',array('class'=>'btn btn-danger btn-xs','title'=>$this->lang->line('asset_sii_button_delete')));
                    
                    if($row->status=="FINISH")
                    {
                        $button = "&nbsp;";
//                              
                    }
                }
                else if(in_array($this->session->userdata('type'), array('USER')))
                {
                    $button = anchor($this->con_page.'/form_edit_detail/'.$row->detail_id,'<i class="fa fa-pencil"></i>',array('class'=>'btn btn-warning btn-xs btn-edit','title'=>$this->lang->line('asset_sii_button_edit')))."&nbsp;".
//                    anchor($this->con_page.'/detail/'.$row->master_id,'<i class="fa fa-bars"></i>',array('class'=>'btn btn-primary btn-xs','title'=>$this->lang->line('asset_sii_button_detail'))).'&nbsp;'.
//                              anchor($this->con_page.'/form_finish_detail/'.$row->detail_id,'<i class="fa fa-flag"></i>',array('class'=>'btn btn-success btn-xs','title'=>$this->lang->line('asset_sii_button_finish')))."&nbsp;".
                              anchor($this->con_page.'/form_delete_detail/'.$row->detail_id,'<i class="fa fa-close"></i>',array('class'=>'btn btn-danger btn-xs btn-del','title'=>$this->lang->line('asset_sii_button_delete')));
                    
                    if(!empty($row->payment_status) && $row->payment_status!='0000-00-00')
                    {
                        $button = anchor($this->con_page.'/form_edit_detail/'.$row->detail_id,'<i class="fa fa-pencil"></i>',array('class'=>'btn btn-warning btn-xs btn-edit','title'=>$this->lang->line('asset_sii_button_edit')));
                    }
                    
                    if($row->status=="FINISH")
                    {
                        $button = "&nbsp;";
//                              
                    }
                }
                
                if($row->status=="NEW")
                {
                    $status = '<span class="label label-primary">'.$this->lang->line('tbl_master_stat_new').'</span>';                    
                }
                else if($row->status=="PROCCESS")
                {
                    $status = '<span class="label label-warning">'.$this->lang->line('tbl_master_stat_proc').'</span>';   
                }
                else if($row->status=="FINISH")
                {
                    $status = '<span class="label label-success">'.$this->lang->line('tbl_master_stat_finish').'</span>';
                }
                else
                {
                    $status = '<span class="label label-danger">'.$this->lang->line('tbl_master_stat_unkn').'</span>';
                }
                
                $this->table->add_row(
//                    $row->priority_no,
                    $status,
//                    $row->departemen,
                    $row->supplier,
//                    $row->mesin,
//                    $row->desc,
                    $row->pr_no,
                    $row->po_no,
                    $row->po_currency,
                    $row->po_value,
                    $row->po_value_usd,
                    $row->top,
//                    $label_status,
                    $row->budged,
//                    $label_delivery,
                    $row->remarks,
                    $this->fungsi->convertDate($row->created_at,'d-m-Y H:i:s'),
                    $row->nama,
                    $button
                );
                $total += $row->po_value_usd;
//                $i++;
            }
            
            $this->table->add_row(
                    array('data' => "<b>Total USD</b>", 'colspan' => 6,'style'=>'text-align: right;'),
                    array('data' => $total, 'colspan' => 7,'style'=>'text-align: left;')
                    );
        }
        
        /*
         * insert generate table into return variable
         */
        $return['table'] = $this->table->generate();
        
        return $return;
    }
}
