<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$controller = "PemakaianBahanBaku";

?>
    <!-- START RIGHT PANEL -->
    <div class="rightpanel">

        <!-- START NAVIGATOR -->
        <ul class="breadcrumbs">
            <li><a href="<?php echo site_url('Dashboard'); ?>"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li>Pemakaian Bahan Baku</li>            
        </ul><!-- END NAVIGATOR -->

        <!-- START PAGE HEADER -->
        <div class="pageheader">
            <!--<a href="" class="searchbar btn btn-primary" id="addButton"><i class="iconfa-plus-sign"></i>&nbsp;Add</a>-->
<!--            <form action="results.html" method="post" class="searchbar">
                <input type="text" name="keyword" placeholder="To search type and hit enter..." />
            </form>-->
            <div class="pageicon"><span class="iconfa-bar-chart"></span></div>
            <div class="pagetitle">
                <h5>Laporan</h5>
                <h1>Laporan Pemakaian Bahan Baku</h1>
            </div>
        </div><!-- END PAGE HEADER -->		

        <!-- START MAIN CONTENT -->
        <div class="maincontent">
            <!-- START MAIN CONTAINER -->
            <div class="maincontentinner">
                
                <div class="headtitle">
                    <div class="btn-group">
                        <button data-toggle="dropdown" class="btn dropdown-toggle">Action <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="#" id="buttonPdf"><i class="iconfa-arrow-down"></i>&nbsp;Download PDF</a>
                            </li>
                            <li>
                                <a href="#" id="buttonPrint"><i class="iconfa-print"></i>&nbsp;Print</a>
                            </li>
<!--                            <li>
                                <a href="#" id="downloadXlsButton"><i class="iconfa-download"></i>&nbsp;Download XLS</a>
                            </li>-->
                        </ul>
                    </div>
                    <h4 class="widgettitle">Tabel Pemakaian Bahan Baku</h4>
                    <table class="table">
                        <thead>
                        <tr>
                        <form id="frmSearch">
                            <td><input type="text" id="txtDateStart" name="txtDateStart" class="input-sm" placeholder="Tanggal Awal">&nbsp;S/D&nbsp;<input type="text" id="txtDateEnd" name="txtDateEnd" class="input-sm" placeholder="Tanggal Akhir">&nbsp;<button id="cmdSearch" class="btn btn-sm"><i class="iconfa-search"></i></button></td>
                        </form>
                        </tr>
                        </thead>
                    </table>
                    <table id="tableId" class="table table-bordered responsive">
                        <?php
                        $arrHead = array('No. Bukti Pengeluaran',
                                         'Tgl Bukti Pengeluaran',
                                         'Kode Barang',
                                         'Nama Barang',
                                         'Batch',
                                         'Satuan',
                                         'Jumlah Digunakan',
                                         'Jumlah Disubkontrakkan',
                                         'Penerima Subkontrak')
                        ?>
                        <colgroup>
                            <?php
                            $col = 0;
                            for($i=0 ; $i<count($arrHead) ; $i++)
                            {
                                echo '<col class="con'.$col.'" />';
                                
                                if($col==0) $col = 1;
                                else $col = 0;
                            }
                            ?>
                        </colgroup>
                        <thead>
                            <tr>
                                <?php
                                $col = 0;
                                for($i=0 ; $i<count($arrHead) ; $i++)
                                {
                                    echo '<th class="head'.$col.'">'.$arrHead[$i].'</th>';

                                    if($col==0) $col = 1;
                                    else $col = 0;
                                }
                                ?>
                            </tr>
                        </thead>
                    </table>
                </div>
                
                
                    <!--</div>widgetcontent-->
                <!--</div>widgetbox-->
                
                <div class="footer">
                    <div class="footer-left">
                        <span>&copy; <?php echo date("Y"); ?>. <?php echo $this->lang->line('lang_app_name')." - ".$this->lang->line('lang_app_company_name'); ?> All Rights Reserved.</span>
                    </div>
                    <div class="footer-right">
                        <span>Created by: <?php echo $this->lang->line('lang_app_created_by')." - ".$this->lang->line('lang_app_company_name'); ?></span>
                    </div>
                </div><!--footer-->
            </div><!-- START MAIN CONTAINER -->
        </div><!-- END MAINCONTENT -->

    </div><!-- END RIGHT PANEL -->
    
</div><!--mainwrapper-->


<script type="text/javascript">
    var table=null;
    
    jQuery(document).ready(function() 
    {        
        jQuery('#cmdSearch').on('click',function(e)
        {
            e.preventDefault();
            
            table.ajax.reload();
        });
        
        
        jQuery('#buttonPdf').on('click',function(e)
        {
            e.preventDefault();
            
            var urli = "<?php echo site_url($controller.'/pdf'); ?>?sD="+jQuery('#txtDateStart').val()+"&eD="+jQuery('#txtDateEnd').val();
        
            window.open(urli);
        });
        
        jQuery('#buttonPrint').on('click',function(e)
        {
            e.preventDefault();
            
            var urli = "<?php echo site_url($controller.'/pdf'); ?>?sD="+jQuery('#txtDateStart').val()+"&eD="+jQuery('#txtDateEnd').val()+"&t=print";
        
            window.open(urli);
        });
        
        table = jQuery('#tableId').DataTable( {
            "sPaginationType": "full_numbers",
            "searching":false,
            "ordering": true,
            "scrollY": 400,
            "scrollX": true,
            "deferRender": true,
            "processing": true,
            "serverSide": true,
            "lengthMenu": [ 10, 25, 50, 75, 100, 500, 1000, 1500, 2000 ],
            "ajax": 
            {
                "url"   : "<?php echo site_url($controller.'/table'); ?>",
                "type"  : 'POST',
                "data"  : function(d)
                {
                    d['sD'] = jQuery('#txtDateStart').val();
                    d['eD'] = jQuery('#txtDateEnd').val();
                    return d;
                }
            },
            "columns": 
            [  
                { "data": "no" },
                { "data": "tgl" },
                { "data": "matCode" },
                { "data": "matDes" },
                { "data": "batch" },
                { "data": "satuan" },
                { "data": "digunakan" },
                { "data": "disubkontrakkan" },
                { "data": "penerima" }      
                
            ]
        });
        
        jQuery('#txtDateStart,#txtDateEnd').datepicker(
        {
            format:'dd-mm-yyyy'
        });
    });
</script>
<style>
th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: 100%;   
    }
</style>