<?php

$controller = "PemasukkanHasilProduksi";

?>
    <!-- START RIGHT PANEL -->
    <div class="rightpanel">

        <!-- START NAVIGATOR -->
        <ul class="breadcrumbs">
            <li><a href="<?php echo site_url('Dashboard'); ?>"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li><a href="<?php echo site_url('PemasukkanHasilProduksi'); ?>">Pemasukkan Hasil Produksi</a> <span class="separator"></span></li>
            <li>Pemasukan Hasil Produksi Detail</li>
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
                <h1>Laporan Pemasukkan Hasil Produksi Detail</h1>
            </div>
        </div><!-- END PAGE HEADER -->		

        <!-- START MAIN CONTENT -->
        <div class="maincontent">
            <!-- START MAIN CONTAINER -->
            <div class="maincontentinner">
                
                <div class="headtitle">                    
                    <h4 class="widgettitle">Tabel Pemasukkan Hasil Produksi Detail</h4>
<!--                    <table class="table table-bordered table-invoice">
                        <tr>
                            <td class="width30">No Pabean:</td>
                            <td class="width70"><strong>John Doe</strong></td>
                        </tr>
                    </table>-->
                    <table id="tableId" class="table table-bordered responsive">
                        <?php
                        $arrHead = array('No. PIB',
                                         'No. Bukti Penerimaan',
                                         'Tanggal Bukti Penerimaan',
                                         'Kode Barang',
                                         'Nama Barang',
                                         'Batch',
                                         'Satuan',
                                         'Jumlah Dari Produksi',
                                         'Jumlah Dari Subkontrak',
                                         'Gudang');
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
        table = jQuery('#tableId').DataTable( {
//            "sPaginationType": "full_numbers",
            "searching":false,
//            "ordering": true,
            "scrollY": 400,
            "scrollX": true,
            "deferRender": true,
            "processing": true,
            "serverSide": true,
            "lengthMenu": [ 100, 500, 1000, 1500, 2000 ],
            "ajax": 
            {
                "url"   : "<?php echo site_url($controller.'/tableDetail'); ?>",
                "type"  : 'POST',
                "data"  : function(d)
                {
                    d['m'] = "<?php echo $this->input->post_get('m'); ?>";
                    d['b'] = "<?php echo $this->input->post_get('b'); ?>";
                    return d;
                }
            },
            "columns": 
            [  
               { "data": "nopib" },
                { "data": "no" },
                { "data": "tgl" },
                { "data": "matCode" },
                { "data": "matDes" },
                { "data": "batch" },
                { "data": "satuan" },
                { "data": "digunakan" },
                { "data": "disubkontrak" },
                { "data": "gudang" }
                
            ]
        });
    });
</script>
<style>
th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: 100%;   
    }
</style>