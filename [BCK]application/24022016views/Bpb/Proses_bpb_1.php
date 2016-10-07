<?php

$controller = "Bpb";

?>
    <!-- START RIGHT PANEL -->
    <div class="rightpanel">

        <!-- START NAVIGATOR -->
        <ul class="breadcrumbs">
            <li><a href="<?php echo site_url('Dashboard'); ?>"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li>BPB</li>            
        </ul><!-- END NAVIGATOR -->

        <!-- START PAGE HEADER -->
        <div class="pageheader">
            <!--<a href="" class="searchbar btn btn-primary" id="addButton"><i class="iconfa-plus-sign"></i>&nbsp;Add</a>-->
<!--            <form action="results.html" method="post" class="searchbar">
                <input type="text" name="keyword" placeholder="To search type and hit enter..." />
            </form>-->
            <div class="pageicon"><span class="iconfa-user"></span></div>
            <div class="pagetitle">
                <h5>BPB</h5>
                <h1>List BPB</h1>
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
                                <a href="#" id="addButton"><i class="iconfa-plus-sign"></i>&nbsp;Tambah</a>
                            </li>
<!--                            <li>
                                <a href="#" id="downloadXlsButton"><i class="iconfa-download"></i>&nbsp;Download XLS</a>
                            </li>-->
                        </ul>
                    </div>
                    <h4 class="widgettitle">Tabel BPB</h4>
                    <table id="tableId" class="table table-bordered responsive">
                        <colgroup>
                            <col class="con0" />
                            <col class="con1" />
                            <col class="con0" />
                            <col class="con1" />
                            <col class="con0" />
                            <col class="con1" />
                            <col class="con0" />
                            <col class="con1" />
                            <col class="con0" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="head0">&nbsp;</th>
                                <th class="head1">No. Faktur</th>
                                <th class="head0">No. Bukti</th>
                                <th class="head1">No. PO</th>
                                <th class="head0">Tanggal BPB</th>
                                <th class="head1">Kriteria</th>
                                <th class="head0">Kode Langganan</th>
                                <th class="head1">Nama Langganan</th>
                                <th class="head0">Jumlah</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                
                
                    <!--</div>widgetcontent-->
                <!--</div>widgetbox-->
                
                <div class="footer">
                    <div class="footer-left">
                        <span>&copy; <?php echo date("Y"); ?>. PT. Indah Jaya Textile Industry. All Rights Reserved.</span>
                    </div>
                    <div class="footer-right">
                        <span>Created by: Taufiq Hari Widodo (Ext. 383)</span>
                    </div>
                </div><!--footer-->
            </div><!-- START MAIN CONTAINER -->
        </div><!-- END MAINCONTENT -->

    </div><!-- END RIGHT PANEL -->
    
