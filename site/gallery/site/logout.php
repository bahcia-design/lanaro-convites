<?php
	/**
		Efetua o logout do usuário
		
		Nota: nesta página não executamos a função initialCheckList() pois considera-se que esta
		é uma página que só é acionada quando o sistema já está funcinando corretamente.
	*/
	
	require_once "./mainlib.php";
	
	@session_start();
	
	/* Conecta-se ao banco de dados */
	$dbConnection = new connMySQL(getDatabaseConfig());
	
	/* Primeiro, busca os dados do usuário logado */
	$user = getLoggedUserData($dbConnection);
	
	/* Se houve algum erro, joga para a página de informações */
	if ($user["error"]!="") {
		/* Encerra a conexão do banco de dados */
		$dbConnection->close();
		
		redirectTo("faillogout.php?error=" . $user["error"]);
	} else {
		/* Apaga a sessão do usuário e volta a página de login */
		if (($user["user_id"]=="") || (logoutUserById($user["user_id"], $dbConnection))) {
			/* Encerra a conexão do banco de dados */
			$dbConnection->close();

			redirectTo("index.php");
		} else {
			/* Encerra a conexão do banco de dados */
			$dbConnection->close();

			/* Se não conseguir efetuar o logout, também ativa a página de erro pertinente */
			redirectTo("faillogout.php?error=Logout não efetuado");
		}
	}

?>