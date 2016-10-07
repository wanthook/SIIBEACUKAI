<?php

/*
 * for value
 */

?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title"><i class="fa fa-lock"></i>&nbsp;&nbsp;<?php echo $this->lang->line('user_change_password_head'); ?></h4>
        </div>
        <form action="<?php echo $action; ?>" id="<?php echo $idForm; ?>">
        <div class="modal-body">
           
                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="txtCurrentPassword" class="col-sm-2 control-label"><?php echo $this->lang->line('user_change_password_prev'); ?></label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control  input-sm" id="txtCurrentPassword" name="txtCurrentPassword" autofocus="" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="txtNewPassword" class="col-sm-2 control-label"><?php echo $this->lang->line('user_change_password_new'); ?></label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control input-sm" id="txtNewPassword" name="txtNewPassword" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="txtConfirmPassword" class="col-sm-2 control-label"><?php echo $this->lang->line('user_change_password_conf'); ?></label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control input-sm" id="txtConfirmPassword" name="txtConfirmPassword" required="">
                        </div>
                    </div>
                </div>
            
            
        </div>
        <div class="modal-footer">
            <div id="info"></div>
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><?php echo $this->lang->line('user_change_password_cancel'); ?></button>
            <button type="submit" id="cmdAddSave" class="btn btn-primary btn-sm"><?php echo $this->lang->line('user_change_password_submit'); ?></button>
        </div>
        </form>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script>
$(document).ready(function()
{
//    $('#txtDate').datepicker({format:'dd-mm-yyyy'});
    $('#formChangePassword').submit(function(e)
    {
        e.preventDefault();
        $.post($(this).attr('action'),$(this).serialize())
            .done(function( data ) 
            {
                var datas = JSON.parse(data);
                if(datas.status==1)
                {
                    $('#info').html('<p class="text-success">'+datas.message+'</p>');
                    
                    setTimeout(function()
                    {
                        location.reload();
                    }, 3000);
                }
                else if(datas.status==0)
                {
                    $('#info').html('<p class="text-danger">'+datas.message+'</p>');
                }
                else
                {
                    $('#info').html('<p class="text-danger">Unknown Error</p>');
                }
            });
    });
});
</script>