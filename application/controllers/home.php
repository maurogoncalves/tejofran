<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//
class Home extends CI_Controller {
 
 function __construct()
 {
   parent::__construct();
   
   $this->load->model('user','',TRUE);
   $this->load->model('registro_model','',TRUE);
   $this->load->library('session');
   $this->load->helper('url');
   $this->load->helper('general_helper');
   $this->load->library('form_validation');
   $this->load->model('estado_model','',TRUE);
   $this->load->model('notificacao_model','',TRUE);
   $this->load->model('infracao_model','',TRUE);
   $this->load->model('protesto_model','',TRUE);
   date_default_timezone_set('America/Sao_Paulo');
   session_start();
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, OPTIONS, POST');
	header('Access-Control-Allow-Headers: origin, x-requested-with,Content-Type, Content-Range, Content-Disposition, Content-Description');
 }
 
  function contagem(){	
	$session_data = $_SESSION['login_tejofran_protesto'];
	$idContratante = $session_data['id_contratante'] ;
	$estadosCnds = array();
	
	$estado = $this->input->get('estado');	
	$contagemNotificacao = $this->notificacao_model->contaNotificacaoByUf($estado);
	$contagemInfracao = $this->infracao_model->contaInfracoesByUf($estado);
	$contagemProtesto = $this->protesto_model->contaProtestoByUf($estado);
	
	$estadosCnds['contagemNotificacao'] = $contagemNotificacao[0]->total;
	$estadosCnds['contagemInfracao'] = $contagemInfracao[0]->total;
	$estadosCnds['contagemProtesto'] = $contagemProtesto[0]->total;
	
	
	echo json_encode($estadosCnds);
	
 }	
 
