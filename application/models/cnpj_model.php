<?php
Class Cnpj_model extends CI_Model{

  function listarCnpjRaiz($id_contratante){
	
	$sql = "select c.* from cnpj_raiz c where c.id_contratante = ?  order by cnpj_raiz";
	$query = $this->db->query($sql, array($id_contratante));
   
   //print_r($this->db->last_query());exit;
   $array = $query->result(); //array of arrays
   return($array);
   

}

function listarBandeira(){	  
	$sql = "select id, descricao_bandeira from bandeira";
	$query = $this->db->query($sql, array());  
	$array = $query->result(); 
    return($array);
 }
 
 function listarCnpj($id_contratante,$cnpj){
   $sql1 = $sql2 = $sql3='';
	
	if($cnpj <> 0){
		$sql3 =" and cc.id = ?";
    }
	
	$sql = "select c.cnpj_raiz,cc.id,cc.cnpj,cc.nome,band.descricao_bandeira
			from 
			cnpj cc join
			cnpj_raiz c on cc.id_cnpj_raiz = c.id
			left join bandeira band on band.id = cc.id_bandeira
			where c.id_contratante = ? 
			$sql1 $sql2 $sql3";
	$query = $this->db->query($sql, array($id_contratante,$cnpj));
   
   
   $array = $query->result(); //array of arrays
   return($array);
   

}

 function listarCnpjInscricao($id_contratante,$estado,$cidade,$cnpj){
   $sql1 = $sql2 = $sql3='';
	if($estado <> 0){
		$sql1 = " and e.id = ? ";
    }
	
	if($cidade <> 0){
		$sql2 =" and cid.id = ?";
    }
	
	if($cnpj <> 0){
		$sql3 =" and c.id = ?";
    }
	
	$sql = "select c.cnpj_raiz,e.nome as uf ,cid.nome as cidade,cc.id,cc.cnpj,cc.nome,band.descricao_bandeira,im.numero as im,ie.numero as ie
			from 
			cnpj cc join
			cnpj_raiz c on cc.id_cnpj_raiz = c.id
			join estados e on cc.id_uf = e.id 
			join cidades cid on cid.estado = e.id 
			left join bandeira band on band.id = cc.id_bandeira
			left join inscricao im on im.id = cc.id
			left join inscricao ie on ie.id = cc.id
			where c.id_contratante = ? and cc.id_municipio = cid.id  $sql1 $sql2 $sql3";
	$query = $this->db->query($sql, array($id_contratante,$estado,$cidade,$cnpj));
   
   //print_r($this->db->last_query());exit;
   $array = $query->result(); //array of arrays
   return($array);
   

}


function listarInscricao($idCnpj,$tipo){ 
	$sql = "select i.id,i.numero,cc.cnpj,c.cnpj_raiz from inscricao i  join cnpj cc on i.id_cpnj = cc.id join cnpj_raiz c on cc.id_cnpj_raiz = c.id  where i.id_cpnj = ? and i.tipo = ?";
	$query = $this->db->query($sql, array($idCnpj,$tipo));
	//print_r($this->db->last_query());exit;
   $array = $query->result(); //array of arrays
   return($array);
 }

function listarCnpjById($id){
   
	$sql = "select cc.*,c.id as id_cnpj_raiz from  cnpj cc  join cnpj_raiz c on cc.id_cnpj_raiz = c.id where cc.id = ? ";
	$query = $this->db->query($sql, array($id));
   
   //print_r($this->db->last_query());exit;
   $array = $query->result(); //array of arrays
   return($array);
   

}

function listarCnpjByIdEstCid($id,$estado,$cidade){
	
	if($id == 0){
		$sql = "select cc.*,c.id as id_cnpj_raiz from  protesto p join cnpj cc on p.id_cnpj = cc.id   join cnpj_raiz c on cc.id_cnpj_raiz = c.id where p.id_uf = ? and p.id_municipio = ? ";
		$query = $this->db->query($sql, array($estado,$cidade));
	}elseif($id == 1){
		$sql = "select distinct cc.id,cc.cnpj from  protesto p join cnpj cc on p.id_cnpj = cc.id order by cc.cnpj";
		$query = $this->db->query($sql, array($estado,$cidade));
	}else{	
		$sql = "select cc.*,c.id as id_cnpj_raiz from  protesto p join cnpj cc on p.id_cnpj = cc.id  join cnpj_raiz c on cc.id_cnpj_raiz = c.id where c.id = ? and p.id_uf = ? p cc.id_municipio = ? ";
		$query = $this->db->query($sql, array($id,$estado,$cidade));
	}
	
	
	//print_r($this->db->last_query());exit;
	$array = $query->result(); //array of arrays
	return($array);
}

function listarEstadoByCnpjRaiz($id){
	if($id <> '0'){
		$sql = "select distinct est.id,est.uf from cnpj c  left join estados est on c.id_uf = est.id left join cnpj_raiz cr on cr.id = c.id_cnpj_raiz where cr.id = ? order by est.uf";
		$query = $this->db->query($sql, array($id));		
	}else{
		$sql = "select distinct est.id,est.uf from estados est  order by est.uf";
		$query = $this->db->query($sql, array($id));
	}
	//print_r($this->db->last_query());exit;
	$array = $query->result(); //array of arrays
	return($array);
}

function listarCnpjRaizById($id){
   
	$sql = "select c.* from cnpj_raiz c  where  c.id = ?";
	$query = $this->db->query($sql, array($id));
   
   //print_r($this->db->last_query());exit;
   $array = $query->result(); //array of arrays
   return($array);
   

}

function listarCnpjByIdRaiz($id){
   
	$sql = "select c.* from cnpj c  where  c.id_cnpj_raiz = ? and c.usado = 1 order by cnpj ";
	$query = $this->db->query($sql, array($id));
   
   //print_r($this->db->last_query());exit;
   $array = $query->result(); //array of arrays
   return($array);
   

}

 public function inserir($tabela,$detalhes = array()){ 
	if($this->db->insert($tabela, $detalhes)) {
		return $id = $this->db->insert_id();
	}	
	return false;
}	

public function atualizar($tabela,$dados,$id){  
	$this->db->where('id', $id);
	$this->db->update($tabela, $dados); 
	return true;  
 } 
 
}
?>