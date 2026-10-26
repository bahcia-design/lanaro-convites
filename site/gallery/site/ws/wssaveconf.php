<?php
	/**
		Salva as configurações
	*/
	
	/*** Parâmetros *******************
	maxPicSize  = Tamanho maximo (megas) das imagens a subir
	maxPicWidth = Largura maxima das imagens a subir
	thumbWidth  = Tamanho padrão das miniaturas
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
			/* Obtem os dados */
			$maxPicSize  = postField("maxPicSize");
			$maxPicWidth = postField("maxPicWidth");
			$thumbWidth  = postField("thumbWidth");
			
			/* Aplica as configurações */
			applyNewConfig(
				$dbConnection, 
				array(
					"max_pic_file_size"=>$maxPicSize,
					"max_pic_width"=>$maxPicWidth,
					"max_pic_height"=>$maxPicWidth,
					"pic_thumb_width"=>$thumbWidth
				)
			);
			
			/* Verifica se deu erro */
			if ($dbConnection->lasterror == "") {
				$dbConnection->close();
				die(arrayPHP_JSON(array("feedback"=>"success")));
			} else {
				$dbConnection->close();
				returnError(ERR_020, FAILCHANGECONFIG); /* Erro na alteração da configuração */
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