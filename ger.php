<?php 
	session_start();
	
	require_once ('lib/functions.php');

	if(!isset($_SESSION['emailUsr']) ){
  		header ("Location: login.php");
    }

	$conexao = conexaoDB();

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
		<script src="js/ckeditor/ckeditor.js"></script>
		<script src="js/ckeditor/adapters/jquery.js"></script>

		<script>
			
		$(document).ready(function()
		{

			$('.nav li').on('click',function(){
				var cod = $(this).attr('id');
				loadConteudo(cod);
			});

			$('.btn').on('click', function(){

				var titShort = $("#titShort").val();
				var tit = $("#tit").val();
				var ativo = $("#ativo").is(':checked')?1:0;
				var conteudo = CKEDITOR.instances.conteudo.getData();
				var cod = $("#cod_conteudo").val();
				var msg = "<p>Não foi possível salvar o conteúdo !!!</p>";
				var css = "bg-danger";

				$('html, body').animate({scrollTop:0}, 'slow');
				
				$("#message").removeClass("hidden").addClass("bg-priority").html("<p>Aguarde ... Salvando dados !!!");

				$.getJSON('saveDadosConteudo.php',{cod:cod, tit:tit, ativo:ativo, conteudo:conteudo, titShort:titShort}).done(function(data){
					if(data.flag){
						msg = "<p>Conteúdo salvo com sucesso !!!</p>";
						css = "bg-success";
					}
					$("#message").removeClass("hidden").addClass(css).html(msg)
				}).fail(function(jqXHR, textStatus, errorThrown) {
					$("#message").removeClass("hidden").addClass(css).html(msg);
				});	
				setTimeout('$("#message").addClass("hidden")',5000);
			});

			CKEDITOR.replace("conteudo");	

			loadConteudo(1);

		});			
		
		function loadConteudo(cod){

			$.getJSON('getDadosConteudo.php',{cod: cod}).done(function(data){

				$('html, body').animate({scrollTop:0}, 'slow');

				if(data.flag){
					$("#titShort").val(data.titShort);
					$("#tit").val(data.tit);
					$("#ativo").attr("checked",data.ativo);
					$("#conteudo").val(data.conteudo);
					CKEDITOR.instances.conteudo.setData(data.conteudo);
					$("#cod_conteudo").val(cod);
					$(".jumbotron").find('h1').text(data.titShort + ' - Conteúdo');
					$(".nav>li").removeClass('active');
					$(".nav li#"+cod).addClass('active');									
				}else{
					$("#message").addClass("bg-danger").text("Não foi possível carregar o conteúdo !!!");
					$(".btn").attr('disabled','disabled');
				}				
			}).fail(function(jqXHR, textStatus, errorThrown) {
				$("#message").addClass("bg-danger").text("Não foi possível carregar o conteúdo !!!");
				$(".btn").attr('disabled','disabled');
			});	
			
		}
		</script>

  	</head>

	<body>
	    <div class="container">
      		<nav class="navbar navbar-default">
        		<div class="container-fluid">
          			<div class="navbar-header"><span class="navbar-brand">Gerenciamento de Conteúdo</span></div>
          			<div id="navbar" class="navbar-collapse collapse">
            			<ul class="nav navbar-nav">
<?php
	$str = "SELECT cod_conteudo AS COD, stit_menu_conteudo AS TIT FROM tbl_conteudo ";
	$rst = $conexao->prepare($str);
	$rst->execute();
	$row = $rst->fetchALL(PDO::FETCH_ASSOC);
	foreach($row as $data) {
		echo '<li class="'.(1==$data['COD']?'active':'').'" id="'.$data['COD'].'"><a href="#">'.strtoupper((utf8_encode($data['TIT']))).'</a></li>';
	}
?>
           	 			</ul>
          			</div>
        		</div>
      		</nav>

			<div class="hidden" id="message"></div>

      		<div class="jumbotron">
	        	<h1>Home - Conteúdo</h1>
	        	<form>	
		        	<div class="row">	
						<div class="col-xs-2">
		        			<label class="control-label">Título Menu</label>
							<input type="text" name="titShort" id="titShort" value="" class="form-control">
						</div>		        		
						<div class="col-xs-4">
		        			<label class="control-label">Título Conteúdo</label>
							<input type="text" name="tit" id="tit" value="" class="form-control">
						</div>		        		
		        	</div>	

					<div class="checkbox">
	        			<label><input type="checkbox" name="ativo" id="ativo" value="1"> Conteúdo Ativo</label>
					</div>

					
					<div class="form-group">
						<textarea id="conteudo" name="conteudo" rows="5" class="form-control"></textarea>
					</div>

	        		<button type="button" class="btn btn-primary btn-lg">SALVAR ALTERAÇÕES</button>

					<input type="hidden" id="cod_conteudo" value="">
	        	</form>
      		</div>	    	
		</div>	    	
	</body>
</html>