 function dashboard(){	
	
	if(! $_SESSION['login_tejofran_protesto']){
		redirect('login', 'refresh');
	}
	$session_data = $_SESSION['login_tejofran_protesto'];
	$idContratante = $session_data['id_contratante'] ;
	$data['perfil'] = $session_data['perfil'];
	
	 if($session_data['primeiro_acesso'] == 0){
		 redirect('home/perfil', 'refresh');	
	 }	 
	 
	
	if(empty($_POST)){
		$tipoOcorrencia = 4;
		$data['descTipo'] = 'Protesto';
		
		
	   
	}else{
		$tipOcorrencia = $this->input->post('tipoOcorrencia');
		if($tipOcorrencia == 0){
			$data['tipoOcorrencia'] =  $tipoOcorrencia ;
			$data['descTipo'] = 'Protesto';
			
		}else{
			$tipoArr = explode("-",$tipOcorrencia);
			$data['tipoOcorrencia'] = $tipoOcorrencia = $tipoArr[0];
			$data['descTipo'] = $tipoArr[1];
		}
		
		
		$tipoOperacao = tipoOperacao($tipoArr[0]);
		
		$dadosReg = array(
			'id_usuario' => $session_data['id'] ,
			'id_operacao' => 2,
			'texto' => $tipoOperacao,
			'data' => date("Y-m-d H:i:s"),		
		);
				
	   $dados = $this->registro_model->inserir($dadosReg);
	   
	}
	//$tipoOcorrencia = 4;
	
	$contaProtestoStatusEncerrado = $this->protesto_model->contaProtestoByStatus(1,$tipoOcorrencia);
	$contaProtestoStatusAberto = $this->protesto_model->contaProtestoByStatus(0,$tipoOcorrencia);
	
	$data['encerrado'] = $contaProtestoStatusEncerrado[0]->total;
	$data['aberto'] = $contaProtestoStatusAberto[0]->total;
	
	$contaProtestoTipoOco = $this->protesto_model->contaProtestoTipoOcorrencia($tipoOcorrencia);
	
	
	$labelsTipoOcorrencia ='';
	if($contaProtestoTipoOco == 0){
		$data['labelsTipoOcorrencia'] = '';
		$data['contaProtestoTipoOco'] = '';
		$data['coresOco'] ='';	
	}else{
		$cores ='';
		foreach($contaProtestoTipoOco as $pen){
			$labelsTipoOcorrencia .= '"'.$pen->descricao.'",';
			$cores .= '"'.$pen->cor.'",';
		}
		$data['labelsTipoOcorrencia'] = $labelsTipoOcorrencia;
		$data['contaProtestoTipoOco'] = $contaProtestoTipoOco;	
		$data['coresOco'] =$cores;	
	}
	
	$contaProtestoDetalhe = $this->protesto_model->contaProtestoByDetalhe($tipoOcorrencia);
	
	
	$labelsProtesto ='';
	if($contaProtestoDetalhe == 0){
		$data['labelProtesto'] = '';
		$data['contaProtestoDetalhe'] = '';
		$data['cores'] ='';	
	}else{
		$cores ='';
		foreach($contaProtestoDetalhe as $pen){
			$labelsProtesto .= '"'.$pen->status.'",';
			$cores .= '"'.$pen->cor.'",';
		}
		$data['labelProtesto'] = $labelsProtesto;
		$data['contaProtestoDetalhe'] = $contaProtestoDetalhe;	
		$data['cores'] =$cores;	
	}
	
	$contaProtestoDetalhePendencia = $this->protesto_model->contaProtestoByDetalhePendencia($tipoOcorrencia);
	$labelsDetalhePendencia ='';
	if($contaProtestoDetalhePendencia == 0){
		$data['labelPendencia'] = '';
		$data['contaProtestoDetalhePendencia'] = '';
		$data['coresPendencia'] ='';	
	}else{
		$coresDetPend ='';
		foreach($contaProtestoDetalhePendencia as $pen){
			$labelsProtesto .= '"'.$pen->status.'",';
			$coresDetPend .= '"'.$pen->cor.'",';
		}
		$data['labelPendencia'] = $labelsProtesto;
		$data['contaProtestoDetalhePendencia'] = $contaProtestoDetalhePendencia;	
		$data['coresPendencia'] =$coresDetPend;	
	}
	
	
	$contaProtestoResp = $this->protesto_model->contaProtestoByResp($tipoOcorrencia);
	
	$labelsResp ='';
	if($contaProtestoResp == 0){
		$data['labelsResp'] = '';
		$data['contaProtestoResp'] = '';
		$data['coresResp'] ='';	
	}else{
		$coresResp ='';
		foreach($contaProtestoResp as $pen){
			$labelsProtesto .= '"'.$pen->status.'",';
			$coresResp .= '"'.$pen->cor.'",';
		}
		$data['labelsResp'] = $labelsProtesto;
		$data['contaProtestoResp'] = $contaProtestoResp;	
		$data['coresResp'] =$coresResp;	
	}
	
	$contaProtestoArea = $this->protesto_model->contaProtestoByArea($tipoOcorrencia);
	
	$labelsResp ='';
	if($contaProtestoArea == 0){
		$data['labelsArea'] = '';
		$data['contaProtestoArea'] = '';
		$data['coresArea'] ='';	
	}else{
		$coresArea ='';
		foreach($contaProtestoArea as $pen){
			$labelsProtesto .= '"'.$pen->status.'",';
			$coresArea .= '"'.$pen->cor.'",';
		}
		$data['labelsArea'] = $labelsProtesto;
		$data['contaProtestoArea'] = $contaProtestoArea;	
		$data['coresArea'] =$coresArea;	
	}
	
	if(!empty($_POST['intervalo'])){
		
	  
					   
		$dataFinal = $_POST['intervalo'];
		$intervaloDias = 2;
		if($dataFinal > 30){
			$intervaloDias = 5;	
		}
	}else{
		$dataFinal = 30;
		$intervaloDias = 2;
	}
	
	
	$i=1;
	$graficoDatasProtesto = array();
	
	$hoje = date('Y-m-d');
	$outraData = strtotime(date('Y-m-d', strtotime('- '.$dataFinal.' days', strtotime($hoje))));
	$endDate = strtotime(date('Y-m-d'));   // Data final
	$final = (date('Y-m-d'));   // Data final

	while ($outraData <= $endDate) {
		$dataFormatadaBR = date('d/m/Y', $outraData);
		$dataFormatadaUS = date('Y-m-d', $outraData);
		$outraData = strtotime('+'.$intervaloDias.' day', $outraData); // Avança um dia
		
		
		$dataEntrada = $this->protesto_model->contaProtestoByData($dataFormatadaUS,1,$tipoOcorrencia); //entrada protesto
		$dataSaida = $this->protesto_model->contaProtestoByData($dataFormatadaUS,2,$tipoOcorrencia); //saida protesto		
		$graficoDatasProtesto[$i]['data']=$dataFormatadaBR;
		$graficoDatasProtesto[$i]['entrada']=$dataEntrada[0]->total;
		$graficoDatasProtesto[$i]['saida']=$dataSaida[0]->total;
		$i++;
		
	}
	
	
		
	$graficoDatasProtesto = ($graficoDatasProtesto);
	$data['graficoDatasProtesto']=$graficoDatasProtesto;	
	
	
	$startDate = strtotime("2022/11/01");
	
	$endDate   = strtotime(date('Y/m/d'));
	
	$i=1;
	$graficoDatasProtestoAux = array();
	while ($endDate >= $startDate) {
		$dataMov = date('Y-m',$endDate);
		
		$endDate = strtotime( date('Y/m/01/',$endDate).' -1 month');
		
		$dataEntrada = $this->protesto_model->contaProtestoByDataAux($dataMov,1,$tipoOcorrencia); //entrada protesto
		$dataSaida = $this->protesto_model->contaProtestoByDataAux($dataMov,2,$tipoOcorrencia); //saida protesto
		
		$dataMovArr = explode("-",$dataMov);
		
		$graficoDatasProtestoAux[$i]['data']= '01-'.$dataMovArr[1].'-'.$dataMovArr[0];
		$graficoDatasProtestoAux[$i]['entrada']=$dataEntrada[0]->total;
		
		$graficoDatasProtestoAux[$i]['saida']=$dataSaida[0]->total;
		
		$i++;
	}
	$graficoDatasProtestoAux = array_reverse($graficoDatasProtestoAux);
	$data['graficoDatasProtestoAux']=$graficoDatasProtestoAux;	
	
	$data['ocorrencias'] = $this->protesto_model->listarTipoOcorrencias();
	$data['intervalo'] =$dataFinal;
	
	
	
	$this->load->view('header_pages_view',$data);
	$this->load->view('dashboard', $data);
	$this->load->view('footer_pages_view');

 }
 public function listaApp(){	
	
	$estados = $this->estado_model->contarCndByEstadoApp();
	echo json_encode($estados);
 }
 function lista(){	
	$session_data = $_SESSION['login_tejofran_protesto'];
	$idContratante = $session_data['id_contratante'] ;
	$estadosCnds = array();
	
	$base = $this->config->base_url();
	$base .='index.php';
	
	$estado = $this->input->get('estado');	
	
	//$dadosNotificacao = $this->notificacao_model->listarNotificacoesByEstado($estado);
	//$dadosInfracao = $this->infracao_model->listarInfracoesByEstado($estado);
	$dadosProtesto = $this->protesto_model->listarProtestoByEstado($estado);
	$retornoNot='';
	$retornoNot .="<tr style='font-weight:bold!important'>
		<td style='text-align:center;width:5%;border:1px solid #002060;'>UF</td>
		<td style='text-align:center;width:11%;border:1px solid #002060;'>Município</td>
		<td style='text-align:center;width:5%;border:1px solid #002060;'>CNPJ</td>
		<td style='text-align:center;width:5%;border:1px solid #002060;'>Credor</td>
		<td style='text-align:center;width:18%;border:1px solid #002060;'>Valor Protestado </td>
		<td style='text-align:center;width:8%;border:1px solid #002060;'>Tipo de pendência</td>
		<td style='text-align:center;width:7%;border:1px solid #002060;'>Última Observação </td>
		<td style='text-align:center;width:8%;border:1px solid #002060;'>Tracking</td></tr>";
	// foreach($dadosNotificacao as $dados){
		// $id = $dados->id;
		// $cnpj = $dados->cnpj;
		// $cnpjRaiz = $dados->cnpj_raiz;
		// $cnpjRaiz = $dados->cnpj_raiz;
		// $num_ie = ($dados->num_ie) ? $dados->num_ie : "" ;	
		// $num_im = ($dados->num_im) ? $dados->num_im : "" ;		
		// $num_lancamento = $dados->num_lancamento;
		// $num_processo = $dados->num_processo;
		// $data_ciencia_br = $dados->data_ciencia_br;
		// $prazo_br = $dados->prazo_br;
		// $relato_infracao = $dados->relato_infracao;
		// $retornoNot .="<tr >
		// <td style='padding-left:4px;border:1px solid #002060;'>$cnpjRaiz</td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$cnpj </td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$num_ie</td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$num_im </td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$num_lancamento</td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$num_processo</td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$data_ciencia_br</td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$prazo_br</td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$relato_infracao</td>
		// <td style='padding-left:4px;border:1px solid #002060;'><a target='_blank' title='Tracking' href='$base/notificacao/tracking?id=$id' class='btn'> <i title='Tracking' class='fa fa-cogs'></i></a></td></tr>";
		
	// }
	
	// $retornoInfra='';
	// $retornoInfra .="<tr style='font-weight:bold!important'>
		// <td style='text-align:center;width:5%;border:1px solid #002060;'>Cnpj Raiz</td>
		// <td style='text-align:center;width:11%;border:1px solid #002060;'>Cnpj</td>
		// <td style='text-align:center;width:5%;border:1px solid #002060;'>I.E.</td>
		// <td style='text-align:center;width:5%;border:1px solid #002060;'>I.M.</td>
		// <td style='text-align:center;width:18%;border:1px solid #002060;'>Num. Lançamento ou Débito</td>
		// <td style='text-align:center;width:8%;border:1px solid #002060;'>Num. Processo</td>
		// <td style='text-align:center;width:7%;border:1px solid #002060;'>Data Ciência</td>
		// <td style='text-align:center;width:7%;border:1px solid #002060;'>Prazo</td>
		// <td style='text-align:center;width:30%;border:1px solid #002060;'>Breve Relato</td>
		// <td style='text-align:center;width:8%;border:1px solid #002060;'>Tracking</td></tr>";
	// foreach($dadosInfracao as $dados){
		// $id = $dados->id;
		// $cnpj = $dados->cnpj;
		// $cnpjRaiz = $dados->cnpj_raiz;
		// $cnpjRaiz = $dados->cnpj_raiz;
		// $num_ie = ($dados->num_ie) ? $dados->num_ie : "" ;	
		// $num_im = ($dados->num_im) ? $dados->num_im : "" ;		
		// $num_lancamento = $dados->num_lancamento;
		// $num_processo = $dados->num_processo;
		// $data_ciencia_br = $dados->data_ciencia_br;
		// $prazo_br = $dados->prazo_br;
		// $relato_infracao = $dados->relato_infracao;
		// $retornoInfra .="<tr>
		// <td style='padding-left:4px;border:1px solid #002060;'>$cnpjRaiz</td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$cnpj </td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$num_ie</td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$num_im </td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$num_lancamento</td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$num_processo</td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$data_ciencia_br</td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$prazo_br</td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$relato_infracao</td>
		// <td style='padding-left:4px;border:1px solid #002060;'><a target='_blank' title='Tracking' href='$base/infracoes/tracking?id=$id' class='btn'> <i title='Tracking' class='fa fa-cogs'></i></a></td></tr>";
		// //$retornoInfra .="<hr>";		
	// }
	
	$retornoProtesto='';
	$retornoProtesto .="<thead>
								<tr style='font-weight:bold!important'>			
								<td style='text-align:center;width:5%;border:1px solid #002060;'>UF</td>
								<td style='text-align:center;width:5%;border:1px solid #002060;'>Município</td>
								<td style='text-align:center;width:10%;border:1px solid #002060;'>CNPJ</td>
								<td style='text-align:center;width:15%;border:1px solid #002060;'>Credor</td>
								<td style='text-align:center;width:10%;border:1px solid #002060;'>Valor Protestado </td>
								<td style='text-align:center;width:15%;border:1px solid #002060;'>Tipo de pendência</td>
								<td style='text-align:center;width:20%;border:1px solid #002060;'>Última Observação </td>
								<td style='text-align:center;width:5%;border:1px solid #002060;'>Tracking</td></tr>
								</thead>";
	foreach($dadosProtesto as $dados){
		$id = $dados->id;
		$cnpj = $dados->cnpj;	
		$credor = $dados->credor_favorecido;
		$valor_protestado = $dados->valor_protestado;
		$ultima_pendencia = $dados->ultima_pendencia;
		$uf = $dados->uf;
		$nome = $dados->nome;
		$ultima_observacao = $dados->ultima_observacao;
		
		$retornoProtesto .="<tr>
		<td style='padding-left:4px;border:1px solid #002060;'>$uf</td>
		<td style='padding-left:4px;border:1px solid #002060;'>$nome </td>
		<td style='padding-left:4px;border:1px solid #002060;'>$cnpj </td>
		<td style='padding-left:4px;border:1px solid #002060;'>$credor</td>
		<td style='padding-left:4px;border:1px solid #002060;'>$valor_protestado</td>
		<td style='padding-left:4px;border:1px solid #002060;'>$ultima_pendencia</td>
		<td style='padding-left:4px;border:1px solid #002060;'>$ultima_observacao</td>
		<td style='padding-left:4px;border:1px solid #002060;'><a target='_blank' title='Tratativa' href='$base/protesto/tratativas?id=$id' class='btn'> <i title='Tracking' class='fa fa-wrench'></i></a></td></tr>";		
	}
	
	//$estadosCnds['listagemNotificacao'] = $retornoNot;
	//$estadosCnds['listagemInfracao'] = $retornoInfra;
	$estadosCnds['listagemProtesto'] = $retornoProtesto;
	echo json_encode($estadosCnds);
	
 }
 
