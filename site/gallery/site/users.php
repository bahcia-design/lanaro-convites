<?php
	@session_start();
	include "mainlib.php";
	
	/* Realizar o check-list inicial em todos os acessos */
	if (initialCheckList()) {
		/* Conexão com Banco de Dados */
		$dbConnection = new connMySQL(getDatabaseConfig());
		
		/* Verifica se usuário está logado */
		if (sessionExists($dbConnection)) {
			/* Se logado, apresenta a area inicial */
			include "head.php"; /* Cabeçalho padrão */
			include "pgusers.php";
			include "foot.php"; /* Rodapé padrão */	
		} else {
			/* Se não está logado, volta para página de login */
			/* Encerra conexão */
			$dbConnection->close();
			redirectTo("index.php");
		}
	}
?>