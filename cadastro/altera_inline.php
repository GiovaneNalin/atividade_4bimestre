<?php
	
	include("conexao.php");
	
	$coluna = $_POST["coluna"];
	$valor = $_POST["valor"];
	$id = $_POST["id"];
	
	$alteracao = "UPDATE cadastro SET 
				$coluna = '$nome'
				WHERE id_cadastro = '$id'";

	mysqli_query($conexao,$alteracao)
		or die(mysqli_error($conexao));
	
	echo "1";
	
?>