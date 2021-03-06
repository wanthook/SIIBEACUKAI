<?php

$controller = "PemasukkanBahanBaku";

?>
    <!-- START RIGHT PANEL -->
    <div class="rightpanel">

        <!-- START NAVIGATOR -->
        <ul class="breadcrumbs">
            <li><a href="<?php echo site_url('Dashboard'); ?>"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li>Pemasukkan Bahan Baku</li>            
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
                <h1>Laporan Pemasukkan Bahan Baku</h1>
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
                    <h4 class="widgettitle">Tabel Pemasukkan Bahan Baku</h4>
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
                        $arrHead = array('Action',
                                         'Jenis Dokumen',
                                         'No. Pabeanan',
                                         'Tanggal Pabeanan',
                                         'No. Seri Barang Pabeanan',
                                         'No. Bukti Penerimaan Barang',
                                         'Tanggal Bukti Penerimaan Barang',
                                         'Kode Barang',
                                         'Nama Barang',
                                         'Batch',
                                         'Jumlah',                                         'Satuan',										 										 'Jumlah (LBS)',                                         'Satuan (LBS)',
                                         'Qty PIB',
                                         'Amount PIB',
                                         'Loss QTY',
                                         'Mata Uang',
                                         'Nilai Barang',
                                         'Gudang',
                                         'Penerima Sub-Kontrak',
                                         'Negara Asal Barang')
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
        
        jQuery('#buttonXls').on('click',function(e)
        {
            e.preventDefault();
            
            var urli = "<?php echo site_url($controller.'/excel'); ?>?sD="+jQuery('#txtDateStart').val()+"&eD="+jQuery('#txtDateEnd').val();
        
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
            "lengthMenu": [ 100, 500, 1000, 1500, 2000 ],
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
                { "data": "action"},
                { "data": "jenisDokumen" },
                { "data": "no" },
                { "data": "tgl" },
                { "data": "noSeri" },
                { "data": "noBukti" },
                { "data": "tglBukti" },
                { "data": "matCode" },
                { "data": "matDes" },
                { "data": "batch" },
                { "data": "jumlah" },                { "data": "satuan" },								{ "data": "lbsJumlah" },                { "data": "satuanlbs" },
                { "data": "pibqty" },
                { "data": "pibamount" },
                { "data": "dnqty" },
                { "data": "mataUang" },
                { "data": "nilaiBarang" },
                { "data": "gudang" },
                { "data": "penerima" },
                { "data": "negara" }
                
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