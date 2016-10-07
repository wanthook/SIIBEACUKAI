<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once ("Secure_area.php");


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author wanthook
 */
class User extends Secure_area
{
    private $con_page      = "User";
    
    public function form_change_password()
    {
        $data['action'] = site_url($this->con_page.'/form_change_password_update');
        $data['idForm'] = "formChangePassword";
        
        echo $this->load->view('user_change_password_form', $data, TRUE);
    }
    
    public function form_change_password_update()
    {
        $ret = array();
        
        $user_id = $this->Mod_user->getSessionUserId();
        
        $cur_password = $this->input->post('txtCurrentPassword');
        $new_password = $this->input->post('txtNewPassword');
        $con_password = $this->input->post('txtConfirmPassword');
        
        $check_pass = $this->Mod_user->getInfoKaryawanLogin();
        
        if(md5($cur_password)==$check_pass->password)
        {
            if($new_password == $con_password)
            {
                $arr['password'] = md5($new_password);
                $arr['modified_by'] = $this->Mod_user->getSessionUserId();
                $arr['updated_at']  = date('Y-m-d H:i:s');
                
                $this->Mod_user->update_user($arr,$this->Mod_user->getSessionUserId());
                
                $ret['status'] = '1';
                $ret['message'] = $this->lang->line('user_change_password_success');
            }
            else
            {
                $ret['status'] = '0';
                $ret['message'] = $this->lang->line('user_change_password_nosame');
            }
        }
        else
        {
            $ret['status'] = '0';
            $ret['message'] = $this->lang->line('user_change_password_incorrect');
        }
        
        echo json_encode($ret);
    }
}
