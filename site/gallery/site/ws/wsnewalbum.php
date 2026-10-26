<?php
	/**
		Cadastra um novo album de fotos no sistema
	*/
	
	/*** Parâmetros *******************
	albumname = Nome do álbum
	albumdesc = Descrição do álbum
	albumid   = ID do álbum (somente se for editar ao invés de criar)
	/**********************************/
	
	/*** Retornos *********************
	Se sucesso:
	feedback = "success",
	albumid = <<id do album gerado>>
	
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
		/* Obtem os dados do novo álbum */
		$albumname = trim(postField("albumname",""));
		$albumdesc = trim(postField("albumdesc",""));
		$albumid   = trim(postField("albumid","0"));

		/* Verifica se informou o nome do álbum */
		if ($albumname!="") {
			/* Busca algum álbum com este nome */
			$idExists = albumAlreadyExists($dbConnection, $albumname, $albumid);
			/* Se ainda não tinha este nome, continua com a inclusão ou alteração */
			if (!$idExists) {
				if ($albumid == "0") {
					/* Faz a inclusão e obtém o ID de retorno */
					$newID = insertNewAlbum($dbConnection, $albumname, $albumdesc);
					if ($newID) {
						$dbConnection->close();
						die(arrayPHP_JSON(array("feedback"=>"success","albumid"=>$newID)));
					} else {
						$dbConnection->close();
						returnError(ERR_017, ALBUMINSERTFAILED); /* Inserção falhou */
					}
				} else {
					/* Faz a alteração dos dados do álbum */
					if (updateAlbumData($dbConnection, $albumid, $albumname, $albumdesc)) {
						$dbConnection->close();
						die(arrayPHP_JSON(array("feedback"=>"success","albumid"=>$albumid)));
					} else {
						$dbConnection->close();
						returnError(ERR_021, ALBUMEDITFAILED); /* Edição falhou */
					}
				}
			} else {
				$dbConnection->close();
				returnError(ERR_016, ALBUMNAMEEXISTS); /* Nome do álbum já existe */
			}
		} else {
			$dbConnection->close();
			returnError(ERR_015, ALBUMNAMENOTFOUND); /* Nome do álbum não informado */
		}
	} else {
		$dbConnection->close();
		returnError(ERR_005, NOLOGIN); /* Erro de usuário não logado */
	}
?>