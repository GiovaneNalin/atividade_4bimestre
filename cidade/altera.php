<?php
	
	include("conexao.php");
	
	$nome = $_POST["nome"];
	$cod_estado = $_POST["cod_estado"];
	
	$alteracao = "UPDATE cadastro SET 
				nome = '$nome',
				cod_estado = '$cod_estado'
				WHERE id_cadastro = '$id'";

	mysqli_query($conexao,$alteracao)
		or die(mysqli_error($conexao));
	
	echo "1";
	
?>