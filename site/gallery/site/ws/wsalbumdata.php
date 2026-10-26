<?php
	/**
		Retorna nome e descrição do álbum
	*/
	
	/*** Parâmetros *******************
	idalbum = ID do álbum
	/**********************************/

	/*** Retornos *********************
	Se sucesso:
	feedback = "success",
	albumName = "nome do álbum";
	albumDesc = "Descrição do álbum";

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
		$idalbum   = postField("idalbum");
		
		/* Traz os dados do álbum */
		$album = getAlbumData($dbConnection, $idalbum);

		/* Devolve o resultado */
		$dbConnection->close();
		
		die(arrayPHP_JSON(array("feedback"=>"success","albumName"=>$album["album_name"],"albumDesc"=>$album["album_description"])));
	} else {
		$dbConnection->close();
		returnError(ERR_005, NOLOGIN); /* Erro de usuário não logado */
	}
?>