</div><!--mainwrapper-->
<div aria-hidden="false" aria-labelledby="mlanggananLabel" role="dialog" tabindex="-1" class="modal hide fade in" id="mlanggananModal">
    <div class="modal-header">
        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
        <h3 id="mlanggananLabel"><i class="iconfa-briefcase"></i>&nbsp;Master Langganan</h3>
    </div>
    <div class="modal-body nopadding">
        <form id="frmLangganan" class="stdform stdform2">
            <input type="hidden" id="txtId" name="txtId">
            <p>
                <label>Kode Langganan</label>
                <span class="field">
                    <input type="text" id="txtKodeLangganan" name="txtKodeLangganan" autofocus=""/>
                </span>
            </p>
            <p>
                <label>Nama Depan</label>
                 <span class="field">
                     <input type="text" id="txtNamaDepanLangganan" name="txtNamaDepanLangganan" />
                 </span>
            </p>
            <p>
                <label>Nama PT</label>
                 <span class="field">
                     <input type="text" id="txtNamaPtLangganan" name="txtNamaPtLangganan" />
                 </span>
            </p>
            <p>
                <label>Alamat</label>
                 <span class="field">
                     <textarea id="txtAlamatLangganan" name="txtAlamatLangganan"  cols="25" rows="3"></textarea>
                 </span>
            </p>
            <p>
                <label>Kota</label>
                 <span class="field">
                     <input type="text" id="txtKotaLangganan" name="txtKotaLangganan" />
                 </span>
            </p>
            <p>
                <label>Kode Pos</label>
                 <span class="field">
                     <input type="text" id="txtKodePosLangganan" name="txtKodePosLangganan" />
                 </span>
            </p>
            <p>
                <label>Telpon</label>
                 <span class="field">
                     <input type="text" id="txtTelponLangganan" name="txtTelponLangganan" />
                 </span>
            </p>
            <p>
                <label>No. HP</label>
                 <span class="field">
                     <input type="text" id="txtHpLangganan" name="txtHpLangganan" />
                 </span>
            </p>
            <p>
                <label>Fax</label>
                 <span class="field">
                     <input type="text" id="txtFaxLangganan" name="txtFaxLangganan" />
                 </span>
            </p>
            <p>
                <label>Nama Orang</label>
                 <span class="field">
                     <input type="text" id="txtNamaOrangLangganan" name="txtNamaOrangLangganan" />
                 </span>
            </p>
            <p>
                <label>No. Identitas</label>
                 <span class="field">
                     <input type="text" id="txtNoIdentitasLangganan" name="txtNoIdentitasLangganan" />
                 </span>
            </p>
            <p>
                <label>PKP</label>
                 <span class="field">
                     <input type="text" id="txtPkpLangganan" name="txtPkpLangganan" />
                 </span>
            </p>
            <p>
                <label>No. NPWP</label>
                 <span class="field">
                     <input type="text" id="txtNpwpLangganan" name="txtNpwpLangganan" />
                 </span>
            </p>
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
        <button class="btn" onClick="resetForm()">Reset</button>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>   
    </div>
</div>