 function listaByStatus(){	
	$session_data = $_SESSION['login_tejofran_protesto'];
	$idContratante = $session_data['id_contratante'] ;
	$estadosCnds = array();
	
	$base = $this->config->base_url();
	$base .='index.php';
	
	$status = $this->input->get('status');	
	$tipoOcorrencia = $this->input->get('tipoOcorrencia');	
	
	
	$dadosProtesto = $this->protesto_model->listarProtestoByStatus($status,$tipoOcorrencia);
	
	$retornoProtesto='';
	$retornoProtesto .="<thead>
								<tr style='font-weight:bold!important'>			
								<th style='text-align:center;width:2%;border:1px solid #002060;'></th>
								<th style='text-align:center;width:5%;border:1px solid #002060;'>Status Geral</th>
								<th style='text-align:center;width:10%;border:1px solid #002060;'>Empresa Devedora</th>
								<th style='text-align:center;width:10%;border:1px solid #002060;'>Tipo de Ocorrência</th>
								<th style='text-align:center;width:20%;border:1px solid #002060;'>Tipo de Pendência</th>
								<th style='text-align:center;width:20%;border:1px solid #002060;'>Detalhes de Pendência</th>
								<th style='text-align:center;width:20%;border:1px solid #002060;'>Responsável pela Regularização</th>
								<th style='text-align:center;width:20%;border:1px solid #002060;'>Área Focal</th>
								<th style='text-align:center;width:10%;border:1px solid #002060;'>Valor Protestado</th>
								<th style='text-align:center;width:3%;border:1px solid #002060;'>Tratativas</th></tr>
								</thead>";
	$i=1;							
	foreach($dadosProtesto as $dados){
		$id = $dados->id_tratativa;
		
		$valor_protestado = $dados->valor_protestado;
		$descricao_pendencia = $dados->descricao;
		$descricao_esfera = $dados->descricao_esfera;
		$ocorrencia = $dados->ocorrencia;
		$descricao_etapa = $dados->descricao_etapa;
		$descricao_area_focal = $dados->descricao_area_focal;
		
		if($dados->tipo_ocorrencia == 4){
			$empresa_devedora = $dados->empresa_devedora_cr;
		}else{
			$empresa_devedora = $dados->empresa_devedora_crr;
		}
		
		if($dados->status == 0){
			$status ='Ativo';
		}else{
			$status ='Baixado';
		}
		
		
		$retornoProtesto .="<tr>
		<td style='padding-left:4px;border:1px solid #002060;'>$i </td>
		<td style='padding-left:4px;border:1px solid #002060;'>$status </td>
		<td style='padding-left:4px;border:1px solid #002060;'>$empresa_devedora </td>
		<td style='padding-left:4px;border:1px solid #002060;'>$ocorrencia </td>		
		<td style='padding-left:4px;border:1px solid #002060;'>$descricao_pendencia</td>
		<td style='padding-left:4px;border:1px solid #002060;'>$descricao_esfera</td>
		<td style='padding-left:4px;border:1px solid #002060;'>$descricao_etapa</td>
		<td style='padding-left:4px;border:1px solid #002060;'>$descricao_area_focal</td>
		<td style='padding-left:4px;border:1px solid #002060;'>$valor_protestado</td>
		<td style='padding-left:4px;border:1px solid #002060;'><a target='_blank' title='Tratativa' href='$base/protesto/tratativas?id=$id' class='btn'> <i title='Tracking' class='fa fa-wrench'></i></a></td></tr>";		
		$i++;
	}
	
	//$estadosCnds['listagemNotificacao'] = $retornoNot;
	//$estadosCnds['listagemInfracao'] = $retornoInfra;
	$estadosCnds['listagemProtesto'] = $retornoProtesto;
	echo json_encode($estadosCnds);
	
 }
 
