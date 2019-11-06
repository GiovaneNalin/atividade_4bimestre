<!DOCTYPE html>

<html lang = "pt-BR">
	
	<head>
		
		<title>Cadastro</title>
		<meta charset = "UTF-8" />
		<script src= "jquery-3.4.1.min.js"></script>
		<script>
			
			var id = null;
			var filtro = null;
			$(function(){
				
				//FILTRO
				$("#filtrar").click(function(){
					$.ajax({
						url:"paginacao_cidade.php",
						type:"post",
						data:{
							nome_filtro: $("input[name='nome_filtro']").val()
						},
						success:  function(d){
							$("#paginacao").html(d);
							filtro = $("input[name='nome_filtro']").val();
							paginacao(0);
							
						}
					});
				});
				
				paginacao(0);
				
				//ALTERAR
				$(document).on("click",".alterar",function(){
					id = $(this).attr("value");
					$.ajax({
						url: "carrega_cidade_alterar.php",
						type: "post",
						data: {id: id},
						success: function(vetor){
							$("input[name='nome']").val(vetor.nome);
							$("input[name='cod_estado']").val(vetor.cod_estado);
							$(".cadastrar").attr("class","alteracao");
							$(".alteracao").val("Alterar cidade");
						}
					});
				});
				
				
				//PAGINACAO
				function paginacao(p) {
					$.ajax ({
						url: "carrega_cidade.php",
						type: "post",
						data: {pg: p, nome_filtro: filtro},
						success: function(matriz){
							$("#identificador").html("");
							for(i=0;i<matriz.length;i++){
								linha = "<tr>";
								linha += "<td class = 'nome'>" + matriz[i].nome + "</td>";
								linha += "<td class = 'cod_estado'>" + matriz[i].cod_estado + "</td>";
								linha += "<td><button type = 'button' class = 'alterar' value ='" + matriz[i].id_cidade + "'>Alterar</button> | <button type = 'button' class ='remover' value ='" + matriz[i].id_cidade + "'>Remover</button></td>";
								linha += "</tr>";
								$("#identificador").append(linha);
							}
						}
					});
				}
				
				//CALCULO PAGINAÇÃO
				$(document).on("click",".pg", function(){
					p = $(this).val();
					p = (p-1)*5;
					paginacao(p);
				});
				
				//INSERE
				$(document).on("click",".cadastrar",function(){
					$.ajax({ 
						url: "insere_cidade.php",
						type: "post",
						data: {nome:$("input[name='nome']").val(), 								
								cod_estado:$("select[name='cod_estado']").val()},
						success: function(data){
							if(data==1){
								$("#resultado").html("Cadastro efetuado!");
							}else {
								console.log(data);
							}
						}
					});
				});
				
				//ALTERACAO
				$(document).on("click",".alteracao",function(){
					$.ajax({ 
						url: "altera.php",
						type: "post",
						data: {id: id, nome:$("input[name='nome']").val(), 							
							cod_estado:$("select[name='cod_estado']").val()},
						success: function(data){
							if(data==1){
								$("#resultado").html("Alteração efetuada!");
								paginacao(0);
								$("input[name='nome']").val("");								
								$("select[name='cod_cidade']").val("");						
								$(".alteracao").attr("class","cadastrar");
								$(".cadastrar").val("Cadastrar");
							}else {
								console.log(data);
							}
						}
					});
				});
				
				//ALTERAÇÃO INLINE
				//nome
				$(document).on("click",".nome",function(){
					td = $(this);
					nome = td.html();
					td.html("<input type = 'text' id = 'nome' value = '" + nome + "' />");
					td.attr("class","nome_alterar");
					$("#nome").focus();
				});
				
				$(document).on("blur",".nome_alterar",function(){
					td = $(this);
					id_linha = $(this).closest("tr").find("button").val();
					$.ajax({
						url: "altera_inline.php",
						type: "post",
						data: {coluna: 'nome', valor: $("#nome").val(), id: id_linha, tabela: "cidade"},
						success: function(){
							nome = $("#nome").val();
							td.html(nome);
							td.attr("class","nome");
						}
					});
				});
				
				//REMOVER
				$(document).on("click", '.remover', function(){
					p = $(this).attr("valor");
					linha = $(this).closest("tr");
					
					$.ajax({
						url:"remover_cidade.php",
						type: "post",
						data: {id: p},

						success: function(data){
							if(data==1){
								$("#resultado").html("Cidade excluida...");
								
								linha.remove();
								
								qtd_linha = $("#tb_cidade tr").length;
								qtd_coluna = $("#tb_cidade td").length;
								
								if(qtd_linha==0 && qtd_coluna==0){
									linha="<tr><td colspan='13'>Não há cidades cadastradas</td></tr>";
									$('#tb_cidade').append(linha);
								}
							}else{
								$("#resultado").html("Cidade não pode ser excluido...");
								$("#resultado").css("color","red");					
							}
						},
						
						error: function(e){
							$("#resultado").html("Erro: sistema de remoção indisponível...");
							$("#resultado").css("color","red");
						}
					});
				});
			});
		
		</script>
		
	</head>
	
	<body>
		
		<h3>Cadastro de Cidades</h3>
		<?php
			include("conexao.php");
			include("menu.html");
		?>
		<form>
			
			<input type = "text" name = "nome" placeholder = "Nome..." /> <br /><br />
			<select name='cod_estado'>
				<?php	
				
				$consulta_estado = "SELECT * FROM estado ORDER BY nome";
				$resultado_estado = mysqli_query($conexao,$consulta_estado) or die ("ERRO");
				
					while($linha=mysqli_fetch_assoc($resultado_estado)){
						echo '<option value = "'. $linha["id_estado"] .'">'. $linha["nome"] .'</option>';
					}
				?>
			</select>
			
			<input type = "button" class = "cadastrar" value = "Cadastrar" />
			
		</form>
		
		<br />
		
		<div id = "resultado"></div>
		
		<br />
		
		<h3>Cidades</h3>
		
		<form name='fltro'>
			<input type ='text' name='nome_filtro' placeholder='filtrar por nome...' />
			
			<button type='button' id='filtrar'> Filtrar </button>
		</form>
		
		<table border = '1'>
						
			<thead>
				<tr>
					<th>Nome</th>
					<th>Código do Estado</th>
					<th>Ação</th>
				</tr>
			 </thead>
		
			<tbody id = 'identificador'></tbody>
					
		</table>
		<br /><br />
		
		<?php
			include("paginacao_cidade.php");
			
		?>
		
	</body>
	
</html>