<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Excel
 *
 * @author taufiq
 */
require_once APPPATH."/third_party/PHPExcel.php"; 

class Excel extends PHPExcel
{
    //put your code here
    public function __construct() 
    {
        parent::__construct();
    }
}
