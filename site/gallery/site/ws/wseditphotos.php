<?php
	/**
		Edita o titulo de fotos selecionadas
	*/

	/*** Parâmetros *******************
	total     = total de imagens a editar
	idpic_N   = id da foto
	namepic_N = nome da foto
	descpic_N = descritivo da foto (mini currículo)
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
		/* Obtem o total de imagens a editar */
		$total = postField("total");
		if ($total > 0) {
			/* Inicia a edição das fotos */
			for ($i=0;$i<$total;$i++) {
				$idpic = postField("idpic_".$i);
				$namepic = postField("namepic_".$i);
				$descpic = postField("descpic_".$i);
				$specsel = postField("specpic_".$i);
				$article = postField("article_".$i);

				if ($idpic > 0) {
					if (!updatePictureName($dbConnection, $idpic, $namepic, $descpic, $article)) {
						$dbConnection->close();
						returnError(ERR_019, FAILPHOTOEDIT); /* Erro de edição da foto */
						exit;
					}

					if ($specsel!="") {
						$specsel  = str_replace(")(",",",$specsel);
						$specsel  = str_replace("(","",$specsel);
						$specsel  = str_replace(")","",$specsel);

						$idSpecialists = explode(",",$specsel);
						if (sizeof($idSpecialists)) {
							setSpecialistsToItem($dbConnection, $idpic, $idSpecialists);
						}
					}
				}
			}

			$dbConnection->close();
			die(arrayPHP_JSON(array("feedback"=>"success")));
		} else {
			$dbConnection->close();
			returnError(ERR_018, NOPICTURESTOEDIT); /* Erro de não ter fotos a editar */
		}
	} else {
		$dbConnection->close();
		returnError(ERR_005, NOLOGIN); /* Erro de usuário não logado */
	}
?>