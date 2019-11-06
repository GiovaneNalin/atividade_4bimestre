<?php
	
	include("conexao.php");
	
	$sql = "SELECT COUNT(*) AS qtd FROM cidade ";
	
	if(!empty($_POST)){
		$nome = $_POST["nome_filtro"];
		$sql .= " WHERE nome LIKE '%$nome%'";
	}
	
	$resultado = mysqli_query($conexao,$sql) or die ("Erro." . mysqli_query($conexao));
	
	$linha = mysqli_fetch_assoc($resultado);
	
	$qtd_tuplas = $linha["qtd"];
	
	$qtd_botoes = (int)($qtd_tuplas / 5);
	
	if ($qtd_tuplas%5!=0){
		$qtd_botoes++;
	}
	
	for ($i=1; $i<=$qtd_botoes; $i++) {
		echo "<button type = 'button' class = 'pg' value = '$i'>$i</button> ";
	}
	
?>