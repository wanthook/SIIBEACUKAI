<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of fungsi
 *
 * @author taufiq
 */
class Fungsi
{
    public function convertDate($date,$format)
    {
        if(!empty($date) && !empty($format) && $date!='0000-00-00')
        {
            $dt = new DateTime($date);
            
            return $dt->format($format);
        }
        
        return "";
    }
    
    public function dateRange($datestart,$dateend,$format)
    {
        $ds = strtotime($datestart);
        $de = strtotime($dateend);
        
        return date($format,($de-$ds));
    }
    
    public function curency($decimal)
    {
        return number_format($decimal,3);
    }
    
    public function days_in_month($month, $year) 
    { 
        // calculate number of days in a month 
        return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31); 
    } 
    
    function template_table($id="bla",$str_add="")
    {
        return array (
                    'table_open'          => '<table class="table table-condensed table-striped table-bordered table-scrollable" '.$str_add.' id="'.$id.'">',
                    
                    'thead_open'          => '<thead>',
                    'thead_close'         => '</thead>',
            
                    'heading_row_start'   => '<tr>',
                    'heading_row_end'     => '</tr>',
                    'heading_cell_start'  => '<th style="text-align: center;">',
                    'heading_cell_end'    => '</th>',
                    
                    'tbody_open'          => '<tbody>',
                    'tbody_close'         => '</tbody>',
            
                    'row_start'           => '<tr align="center">',
                    'row_end'             => '</tr>',
                    'cell_start'          => '<td style="text-align: center;">',
                    'cell_end'            => '</td>',

                    'row_alt_start'       => '<tr>',
                    'row_alt_end'         => '</tr>',
                    'cell_alt_start'      => '<td style="text-align: center;">',
                    'cell_alt_end'        => '</td>',

                    'table_close'         => '</table>'
              );
    }
    
    function template_pagging($baseUrl, $totalRows, $perPage, $url)
    {
        $arrRet = array();
        
        $arrRet['base_url'] = $baseUrl;
        $arrRet['total_rows'] = $totalRows;
        $arrRet['per_page'] = $perPage;
        $arrRet['uri_segment'] = $url;
        
        $arrRet['full_tag_open'] = '<ul class="pagination">';
        $arrRet['full_tag_close'] = '</ul>';
        
        $arrRet['first_tag_open'] = '<li>';
        $arrRet['first_tag_close'] = '</li>';
        
        $arrRet['last_tag_open'] = '<li>';
        $arrRet['last_tag_close'] = '</li>';
        
        $arrRet['cur_tag_open'] = '<li class="disabled"><a href="#">';
        $arrRet['cur_tag_close'] = '</a></li>';
        
        $arrRet['num_tag_open'] = '<li>';
        $arrRet['num_tag_close'] = '</li>';
        
        $arrRet['next_tag_open'] = '<li>';
        $arrRet['next_tag_close'] = '</li>';
        
        $arrRet['prev_tag_open'] = '<li>';
        $arrRet['prev_tag_close'] = '</li>';
        
        return $arrRet;
    }
    
    /*
     * function convert decimal into time
     */
    function DecToTime($num)
    {
        $jam="00";
        $menit="00";
        
        /*
         * Rubah desimal ke string
         */
        $strDec = floatval($num);
        
        /*
         * pecah string dengan pembatas "."
         */
        $splitDec = explode(".", $strDec);
        
        /*
         * cek apakah terjadi split?
         */
        if(count($splitDec)>1)
        {
            $jam    = (strlen($splitDec[0])<2)? "0".$splitDec[0] : $splitDec[0];
            $menit  = (strlen($splitDec[1])<2)? $splitDec[1]."0" : $splitDec[1];
        }
        else
            $jam    = (strlen($strDec)<2)? "0".$strDec : $strDec;
        
        return $jam.":".$menit;
    }
    
    /*
     * function convert time to decimal
     * $time berbentuk decimal
     */
    function TimeToDecimal($time)
    {
        
        $ret = 0;
        
        if(!empty($time))
        {
            $flt = floor($time);
            
            if($flt>0)
            {
                
                /*
                 * depatin buntutnya
                 */                
                $dec = ($time-$flt)/0.6;
                
                $ret = $flt + $dec;
            }
            else
            {
                $ret = $time/0.6;
            }
        }
        
        return $ret;
    }
            
    function getDateRange($dateStart, $dateEnd, $step="+1 month", $format="Y-m-d")
    {
        $dates = array();
	$current = strtotime( $dateStart );
	$last = strtotime( $dateEnd );

	while( $current <= $last ) 
        {
            $dates[] = date( $format, $current );
            $current = strtotime( $step, $current );
	}

	return $dates;
    }
    
    function singkatanBulan($param)
    {
        $ret = "";
        switch (intval($param))
        {
            case 1: 
                $ret = "Jan";
            break;
            case 2: 
                $ret = "Peb";
            break;
            case 3: 
                $ret = "Mar";
            break;
            case 4: 
                $ret = "Apr";
            break;
            case 5: 
                $ret = "Mei";
            break;
            case 6: 
                $ret = "Jun";
            break;
            case 7: 
                $ret = "Jul";
            break;
            case 8: 
                $ret = "Agu";
            break;
            case 9: 
                $ret = "Sep";
            break;
            case 10: 
                $ret = "Okt";
            break;
            case 11: 
                $ret = "Nop";
            break;
            case 12: 
                $ret = "Des";
            break;
        }
        
        return $ret;
    }
    
    function bacaBulan($param)
    {
        $ret = "";
        switch (intval($param))
        {
            case 1: 
                $ret = "Januari";
            break;
            case 2: 
                $ret = "Pebruari";
            break;
            case 3: 
                $ret = "Maret";
            break;
            case 4: 
                $ret = "April";
            break;
            case 5: 
                $ret = "Mei";
            break;
            case 6: 
                $ret = "Juni";
            break;
            case 7: 
                $ret = "Juli";
            break;
            case 8: 
                $ret = "Agustus";
            break;
            case 9: 
                $ret = "September";
            break;
            case 10: 
                $ret = "Oktober";
            break;
            case 11: 
                $ret = "Nopember";
            break;
            case 12: 
                $ret = "Desember";
            break;
        }
        
        return $ret;
    }
}

?>
