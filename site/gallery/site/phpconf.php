<?php
	@session_start();
	include "mainlib.php";
	
	/* Realizar o check-list inicial em todos os acessos */
	if (initialCheckList()) {
		/* Se logado, apresenta a area inicial */
		include "head.php"; /* Cabeçalho padrão */
		include "pgconfigphp.php";
		include "foot.php"; /* Rodapé padrão */	
	}
?>