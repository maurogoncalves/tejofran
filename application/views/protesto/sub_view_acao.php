<?php
								$attributes = array('class' => 'form-horizontal style-form','id' => 'form' ,'enctype' => 'multipart/form-data');
								echo form_open('protesto/cadastrar_protesto_acao', $attributes); 
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
										$valor['distribuicao'] = $dados[0]->distribuicao;
										$valor['vara'] = $dados[0]->vara;
										$valor['id_natureza'] = $dados[0]->id_natureza;
										$valor['uf'] = $dados[0]->idEstado;
										$valor['cidade'] = $dados[0]->idCidade;										
									}else{
										$valor['idOco'] = 0;
										$valor['id_natureza'] = 0;
										$valor['cidade'] = $valor['uf'] =  $valor['vara'] = $valor['distribuicao'] = $valor['cnpj_raiz']= $valor['local'] =  $valor['avalista'] = $valor['valor_protestado'] = $valor['contrato'] = $valor['modalidade'] = $valor['empresa'] = $valor['data_protesto_br'] ='';
									}
								 
								 ?>
								 
								<div class="form-group" >
									<label class="col-lg-12 control-label" style='font-size:16px;color:#002060;font-weight:bold;text-align:left!important'>Dados Ações Judiciais</label>
                                </div>
                                <div class="form-group" >
									<label class="col-lg-2 control-label">Encontre o CNPJ Raiz</label>
                                    <div class="col-lg-4">
									<input type="hidden"  id='tipo_ocorrencia' required  name='tipo_ocorrencia' value='5'   >
									<input type="hidden" id='empresa' name='empresa'     >
									<input type="hidden"  id='op' required  name='op' value='<?php echo $op ?>'   >
									<input type="hidden"  id='cnpj_bd' required  name='cnpj_bd'  value='<?php echo $valor['cnpj_raiz'] ?>'  >
									<input type="hidden"  id='natureza_bd' required  name='natureza_bd'  value='<?php echo $valor['id_natureza'] ?>'  >
									<input type="hidden"  id='uf_bd' required  name='uf_bd'  value='<?php echo $valor['uf'] ?>'  >
									<input type="hidden"  id='cidade_bd' required  name='cidade_bd'  value='<?php echo $valor['cidade'] ?>'  >
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
									
									<label class="col-sm-2 control-label">Natureza</label>
                                    <div class="col-lg-4">
                                      	<select name="natureza" tabindex="2" id="naturezaAcao"  class='form-control' required=""></select>	
                                    </div>		
									
								
									
                                </div>

								
								<div class="form-group">													
									<label class="col-sm-2 control-label">Distribuição</label>
                                    <div class="col-lg-2">
                                      	<input type="text" tabindex="3" id='distribuicao' name='distribuicao' class="form-control" value='<?php echo $valor['distribuicao'] ?>'    >
                                    </div>	
									<label class="col-sm-2 control-label">Vara</label>
                                    <div class="col-lg-2">
                                      	<input type="text" tabindex="4" id='vara' name='vara' class="form-control"    value='<?php echo $valor['vara'] ?>'   >
                                    </div>	
									
									<label class="col-lg-2 control-label">Data</label>
                                    <div class="col-lg-2">
									<input type="text" data-masked="" data-inputmask="'mask': '99/99/9999' " id='dataAcao' name='dataAcao' tabindex="6" class="form-control"  value='<?php echo $valor['data_protesto_br'] ?>'   >
                                    </div>
									
									
									
									
                                </div>
								
								<div class="form-group">
									
									<label class="col-lg-2 control-label">Valor </label>
                                    <div class="col-lg-2">
									<input type="text"  id='valorProtestado' data-a-dec="," data-a-sep="." tabindex="7" name='valorProtestado' class="auto  form-control"  value='<?php echo $valor['valor_protestado'] ?>'   >  
                                    </div>
									
									<label class="col-lg-2 control-label">Escolha o Estado</label>
                                    <div class="col-lg-2">
									<select name="estado" tabindex="5" id="estadoAcao"  class='form-control ' required="">
									
									</select>	
									</div>
									
									<label class="col-lg-2 control-label">Escolha a Cidade</label>
                                    <div class="col-lg-2">
									<select name="cidade" tabindex="2" id="cidadeAc" class='form-control ' required="">
									<option value=0> Selecione a cidade</option>									
									</select>	
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