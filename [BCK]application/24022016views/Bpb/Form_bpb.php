<?php

$controller = "Bpb";

$nFaktur = "";

if(isset($faktur))
{
    $nFaktur = $faktur;
}

?>
    <!-- START RIGHT PANEL -->
    <div class="rightpanel">

        <!-- START NAVIGATOR -->
        <ul class="breadcrumbs">
            <li><a href="<?php echo site_url('Dashboard'); ?>"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li><a href="<?php echo site_url('Bpb'); ?>">BPB</a> <span class="separator"></span></li>
            <li>Form BPB</li>
        </ul><!-- END NAVIGATOR -->

        <!-- START PAGE HEADER -->
        <div class="pageheader">
            <!--<a href="" class="searchbar btn btn-primary" id="addButton"><i class="iconfa-plus-sign"></i>&nbsp;Add</a>-->
<!--            <form action="results.html" method="post" class="searchbar">
                <input type="text" name="keyword" placeholder="To search type and hit enter..." />
            </form>-->
            <div class="pageicon"><span class="iconfa-shopping-cart"></span></div>
            <div class="pagetitle">
                <h5>BPB</h5>
                <h1>Form BPB</h1>
            </div>
        </div><!-- END PAGE HEADER -->		

        <!-- START MAIN CONTENT -->
        <div class="maincontent">
            <!-- START MAIN CONTAINER -->
            <div class="maincontentinner">
                
                <div class="widget">
                    <h4 class="widgettitle">Form BPB</h4>
                    <div class="widgetcontent">
                        <form id="frmInput" class="stdform stdform2" action="<?php echo site_url('Bpb/proc'); ?>" method="POST">
                            <input type="hidden" id="txtId" name="txtId">
                            <div class="row-fluid">
                                <div class="span6">                                    
                                    <p>
                                        <label>No. Faktur *</label>
                                        <span class="field">
                                            <input type="text" id="txtNoFaktur" name="txtNoFaktur" value="<?php echo $nFaktur; ?>" readonly=""/>
                                        </span>
                                    </p>
                                    <p>
                                        <label>No. Bukti *</label>
                                        <span class="field">
                                            <input type="text" id="txtNoBukti" name="txtNoBukti" autofocus="" required=""/>
                                        </span>
                                    </p>
                                    <p>
                                        <label>No. PO *</label>
                                        <span class="field">
                                            <input type="text" id="txtNoPo" name="txtNoPo" required=""/>
                                        </span>
                                    </p>
                                    <p>
                                        <div class="par">
                                            <label>Tanggal BPB *</label>
                                            <span class="field input-append">
                                                <!--<div class="input-append">-->
                                                <input type="text" id="txtTanggalBpb" name="txtTanggalBpb" style="width:90%;" required=""/>
                                                    <span class="add-on"><i class="iconfa-calendar"></i></span>
                                                <!--</div>-->
                                            </span>
                                        </div>
                                    </p>
                                    
                                </div> <!--end span-->
                                <div class="span6">
                                    <p>
                                        <label>Sales</label>
                                        <span class="field">
                                            <input type="text" id="txtSales" name="txtSales" style="width:100%;"/>
                                        </span>
                                    </p>
                                    <p>
                                        <label>Langganan</label>
                                        <span class="field">
                                            <input type="text" id="txtLangganan" name="txtLangganan" style="width:100%;"/>
                                        </span>
                                    </p>
                                    <p>
                                        <label>Kriteria</label>
                                        <span class="field">
                                            <input type="text" id="txtKriteria" name="txtKriteria" style="width:100%;"/>
                                        </span>
                                    </p>
                                    <p>                                        
                                        <label>Syarat Penjualan</label>
                                        <span class="field">
                                            <input type="text" id="txtSyarat" name="txtSyarat"/>
                                        </span>
                                        <small class="desc" id="msgSyarat">&nbsp;</small>
                                    </p>
                                </div><!--end span6-->
                            </div><!--end row-fluid-->                            
                        <!--end form-->
                        <div id="msg"></div>
                        <button class="btn btn-info" id="cmdAdd"><span class="iconfa-plus"></span>&nbsp;Tambah Detail</button>
                        <button type="submit" class="btn btn-primary pull-right" id="cmdSave"><span class="iconfa-save"></span>&nbsp;Simpan</button>
                        <table id="tableId" class="table table-bordered responsive">
                            
                            <thead>
                                <tr>
                                    <th class="head0">&nbsp;</th>
                                    <th class="head1">Kode Barang</th>
                                    <th class="head0">Unit</th>
                                    <th class="head1">Harga Satuan</th>
                                    <th class="head0">Diskon (%)</th>
                                    <th class="head1">Jumlah</th>
                                </tr>
                            </thead>
                    </table>
                        </form>
                    </div><!--end widgetcontent-->
                </div><!--end widget-->
                
                
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


