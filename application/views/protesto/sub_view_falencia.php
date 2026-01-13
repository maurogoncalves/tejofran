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
									<label class="col-lg-12 control-label" style='font-size:16px;color:#002060;font-weight:bold;text-align:left!important'>Dados</label>
                                </div>
                                <div class="form-group" >
									<label class="col-lg-2 control-label">Encontre o CNPJ Raiz</label>
                                    <div class="col-lg-4">
									<input type="hidden"  id='op'   name='op' value='<?php echo $op ?>'   >
									<input type="hidden"  id='cnpj_bd_falencia'  name='cnpj_bd' value='<?php echo $valor['cnpj_raiz'] ?>'  >
									<input type="hidden"  id='idOco'   name='idOco' value='<?php echo $valor['idOco'] ?>'  >
									
									<input type="hidden"  id='tipo_ocorrencia'   name='tipo_ocorrencia' value='6'   >
									<input type="hidden" id='contrato' name='contrato'  value=''       >
										<input type="hidden"  name='modalidade'  value=''      >
										<input type="hidden"  name='dataPefin'  value=''    >
										<input type="hidden"  name='valorProtestado'  value=''    >  
										<input type="hidden"  name='avalista'value=''     >
										<input type="hidden"  name='local'   value=''   >
										
										
										
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
									
									<label class="col-sm-2 control-label">Empresa Credora</label>
                                    <div class="col-lg-4">
                                      	<input type="text" tabindex="4" id='empresa' name='empresa' value='<?php echo $valor['empresa'] ?>' required class="form-control"     >
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