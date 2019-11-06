<?php
	
	include("conexao.php");
	
	$nome = $_POST["nome"];
	$uf = $_POST["uf"];

	$insercao = "INSERT INTO estado (nome, uf)
						VALUES('$nome', '$uf')";

	mysqli_query($conexao,$insercao)
		or die("0");
	
	echo "1";
	
?>