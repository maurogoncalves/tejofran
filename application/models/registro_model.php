<?php
Class Registro_model extends CI_Model
{
 
 
 function inserir($dados){
 	$this->db->insert('log_tabelas', $dados); 

	return true;
 
 }
 
 public function listar(){

	
		$sql = "SELECT lo.texto as oque,l.texto as aonde ,u.email as quem, DATE_FORMAT(l.data,'%d/%m/%Y %H:%i') as quando FROM log_tabelas l
				JOIN log_tipo_operacao lo ON l.id_operacao = lo.id
				JOIN usuarios u ON u.id = l.id_usuario
				"; 			
		$query = $this->db->query($sql, array());
		
		//print_r($this->db->last_query());exit;
		$array = $query->result(); //array of arrays
		return($array);
			
}
 

	 
}
?>