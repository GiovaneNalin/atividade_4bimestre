<?php
	
	include("conexao.php");
	
	$id_estado = $_POST["id_estado"];
	$nome = $_POST["nome"];
	$uf = $_POST["uf"];
	
	$alteracao = "UPDATE cadastro SET 
				nome = '$nome',
				uf = '$uf'
				WHERE id_estado = '$id_estado'";

	mysqli_query($conexao,$alteracao)
		or die(mysqli_error($conexao));
	
	echo "1";
	
?>