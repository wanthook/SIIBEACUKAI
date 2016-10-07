<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$controller = "PenyelesaianWaste";

?>
    <!-- START RIGHT PANEL -->
    <div class="rightpanel">

        <!-- START NAVIGATOR -->
        <ul class="breadcrumbs">
            <li><a href="<?php echo site_url('Dashboard'); ?>"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li>Penyelesaian Waste</li>            
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
                <h1>Laporan Penyelesaian Waste</h1>
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
                                <a href="#" id="buttonXls"><i class="iconfa-arrow-down"></i>&nbsp;Download Excel</a>
                            </li>
                            <li>
                                <a href="#" id="buttonPrint"><i class="iconfa-print"></i>&nbsp;Print</a>
                            </li>
<!--                            <li>
                                <a href="#" id="downloadXlsButton"><i class="iconfa-download"></i>&nbsp;Download XLS</a>
                            </li>-->
                        </ul>
                    </div>
                    <h4 class="widgettitle">Tabel Penyelesaian Waste</h4>
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
                        $arrHead = array('No. BC 2.4',
                                         'Tgl BC 2.4',
                                         'Kode Barang',
                                         'Nama Barang',
                                         'Satuan',
                                         'Jumlah',
                                         'Nilai Barang',
                                         'Gudang',
                                         'Nomor Dokumen',
                                         'Mark')
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
        
        jQuery('.btnEdit').on('click',function(e)
        {
            e.preventDefault();
            
            console.log('bla');
        });
        
        jQuery('#cmdSearch').on('click',function(e)
        {
            e.preventDefault();
            
            table.ajax.reload();
        });
        
        
        jQuery('#buttonPdf').on('click',function(e)
        {
            e.preventDefault();
            
            var urli = "<?php echo site_url($controller.'/pdf'); ?>?sD="+jQuery('#txtDateStart').val()+"&eD="+jQuery('#txtDateEnd').val();
        
//            var dialog = new BootstrapDialog(
//            {
//                title : "Informasi",
//                message: "Menyiapkan file pdf"
//            });

            window.open(urli);
        });
        
        jQuery('#buttonXls').on('click',function(e)
        {
            e.preventDefault();
            
            var urli = "<?php echo site_url($controller.'/excel'); ?>?sD="+jQuery('#txtDateStart').val()+"&eD="+jQuery('#txtDateEnd').val();
        
//            var dialog = new BootstrapDialog(
//            {
//                title : "Informasi",
//                message: "Menyiapkan file pdf"
//            });

            window.open(urli);
        });
        
        jQuery('#buttonPrint').on('click',function(e)
        {
            e.preventDefault();
            
            var urli = "<?php echo site_url($controller.'/pdf'); ?>?sD="+jQuery('#txtDateStart').val()+"&eD="+jQuery('#txtDateEnd').val()+"&t=print";
        
            window.open(urli);
        });
//        jQuery('#tableId_length').html("Bla");
        
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
                { "data": "nobc" },
                { "data": "tglbc" },
                { "data": "matCode" },
                { "data": "matDes" },
                { "data": "satuan" },
                { "data": "jumlah" },                
                { "data": "nilai" },
                { "data": "gudang" },
                { "data": "material" },
                { "data": "mark" }  
                
            ]
        });
        
        jQuery('#txtDateStart,#txtDateEnd').datepicker(
        {
            format:'dd-mm-yyyy'
        });
    });
//    function deleteData(id)
//    {
//        if(confirm('Apakah anda yakin ingin menghapus data ini?'))
//        {
//            jQuery.ajax({
//                type: "POST",
//                url: "<?php echo site_url($controller.'/procRemove'); ?>",
//                dataType: 'json',
//                data: {id:id},
//                success: function(res) 
//                {
//                    if(res.status=='1')
//                    {
//                        jQuery.alerts.dialogClass = 'alert-success';
//                        jAlert(res.msg, 'Informasi', function(){
//                            jQuery.alerts.dialogClass = null; // reset to default
//                        });
//                        
//                        $table.ajax.reload();
//                    }
//                    else if(res.status=='0')
//                    {
//                        jQuery.alerts.dialogClass = 'alert-warning';
//                        jAlert(res.msg, 'Warning', function(){
//                            jQuery.alerts.dialogClass = null; // reset to default
//                        });
//                    }
//                }
//            });
//        }
//    }
</script>
<style>
th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: 100%;   
    }
</style>