 function listaByStatusDetPend(){	
	$session_data = $_SESSION['login_tejofran_protesto'];
	$idContratante = $session_data['id_contratante'] ;
	$estadosCnds = array();
	
	$base = $this->config->base_url();
	$base .='index.php';
	
	$status = $this->input->get('status');	
	$tipoOcorrencia = $this->input->get('tipoOcorrencia');
	
	//$dadosNotificacao = $this->notificacao_model->listarNotificacoesByEstado($estado);
	//$dadosInfracao = $this->infracao_model->listarInfracoesByEstado($estado);
	$dadosProtesto = $this->protesto_model->listarprotestoByDetPend($status,$tipoOcorrencia);
	
	$retornoProtesto='';
	$retornoProtesto .="<thead>
								<tr style='font-weight:bold!important'>			
								<th style='text-align:center;width:2%;border:1px solid #002060;'></th>
								<th style='text-align:center;width:5%;border:1px solid #002060;'>Status Geral</th>
								<th style='text-align:center;width:10%;border:1px solid #002060;'>Empresa Devedora</th>
								<th style='text-align:center;width:10%;border:1px solid #002060;'>Tipo de Ocorrência</th>
								<th style='text-align:center;width:20%;border:1px solid #002060;'>Tipo de Pendência</th>
								<th style='text-align:center;width:20%;border:1px solid #002060;'>Detalhes de Pendência</th>
								<th style='text-align:center;width:20%;border:1px solid #002060;'>Responsável pela Regularização</th>
								<th style='text-align:center;width:20%;border:1px solid #002060;'>Área Focal</th>
								<th style='text-align:center;width:10%;border:1px solid #002060;'>Valor Protestado</th>
								<th style='text-align:center;width:3%;border:1px solid #002060;'>Tratativas</th></tr>
								</thead>";
	$i=1;							
	foreach($dadosProtesto as $dados){
		$id = $dados->id_tratativa;
		$cnpj = $dados->cnpj;
		$cnpjRaiz = $dados->cnpj_raiz;
		
		$credor = $dados->credor_favorecido;
		$valor_titulo = $dados->valor_titulo;
		$valor_protestado = $dados->valor_protestado;
		$descricao_pendencia = $dados->descricao;
		$descricao_area_focal = $dados->descricao_area_focal;
		$ocorrencia = $dados->ocorrencia;
		$descricao_esfera = $dados->descricao_esfera;
		$descricao_etapa = $dados->descricao_etapa;
		
		if($dados->status == 0){
			$status ='Ativo';
		}else{
			$status ='Baixado';
		}
		
		
		if($dados->tipo_ocorrencia == 4){
			$empresa_devedora = $dados->empresa_devedora_cr;
		}else{
			$empresa_devedora = $dados->empresa_devedora_crr;
		}
		
		$retornoProtesto .="<tr>
		<td style='padding-left:4px;border:1px solid #002060;'>$i </td>
		<td style='padding-left:4px;border:1px solid #002060;'>$status </td>
		<td style='padding-left:4px;border:1px solid #002060;'>$empresa_devedora </td>
		<td style='padding-left:4px;border:1px solid #002060;'>$ocorrencia </td>		
		<td style='padding-left:4px;border:1px solid #002060;'>$descricao_pendencia</td>
		<td style='padding-left:4px;border:1px solid #002060;'>$descricao_esfera</td>
		<td style='padding-left:4px;border:1px solid #002060;'>$descricao_etapa</td>
		<td style='padding-left:4px;border:1px solid #002060;'>$descricao_area_focal</td>
		<td style='padding-left:4px;border:1px solid #002060;'>$valor_protestado</td>
		<td style='padding-left:4px;border:1px solid #002060;'><a target='_blank' title='Tratativa' href='$base/protesto/tratativas?id=$id' class='btn'> <i title='Tracking' class='fa fa-wrench'></i></a></td></tr>";		
		$i++;
		
		
	}
	
	$estadosCnds['listagemProtesto'] = $retornoProtesto;
	echo json_encode($estadosCnds);
	
 }
 
