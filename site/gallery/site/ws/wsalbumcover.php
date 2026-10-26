<?php
	/**
		Define a capa de um álbum
	*/
	
	/*** Parâmetros *******************
	filecover = Caminho da capa
	idalbum = ID do álbum
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
		/* Obtém os dados passados */
		$filecover = postField("filecover");
		$idalbum   = postField("idalbum");
		
		/* Atualiza a capa no banco de dados */
		updateAlbumCover($dbConnection, $idalbum, $filecover);

		/* Devolve o resultado */
		$dbConnection->close();
		die(arrayPHP_JSON(array("feedback"=>"success")));
	} else {
		$dbConnection->close();
		returnError(ERR_005, NOLOGIN); /* Erro de usuário não logado */
	}
?>