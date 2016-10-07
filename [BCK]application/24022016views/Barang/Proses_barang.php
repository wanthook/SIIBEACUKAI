<?php

$controller = "Barang";

?>
    <!-- START RIGHT PANEL -->
    <div class="rightpanel">

        <!-- START NAVIGATOR -->
        <ul class="breadcrumbs">
            <li><a href="<?php echo site_url('Dashboard'); ?>"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li>Master Barang</li>            
        </ul><!-- END NAVIGATOR -->

        <!-- START PAGE HEADER -->
        <div class="pageheader">
            <!--<a href="" class="searchbar btn btn-primary" id="addButton"><i class="iconfa-plus-sign"></i>&nbsp;Add</a>-->
<!--            <form action="results.html" method="post" class="searchbar">
                <input type="text" name="keyword" placeholder="To search type and hit enter..." />
            </form>-->
            <div class="pageicon"><span class="iconfa-wrench"></span></div>
            <div class="pagetitle">
                <h5>Barang</h5>
                <h1>List Barang</h1>
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
                    <h4 class="widgettitle">Tabel Barang</h4>
                    <table id="tableId" class="table table-bordered responsive">
<!--                        <colgroup>
                            <col class="con0" />
                            <col class="con1" />
                            <col class="con0" />
                            <col class="con1" />
                            <col class="con0" />
                            <col class="con1" />
                            <col class="con0" />
                            <col class="con1" />
                            <col class="con0" />
                            <col class="con1" />
                            <col class="con0" />
                            <col class="con1" />
                            <col class="con0" />
                            <col class="con1" />
                            <col class="con0" />
                            <col class="con1" />
                            <col class="con0" />
                            <col class="con1" />
                        </colgroup>-->
                        <thead>
                            <tr>
                                <th class="head1">&nbsp;</th>
                                <th class="head1">Kode Barang</th>
                                <th class="head1">Nama Barang</th>
                                <th class="head1">Nama Barang 2</th>
                                <th class="head1">Harga</th>
                                <th class="head1">Lebar</th>
                                <th class="head1">Panjang</th>
                                <th class="head1">Berat</th>
                                <th class="head1">Warna</th>
                                <th class="head1">Grade</th>
                                <th class="head1">Unit</th>
                                <th class="head1">Satuan</th>
                                <th class="head1">Kategori</th>
                                <th class="head1">Tanggal Masuk</th>
                                <th class="head1">Grup</th>
                                <th class="head1">Kategori Ukuran</th>
                                <th class="head1">Kategori Harga</th>
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
<div aria-hidden="false" aria-labelledby="mbarangLabel" role="dialog" tabindex="-1" class="modal hide fade in" id="mbarangModal">
    <div class="modal-header">
        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
        <h3 id="mbarangLabel"><i class="iconfa-briefcase"></i>&nbsp;Master Barang</h3>
    </div>
    <div class="modal-body nopadding">
        <form id="frmBarang" class="stdform stdform2">
            <input type="hidden" id="txtId" name="txtId">
            <p>
                <label>Kode Barang</label>
                <span class="field">
                    <input type="text" id="txtKodeBarang" name="txtKodeBarang" autofocus=""/>
                </span>
            </p>
            <p>
                <label>Nama Barang</label>
                 <span class="field">
                     <input type="text" id="txtNamaBarang" name="txtNamaBarang" />
                 </span>
            </p>
            <p>
                <label>Nama Barang 2</label>
                 <span class="field">
                     <input type="text" id="txtNamaBarang2" name="txtNamaBarang2" />
                 </span>
            </p>
            <p>
                <label>Harga</label>
                 <span class="field">
                     <input type="text" id="txtHargaBarang" name="txtHargaBarang" />
                 </span>
            </p>
            <p>
                <label>Lebar</label>
                 <span class="field">
                     <input type="text" id="txtLebarBarang" name="txtLebarBarang" />
                 </span>
            </p>
            <p>
                <label>Panjang</label>
                 <span class="field">
                     <input type="text" id="txtPanjangBarang" name="txtPanjangBarang" />
                 </span>
            </p>
            <p>
                <label>Berat</label>
                 <span class="field">
                     <input type="text" id="txtBeratBarang" name="txtBeratBarang" />
                 </span>
            </p>
            <p>
                <label>Warna</label>
                 <span class="field">
                     <input type="text" id="txtWarnaBarang" name="txtWarnaBarang" />
                 </span>
            </p>
            <p>
                <label>Grade</label>
                 <span class="field">
                     <input type="text" id="txtGradeBarang" name="txtGradeBarang" />
                 </span>
            </p>
            <p>
                <label>Unit</label>
                 <span class="field">
                     <input type="text" id="txtUnitBarang" name="txtUnitBarang" />
                 </span>
            </p>
            <p>
                <label>Satuan</label>
                 <span class="field">
                     <input type="text" id="txtSatuanBarang" name="txtSatuanBarang" />
                 </span>
            </p>
            <p>
                <label>Kategori</label>
                 <span class="field">
                     <input type="text" id="txtKategoriBarang" name="txtKategoriBarang" />
                 </span>
            </p>
            <p>
                <label>Tanggal Masuk</label>
                 <span class="field">
                     <input type="text" id="txtTanggalmasukBarang" name="txtTanggalmasukBarang" />
                 </span>
            </p>
            <p>
                <label>Group</label>
                 <span class="field">
                     <input type="text" id="txtGroupBarang" name="txtGroupBarang" />
                 </span>
            </p>
            <p>
                <label>Kategori Ukuran</label>
                 <span class="field">
                     <input type="text" id="txtKategoriukuranBarang" name="txtKategoriukuranBarang" />
                 </span>
            </p>
            <p>
                <label>Kategori Harga</label>
                 <span class="field">
                     <input type="text" id="txtKategorihargaBarang" name="txtKategorihargaBarang" />
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
            
            jQuery('#mbarangModal').modal('show');
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
                { "data": "kodeBarang" },
                { "data": "namaBarang" },
                { "data": "nama2Barang" },
                { "data": "hargaBarang" },
                { "data": "lebarBarang" },
                { "data": "panjangBarang" },
                { "data": "BeratBarang" },
                { "data": "warnaBarang" },
                { "data": "gradeBarang" },
                { "data": "unitBarang" },
                { "data": "satuanBarang" },
                { "data": "kategoriBarang" },
                { "data": "tanggalmasukBarang" },
                { "data": "groupBarang" },
                { "data": "kategoriukuranBarang" },
                { "data": "kategorihargaBarang" }
                
            ]
        });
        
        jQuery('#mbarangModal').on('show.bs.modal', function () {
            jQuery("#txtKodeBarang").focus();
        });
        
        jQuery('#mbarangModal').on('hide.bs.modal', function () {
            
            jQuery("#msg").html("");
            //resetForm();
//            console.log('masa kesini');
            //document.getElementById("frmScan").reset();

        });
        
        jQuery('#txtTanggalmasukBarang').datepicker(
        {
            format:'dd-mm-yyyy'
        });
        
        jQuery('#frmBarang').on('submit',function(e)
        {
            e.preventDefault();
            console.log('submit');
//            console.log($table);
            if(jQuery('#txtKodeBarang').val()!="" && jQuery('#txtNamaBarang').val()!="" && jQuery('#txtTanggalmasukBarang').val()!="")
            {
                postData();
            }
            else
            {
                jQuery.alerts.dialogClass = 'alert-warning';
                jAlert('Kode Barang, Nama Barang, dan Tanggal Barang harus diisi terlebih dahulu.', 'Perhatian', function(){
                       jQuery.alerts.dialogClass = null; // reset to default
                    });

                jQuery("#txtKodeBarang").focus();
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
        $txt = jQuery('#txtKodeBarang');
        jQuery.ajax({
            type: "POST",
            url: "<?php echo site_url($controller.'/proc'); ?>",
            data: jQuery('#frmBarang').serialize(),
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
        jQuery("#txtKodeBarang").val(data[0].kodeBarang);
        jQuery("#txtNamaBarang").val(data[0].namaBarang);
        jQuery("#txtNamaBarang2").val(data[0].nama2Barang);
        jQuery("#txtHargaBarang").val(data[0].hargaBarang);
        jQuery("#txtLebarBarang").val(data[0].lebarBarang);
        jQuery("#txtPanjangBarang").val(data[0].panjangBarang);
        jQuery("#txtBeratBarang").val(data[0].BeratBarang);
        jQuery("#txtWarnaBarang").val(data[0].warnaBarang);
        jQuery("#txtGradeBarang").val(data[0].gradeBarang);
        jQuery("#txtUnitBarang").val(data[0].unitBarang);
        jQuery("#txtSatuanBarang").val(data[0].satuanBarang);
        jQuery("#txtKategoriBarang").val(data[0].kategoriBarang);
        jQuery("#txtTanggalmasukBarang").val(data[0].tanggalmasukBarang);
        jQuery("#txtGroupBarang").val(data[0].groupBarang);
        jQuery("#txtKategoriukuranBarang").val(data[0].kategoriukuranBarang);
        jQuery("#txtKategorihargaBarang").val(data[0].kategorihargaBarang);
        
        jQuery('#mbarangModal').modal('show');
        
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
        jQuery("#txtKodeBarang").val("");
        jQuery("#txtNamaBarang").val("");
        jQuery("#txtNamaBarang2").val("");
        jQuery("#txtLebarBarang").val("");
        jQuery("#txtPanjangBarang").val("");
        jQuery("#txtBeratBarang").val("");
        jQuery("#txtWarnaBarang").val("");
        jQuery("#txtGradeBarang").val("");
        jQuery("#txtUnitBarang").val("");
        jQuery("#txtSatuanBarang").val("");
        jQuery("#txtKategoriBarang").val("");
        jQuery("#txtTanggalmasukBarang").val("");
        jQuery("#txtGroupBarang").val("");
        jQuery("#txtKategoriukuranBarang").val("");
        jQuery("#txtKategorihargaBarang").val("");
        //jQuery("#").val("");
    }
</script>
<style>
th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: 100%;   
    }
</style>