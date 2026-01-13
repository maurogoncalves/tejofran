<?php
Class Protesto_model extends CI_Model{
	public function add($detalhes = array()){
		$this->db->insert('protesto', $detalhes);
		$id = $this->db->insert_id();
		return $id;
	}
 
	public function addArq($detalhes = array()){ 
		if($this->db->insert('protesto_arquivos', $detalhes)) {
		return $id = $this->db->insert_id();
		}
		return false;
	}	
	
	 function excluirTratativa($id){	
	$this->db->where('id', $id);	
	$this->db->delete('cnd_mob_tratativa_obs'); 	
	return true; 
} 

	
	public function add_resp($detalhes = array()){
		$this->db->insert('responsavel_Protesto', $detalhes);
		$id = $this->db->insert_id();
		return $id;
	}
	public function listarprotesto($estado,$cidade,$cnpjRaiz,$cnpj,$status){

		 $sql1 = $sql2 = $sql3 = $sql4='';
			if($estado <> 0){
				$sql1 = " and i.id_uf = $estado ";
			}
			
			if($cidade <> 0){
				$sql2 =" and i.id_municipio= $cidade";
			}
			
			
			if($cnpj <> 0){
				$sql4 =" and c.id = $cnpj";
			}
	
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
	
	public function listarPefin($cnpjRaiz,$tipo,$status){

		 $sql1 = '';
			if($cnpjRaiz <> 0){
				$sql1 = " and i.id_cnpj_raiz = $cnpjRaiz and i.tipo_ocorrencia = $tipo  ";
			}
			
		$sql = "select i.*,DATE_FORMAT(i.data_baixa_protesto,'%d/%m/%Y') as data_baixa_protesto_br,DATE_FORMAT(i.data_protesto,'%d/%m/%Y') as data_protesto_br,c.cnpj_raiz,n.descricao_natureza,est.uf,c.empresa as empresa_devedora,cc.nome as cidade
				from protesto i join cnpj_raiz c on i.id_cnpj_raiz = c.id	
				left join natureza n on n.id = i.id_natureza
				left join estados est on est.id = i.id_uf
				left join cidades cc on cc.id = i.id_municipio
				where 1=1 $sql1 and i.tipo_ocorrencia = $tipo and i.status = $status"; 			
		$query = $this->db->query($sql, array());
		
		//print_r($this->db->last_query());exit;
		$array = $query->result(); //array of arrays
		return($array);
			
	}
	
	
	public function listarPefinExp($cnpjRaiz,$tipo,$status){

		 $sql1 = '';
			if($cnpjRaiz <> 0){
				$sql1 = " and i.id_cnpj_raiz = $cnpjRaiz and i.tipo_ocorrencia = $tipo  ";
			}
			
		$sql = "select i.*,DATE_FORMAT(i.data_baixa_protesto,'%d/%m/%Y') as data_baixa_protesto_br,DATE_FORMAT(i.data_protesto,'%d/%m/%Y') as data_protesto_br,c.cnpj_raiz,n.descricao_natureza,est.uf,c.empresa as empresa_devedora,cc.nome as cidade
				from protesto i join cnpj_raiz c on i.id_cnpj_raiz = c.id	
				left join natureza n on n.id = i.id_natureza
				left join estados est on est.id = i.id_uf
				left join cidades cc on cc.id = i.id_municipio
				where 1=1 $sql1 and i.tipo_ocorrencia = $tipo "; 			
		$query = $this->db->query($sql, array());
		
		//print_r($this->db->last_query());exit;
		$array = $query->result(); //array of arrays
		return($array);
			
	}
	
	
	public function listarprotestoapp($estado){

		$sql1 = '';
		if($estado <> '0'){
			$sql1 = " and e.uf = '$estado' ";
		}
			

		$sql = "select i.*,c.cnpj,cr.cnpj_raiz,DATE_FORMAT(i.data_protesto,'%d/%m/%Y') as data_protesto_br,DATE_FORMAT(i.vencimento,'%d/%m/%Y') as vencimento_br,ie.numero as num_ie,im.numero as num_im, n.descricao_natureza,cnpj_credor,DATE_FORMAT(i.data_admissao_titulo,'%d/%m/%Y') as data_admissao_titulo_br,nr_auto_infracao,dados_cartorio	
		from protesto i 
		left join cnpj c on i.id_cnpj = c.id 
		left join cnpj_raiz cr on c.id_cnpj_raiz = cr.id 
		left join estados e on c.id_uf = e.id
		left join cidades cid on cid.id = c.id_uf 
		left join natureza n on n.id = i.id_natureza
		left join inscricao ie on ie.id = i.id_ie and ie.tipo = 1  left join inscricao im on im.id = i.id_im and im.tipo = 2
		where 1=1 $sql1 "; 			
		$query = $this->db->query($sql, array());
		
		//print_r($this->db->last_query());exit;
		$array = $query->result(); //array of arrays
		return($array);
			
	}

public function listarprotestoByEstado($estado){

		if($estado == '0'){
			$sql = "select i.id,i.valor_protestado,e.uf,cid.nome,c.cnpj,i.credor_favorecido,(select tp.descricao from cnd_mob_tratativa c left join tipo_pendencia tp on tp.id = c.pendencia where c.id_cnd_mob = i.id and c.status_chamado_sis_ext = 1 order by c.id desc limit 1 ) as ultima_pendencia,(select co.observacao from cnd_mob_tratativa c  left join cnd_mob_tratativa_obs co on co.id_cnd_trat = c.id where c.id_cnd_mob = i.id  	and c.status_chamado_sis_ext = 1 order by co.id desc limit 1) as ultima_observacao
					from protesto i 
					join cnpj c on i.id_cnpj = c.id 
					join cnpj_raiz cr on c.id_cnpj_raiz = cr.id 
					join estados e on c.id_uf = e.id 
					join cidades cid on cid.id = c.id_uf 
					left join natureza n on n.id = i.id_natureza 
					left join inscricao ie on ie.id = i.id_ie and ie.tipo = 1 
					left join inscricao im on im.id = i.id_im and im.tipo = 2			
				"; 			
			$query = $this->db->query($sql, array());
		}else{
			$sql = "select i.id,i.valor_protestado,e.uf,cid.nome,c.cnpj,i.credor_favorecido,(select tp.descricao from cnd_mob_tratativa c left join tipo_pendencia tp on tp.id = c.pendencia where c.id_cnd_mob = i.id and c.status_chamado_sis_ext = 1 order by c.id desc limit 1 ) as ultima_pendencia,(select co.observacao from cnd_mob_tratativa c  left join cnd_mob_tratativa_obs co on co.id_cnd_trat = c.id where c.id_cnd_mob = i.id  	and c.status_chamado_sis_ext = 1 order by co.id desc limit 1) as ultima_observacao
					from protesto i 
					join cnpj c on i.id_cnpj = c.id 
					join cnpj_raiz cr on c.id_cnpj_raiz = cr.id 
					join estados e on c.id_uf = e.id 
					join cidades cid on cid.id = c.id_uf 
					left join natureza n on n.id = i.id_natureza 
					left join inscricao ie on ie.id = i.id_ie and ie.tipo = 1 
					left join inscricao im on im.id = i.id_im and im.tipo = 2
				where 1=1 and e.uf = ? "; 			
			$query = $this->db->query($sql, array($estado));
		}		
		
		
		//print_r($this->db->last_query());exit;
		$array = $query->result(); //array of arrays
		return($array);
			
	}
	
	public function listarprotestoByStatus($estado,$tipoOcorrencia){

		 
		$sql = "select distinct 
				p.valor_titulo,tp.descricao,e.descricao_esfera,c.id_cnd_mob as id_tratativa,
				p.valor_protestado,p.status,cr.empresa as empresa_devedora_cr,tpo.descricao as ocorrencia,eta.descricao_etapa,af.descricao_area_focal,crr.empresa as empresa_devedora_crr,p.tipo_ocorrencia 
				from cnd_mob_tratativa c 
				left join protesto p on p.id = c.id_cnd_mob 
				left join cnpj cn on cn.id = p.id_cnpj 
				left  join cnpj_raiz cr on cr.id = cn.id_cnpj_raiz
				left  join cnpj_raiz crr on crr.id = p.id_cnpj_raiz
				left join tipo_pendencia tp on tp.id = c.pendencia
				left  join esfera e on e.id = c.esfera 
				left  join tipo_ocorrencia tpo on tpo.id = p.tipo_ocorrencia
				left join etapa eta on eta.id = c.etapa
				left join area_focal af on af.codigo = c.area_focal
				where tp.id =  ? and p.status = 0
				and c.id in (
				select id_tratativa from (
				select p.id,
				(select max(id) from cnd_mob_tratativa cc where cc.id_cnd_mob = p.id)  as id_tratativa
				from 
				protesto p 
				where p.`status` = 0 and p.tipo_ocorrencia = $tipoOcorrencia) as aux where id_tratativa is not null)
				"; 			 
		$query = $this->db->query($sql, array($estado));
		
		//print_r($this->db->last_query());exit;
		$array = $query->result(); //array of arrays
		return($array);
			
	}
	
	
	public function listarprotestoByDetPend($estado,$tipoOcorrencia){

		 
		$sql = "select cr.cnpj_raiz,cn.cnpj,p.credor_favorecido,
				p.valor_titulo,tp.descricao,e.descricao_esfera,c.id_cnd_mob as id_tratativa,p.valor_protestado,
				ar.descricao_area_focal,p.status,cr.empresa as empresa_devedora_cr,tpo.descricao as ocorrencia ,crr.empresa as empresa_devedora_crr,p.tipo_ocorrencia,
				eta.descricao_etapa	
				from cnd_mob_tratativa c 
				left join protesto p on p.id = c.id_cnd_mob 
				left join cnpj cn on cn.id = p.id_cnpj 
				left join cnpj_raiz cr on cr.id = cn.id_cnpj_raiz
				left  join cnpj_raiz crr on crr.id = p.id_cnpj_raiz
				left join tipo_pendencia tp on tp.id = c.pendencia 
				left join esfera e on e.id = c.esfera 
				left join area_focal ar on ar.codigo = c.area_focal
				left  join tipo_ocorrencia tpo on tpo.id = p.tipo_ocorrencia
				left join etapa eta on eta.id = c.etapa
				where c.esfera =  ? and p.status =0 and c.id in (
				select id_tratativa from (
				select p.id,
				(select max(id) from cnd_mob_tratativa cc where cc.id_cnd_mob = p.id)  as id_tratativa
				from 
				protesto p 
				where p.`status` = 0 and p.tipo_ocorrencia = $tipoOcorrencia) as aux where id_tratativa is not null)"; 			 
		$query = $this->db->query($sql, array($estado));
		
		//print_r($this->db->last_query());exit;
		$array = $query->result(); //array of arrays
		return($array);
			
	}
	
		public function listarprotestoByResp($estado,$tipoOcorrencia){

		 
		$sql = "select p.valor_titulo,tp.descricao,e.descricao_esfera,c.id_cnd_mob as id_tratativa,
				p.valor_protestado,p.status,cr.empresa as empresa_devedora_cr,tpo.descricao as ocorrencia,
				eta.descricao_etapa,af.descricao_area_focal,crr.empresa as empresa_devedora_crr,p.tipo_ocorrencia 
				from cnd_mob_tratativa c 
				left join protesto p on p.id = c.id_cnd_mob 
				left join cnpj cn on cn.id = p.id_cnpj 
				left join cnpj_raiz cr on cr.id = cn.id_cnpj_raiz
				left join tipo_pendencia tp on tp.id = c.pendencia 
				left  join cnpj_raiz crr on crr.id = p.id_cnpj_raiz
				left  join tipo_ocorrencia tpo on tpo.id = p.tipo_ocorrencia
				left join etapa eta on eta.id = c.etapa
				left join area_focal af on af.codigo = c.area_focal
				left join esfera e on e.id = c.esfera where c.etapa =  ? 
				and c.id in (
				select id_tratativa from (
				select p.id,
				(select max(id) from cnd_mob_tratativa cc where cc.id_cnd_mob = p.id)  as id_tratativa
				from 
				protesto p 
				where p.`status` = 0 and p.tipo_ocorrencia = $tipoOcorrencia) as aux where id_tratativa is not null)				"; 			 
				//and p.protesto =0
		$query = $this->db->query($sql, array($estado));
		
		//print_r($this->db->last_query());exit;
		$array = $query->result(); //array of arrays
		return($array);
			
	}
	
	public function listarprotestoByOcorrencia($estado){

		 
		$sql = "select p.valor_titulo,tp.descricao,e.descricao_esfera,c.id_cnd_mob as id_tratativa,
				p.valor_protestado,p.status,cr.empresa as empresa_devedora_cr,tpo.descricao as ocorrencia,
				eta.descricao_etapa,af.descricao_area_focal,crr.empresa as empresa_devedora_crr,p.tipo_ocorrencia 
				from cnd_mob_tratativa c 
				left join protesto p on p.id = c.id_cnd_mob 
				left join cnpj cn on cn.id = p.id_cnpj 
				left join cnpj_raiz cr on cr.id = cn.id_cnpj_raiz
				left  join cnpj_raiz crr on crr.id = p.id_cnpj_raiz
				left join tipo_pendencia tp on tp.id = c.pendencia 
				left join esfera e on e.id = c.esfera 
				left join tipo_ocorrencia tpo on tpo.id = p.tipo_ocorrencia
				left join etapa eta on eta.id = c.etapa
				left join area_focal af on af.codigo = c.area_focal
				where p.tipo_ocorrencia =  ? and c.id in (
				select id_tratativa from (
				select p.id,
				(select max(id) from cnd_mob_tratativa cc where cc.id_cnd_mob = p.id)  as id_tratativa
				from 
				protesto p 
				where p.`status` = 0) as aux where id_tratativa is not null)"; 			 
		$query = $this->db->query($sql, array($estado));
		
		//print_r($this->db->last_query());exit;
		$array = $query->result(); //array of arrays
		return($array);
			
	}
	
	public function listarprotestoByArea($estado,$tipoOcorrencia){

		 
		$sql = "select p.valor_titulo,tp.descricao,e.descricao_esfera,c.id_cnd_mob as id_tratativa,
				p.valor_protestado,p.status,cr.empresa as empresa_devedora_cr,tpo.descricao as ocorrencia,
				eta.descricao_etapa,af.descricao_area_focal,crr.empresa as empresa_devedora_crr,p.tipo_ocorrencia 
				from cnd_mob_tratativa c 
				left join protesto p on p.id = c.id_cnd_mob 
				left join cnpj cn on cn.id = p.id_cnpj 
				left join cnpj_raiz cr on cr.id = cn.id_cnpj_raiz
				left  join cnpj_raiz crr on crr.id = p.id_cnpj_raiz
				left join tipo_pendencia tp on tp.id = c.pendencia 
				left join esfera e on e.id = c.esfera 
				left  join tipo_ocorrencia tpo on tpo.id = p.tipo_ocorrencia
				left join etapa eta on eta.id = c.etapa
				left join area_focal af on af.codigo = c.area_focal
				where c.area_focal =  ? and c.id in (
select id_tratativa from (
				select p.id,
				(select max(id) from cnd_mob_tratativa cc where cc.id_cnd_mob = p.id)  as id_tratativa
				from 
				protesto p 
				where p.`status` = 0 and p.tipo_ocorrencia = $tipoOcorrencia) as aux where id_tratativa is not null)"; 			 
				//and p.protesto =0
		$query = $this->db->query($sql, array($estado));
		
		//print_r($this->db->last_query());exit;
		$array = $query->result(); //array of arrays
		return($array);
			
	}
	
	public function listarprotestoByStatusGeral($id){

		 
		$sql = "select cr.cnpj_raiz,cn.cnpj,p.credor_favorecido,p.valor_titulo,tp.descricao,e.descricao_esfera,c.id_cnd_mob as id_tratativa,p.valor_protestado from cnd_mob_tratativa c 
				left join protesto p on p.id = c.id_cnd_mob left join cnpj cn on cn.id = p.id_cnpj left join cnpj_raiz cr on cr.id = cn.id_cnpj_raiz
				left join tipo_pendencia tp on tp.id = c.pendencia left join esfera e on e.id = c.esfera where c.status_chamado_sis_ext  =  ? and p.status =0 "; 			 
		$query = $this->db->query($sql, array($id));
		
		//print_r($this->db->last_query());exit;
		$array = $query->result(); //array of arrays
		return($array);
			
	}
	
	public function listarTipoOcorrencias(){

		 
		$sql = "select * from tipo_ocorrencia"; 			 
		$query = $this->db->query($sql, array());
		
		//print_r($this->db->last_query());exit;
		$array = $query->result(); //array of arrays
		return($array);
			
	}
	
	
	public function addEnc($detalhes = array()){
		$this->db->insert('protesto_encaminhamento', $detalhes);
		$id = $this->db->insert_id();
		return $id;
	}
	
	public function addArqProt($detalhes = array()){
		$this->db->insert('protesto_arquivos', $detalhes);
		$id = $this->db->insert_id();
		
		return $id;
	}
	
	
	public function addArqEnc($detalhes = array()){ 
		if($this->db->insert('protesto_enc_arquivos', $detalhes)) {
		return $id = $this->db->insert_id();
		}
		return false;
	}	
	
	public function listarProtestoTrackingById($id){
		$sql = "SELECT em.nome,em.email,em.cargo,enc.*,DATE_FORMAT(enc.data_envio,'%d/%m/%Y %H:%i:%s') as data_envio_br,ienc.arquivo FROM protesto_encaminhamento enc  left join email em on enc.id_email = em.id left join protesto_enc_arquivos ienc on enc.id = ienc.id_protesto_enc where enc.id_protesto = ? order by enc.id desc";  
		$query = $this->db->query($sql, array($id));
		//print_r($this->db->last_query());exit;
		$array = $query->result(); //array of arrays
		return($array);
	}		
	
	function listarTodasTratativas($id){
	 
	$sql = "select cnd_mob_tratativa.id,cnd_mob_tratativa.id as id_tratativa,cnd_mob_tratativa.pendencia,
	DATE_FORMAT(data_inclusao_sis_ext,'%d/%m/%Y') as data_envio_voiza ,
	DATE_FORMAT(prazo_solucao_sis_ext,'%d/%m/%Y') as prazo_solucao_voiza ,
	DATE_FORMAT(data_envio,'%d/%m/%Y') as data_envio_bd ,
	DATE_FORMAT(prazo_solucao,'%d/%m/%Y') as prazo_solucao_bd ,
	status_chamado_sis_ext,
	status_demanda,
	status_demanda.descricao_etapa,
	status_chamado_interno_mobiliario.descricao,
	data_atualizacao as ultima_tratativa,descricao_area_focal,contato,tp.descricao as desc_pendencia
	from cnd_mob_tratativa
	left join status_demanda on status_demanda.id = cnd_mob_tratativa.status_demanda
	left join status_chamado_interno_mobiliario on status_chamado_interno_mobiliario.id = cnd_mob_tratativa.status_chamado_sis_ext  
	left join area_focal ar on ar.codigo = cnd_mob_tratativa.area_focal
	left join tipo_pendencia tp on tp.id = cnd_mob_tratativa.pendencia
	where id_cnd_mob = ? 
	order by cnd_mob_tratativa.id desc
	";

	$query = $this->db->query($sql, array($id));
	//print_r($this->db->last_query());exit;
   if($query -> num_rows() <> 0){
     return $query->result();
   }else{
     return 0;
   }

 }
 
 function listarPendencias(){
   $this -> db -> select('*');
   $this -> db -> from('tipo_pendencia');
 
   $query = $this -> db -> get();

   
   if($query -> num_rows() <> 0)
   {
     return $query->result();
   }
   else
   {
     return false;
   }
 }
 
 
 function listarAreaFocal(){
   $this -> db -> select('*');
   $this -> db -> from('area_focal');
 
   $query = $this -> db -> get();

   
   if($query -> num_rows() <> 0)
   {
     return $query->result();
   }
   else
   {
     return false;
   }
 }
 
  function listarEsfera($id){
   $sql = "select id,descricao_esfera from esfera  where id_pendencia = ? ";
   $query = $this->db->query($sql, array($id));  
	
	$array = $query->result(); 
    return($array);
 }
 
 function listarEtapa(){
   $this -> db -> select('*');
   $this -> db -> from('etapa');
 
   $query = $this -> db -> get();

   
   if($query -> num_rows() <> 0)
   {
     return $query->result();
   }
   else
   {
     return false;
   }
 }
 
  function listarStatusInterno(){
   $this -> db -> select('*');
   $this -> db -> from('status_chamado_interno_mobiliario');
 
   $query = $this -> db -> get();

   
   if($query -> num_rows() <> 0)
   {
     return $query->result();
   }
   else
   {
     return false;
   }
 }
 
  function listarTratativaById($idContratante,$id){
	 
	$sql = "select cnd_mob_tratativa.id,
			cnd_mob_tratativa.tipo_tratativa,
			cnd_mob_tratativa.pendencia,
			cnd_mob_tratativa.esfera,cnd_mob_tratativa.etapa,
			esfera.descricao_esfera,etapa.descricao_etapa,
			DATE_FORMAT(cnd_mob_tratativa.data_informe_pendencia,'%d/%m/%Y') as data_informe_pendencia,
			cnd_mob_tratativa.id_sis_ext,
			DATE_FORMAT(cnd_mob_tratativa.data_inclusao_sis_ext,'%d/%m/%Y') as data_inclusao_sis_ext,
			DATE_FORMAT(cnd_mob_tratativa.prazo_solucao_sis_ext,'%d/%m/%Y') as prazo_solucao_sis_ext,
			DATE_FORMAT(cnd_mob_tratativa.data_encerramento_sis_ext,'%d/%m/%Y') as data_encerramento_sis_ext,
			status_chamado_interno.descricao as desc_chamado_int,
			cnd_mob_tratativa.status_chamado_sis_ext,
			cnd_mob_tratativa.sla_sis_ext,
			cnd_mob_tratativa.usu_inc,
			cnd_mob_tratativa.area_focal,
			cnd_mob_tratativa.sub_area_focal,
			cnd_mob_tratativa.contato,
			DATE_FORMAT(cnd_mob_tratativa.data_envio,'%d/%m/%Y') as data_envio,
			DATE_FORMAT(cnd_mob_tratativa.prazo_solucao,'%d/%m/%Y') as prazo_solucao,
			DATE_FORMAT(cnd_mob_tratativa.data_retorno,'%d/%m/%Y') as data_retorno,
			cnd_mob_tratativa.sla,
			cnd_mob_tratativa.status_demanda,
			status_demanda.descricao_etapa,
			DATE_FORMAT(cnd_mob_tratativa.esc_data_prazo_um,'%d/%m/%Y') as esc_data_prazo_um,
			DATE_FORMAT(cnd_mob_tratativa.esc_data_retorno_um,'%d/%m/%Y') as esc_data_retorno_um,
			cnd_mob_tratativa.esc_status_um,
			DATE_FORMAT(cnd_mob_tratativa.esc_data_prazo_dois,'%d/%m/%Y') as esc_data_prazo_dois,
			DATE_FORMAT(cnd_mob_tratativa.esc_data_retorno_dois,'%d/%m/%Y') as esc_data_retorno_dois,
			cnd_mob_tratativa.esc_status_dois,
			DATE_FORMAT(cnd_mob_tratativa.esc_data_prazo_dois,'%d/%m/%Y') as esc_data_prazo_tres,
			DATE_FORMAT(cnd_mob_tratativa.esc_data_retorno_dois,'%d/%m/%Y') as esc_data_retorno_tres,
			cnd_mob_tratativa.esc_status_tres
			from cnd_mob_tratativa 
			left join esfera on esfera.id = cnd_mob_tratativa.esfera
			left join etapa on etapa.id = cnd_mob_tratativa.etapa
			left join status_chamado_interno on status_chamado_interno.id = cnd_mob_tratativa.status_chamado_sis_ext
			left join status_demanda on status_demanda.id = cnd_mob_tratativa.id
		where cnd_mob_tratativa.id_contratante = ? and cnd_mob_tratativa.id = ?";

	$query = $this->db->query($sql, array($idContratante,$id));
//print_r($this->db->last_query());exit;
   if($query -> num_rows() <> 0){
     return $query->result();
   }else{
     return 0;
   }

 }
	public function listarProtestoById($id){
		// $sql = "select i.*,c.nome as razao_social,c.cnpj,cr.cnpj_raiz,DATE_FORMAT(i.data_protesto,'%d/%m/%Y') as data_protesto_br,DATE_FORMAT(i.vencimento,'%d/%m/%Y') as vencimento_br,ie.numero as num_ie,im.numero as num_im,c.id_uf,c.id_municipio,cr.id as id_cnpj_raiz,c.ID as id_cnpj,
		// cnpj_credor,DATE_FORMAT(i.data_admissao_titulo,'%d/%m/%Y') as data_admissao_titulo_br,nr_auto_infracao,est.uf,cid.nome as cidade,nat.descricao_natureza,cpt.descricao as desc_compent,
		// estP.uf as estado_protesto,cidP.nome as cidade_protesto,crr.empresa as empresa_devedora,crr.cnpj_raiz as cnpj_raiz_segundo,natr.descricao_natureza as desc_nat,i.id as idOco,i.id_uf as idEstado,i.id_municipio as idCidade,
		// crr.id as id_cnpj_raiz_segundo
		// from protesto i 
		// left join cnpj c on i.id_cnpj = c.id 
		// left join cnpj_raiz cr on c.id_cnpj_raiz = cr.id 
		// left join inscricao ie on ie.id = i.id_ie and ie.tipo = 1  
		// left join inscricao im on im.id = i.id_im and im.tipo = 2 
		// left join estados est on est.id = i.id_uf
		// left join cidades cid on cid.id = i.id_municipio
		// left join natureza nat on nat.id = c.id
		// left join competencia_legis cpt on cpt.id = c.id
		// left join estados estP on estP.id = i.id_uf_protesto 
		// left join cidades cidP on cidP.id = i.id_municipio_protesto
		// left join cnpj_raiz crr on crr.id = i.id_cnpj_raiz
		 // left join natureza natr on natr.id = i.id_natureza
		// where i.id = ?"; 
		
		$sql = "select i.*,c.nome as razao_social,c.cnpj,cr.cnpj_raiz,DATE_FORMAT(i.data_protesto,'%d/%m/%Y') as data_protesto_br,DATE_FORMAT(i.vencimento,'%d/%m/%Y') as vencimento_br,
		c.id_uf,c.id_municipio,cr.id as id_cnpj_raiz,c.ID as id_cnpj,
		cnpj_credor,DATE_FORMAT(i.data_admissao_titulo,'%d/%m/%Y') as data_admissao_titulo_br,
		nr_auto_infracao,est.uf,cid.nome as cidade,nat.descricao_natureza,cpt.descricao as desc_compent,
		estP.uf as estado_protesto,cidP.nome as cidade_protesto,crr.empresa as empresa_devedora,crr.cnpj_raiz as cnpj_raiz_segundo,
		natr.descricao_natureza as desc_nat,i.id as idOco,i.id_uf as idEstado,i.id_municipio as idCidade,
		crr.id as id_cnpj_raiz_segundo
		from protesto i 
		left join cnpj c on i.id_cnpj = c.id 
		left join cnpj_raiz cr on c.id_cnpj_raiz = cr.id 
		left join estados est on est.id = i.id_uf
		left join cidades cid on cid.id = i.id_municipio
		left join natureza nat on nat.id = c.id
		left join competencia_legis cpt on cpt.id = c.id
		left join estados estP on estP.id = i.id_uf_protesto 
		left join cidades cidP on cidP.id = i.id_municipio_protesto
		left join cnpj_raiz crr on crr.id = i.id_cnpj_raiz
		 left join natureza natr on natr.id = i.id_natureza
		where i.id = ?"; 
		
		$query = $this->db->query($sql, array($id));
		//print_r($this->db->last_query());exit;
		$array = $query->result(); //array of arrays
		return($array);
			
	}
	
	public function contaProtestoByUf($uf){	
		if($uf == '0'){
			$sql = "select  count(*) as total  from protesto i join cnpj c on i.id_cnpj = c.id  left join estados e on e.id = i.id_uf where i.status =0"; 
			$query = $this->db->query($sql, array());
		}else{
			$sql = "select  count(*) as total  from protesto i join cnpj c on i.id_cnpj = c.id  left join estados e on e.id = i.id_uf where e.uf = ? and i.status =0"; 
			$query = $this->db->query($sql, array($uf));
		}		
		//print_r($this->db->last_query());exit;
		$array = $query->result(); //array of arrays
		return($array);
			
	}
	
	public function contaProtestoByStatus($status,$tipoOcorrencia){
		
		$sql = "select count(*) as total from protesto p where p.status = ? and tipo_ocorrencia = ?"; 
		$query = $this->db->query($sql, array($status,$tipoOcorrencia));
		
		//print_r($this->db->last_query());exit;
		$array = $query->result(); //array of arrays
		return($array);
			
	}
	
	public function contaProtestoByData($data,$tipo,$tipoOcorrencia){
		
		$ultimoDia = date("t", strtotime($data));
		$dtIni = $data.'-01';
		$dtFim = $data.'-'.$ultimoDia;
		
		if($tipo == 1){
			$sql = "select count(*) as total from protesto p where p.data_protesto between ? and ? and tipo_ocorrencia = ?"; 
		}else{
			$sql = "select count(*) as total from protesto p where p.data_baixa_protesto between ? and ? and tipo_ocorrencia = ?"; 
		}
		
		$query = $this->db->query($sql, array($dtIni,$dtFim,$tipoOcorrencia));
		
		//print_r($this->db->last_query());exit;
		$array = $query->result(); //array of arrays
		return($array);
			
	}
	
	public function contaProtestoByDetalhe($tipoOcorrencia){
		
		$sql = "select count(*) as total,e.descricao as status,e.cor,e.id from protesto p  join cnd_mob_tratativa c on p.id = c.id_cnd_mob join tipo_pendencia e on c.pendencia = e.id where p.status = 0 group by e.descricao"; 
		$sql = "select count(*) as total,e.descricao as status,e.cor,e.id   from cnd_mob_tratativa c 
				join tipo_pendencia e on c.pendencia = e.id
				where c.id in (
				select id_tratativa from (
				select p.id,
				(select max(id) from cnd_mob_tratativa cc where cc.id_cnd_mob = p.id)  as id_tratativa
				from 
				protesto p 
				where p.`status` = 0 and tipo_ocorrencia = $tipoOcorrencia) as aux 
				where id_tratativa is not null)
				 group by e.descricao"; 
		$query = $this->db->query($sql, array());
		
		//print_r($this->db->last_query());exit;
		$array = $query->result(); //array of arrays
		return($array);
			
	}
	
	public function contaProtestoTipoOcorrencia($tipoOcorrencia){
		
		$sql = "select count(*) as total, tp.descricao,tp.cor,tp.id from protesto p join tipo_ocorrencia tp on tp.id = p.tipo_ocorrencia and p.`status` = 0 and p.tipo_ocorrencia = $tipoOcorrencia group by tp.descricao"; 
		$query = $this->db->query($sql, array());
		
		
		
		//print_r($this->db->last_query());exit;
		$array = $query->result(); //array of arrays
		return($array);
			
	}
	
	public function contaProtestoByDetalhePendencia($tipoOcorrencia){
		
		$sql = "select count(*) as total,e.descricao_esfera as status,e.cor,e.id as id_cnd from protesto p join cnd_mob_tratativa c on p.id = c.id_cnd_mob join esfera e on c.esfera = e.id where p.status = 0 group by e.descricao_esfera"; 
		
		$sql = "select count(*) as total,e.descricao_esfera as status,e.cor,e.id as id_cnd   from cnd_mob_tratativa c 
			join esfera e on c.esfera = e.id
			where c.id in (
			select id_tratativa from (
			select p.id,
			(select max(id) from cnd_mob_tratativa cc where cc.id_cnd_mob = p.id)  as id_tratativa
			from 
			protesto p 
			where p.`status` = 0 and tipo_ocorrencia = $tipoOcorrencia) as aux 
			where id_tratativa is not null)
			 group by e.descricao_esfera"; 
		$query = $this->db->query($sql, array());
		
		//print_r($this->db->last_query());exit;
		$array = $query->result(); //array of arrays
		return($array);
			
	}
	
	public function contaProtestoByResp($tipoOcorrencia){
		
		$sql = "select count(*) as total,e.descricao_etapa as status,e.cor,c.etapa from protesto p join cnd_mob_tratativa c on p.id = c.id_cnd_mob join etapa e on c.etapa = e.id where p.status = 0 group by e.descricao_etapa"; 
		$sql = "select count(*) as total,e.descricao_etapa as status,e.cor,e.id as etapa  from cnd_mob_tratativa c 
		join etapa e on c.etapa = e.id
		where c.id in (
		select id_tratativa from (
		select p.id,
		(select max(id) from cnd_mob_tratativa cc where cc.id_cnd_mob = p.id)  as id_tratativa
		from 
		protesto p 
		where p.`status` = 0 and tipo_ocorrencia = $tipoOcorrencia) as aux 
		where id_tratativa is not null)
		 group by e.descricao_etapa"; 
		$query = $this->db->query($sql, array());
		
		//print_r($this->db->last_query());exit;
		$array = $query->result(); //array of arrays
		return($array);
			
	}
	
	public function contaProtestoByArea($tipoOcorrencia){
		
		$sql = "select count(*) as total,e.descricao_area_focal as status,e.cor,c.area_focal from protesto p join cnd_mob_tratativa c on p.id = c.id_cnd_mob join area_focal e on c.area_focal = e.codigo where p.status = 0 group by e.descricao_area_focal"; 
		$sql = "select count(*) as total,e.descricao_area_focal as status,e.cor,c.area_focal    from cnd_mob_tratativa c 
			join area_focal e on c.area_focal = e.codigo
			where c.id in (
			select id_tratativa from (
			select p.id,
			(select max(id) from cnd_mob_tratativa cc where cc.id_cnd_mob = p.id)  as id_tratativa
			from 
			protesto p 
			where p.`status` = 0 and tipo_ocorrencia = $tipoOcorrencia) as aux 
			where id_tratativa is not null)
			 group by e.descricao_area_focal"; 
		$query = $this->db->query($sql, array());
		
		//print_r($this->db->last_query());exit;
		$array = $query->result(); //array of arrays
		return($array);
			
	}
	
	public function listarNatureza(){
		$sql = "select id,descricao_natureza from natureza"; 
		$query = $this->db->query($sql, array());
		//print_r($this->db->last_query());exit;
		$array = $query->result(); //array of arrays
		return($array);
			
	}
	
	public function listarArquivoProtestoById($id){
		$sql = "select id,id_Protesto,nome_arquivo,arquivo from protesto_arquivos a where a.id_Protesto =  ?"; 
		$query = $this->db->query($sql, array($id));
		//print_r($this->db->last_query());exit;
		$array = $query->result(); //array of arrays
		return($array);
			
	}
	
	public function atualizar($tabela,$dados,$id){  

	$this->db->where('id', $id);
	$this->db->update($tabela, $dados); 

	return true;  
 } 
 
 

 public function add_tratativa($detalhes = array()){
	if($this->db->insert('cnd_mob_tratativa', $detalhes)) {			
		$id = $this->db->insert_id();
		return $id;
	}
	return false;
}

  function listarObsTratById($id){ 
	$sql = "select DATE_FORMAT(c.data,'%d/%m/%Y') as data,c.hora,c.data_hora,u.email,u.nome_usuario,c.observacao,c.id,c.arquivo,p.`status`
		from cnd_mob_tratativa_obs c left join usuarios u on u.id = c.id_usuario 
		left join cnd_mob_tratativa cnd on cnd.id = c.id_cnd_trat
		left join protesto p on p.id = cnd.id_cnd_mob
		where c.id_cnd_trat = ? order by c.id desc";
	$query = $this->db->query($sql, array($id));
	//print_r($this->db->last_query());exit;
   if($query -> num_rows() <> 0){
     return $query->result();
   }else{
     return 0;
   }

 }


 function listarUltimaObsTratById($id){
	 
	$sql = "select 	max(c.id) as id from cnd_mob_tratativa_obs c where c.id_cnd_trat = ? ";

	$query = $this->db->query($sql, array($id));
	

   if($query -> num_rows() <> 0){
     return $query->result();
   }else{
     return 0;
   }

 }
 
 function addObsTrat($detalhes = array()){ 
	if($this->db->insert('cnd_mob_tratativa_obs', $detalhes)) {
		return $id = $this->db->insert_id();
	}
	return false;
 }




     function buscaTodasObservacoes($id,$modulo){	
	$sql = "SELECT DATE_FORMAT(data,'%d/%m/%Y') as data,hora,observacao,usuarios.email
			FROM (`cnd_mob_observacao`) 	
			left join usuarios on usuarios.id = cnd_mob_observacao.id_usuario
			WHERE `cnd_mob_observacao`.`id_cnd_mob` = ? and cnd_mob_observacao.modulo = ?
			ORDER BY `cnd_mob_observacao`.`data` ";
			
	$query = $this->db->query($sql, array($id,$modulo));
	
	$array = $query->result(); 
    return($array);

 }

 function listarInscricaoByLoja($id){
	 
	  $sql = "SELECT 
				*, `cnd_mobiliaria`.`ativo` as status_insc, `cnd_mobiliaria`.`id` as id_cnd, `loja`.`ins_cnd_mob` as ins_cnd_mob, 
				`loja`.`id` as id_loja, 
				DATE_FORMAT(data_emissao,'%d/%m/%Y') as data_emissao_br,DATE_FORMAT(data_vencto,'%d/%m/%Y') as data_vencto_br,DATE_FORMAT(data_pendencias,'%d/%m/%Y') as data_pendencia_br								
	  FROM (`cnd_mobiliaria`) 
	  LEFT JOIN `loja` ON `cnd_mobiliaria`.`id_loja` = `loja`.`id` 
	  WHERE `cnd_mobiliaria`.`id` = ? ";
	 $query = $this->db->query($sql, array($id));	
	 
	 //print_r($this->db->last_query());exit;
	 if($query -> num_rows() <> 0){
		return $query->result();
	 }else{
		return false;
	 }

 }


   function atualizar_tratativa($dados,$id){
	$this->db->where('id', $id);
	$this->db->update('cnd_mob_tratativa', $dados); 
	

	return true;
 }

  public function inserirNovoArquivo($detalhes = array()){
	if($this->db->insert('arquivo_tratativas', $detalhes)) {
		$id = $this->db->insert_id();
		return $id;
	}
	return false;

}


  function listarArquivosMobiliaria($id){ 
	$sql = "select arquivo from arquivo_tratativas a where a.id_tratativas = ? and modulo = 0";
	$query = $this->db->query($sql, array($id));
   if($query -> num_rows() <> 0){
     return $query->result();
   }else{
     return 0;
   }

 } 
 

function listarTratativasIdCnd($idCnd){
	 
	$sql = "select cnd_mob_tratativa.id,cnd_mob_tratativa.pendencia,
			cnd_mob_tratativa.esfera,cnd_mob_tratativa.etapa,
			esfera.descricao_esfera,etapa.descricao_etapa,
			DATE_FORMAT(cnd_mob_tratativa.data_informe_pendencia,'%d/%m/%Y') as data_informe_pendencia,
			cnd_mob_tratativa.id_sis_ext,
			DATE_FORMAT(cnd_mob_tratativa.data_inclusao_sis_ext,'%d/%m/%Y') as data_inclusao_sis_ext,
			cnd_mob_tratativa.prazo_solucao_sis_ext,
			DATE_FORMAT(cnd_mob_tratativa.data_encerramento_sis_ext,'%d/%m/%Y') as data_encerramento_sis_ext,
			status_chamado_interno.descricao as desc_chamado_int,
			cnd_mob_tratativa.status_chamado_sis_ext,
			cnd_mob_tratativa.sla_sis_ext,
			cnd_mob_tratativa.usu_inc,
			cnd_mob_tratativa.area_focal,
			cnd_mob_tratativa.sub_area_focal,
			cnd_mob_tratativa.contato,
			DATE_FORMAT(cnd_mob_tratativa.data_envio,'%d/%m/%Y') as data_envio,
			DATE_FORMAT(cnd_mob_tratativa.prazo_solucao,'%d/%m/%Y') as prazo_solucao,
			DATE_FORMAT(cnd_mob_tratativa.data_retorno,'%d/%m/%Y') as data_retorno,
			cnd_mob_tratativa.sla,
			cnd_mob_tratativa.status_demanda,
			status_demanda.descricao_etapa as desc_demanda,
			DATE_FORMAT(cnd_mob_tratativa.esc_data_prazo_um,'%d/%m/%Y') as esc_data_prazo_um,
			DATE_FORMAT(cnd_mob_tratativa.esc_data_retorno_um,'%d/%m/%Y') as esc_data_retorno_um,
			cnd_mob_tratativa.esc_status_um,
			DATE_FORMAT(cnd_mob_tratativa.esc_data_prazo_dois,'%d/%m/%Y') as esc_data_prazo_dois,
			DATE_FORMAT(cnd_mob_tratativa.esc_data_retorno_dois,'%d/%m/%Y') as esc_data_retorno_dois,
			cnd_mob_tratativa.esc_status_dois,
			DATE_FORMAT(cnd_mob_tratativa.esc_data_prazo_dois,'%d/%m/%Y') as esc_data_prazo_tres,
			DATE_FORMAT(cnd_mob_tratativa.esc_data_retorno_dois,'%d/%m/%Y') as esc_data_retorno_tres,
			cnd_mob_tratativa.esc_status_tres,tipo_tratativa
			from cnd_mob_tratativa 
			left join esfera on esfera.id = cnd_mob_tratativa.esfera
			left join etapa on etapa.id = cnd_mob_tratativa.etapa
			left join status_chamado_interno on status_chamado_interno.id = cnd_mob_tratativa.status_chamado_sis_ext
			left join status_demanda on status_demanda.id = cnd_mob_tratativa.id
		where cnd_mob_tratativa.id_cnd_mob = ? and modulo = 1 order by cnd_mob_tratativa.id desc";

	$query = $this->db->query($sql, array($idCnd));
	

   if($query -> num_rows() <> 0){
     return $query->result();
   }else{
     return 0;
   }

 }
 
}
?>