<script type="text/javascript">
    var $table  = null;
    
    var sales  =[
            {'id':'KAN', 'text':'KANTOR'}
        ];
        
    var kriteria  =[
            {'id':'KN', 'text':'KN - KAIN/BAHAN'},
            {'id':'AF', 'text':'AF - AFALAN'},
            {'id':'IJ', 'text':'IJ - LAIN-LAIN'}
        ];
    
    jQuery(document).ready(function() 
    {
        
//        jQuery("#commentForm").validate();
        
        jQuery('#txtTanggalBpb').datepicker(
        {
            format:'dd-mm-yyyy'
        });
        
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
        
        jQuery('#txtSyarat').on('keyup','',function(e)
        {
            //console.log($(this).val());
            
            var value   = jQuery('#txtSyarat').val();
            
            
            jQuery('#msgSyarat').text(msgSyarat(value));
        });
        
        jQuery('#tableId').on('keyup','.txtHargaSatuan,.txtUnit,.txtDiskon',function(e)
        {
            var test = $table.$(this).closest('tr');
            var indx = test.index();
            
            var hSatuan = jQuery('#txtHargaSatuan'+indx).val();
            var hUnit   = jQuery('#txtUnit'+indx).val();
            var hDisc   = jQuery('#txtDiskon'+indx).val();
            
            var hasil   = (hSatuan*hUnit)-((hSatuan*hUnit)*(hDisc/100));
            
            jQuery('#txtJumlah'+indx).val(hasil.toFixed(2));
        });
        
        jQuery('#txtLangganan').select2({
            placeholder: "Langganan",
            minimumInputLength:0,
            ajax: 
            {
                url: '<?php echo site_url('Langganan/sLangganan'); ?>',
                dataType: 'json',         
                data: function (term, page) 
                {                
                    return { q : term  }
                },
                results: function(data, page ) 
                {
                    return { results: data }
                }
            },
            initSelection: function(element, callback) 
            {
                var id = $(element).val();

                if(id!="")
                {
                    $.ajax( 
                    {                    
                        url: '<?php echo site_url('Langganan/sLangganan'); ?>',
                        dataType: 'json',
                        data: {id: id}
                    }).done(function(data){ callback(data[0]); });
                }
            }
        });
        
        $table = jQuery('#tableId').DataTable( {
            "paging":false,
            "searching":false,
            "scrollY": 300,
            "scrollX": true
//            "deferRender": true,
//            "ajax": "<?php echo site_url($controller.'/table'); ?>",
//            "columns": 
//            [                
//                {
//                    sortable: false,
//                    "render": function ( data, type, full, meta ) 
//                    {
//                        return '<button class="btn btn-sm" onClick="showModal('+full.id+')" role="button"><span class="iconfa-pencil"></span></button>&nbsp;' +
//                               '<button class="btn btn-sm btn-danger" onClick="deleteData('+full.id+')" role="button"><span class="iconfa-minus-sign"></span></button>';
//                    }
//                },
//                { "data": "nofakturBpb" },
//                { "data": "nobuktiBpb" },
//                { "data": "nopoBpb" },
//                { "data": "tanggalBpb" },
//                { "data": "kriteriaBpb" },
//                { "data": "langganankodeBpb" },
//                { "data": "langganannamadepanBpb" },
//                { "data": "jumlahBpb" }
//                
//            ]
        });
        
        jQuery('#cmdSave').on('click',function(e)
        {
            e.preventDefault();
            if(jQuery("#frmInput").valid())
            {
                postData();
            }
        });
        
        jQuery('#txtSales').select2(
        {
            placeholder         : "Sales",
            minimumInputLength  : 0,
            data                : {results : sales}
        });
        
        jQuery('#txtKriteria').select2(
        {
            placeholder         : "Kriteria",
            minimumInputLength  : 0,
            data                : {results : kriteria}
        });            
        
        var counter = 0;
        jQuery('#cmdAdd').on('click',function(e)
        {
            e.preventDefault();
            
            $table.row.add( [
                '',
                '<input type="text" name="txtKodeBarang[]" id="txtKodeBarang'+counter+'" class="txtKodeBarang">',
                '<input type="text" name="txtUnit[]" id="txtUnit'+counter+'" class="txtUnit">',
                '<input type="text" name="txtHargaSatuan[]" id="txtHargaSatuan'+counter+'" class="txtHargaSatuan">',
                '<input type="text" name="txtDiskon[]" id="txtDiskon'+counter+'" class="txtDiskon" value="0">',
                '<input type="text" name="txtJumlah[]" id="txtJumlah'+counter+'" class="txtJumlah" readonly>'
            ] ).draw();

            
            
            jQuery('#txtKodeBarang'+counter).select2({
                placeholder: "Kode Barang",
                minimumInputLength:0,
                ajax: 
                {
                    url: '<?php echo site_url('Barang/sBarang'); ?>',
                    dataType: 'json',         
                    data: function (term, page) 
                    {                
                        return { q : term  }
                    },
                    results: function(data, page ) 
                    {
                        return { results: data }
                    }
                },
                initSelection: function(element, callback) 
                {
                    var id = $(element).val();

                    if(id!="")
                    {
                        $.ajax( 
                        {                    
                            url: '<?php echo site_url('Barang/sBarang'); ?>',
                            dataType: 'json',
                            data: {id: id}
                        }).done(function(data){ callback(data[0]); });
                    }
                }
            });
            
            
            jQuery('#txtKodeBarang'+counter).on("select2-selecting", function(e) 
            {
                var harga = e.object.harga;
                var test = $table.$(this).closest('tr');
                var indx = test.index();

                jQuery('#txtHargaSatuan'+indx).val(harga);
            });
            
            counter++;
        });
    });
    
    function msgSyarat(val)
    {
        var msg     = "";
        
        if(jQuery.isNumeric(val))
        {
            var abs = Math.abs(val);
            if(abs>0)
            {
                msg     = "Kredit "+abs+" hari.";
            }
            else
            {
                msg     = "Kontan";
            }
        }
        else if(val=="")
        {
            msg         = "";
        }
        else
        {
            msg     = "Nilai yang dimasukkan, salah";
        }
        
        return msg;
    }
    
    function postData()
    {
        //console.log(jQuery('#txtEfaktur').val());
//        $txt = jQuery('#txtKodeBarang');
        jQuery.ajax({
            type: "POST",
            url: jQuery('#frmInput').attr('action'),
            data: jQuery('#frmInput').serialize(),
            dataType: "json",
            success: function(data)
            {
                if(data.status==1)
                {
                    jQuery.alerts.dialogClass = 'alert-warning';
                    jAlert(data.msg, 'Perhatian', function(){
                        jQuery.alerts.dialogClass = null; // reset to default
                    });
                    window.setTimeout(function(){getBack()}, 3000);
//                    jQuery('#msg').html('<span class="label label-success">'+data.msg+'</span>');
//                        
//                    if($table!=null)
//                    {
//                        window.setTimeout(function(){getBack()}, 3000);
//                        //$table.ajax.reload();
//                    }
                    
                    //$txtEFaktur.val("");
                }
                else if(data.status==0)
                {
                    jQuery.alerts.dialogClass = 'alert-warning';
                    jAlert(data.msg, 'Perhatian', function(){
                           jQuery.alerts.dialogClass = null; // reset to default
                        });
//                    $txt.val("");
                }
            },
            failure: function(errMsg) 
            {
                jQuery.alerts.dialogClass = 'alert-warning';
                    jAlert('Error', 'Perhatian', function(){
                           jQuery.alerts.dialogClass = null; // reset to default
                        });
//                $txt.val("");
            }
        });
    }
    
    function getBack()
    {
        location.replace("<?php echo site_url("Bpb"); ?>");
    }
</script>
<style>
/*th, td { white-space: nowrap; }*/
    div.dataTables_wrapper {
        width: 100%;   
    }
</style>