 function listaByStatusResp(){	
	$session_data = $_SESSION['login_tejofran_protesto'];
	$idContratante = $session_data['id_contratante'] ;
	$estadosCnds = array();
	
	$base = $this->config->base_url();
	$base .='index.php';
	
	$status = $this->input->get('status');			
	$tipoOcorrencia = $this->input->get('tipoOcorrencia');		
	$dadosProtesto = $this->protesto_model->listarprotestoByResp($status,$tipoOcorrencia);
	
	$retornoProtesto='';
	$retornoProtesto .="<thead>
								<tr style='font-weight:bold!important'>			
								<th style='text-align:center;width:2%;border:1px solid #002060;'></th>
								<th style='text-align:center;width:5%;border:1px solid #002060;'>Status Geral</th>
								<th style='text-align:center;width:10%;border:1px solid #002060;'>Empresa Devedora</th>
								<th style='text-align:center;width:10%;border:1px solid #002060;'>Tipo de Ocorrência</th>
								<th style='text-align:center;width:20%;border:1px solid #002060;'>Tipo de Pendência</th>
								<th style='text-align:center;width:20%;border:1px solid #002060;'>Detalhes de Pendência</th>
								<th style='text-align:center;width:20%;border:1px solid #002060;'>Responsável pela Regularização</th>
								<th style='text-align:center;width:20%;border:1px solid #002060;'>Área Focal</th>
								<th style='text-align:center;width:10%;border:1px solid #002060;'>Valor Protestado</th>
								<th style='text-align:center;width:3%;border:1px solid #002060;'>Tratativas</th></tr>
								</thead>";
	$i=1;
	foreach($dadosProtesto as $dados){
		$id = $dados->id_tratativa;
		

		$valor_titulo = $dados->valor_titulo;
		$valor_protestado = $dados->valor_protestado;
		$descricao_pendencia = $dados->descricao;
		$descricao_area_focal = $dados->descricao_area_focal;
		$ocorrencia = $dados->ocorrencia;
		$descricao_esfera = $dados->descricao_esfera;
		$descricao_etapa = $dados->descricao_etapa;
		
		if($dados->status == 0){
			$status ='Ativo';
		}else{
			$status ='Baixado';
		}
		
		
		if($dados->tipo_ocorrencia == 4){
			$empresa_devedora = $dados->empresa_devedora_cr;
		}else{
			$empresa_devedora = $dados->empresa_devedora_crr;
		}
		
		$retornoProtesto .="<tr>
		<td style='padding-left:4px;border:1px solid #002060;'>$i </td>
		<td style='padding-left:4px;border:1px solid #002060;'>$status </td>
		<td style='padding-left:4px;border:1px solid #002060;'>$empresa_devedora </td>
		<td style='padding-left:4px;border:1px solid #002060;'>$ocorrencia </td>		
		<td style='padding-left:4px;border:1px solid #002060;'>$descricao_pendencia</td>
		<td style='padding-left:4px;border:1px solid #002060;'>$descricao_esfera</td>
		<td style='padding-left:4px;border:1px solid #002060;'>$descricao_etapa</td>
		<td style='padding-left:4px;border:1px solid #002060;'>$descricao_area_focal</td>
		<td style='padding-left:4px;border:1px solid #002060;'>$valor_protestado</td>
		<td style='padding-left:4px;border:1px solid #002060;'><a target='_blank' title='Tratativa' href='$base/protesto/tratativas?id=$id' class='btn'> <i title='Tracking' class='fa fa-wrench'></i></a></td></tr>";		
		$i++;
		
		
	}
	
	$estadosCnds['listagemProtesto'] = $retornoProtesto;
	echo json_encode($estadosCnds);
	
 }
 
