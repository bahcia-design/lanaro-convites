<?php
	/**
		Apaga uma imagem do álbum e do disco
	*/
	
	/*** Parâmetros *******************
	idpic = ID da imagem a excluir
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
		$idpic   = postField("idpic");
		$idalbum = postField("idalbum");
		
		/* Dados do álbum */
		$albumData = getAlbumData($dbConnection, $idalbum);
		
		/* Busca o caminho da imagem */
		$picture = getPictureData($dbConnection, $idpic);
		
		/* Exclui Miniatura */
		$img = "../photos/thumb/".$picture["pic_filename"];
		if (file_exists($img))
			@unlink($img); /* Protegido com @ para não gerar exceção caso ocorra algum problema na deleção */
		/* Exclui Original */
		$img = "../photos/".$picture["pic_filename"];
		if (file_exists($img))
			@unlink($img); /* Protegido com @ para não gerar exceção caso ocorra algum problema na deleção */
		
		/* Exclui o registro do banco de dados */
		deletePictureRecord($dbConnection, $idpic);
		
		/* Verifica se precisa remover a foto de capa */
		if ($albumData["album_cover"]==$picture["pic_filename"])
			updateAlbumCover($dbConnection, $idalbum, "");

		/* Devolve o resultado */
		$dbConnection->close();
		die(arrayPHP_JSON(array("feedback"=>"success")));
	} else {
		$dbConnection->close();
		returnError(ERR_005, NOLOGIN); /* Erro de usuário não logado */
	}
?>