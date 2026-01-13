var status = <?php echo$status;?>;
$( "#tipoOcorrencia" ).change(function() {
		$(".todos").hide();
		var tipoOcorrencia = $('#tipoOcorrencia').val();	
		//$("#"+tipoOcorrencia).show();		
		if(tipoOcorrencia == 'pefin'){
			window.location.href = "<?php echo$base?>index.php/protesto/listarPefin/"+status;
		}else if(tipoOcorrencia == 'refin'){
			window.location.href = "<?php echo$base?>index.php/protesto/listarRefin/"+status;
		}else if(tipoOcorrencia == 'cheque'){
			window.location.href = "<?php echo$base?>index.php/protesto/listarCheque/"+status;
		}else if(tipoOcorrencia == 'protesto'){
			window.location.href = "<?php echo$base?>index.php/protesto/listar/"+status;
		}else if(tipoOcorrencia == 'acao'){
			window.location.href = "<?php echo$base?>index.php/protesto/listarAcao/"+status;
		}else if(tipoOcorrencia == 'falencia'){
			window.location.href = "<?php echo$base?>index.php/protesto/listarFalencia/"+status;
		}else if(tipoOcorrencia == 'divida'){
			window.location.href = "<?php echo$base?>index.php/protesto/listarDivida/"+status;
		}
			
	});