 function listaByStatusArea(){	
	$session_data = $_SESSION['login_tejofran_protesto'];
	$idContratante = $session_data['id_contratante'] ;
	$estadosCnds = array();
	
	$base = $this->config->base_url();
	$base .='index.php';
	
	$status = $this->input->get('status');	
	$tipoOcorrencia = $this->input->get('tipoOcorrencia');	
	$dadosProtesto = $this->protesto_model->listarprotestoByArea($status,$tipoOcorrencia);
	$retornoProtesto='';
	$retornoProtesto .="<thead>
								<tr style='font-weight:bold!important'>			
								<th style='text-align:center;width:2%;border:1px solid #002060;'></th>
								<th style='text-align:center;width:5%;border:1px solid #002060;'>Status Geral</th>
								<th style='text-align:center;width:10%;border:1px solid #002060;'>Empresa Devedora</th>
								<th style='text-align:center;width:10%;border:1px solid #002060;'>Tipo de Ocorrência</th>
								<th style='text-align:center;width:20%;border:1px solid #002060;'>Tipo de Pendência</th>
								<th style='text-align:center;width:20%;border:1px solid #002060;'>Detalhes de Pendência</th>
								<th style='text-align:center;width:20%;border:1px solid #002060;'>Responsável pela Regularização</th>
								<th style='text-align:center;width:20%;border:1px solid #002060;'>Área Focal</th>
								<th style='text-align:center;width:10%;border:1px solid #002060;'>Valor Protestado</th>
								<th style='text-align:center;width:3%;border:1px solid #002060;'>Tratativas</th></tr>
								</thead>";
	$i=1;
	foreach($dadosProtesto as $dados){
		$id = $dados->id_tratativa;
		

		$valor_titulo = $dados->valor_titulo;
		$valor_protestado = $dados->valor_protestado;
		$descricao_pendencia = $dados->descricao;
		$descricao_area_focal = $dados->descricao_area_focal;
		$ocorrencia = $dados->ocorrencia;
		$descricao_esfera = $dados->descricao_esfera;
		$descricao_etapa = $dados->descricao_etapa;
		
		if($dados->status == 0){
			$status ='Ativo';
		}else{
			$status ='Baixado';
		}
		
		
		if($dados->tipo_ocorrencia == 4){
			$empresa_devedora = $dados->empresa_devedora_cr;
		}else{
			$empresa_devedora = $dados->empresa_devedora_crr;
		}
		
		$retornoProtesto .="<tr>
		<td style='padding-left:4px;border:1px solid #002060;'>$i </td>
		<td style='padding-left:4px;border:1px solid #002060;'>$status </td>
		<td style='padding-left:4px;border:1px solid #002060;'>$empresa_devedora </td>
		<td style='padding-left:4px;border:1px solid #002060;'>$ocorrencia </td>		
		<td style='padding-left:4px;border:1px solid #002060;'>$descricao_pendencia</td>
		<td style='padding-left:4px;border:1px solid #002060;'>$descricao_esfera</td>
		<td style='padding-left:4px;border:1px solid #002060;'>$descricao_etapa</td>
		<td style='padding-left:4px;border:1px solid #002060;'>$descricao_area_focal</td>
		<td style='padding-left:4px;border:1px solid #002060;'>$valor_protestado</td>
		<td style='padding-left:4px;border:1px solid #002060;'><a target='_blank' title='Tratativa' href='$base/protesto/tratativas?id=$id' class='btn'> <i title='Tracking' class='fa fa-wrench'></i></a></td></tr>";		
		$i++;
		
		
	}
	
	$estadosCnds['listagemProtesto'] = $retornoProtesto;
	echo json_encode($estadosCnds);
	
 }
 
 function listaByOcorrencia(){	
	$session_data = $_SESSION['login_tejofran_protesto'];
	$idContratante = $session_data['id_contratante'] ;
	$estadosCnds = array();
	
	$base = $this->config->base_url();
	$base .='index.php';
	
	$status = $this->input->get('status');	
	$tipoOcorrencia = $this->input->get('tipoOcorrencia');	
	$dadosProtesto = $this->protesto_model->listarprotestoByOcorrencia($status);
	$retornoProtesto='';
	$retornoProtesto .="<thead>
								<tr style='font-weight:bold!important'>			
								<th style='text-align:center;width:2%;border:1px solid #002060;'></th>
								<th style='text-align:center;width:5%;border:1px solid #002060;'>Status Geral</th>
								<th style='text-align:center;width:10%;border:1px solid #002060;'>Empresa Devedora</th>
								<th style='text-align:center;width:10%;border:1px solid #002060;'>Tipo de Ocorrência</th>
								<th style='text-align:center;width:20%;border:1px solid #002060;'>Tipo de Pendência</th>
								<th style='text-align:center;width:20%;border:1px solid #002060;'>Detalhes de Pendência</th>
								<th style='text-align:center;width:20%;border:1px solid #002060;'>Responsável pela Regularização</th>
								<th style='text-align:center;width:20%;border:1px solid #002060;'>Área Focal</th>
								<th style='text-align:center;width:10%;border:1px solid #002060;'>Valor Protestado</th>
								<th style='text-align:center;width:3%;border:1px solid #002060;'>Tratativas</th></tr>
								</thead>";
	$i=1;
	foreach($dadosProtesto as $dados){
		$id = $dados->id_tratativa;
		

		$valor_titulo = $dados->valor_titulo;
		$valor_protestado = $dados->valor_protestado;
		$descricao_pendencia = $dados->descricao;
		$descricao_area_focal = $dados->descricao_area_focal;
		$ocorrencia = $dados->ocorrencia;
		$descricao_esfera = $dados->descricao_esfera;
		$descricao_etapa = $dados->descricao_etapa;
		
		if($dados->status == 0){
			$status ='Ativo';
		}else{
			$status ='Baixado';
		}
		
		
		if($dados->tipo_ocorrencia == 4){
			$empresa_devedora = $dados->empresa_devedora_cr;
		}else{
			$empresa_devedora = $dados->empresa_devedora_crr;
		}
		
		$retornoProtesto .="<tr>
		<td style='padding-left:4px;border:1px solid #002060;'>$i </td>
		<td style='padding-left:4px;border:1px solid #002060;'>$status </td>
		<td style='padding-left:4px;border:1px solid #002060;'>$empresa_devedora </td>
		<td style='padding-left:4px;border:1px solid #002060;'>$ocorrencia </td>		
		<td style='padding-left:4px;border:1px solid #002060;'>$descricao_pendencia</td>
		<td style='padding-left:4px;border:1px solid #002060;'>$descricao_esfera</td>
		<td style='padding-left:4px;border:1px solid #002060;'>$descricao_etapa</td>
		<td style='padding-left:4px;border:1px solid #002060;'>$descricao_area_focal</td>
		<td style='padding-left:4px;border:1px solid #002060;'>$valor_protestado</td>
		<td style='padding-left:4px;border:1px solid #002060;'><a target='_blank' title='Tratativa' href='$base/protesto/tratativas?id=$id' class='btn'> <i title='Tracking' class='fa fa-wrench'></i></a></td></tr>";		
		$i++;
		
		
	}
	
	$estadosCnds['listagemProtesto'] = $retornoProtesto;
	echo json_encode($estadosCnds);
	
 }
 
