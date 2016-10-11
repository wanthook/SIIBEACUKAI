<?php

$controller = "UploadData";

?>
    <!-- START RIGHT PANEL -->
    <div class="rightpanel">

        <!-- START NAVIGATOR -->
        <ul class="breadcrumbs">
            <li><a href="<?php echo site_url('Dashboard'); ?>"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li>Upload Data</li>            
        </ul><!-- END NAVIGATOR -->

        <!-- START PAGE HEADER -->
        <div class="pageheader">
            <!--<a href="" class="searchbar btn btn-primary" id="addButton"><i class="iconfa-plus-sign"></i>&nbsp;Add</a>-->
<!--            <form action="results.html" method="post" class="searchbar">
                <input type="text" name="keyword" placeholder="To search type and hit enter..." />
            </form>-->
            <div class="pageicon"><span class="iconfa-upload"></span></div>
            <div class="pagetitle">
                <h5>Upload</h5>
                <h1>Upload Data Excel</h1>
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
                                <a href="#" id="addButton"><i class="iconfa-plus-sign"></i>&nbsp;Upload Data</a>
                            </li>
<!--                            <li>
                                <a href="#" id="downloadXlsButton"><i class="iconfa-download"></i>&nbsp;Download XLS</a>
                            </li>-->
                        </ul>
                    </div>
                    <h4 class="widgettitle">Tabel Log Upload</h4>
                    <table id="tableId" class="table table-bordered responsive">
                        <?php
                        $arrHead = array('Kode File',
                                         'Nama File',
                                         'Ukuran File',
                                         'Total Baris Dalam File',
                                         'Total Data Baru',
                                         'Total Data Update',
                                         'Total Data Mutasi',
                                         'Tanggal Upload');
                        
                        if($this->session->userdata('type')=='ADMIN')
                        {
                            $arrHead = array('',
                                        'Kode File',
                                         'Nama File',
                                         'Ukuran File',                                         
                                         'Total Baris Dalam File',
                                         'Total Data Baru',
                                         'Total Data Update',
                                         'Total Data Mutasi',
                                         'Tanggal Upload');
                        }
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
                        <span>&copy; <?php echo date("Y"); ?>. PT. Spinmill Indah Industry. All Rights Reserved.</span>
                    </div>
                    <div class="footer-right">
                        <span>Created by: Taufiq Hari Widodo (Ext. 383)</span>
                    </div>
                </div><!--footer-->
            </div><!-- START MAIN CONTAINER -->
        </div><!-- END MAINCONTENT -->

    </div><!-- END RIGHT PANEL -->
    
</div><!--mainwrapper-->
<div aria-hidden="false" aria-labelledby="mUploadLabel" role="dialog" tabindex="-1" class="modal hide fade in" id="mUploadModal">
    <div class="modal-header">
        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
        <h3 id="mlanggananLabel"><i class="iconfa-upload"></i>&nbsp;Upload Excel</h3>
    </div>
    <div class="modal-body nopadding">
        <form id="frmUpload" action="<?php echo site_url("UploadData/doUpload"); ?>" method="post" class="stdform stdform2" role="form" enctype="multipart/form-data">
            <p>
                <label>File</label>
                <span class="field">
                    <input type="file" name="txtFile" id="txtFile" class="form-control file">
                </span>
            </p>
<!--            <div class="progress">
                <div id="progresBar" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span class="sr-only">0% Complete</span>
                </div>
            </div>-->
            <p>
                <div id="msg"></div>
            </p>
        
<!--        <p>Letakkan kursor di inputan teks berikut, lalu mulai scan barcodenya</p>
        <form id="frmScan">
            <input type="text" id="txtEfaktur" name="txtEfaktur" autofocus=""/>
        </form>-->
        
    </div>
    <div class="modal-footer">
        <button data-dismiss="modal" class="btn">Keluar</button>
        <button type="submit" class="btn btn-primary" id="cmdSubmit">Upload</button>
    </form>   
    </div>
