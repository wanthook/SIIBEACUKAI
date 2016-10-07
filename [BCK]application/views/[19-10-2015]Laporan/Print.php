<html>
    <head>
        <title><?php echo "$laporan \nPeriode ".$tanggal_awal." s/d ".$tanggal_awal; ?></title>
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css" type="text/css" />
    </head>
    <body>
        <div class="table-responsive">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 20%;"><img src="<?php echo base_url(); ?>assets/images/LogoSpinmill.png" /></td>
                    <td>&nbsp;</td>
                    <td><h2><?php echo $laporan;?></h2><h4>Periode:&nbsp;<?php echo $tanggal_awal." S/D ".$tanggal_akhir; ?></h4></td>
                </tr>
                <tr>
                    <td colspan="3"><hr></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <?php echo $tabel; ?>                    
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <h5>&copy; <?php echo date("Y"); ?>. <?php echo $this->lang->line('lang_app_name')." - ".$this->lang->line('lang_app_company_name'); ?> All Rights Reserved.</h5>                  
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>