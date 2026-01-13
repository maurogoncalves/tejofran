<script src="<?php echo $this->config->base_url(); ?>assets/js/jquery.js"></script><script src="<?php echo $this->config->base_url(); ?>assets/js/jquery-ui-1.9.2.custom.min.js"></script><script type="text/javascript" src="<?php echo $this->config->base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script><script src="<?php echo $this->config->base_url(); ?>assets/js/bootstrap-editable.min.js"></script><script src="<?php echo $this->config->base_url(); ?>assets/js/moment-with-locales.min.js"></script>		<script type="text/javascript">	$(document).ready(function(){	
	$( "#export" ).click(function() {		var cnpj = $('#cnpj').val();				$("#cnpjExp").val(cnpj);		$("#exportForm").submit();	});
});		
</script>		<div id="wrapper">                <div class="content-wrapper container">                    <div class="row">                        <div class="col-sm-12">                            <div class="page-title">                                <h1>Cnpjs <small>	</small></h1>                                <ol class="breadcrumb">                                                                        <li class="active">Lista de Cnpjs </li>                                </ol>                            </div>                        </div>                    </div><!-- end .page title-->                    <div class="row">                        <div class="col-md-12">                            <div class="panel panel-card ">                                <!-- Start .panel -->                                <div class="panel-heading">                                    <h4 class="panel-title"> 									<a href="<?php echo $this->config->base_url();?>index.php/cnpj/cadastrarCnpj"> <i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Novo CNPJ</a> 									&nbsp;				  																		<a id='export' href="#"><i class="fa fa-file-excel-o"></i> Exportar para Excel</a>	&nbsp;									</h4>									<br>		                                    <div class="panel-actions">
                                        <a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
                                        <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
                                    </div>
                                </div>
                                 <div class="panel-body">
                                    <table id="basic-datatables" class="table table-bordered" cellspacing="0" width="100%">                                        <thead>                                            <tr>												  <th > CNPJ Raiz</th>												  <th > CNPJ </th>												  <th > Estabelecimento </th>												  <th > Bandeira </th>												  <th > A&ccedil;&otilde;es</th>								  
                                            </tr>
                                        </thead>
                                        <tfoot>                                            <tr>
												<th > CNPJ Raiz</th>												<th > CNPJ </th>												  <th > Estabelecimento </th>												  <th > Bandeira </th>												<th >A&ccedil;&otilde;es</th>								  
                                            </tr>
                                        </tfoot>
                                        <tbody>
										<?php 		
										$isArray =  is_array($cnpjs) ? '1' : '0';			
										if(!$isArray == 0){											 foreach($cnpjs as $key => $emitente){ 											 ?>
												 <tr>
												 <td ><?php echo $emitente->cnpj_raiz; ?></td>																								<td ><?php echo $emitente->cnpj; ?></td>													 												<td ><?php echo $emitente->nome; ?></td>												<td ><?php echo $emitente->descricao_bandeira; ?></td>													<td >												<a href="<?php echo $this->config->base_url();?>index.php/cnpj/editarCnpj?id=<?php echo $emitente->id; ?>" class="btn"><i class="fa fa-pencil"></i></a>												<a href="<?php echo $this->config->base_url();?>index.php/cnpj/listarInscricao?idCnpj=<?php echo $emitente->id; ?>&tipo=2" class="btn"><i  title='Inscri&ccedil;&otilde;es Estaduais' class="fa fa-file-o"></i></a>												<a href="<?php echo $this->config->base_url();?>index.php/cnpj/listarInscricao?idCnpj=<?php echo $emitente->id; ?>&tipo=1" class="btn"><i title='Inscri&ccedil;&otilde;es Mobili&aacute;rias' class="fa fa-file"></i></a>												</td>													
												</tr>
										 <?php
											}//fim foreach
										  }//fim if
										  ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- End .panel -->  
                        </div><!--end .col-->
                    </div><!--end .row-->                </div> 
            </div><form  id='exportForm' action='<?php echo $this->config->base_url(); ?>index.php/cnpj/exportCnpj' method='post'>							<input type='hidden' id='estadoExp' name='estado'>	<input type='hidden' id='cidadeExp' name='cidade' >		<input type='hidden' id='cnpjExp' name='cnpjRaiz' value=''>				  						  		  						  </form>
