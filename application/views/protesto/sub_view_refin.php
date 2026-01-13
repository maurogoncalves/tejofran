<?php
								$attributes = array('class' => 'form-horizontal style-form','id' => 'form' ,'enctype' => 'multipart/form-data');
								echo form_open('protesto/cadastrar_pefin', $attributes); 
								?>
                                <div class="panel-body">
                                 <form class="form-horizontal">
								 
								 <?php 								 
									if($op == 1){
										$valor['contrato'] = $dados[0]->contrato;
										$valor['modalidade'] = $dados[0]->modalidade;
										$valor['empresa'] = $dados[0]->credor_favorecido;
										$valor['data_protesto_br'] = $dados[0]->data_protesto_br;
										$valor['valor_protestado'] = $dados[0]->valor_protestado;
										$valor['avalista'] = $dados[0]->avalista;
										$valor['local'] = $dados[0]->local;
										$valor['cnpj_raiz'] = $dados[0]->id_cnpj_raiz_segundo;
										$valor['idOco'] = $dados[0]->idOco;
										
										
										
									}else{
										$valor['idOco'] = 0;
										$valor['cnpj_raiz']= $valor['local'] =  $valor['avalista'] = $valor['valor_protestado'] = $valor['contrato'] = $valor['modalidade'] = $valor['empresa'] = $valor['data_protesto_br'] ='';
									}
								 
								 ?>
				
								<div class="form-group" >
									<label class="col-lg-12 control-label" style='font-size:16px;color:#002060;font-weight:bold;text-align:left!important'>Dados Refin</label>
                                </div>
                                <div class="form-group" >
									<label class="col-lg-2 control-label">Encontre o CNPJ Raiz</label>
                                    <div class="col-lg-4">
									<input type="hidden"  id='tipo_ocorrencia' required  name='tipo_ocorrencia' value='2'   >
									<input type="hidden"  id='op' required  name='op' value='<?php echo $op ?>'   >
									<input type="hidden"  id='cnpj_bd' required  name='cnpj_bd' value='1' value='<?php echo $valor['cnpj_raiz'] ?>'  >
									<input type="hidden"  id='idOco' required  name='idOco' value='<?php echo $valor['idOco'] ?>'  >
										<select name="cnpj_raiz" tabindex="1"  required class='form-control' required="">
											<?php foreach($cnpj_raiz as $raiz){
												if($valor['cnpj_raiz'] == $raiz->id ){
													echo"<option value=".$raiz->id." selected>".$raiz->cnpj_raiz."</option>";
												}else{
													echo"<option value=".$raiz->id.">".$raiz->cnpj_raiz."</option>";
												}
												
											}?>
										</select>	
									</div>
									<label class="col-sm-2 control-label">Contrato</label>
                                    <div class="col-lg-4">
                                      	<input type="text" tabindex="2" id='contrato' name='contrato' value='<?php echo $valor['contrato'] ?>' required class="form-control"     >
                                    </div>	
									
								
                                </div>

							
							

								<div class="form-group">													
									
									<label class="col-sm-2 control-label">Modalidade</label>
                                    <div class="col-lg-4">
                                      	<input type="text" tabindex="3" id='modalidade' name='modalidade' required class="form-control" value='<?php echo $valor['modalidade'] ?>'    >
                                    </div>	
									
									<label class="col-sm-2 control-label">Empresa Credora</label>
                                    <div class="col-lg-4">
                                      	<input type="text" tabindex="4" id='empresa' name='empresa' value='<?php echo $valor['empresa'] ?>' required class="form-control"     >
                                    </div>	
									
									
                                </div>
								
								<div class="form-group">													
									<label class="col-sm-2 control-label">Data</label>
                                    <div class="col-lg-4">
                                      	<input type="text" data-masked="" required data-inputmask="'mask': '99/99/9999' " id='dataRefin' name='dataPefin' tabindex="5" class="form-control" value='<?php echo $valor['data_protesto_br'] ?>'   >
                                    </div>	
									<label class="col-sm-2 control-label">Valor</label>
                                    <div class="col-lg-4">
                                      	<input type="text" required id='valorProtestado' data-a-dec="," data-a-sep="." tabindex="6" name='valorProtestado' class="auto  form-control"  value='<?php echo $valor['valor_protestado'] ?>'  >  
                                    </div>	
									
									
									
                                </div>
								
								<div class="form-group">
								
									<label class="col-lg-2 control-label">Avalista</label>
                                    <div class="col-lg-4">
									<input type="text"  id='avalista' required name='avalista' tabindex="7" class="form-control"  value='<?php echo $valor['avalista'] ?>' >
                                    </div>	
									
									<label class="col-lg-2 control-label">Local</label>
                                    <div class="col-lg-4">
									<input type="text"  id='local' required  name='local' tabindex="8" class="form-control" value='<?php echo $valor['local'] ?>'  >
                                    </div>	
									
									
                                </div>
								
								
								<div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <div class="col-sm-4 col-sm-offset-2">
										<button class="btn btn-white" type="submit">Cancelar</button>
										<button class="btn btn-primary" type="submit">Salvar/Enviar</button>
                                    </div>
                                </div>
                            </form>

                                </div>