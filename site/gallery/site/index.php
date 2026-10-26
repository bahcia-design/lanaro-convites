<?php
	include "mainlib.php"; /* Funções gerais, chamar em todo .php de conteúdo que for criado */
	
	/* Realizar o check-list inicial em todos os acessos */
	if (initialCheckList()) {
		include "head.php"; /* Cabeçalho padrão */
		include "pglogin.php"; /* Página de Login */
		include "foot.php"; /* Rodapé padrão */	
	}
?>