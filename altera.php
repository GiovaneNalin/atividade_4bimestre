<?php
	
	include("conexao.php");
	
	$id = $_POST["id"];
	$nome = $_POST["nome"];
	$email = $_POST["email"];
	$sexo = $_POST["sexo"];
	$cod_cidade = $_POST["cod_cidade"];
	$salario = $_POST["salario"];
	
	$alteracao = "UPDATE cadastro SET 
				nome = '$nome',
				email = '$email',
				sexo = '$sexo',
				cod_cidade = '$cod_cidade',
				salario = '$salario'
				WHERE id_cadastro = '$id'";

	mysqli_query($conexao,$alteracao)
		or die(mysqli_error($conexao));
	
	echo "1";
	
?>