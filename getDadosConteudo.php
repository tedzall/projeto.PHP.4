<?php 
	require_once ('lib/functions.php');

	$conexao = conexaoDB();

	$dados['flag'] = false;

	if(isset($_REQUEST['cod'])){
		
		$str = "SELECT * FROM tbl_conteudo WHERE cod_conteudo = :cod ";
		$rst = $conexao->prepare($str);
		$rst->bindValue(":cod",$_REQUEST['cod']); 
		$rst->execute();
		$row = $rst->fetch(PDO::FETCH_ASSOC);
		
		$dados['titShort'] = utf8_encode($row['stit_menu_conteudo']);
		$dados['tit'] = utf8_encode($row['stit_conteudo']);
		$dados['conteudo'] = utf8_encode($row['mtexto_conteudo']);
		$dados['ativo'] = 1==$row['bativo_conteudo']?true:false;
		$dados['flag'] = true;
		
	}

	echo json_encode($dados);	
?>