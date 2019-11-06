<?php
	
	include("conexao.php");
	
	$id = $_POST["id"];
	$nome = $_POST["nome"];
	$email = $_POST["email"];
	$sexo = $_POST["sexo"];
	
	$alteracao = "UPDATE cadastro SET 
				nome = '$nome',
				email = '$email',
				sexo = '$sexo'
				WHERE id_cadastro = '$id'";

	mysqli_query($conexao,$alteracao)
		or die(mysqli_error($conexao));
	
	echo "1";
	
?>