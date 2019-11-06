<!DOCTYPE html>

<html lang = "pt-BR">
	
	<head>
		
		<title>Cadastro</title>
		<meta charset = "UTF-8" />
		<script src= "jquery-3.4.1.min.js"></script>
		<script>
			
			var id = null;
			
			$(function(){
				
				paginacao(0);
				
				$(document).on("click",".alterar",function(){
					id = $(this).attr("value");
					$.ajax({
						url: "carrega_cadastro_alterar.php",
						type: "post",
						data: {id: id},
						success: function(vetor){
							$("input[name='nome']").val(vetor.nome);
							$("input[name='email']").val(vetor.email);
							if(vetor.sexo=='F'){
								$("input[name='sexo'][value='M']").attr("checked",false);
								$("input[name='sexo'][value='F']").attr("checked",true);
							}else {
								$("input[name='sexo'][value='F']").attr("checked",false);
								$("input[name='sexo'][value='M']").attr("checked",true);
							}
							$(".cadastrar").attr("class","alteracao");
							$(".alteracao").val("Alterar Cadastro");
						}
					});
				});
				
				function paginacao(p) {
					$.ajax ({
						url: "carrega_cadastro.php",
						type: "post",
						data: {pg: p},
						success: function(matriz){
							$("#identificador").html("");
							for(i=0;i<matriz.length;i++){
								linha = "<tr>";
								linha += "<td class = 'nome'>" + matriz[i].nome + "</td>";
								linha += "<td class = 'email'>" + matriz[i].email + "</td>";
								linha += "<td class = 'sexo'>" + matriz[i].sexo + "</td>";
								linha += "<td><button type = 'button' id = 'nome_alterar' class = 'alterar' value ='" + matriz[i].id_cadastro + "'>Alterar</button> | <button type = 'button' class ='remover' value ='" + matriz[i].id_cadastro + "'>Remover</button></td>";
								linha += "</tr>";
								$("#identificador").append(linha);
							}
						}
					});
				}
				
				$(".pg").click(function(){
					p = $(this).val();
					p = (p-1)*5;
					paginacao(p);
				});
				
				$(document).on("click",".cadastrar",function(){
					$.ajax({ 
						url: "insere.php",
						type: "post",
						data: {nome:$("input[name='nome']").val(), email:$("input[name='email']").val(), sexo:$("input[name='sexo']:checked").val()},
						success: function(data){
							if(data==1){
								$("#resultado").html("Cadastro efetuado!");
							}else {
								console.log(data);
							}
						}
					});
				});
				$(document).on("click",".alteracao",function(){
					$.ajax({ 
						url: "altera.php",
						type: "post",
						data: {id: id, nome:$("input[name='nome']").val(), email:$("input[name='email']").val(), sexo:$("input[name='sexo']:checked").val()},
						success: function(data){
							if(data==1){
								$("#resultado").html("Alteração efetuada!");
								paginacao(0);
								$("input[name='nome']").val("");
								$("input[name='email']").val("");
								$("input[name='sexo'][value='M']").attr("checked",false)
								$("input[name='sexo'][value='F']").attr("checked",false)
								$(".alteracao").attr("class","cadastrar");
								$(".cadastrar").val("Cadastrar");
							}else {
								console.log(data);
							}
						}
					});
				});
				
				$(document).on("click",".nome",function(){
					td = $(this);
					nome = td.html();
					td.html("<input type = 'text' name = 'nome' value = '" + nome + "' />");
					td.attr("class","nome_alterar");
				});
				
				$(document).on("blur",".nome_alterar",function(){
					td = $(this);
					id_linha = $(this).closest("tr").find("button").val();
					$.ajax({
						url: "altera_inline.php",
						type: "post",
						data: {coluna: 'nome', valor: $("#nome_alterar").val(), id: id_linha},
						success: function(){
							nome = $("#nome_alterar").val();
							td.html(nome);
							td.attr("class","nome");
						}
					});
				});
				
			});
		
		</script>
		
	</head>
	
	<body>
		
		<h3>Cadastro de Estados</h3>
		<?php
			include("menu.html");
		?>
		<form>
			
			<input type = "text" name = "nome" placeholder = "Nome..." /><br /><br />
			<input type = "text" name = "uf" placeholder = "UF..." /><br /><br />
			
			<input type = "button" class = "cadastrar" value = "Cadastrar" />
			
		</form>
		
		<br />
		
		<div id = "resultado"></div>
		
		<br />
		
		<h3>Cadastros</h3>
		
		<table border = '1'>
						
			<thead>
				<tr>
					<th>ID_Estado</th>
					<th>Nome</th>
					<th>UF</th>
					<th>Ação</th>
				</tr>
			 </thead>
		
			<tbody id = 'identificador'></tbody>
					
		</table>
		<br /><br />
		
		<?php
			
			include("conexao.php");
				
				// $consulta = "SELECT * FROM cadastro ORDER BY nome";
				
				// $resultado = mysqli_query($conexao,$consulta) or die ("Erro." . mysqli_query($conexao)); 
				

			include("paginacao_cadastro.php");
			
		?>
		
	</body>
	
</html>