 function listaByStatusGeral(){	
	$session_data = $_SESSION['login_tejofran_protesto'];
	$idContratante = $session_data['id_contratante'] ;
	$estadosCnds = array();
	
	$base = $this->config->base_url();
	$base .='index.php';
	
	$status = $this->input->get('status');	
	
	
	//$dadosNotificacao = $this->notificacao_model->listarNotificacoesByEstado($estado);
	//$dadosInfracao = $this->infracao_model->listarInfracoesByEstado($estado);
	$dadosProtesto = $this->protesto_model->listarprotestoByStatusGeral($status);
	$retornoNot='';
	$retornoNot .="<tr style='font-weight:bold!important'>
	
		<td style='text-align:center;width:5%;border:1px solid #002060;'>Cnpj Raiz</td>
		<td style='text-align:center;width:11%;border:1px solid #002060;'>Cnpj</td>
		<td style='text-align:center;width:5%;border:1px solid #002060;'>I.E.</td>
		<td style='text-align:center;width:5%;border:1px solid #002060;'>I.M.</td>
		<td style='text-align:center;width:18%;border:1px solid #002060;'>Num. Lançamento ou Débito</td>
		<td style='text-align:center;width:8%;border:1px solid #002060;'>Num. Processo</td>
		<td style='text-align:center;width:7%;border:1px solid #002060;'>Data Ciência</td>
		<td style='text-align:center;width:7%;border:1px solid #002060;'>Prazo</td>
		<td style='text-align:center;width:30%;border:1px solid #002060;'>Breve Relato</td>
		<td style='text-align:center;width:8%;border:1px solid #002060;'>Tracking</td></tr>";
	// foreach($dadosNotificacao as $dados){
		// $id = $dados->id;
		// $cnpj = $dados->cnpj;
		// $cnpjRaiz = $dados->cnpj_raiz;
		// $cnpjRaiz = $dados->cnpj_raiz;
		// $num_ie = ($dados->num_ie) ? $dados->num_ie : "" ;	
		// $num_im = ($dados->num_im) ? $dados->num_im : "" ;		
		// $num_lancamento = $dados->num_lancamento;
		// $num_processo = $dados->num_processo;
		// $data_ciencia_br = $dados->data_ciencia_br;
		// $prazo_br = $dados->prazo_br;
		// $relato_infracao = $dados->relato_infracao;
		// $retornoNot .="<tr >
		// <td style='padding-left:4px;border:1px solid #002060;'>$cnpjRaiz</td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$cnpj </td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$num_ie</td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$num_im </td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$num_lancamento</td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$num_processo</td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$data_ciencia_br</td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$prazo_br</td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$relato_infracao</td>
		// <td style='padding-left:4px;border:1px solid #002060;'><a target='_blank' title='Tracking' href='$base/notificacao/tracking?id=$id' class='btn'> <i title='Tracking' class='fa fa-cogs'></i></a></td></tr>";
		
	// }
	
	// $retornoInfra='';
	// $retornoInfra .="<tr style='font-weight:bold!important'>
		// <td style='text-align:center;width:5%;border:1px solid #002060;'>Cnpj Raiz</td>
		// <td style='text-align:center;width:11%;border:1px solid #002060;'>Cnpj</td>
		// <td style='text-align:center;width:5%;border:1px solid #002060;'>I.E.</td>
		// <td style='text-align:center;width:5%;border:1px solid #002060;'>I.M.</td>
		// <td style='text-align:center;width:18%;border:1px solid #002060;'>Num. Lançamento ou Débito</td>
		// <td style='text-align:center;width:8%;border:1px solid #002060;'>Num. Processo</td>
		// <td style='text-align:center;width:7%;border:1px solid #002060;'>Data Ciência</td>
		// <td style='text-align:center;width:7%;border:1px solid #002060;'>Prazo</td>
		// <td style='text-align:center;width:30%;border:1px solid #002060;'>Breve Relato</td>
		// <td style='text-align:center;width:8%;border:1px solid #002060;'>Tracking</td></tr>";
	// foreach($dadosInfracao as $dados){
		// $id = $dados->id;
		// $cnpj = $dados->cnpj;
		// $cnpjRaiz = $dados->cnpj_raiz;
		// $cnpjRaiz = $dados->cnpj_raiz;
		// $num_ie = ($dados->num_ie) ? $dados->num_ie : "" ;	
		// $num_im = ($dados->num_im) ? $dados->num_im : "" ;		
		// $num_lancamento = $dados->num_lancamento;
		// $num_processo = $dados->num_processo;
		// $data_ciencia_br = $dados->data_ciencia_br;
		// $prazo_br = $dados->prazo_br;
		// $relato_infracao = $dados->relato_infracao;
		// $retornoInfra .="<tr>
		// <td style='padding-left:4px;border:1px solid #002060;'>$cnpjRaiz</td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$cnpj </td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$num_ie</td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$num_im </td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$num_lancamento</td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$num_processo</td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$data_ciencia_br</td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$prazo_br</td>
		// <td style='padding-left:4px;border:1px solid #002060;'>$relato_infracao</td>
		// <td style='padding-left:4px;border:1px solid #002060;'><a target='_blank' title='Tracking' href='$base/infracoes/tracking?id=$id' class='btn'> <i title='Tracking' class='fa fa-cogs'></i></a></td></tr>";
		// //$retornoInfra .="<hr>";		
	// }
	
	$retornoProtesto='';
	$retornoProtesto .="<thead>
								<tr style='font-weight:bold!important'>			
								<th style='text-align:center;width:10%;border:1px solid #002060;'>Cnpj</th>
								<th style='text-align:center;width:36%;border:1px solid #002060;'>Credor</th>
								<th style='text-align:center;width:10%;border:1px solid #002060;'>Valor Protestado</th>
								<th style='text-align:center;width:30%;border:1px solid #002060;'>Tipo de Pendência</th>
								<th style='text-align:center;width:30%;border:1px solid #002060;'>Status de Pendência</th>
								<th style='text-align:center;width:7%;border:1px solid #002060;'>Tratativas</th></tr>
								</thead>";
	foreach($dadosProtesto as $dados){
		$id = $dados->id_tratativa;
		$cnpj = $dados->cnpj;
		$cnpjRaiz = $dados->cnpj_raiz;
		//$num_ie = ($dados->num_ie) ? $dados->num_ie : "" ;	
		//$num_im = ($dados->num_im) ? $dados->num_im : "" ;		
		
		$credor = $dados->credor_favorecido;
		$valor_titulo = $dados->valor_titulo;
		$valor_protestado = $dados->valor_protestado;
		$descricao_pendencia = $dados->descricao;
		$descricao_esfera = $dados->descricao_esfera;
		
		
		$retornoProtesto .="<tr>
		<td style='padding-left:4px;border:1px solid #002060;'>$cnpj </td>
		<td style='padding-left:4px;border:1px solid #002060;'>$credor</td>
		<td style='padding-left:4px;border:1px solid #002060;'>$valor_protestado</td>
		<td style='padding-left:4px;border:1px solid #002060;'>$descricao_pendencia</td>
		<td style='padding-left:4px;border:1px solid #002060;'>$descricao_esfera</td>
		<td style='padding-left:4px;border:1px solid #002060;'><a target='_blank' title='Tratativa' href='$base/protesto/tratativas?id=$id' class='btn'> <i title='Tracking' class='fa fa-wrench'></i></a></td></tr>";		
	}
	
	//$estadosCnds['listagemNotificacao'] = $retornoNot;
	//$estadosCnds['listagemInfracao'] = $retornoInfra;
	$estadosCnds['listagemProtesto'] = $retornoProtesto;
	echo json_encode($estadosCnds);
	
 }
 
 
 function index(){	
	
	if(! $_SESSION['login_tejofran_protesto']){
		redirect('login', 'refresh');
	}
	$session_data = $_SESSION['login_tejofran_protesto'];
	$idContratante = $session_data['id_contratante'] ;
	$data['perfil'] = $session_data['perfil'];
	
	 if($session_data['primeiro_acesso'] == 0){
		 redirect('home/perfil', 'refresh');	
	 }	 
		
	
	$estados = $this->estado_model->listarEstados(0);
	$estadosCnds = array();
	$i=1;
	
	$totalNotificacao =$totalProtesto = $totalInfracao = 0;
	
	foreach($estados as $est){			
		if(($est->uf =='DF')|| ($est->uf =='RJ')|| ($est->uf =='ES')|| ($est->uf =='SE')|| ($est->uf =='AL')||  ($est->uf =='RN')){
			$estadosCnds[$i]['cor'] ='#bababa';
		}else{
			$estadosCnds[$i]['cor'] ='#dedede';
		}		
		$i++;		
	}
	
	//$contagemNotificacao = $this->notificacao_model->contaNotificacaoByUf(0);
	//$contagemInfracao = $this->infracao_model->contaInfracoesByUf(0);
	$contagemProtesto = $this->protesto_model->contaProtestoByUf(0);
	//$data['totalNotificacao'] =$contagemNotificacao[0]->total;
	//$data['totalInfracao'] = $contagemInfracao[0]->total;
	$data['totalProtesto'] =$contagemProtesto[0]->total;


	$data['cndEstado'] = $estadosCnds;
	
	$this->load->view('header_pages_view',$data);
	$this->load->view('dados_agrupados_mapa', $data);
	$this->load->view('footer_pages_view');

 }
 
	
 function perfil(){
	 $session_data = $_SESSION['login_tejofran_protesto'];
     $data['email'] = $session_data['email'];
	 $data['empresa'] = $session_data['empresa'];
	 $data['perfil'] = $session_data['perfil'];
	 
	$data['dadosUsu'] = $this->user->dadosUsu($session_data['id'] ,$session_data['id_contratante']);
	
	 
	$this->load->view('header_pages_view',$data);
	$this->load->view('perfil', $data);
	$this->load->view('footer_pages_view');
 }
 
 	
 
