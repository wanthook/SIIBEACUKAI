<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

        <!-- Latest compiled and minified JQuery -->
        <script src="<?php echo base_url();?>assets/js/jquery-1.9.1.js"></script>
        <!--<script src="<?php // echo base_url();?>assets/js/jquery-2.1.1.min.js"></script>-->
        
        <!-- Latest compiled and minified JQuery UI -->
        <!--<script src="<?php echo base_url();?>assets/js/jquery-ui.min.js"></script>-->
        
        <!-- Latest compiled and minified JavaScript -->
        <script src="<?php echo base_url();?>assets/bootstrap/js/bootstrap.min.js"></script>
        
        <script src="<?php echo base_url();?>assets/js/plugins/bootstrap3-dialog-master/src/js/bootstrap-dialog.js"></script>
        
        <!-- Metis Menu Plugin JavaScript -->
        <!--<script src="<?php echo base_url();?>assets/js/plugins/metisMenu/metisMenu.min.js"></script>-->

        <!-- Morris Charts JavaScript -->
<!--        <script src="<?php echo base_url();?>assets/js/plugins/morris/raphael.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/plugins/morris/morris.min.js"></script>-->
        
        <!-- DataTables Plugin JavaScript -->
        <script src="<?php echo base_url();?>assets/datatables/js/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url();?>assets/datatables/js/dataTables.bootstrap.js"></script>
        
        <script src="<?php echo base_url();?>assets/js/jquery.mask.js"></script>
        
        <script src="<?php echo base_url();?>assets/js/select2.js"></script>
        
        <script src="<?php echo base_url();?>assets/js/bootstrap-datepicker.js"></script>
        
        <!--<script src="<?php echo base_url();?>assets/js/plugins/webcamjs-master/webcam.min.js"></script>-->
        
        <script src="<?php echo base_url();?>assets/js/plugins/jquery.fileDownload-master/src/Scripts/jquery.fileDownload.js"></script>
        
        <!--<script src="<?php echo base_url();?>assets/bootstrap-fileinput/js/fileinput.js"></script>-->
        
        <script src="<?php echo base_url();?>assets/js/jquery.form.js"></script>
        
        <script src="<?php echo base_url();?>assets/jquery-cookie/src/jquery.cookie.js"></script>
        
        <script src="<?php echo base_url();?>assets/js/wanthook.js"></script>
        
        <script src="<?php echo base_url();?>assets/js/plugins/tinymce/tinymce.min.js"></script>
        
        <script>
        $(function()
        {
            $("#changePassword").click(function(e)
            {
                e.preventDefault();

                $("#modalChangePassword").remove();

                var categoryModal = $('<div id="modalChangePassword" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalAdd" aria-hidden="true"></div>');

                $.post('<?php echo site_url('User/form_change_password'); ?>',
                    function(response)
                    {
                        categoryModal.html(response);
                        var modal = categoryModal.modal('show');
                        modal.find('#id').val('<?php echo $this->uri->segment(3); ?>');
                    });

            });
        });
        </script>