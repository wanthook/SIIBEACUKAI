<?php

$controller = "PemasukkanBahanBaku";

?>
    <!-- START RIGHT PANEL -->
    <div class="rightpanel">

        <!-- START NAVIGATOR -->
        <ul class="breadcrumbs">
            <li><a href="<?php echo site_url('Dashboard'); ?>"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li><a href="<?php echo site_url('PemasukkanBahanBaku'); ?>">Pemasukkan Bahan Baku</a> <span class="separator"></span></li>
            <li>Pemasukan Bahan Baku Detail</li>
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
                <h1>Laporan Pemasukkan Bahan Baku Detail</h1>
            </div>
        </div><!-- END PAGE HEADER -->		

        <!-- START MAIN CONTENT -->
        <div class="maincontent">
            <!-- START MAIN CONTAINER -->
            <div class="maincontentinner">
                
                <div class="headtitle">                    
                    <h4 class="widgettitle">Tabel Pemasukkan Bahan Baku Detail</h4>
<!--                    <table class="table table-bordered table-invoice">
                        <tr>
                            <td class="width30">No Pabean:</td>
                            <td class="width70"><strong>John Doe</strong></td>
                        </tr>
                    </table>-->
                    <table id="tableId" class="table table-bordered responsive">
                        <?php
                        $arrHead = array('Jenis Dokumen',
                                         'No. Pabeanan',
                                         'Tanggal Pabeanan',
                                         'No. Seri Barang Pabeanan',
                                         'No. Bukti Penerimaan Barang',
                                         'Tanggal Bukti Penerimaan Barang',
                                         'Kode Barang',
                                         'Nama Barang',
                                         'Batch',
                                         'Jumlah',                                         
                                         'Satuan',										 										 'Jumlah (LBS)',                                         'Satuan (LBS)',
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
                    d['p'] = "<?php echo $this->input->post_get('p'); ?>";
                    return d;
                }
            },
            "columns": 
            [  
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
    });
</script>
<style>
th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: 100%;   
    }
</style>