 function atualizar_perfil(){
	  $session_data = $_SESSION['login_tejofran_protesto'];
     $data['email'] = $session_data['email'];
	 $data['empresa'] = $session_data['empresa'];
	 $data['perfil'] = $session_data['perfil'];
	 
	 
	 $nome = $this->input->post('nome');
	 $tel = $this->input->post('tel');
	 $cel = $this->input->post('cel');
	 $whats = $this->input->post('whats');
	 $senha = $this->input->post('senha');
	 
	 if(empty($senha)){
		 $dados = array(
			'nome_usuario' => $nome,
			'telefone' => $tel,
			'celular' => $cel,
			'whatsapp' => $whats,
			'primeiro_acesso' => 1
		);
	 }else{
		 $dados = array(
			'nome_usuario' => $nome,
			'telefone' => $tel,
			'celular' => $cel,
			'whatsapp' => $whats,
			'primeiro_acesso' => 1,
			'senha' => md5($senha)
		);
	 }
	 
	 $this->session->set_flashdata('message', 'Dados Atualizados');	
	 $dados = $this->user->atualizar_dados_usuario($dados,$session_data['id']);
	 redirect('/home/logout');

 }
 
 
 
 function logout()
 {

   $session_data = $_SESSION['login_tejofran_protesto'];	
   $dadosReg = array(
		'id_usuario' => $session_data['id'] ,
		'id_operacao' => 14,
		'texto' => 'Saiu do sistema',
		'data' => date("Y-m-d H:i:s"),		
	);
		
   $dados = $this->registro_model->inserir($dadosReg);
   $_SESSION['login_tejofran_protesto'] = '';
	$referer = $_SERVER['HTTP_REFERER'] ?? '';
	$url = explode("/", $referer);
	if($url[2] == 'localhost'){
		redirect('http://localhost/tejofran/', 'refresh');
	}else{
		redirect('https://bdwebgestora.com.br/tejofran', 'refresh');
	}
 }
 
  function troca_senha()
 {
    $session_data = $this->session->userdata('logged_in');
	
	//print_r($session_data);exit;
	$data['id'] = $session_data['id'];
    $data['email'] = $session_data['email'];
	$data['empresa'] = $session_data['empresa'];
	$data['perfil'] = $session_data['perfil'];
    $this->load->view('header_view',$data);
    $this->load->view('troca_senha', $data);
	$this->load->view('footer_view');


	 
   
 }
 
 function atualizar_senha(){
 
	$senha = md5($this->input->post('senha'));
	$id_usuario = $this->input->post('id');
	
	$this->user->atualizar($senha,$id_usuario);
	$session_data = $this->session->userdata('logged_in');
	$data['id'] = $session_data['id'];
    $data['email'] = $session_data['email'];
	$data['empresa'] = $session_data['empresa'];
	$data['perfil'] = $session_data['perfil'];
	$data['mensagem'] = 'Senha Alterada Com Sucesso';
	
	$this->load->view('header_view',$data);
    $this->load->view('troca_senha', $data);
	$this->load->view('footer_view');
	
 
 }
 
}
 
?>