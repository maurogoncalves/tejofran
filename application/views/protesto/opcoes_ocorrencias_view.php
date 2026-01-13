<select name="tipoOcorrencia" tabindex="1" id="tipoOcorrencia" >
	<option value=0> Escolher tipo de ocorr&ecirc;ncia para que os filtros apare&ccedil;am </option>		
	<?php foreach($ocorrencias as $oco){
		echo"<option value=".$oco->id_jquery.">".$oco->descricao."</option>";
	}
	?>
</select>
<br>
