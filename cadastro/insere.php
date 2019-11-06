<?php
	
	include("conexao.php");
	
	$nome = $_POST["nome"];
	$email = $_POST["email"];
	$sexo = $_POST["sexo"];
	$cod_cidade = $_POST["cod_cidade"];
	$salario = $_POST["salario"];
	
	$insercao = "INSERT INTO cadastro (nome, email, sexo, cod_cidade, salario)
						VALUES('$nome', '$email', '$sexo', '$cod_cidade', '$salario')";

	mysqli_query($conexao,$insercao)
		or die("0");
	
	echo "1";
	
?>