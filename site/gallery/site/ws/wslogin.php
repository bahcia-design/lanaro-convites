<?php
	/**
		Efetua o login do usuário
	*/
	
	/*** Parâmetros *******************
	ps = Senha do usuário
	/**********************************/
	
	/*** Retornos *********************
	Se sucesso:
	feedback = "success",
	usersession = "<<sessão criada>>",
	username = "<<nome do usuário>>";
	useradmin = "1 = admin, 0 = não admin"
	
	Se falha:
	feedback = "fail";
	errorcode = "<<codigo do erro>>";
	errorname = "<<descrição do erro>>";
	/**********************************/
	
	require_once "../mainlib.php";
	
	@session_start();
	
	header('Content-Type: text/javascript; charset=utf-8');
	
	/* Obtem a senha informada */
	$password = postField("ps","");
	
	/**** Algumas validações da senha *****/
	if ($password=="") {
		/* Retorna o erro de senha não informada */
		returnError(ERR_001, NOPASSWORD);
	} else {
		/* Configuração do Database */
		$databaseConfig = getDatabaseConfig();
		/* Conecta-se ao banco de dados */
		$connection = new connMySQL($databaseConfig);
		/* Se não conectar retorna o erro */
		if ($connection->fail) {
			returnError(ERR_002, NODBCONNECTION);
		} else {
			/* Retorna os dados de um usuário via senha */
			$userData = getUserDataByPass($password, $connection);
			/* Verifica se senha é incorreta (a igualdade de tipos === é importante) */
			if ($userData===FALSE) {
				/* Encerra a conexão antes de retornar o erro */
				$connection->close();
				returnError(ERR_003, INVALIDPASSWORD);
			} else {
				/* Se acertou a senha, cria uma única sessão para o usuário */
				$maskSession = md5($userData["user_id"] . date('h-i-s u, j-m-y, w '));
				/* Tenta aplicar a nova sessão ao usuário para efetuar o login */
				if (setUserSession($userData["user_id"], $maskSession, $connection)) {
					/* Gera o retorno de login com sucesso */
					$success = array(
						"feedback"=>"success",
						"usersession"=>$maskSession,
						"username"=>$userData["user_name"],
						"useradmin"=>$userData["user_admin"]
					);			
					/* Grava a sessão na memória */
					$_SESSION["usersession"] = $maskSession;
					$_SESSION["username"]    = $userData["user_name"];
					$_SESSION["useradmin"]   = $userData["user_admin"];
					/* Encerra a conexão */
					$connection->close();
					die(arrayPHP_JSON($success));
				} else {
					/* Encerra a conexão */
					$connection->close();
					returnError(ERR_004, UPDATESESSION);
				}
			}
		}
	}
?>