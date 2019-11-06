<?php

	$user = "root";
	$senha = "usbw";
	$banco = "ex_4obimestre";
	$server = "localhost";
	
	$conexao = mysqli_connect($server, $user, $senha, $banco);
	
	mysqli_set_charset($conexao,"utf8");
?>