<?php
	/**
		Altera a senha do usuário selecionado
	*/
	
	/*** Parâmetros *******************
	oldpass = Senha antiga
	newpass = Nova senha
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
			$oldpass  = trim(postField("oldpass",""));
			$newpass  = trim(postField("newpass",""));
			$usertype = postField("usertype","");

			/* Verifica se informou a antiga senha */
			if (($usertype == USER_EDITOR) || ($oldpass!="")) {
				/* Verifica se informou a nova senha */
				if ($newpass!="") {
					/* Verifica se o tipo de usuário é válido */
					if (($usertype == USER_ADMIN) || ($usertype == USER_EDITOR)) {
							/* Busca os dados do usuário */
							$userData = getUserDataByType($dbConnection, $usertype);
							
							/* Verifica se a senha antiga está correta (apenas se Administrador) */
							if (($usertype == USER_EDITOR) || ($userData["user_pass"] == md5($oldpass))) {
								/* Busca os dados do outro usuário para comparar as senhas */
								/* Neste sistema de dois usuários fixos, as senhas não podem ser iguais */
								$anotherUserType = ($usertype == USER_ADMIN) ? USER_EDITOR : USER_ADMIN;
								$anotherUser = getUserDataByType($dbConnection, $anotherUserType);
								
								/* Compara a senha dos dois usuários */
								if ($anotherUser["user_pass"] != md5($newpass)) {
									/* Procede com a alteração */
									$dbConnection->updateRecord(
										"gallery_users",
										array("user_pass"=>md5($newpass)),
										array("user_admin=".$usertype)
									);

									/* Verifica se não deu erro */
									if ($dbConnection->lasterror == "") {
										$dbConnection->close();
										die(arrayPHP_JSON(array("feedback"=>"success")));
									} else {
										$dbConnection->close();
										returnError(ERR_009, UPDATEPASSERROR); /* Erro na alteração da senha */
									}
								} else {
									$dbConnection->close();
									returnError(ERR_014, EQUALPASSWORDS); /* Senhas não podem ser iguais */
								}
							} else {
								$dbConnection->close();
								returnError(ERR_012, INVALIDOLDPASS); /* Senha atual inválida */
							}
					} else {
						$dbConnection->close();
						returnError(ERR_008, INVALIDUSERTYPE); /* Tipo de usuário é inválido */
					}
				} else {
					$dbConnection->close();
					returnError(ERR_011, NEWPASSNOTFOUND); /* Nova senha não informada */
				}
			} else {
				$dbConnection->close();
				returnError(ERR_010, OLDPASSNOTFOUND); /* Senha antiga não informada */
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