<?php
	include("conexao.php");
	
	$id = $_POST["id"];
	
	$remocao = "DELETE FROM cadastro WHERE id_cadastro = '$id'";
		
	mysqli_query($conexao,$remocao)
		or die("0");
			
	echo 1;
?>