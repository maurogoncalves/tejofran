<?php
Class Registro_model extends CI_Model
{
 
 
 function inserir($dados){
 	$this->db->insert('log_tabelas', $dados); 

	return true;
 
 }
 
 public function listar(){

	
		$sql = "select i.*,c.cnpj,cr.cnpj_raiz,DATE_FORMAT(i.data_baixa_protesto,'%d/%m/%Y') as data_baixa_protesto_br,DATE_FORMAT(i.data_protesto,'%d/%m/%Y') as data_protesto_br,DATE_FORMAT(i.vencimento,'%d/%m/%Y') as vencimento_br,ie.numero as num_ie,im.numero as num_im, n.descricao_natureza,cnpj_credor,DATE_FORMAT(i.data_admissao_titulo,'%d/%m/%Y') as data_admissao_titulo_br,nr_auto_infracao,dados_cartorio,cl.descricao as competencia_legis,
				estP.uf as estado_protesto,cidP.nome as cidade_protesto
				from protesto i 
				left join cnpj c on i.id_cnpj = c.id 
				left join cnpj_raiz cr on c.id_cnpj_raiz = cr.id 
				left join estados e on i.id_uf = e.id 
				left join cidades cid on cid.id = i.id_municipio 
				left join natureza n on n.id = i.id_natureza
				left join inscricao ie on ie.id = i.id_ie and ie.tipo = 1  left join inscricao im on im.id = i.id_im and im.tipo = 2
				left join competencia_legis cl on cl.id = i.id_competencia_legis
				left join estados estP on estP.id = i.id_uf_protesto 
				left join cidades cidP on cidP.id = i.id_municipio_protesto
				where 1=1 $sql1 $sql2 $sql3 $sql4 and i.status = $status and i.tipo_ocorrencia = 4"; 			
		$query = $this->db->query($sql, array());
		
		//print_r($this->db->last_query());exit;
		$array = $query->result(); //array of arrays
		return($array);
			
}
 

	 
}
?>