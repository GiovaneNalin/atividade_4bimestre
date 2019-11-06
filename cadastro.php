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
						url:"paginacao_cadastro.php",
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
							$("select[name='cod_cidade']").val(vetor.cod_cidade);
							$("input[name='salario']").val(vetor.salario);
							$(".cadastrar").attr("class","alteracao");
							$(".alteracao").val("Alterar Cadastro");
						}
					});
				});
				
				
				//PAGINACAO
				function paginacao(p) {
					$.ajax ({
						url: "carrega_cadastro.php",
						type: "post",
						data: {pg: p, nome_filtro: filtro},
						success: function(matriz){
							$("#identificador").html("");
							for(i=0;i<matriz.length;i++){
								linha = "<tr>";
								linha += "<td class = 'nome'>" + matriz[i].nome + "</td>";
								linha += "<td class = 'email'>" + matriz[i].email + "</td>";
								linha += "<td class = 'sexo'>" + matriz[i].sexo + "</td>";
								linha += "<td class = 'cod_cidade'>" + matriz[i].cod_cidade + "</td>";
								linha += "<td class = 'salario'>" + matriz[i].salario + "</td>";
								linha += "<td><button type = 'button' class = 'alterar' value ='" + matriz[i].id_cadastro + "'>Alterar</button> | <button type = 'button' class ='remover' value ='" + matriz[i].id_cadastro + "'>Remover</button></td>";
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
						url: "insere.php",
						type: "post",
						data: {nome:$("input[name='nome']").val(), 
								email:$("input[name='email']").val(), 
								sexo:$("input[name='sexo']:checked").val(), 								
								cod_cidade:$("select[name='cod_cidade']").val(),
								salario:$("input[name='salario']").val()},
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
							email:$("input[name='email']").val(), 
							sexo:$("input[name='sexo']:checked").val(),
							cod_cidade:$("select[name='cod_cidade']").val(),
							salario:$("input[name='salario']").val()},
						success: function(data){
							if(data==1){
								$("#resultado").html("Alteração efetuada!");
								paginacao(0);
								$("input[name='nome']").val("");
								$("input[name='email']").val("");
								$("input[name='sexo'][value='M']").attr("checked",false)
								$("input[name='sexo'][value='F']").attr("checked",false)
								$("select[name='cod_cidade']").val("");
								$("input[name='salario']").val("");
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
						data: {coluna: 'nome', valor: $("#nome").val(), id: id_linha, tabela: "cadastro"},
						success: function(){
							nome = $("#nome").val();
							td.html(nome);
							td.attr("class","nome");
						}
					});
				});
				
				//CIDADE
				
				$(document).on("click",".cidade",function(){
					td = $(this);
					nome = td.html();
					
					select = "<select id='cidade_alterar'>";
					select += $("select[name='cod_cidade']").html();
					select += "</select>";
					
					td.html(select);
					valor = $("option:contains('"+cidade+"')").val();
					$("#cod_cidade").val(valor);
					$("#cod_cidade").focus();
					td.attr("class", "cidade_alterar");					
				});
				
				$(document).on("blur",".cidade_alterar",function(){
					td = $(this);
					id_linha = $(this).closest("tr").find("button").val();
					$.ajax({
						url: "altera_inline.php",
						type: "post",
						data: {coluna: 'cod_cidade', valor: $("#cidade_alterar").val(),
						id: id_linha},
						success: function(r){
						cod_cidade = $("#cidade_alterar").val();
						cidade_estado = $("#cidade_alterar").find(
								"option[value='"+cod_cidade+"']").html();
								cidade_estado = cidade_estado.split("|");
								cidade = cidade_estado[0];
								estado = cidade_estado[1];
								td.closest("tr").find(".estado").html(estado);
								td.html(cidade);
								td.attr("class","cidade");
							)
						}
					});
				}
				
				//email
				$(document).on("click",".email",function(){
					td = $(this);
					email = td.html();
					td.html("<input type = 'text' id = 'email' value = '" + email + "' />");
					td.attr("class","email_alterar");
					$("#email").focus();
				});
				
				$(document).on("blur",".email_alterar",function(){
					td = $(this);
					id_linha = $(this).closest("tr").find("button").val();
					$.ajax({
						url: "altera_inline.php",
						type: "post",
						data: {coluna: 'email', valor: $("#email").val(), id: id_linha, tabela: "cadastro"},
						success: function(){
							email = $("#email").val();
							td.html(email);
							td.attr("class","email");
						}
					});
				});
				
				//sexo
				$(document).on("click",".sexo",function(){
					td = $(this);
					sexo = td.html();
					sexo_input = "<>"
								
				
				//salario
				$(document).on("click",".salario",function(){
					td = $(this);
					salario = td.html();
					td.html("<input type = 'text' id = 'salario' value = '" + salario + "' />");
					td.attr("class","salario_alterar");
					$("#salario").focus();
				});
				
				$(document).on("blur",".salario_alterar",function(){
					td = $(this);
					id_linha = $(this).closest("tr").find("button").val();
					$.ajax({
						url: "altera_inline.php",
						type: "post",
						data: {coluna: 'salario', valor: $("#salario").val(), id: id_linha, tabela: "cadastro"},
						success: function(){
							salario = $("#salario").val();
							td.html(salario);
							td.attr("class","salario");
						}
					});
				});
				
				//sexo
				$(document).on("click",".sexo",function(){
					td = $(this);
					sexo = td.html();
					sexo_input = "";//incompleto
					sexo_input += "";//incompleto
					td.html(sexo_input);
					if(sexo=='M'){
						$(".alterar_sexo[value='M']").prop("checked", true);
						$(".alterar_sexo[value='F']").prop("checked", false);
					}else{
						$(".alterar_sexo[value='F']").prop("checked", false);
						$(".alterar_sexo[value='M']").prop("checked", true);						
					}
					td.attr("class","sexo_alterar");
				});
				
				$(document).on("blur",".sexo_alterar",function(){
					td = $(this);
					id_linha = $(this).closest("tr").find("button").val();
					$.ajax({
						url: "altera_inline.php",
						type: "post",
						data: {coluna: 'sexo', valor: $(".alterar_sexo:checked").val(), id: id_linha},
						success: function(){
							sexo = $(".alterar_sexo").val();
							td.html(sexo);
							td.attr("class","sexo");
						}
					});
				});
				
				//REMOVER
				$(document).on("click", '.remover', function(){
					p = $(this).attr("valor");
					linha = $(this).closest("tr");
					
					$.ajax({
						url:"remover_cadastro.php",
						type: "post",
						data: {id: p},

						success: function(data){
							if(data==1){
								$("#resultado").html("Usuário excluido...");
								
								linha.remove();
								
								qtd_linha = $("#tb_cadastro tr").length;
								qtd_coluna = $("#tb_cadastro td").length;
								
								if(qtd_linha==0 && qtd_coluna==0){
									linha="<tr><td colspan='13'>Não há usuários cadastrados</td></tr>";
									$('#tb_cadastro').append(linha);
								}
							}else{
								$("#resultado").html("Usuário não pode ser excluido...");
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
		
		<h3>Cadastro de Pessoas</h3>
		<?php
			include("conexao.php");
			include("menu.html");
		?>
		<form>
			
			<input type = "text" name = "nome" placeholder = "Nome..." /> <br /><br />
			<input type = "email" name = "email" placeholder = "E-mail..." /><br /><br />
			Sexo: <br />
			M <input type = "radio" name = "sexo" value = "M" />
			F <input type = "radio" name = "sexo" value = "F" /><br /><br />
			<select name='cod_cidade'>
				<?php	
				
				$consulta_cidade = "SELECT * FROM cidade ORDER BY nome";
				$resultado_cidade = mysqli_query($conexao,$consulta_cidade) or die ("ERRO");
				
					while($linha=mysqli_fetch_assoc($resultado_cidade)){
						echo '<option value = "'. $linha["id_cidade"] .'">'. $linha["nome"].'</option>';
					}
				?>
			</select>
			<input type ="number" name="salario" placeholder="Digite o salário" min="0" step="0.01" />
			
			<input type = "button" class = "cadastrar" value = "Cadastrar" />
			
		</form>
		
		<br />
		
		<div id = "resultado"></div>
		
		<br />
		
		<h3>Cadastros</h3>
		
		<form name='fltro'>
			<input type ='text' name='nome_filtro' placeholder='filtrar por nome...' />
			
			<button type='button' id='filtrar'> Filtrar </button>
		</form>
		<br />
		<table border = '1'>
			<thead>
				<tr>
					<th>Nome</th>
					<th>E-mail</th>
					<th>Sexo</th>
					<th>Código da Cidade</th>
					<th>Salário</th>
					<th>Ação</th>
				</tr>
			 </thead>
		
			<tbody id = 'identificador'></tbody>
					
		</table>
		<br /><br />
		
		<div id='paginacao'>
		<?php
			include("paginacao_cadastro.php");
		?>
		</div>
		
	</body>
	
</html>