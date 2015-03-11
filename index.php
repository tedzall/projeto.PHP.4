<?php 
	require_once ('lib/functions.php');

	$conexao = conexaoDB();

	$sql = "SELECT cod_conteudo AS COD, stit_menu_conteudo AS TIT FROM tbl_conteudo WHERE bativo_conteudo = 1 ";
	$rst = $conexao->prepare($sql);
	$rst->execute();
	$row = $rst->fetchALL(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

    	<title>Projeto PHP 4</title>

    	<link href="css/bootstrap.min.css" rel="stylesheet">
    	<link href="css/bootstrap-theme.min.css" rel="stylesheet">
    	<link href="css/standard.css" rel="stylesheet">

	    <script src="js/jquery.js"></script>
   	 	<script src="js/bootstrap.min.js"></script>

		<script>
			$(document).ready(function() {

				$(this).on("click",".btn",function(){
					var nome = $("#nome").val();
					var email = $("#email").val();
					var assunto = $("#assunto").val();
					var mensagem = $("#mensagem").val();
					var html = "<h3>Seus dados foram enviados com sucesso !!!</h3><p>Seque abaixo os dados que você enviou:</p><ul><li>Nome: <b>"+nome+"</b></li><li>Email: <b>"+email+"</b></li><li>Assunto: <b>"+assunto+"</b></li><li>Mensagem: <br/><b>"+mensagem+"</b></li></ul>";
					$("#msg-form").removeClass("hidden").html(html);
					setTimeout(clearAllForm,8000);
				});
			
				$(this).on("click",".menu-bar li, .result-conteudo",function(event){
					if('login.php'!=$(this).find('a').attr('href')){
						event.preventDefault();
						var codConteudo = $(this).find('a').attr('href').replace( /#/g, "" );
						var pagina = 0==codConteudo?'contato.php':'conteudo.php';
						var id = $(this).index();
						loadConteudo(codConteudo,pagina,id);
					}
				});
			
				$(this).on("click", ".searchBt", function(event){
					event.preventDefault();
					var palavra = $('#search').val();	

					$('#conteudo').load('search.php', {palavra:palavra},function( response, status, xhr ) {
						if ( status == "error" ) {
   	 					var msg = "Ops !!! Não foi possível efetuar sua pesquisa neste momento. Tente novamente.";
   	 						$( "#conteudo" ).html( msg + xhr.status + " " + xhr.statusText );
  						}
					});
				
				});
			
				loadConteudo(1,'conteudo.php',0);
			});
			
			function loadConteudo(cod,pag,id){
				$('#conteudo').load(pag, {codConteudo:cod},function( response, status, xhr ) {
					if ( status == "error" ) {
    					var msg = "Ops !!! Não foi possível carregar o conteúdo neste momento. Tente novamente.";
    						$( "#conteudo" ).html( msg + xhr.status + " " + xhr.statusText );
  					}
  					$('.menu-bar li').removeClass('active');
  					$('.menu-bar li:eq('+id+')').addClass('active');
				});
			}
			
			function clearAllForm(){
				$("#msg-form").addClass("hidden");
				$("#nome").val('');
				$("#email").val('');
				$("#assunto").val('');
				$("#mensagem").val('');
			}
		</script>

  	</head>

	<body>

		<!-- Header -->
  		<?php require_once('header.php') ?>
		<!-- end.Header -->	

		<!-- Miolo -->
		<div id="miolo"> 

			<!-- Menu -->
			<div id="menu">
        		<ul class="menu-bar">
<?php
	foreach($row as $data) {
		echo '<li class="'.(1==$data['COD']?'active':'').'"><a href="#'.$data['COD'].'">'.ucfirst(utf8_encode($data['TIT'])).'</a></li>';
}
?>        			
					<li><a href="#0">Contato</a></li>';
					<li><a href="login.php" title="Gerenciador de Conteúdo" target="_blank">Admin</a></li>';
            	</ul>
            	
				<form class="form-pesquisa">
					<div class="input-append">
						<input class="span2" id="search" type="text" placeholder="Pesquisa...">
						<button class="btn btn-mini searchBt" type="button">OK</button>
					</div>
				</form>
    	    </div>
			<!-- end.Menu -->

			<!-- Conteudo -->
	        <div id="conteudo"></div>
			<!-- end.Conteudo -->

    	</div> 
    	<!-- end.Miolo -->

		<!-- Footer -->
		<?php require_once('footer.php') ?>
		<!-- end.Footer -->	

	</body>
</html>