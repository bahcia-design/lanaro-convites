<?php
	/**
		Inclui ou altera um novo artigo
	*/
	
	/*** Parâmetros *******************
	edt_article_title = Título do Artigo
	edt_article_text  = Texto do Artigo
	img_article       = Imagem do Artigo
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
			/* Obtem os dados do Artigo */
			$article_id        = trim(postField("edt_article_id",""));
			$edt_article_title = trim(postField("edt_article_title",""));
			$edt_article_text  = trim(postField("edt_article_text",""));
			$img_article       = trim(postField("img_article",""));

			/* Verifica se informou o título */
			if ($edt_article_title!="") {
				/* Verifica se informou o texto */
				if ($edt_article_text!="") {

					/* Procede com a gravação: */
					$article_id = setArticle($dbConnection, $article_id, $edt_article_title, $edt_article_text, $img_article);

					/* Verifica se não deu erro */
					if ($article_id > 0) {
						$dbConnection->close();
						die(arrayPHP_JSON(array("feedback"=>"success","article_id"=>$article_id)));
					} else {
						$dbConnection->close();
						returnError(ERR_025, FAILSAVEARTICLE); /* Erro na alteração do nome */
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