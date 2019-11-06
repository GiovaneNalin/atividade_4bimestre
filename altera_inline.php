<?php
	
	include("conexao.php");
	
	$tabela = $_POST["tabela"];
	$coluna = $_POST["coluna"];
	$valor = $_POST["valor"];
	$id = $_POST["id"];
	
	$alteracao = "UPDATE $tabela SET 
				$coluna = '$valor'
				WHERE id_$tabela = '$id'";

	mysqli_query($conexao,$alteracao)
		or die(mysqli_error($conexao));
	
	echo "1";
	
?>