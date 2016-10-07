<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of login
 *
 * @author taufiq
 */
class Login extends CI_Controller
{
    function __construct() 
    {
        parent::__construct();
    }
    
    function index()
    {
//        $this->load->view('login/login');
        if($this->Mod_user->isLoggin())
        {
            redirect('Dashboard');
        }
        else
        {
            $this->form_validation->set_rules('txtUsername', 'Username', 'trim|required|callback_login');
            $this->form_validation->set_error_delimiters('<div class="alert-danger fade in">', '</div>');

            if($this->form_validation->run() == FALSE)
            {
                $this->load->view('login');
            }
            else
            {
                redirect('Dashboard');
            }
        }
    }
    
    function login($username)
    {
        $password = $this->input->post('txtPassword');
        
        if(!$this->Mod_user->login($username,$password))
        {
            $this->form_validation->set_message('login', 
                    $this->lang->line('login_invalid'));
            return false;
        }
        $this->Mod_log->createLog('LOGIN','UserId='.$this->session->userdata('user_id').';UserName='.$username.';',$this->session->userdata('user_id'));
        return true;
    }
    
    function logout()
    {
        $this->Mod_user->logout();
        
        redirect('Login');
    }
}
