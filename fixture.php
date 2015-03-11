<?php
	require_once ('lib/functions.php');
	require_once ('lib/bycript.php');

	$conexao = conexaoDB();
	
	$str = "DROP TABLE IF EXISTS tbl_user";
	$conexao->query($str);

	$str = "CREATE TABLE tbl_user (
  		cod_usr int(3) NOT NULL AUTO_INCREMENT,
		semail_usr varchar(150) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  		snome_usr char(30) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
		scookie_usr char(30) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
 		ssenha_usr varchar(60) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
 		bativo_usr smallint(1) NOT NULL DEFAULT '1', 
 		PRIMARY KEY (cod_usr),
 		UNIQUE KEY email (semail_usr)) 
 		ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci
 		";
	$conexao->query($str);
	
	$senha = ('123SenhaProvisoria');
	$hash = Bcrypt::hash($senha, 12);	

	$str = "INSERT INTO tbl_user (semail_usr, snome_usr, scookie_usr, ssenha_usr, bativo_usr) VALUES 
	('dzbola@bol.com.br','Eduardo','','$hash',1)
	";
	$conexao->query($str);
?>