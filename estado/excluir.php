<?php
	include("conexao.php");
	
	$id = $_GET["id"];

	$remocao = "DELETE FROM estado WHERE id_estado ='$id'";

	mysqli_query($conexao,$remocao)
			or die("0");
	echo "1";
?>