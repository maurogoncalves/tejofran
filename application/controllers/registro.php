<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//
class Registro extends CI_Controller {
 
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
 
 function listarLog(){
	 
	$this->logado();    
	$session_data = $_SESSION['login_tejofran_protesto'];
	$idContratante = $session_data['id_contratante'] ;
	$data['perfil'] = $session_data['perfil'];
	$data['dados'] = $this->registro_model->listar();
	$this->load->view('header_pages_view',$data);
	$this->load->view('registro/listar_registro_view', $data);
	$this->load->view('footer_pages_view');
	 
 }
 
 function logado(){	
	if(! $_SESSION['login_tejofran_protesto']) {	
	 redirect('login', 'refresh');
	}			
}  
 
}
 
?>