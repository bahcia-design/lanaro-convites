<?php
	/**
		Apaga um álbum e todas as suas fotos
	*/
	
	/*** Parâmetros *******************
	confirmpass = senha de confirmação
	albumid     = id do album a apagar
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
		$confirmpass = postField("confirmpass");
		$albumid     = postField("albumid");
		
		/* Busca os dados do usuário logado */
		$loggedUser = getLoggedUserData($dbConnection);
		
		/* Checa a senha de confirmação */
		if (md5($confirmpass) == $loggedUser["user_pass"]) {
			/* Busca as fotos do álbum */
			$pictures = getAlbumPictures($dbConnection, $albumid);
			
			/* Varre as imagens para realizar a exclusão do arquivo físico */
			if (sizeof($pictures)) {
				foreach ($pictures as $picture) {
					/* Miniatura */
					$img = "../photos/thumb/".$picture["pic_filename"];
					if (file_exists($img))
						@unlink($img); /* Protegido com @ para não gerar exceção caso ocorra algum problema na deleção */
					/* Original */
					$img = "../photos/".$picture["pic_filename"];
					if (file_exists($img))
						@unlink($img); /* Protegido com @ para não gerar exceção caso ocorra algum problema na deleção */
				}
			}
			
			/* Exclui o registro do banco de dados */
			deleteAlbumRecord($dbConnection, $albumid);

			/* Devolve o resultado */
			$dbConnection->close();
			die(arrayPHP_JSON(array("feedback"=>"success")));
		} else {
			$dbConnection->close();
			returnError(ERR_003, INVALIDPASSWORD); /* Senha inválida */
		}
	} else {
		$dbConnection->close();
		returnError(ERR_005, NOLOGIN); /* Erro de usuário não logado */
	}
?>