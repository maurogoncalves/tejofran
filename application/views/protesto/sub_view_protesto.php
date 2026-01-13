<?php
								$attributes = array('class' => 'form-horizontal style-form','id' => 'form' ,'enctype' => 'multipart/form-data');
								echo form_open('protesto/cadastrar_protesto', $attributes); 
								?>
                                <div class="panel-body">
                                 <form class="form-horizontal">
								<div class="form-group" >
									<label class="col-lg-12 control-label" style='font-size:16px;color:#002060;font-weight:bold;text-align:left!important'>Dados da Unidade</label>
                                </div>
                                <div class="form-group" >
									<label class="col-lg-2 control-label">Encontre o CNPJ Raiz</label>
                                    <div class="col-lg-4">
										<select name="cnpj_raiz" tabindex="3" id="cnpj_raiz" class='form-control' required="">

										</select>	
									</div>
									<label class="col-lg-2 control-label">Encontre o CNPJ</label>
                                    <div class="col-lg-4">
										<select name="cnpj" tabindex="3" id="cnpj" class='form-control ' required="">
										<option value=0> Selecione o Cnpj</option>									
										</select>	
									</div>
									
									
								
                                </div>

								<div class="form-group" id='cnpj_div'>
									<label class="col-lg-2 control-label">Escolha o Estado</label>
                                    <div class="col-lg-4">
									<select name="estado" tabindex="1" id="estado"  class='form-control ' required="">
									
									</select>	
									</div>
									
										<label class="col-lg-2 control-label">Escolha a Cidade</label>
                                    <div class="col-lg-4">
									<select name="cidade" tabindex="2" id="cidade" class='form-control ' required="">
									<option value=0> Selecione a cidade</option>									
									</select>	
									</div>
									
									
									
                                </div>
								<!--
                                <div class="form-group" id='cnpj_div'>
									<label class="col-lg-2 control-label">Inscri&ccedil;&atilde;o Estadual</label>
                                    <div class="col-lg-4">
										<select name="ie" tabindex="4" id="ie" class='form-control' required="">
										<option value=0> Selecione</option>									
										</select>	
									</div>
									<label class="col-lg-2 control-label">Inscri&ccedil;&atilde;o Mobili&aacute;ria</label>
                                    <div class="col-lg-4">
										<select name="im" tabindex="5" id="im" class='form-control' required="">
										<option value=0> Selecione</option>									
										</select>	
									</div>
                                </div>
								-->
								<div class="form-group" >
									<label class="col-lg-12 control-label" style='font-size:16px;color:#002060;font-weight:bold;text-align:left!important'>Dados do Protesto</label>
                                </div>
							

								<div class="form-group">													
									<label class="col-sm-2 control-label">Cart&oacute;rio</label>
                                    <div class="col-lg-4">
                                      	<input type="text" tabindex="8" id='cartorio' name='cartorio' class="form-control"     >
                                    </div>	
									<label class="col-sm-2 control-label">Dados do Cart&oacute;rio</label>
                                    <div class="col-lg-4">
                                      	<input type="text" tabindex="9" id='dadosCartorio' name='dadosCartorio' class="form-control"     >
                                    </div>	
									
									
                                </div>
								
								<div class="form-group">													
									<label class="col-sm-2 control-label">Livro</label>
                                    <div class="col-lg-2">
                                      	<input type="text" tabindex="10" id='livro' name='livro' class="form-control"     >
                                    </div>	
									<label class="col-sm-2 control-label">Folha</label>
                                    <div class="col-lg-2">
                                      	<input type="text" tabindex="11" id='folha' name='folha' class="form-control"     >
                                    </div>	
									
									<label class="col-lg-2 control-label">Data Protesto</label>
                                    <div class="col-lg-2">
									<input type="text" data-masked="" data-inputmask="'mask': '99/99/9999' " id='dataProtesto' name='dataProtesto' tabindex="13" class="form-control"   >
                                    </div>
									
                                </div>
								
								<div class="form-group">
									<label class="col-lg-2 control-label">N&uacute;m. T&iacute;tulo</label>
                                    <div class="col-lg-2">
									<input type="text"  id='numTitulo' tabindex="14" name='numTitulo' class="form-control"  value=''    >  
                                    </div>
									<label class="col-lg-2 control-label">Valor Protestado</label>
                                    <div class="col-lg-2">
									<input type="text"  id='valorProtestado' data-a-dec="," data-a-sep="." tabindex="15" name='valorProtestado' class="auto  form-control"  value=''    >  
                                    </div>
									
									<label class="col-sm-2 control-label">Data de Emiss&atilde;o</label>
                                    <div class="col-lg-2">
                                      	<input type="text" tabindex="16"  data-masked="" data-inputmask="'mask': '99/99/9999' " id='dataAdmissaoTitulo' name='dataAdmissaoTitulo' class="form-control"     >
                                    </div>

															
								
								
                                </div>
								
								
								<div class="form-group">
								
									<label class="col-lg-2 control-label">Vencimento</label>
                                    <div class="col-lg-2">
									<input type="text" data-masked="" data-inputmask="'mask': '99/99/9999' " id='vencimento'  name='vencimento' tabindex="17" class="form-control"     >
                                    </div>		
									
									<label class="col-lg-2 control-label">Valor T&iacute;tulo</label>
                                    <div class="col-lg-2">
									<input type="text"  id='valorTitulo' data-a-dec="," data-a-sep="." tabindex="18" name='valorTitulo' class="auto  form-control"  value=''    >  
                                    </div>
									
									<label class="col-lg-2 control-label">Natureza</label>
                                    <div class="col-lg-2">
									<select name="natureza" tabindex="19" id="natureza"  class='form-control' required=""></select>	
                                    </div>	
									
									
									
                                </div>
								
								<div class="form-group">

										
									<input type="hidden"  id='competencia_legis'  value='0'    >  
									
									
									<label class="col-lg-2 control-label">Uf do Protesto</label>
                                    <div class="col-lg-4">
									<select name="estadoProtesto" tabindex="20" id="estadoProtesto"  class='form-control' required=""></select>	
                                    </div>
									
									<label class="col-lg-2 control-label">Municipio do Protesto</label>
                                    <div class="col-lg-4">
									<select name="cidadeProtesto" tabindex="21" id="cidadeProtesto"  class='form-control' required=""></select>	
                                    </div>
									
                                </div>
								
								<div class="form-group" >
									<label class="col-lg-12 control-label" style='font-size:16px;color:#002060;font-weight:bold;text-align:left!important'>Dados do Apresentante</label>
                                </div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Nome</label>
                                    <div class="col-lg-4">
                                      	<input type="text" tabindex="22"  id='nomeApresentante' name='nomeApresentante' class="form-control"     >
                                    </div>									
									<label class="col-sm-2 control-label">Cnpj </label>
                                    <div class="col-lg-4">
                                      	<input type="text" tabindex="23" id='cnpjApresentante' name='cnpjApresentante' class="form-control"     data-masked="" data-inputmask="'mask': '99.999.999/9999-99' " >
                                    </div>		
                                </div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Dados de contato</label>
                                    <div class="col-lg-4">
                                      	<input type="text" tabindex="24"  id='contatoApresentante' name='contatoApresentante' class="form-control"     >
                                    </div>									
									<label class="col-sm-2 control-label"> </label>
                                    <div class="col-lg-4">                                      	
                                    </div>		
                                </div>
								
								<div class="form-group" >
									<label class="col-lg-12 control-label" style='font-size:16px;color:#002060;font-weight:bold;text-align:left!important'>Dados do Credor</label>
                                </div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Credor/Favorecido</label>
                                    <div class="col-lg-4">
                                      	<input type="text" tabindex="25"  id='credorFavorecido' name='credorFavorecido' class="form-control"     >
                                    </div>									
									<label class="col-sm-2 control-label">Cnpj Credor</label>
                                    <div class="col-lg-4">
                                      	<input type="text" tabindex="26" id='cnpjCredor' name='cnpjCredor' class="form-control"     data-masked="" data-inputmask="'mask': '99.999.999/9999-99' " >
                                    </div>	
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Dados de contato</label>
                                    <div class="col-lg-4">
                                      	<input type="text" tabindex="27"  id='contatoCredor' name='contatoCredor' class="form-control"     >
                                    </div>									
									<label class="col-sm-2 control-label">NF protestada</label>
                                    <div class="col-lg-4">
                                      	<input type="text" tabindex="27"  id='nfProtestada' name='nfProtestada' class="form-control"     >
                                    </div>		
                                </div>
								
                               <div class="form-group" >
									<label class="col-lg-12 control-label" style='font-size:16px;color:#002060;font-weight:bold;text-align:left!important'>Observa&ccedil;&otilde;es</label>
                                </div>
								<div class="form-group">
									<label class="col-sm-2 control-label">N&uacute;m. certid&atilde;o divida ativa</label>
                                    <div class="col-lg-4">
                                      	<input type="text" tabindex="28"  id='nrCertDivAtiv' name='nrCertDivAtiv' class="form-control"    >
                                    </div>									
                                </div>
								<div class="form-group">
									<label class="col-lg-2 control-label">N&uacute;m. do auto de infra&ccedil;&atilde;o</label>
                                    <div class="col-lg-4"><input type="text"  id='nrAutoInfracao' name='nrAutoInfracao' tabindex="29" class="form-control"     >
                                    </div>
                                </div>
								<div class="form-group">									
									<label class="col-lg-2 control-label">Breve Relato da Protesto</label>
                                    <div class="col-lg-10"><input type="text"  id='breve_relato' name='breve_relato' tabindex="30" class="form-control"     >
                                    </div>
                                </div>
								<div class="form-group"></div>
								<div class="form-group">
									
											
									<div class="col-lg-4">										
										<span class="btn btn-success fileinput-button">
										<i class="glyphicon glyphicon-plus"></i>
										<span>Upload PDF (5 arquivos)</span>
										<input id="fileupload" multiple="multiple" type="file" name="userfile[]" >
										</span>
                                    </div>										
                                </div>
								
								<div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <div class="col-sm-4 col-sm-offset-2">
										<button class="btn btn-white" type="submit">Cancelar</button>
										<button class="btn btn-primary" type="submit">Salvar/Enviar</button>
										
										<input type="hidden"  id='ie' name='ie' value='0'    >
										<input type="hidden"  id='im' name='im' value='0'    >
										
										
                                    </div>
                                </div>
                            </form>

                                </div>