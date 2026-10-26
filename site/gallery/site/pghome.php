<?php
	require_once "mainlib.php"; /* Funções gerais, chamar em todo .php de conteúdo que for criado */

	/* Conexão com Banco de Dados */
	$dbConnection = new connMySQL(getDatabaseConfig());
	/* Dados do usuário logado */
	$userData = getLoggedUserData($dbConnection);
	/* Encerra conexão */
	$dbConnection->close();

	/* Se achou o usuário continua: */
	if ($userData["user_name"]!="") {
		/* Prepara a home do administrador */
		if ($userData["user_admin"]==USER_ADMIN) {
			titleInfo("Bem vindo ".sessionField("username")."!","Escolha uma opção para continuar:");
			administratorIcons();
		} else if ($userData["user_admin"]==USER_EDITOR) {
			/* Prepara a home do operador (direto às galerias) */
			include "pggallery.php";
		}	
	} else {
		/* Informa que não encontrou o usuário */
		titleInfo("Ops!","Aparentemente não identificamos seu login, desculpe o transtorno. <a href='./index.php'>Clique aqui</a> para entrar novamente.");
	}
?>