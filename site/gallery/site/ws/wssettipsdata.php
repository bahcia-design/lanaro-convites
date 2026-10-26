<?php
	/**
		Altera os dados da página de Quem Somos
	*/
	
	/*** Parâmetros *******************
	edt_tips_title = Título da Página
	edt_tips_text  = Texto da Página
	img_tips       = Imagem da Página
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
			$edt_tips_title = trim(postField("edt_tips_title",""));
			$edt_tips_text  = trim(postField("edt_tips_text",""));
			$img_tips       = trim(postField("img_tips",""));

			/* Verifica se informou o título */
			if ($edt_tips_title!="") {
				/* Verifica se informou o texto */
				if ($edt_tips_text!="") {

					/* Procede com a alteração */
					$dbConnection->updateRecord(
						"gallery_tips",
						array("tips_title"=>substr($edt_tips_title,0,100),"tips_text"=>$edt_tips_text,"tips_image"=>$img_tips),
						array()
					);

					/* Verifica se não deu erro */
					if ($dbConnection->lasterror == "") {
						$dbConnection->close();
						die(arrayPHP_JSON(array("feedback"=>"success")));
					} else {
						$dbConnection->close();
						returnError(ERR_026, FAILSAVETIPS . ": " . $dbConnection->lasterror); /* Erro na alteração do nome */
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