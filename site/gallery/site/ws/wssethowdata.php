<?php
	/**
		Altera os dados da página de Quem Somos
	*/
	
	/*** Parâmetros *******************
	edt_how_title = Título da Página
	edt_how_text  = Texto da Página
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
			/* Obtem os novos dados da página */
			$edt_how_title = trim(postField("edt_how_title",""));
			$edt_how_text  = trim(postField("edt_how_text",""));

			/* Verifica se informou o título */
			if ($edt_how_title!="") {
				/* Verifica se informou o texto */
				if ($edt_how_text!="") {

					/* Procede com a alteração */
					$dbConnection->updateRecord(
						"gallery_how",
						array("how_title"=>substr($edt_how_title,0,100),"how_text"=>$edt_how_text),
						array()
					);

					/* Verifica se não deu erro */
					if ($dbConnection->lasterror == "") {
						$dbConnection->close();
						die(arrayPHP_JSON(array("feedback"=>"success")));
					} else {
						$dbConnection->close();
						returnError(ERR_027, FAILSAVEHOW . ": " . $dbConnection->lasterror); /* Erro na alteração do nome */
					}

				} else {
					$dbConnection->close();
					returnError(ERR_023, TEXTNOTFOUND); /* Título não informado */
				}
			} else {
				$dbConnection->close();
				returnError(ERR_022, TITLENOTFOUND); /* Título não informado */
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