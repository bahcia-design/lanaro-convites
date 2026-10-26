<?php
	/**
		Altera o nome de um usuário selecionado
	*/
	
	/*** Parâmetros *******************
	newname = Novo nome do usuário
	usertype = Tipo do usuário (admin ou editor)
	/**********************************/
	
	/*** Retornos *********************
	Se sucesso:
	feedback = "success",
	
	Se falha:
	feedback = "fail";
	errorcode = "<<codigo do erro>>";
	errorname = "<<descrição do erro>>";
	/**********************************/

	@session_start();
	include "../mainlib.php";
	
	header('Content-Type: text/javascript; charset=utf-8');
	
	/* Conexão com Banco de Dados */
	$dbConnection = new connMySQL(getDatabaseConfig());
	
	/* Verifica se usuário está logado */
	if (sessionExists($dbConnection)) {
		/* Verifica se é usuário administrador */
		$loggedUser = getLoggedUserData($dbConnection);
		if ($loggedUser["user_admin"]==USER_ADMIN) {
			/* Obtem os dados do novo nome de usuário */
			$newname  = trim(postField("newname",""));
			$usertype = postField("usertype","");
			
			/* Verifica se informou o nome */
			if ($newname!="") {
				/* Verifica se o tipo de usuário é válido */
				if (($usertype == USER_ADMIN) || ($usertype == USER_EDITOR)) {
					/* Procede com a alteração */
					$dbConnection->updateRecord(
						"gallery_users",
						array("user_name"=>substr($newname,0,50)),
						array("user_admin=".$usertype)
					);

					/* Verifica se não deu erro */
					if ($dbConnection->lasterror == "") {
						$dbConnection->close();
						$_SESSION["username"] = substr($newname,0,50);
						die(arrayPHP_JSON(array("feedback"=>"success")));
					} else {
						$dbConnection->close();
						returnError(ERR_009, UPDATENAMEERROR); /* Erro na alteração do nome */
					}
				} else {
					$dbConnection->close();
					returnError(ERR_008, INVALIDUSERTYPE); /* Tipo de usuário é inválido */
				}
			} else {
				$dbConnection->close();
				returnError(ERR_007, NAMENOTFOUND); /* Nome não informado */
			}
		} else {
			$dbConnection->close();
			returnError(ERR_006, NOADMINPERMISSION); /* Erro de usuário não logado */
		}
	} else {
		$dbConnection->close();
		returnError(ERR_005, NOLOGIN); /* Erro de usuário não logado */
	}
?>