<script type="text/javascript">
    var $table=null;
    
    jQuery(document).ready(function() 
    {
        
        jQuery('#addButton').click(function(e)
        {
            e.preventDefault();
            
            jQuery('#mlanggananModal').modal('show');
//            jQuery('#efakturModal').modal('reload');
            //console.log("bla");
        });
        
        jQuery('.btnEdit').on('click',function(e)
        {
            e.preventDefault();
            
            console.log('bla');
        });
        
        $table = jQuery('#tableId').DataTable( {
            "sPaginationType": "full_numbers",
            "ordering": true,
            "scrollY": 400,
            "scrollX": true,
            "deferRender": true,
            "ajax": "<?php echo site_url($controller.'/table'); ?>",
            "columns": 
            [                
                {
                    sortable: false,
                    "render": function ( data, type, full, meta ) 
                    {
                        return '<button class="btn btn-sm" onClick="showModal('+full.id+')" role="button"><span class="iconfa-pencil"></span></button>&nbsp;' +
                               '<button class="btn btn-sm btn-danger" onClick="deleteData('+full.id+')" role="button"><span class="iconfa-minus-sign"></span></button>';
                    }
                },
                { "data": "kodeLangganan" },
                { "data": "namadepanLanggangan" },
                { "data": "namaptLangganan" },
                { "data": "alamatLangganan" },
                { "data": "kotaLangganan" },
                { "data": "kodeposLangganan" },
                { "data": "telponLangganan" },
                { "data": "hpLangganan" },
                { "data": "faxLangganan" },
                { "data": "namaorangLangganan" },
                { "data": "noidentitasLangganan" },
                { "data": "pkpLangganan" },
                { "data": "npwpLangganan" },
                
            ]
        });
        
        jQuery('#mlanggananModal').on('show.bs.modal', function () {
            jQuery("#txtKodeLangganan").focus();
        });
        
        jQuery('#mlanggananModal').on('hide.bs.modal', function () {
            
            jQuery("#msg").html("");
            //resetForm();
//            console.log('masa kesini');
            //document.getElementById("frmScan").reset();

        });
        
        
        jQuery('#frmLangganan').on('submit',function(e)
        {
            e.preventDefault();
//            console.log($table);
            if(jQuery('#txtKodeLangganan').val()!="" && jQuery('#txtNamaDepanLangganan').val()!="")
            {
                postData();
            }
            else
            {
                jQuery.alerts.dialogClass = 'alert-warning';
                jAlert('Kode Langganan dan Nama Depan harus diisi terlebih dahulu.', 'Perhatian', function(){
                       jQuery.alerts.dialogClass = null; // reset to default
                    });

                jQuery("#txtKodeLangganan").focus();
            }
        });
    });
    
    function showModal(id)
    {
        if(id)
        {
            jQuery.ajax({
                type: "POST",
                url: "<?php echo site_url($controller.'/table'); ?>",
                dataType: 'json',
                data: {id:id},
                success: function(res) {
                        openEdit( res.data );
                }
            });
        }
    }
    
    function postData()
    {
        //console.log(jQuery('#txtEfaktur').val());
        $txt = jQuery('#txtKodeLangganan');
        jQuery.ajax({
            type: "POST",
            url: "<?php echo site_url($controller.'/proc'); ?>",
            data: jQuery('#frmLangganan').serialize(),
            dataType: "json",
            success: function(data)
            {
                if(data.status==1)
                {
                        
                    jQuery('#msg').html('<span class="label label-success">'+data.msg+'</span>');
                        
                    if($table!=null)
                    {
                        $table.ajax.reload();
                    }
                    
                    //$txtEFaktur.val("");
                }
                else if(data.status==0)
                {
                    jQuery.alerts.dialogClass = 'alert-warning';
                    jAlert(data.msg, 'Perhatian', function(){
                           jQuery.alerts.dialogClass = null; // reset to default
                        });
                    $txt.val("");
                }
            },
            failure: function(errMsg) 
            {
                jQuery.alerts.dialogClass = 'alert-warning';
                    jAlert('Error', 'Perhatian', function(){
                           jQuery.alerts.dialogClass = null; // reset to default
                        });
                $txt.val("");
            }
        });
    }
    
    function openEdit(data)
    {
        jQuery("#txtId").val(data[0].id);
        
//        jQuery("#txtNamaDepartemen").val(data[0].nama);
//        jQuery("#txtDeskripsiDepartemen").val(data[0].keterangan);
        jQuery("#txtKodeLangganan").val(data[0].kodeLangganan);
        jQuery("#txtNamaDepanLangganan").val(data[0].namadepanLanggangan);
        jQuery("#txtNamaPtLangganan").val(data[0].namaptLangganan);
        jQuery("#txtAlamatLangganan").val(data[0].alamatLangganan);
        jQuery("#txtKotaLangganan").val(data[0].kotaLangganan);
        jQuery("#txtKodePosLangganan").val(data[0].kodeposLangganan);
        jQuery("#txtTelponLangganan").val(data[0].telponLangganan);
        jQuery("#txtHpLangganan").val(data[0].hpLangganan);
        jQuery("#txtFaxLangganan").val(data[0].faxLangganan);
        jQuery("#txtNamaOrangLangganan").val(data[0].namaorangLangganan);
        jQuery("#txtNoIdentitasLangganan").val(data[0].noidentitasLangganan);
        jQuery("#txtPkpLangganan").val(data[0].pkpLangganan);
        jQuery("#txtNpwpLangganan").val(data[0].npwpLangganan);
        
        jQuery('#mlanggananModal').modal('show');
        
        jQuery("#msg").html("");
        
    }
    
    function deleteData(id)
    {
        if(confirm("Apakah yakin akan menghapus data ini?"))
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
                }
            });
        }
    }
    
    function resetForm()
    {
        jQuery("#txtId").val("");
        jQuery("#txtKodeLangganan").val("");
        jQuery("#txtNamaDepanLangganan").val("");
        jQuery("#txtNamaPtLangganan").val("");
        jQuery("#txtAlamatLangganan").val("");
        jQuery("#txtKotaLangganan").val("");
        jQuery("#txtKodePosLangganan").val("");
        jQuery("#txtTelponLangganan").val("");
        jQuery("#txtHpLangganan").val("");
        jQuery("#txtFaxLangganan").val("");
        jQuery("#txtNamaOrangLangganan").val("");
        jQuery("#txtNoIdentitasLangganan").val("");
        jQuery("#txtPkpLangganan").val("");
        jQuery("#txtNpwpLangganan").val("");
        //jQuery("#").val("");
    }
</script>
<style>
th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: 100%;   
    }
</style>