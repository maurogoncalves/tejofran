<script src="<?php echo $this->config->base_url(); ?>assets/js/jquery.js"></script><script src="<?php echo $this->config->base_url(); ?>assets/js/jquery-ui-1.9.2.custom.min.js"></script><script type="text/javascript" src="<?php echo $this->config->base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script><script src="<?php echo $this->config->base_url(); ?>assets/js/bootstrap-editable.min.js"></script><script src="<?php echo $this->config->base_url(); ?>assets/js/moment-with-locales.min.js"></script>		<?php $base = base_url();?><script type="text/javascript">	$(document).ready(function(){					
});		
</script>		<div id="wrapper">                <div class="content-wrapper container">                    <div class="row">                        <div class="col-sm-12">                            <div class="page-title">                                <h1>Atividades <small>	</small></h1>                                <ol class="breadcrumb">                                                                        <li class="active">Lista de Atividades </li>                                </ol>                            </div>                        </div>                    </div><!-- end .page title-->                    <div class="row">                        <div class="col-md-12">                            <div class="panel panel-card ">                                <!-- Start .panel -->                                <div class="panel-heading">                                   									                                    <div class="panel-actions">
                                        <a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
                                        <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
                                    </div>
                                </div>
                                 <div class="panel-body">
                                    <table id="basic-datatables" class="table table-bordered protesto" cellspacing="0" width="100%" style='font-size:10px!important'>                                        <thead>                                            <tr>												  <th width='5%'> O que fez</th>												  <th width='14%'> Aonde fez</th>												  <th width='22%'> Quem fez </th>												  <th width='30%'> Quando fez </th>                                            </tr>
                                        </thead>
                                        <tfoot>                                            <tr>
												  <th width='5%'> O que fez</th>												  <th width='14%'> Aonde fez</th>												  <th width='22%'> Quem fez </th>												  <th width='30%'> Quando fez </th>                                            </tr>					  
                                            </tr>
                                        </tfoot>
                                        <tbody>
										<?php 	foreach($dados as $key => $emitente){ 																																															?>
												<tr style='font-size:10px!important' id='tr-<?php echo $emitente->id; ?>'>
												<td ><?php echo $emitente->oque; ?></td>																								<td ><?php echo $emitente->aonde; ?></td>													 												<td ><?php echo $emitente->quem; ?></td>												<td ><?php echo $emitente->quando; ?></td>																																				
												</tr>
										 <?php }//fim foreach  ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- End .panel -->  
                        </div><!--end .col-->
                    </div><!--end .row-->                </div> 
            </div>