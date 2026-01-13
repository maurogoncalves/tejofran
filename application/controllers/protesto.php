<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Protesto extends CI_Controller {

 

 function __construct(){
   parent::__construct();
   $this->load->model('registro_model','',TRUE); 
   $this->load->model('email_model','',TRUE);
   $this->load->model('estado_model','',TRUE);
   $this->load->model('protesto_model','',TRUE);		
   $this->load->model('cnpj_model','',TRUE);
   $this->load->model('user','',TRUE);
   $this->load->model('contratante','',TRUE);
   $this->load->library('session');
   $this->load->library('form_validation');
   $this->load->library('Auxiliador');
   $this->load->helper('url');
   $this->load->helper('general_helper');
   date_default_timezone_set('America/Sao_Paulo');
	session_start();
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, OPTIONS, POST');
	header('Access-Control-Allow-Headers: origin, x-requested-with,Content-Type, Content-Range, Content-Disposition, Content-Description');
	
}

 

 function index(){
	 
   $this->logado();   
   redirect('/protesto/listar/0', 'refresh');
 }

function cadastrar_protesto_acao(){
	$session_data = $_SESSION['login_tejofran_protesto'];
	$idContratante = $session_data['id_contratante'] ;
	$emailRemetente = $session_data['email'] ;
	$cnpjRaiz  = $this->input->post('cnpj_raiz'); 
	$idOco  = $this->input->post('idOco'); 
	$op  = $this->input->post('op'); 
	$natureza = $this->input->post('natureza'); 
	$distribuicao = $this->input->post('distribuicao'); 
	$vara = $this->input->post('vara'); 
	$estado= $this->input->post('estado'); 
	$cidade= $this->input->post('cidade'); 
	
	$dataPefin = $this->input->post('dataAcao'); 
	$valorProtestado = $this->input->post('valorProtestado'); 
	$valorProtestado  = $this->input->post('valorProtestado');	
	$valorProtestado = str_replace(".","",$valorProtestado);
	$valorProtestado = str_replace(",",".",$valorProtestado);
	
	$tipoOcorrencia = $this->input->post('tipo_ocorrencia');

	if(!empty($dataPefin)){		
		if($dataPefin == '0000-00-00') {
			$dataPefin = '1900-01-01';
		}else{
			$dataPefinArr = explode('/',$dataPefin);
			$dataProtesto = $dataPefinArr[2].'-'.$dataPefinArr[1].'-'.$dataPefinArr[0];
		}
	}else{		
		$dataProtesto = '1900-01-01';
	}	
	
	$dados = array(
		'id_cnpj_raiz' => $cnpjRaiz,
		'data_protesto' => $dataProtesto,
		'valor_protestado' => $valorProtestado,
		'tipo_ocorrencia' => $tipoOcorrencia,
		'distribuicao' => $distribuicao,
		'vara' => $vara,
		'id_natureza' => $natureza,
		'id_uf' => $estado,
		'id_municipio' => $cidade,
		);	

	if($op == 0){
		$id = $this->protesto_model->add($dados);
	}else{
		$id = $this->protesto_model->atualizar('protesto',$dados,$idOco);
	}		
	
	
	redirect('/protesto/listarAcao/0', 'refresh');
	
	
}	

function cadastrar_pefin(){
	$session_data = $_SESSION['login_tejofran_protesto'];
	$idContratante = $session_data['id_contratante'] ;
	$emailRemetente = $session_data['email'] ;
	
	$idOco  = $this->input->post('idOco'); 
	$op  = $this->input->post('op'); 
	$cnpjRaiz  = $this->input->post('cnpj_raiz'); 	
	$contrato = $this->input->post('contrato'); 
	$modalidade = $this->input->post('modalidade'); 
	$empresa = $this->input->post('empresa'); 
	$dataPefin = $this->input->post('dataPefin'); 
	$valorProtestado = $this->input->post('valorProtestado'); 
	$valorProtestado  = $this->input->post('valorProtestado');	
	$valorProtestado = str_replace(".","",$valorProtestado);
	$valorProtestado = str_replace(",",".",$valorProtestado);
	
	$avalista = $this->input->post('avalista'); 
	$local = $this->input->post('local'); 
	$tipoOcorrencia = $this->input->post('tipo_ocorrencia');

	if(!empty($dataPefin)){		
		if($dataPefin == '0000-00-00') {
			$dataPefin = '1900-01-01';
		}else{
			$dataPefinArr = explode('/',$dataPefin);
			$dataProtesto = $dataPefinArr[2].'-'.$dataPefinArr[1].'-'.$dataPefinArr[0];
		}
	}else{		
		$dataProtesto = '1900-01-01';
	}	
	
	$dados = array(
		'id_cnpj_raiz' => $cnpjRaiz,
		'data_protesto' => $dataProtesto,
		'valor_protestado' => $valorProtestado,
		'tipo_ocorrencia' => $tipoOcorrencia,
		'contrato' => $contrato,
		'modalidade' => $modalidade,
		'avalista' => $avalista,
		'local' => $local,
		'credor_favorecido' => $empresa
		);		
	
	if($op == 0){
		$id = $this->protesto_model->add($dados);	
		$this->gravarOperacao($tipoOcorrencia,5,$id);
	}else{
		$this->gravarOperacao($tipoOcorrencia,6,$idOco);
		$id = $this->protesto_model->atualizar('protesto',$dados,$idOco);
	}
	
	
	if($tipoOcorrencia == 1){
		redirect('/protesto/listarPefin/0', 'refresh');
	}elseif($tipoOcorrencia == 2){
		redirect('/protesto/listarRefin/0', 'refresh');
	}elseif($tipoOcorrencia == 3){
		redirect('/protesto/listarCheque/0', 'refresh');
	}elseif($tipoOcorrencia == 6){
		redirect('/protesto/listarFalencia/0', 'refresh');
	}elseif($tipoOcorrencia == 7){
		redirect('/protesto/listarDivida/0', 'refresh');
	}
	
	
	
}	
 function cadastrar_protesto(){
	
	 
	$session_data = $_SESSION['login_tejofran_protesto'];
	$idContratante = $session_data['id_contratante'] ;
	$emailRemetente = $session_data['email'] ;
	$cnpj  = $this->input->post('cnpj'); 
	$ie  = $this->input->post('ie');
	$im  = $this->input->post('im');
	$credorFavorecido  = $this->input->post('credorFavorecido');  
	$cnpjCredor = $this->input->post('cnpjCredor');
	$contatoCredor = $this->input->post('contatoCredor');
	
	$cartorio  = $this->input->post('cartorio');  
	$dadosCartorio  = $this->input->post('dadosCartorio');  
	$folha  = $this->input->post('folha');  
	$livro  = $this->input->post('livro');  
	$natureza  = $this->input->post('natureza'); 
	$competencia_legis  = $this->input->post('competencia_legis'); 
	$numTitulo  = $this->input->post('numTitulo'); 
	
	$valorTitulo  = $this->input->post('valorTitulo');	
	$valorTitulo = str_replace(".","",$valorTitulo);
	$valorTitulo = str_replace(",",".",$valorTitulo);
	
	$valorProtestado  = $this->input->post('valorProtestado');	
	$valorProtestado = str_replace(".","",$valorProtestado);
	$valorProtestado = str_replace(",",".",$valorProtestado);
	
	$breveRelato  = $this->input->post('breve_relato');
	$dataProtesto  = $this->input->post('dataProtesto');
	$vencimento  = $this->input->post('vencimento');
	
	if(!empty($dataProtesto)){
		
		if($dataProtesto == '0000-00-00') {
			$dataProtesto = '1900-01-01';
		}else{
			$dataProtestoArr = explode('/',$dataProtesto);
			$dataProtesto = $dataProtestoArr[2].'-'.$dataProtestoArr[1].'-'.$dataProtestoArr[0];
		}
		
		
	}else{		
		$dataProtesto = '1900-01-01';
	}	
	if(!empty($vencimento)){
		if($vencimento == '0000-00-00') {
			$dtVencimento = '1900-01-01';
		}else{
			$vencimentoArr = explode('/',$vencimento);
			$dtVencimento = $vencimentoArr[2].'-'.$vencimentoArr[1].'-'.$vencimentoArr[0];
		}
		
		
	}else{		
		$dtVencimento = '1900-01-01';
	}	
	
	$cnpjCredor  = $this->input->post('cnpjCredor');
	$dataAdmissaoTitulo  = $this->input->post('dataAdmissaoTitulo');
	
	if(!empty($dataAdmissaoTitulo)){
		if($dataAdmissaoTitulo == '0000-00-00') {
			$dataAdm = '1900-01-01';
		}else{
			$dataAdmissaoTituloArr = explode('/',$dataAdmissaoTitulo);
			$dataAdm = $dataAdmissaoTituloArr[2].'-'.$dataAdmissaoTituloArr[1].'-'.$dataAdmissaoTituloArr[0];
		}	
		
	}else{		
		$dataAdm = '1900-01-01';
	}	
	
	
	$nrAutoInfracao  = $this->input->post('nrAutoInfracao');
	
	$nomeApresentante = $this->input->post('nomeApresentante');
	$cnpjApresentante = $this->input->post('cnpjApresentante');
	$contatoApresentante = $this->input->post('contatoApresentante');
	
	$estado = $this->input->post('estado');
	$cidade = $this->input->post('cidade');
	$nrCertDivAtiv = $this->input->post('nrCertDivAtiv');
	
	$estadoProtesto = $this->input->post('estadoProtesto');
	$cidadeProtesto = $this->input->post('cidadeProtesto');
	
	$nfProtestada = $this->input->post('nfProtestada');
	
	$dados = array(
		'id_cnpj' => $cnpj,
		'id_ie' => $ie,
		'id_im' => $im,
		'credor_favorecido' => $credorFavorecido,
		'contato_credor' => $contatoCredor,
		'cnpj_credor' => $cnpjCredor,
		'cartorio' => $cartorio,
		'dados_cartorio' => $dadosCartorio,
		'livro' => $livro,
		'folha' => $folha,
		'data_protesto' => $dataProtesto,
		'valor_protestado' => $valorProtestado,
		'numero_titulo' => $numTitulo,
		'valor_titulo' => $valorTitulo,		
		'vencimento' =>  $dtVencimento,		
		'id_natureza' => $natureza,		
		'id_competencia_legis' => $competencia_legis,		
		'relato_protesto' => $breveRelato,
		'nome_apresentante' => $nomeApresentante,
		'cnpj_apresentante' => $cnpjApresentante,
		'contato_apresentante' => $contatoApresentante,		
		'data_admissao_titulo' => $dataAdm,
		'nr_auto_infracao' => $nrAutoInfracao,
		'nr_cert_div_ativ' => $nrCertDivAtiv,
		'id_uf' => $estado,
		'id_municipio' => $cidade,
		'id_uf_protesto' => $estadoProtesto,
		'id_municipio_protesto' => $cidadeProtesto,
		'nf_protestada' => $nfProtestada,
		'tipo_ocorrencia' => 4
		);		
	$id = $this->protesto_model->add($dados);
	$this->gravarOperacao(4,5,$id);
	
	define('DEST_DIR', './arquivos/protesto/');
	if (($_FILES['userfile']['name'][0] !== '')){	
		// se o "name" estiver vazio, é porque nenhum arquivo foi enviado e cria uma variável para facilitar
		$arquivos = $_FILES['userfile'];
	 	// total de arquivos enviados
		$total = count($arquivos['name']);
		for ($i = 0; $i < $total; $i++){
			// podemos acessar os dados de cada arquivo desta forma:  $arquivos['name'][$i] - $arquivos['tmp_name'][$i] - $arquivos['size'][$i]  - $arquivos['error'][$i]  - $arquivos['type'][$i] 
			$extensao = str_replace('.','',strrchr($arquivos['name'][$i], '.'));	
			$nomeArq =  $id.'-'.$i.'.'.$extensao;		
			if (!move_uploaded_file($arquivos['tmp_name'][$i], DEST_DIR . '/' . $nomeArq)) {
				echo "Erro ao enviar o arquivo: " . $arquivos['name'][$i];
			}		
			$dadosArq = array(
			'id_protesto' => $id,
			'arquivo' => $nomeArq		
			);			
			$this->protesto_model->addArq($dadosArq);			
		}     
	}
	
	$dadosProtesto = $this->protesto_model->listarProtestoById($id);
	
	
	$emails = $this->email_model->listarEmailFiscalizacao();
	
	$this->load->library('email');
	$this->email->from('notificacoes@bdservicos.com.br', 'BD WebNotifica');
	$this->email->cc($emailRemetente);
	
	foreach($emails as $valor){
		
		$this->email->to($valor->email);
		$this->email->subject('Sistema BD WebNotifica - Envio de Novo Protesto' );
				
		$textoOld = '
		<strong>Não responda esse email.</strong> <BR> <hR> 
		Acesse o link abaixo para acessar o sistema.
		<BR>
		<a href="https://bdwebgestora.com.br/tejofran/index.php/protesto/tratativas?id='.$id.'" target="_blank" >https://bdwebgestora.com.br/tejofran/</a>
		<BR><BR>
			
		<strong>Dados da novo protesto</strong> <BR> <BR> 
		CNPJ Raiz:'.$dadosProtesto[0]->cnpj_raiz.'
		<BR>
		CNPJ : '.$dadosProtesto[0]->cnpj.'
		<BR>
		Credor Favorecido : '.$dadosProtesto[0]->credor_favorecido.'
		<BR>
		Cartório : '.$dadosProtesto[0]->cartorio.'
		<BR>
		Número Título : '.$dadosProtesto[0]->numero_titulo.'
		<BR>
		Valor Título : '.$dadosProtesto[0]->valor_titulo.'
		<BR>
		Data Protesto : '.$dadosProtesto[0]->data_protesto_br.'
		<BR>
		Vencimento : '.$dadosProtesto[0]->vencimento_br.'
		<BR>
		Breve Relato do Protesto : '.$dadosProtesto[0]->relato_protesto.'
		<hR>
		
		Atenciosamente, <BR> <BR> <strong>Sistema BD WebNotifica <br> Apoio Técnico</strong>';
		
		$texto = '
		<strong>Não responda esse email.</strong> <BR> <hR> 
		Acesse o link abaixo para acessar o sistema.
		<BR>
		<a href="https://bdwebgestora.com.br/tejofran/index.php/protesto/tratativas?id='.$id.'" target="_blank" >https://bdwebgestora.com.br/tejofran/</a>
		<BR><BR>
			
		<strong>Dados da novo protesto</strong> <BR> <BR> 
		CNPJ Raiz:'.$dadosProtesto[0]->cnpj_raiz.'
		<BR>
		Credor Favorecido : '.$dadosProtesto[0]->credor_favorecido.'
		<BR>
		Cartório : '.$dadosProtesto[0]->cartorio.'
		<BR>
		Valor do Protesto : '.$dadosProtesto[0]->valor_protestado.'
		<BR>
		Data Protesto : '.$dadosProtesto[0]->data_protesto_br.'
		<BR>
		Breve Relato do Protesto : '.$dadosProtesto[0]->relato_protesto.'
		<hR>
		
		Atenciosamente, <BR> <BR> <strong>Sistema BD WebNotifica <br> Apoio Técnico</strong>';
		
		$html = "<html><body style='font-family:Trebuchet MS'>".$texto."</body></html>";
		
		$this->email->set_mailtype("html");
		$this->email->set_alt_message($texto);
		$this->email->message($html);
		$this->email->send();
	}
	
	
	redirect('/protesto/listar/0', 'refresh');
 }

function alterar_protesto(){
	$session_data = $_SESSION['login_tejofran_protesto'];
	$idContratante = $session_data['id_contratante'] ;
	$id  = $this->input->post('id'); 
	$cnpj  = $this->input->post('cnpj'); 
	$ie  = $this->input->post('ie');
	$im  = $this->input->post('im');
	$credorFavorecido  = $this->input->post('credorFavorecido');  
	$cnpjCredor = $this->input->post('cnpjCredor');
	$contatoCredor = $this->input->post('contatoCredor');
	
	$cartorio  = $this->input->post('cartorio');  
	$dadosCartorio  = $this->input->post('dadosCartorio');  
	$folha  = $this->input->post('folha');  
	$livro  = $this->input->post('livro');  
	$natureza  = $this->input->post('natureza'); 
	$competencia_legis  = $this->input->post('competencia_legis'); 
	$numTitulo  = $this->input->post('numTitulo'); 
	
	$valorTitulo  = $this->input->post('valorTitulo');	
	$valorTitulo = str_replace(".","",$valorTitulo);
	$valorTitulo = str_replace(",",".",$valorTitulo);
	
	$valorProtestado  = $this->input->post('valorProtestado');	
	$valorProtestado = str_replace(".","",$valorProtestado);
	$valorProtestado = str_replace(",",".",$valorProtestado);
	
	$breveRelato  = $this->input->post('breve_relato');
	$dataProtesto  = $this->input->post('dataProtesto');
	$vencimento  = $this->input->post('vencimento');
	
	if(!empty($dataProtesto)){
		$dataProtestoArr = explode('/',$dataProtesto);
		$dataProtesto = $dataProtestoArr[2].'-'.$dataProtestoArr[1].'-'.$dataProtestoArr[0];
	}else{		
		$dataProtesto = '1900-01-01';
	}	
	if(!empty($vencimento)){
		$vencimentoArr = explode('/',$vencimento);
		$dtVencimento = $vencimentoArr[2].'-'.$vencimentoArr[1].'-'.$vencimentoArr[0];
	}else{		
		$dtVencimento = '1900-01-01';
	}	
	
	$cnpjCredor  = $this->input->post('cnpjCredor');
	$dataAdmissaoTitulo  = $this->input->post('dataAdmissaoTitulo');
	
	if(!empty($dataAdmissaoTitulo)){
		$dataAdmissaoTituloArr = explode('/',$dataAdmissaoTitulo);
		$dataAdm = $dataAdmissaoTituloArr[2].'-'.$dataAdmissaoTituloArr[1].'-'.$dataAdmissaoTituloArr[0];
	}else{		
		$dataAdm = '1900-01-01';
	}	
	
	
	$nrAutoInfracao  = $this->input->post('nrAutoInfracao');
	
	$nomeApresentante = $this->input->post('nomeApresentante');
	$cnpjApresentante = $this->input->post('cnpjApresentante');
	$contatoApresentante = $this->input->post('contatoApresentante');
	
	
	$nrCertDivAtiv = $this->input->post('nrCertDivAtiv');	
	$estado = $this->input->post('estado');
	$cidade = $this->input->post('cidade');
	
	$estadoProtesto = $this->input->post('estadoProtesto');
	$cidadeProtesto = $this->input->post('cidadeProtesto');
	
	$dados = array(
		'id_cnpj' => $cnpj,
		'id_ie' => $ie,
		'id_im' => $im,
		'credor_favorecido' => $credorFavorecido,
		'contato_credor' => $contatoCredor,
		'cnpj_credor' => $cnpjCredor,
		'cartorio' => $cartorio,
		'dados_cartorio' => $dadosCartorio,
		'livro' => $livro,
		'folha' => $folha,
		'data_protesto' => $dataProtesto,
		'valor_protestado' => $valorProtestado,
		'numero_titulo' => $numTitulo,
		'valor_titulo' => $valorTitulo,		
		'vencimento' =>  $dtVencimento,		
		'id_natureza' => $natureza,		
		'id_competencia_legis' => $competencia_legis,		
		'relato_protesto' => $breveRelato,
		'nome_apresentante' => $nomeApresentante,
		'cnpj_apresentante' => $cnpjApresentante,
		'contato_apresentante' => $contatoApresentante,		
		'data_admissao_titulo' => $dataAdm,
		'nr_auto_infracao' => $nrAutoInfracao,
		'nr_cert_div_ativ' => $nrCertDivAtiv,
		'id_uf' => $estado,
		'id_municipio' => $cidade,
		'id_uf_protesto' => $estadoProtesto,
		'id_municipio_protesto' => $cidadeProtesto
		);		
	$this->gravarOperacao(4,6,$id);	
	$this->protesto_model->atualizar('protesto',$dados,$id);
	
	redirect('/protesto/listar/0', 'refresh');
 }
 
  function encerrar(){
	$session_data = $_SESSION['login_tejofran_protesto'];
	$idContratante = $session_data['id_contratante'] ;
	$id  = $this->input->post('idProtesto');  
	$tipo  = $this->input->post('tipo');  
	$dataProtesto  = $this->input->post('dataProtesto');  
	
	if(!empty($dataProtesto)){
		
		if($dataProtesto == '0000-00-00') {
			$dataProtesto = '1900-01-01';
		}else{
			$dataProtestoArr = explode('/',$dataProtesto);
			$dataProtesto = $dataProtestoArr[2].'-'.$dataProtestoArr[1].'-'.$dataProtestoArr[0];
		}
		
		
	}else{		
		$dataProtesto = '1900-01-01';
	}	
	
	$dados = array(
		'data_baixa_protesto' => $dataProtesto,
		'status' => 1,		
	);		
		
	$this->protesto_model->atualizar('protesto',$dados,$id);	
	
	$this->gravarOperacao($tipo,7,$id);
	
	switch ($tipo) {
		case 1:
			redirect('/protesto/listarPefin/0', 'refresh');
			break;
		case 2:
			redirect('/protesto/listarRefin/0', 'refresh');
			break;
		case 3:
			redirect('/protesto/listarCheque/0', 'refresh');
			break;
		case 4:
			redirect('/protesto/listar/0', 'refresh');
			break;
		case 5:
			redirect('/protesto/listarAcao/0', 'refresh');
			break;	
		case 6:
			redirect('/protesto/listarFalencia/0', 'refresh');
			break;	
		case 7:
			redirect('/protesto/listarDivida/0', 'refresh');
			break;		
	}

	
 }	 
 
 function exportProtesto(){	
		$this->logado();    
		$session_data = $_SESSION['login_tejofran_protesto'];
		$idContratante = $session_data['id_contratante'] ;
		
		if(empty($_POST)){
			$result = $this->protesto_model->listarprotesto(0,0,0,0,0);
		}else{	
			$estado = $this->input->post('estado');
			$cidade = $this->input->post('cidade');
			$cnpjRaiz = 0;
			$cnpj = $this->input->post('cnpj');
			$result = $this->protesto_model->listarprotesto($estado,$cidade,$cnpjRaiz,$cnpj,0);
			$this->gravarOperacao(4,7,0);
		}
	
		$this->csvProtesto($result);
	
	}
	 
	 
	function export(){	
		$this->logado();    
		$session_data = $_SESSION['login_tejofran_protesto'];
		$idContratante = $session_data['id_contratante'] ;
			
		if(empty($_POST)){
			$result = $this->protesto_model->listarPefinExp(0,1,$status,'Pefin');
		}else{
			$tipo = $this->input->post('tipo');	
			$cnpjRaiz = $this->input->post('cnpj');		
			$status = $this->input->post('status');					
			$nome = $this->input->post('nome');		
			$result = $this->protesto_model->listarPefinExp($cnpjRaiz,$tipo,$status,$nome);
			$this->gravarOperacao($tipo,4,0);
		}
		
		$this->csv($result,$nome);
	
	}
	

	
	function csv($result,$nome){
		$file=$nome.".xls";

				
		$test="<table border=1>
		<tr>
		<td>Id</td>
		<td>CNPJ Raiz</td>		
		<td>Contrato</td>
		<td>Modalidade</td>	
		<td>Empresa Devedora</td>
		<td>Empresa Credora</td>
		<td>Data</td>
		<td>Data Baixa</td>
		<td>Valor</td>
		<td>Avalista</td>
		<td>Local</td>
		<td>Status</td>
		<td>Tratativas</td>		
		</tr>
		";
		
		$isArray =  is_array($result) ? '1' : '0';
		if($isArray == 0){
			$test="
			<tr>
			<td>Não Há Dados para exibi&ccedil;&atilde;o</td>		
			</tr>
			";
		}else{			
			  foreach($result as $key => $emitente){ 	
			  
			    $resultCnd = $this->protesto_model->listarTodasTratativas($emitente->id);
				$isArrayTrat =  is_array($resultCnd) ? '1' : '0';
				$tratArr = '';
				if($isArrayTrat == 0){
					$tratArr ='Sem Tratativas';
				}else{
					foreach($resultCnd as $key => $trat){
						$tratArr .="<table border=1>
						<tr>
						<td>ID</td>
						<td>&Uacute;ltima Tratativa</td>
						<td>Pend&ecirc;ncia</td>		
						<td>Data Envio</td>
						<td>Status</td>
						<td>&Aacute;rea Focal</td>
						<td>Contato</td>	
						</tr>
						";
						
						$tratArr .= "<tr>";
						$tratArr .= "<td>".utf8_decode($trat->id)."</td>";
						$tratArr .= "<td>".utf8_decode($trat->ultima_tratativa)."</td>";
						$tratArr .= "<td>".utf8_decode($trat->desc_pendencia)."</td>";						
						if( empty($trat->data_envio_voiza) || ($trat->data_envio_voiza == '11/11/1111') ){
							
							if($trat->data_envio_bd == '11/11/1111'){
								$tratArr .= "<td></td>";						
							}else{
								$tratArr .= "<td>".utf8_decode($trat->data_envio_bd)."</td>";
							}
						}else{ 
							if($trat->data_envio_voiza == '11/11/1111'){
								$tratArr .= "<td></td>";												
							}else{
								$tratArr .= "<td>".utf8_decode($trat->data_envio_voiza)."</td>";
							}	
						}
						
						
						$tratArr .= "<td>".utf8_decode($trat->descricao)."</td>";
						$tratArr .= "<td>".utf8_decode($trat->descricao_area_focal)."</td>";
						$tratArr .= "<td>".utf8_decode($trat->contato)."</td>";
						$tratArr .= "<tr>";
						$tratArr .="</table>";
					}	
				}	
				
				$dataProtesto = $emitente->data_protesto_br == '11/11/1111' ? '' : $emitente->data_protesto_br ;
				$data_baixa_protesto_br = $emitente->data_baixa_protesto_br == '11/11/1111' ? '' : $emitente->data_baixa_protesto_br ;
				$valor = number_format($emitente->valor_protestado, 2, ',', '.');
				$status =  $emitente->status == '0' ? 'Ativo' : 'Baixado';
				$test .= "<tr>";
				$test .= "<td>".utf8_decode($emitente->id)."</td>";
				$test .= "<td>".utf8_decode($emitente->cnpj_raiz)."</td>";
				$test .= "<td>".utf8_decode($emitente->contrato)."</td>";
				$test .= "<td>".utf8_decode($emitente->modalidade)."</td>";
				$test .= "<td>".utf8_decode($emitente->empresa_devedora)."</td>";
				$test .= "<td>".utf8_decode($emitente->credor_favorecido)."</td>";
				$test .= "<td>".utf8_decode($dataProtesto)."</td>";
				$test .= "<td>".utf8_decode($data_baixa_protesto_br)."</td>";
				$test .= "<td>".utf8_decode($valor)."</td>";
				$test .= "<td>".utf8_decode($emitente->avalista)."</td>";
				$test .= "<td>".utf8_decode($emitente->local)."</td>";
				$test .= "<td>".utf8_decode($status)."</td>";
				$test .= "<td>".($tratArr)."</td>";							
				$test .= "</tr>";				
			}
		}
		$test .='</table>';

		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$file");
		echo $test;	
		
 }	
 
	function csvProtesto($result){
	 
	 $file="Protesto.xls";

				
		$test="<table border=1>
		<tr>
		<td>Id</td>
		<td>CNPJ Raiz</td>		
		<td>CNPJ</td>
		<td>Cart&oacute;rio</td>
		<td>Dados Cart&oacute;rio</td>
		<td>Folha</td>
		<td>Livro</td>
		<td>Data Protesto</td>
		<td>N&uacute;m. T&iacute;tulo</td>
		<td>Valor Protestado</td>
		<td>Data de Emiss&atilde;o</td>
		<td>Data da Baixa</td>
		<td>Vencimento</td>
		<td>Valor T&iacute;tulo</td>
		<td>Natureza</td>
		<td>Compet&ecirc;ncia Legislativa</td>
		<td>Dados Apresentante</td>
		<td>Cnpj Apresentante</td>
		<td>Contato Apresentante</td>		
		<td>Credor/Favorecido</td>
		<td>CNPJ Credor/Favorecido</td>
		<td>Contato Credor/Favorecido</td>		
		<td>N&uacute;m. certid&atilde;o divida ativa</td>		
		<td>N&uacute;m. do auto de infra&ccedil;&atilde;o</td>	
		<td>Breve Relato do Protesto</td>		
		<td>UF do Protesto</td>		
		<td>Cidade do Protesto</td>		
		<td>Tratativas</td>		
		</tr>
		";
		
		$isArray =  is_array($result) ? '1' : '0';
		if($isArray == 0){
			$test="
			<tr>
			<td>Não Há Dados para exibi&ccedil;&atilde;o</td>		
			</tr>
			";
		}else{			
			  foreach($result as $key => $emitente){ 	
			  
			    $resultCnd = $this->protesto_model->listarTodasTratativas($emitente->id);
				$isArrayTrat =  is_array($resultCnd) ? '1' : '0';
				$tratArr = '';
				if($isArrayTrat == 0){
					$tratArr ='Sem Tratativas';
				}else{
					foreach($resultCnd as $key => $trat){
						$tratArr .="<table border=1>
						<tr>
						<td>ID</td>
						<td>&Uacute;ltima Tratativa</td>
						<td>Pend&ecirc;ncia</td>		
						<td>Data Envio</td>
						<td>Status</td>
						<td>&Aacute;rea Focal</td>
						<td>Contato</td>	
						</tr>
						";
						
						$tratArr .= "<tr>";
						$tratArr .= "<td>".utf8_decode($trat->id)."</td>";
						$tratArr .= "<td>".utf8_decode($trat->ultima_tratativa)."</td>";
						$tratArr .= "<td>".utf8_decode($trat->desc_pendencia)."</td>";						
						if($trat->data_envio_bd == '1900-01-01'){
							$tratArr .= "<td></td>";						
						}else{
							$tratArr .= "<td>".utf8_decode($trat->data_envio_bd)."</td>";
						}
													
						$tratArr .= "<td>".utf8_decode($trat->descricao)."</td>";
						$tratArr .= "<td>".utf8_decode($trat->descricao_area_focal)."</td>";
						$tratArr .= "<td>".utf8_decode($trat->contato)."</td>";
						$tratArr .= "<tr>";
						$tratArr .="</table>";
					}	
				}	
				
				if($emitente->valor_titulo == '0'){
					$emitente->valor_titulo = '';	
				}
				
				if($emitente->dados_cartorio == '0'){
					$emitente->dados_cartorio = '';	
				}
				
				if($emitente->folha == '0'){
					$emitente->folha = '';	
				}
				
				if($emitente->livro == '0'){
					$emitente->livro = '';	
				}
				
				if($emitente->relato_protesto == '0'){
					$emitente->relato_protesto = '';	
				}
				
				
				$test .= "<tr>";
				$test .= "<td>".utf8_decode($emitente->id)."</td>";
				$test .= "<td>".utf8_decode($emitente->cnpj_raiz)."</td>";
				$test .= "<td>".utf8_decode($emitente->cnpj)."</td>";
				$test .= "<td>".utf8_decode($emitente->cartorio)."</td>";
				$test .= "<td>".utf8_decode($emitente->dados_cartorio)."</td>";
				$test .= "<td>".utf8_decode($emitente->folha)."</td>";
				$test .= "<td>".utf8_decode($emitente->livro)."</td>";
				$test .= "<td>".utf8_decode($emitente->data_protesto_br == '01/01/1900' ? '' : $emitente->data_protesto_br)."</td>";
				$test .= "<td>".utf8_decode($emitente->numero_titulo)."</td>";
				$test .= "<td>".utf8_decode($emitente->valor_protestado)."</td>";
				$test .= "<td>".utf8_decode($emitente->data_baixa_protesto_br == '01/01/1900' ? '' : $emitente->data_baixa_protesto_br)."</td>";
				$test .= "<td>".utf8_decode($emitente->data_admissao_titulo_br == '01/01/1900' ? '' : $emitente->data_admissao_titulo_br)."</td>";
				$test .= "<td>".utf8_decode($emitente->vencimento_br == '01/01/1900' ? '' : $emitente->vencimento_br)."</td>";
				$test .= "<td>".utf8_decode($emitente->valor_titulo)."</td>";	
				$test .= "<td>".utf8_decode($emitente->descricao_natureza)."</td>";
				$test .= "<td>".utf8_decode($emitente->competencia_legis)."</td>";
				$test .= "<td>".utf8_decode($emitente->nome_apresentante)."</td>";
				$test .= "<td>".utf8_decode($emitente->cnpj_apresentante)."</td>";
				$test .= "<td>".utf8_decode($emitente->contato_apresentante)."</td>";				
				$test .= "<td>".utf8_decode($emitente->credor_favorecido)."</td>";
				$test .= "<td>".utf8_decode($emitente->cnpj_credor)."</td>";
				$test .= "<td>".utf8_decode($emitente->contato_credor)."</td>";				
				$test .= "<td>".utf8_decode($emitente->nr_cert_div_ativ)."</td>";				
				$test .= "<td>".utf8_decode($emitente->nr_auto_infracao)."</td>";
				$test .= "<td>".utf8_decode($emitente->relato_protesto)."</td>";	
				$test .= "<td>".utf8_decode($emitente->estado_protesto)."</td>";	
				$test .= "<td>".utf8_decode($emitente->cidade_protesto)."</td>";
				$test .= "<td>".($tratArr)."</td>";							
				$test .= "</tr>";				
			}
		}
		$test .='</table>';

		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$file");
		echo $test;	
		
 }	

	function cadastrar(){	
		$this->logado();    
		$session_data = $_SESSION['login_tejofran_protesto'];
		$idContratante = $session_data['id_contratante'] ;
		$data['op'] = 0;
		$data['perfil'] = $session_data['perfil'];
		$data['cnpj_raiz'] = $this->cnpj_model->listarCnpjRaiz($session_data['id_contratante'],0);
		$data['ocorrencias'] = $this->protesto_model->listarTipoOcorrencias();
		$this->load->view('header_pages_view',$data);
		$this->load->view('protesto/cadastrar_protesto_view', $data);
		$this->load->view('footer_pages_view');
	}

	function editar(){	
		$this->logado();    
		$referer = $_SERVER['HTTP_REFERER'] ?? '';

		if (isset($_SERVER['HTTP_REFERER'])) {
			$_SESSION['referer_guardado'] = $_SERVER['HTTP_REFERER'];
		}

		$path = parse_url($_SESSION['referer_guardado'], PHP_URL_PATH);
		$segments = explode('/', trim($path, '/'));
		$last = end($segments);
		$id = $this->input->get('id');		
		$retorno = $this->auxiliador->verificaID($id);
		if($retorno and $last == 1){
			redirect('protesto/listarBaixados', 'refresh');
		}elseif($retorno and $last == 0){
			redirect('protesto/listarOcorrencias', 'refresh');
		}

		$session_data = $_SESSION['login_tejofran_protesto'];
		$idContratante = $session_data['id_contratante'] ;
		$data['op'] = 1;
		$data['dados'] = $this->protesto_model->listarProtestoById($id);
		$data['ocorrencias'] = $this->protesto_model->listarTipoOcorrencias();
		$data['cnpj_raiz'] = $this->cnpj_model->listarCnpjRaiz($session_data['id_contratante'],0);
		if($data['dados']== false and $last == 1 or is_null($data['dados']) and $last == 1){
			redirect('protesto/listarBaixados', 'refresh');
		}elseif($data['dados']== false and $last == 0 or is_null($data['dados']) and $last == 0){
			redirect('protesto/listarOcorrencias', 'refresh');
		}

		$this->load->view('header_pages_view',$data);
		switch ($data['dados'][0]->tipo_ocorrencia) {
		case 1:
			$this->load->view('protesto/edita_protesto_view', $data);
			break;
		case 2:
			$this->load->view('protesto/edita_protesto_view', $data);
			break;
		case 3:
			$this->load->view('protesto/edita_protesto_view', $data);
			break;
		case 4:
			$this->load->view('protesto/editar_protesto_view', $data);
			break;
		case 5:
			$this->load->view('protesto/edita_protesto_view', $data);
			break;	
		case 6:
			$this->load->view('protesto/edita_protesto_view', $data);
			break;	
		case 7:
			$this->load->view('protesto/edita_protesto_view', $data);
			break;		
		}
	
		
		
		$this->load->view('footer_pages_view');
	}
	
	function arquivos(){	
		$this->logado();    
		$session_data = $_SESSION['login_tejofran_protesto'];
		$idContratante = $session_data['id_contratante'] ;
		$data['id'] = $id = $this->input->get('id');
		$data['dadosProtesto'] = $this->protesto_model->listarProtestoById($id);
		$data['dados'] = $this->protesto_model->listarArquivoProtestoById($id);

		$referer = $_SERVER['HTTP_REFERER'] ?? '';
		if (isset($_SERVER['HTTP_REFERER'])) {
			$_SESSION['referer_guardado'] = $_SERVER['HTTP_REFERER'];
		}
		$path = parse_url($_SESSION['referer_guardado'], PHP_URL_PATH);
		$segments = explode('/', trim($path, '/'));
		$last = end($segments);	
		$retorno = $this->auxiliador->verificaID($id);
		if($retorno and $last == 0){
			redirect('protesto/listarOcorrencias', 'refresh');
		}elseif($retorno and $last == 1){
			redirect('protesto/listarBaixados', 'refresh');
		}
		if($data['dadosProtesto']== false and $last == 1 or is_null($data['dadosProtesto']) and $last == 1){
			redirect('protesto/listarBaixados', 'refresh');
		}elseif($data['dadosProtesto']== false and $last == 0 or is_null($data['dadosProtesto']) and $last == 0){
			redirect('protesto/listarOcorrencias', 'refresh');
		}
		$this->load->view('header_pages_view',$data);
		$this->load->view('protesto/arquivos_protesto_view', $data);
		$this->load->view('footer_pages_view');
	}
	
	function salvarNomeArquivo(){	
		$id = $this->input->get('id');		
		$nomeArquivo = $this->input->get('nomeArquivo');	
		
		$dados = array(
		'nome_arquivo' => $nomeArquivo,
		);		
		$id = $this->protesto_model->atualizar('protesto_arquivos',$dados,$id);
		echo 1;
		
	}
	function enviar(){		
	$session_data = $_SESSION['login_tejofran_protesto'];
	$id = $this->input->get('id');		
	$arquivo = $this->input->get('arquivo');		
	$file = $_FILES["userfile"]["name"];
	$extensao = str_replace('.','',strrchr($file, '.'));						
	$base = base_url();		        
	$config['upload_path'] = './arquivos/protesto/';			
	$config['allowed_types'] = '*';		
	$config['overwrite'] = 'true';				
	$config['file_name'] = $id.'-'.$arquivo.'.'.$extensao;				
	$this->load->library('upload', $config);	
	$this->upload->initialize($config);		
	$field_name = "userfile";				
	
	$filename =  './arquivos/protesto/'.$id.'-'.$arquivo.'.pdf';
	if (file_exists($filename)) {
		unlink($filename);
	} 
	if (!$this->upload->do_upload($field_name)){			
		$error = array('error' => $this->upload->display_errors());						
		$_SESSION['mensagemIptu'] =  $this->upload->display_errors();
		echo '0'; 
	}else{		
	
		
		$dados = array(
		'id_protesto' => $id,
		'arquivo' => $id.'-'.$arquivo.'.pdf',
		);		
		$id = $this->protesto_model->addArqProt($dados);
		
		
		$this->gravarOperacao(4,8,$id);
		
		echo'1';

	}

}
	
 function listarNaturezaJson(){
	$session_data = $_SESSION['login_tejofran_protesto'];
	$idContratante = $session_data['id_contratante'] ;
	$data['perfil'] = $session_data['perfil'];
	
	echo json_encode($this->protesto_model->listarNatureza());
  
 }
 
 function listarOcorrencias(){
	$this->logado();    
	$session_data = $_SESSION['login_tejofran_protesto'];
	$idContratante = $session_data['id_contratante'] ;
	$data['ocorrencias'] = $this->protesto_model->listarTipoOcorrencias();
	$data['perfil'] = $session_data['perfil'];
	$data['status'] = 0;
	$this->load->view('header_pages_view',$data);
	$this->load->view('protesto/listar_ocorrencias_view', $data);
	$this->load->view('footer_pages_view');	
 }
 
 function listarBaixados(){
	$this->logado();    
	$session_data = $_SESSION['login_tejofran_protesto'];
	$idContratante = $session_data['id_contratante'] ;
	$data['ocorrencias'] = $this->protesto_model->listarTipoOcorrencias();
	$data['perfil'] = $session_data['perfil'];
	$data['status'] = 1;
	$this->load->view('header_pages_view',$data);
	$this->load->view('protesto/listar_ocorrencias_view', $data);
	$this->load->view('footer_pages_view');	
 }
		
function limparFitro(){
	$_SESSION['estadoFiltroProt'] = '';
	$_SESSION['cidadeFiltroProt'] = '';
	$_SESSION['cnpjFiltroProt'] = '';
	
	redirect('/protesto/listar/0', 'refresh');
}
	function listar($status){
		$this->logado();   
		$session_data = $_SESSION['login_tejofran_protesto'];
		$idContratante = $session_data['id_contratante'] ;
		$data['perfil'] = $session_data['perfil'];
		if(empty($_POST)){
			$_SESSION['estadoFiltroProt'] = $_SESSION['cidadeFiltroProt'] = $_SESSION['cnpjFiltroProt'] = 0;
			$data['dados'] = $this->protesto_model->listarprotesto(0,0,0,0,$status);
		}else{
			$_SESSION['estadoFiltroProt'] = $estado = $this->input->post('estado');
			$_SESSION['cidadeFiltroProt'] = $cidade = $this->input->post('cidade');
			$cnpjRaiz = $this->input->post('cnpjRaiz');
			$_SESSION['cnpjFiltroProt'] =$cnpj = $this->input->post('cnpj');
			
			$data['dados'] = $this->protesto_model->listarprotesto($estado,$cidade,$cnpjRaiz,$cnpj,$status);
			$this->gravarOperacao(4,3,0);
		}
		
		$data['ocorrencias'] = $this->protesto_model->listarTipoOcorrencias();
		$data['status'] = $status;
		$data['emails'] = $this->email_model->listarEmail(0);
		$this->load->view('header_pages_view',$data);
		$this->load->view('protesto/listar_protesto_view', $data);
		$this->load->view('footer_pages_view');
	}

	function listarPefin($status){
		$this->logado();    
		$session_data = $_SESSION['login_tejofran_protesto'];
		$idContratante = $session_data['id_contratante'] ;
		$data['perfil'] = $session_data['perfil'];
		if(empty($_POST)){
			$data['dados'] = $this->protesto_model->listarPefin(0,1,$status);
		}else{
			$cnpjRaiz = $this->input->post('cnpj');						
			$data['dados'] = $this->protesto_model->listarPefin($cnpjRaiz,1,$status);
			$this->gravarOperacao(1,3,0);
		}
		
		
	   
		$data['ocorrencias'] = $this->protesto_model->listarTipoOcorrencias();
		$data['status'] = $status;
		$data['emails'] = $this->email_model->listarEmail(0);
		$this->load->view('header_pages_view',$data);
		$this->load->view('protesto/listar_pefin_view', $data);
		$this->load->view('footer_pages_view');
	}
	
	
	
	function listarRefin($status){
		$this->logado();    
		$session_data = $_SESSION['login_tejofran_protesto'];
		$idContratante = $session_data['id_contratante'] ;
		$data['perfil'] = $session_data['perfil'];
		if(empty($_POST)){
			$data['dados'] = $this->protesto_model->listarPefin(0,2,$status);
		}else{
			$cnpjRaiz = $this->input->post('cnpj');						
			$data['dados'] = $this->protesto_model->listarPefin($cnpjRaiz,2,$status);
			$this->gravarOperacao(2,3,0);
		}
		
		$data['ocorrencias'] = $this->protesto_model->listarTipoOcorrencias();
		$data['status'] = $status;
		$data['emails'] = $this->email_model->listarEmail(0);
		$this->load->view('header_pages_view',$data);
		$this->load->view('protesto/listar_refin_view', $data);
		$this->load->view('footer_pages_view');
	}
	
	function listarAcao($status){
		$this->logado();    
		$session_data = $_SESSION['login_tejofran_protesto'];
		$idContratante = $session_data['id_contratante'] ;
		$data['perfil'] = $session_data['perfil'];
		if(empty($_POST)){
			$data['dados'] = $this->protesto_model->listarPefin(0,5,$status);
		}else{
			$cnpjRaiz = $this->input->post('cnpj');						
			$data['dados'] = $this->protesto_model->listarPefin($cnpjRaiz,5,$status);
			$this->gravarOperacao(5,3,0);
		}
		
		$data['ocorrencias'] = $this->protesto_model->listarTipoOcorrencias();
		$data['status'] = $status;
		$data['emails'] = $this->email_model->listarEmail(0);
		$this->load->view('header_pages_view',$data);
		$this->load->view('protesto/listar_acao_view', $data);
		$this->load->view('footer_pages_view');
	}
	
	function listarCheque($status){
		$this->logado();    
		$session_data = $_SESSION['login_tejofran_protesto'];
		$idContratante = $session_data['id_contratante'] ;
		$data['perfil'] = $session_data['perfil'];
		if(empty($_POST)){
			$data['dados'] = $this->protesto_model->listarPefin(0,3,$status);
		}else{
			$cnpjRaiz = $this->input->post('cnpj');						
			$data['dados'] = $this->protesto_model->listarPefin($cnpjRaiz,3,$status);
			$this->gravarOperacao(3,3,0);
		}
		
		$data['ocorrencias'] = $this->protesto_model->listarTipoOcorrencias();
		$data['status'] = $status;	
		$data['emails'] = $this->email_model->listarEmail(0);
		$this->load->view('header_pages_view',$data);
		$this->load->view('protesto/listar_cheque_view', $data);
		$this->load->view('footer_pages_view');
	}
	
	function listarFalencia($status){
		$this->logado();    
		$session_data = $_SESSION['login_tejofran_protesto'];
		$idContratante = $session_data['id_contratante'] ;
		$data['perfil'] = $session_data['perfil'];
		if(empty($_POST)){
			$data['dados'] = $this->protesto_model->listarPefin(0,6,$status);
		}else{
			$cnpjRaiz = $this->input->post('cnpj');						
			$data['dados'] = $this->protesto_model->listarPefin($cnpjRaiz,6,$status);
			$this->gravarOperacao(6,3,0);
		}
		
		$data['ocorrencias'] = $this->protesto_model->listarTipoOcorrencias();
		$data['status'] = $status;
		$data['emails'] = $this->email_model->listarEmail(0);
		$this->load->view('header_pages_view',$data);
		$this->load->view('protesto/listar_falencia_view', $data);
		$this->load->view('footer_pages_view');
	}
	
	function listarDivida($status){
		$this->logado();    
		$session_data = $_SESSION['login_tejofran_protesto'];
		$idContratante = $session_data['id_contratante'] ;
		$data['perfil'] = $session_data['perfil'];
		if(empty($_POST)){
			$data['dados'] = $this->protesto_model->listarPefin(0,7,$status);
		}else{
			$cnpjRaiz = $this->input->post('cnpj');						
			$data['dados'] = $this->protesto_model->listarPefin($cnpjRaiz,7,$status);
			$this->gravarOperacao(7,3,0);
		}
		
		$data['ocorrencias'] = $this->protesto_model->listarTipoOcorrencias();
		$data['status'] = $status;
		$data['emails'] = $this->email_model->listarEmail(0);
		$this->load->view('header_pages_view',$data);
		$this->load->view('protesto/listar_divida_view', $data);
		$this->load->view('footer_pages_view');
	}
	
	
	
	
	
	function listar_baixados(){
		$this->logado();    
		$session_data = $_SESSION['login_tejofran_protesto'];
		$idContratante = $session_data['id_contratante'] ;
		$data['perfil'] = $session_data['perfil'];
		$status = 1;
		if(empty($_POST)){
			$_SESSION['estadoFiltroProt'] = $_SESSION['cidadeFiltroProt'] = $_SESSION['cnpjFiltroProt'] = 0;
			$data['dados'] = $this->protesto_model->listarprotesto(0,0,0,0,$status);
		}else{
			$_SESSION['estadoFiltroProt'] = $estado = $this->input->post('estado');
			$_SESSION['cidadeFiltroProt'] = $cidade = $this->input->post('cidade');
			$cnpjRaiz = $this->input->post('cnpjRaiz');
			$_SESSION['cnpjFiltroProt'] =$cnpj = $this->input->post('cnpj');
			
			$data['dados'] = $this->protesto_model->listarprotesto($estado,$cidade,$cnpjRaiz,$cnpj,$status);
		}
		

		$data['emails'] = $this->email_model->listarEmail(0);
		$this->load->view('header_pages_view',$data);
		$this->load->view('protesto/listar_protesto_baixado_view', $data);
		$this->load->view('footer_pages_view');
	}
	
	function listarApp(){
		
		$estado = $_GET['estado'];
		$cidade = 0;$cnpjRaiz= 0;$cnpj= 0;
		$result = $this->protesto_model->listarprotestoapp($estado);
		echo json_encode($result);	
		
	
	}
function encaminhar(){
	
	
	date_default_timezone_set('America/Sao_Paulo');	
	$session_data = $_SESSION['login_tejofran_protesto'];
	$idContratante = $session_data['id_contratante'] ;
	$emailRemetente = $session_data['email'] ;
	$emailEnviar  = $this->input->post('email'); 
	$assunto  = $this->input->post('assunto');
	$idProtesto  = $this->input->post('id');
	$completo  = $this->input->post('completo');
	
	//$dadosEmail = $this->email_model->listarEmail($emailEnviar);
	
	$contaEmails = count($emailEnviar);
	$i=1;
	$stringEmails = '';
	foreach($emailEnviar as $email){				
		$dados = $this->email_model->listarEmail($email);
		if($i==$contaEmails){
			$stringEmails .= $dados[0]->email;
		}else{
			$stringEmails .= $dados[0]->email.',';	
		}
		$i++;
	}
	
	$list = array($stringEmails);
	
	$dados = array(
		'id_protesto' => $idProtesto,
		'id_email' => $stringEmails,
		'texto' => $assunto,
		'data_envio' => date("Y-m-d H:i:s"),
		);		
	$id = $this->protesto_model->addEnc($dados);

	$nomeArquivoRandomico = strtotime("now");
	
	
	$file = $_FILES["userfile"]["name"];
	
	$extensao = str_replace('.','',strrchr($file, '.'));						
	$base = base_url();		        
	$config['upload_path'] = './arquivos/enc_protesto/';			
	$config['allowed_types'] = '*';		
	$config['overwrite'] = 'true';				
	$config['file_name'] =$nomeArquivoRandomico.'.'.$extensao;				
	$this->load->library('upload', $config);	
	$this->upload->initialize($config);		
	$field_name = "userfile";				
	
	
	if (!$this->upload->do_upload($field_name)){			
		$error = array('error' => $this->upload->display_errors());						
		$_SESSION['mensagemIptu'] =  $this->upload->display_errors();
		
	}else{				
		$dadosArq = array(
		'id_protesto_enc' => $id,
		'arquivo' => $nomeArquivoRandomico.'.'.$extensao,	
		);			
		$this->protesto_model->addArqEnc($dadosArq);
		
	}
	
	$dadosProtesto = $this->protesto_model->listarProtestoById($idProtesto);

	if($completo == 1){
		$dadosEnc = $this->protesto_model->listarProtestoTrackingById($idProtesto);
		$textoEnc ='Histórico de Encaminhamento <BR><BR>';
		
		foreach($dadosEnc as $val){
			$textoEnc .="<span style='font-weight:bold'>Enviado para ".$val->nome." : ".$val->email." ".$val->data_envio_br."</span><BR>";
			$textoEnc .=$val->texto."<BR>";
			$textoEnc .="<BR>";

		}
	}else{
		$textoEnc ='';
	}
	
	
	
	
	$this->load->library('email');
	$this->email->from('notificacoes@bdservicos.com.br', 'BD WebNotifica');
	$this->email->cc($emailRemetente);
	$this->email->to($list);
		$this->email->subject('Sistema BD WebNotifica - Envio de Encaminhamento de Protesto' );
				
				
		$texto = '
		<strong>Não responda esse email.</strong> <BR> <hr> 
		Acesse o link abaixo para acessar o sistema.
		<BR>
		<a href="https://bdwebgestora.com.br/tejofran/index.php/protesto/tratativas?id='.$idProtesto.'" target="_blank" >https://bdwebgestora.com.br/tejofran/</a>
		<BR><BR>
			
		<strong>Dados da novo protesto</strong> <BR> <BR> 
		CNPJ Raiz:'.$dadosProtesto[0]->cnpj_raiz.'
		<BR>
		CNPJ : '.$dadosProtesto[0]->cnpj.'
		<BR>
		Credor Favorecido : '.$dadosProtesto[0]->credor_favorecido.'
		<BR>
		Cartório : '.$dadosProtesto[0]->cartorio.'
		<BR>
		Número Título : '.$dadosProtesto[0]->numero_titulo.'
		<BR>
		Valor Título : '.$dadosProtesto[0]->valor_titulo.'
		<BR>
		Data Protesto : '.$dadosProtesto[0]->data_protesto_br.'
		<BR>
		Vencimento : '.$dadosProtesto[0]->vencimento_br.'
		<BR>
		Breve Relato do Protesto : '.$dadosProtesto[0]->relato_protesto.'
		<BR><BR>
		Novo Encaminhamento : '.$assunto.'
		<BR><BR>
		'.$textoEnc.'
		<hr>
			
		Atenciosamente, <BR> <BR> <strong>Sistema BD WebNotifica <br> Apoio Técnico</strong>';
		$html = "<html><body style='font-family:Trebuchet MS'>".$texto."</body></html>";
		
		$this->email->set_mailtype("html");
		$this->email->set_alt_message($texto);
		$this->email->message($html);
		//$this->email->send();
	
	if($completo == 1){
		redirect('/protesto/tracking?id='.$idProtesto, 'refresh');
	}else{
		redirect('/protesto/listar/0', 'refresh');
	}
	
 }
 
 function verDadosProtesto(){
		$session_data = $_SESSION['login_tejofran_protesto'];
		$idContratante = $session_data['id_contratante'] ;
		$data['perfil'] = $session_data['perfil'];
	
		$id = $this->input->get('id');	
		echo json_encode($this->protesto_model->listarProtestoById($id));
  
	}
	
	function trackingApp(){	
	
		$id = $_REQUEST['id'];		
		$result = $this->protesto_model->listarProtestoTrackingById($id);		
		echo json_encode($result);
		
		
	}
	
	function arquivosApp(){	
		$id = $_REQUEST['id'];
		$urlArr=array();
		for($i=0;$i<=4;$i++){
			$filename = './arquivos/protesto/'.$id.'-'.$i.'.pdf';
			$arq = '/arquivos/protesto/'.$id.'-'.$i.'.pdf';
			if (file_exists($filename)) {
				$urlArr[$i]['url'] = 'https://bdwebgestora.com.br/tejofran/arquivos/protesto/'.$id.'-'.$i.'.pdf';				
			}else{
				$urlArr[$i]['url'] = '-';
			}
		}	
		echo json_encode($urlArr);
	}
	
 function tracking(){	
		$this->logado();    
		$session_data = $_SESSION['login_tejofran_protesto'];
		$idContratante = $session_data['id_contratante'] ;
		$data['id'] = $id = $this->input->get('id');
		
		$data['dadosProtesto'] = $this->protesto_model->listarProtestoById($id);
		
		$data['dados'] = $this->protesto_model->listarProtestoTrackingById($id);
		$data['emails'] = $this->email_model->listarEmail(0);
		$this->load->view('header_pages_view',$data);
		$this->load->view('protesto/tracking_view', $data);
		$this->load->view('footer_pages_view');
	}
	
 function listarEsferas(){	
	$id_pendencia = $this->input->get('id_pendencia');
	 echo json_encode($this->protesto_model->listarEsfera($id_pendencia));
	 
 } 
 function tratativas(){	
		$this->logado();    
		$session_data = $_SESSION['login_tejofran_protesto'];
		$idContratante = $session_data['id_contratante'] ;
		$data['id_cnd'] = $data['id'] = $id = $this->input->get('id');
		
		$referer = $_SERVER['HTTP_REFERER'] ?? '';
		if (isset($_SERVER['HTTP_REFERER'])) {
			$_SESSION['referer_guardado'] = $_SERVER['HTTP_REFERER'];
		}
		$path = parse_url($_SESSION['referer_guardado'], PHP_URL_PATH);
		$segments = explode('/', trim($path, '/'));
		$last = end($segments);	
		$retorno = $this->auxiliador->verificaID($id);
		if($retorno and $last == 0){
			redirect('protesto/listarOcorrencias', 'refresh');
		}elseif($retorno and $last == 1){
			redirect('protesto/listarBaixados', 'refresh');
		}

		if(empty($_SESSION['idTratativa'])){
			$idTratativa = 0;
		}else{
			$idTratativa = $_SESSION['idTratativa'];
		}
		$data['idTratativa'] = $idTratativa;
	
		//$data['tratativas'] = $this->protesto_model->listarProtestoTrackingById($id);
		$data['tratativas'] =  $this->protesto_model->listarTodasTratativas($id);

		$data['dados'] = $this->protesto_model->listarProtestoById($id);
		$data['pendencias'] = $this->protesto_model->listarPendencias();
		$data['areas'] = $this->protesto_model->listarAreaFocal();
		//$data['esferas'] = $this->protesto_model->listarEsfera();
		$data['etapas'] = $this->protesto_model->listarEtapa();
		$data['statusInterno'] = $this->protesto_model->listarStatusInterno();

		if($data['dados']== false and $last == 1 or is_null($data['dados']) and $last == 1){
			redirect('protesto/listarBaixados', 'refresh');
		}elseif($data['dados']== false and $last == 0 or is_null($data['dados']) and $last == 0){
			redirect('protesto/listarOcorrencias', 'refresh');
		}
		
		$this->load->view('header_pages_view',$data);
		
		switch ($data['dados'][0]->tipo_ocorrencia) {
			case 1:
				$this->load->view('protesto/tratativas_pefin_view', $data);
				break;
			case 2:
				$this->load->view('protesto/tratativas_refin_view', $data);
				break;
			case 3:			
				$this->load->view('protesto/tratativas_cheque_view', $data);
				break;
			case 4:
				$this->load->view('protesto/tratativas_view', $data);
				break;
			case 5:
				$this->load->view('protesto/tratativas_acao_view', $data);
				break;
			case 6:
				$this->load->view('protesto/tratativas_falencias_view', $data);
				break;	
			case 7:			
				$this->load->view('protesto/tratativas_dividas_view', $data);
				break;	
		}

		$this->load->view('footer_pages_view');
	}
	
	function listaObsTrat(){
	$base = $this->config->base_url().'index.php';
	$session_data = $_SESSION['login_tejofran_protesto'];
	//$idContratante = $_SESSION['id_contratante'] ;
	$id = $this->input->get('id');
	$controller = 'cnd_estadual';
	$data ='';
	$dados =  $this->cnd_federal_model->listarObsTratById($id);
	$base = $this->config->base_url();
	
	$isArrayLog =  is_array($dados) ? '1' : '0';
	if($isArrayLog == 1) {
		foreach($dados as $dado){			
			$arquivo = "<i style='color: rgb(0, 176, 240);' class='fa fa-eye ver_arquivo' id=".$dado->id." aria-hidden='true'></i>";				
			$data .= "<span >".$dado->data.' - '.$dado->hora.' - '.$dado->email.' - '.$dado->observacao.' - '.$arquivo."	</span> <BR>";
		}
	}else{
		$data .= "0";
	}
	
	
	
	echo json_encode($data);
	
 }
 
 function listarTratativaById(){
	$session_data = $_SESSION['login_tejofran_protesto'];
	$idContratante = $session_data['id_contratante'];	
	$id = $this->input->get('id');
	
	$data = array();
	$dados =  $this->protesto_model->listarTratativaById($idContratante,$id);
	
	$data['id'] = $dados[0]->id;
	$data['tipo_tratativa'] = $dados[0]->tipo_tratativa;
	$data['pendencia'] = $dados[0]->pendencia;
	$data['esfera'] = $dados[0]->esfera;
	$data['etapa'] = $dados[0]->etapa;
	$data['data_informe_pendencia'] = $dados[0]->data_informe_pendencia;
	$data['id_sis_ext'] = $dados[0]->id_sis_ext;
	$data['data_inclusao_sis_ext'] = $dados[0]->data_inclusao_sis_ext;
	$data['prazo_solucao_sis_ext'] = $dados[0]->prazo_solucao_sis_ext;
	$data['data_encerramento_sis_ext'] = $dados[0]->data_encerramento_sis_ext;
	$data['status_chamado_sis_ext'] = $dados[0]->status_chamado_sis_ext;
	$data['id_sla_sis_ext'] = $dados[0]->sla_sis_ext;
	$data['usu_inc'] = $dados[0]->usu_inc;
	$data['area_focal'] = $dados[0]->area_focal;
	$data['sub_area_focal'] = $dados[0]->sub_area_focal;
	$data['contato'] = $dados[0]->contato;
	$data['data_envio'] = $dados[0]->data_envio;
	$data['prazo_solucao'] = $dados[0]->prazo_solucao;
	$data['data_retorno'] = $dados[0]->data_retorno;
	$data['sla'] = $dados[0]->sla;
	$data['status_demanda'] = $dados[0]->status_demanda;
	$data['esc_data_prazo_um'] = $dados[0]->esc_data_prazo_um;
	$data['esc_data_retorno_um'] = $dados[0]->esc_data_retorno_um;
	$data['esc_status_um'] = $dados[0]->esc_status_um;
	$data['esc_data_prazo_dois'] = $dados[0]->esc_data_prazo_dois;
	$data['esc_data_retorno_dois'] = $dados[0]->esc_data_retorno_dois;
	$data['esc_status_dois'] = $dados[0]->esc_status_dois;
	$data['esc_data_prazo_tres'] = $dados[0]->esc_data_prazo_tres;
	$data['esc_data_retorno_tres'] = $dados[0]->esc_data_retorno_tres;
	$data['esc_status_tres'] = $dados[0]->esc_status_tres;
	$data['cnpj_pendencia'] = $dados[0]->cnpj_pendencia;
	$data['origem_pendencia'] = $dados[0]->origem_pendencia;
	$data['natureza_pendencia'] = $dados[0]->natureza_pendencia;
	$data['agrupamento'] = $dados[0]->agrupamento;
	$data['inscricao_divida_ativa'] = $dados[0]->inscricao_divida_ativa;
	$data['processo'] = $dados[0]->processo;
	$data['debito_cobranca_rfb'] = $dados[0]->debito_cobranca_rfb;
	$data['debito_cobranca_pgfn'] = $dados[0]->debito_cobranca_pgfn;
	$data['descricao_pendencia'] = $dados[0]->descricao_pendencia;
	$data['valor_pendencia'] = $dados[0]->valor_pendencia;

	echo json_encode($data);
	
}

function atualizar_cnd_mob_tratativa_unica(){
	 
	 $this->logado();
	 
	$session_data = $_SESSION['login_tejofran_protesto'];
	$idContratante = $session_data['id_contratante'];	
	$idUsu = $session_data['id'];


	$acao 	= $this->input->post('acao');
		
	$data_entrada_cadin 	= $this->input->post('data_entrada_cadin');
	
	
	if(empty($data_entrada_cadin)){
		$dtCadin = '1900-01-01';	
	}else{
		$dtCadinArr = explode("/",$data_entrada_cadin);	
		$dtCadin = $dtCadinArr[2].'-'.$dtCadinArr[1].'-'.$dtCadinArr[0];
	}
	$id_cnd 	= $this->input->post('id');
	
	$tipo_tratativa 	= $this->input->post('tipo_tratativa');
	if(empty($tipo_tratativa)){		
		$tipo_tratativa = $this->input->post('tipo_tratativa_bd');
		if(empty($tipo_tratativa)){
			$tipo_tratativa =0;
		}
	}
	
	$id_tratativa 	= $this->input->post('id_tratativa');
	$id_pendencia 	= $this->input->post('id_pendencia');
	$id_esfera 	= $this->input->post('id_esfera');
	$id_etapa 	= $this->input->post('id_etapa');
	$data_informe_pendencia 	= $this->input->post('data_informe_pendencia');
	$id_sis_ext 	= $this->input->post('id_sis_ext');
	$data_inclusao_sis_ext 	= $this->input->post('data_inclusao_sis_ext');
	$prazo_solucao_sis_ext 	= $this->input->post('prazo_solucao_sis_ext');
	$data_encerramento_sis_ext 	= $this->input->post('data_encerramento_sis_ext');
	$status_chamado_sis_ext 	= $this->input->post('status_chamado_sis_ext');
	
	$id_sla = $this->input->post('id_sla');
	if(empty($id_sla)){
		$id_sla = 0;
	}
	
	$usu_inc 	= $this->input->post('usu_inc');
	$area_focal 	= $this->input->post('area_focal');
	$sub_area_focal 	= $this->input->post('sub_area_focal');
	$contato 	= $this->input->post('contato');
	$data_envio 	= $this->input->post('data_envio');
	$prazo_solucao 	= $this->input->post('prazo_solucao');
	$data_retorno 	= $this->input->post('data_retorno');
	$sla 	= $this->input->post('sla');
	$status_demanda 	= $this->input->post('status_demanda');
	$esc_data_prazo_um 	= $this->input->post('esc_data_prazo_um');
	$esc_data_retorno_um 	= $this->input->post('esc_data_retorno_um');
	$esc_status_um 	= $this->input->post('esc_status_um');
	$esc_data_prazo_dois 	= $this->input->post('esc_data_prazo_dois');
	$esc_data_retorno_dois 	= $this->input->post('esc_data_retorno_dois');
	$esc_status_dois 	= $this->input->post('esc_status_dois');
	$esc_data_prazo_tres 	= $this->input->post('esc_data_prazo_tres');
	$esc_data_retorno_tres 	= $this->input->post('esc_data_retorno_tres');
	$esc_status_tres 	= $this->input->post('esc_status_tres');
	$nova_tratativa 	= $this->input->post('nova_tratativa');
	

	if(empty($data_informe_pendencia)){
		$dtInforme = '1900-01-01';	
	}else{
		if($data_informe_pendencia == '00/00/0000'){
			$dtInforme = '1900-01-01';	
		}else{
			$dataInformeArr = explode("/",$data_informe_pendencia);	
			$dtInforme = $dataInformeArr[2].'-'.$dataInformeArr[1].'-'.$dataInformeArr[0];
		}	
	}
	if(empty($data_inclusao_sis_ext)){
		$dtInclusaoSisExt = '1900-01-01';	
	}else{
		
		if($data_inclusao_sis_ext == '00/00/0000'){
			$dtInclusaoSisExt = '1900-01-01';	
		}else{
			$dataInformeArr = explode("/",$data_inclusao_sis_ext);	
			$dtInclusaoSisExt = $dataInformeArr[2].'-'.$dataInformeArr[1].'-'.$dataInformeArr[0];
		}	
		
		
	}
	
	if(empty($prazo_solucao_sis_ext)){
		$dtSolSisExt = '1900-01-01';	
	}else{
		
		
		if($prazo_solucao_sis_ext == '00/00/0000'){
			$dtSolSisExt = '1900-01-01';	
		}else{
			$dataSolArr = explode("/",$prazo_solucao_sis_ext);	
			$dtSolSisExt = $dataSolArr[2].'-'.$dataSolArr[1].'-'.$dataSolArr[0];
		}	
		
		
	}
	
	if(empty($data_encerramento_sis_ext)){
		$dtEncerSisExt = '1900-01-01';	
	}else{
		
		if($data_encerramento_sis_ext == '00/00/0000'){
			$dtEncerSisExt = '1900-01-01';	
		}else{
			$dataEncerramentoSisExtArr = explode("/",$data_encerramento_sis_ext);
			$dtEncerSisExt = $dataEncerramentoSisExtArr[2].'-'.$dataEncerramentoSisExtArr[1].'-'.$dataEncerramentoSisExtArr[0];
		}
		
		
	}
	
	if(empty($prazo_solucao)){
		$dtPrazoSolucao = '1900-01-01';	
	}else{
		
		if($prazo_solucao == '00/00/0000'){
			$dtPrazoSolucao = '1900-01-01';	
		}else{
			$dtPrazoSolucaoArr = explode("/",$prazo_solucao);		
			$dtPrazoSolucao = $dtPrazoSolucaoArr[2].'-'.$dtPrazoSolucaoArr[1].'-'.$dtPrazoSolucaoArr[0];
		}	
		
	}
	
	if(empty($data_envio)){
		$dtEnvio = '1900-01-01';	
	}else{
		
		if($data_envio == '00/00/0000'){
			$dtEnvio = '1900-01-01';	
		}else{
			$dataEnvioArr = explode("/",$data_envio);
			$dtEnvio = $dataEnvioArr[2].'-'.$dataEnvioArr[1].'-'.$dataEnvioArr[0];
		}	
		
		
	}
	
	if(empty($data_retorno)){
		$dtRetorno = '1900-01-01';	
	}else{
		
		if($data_retorno == '00/00/0000'){
			$dtRetorno = '1900-01-01';	
		}else{
			$dataRetornoArr = explode("/",$data_retorno);
			$dtRetorno = $dataRetornoArr[2].'-'.$dataRetornoArr[1].'-'.$dataRetornoArr[0];
		}	
		
	}
	
	if(empty($esc_data_prazo_um)){
		$escDtPrazoUm = '1900-01-01';	
	}else{
		
		if($esc_data_prazo_um == '00/00/0000'){
			$escDtPrazoUm = '1900-01-01';	
		}else{
			$escDataPrazoUmArr = explode("/",$esc_data_prazo_um);
			$escDtPrazoUm = $escDataPrazoUmArr[2].'-'.$escDataPrazoUmArr[1].'-'.$escDataPrazoUmArr[0];
		}	
		
		
		
	}
	
	if(empty($esc_data_retorno_um)){
		$escDtRetUm = '1900-01-01';	
	}else{
		$escDataPrazoUmArr = explode("/",$esc_data_retorno_um);
		$escDtRetUm = $escDataPrazoUmArr[2].'-'.$escDataPrazoUmArr[1].'-'.$escDataPrazoUmArr[0];
	}
	
	if(empty($esc_data_prazo_dois)){
		$escDtPrazoDois = '1900-01-01';	
	}else{
		$escDataPrazoDoisArr = explode("/",$esc_data_prazo_dois);
		$escDtPrazoDois = $escDataPrazoDoisArr[2].'-'.$escDataPrazoDoisArr[1].'-'.$escDataPrazoDoisArr[0];
	}
	
	if(empty($esc_data_retorno_dois)){
		$escDtRetDois = '1900-01-01';	
	}else{
		$escDataRetornoDoisArr = explode("/",$esc_data_retorno_dois);
		$escDtRetDois = $escDataRetornoDoisArr[2].'-'.$escDataRetornoDoisArr[1].'-'.$escDataRetornoDoisArr[0];
	}
	

	if(empty($esc_data_prazo_tres)){
		$escDtPrazoTres = '1900-01-01';	
	}else{
		$escDataPrazoTresArr = explode("/",$esc_data_prazo_tres);
		$escDtPrazoTres = $escDataPrazoTresArr[2].'-'.$escDataPrazoTresArr[1].'-'.$escDataPrazoTresArr[0];
	}
	
	if(empty($esc_data_retorno_tres)){
		$escDtRetTres = '1900-01-01';	
	}else{
		$escDataRetornoDoisArr = explode("/",$esc_data_retorno_tres);
		$escDtRetTres = $escDataRetornoDoisArr[2].'-'.$escDataRetornoDoisArr[1].'-'.$escDataRetornoDoisArr[0];
	}


	
	$dados = array(

		'id_contratante' => $idContratante,
		'tipo_tratativa' => $tipo_tratativa,
		'id_sis_ext' => $id_sis_ext,
		'id_cnd_mob' => $id_cnd,
		'pendencia' => $id_pendencia,
		'esfera' => $id_esfera,
		'etapa' => $id_etapa,
		'data_informe_pendencia' => $dtInforme ,
		'data_inclusao_sis_ext' => $dtInclusaoSisExt,
		'prazo_solucao_sis_ext' => $dtSolSisExt ,
		'data_encerramento_sis_ext' => $dtEncerSisExt,
		'sla_sis_ext' => $id_sla,
		'status_chamado_sis_ext' => $status_chamado_sis_ext , 
		'usu_inc' => $usu_inc,
		'area_focal' => $area_focal,
		'sub_area_focal' => $sub_area_focal,
		'contato' => $contato ,
		'data_envio' =>$dtEnvio,
		'prazo_solucao' => $dtPrazoSolucao,
		'data_retorno' =>$dtRetorno,
		'sla' => $sla,
		'status_demanda' => $status_demanda,
		'esc_data_prazo_um' =>$escDtPrazoUm,
		'esc_data_retorno_um' =>$escDtRetUm,
		'esc_status_um' => $esc_status_um,
		'esc_data_prazo_dois' =>$escDtPrazoDois,
		'esc_data_retorno_dois' =>$escDtRetDois,
		'esc_status_dois' => $esc_status_dois ,
		'esc_data_prazo_tres' =>$escDtPrazoTres,
		'esc_data_retorno_tres' =>$escDtRetTres ,
		'esc_status_tres' => $esc_status_tres,
		'modulo'=>1,
		'data_atualizacao' => date("Y-m-d H:i:s") 

		);

		if($acao == 1){
			$id_tratativa = $this->protesto_model->add_tratativa($dados);	
			$this->gravarOperacao(4,9,$id_tratativa);	
		}else{
			if(($status_chamado_sis_ext <> 1) && ($status_chamado_sis_ext <> 0)){
				$this->gravarOperacao(4,12,$id_tratativa);	
			}else{
				$this->gravarOperacao(4,10,$id_tratativa);	
			}
			$this->protesto_model->atualizar_tratativa($dados,$id_tratativa);
					
		}
		
		//guardando id para abrir novamente apos salvar
		$_SESSION['idTratativa'] = $id_tratativa;
		

		
		if(($status_chamado_sis_ext <> 1) && ($status_chamado_sis_ext <> 0)){
			
			if(!empty($nova_tratativa)){
				$dadosNovaTratativa = array(	
				'id_contratante' => $idContratante,
				'id_cnd_trat' => $id_tratativa,
				'observacao' =>$nova_tratativa,
				'id_usuario' => $idUsu,
				'data' => date("Y-m-d"),
				'hora'=> date("H:i:s"),
				'modulo'=>1,
				'data_hora' => date("Y-m-d H:i:s") 
			
				);
				$this->protesto_model->addObsTrat($dadosNovaTratativa);
			}
		
			$email = $this->user->buscaEmailById($idUsu);
			
			$dadosNovaTratativa = array(	
			'id_contratante' => $idContratante,
			'id_cnd_trat' => $id_tratativa,
			'observacao' =>'Tratativa Cancelada/Encerrada',
			'id_usuario' => $idUsu,
			'data' => date("Y-m-d"),
			'hora'=> date("H:i:s"),
			'modulo'=>1,
			'data_hora' => date("Y-m-d H:i:s") 
		
			);
			$this->protesto_model->addObsTrat($dadosNovaTratativa);
			
		}else{
			
			$dados =  $this->protesto_model->listarObsTratById($id_tratativa);
			$isArray =  is_array($dados) ? '1' : '0';

			if($isArray == 0){
			
				$email = $this->user->buscaEmailById($idUsu);
				
				$dadosNovaTratativa = array(	
				'id_contratante' => $idContratante,
				'id_cnd_trat' => $id_tratativa,
				'observacao' =>'Tratativa Aberta',
				'id_usuario' => $idUsu,
				'data' => date("Y-m-d"),
				'hora'=> date("H:i:s"),
				'modulo'=>1,
				'data_hora' => date("Y-m-d H:i:s") 		
				);
				$this->protesto_model->addObsTrat($dadosNovaTratativa);
			}
			
			if(!empty($nova_tratativa)){
				$dadosNovaTratativa = array(	
				'id_contratante' => $idContratante,
				'id_cnd_trat' => $id_tratativa,
				'observacao' =>$nova_tratativa,
				'id_usuario' => $idUsu,
				'data' => date("Y-m-d"),
				'hora'=> date("H:i:s"),
				'modulo'=>1,
				'data_hora' => date("Y-m-d H:i:s") 
			
				);
				$this->protesto_model->addObsTrat($dadosNovaTratativa);
			}

		}
		
	redirect('/protesto/tratativas?id='.$id_cnd);

		
}

function visao_interna_cnd_mob(){

   $this->logado();
   
   
   if(empty($_SESSION['idTratativa'])){
		$idTratativa = 0;
	}else{
		$idTratativa = $_SESSION['idTratativa'];
	}
	
	
	$data['idTratativa'] = $idTratativa;
	$id = $this->input->get('id');
	$session_data = $_SESSION['login_tejofran_protesto'];
	$data['email'] = $session_data['email'];
	$data['empresa'] = $session_data['empresa'];
	$data['perfil'] = $session_data['perfil'];
	//$data['adm'] = $session_data['adm'];
	
	$idContratante = $session_data['id_contratante'];
	
	$data['obs'] = $this->protesto_model->buscaTodasObservacoes($id,1);	

	//$data['imovel'] = $this->protesto_model->listarInscricaoByLoja($id);	
	
	$data['id_cnd'] = $id;
	$data['tratativas'] =  $this->protesto_model->listarTodasTratativas($idContratante,$id,1);
	$data['esferas'] = $this->protesto_model->listarEsfera();
	$data['etapas'] = $this->protesto_model->listarEtapa();
	$data['statusInterno'] = $this->protesto_model->listarStatusInterno();
	//$data['statusDemanda'] = $this->protesto_model->listarStatusDemanda();
	
	$data['empresa'] = $session_data['empresa'];
	$data['nome_modulo'] = 'Cnd Mobiliária';	
	$data['modulo'] = '1';	
	
	$data['usuarios']  = $this->user->dadosUsuarios();
	
	$this->load->view('header_pages_view',$data);
    $this->load->view('cnd_mob_visao_unica_view', $data);
	$this->load->view('footer_pages_view');

}

	function logado(){	
		if(! $_SESSION['login_tejofran_protesto']) {	
		 redirect('login', 'refresh');
		}			
	}  



function listarTratativaMobById(){
	$session_data = $_SESSION['login_tejofran_protesto'];
	$idContratante = $session_data['id_contratante'];	
	$id = $this->input->get('id');
	
	$data = array();
	$dados =  $this->protesto_model->listarTratativaById($idContratante,$id);
	
	$data['id'] = $dados[0]->id;
	$data['tipo_tratativa'] = $dados[0]->tipo_tratativa;
	$data['pendencia'] = $dados[0]->pendencia;
	$data['esfera'] = $dados[0]->esfera;
	$data['etapa'] = $dados[0]->etapa;
	$data['data_informe_pendencia'] = $dados[0]->data_informe_pendencia;
	$data['id_sis_ext'] = $dados[0]->id_sis_ext;
	$data['data_inclusao_sis_ext'] = $dados[0]->data_inclusao_sis_ext;
	$data['prazo_solucao_sis_ext'] = $dados[0]->prazo_solucao_sis_ext;
	$data['data_encerramento_sis_ext'] = $dados[0]->data_encerramento_sis_ext;
	$data['status_chamado_sis_ext'] = $dados[0]->status_chamado_sis_ext;
	$data['id_sla_sis_ext'] = $dados[0]->sla_sis_ext;
	$data['usu_inc'] = $dados[0]->usu_inc;
	$data['area_focal'] = $dados[0]->area_focal;
	$data['sub_area_focal'] = $dados[0]->sub_area_focal;
	$data['contato'] = $dados[0]->contato;
	$data['data_envio'] = $dados[0]->data_envio;
	$data['prazo_solucao'] = $dados[0]->prazo_solucao;
	$data['data_retorno'] = $dados[0]->data_retorno;
	$data['sla'] = $dados[0]->sla;
	$data['status_demanda'] = $dados[0]->status_demanda;
	$data['esc_data_prazo_um'] = $dados[0]->esc_data_prazo_um;
	$data['esc_data_retorno_um'] = $dados[0]->esc_data_retorno_um;
	$data['esc_status_um'] = $dados[0]->esc_status_um;
	$data['esc_data_prazo_dois'] = $dados[0]->esc_data_prazo_dois;
	$data['esc_data_retorno_dois'] = $dados[0]->esc_data_retorno_dois;
	$data['esc_status_dois'] = $dados[0]->esc_status_dois;
	$data['esc_data_prazo_tres'] = $dados[0]->esc_data_prazo_tres;
	$data['esc_data_retorno_tres'] = $dados[0]->esc_data_retorno_tres;
	$data['esc_status_tres'] = $dados[0]->esc_status_tres;
	echo json_encode($data);
	
}	


 function listaObsTratEst(){
	$base = $this->config->base_url().'index.php';
	$session_data = $_SESSION['login_tejofran_protesto'];
	//$idContratante = $_SESSION['id_contratante'] ;
	$id = $this->input->get('id');
	$modulo = $this->input->get('modulo');
	$controller = 'cnd_estadual';
	$data ='';
	$dados =  $this->protesto_model->listarObsTratById($id,$modulo);
	
	$base = $this->config->base_url();
	
	$isArrayLog =  is_array($dados) ? '1' : '0';
	if($isArrayLog == 1) {
		foreach($dados as $dado){			
			// if($session_data['adm'] == 3){
				// $excluir = " - <i style='color: rgb(0, 176, 240);' title='Excluir Tratativa' class='fa fa-trash excluirTratativa' id=".$dado->id." aria-hidden='true'></i>";				
			// }else{
				// $excluir ="";
			// }
			$excluir = " - <i style='color: rgb(0, 176, 240);' title='Excluir Tratativa' class='fa fa-trash excluirTratativa' id=".$dado->id." aria-hidden='true'></i>";				
			$arquivo = "<i style='color: rgb(0, 176, 240);' class='fa fa-eye ver_arquivo' id=".$dado->id." aria-hidden='true'></i>";				
			if($dado->status == 0){
				$data .= "<span style='font-weight:bold'>".$dado->data.' - '.$dado->hora.' - '.$dado->email.'</span><span> - '.$dado->observacao.' - '.$arquivo." ".$excluir."	</span> <BR>";
			}else{
				
				$data .= "<span style='font-weight:bold'>".$dado->data.' - '.$dado->hora.' - '.$dado->email.'</span><span> - '.$dado->observacao.' - '.$arquivo."	</span> <BR>";
			}			
			
			
			
		}
	}else{
		$data .= "0";
	}
	
	
	
	echo json_encode($data);
	
 }	
 
 function excluirTratativa(){
	$id = $this->input->get('id');	
	$this->protesto_model->excluirTratativa($id);	
	$obj['tem'] = 0; 	
	echo(json_encode($obj));
	
 }
 
  function listaObsTratCheck(){
	 $base = $this->config->base_url().'index.php';
	$session_data = $_SESSION['login_tejofran_protesto'];
	$idContratante = $session_data['id_contratante'];	
	$id = $this->input->get('id');
	$controller = 'cnd_estadual';
	$data ='';
	$dados =  $this->protesto_model->listarObsTratById($id);
	
	$isArrayLog =  is_array($dados) ? '1' : '0';
	if($isArrayLog == 1) {
		foreach($dados as $dado){
			$data .= "<span> <input type='radio' name='obsId' value='$dado->id'>".$dado->data.' - '.$dado->hora.' - '.$dado->email.' - '.$dado->observacao."	</span> <BR>";
		}
	}else{
		$data .= "<span></span>";
	}
	
	
	
	echo json_encode($data);
	
 }	
 
 
 function upload_obs(){		

	$session_data = $_SESSION['login_tejofran_protesto'];

	
	$data['controller'] = 'falencia_concordata';
	$controller = 'falencia_concordata';
	
	$id = $this->input->post('obsId');		
	$id_cnd_mob = $this->input->post('id_cnd_mob');		
	$modulo = $this->input->post('modulo');		
	
	$dados =  $this->protesto_model->listarUltimaObsTratById($id);
	
	$file = $_FILES["userfile"]["name"];				

	$extensao = str_replace('.','',strrchr($file, '.'));						

	$base = base_url();		        

	$config['upload_path'] = './assets/observacoes/';		

	$config['allowed_types'] = '*';		

	$config['overwrite'] = 'true';				

	$config['file_name'] = $id_cnd_mob.'-'.$id.'.'.$extensao;				

	$this->load->library('upload', $config);	

	$this->upload->initialize($config);		

	$field_name = "userfile";				

	if (!$this->upload->do_upload($field_name)){			
		$error = array('error' => $this->upload->display_errors());						
		$_SESSION['mensagemIptu'] =  $this->upload->display_errors();
	}else{			
		$dados = array(
			'id_tratativas' => $id,
			'arquivo' => $id_cnd_mob.'-'.$id.'.'.$extensao,
			'modulo' => 0,
		);							
		$this->protesto_model->inserirNovoArquivo($dados,$id);	
		$data = array('upload_data' => $this->upload->data($field_name));		
		$_SESSION['mensagemIptu'] =  UPLOAD;
		$this->gravarOperacao(4,13,$id);	

	}
	


redirect('protesto/tratativas?id='.$id_cnd_mob, 'refresh');		

}


function listaArquivosMob(){
	$base = $this->config->base_url().'index.php';
	$session_data = $_SESSION['login_tejofran_protesto'];
	//$idContratante = $_SESSION['id_contratante'] ;
	$id = $this->input->get('id');
	$controller = 'cnd_estadual';
	$data ='';
	$dados =  $this->protesto_model->listarArquivosMobiliaria($id);
	
	$base = $this->config->base_url();
	
	$isArrayLog =  is_array($dados) ? '1' : '0';
	if($isArrayLog == 1) {
		foreach($dados as $dado){
			
			if(!empty($dado->arquivo)){
				$arquivo = "<a href=".$base."assets/observacoes/".$dado->arquivo." target='_blank'>  <i class='fa fa-download' aria-hidden='true'></i></a>";				
				$data .= "<span>".$dado->arquivo." ".$arquivo."</span> <BR>";
			}else{
				$data .= "<span>Sem Arquivo</span> <BR>";	
			}
			
		}
	}else{
		$data .= "<span>Sem Arquivo</span>";
	}
	
	
	
	echo json_encode($data);
	
 }	
 
 
  function export_tratativas_ext(){	
	$id = $this->input->get('id');
	//$dados = $this->protesto_model->listarInscricaoByLoja($id);
	$dados = $this->protesto_model->listarProtestoById($id);
	
	$this->csvTratativaMobExt($dados);

	 
 } 
 function gravarOperacao($tipo,$idOperacao,$idTabela){
    $session_data = $_SESSION['login_tejofran_protesto'];	 
	$tipoOperacao = tipoOperacao($tipo);	
	$dadosReg = array(
		'id_usuario' => $session_data['id'] ,
		'id_operacao' => $idOperacao,
		'id_tabela' => $idTabela,
		'texto' => $tipoOperacao,
		'data' => date("Y-m-d H:i:s"),		
	);
				
	$dados = $this->registro_model->inserir($dadosReg);
		
 }
function csvTratativaMobExt($result){
	$session_data = $_SESSION['login_tejofran_protesto'];
	$idContratante = $session_data['id_contratante'];	
				
	$file="tratativas_protesto.xls";
	if($result == 0){	
		$test="<table border=1>
		<tr>
		<td>N&atilde;o H&aacute; Dados Para Esse Filtro</td>
		</tr>
		";
		$test .='</table>';
    }else{	
	
	
			$test="<table border=1>
			<tr>
			<td>CNPJ Raiz</td>
			<td>CNPJ</td>
			<td>N&uacute;mero T&iacute;tulo</td>
			<td>Cart&oacute;rio</td>
			</tr>
			";
			foreach($result as $key => $iptu){ 	
				$cor='#fff';
				$test .= "<tr >";
				$test .= "<td>".utf8_decode($iptu->cnpj_raiz)."</td>";
				$test .= "<td>".utf8_decode($iptu->cnpj)."</td>";
				$test .= "<td>".utf8_decode($iptu->numero_titulo)."</td>";
				$test .= "<td>".utf8_decode($iptu->cartorio)."</td>";
				$test .= "</tr>";

			}
				
				$dadosPendencia = $this->protesto_model->listarTratativasIdCnd($iptu->id);				
				
				$test .= "<tr >";
				$test .= "<td >".utf8_decode('Tratativa')."</td>";
				$test .= "<td >".utf8_decode('Tipo Pendência')."</td>";
				$test .= "<td >".utf8_decode('Esfera')."</td>";
				$test .= "<td >".utf8_decode('Etapa')."</td>";
				$test .= "<td >".utf8_decode('Tratativa da Pendência')."</td>";
				$test .= "<td >".utf8_decode('Área Focal')."</td>";
				$test .= "<td >".utf8_decode('Sub Área Focal')."</td>";
				$test .= "<td >".utf8_decode('Contato')."</td>";
				$test .= "<td >".utf8_decode('Data Envio')."</td>";
				$test .= "<td >".utf8_decode('Status Chamado')."</td>";
				$test .= "<td >".utf8_decode('Data Encerramento')."</td>";
				$test .= "<td >".utf8_decode('SLA')."</td>";
				$test .= "<td >".utf8_decode('Histórico')."</td>";
			
				$test .= "</tr>";
				foreach($dadosPendencia as $key => $pend){ 
					
					$observacoes='';
					
					$observacoes ='';
					$dados =  $this->protesto_model->listarObsTratById($pend->id);
					 $isArrayHist =  is_array($dados) ? '1' : '0';
					 if($isArrayHist == 1){
						foreach($dados as $dado){
							$observacoes .= $dado->data.' - '.$dado->hora.' - '.$dado->email.' - '.$dado->observacao."<BR>";
						} 
					 }else{
						$observacoes .= 'Sem históricos';
					  }
					  
					  
					if($pend->tipo_tratativa == 1){
						$tipoTratavia ='Áreas Externas';
					}else{
						$tipoTratavia ='Áreas Internas';
					}
					if($pend->status_chamado_sis_ext ==1){
						$statusChamado ='Em Atendimento';
					}elseif($pend->status_chamado_sis_ext ==2){
						$statusChamado ='Cancelado';
					}elseif($pend->status_chamado_sis_ext ==3){
						$statusChamado ='Encerrado';
					}else{
						$statusChamado ='Sem Status Definido';
					}
					$test .= "<tr >";
					$test .= "<td >".utf8_decode($pend->id)."</td>";
					$test .= "<td >".utf8_decode($pend->pendencia)."</td>";
					$test .= "<td >".utf8_decode($pend->descricao_esfera)."</td>";
					$test .= "<td >".utf8_decode($pend->descricao_etapa)."</td>";
					$test .= "<td >".utf8_decode($tipoTratavia)."</td>";
					$test .= "<td >".utf8_decode($pend->area_focal)."</td>";
					$test .= "<td >".utf8_decode($pend->sub_area_focal)."</td>";
					$test .= "<td >".utf8_decode($pend->contato)."</td>";
					$test .= "<td >".utf8_decode($pend->data_envio)."</td>";
					$test .= "<td >".utf8_decode($statusChamado)."</td>";
					$test .= "<td >".utf8_decode($pend->data_encerramento_sis_ext)."</td>";
					$test .= "<td >".utf8_decode($pend->sla_sis_ext)."</td>";
					$test .= "<td >".utf8_decode($observacoes)."</td>";
					
					
					$test .= "</tr>";
				}
				
			$test .='</table>';

		}
		
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$file");
		echo $test;	
 }
 
}

 
 

?>