</div>

<script type="text/javascript">
    var $table      = null;
    var progresBar  = jQuery("#progresBar");
    var msgInfo     = jQuery("#msg");
    
    jQuery(document).ready(function() 
    {
        
        jQuery('#addButton').click(function(e)
        {
            e.preventDefault();
            
            jQuery('#mUploadModal').modal('show');
        });
        
        $table = jQuery('#tableId').DataTable( {
            "sPaginationType": "full_numbers",
            "ordering": true,
            "scrollY": 400,
            "scrollX": true,
            "deferRender": true,
            "ajax": "<?php echo site_url($controller.'/table'); ?>",
            "columns": 
            [  <?php
                if($this->session->userdata('type')=='ADMIN')
                {
                    ?>
                    {
                    sortable: false,
                    "render": function ( data, type, full, meta ) 
                    {
                        return '<button class="btn btn-sm btn-danger" onClick="deleteData('+full.id+')" role="button"><span class="iconfa-minus-sign"></span></button>';
                    }
                },
                    <?php
                }
                ?>
                { "data": "kode" },
                { "data": "filename" },
                { "data": "size" },
                { "data": "row" },
                { "data": "ins" },
                { "data": "upd" },
                { "data": "mut" },
                { "data": "createdAt" }
                
            ]
        });
        
        jQuery('#tableId tbody').on( 'click', 'tr', function () {
            if ( jQuery(this).hasClass('selected') ) {
                jQuery(this).removeClass('selected');
            }
            else {
                $table.$('tr.selected').removeClass('selected');
                jQuery(this).addClass('selected');
            }
        } );
        
        jQuery("#frmUpload").ajaxForm({
            beforeSend: function() {
    //                status.empty();
                jQuery("#cmdSubmit").attr("disabled","disabled");
            },
            uploadProgress: function(event, position, total, percentComplete) {
                jQuery.alerts.dialogClass = 'alert-info';
                jAlert('File sedang diupload.', 'Informasi', function(){
                       jQuery.alerts.dialogClass = null; // reset to default
                    });
            },
            success: function() {
                jQuery.alerts.dialogClass = 'alert-info';
                jAlert('Data sedang diproses.', 'Informasi', function(){
                       jQuery.alerts.dialogClass = null; // reset to default
                    });
            },
            complete: function(xhr) {
                jQuery.alerts.dialogClass = 'alert-warning';
                jAlert('Data selesai diproses.', 'Informasi', function(){
                       jQuery.alerts.dialogClass = null; // reset to default
                    });
                jQuery("#cmdSubmit").removeAttr("disabled");
                $table.ajax.reload();
    //                        status.html(xhr.responseText);
            }
        }); 
        
    });
    <?php
    if($this->session->userdata('type')=='ADMIN')
    {
        ?>
    function deleteData(id)
    {
        if(confirm("Apakah yakin akan menghapus file ini?"))
        {
            jQuery.ajax({
                type: "POST",
                url: "<?php echo site_url($controller.'/procRemove'); ?>",
                dataType: 'json',
                data: {id:id},
                success: function(res) 
                {
                    if(res.status=='1')
                    {
                        jQuery.alerts.dialogClass = 'alert-success';
                        jAlert(res.msg, 'Informasi', function(){
                            jQuery.alerts.dialogClass = null; // reset to default
                        });
                        
                        $table.ajax.reload();
                    }
                    
                    else if(res.status=='0')
                    {
                        jQuery.alerts.dialogClass = 'alert-warning';
                        jAlert(res.msg, 'Perhatian', function(){
                            jQuery.alerts.dialogClass = null; // reset to default
                        });
                        
                        $table.ajax.reload();
                    }
                }
            });
        }
    }
        <?php
    }
    ?>
</script>
<style>
th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: 100%;   
    }
</style>