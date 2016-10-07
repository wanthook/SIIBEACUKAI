<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once ("Secure_area.php");

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Dashboard
 *
 * @author wanthook
 */
class Dashboard extends Secure_area
{
    function __construct() 
    {
        parent::__construct();
    }
    
    function index()
    {
        $this->_viewloader("dashboard", "","dashboard");
    }
}
