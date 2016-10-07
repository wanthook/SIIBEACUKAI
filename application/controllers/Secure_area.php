<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of secure_area
 *
 * @author taufiq
 */
class Secure_area extends CI_Controller 
{
    
    function __construct($module_id=null)
    {
        parent::__construct();
        
        if(!$this->Mod_user->isLoggin())
        {
                redirect('Login');
        }

        //load up global data
        $karyawan_login_info = $this->Mod_user->getInfoKaryawanLogin();

        $data['user_info']=$karyawan_login_info;
//        $data['menu'] = $this->menu_list();
        
        $this->load->vars($data);
    }
    
    private function menu_list($selected="")
    {
        $this->load->model("Mod_modul");
        
        $menu = $this->Mod_modul->get_menu();
        
        $str = '';
        
        if($selected=='dashboard')
        {
            $str .= '<li class="active"><a href="'.site_url('Dashboard').'"><span class="iconfa-laptop"></span> Dashboard</a></li>';
        }
        else
        {
            $str .= '<li><a href="'.site_url('Dashboard').'"><span class="iconfa-laptop"></span> Dashboard</a></li>';
        }
        
        foreach($menu as $listMenu)
        {
            
            
            if(isset($listMenu['child']))
            {
                //var_dump($listMenu['child']);
                
                if($this->in_array_r($selected,$listMenu['child']))
                {
                    $str .= '<li class="dropdown active">';
                    
                    $str .= '<a href=""><span class="'.$listMenu["modul_icon"].'"></span> '.$listMenu["modul_name"].'</a>';

                    $str .= '<ul style="display: block">';
                }
                else
                {
                    $str .= '<li class="dropdown">';
                    
                    
                    $str .= '<a href=""><span class="'.$listMenu["modul_icon"].'"></span> '.$listMenu["modul_name"].'</a>';

                    $str .= '<ul>';
                }
                
                
                foreach($listMenu['child'] as $child)
                {
                    $param = '';
                    
                    if(!empty($child['param']))
                    {
                        $param = '?'.$child['param'];
                    }
                    
                    if($selected == $child["selected"])
                    {
                        $str .= '<li class="active"><a href="'.site_url($child["modul_route"]).$param.'">'.$child["modul_name"].'</a></li>';
                    }
                    else
                    {
                        $str .= '<li><a href="'.site_url($child["modul_route"]).$param.'">'.$child["modul_name"].'</a></li>';
                    }
                }
                
                $str .= '</ul>';
            }
            else
            {        
                $param = '';
                    
                if(!empty($listMenu['param']))
                {
                    $param = '?'.$listMenu['param'];
                }
                
                
                if($selected == $listMenu["selected"])
                {
                    $str .= '<li class="active">';
                    $str .= '<a href="'.site_url($listMenu["modul_route"]).$param.'" class="active"><span class="'.$listMenu["modul_icon"].'"></span> '.$listMenu["modul_name"].'</a>';
                }
                else
                {
                    $str .= '<li>';
                    $str .= '<a href="'.site_url($listMenu["modul_route"]).$param.'"><span class="'.$listMenu["modul_icon"].'"></span> '.$listMenu["modul_name"].'</a>';
                }
            }
            
            $str .= '</li>';
        }
        
        return $str;
    }
    
    function _viewloader($view, $data, $menu="") 
    {
        $this->load->view('shamcy/head_open', $data);
        $this->load->view('shamcy/head_css', $data);
        $this->load->view('shamcy/head_js', $data);
        $this->load->view('shamcy/head_close', $data);
        $this->load->view('shamcy/body_open_head', $data);
        
        $data['menu'] = $this->menu_list($menu);        
        $this->load->view('shamcy/body_left_menu', $data);
        
        if(!empty($view))
        {
            $this->load->view($view, $data);
        }        
        $this->load->view('shamcy/close', $data); 
    }
    
    function in_array_r($needle, $haystack, $strict = false) 
    {
        foreach ($haystack as $item) 
        {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->in_array_r($needle, $item, $strict))) 
            {
                return true;
            }
        }

        return false;
    }
}

?>
