<?php 
	session_start();

	require_once ('lib/functions.php');
	require_once ('lib/bycript.php');

	unset($_SESSION["nomeUsr"]);
	unset($_SESSION["emailUsr"]);

	$conexao = conexaoDB();

	$pagina = "ger.php";

	if (isset($_COOKIE["idUsr"]) && isset($_COOKIE["marcaUsr"])){
    	if ($_COOKIE["idUsr"]!="" || $_COOKIE["marcaUsr"]!=""){
       		$marca = base64_decode($_COOKIE["marcaUsr"]);	
       		$str = "SELECT * FROM tbl_usuario WHERE cod_usr = :idUsr AND scookie_usr = :marca AND scookie_usr <> '' AND bativo_usr = 1 ";
			$rst = $conexao->prepare($str);
			$rst->bindValue(":idUsr",$_COOKIE["idUsr"]); 
			$rst->bindValue(":marca",$marca); 
			$rst->execute();
			
			if(1==$rst->rowCount()){			

				$row = $rst->fetch(PDO::FETCH_ASSOC);

				$_SESSION["nomeUsr"] = $row['snome_usr'];
				$_SESSION["emailUsr"] = $row['semail_usr'];

				header ("Location: $pagina");
       		}
   	 	}
 	}

	$error = false;

	if (isset($_POST['flagSubmit'])){

		$valid = false;


       	$str = " SELECT * FROM tbl_usuario WHERE semail_usr = :email ";
		$rst = $conexao->prepare($str);
		$rst->bindValue(":email",$_POST['email']); 
		$rst->execute();
       	       	
		if(1==$rst->rowCount()){
						
			$row = $rst->fetch(PDO::FETCH_ASSOC);

			if (Bcrypt::check($_POST['senha'], $row['ssenha_usr'])) {
				if(1==$row['bativo_usr']){

                	$valid = true;
                	
                	$cod_usr = $row['cod_usr'];

                	$_SESSION['nome'] = $row['snome_usr']; 
   		        	$_SESSION['email'] = $row['semail_usr'];

					$error = "Login efetuado com sucesso !!!"; 
				}else{
					$error = "Acesso bloqueado !!!";
				}
           	}else{
               	$error = "Senha incorreta !!!";
			}	
		}else{
       		$error = 'Email incorreto !!!';
		}
        
		$ajax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

   		if ($valid){
			if (isset($_POST['flagLogged']) and $_POST['flagLogged'] == 1){
				$numero_aleatorio = mt_rand(1000000,999999999);
 				$str = "UPDATE tbl_usuario SET scookie_usr = :scookie_usr WHERE cod_usr = :cod_usr ";
				$rst = $conexao->prepare($str);
				$rst->bindValue(":scookie_usr",$numero_aleatorio); 
				$rst->bindValue(":cod_usr",$cod_usr); 

				setcookie("idUsr", $cod_usr, time()+(60*60*24*365));
       			setcookie("marcaUsr", base64_encode($numero_aleatorio), time()+(60*60*24*365));
			}
			if ($ajax){
				header('Cache-Control: no-cache, must-revalidate');
				header('Expires: '.date('r', time()+(86400*365)));
				header('Content-type: application/json');
				echo json_encode(array(
					'valid' => true,
					'redirect' => $pagina
				));
				exit();
			}else{
				header('Location: '.$pagina);
				exit();
			}
		}else{
			if ($ajax){
				header('Cache-Control: no-cache, must-revalidate');
				header('Expires: '.date('r', time()+(86400*365)));
				header('Content-type: application/json');
				echo json_encode(array(
					'valid' => false,
					'error' => $error
				));
				exit();
			}
        }
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

    	<title>Projeto PHP 4</title>

    	<link href="css/bootstrap.min.css" rel="stylesheet">
    	<link href="css/standard.css" rel="stylesheet">

	    <script src="js/jquery.js"></script>
   	 	<script src="js/bootstrap.min.js"></script>

		<script>
			
		$(document).ready(function()
		{
			$('.form-signin').submit(function(event){

				event.preventDefault();
				
				var email = $('#email').val();
				var senha = $('#senha').val();
				
				var data = {
						flagSubmit: 'send',
						email: email,
						senha: senha,
						flagLogged: $('#flagLogged').attr('checked') ? 1 : 0
				}

				$('#message').removeClass('hidden').addClass('bg-info').text('Aguarde !!! Processando ...');


				$.ajax({
						url: 'index.php',
						dataType: 'json',
						type: 'POST',
						data: data,
						success: function(data, textStatus, XMLHttpRequest){
							if (data.valid){
								document.location.href = data.redirect;
							}else{
								$('#message').removeClass('bg-info').addClass('bg-danger').text(data.error);
							}	
						},
						error: function(XMLHttpRequest, textStatus, errorThrown){
							$('#message').removeClass('bg-info').addClass('bg-danger').text('Sistema instável !!! Tente novamente.');
						}
				});
					
			});
			
		});			
		</script>

  	</head>

	<body>
	    <div class="container">
     		<form class="form-signin">
        		<h2 class="form-signin-heading">Acesso Gerenciador</h2>
        		<p class="hidden" id="message"></p>
		        <label for="email" class="sr-only">Email</label>
    		    <input type="email" id="email" class="form-control" placeholder="Email" required autofocus>
        		<label for="senha" class="sr-only">Senha</label>
        		<input type="password" id="senha" class="form-control" placeholder="Senha" required>
        		<div class="checkbox"><label><input type="checkbox" id="flagLogged" value="1"> Mantenha-me conectado</label></div>
        		<button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
		      </form>
	    </div>
	</body>
</html>