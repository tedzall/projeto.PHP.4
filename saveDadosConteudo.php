<?php 
	require_once ('lib/functions.php');

	$conexao = conexaoDB();

	$cod = $_REQUEST['cod'];
	$tit = utf8_decode($_REQUEST['tit']);
	$titShort = utf8_decode($_REQUEST['titShort']);
	$conteudo = utf8_decode($_REQUEST['conteudo']);
	$ativo = $_REQUEST['ativo'];

	$str = "UPDATE tbl_conteudo SET stit_menu_conteudo = :titShort, stit_conteudo = :tit, 
	mtexto_conteudo = :conteudo, bativo_conteudo = :ativo WHERE cod_conteudo = :cod ";
	$rst = $conexao->prepare($str);
	$rst->bindValue(":cod",$cod); 
	$rst->bindValue(":titShort",$titShort); 
	$rst->bindValue(":tit",$tit); 
	$rst->bindValue(":conteudo",$conteudo); 
	$rst->bindValue(":ativo",$ativo); 
		
	$dados['flag'] = $rst->execute();
		
	echo json_encode($dados);	
?>