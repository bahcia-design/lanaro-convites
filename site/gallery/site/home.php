<?php
	require_once "mainlib.php"; /* Funções gerais, chamar em todo .php de conteúdo que for criado */
	@session_start();
	/* Realizar o check-list inicial em todos os acessos */
	if (initialCheckList()) {
		/* Conexão com Banco de Dados */
		$dbConnection = new connMySQL(getDatabaseConfig());
		
		/* Verifica se usuário está logado */
		if (sessionExists($dbConnection)) {
			/* Se logado, apresenta a area inicial */
			$page="home";
			
			include "head.php"; /* Cabeçalho padrão */
			include "pghome.php";
			include "foot.php"; /* Rodapé padrão */	
		} else {
			/* Se não está logado, volta para página de login */
			/* Encerra conexão */
			$dbConnection->close();
			redirectTo("index.php");
		}
	}
?>