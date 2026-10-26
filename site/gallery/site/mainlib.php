<?php
	require_once "dbconf.php"; /* Config do Banco de Dados */
	require_once "connmysql.php"; /* Biblioteca de comunicação com o banco de dados */
	require_once "errorcodes.php"; /* Dicionário de erros conhecidos */
	
	/* Algumas constantes necessárias */
	define("USER_ADMIN","1"); 
	define("USER_EDITOR","0");
	
	define("SPECIALISTS","Especialistas");
	define("AESTHETICS","Estética");
	define("ORTHODONTICS","Ortodontia");
	
	define("WITHOUT_TEXT",false);
	define("WITH_TEXT",true);
	
	$page=""; /* Para controle de navegação */
	
	/* Retorna um botão bootstrap */
	function actionButton($caption, $click, $style, $icon) {
		return "<button type=\"button\" class=\"btn btn-$style\" onclick=\"$click\"><span class=\"glyphicon glyphicon-$icon\"></span> $caption</button>";
	}
	
	/* Imprime uma sequencia de botões */
	function actionButtons($buttons) {
		if (sizeof($buttons)) {
			echo "<div class=\"row\">";
			foreach ($buttons as $button) {
				echo "<div class=\"col-lg-3 col-md-4 col-xs-6 thumb\">".$button."</div>";
			}
			echo "</div>";
		}
	}
	
	/* Gerar os ícones do administrador */
	function administratorIcons() {
		echo "<div class='row'>";
		iconOption("Álbuns", "gallery.php", "albuns1", "3");
		iconOption("Quem Somos", "about.php", "about1", "3");
		iconOption("Dicas", "tips.php", "tips1", "3");
		iconOption("Frases", "frases.php", "frases1", "3");
		echo "</div>";
		echo "<div class='row'>";
		iconOption("Como encomendar", "encom.php", "shop1", "3");
		iconOption("Parceiros", "album.php?albumname=Parceiros", "parceiros1", "3");
		iconOption("Configurações", "config.php", "config1", "3");
		iconOption("Usuários", "users.php", "users1", "3");
		echo "</div>";
	}
	
	/* Verifica se um álbum já existe pelo nome */
	function albumAlreadyExists($dbConnection, $albumName, $exceptionID=0) {
		return (searchTableID($dbConnection,
			                 "gallery_albuns",
							 array("album_name='$albumName'","album_id<>$exceptionID"),
							 "album_id") > 0);
	}
	
	/* Salva as configurações */
	function applyNewConfig($dbConnection, $fields) {
		$keys = array_keys($fields);
		for ($i=0;$i<sizeof($fields);$i++) {
			$dbConnection->updateRecord("gallery_configs",array("config_value"=>$fields[$keys[$i]]),array("config_name='".$keys[$i]."'"));
			if ($dbConnection->lasterror!="")
				return false;
		}
		return true;
	}
	
	
	/* Transforma array PHP em JSON */
	function arrayPHP_JSON($data) {
		return json_encode($data);
		
		/*$res = "{";
		if (sizeof($data) > 0) {
			$keys = array_keys($data);
			for ($i=0;$i<sizeof($keys);$i++) {
				$key = $keys[$i];
				$val = $data[$key];
				
				if ($res!="{")
					$res .= ",";
				
				if (gettype($val) == "array") {
					$res .= arrayPHP_JSON($val);
				} else {
					$key = str_replace("\"", "", $key);
					$key = str_replace("'", "", $key);
					$key = str_replace("\n", "", $key);

					$val = str_replace("\"", "\\\"", $val);	
					$val = str_replace("\n", "\\n", $val);
					
					$res .= $key . ":\"" . $val . "\"";
				}
			}
		}
		$res .= "}";
		return $res;*/
	}
	
	/* Informação sobre contagem de registros personalizada */
	/* %% é a chave para o total de registros em $ifmany  */
	/* Exemplo $ifmany = "Achei %% registros"; */
	function counterInfo($value, $ifempty, $ifone, $ifmany) {
		if ($value==0)
			return $ifempty;
		else if ($value==1)
			return $ifone;
		else
			return str_replace("%%",$value,$ifmany);
	}
	
	/* Cria uma tabela do sistema */
	function createTable($neededTable, $connection) {
		if ($neededTable == "gallery_users") {
			$sql = "CREATE TABLE IF NOT EXISTS `gallery_users` (
					  `user_id` int(11) NOT NULL AUTO_INCREMENT,
					  `user_admin` smallint(6) NOT NULL,
					  `user_name` varchar(50) NOT NULL,
					  `user_pass` varchar(32) NOT NULL,
					  `user_session` varchar(32) DEFAULT NULL,
					  PRIMARY KEY (`user_id`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;";
			$connection->manipule($sql);
			if ($connection->lasterror=="") {
				$sql = "INSERT INTO `gallery_users` (`user_id`, `user_admin`, `user_name`, `user_pass`, `user_session`) VALUES
						(1, 1, 'Administrador', md5('gallery'), NULL),
						(2, 0, 'Editor', md5('editor'), NULL);";
				$connection->manipule($sql);
				if ($connection->lasterror=="") {
					$sql = "CREATE TABLE IF NOT EXISTS `gallery_albuns` (
							  `album_id` int(11) NOT NULL AUTO_INCREMENT,
							  `album_name` varchar(50) NOT NULL,
							  `album_description` varchar(255) DEFAULT NULL,
							  `album_cover` varchar(40) DEFAULT NULL,
							  PRIMARY KEY (`album_id`)
							) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
					$connection->manipule($sql);
					if ($connection->lasterror=="") {
						$sql = "CREATE TABLE IF NOT EXISTS `gallery_pictures` (
								  `pic_id` int(11) NOT NULL AUTO_INCREMENT,
								  `album_id` int(11) NOT NULL,
								  `pic_name` varchar(50) NOT NULL,
								  `pic_filename` varchar(40) NOT NULL,
								  `pic_originalname` varchar(255) NOT NULL,
								  PRIMARY KEY (`pic_id`)
								) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
						$connection->manipule($sql);
						if ($connection->lasterror=="") {
							$sql = "CREATE TABLE IF NOT EXISTS `gallery_configs` (
									  `config_id` int(11) NOT NULL AUTO_INCREMENT,
									  `config_name` varchar(50) NOT NULL,
									  `config_value` varchar(100) NOT NULL,
									  PRIMARY KEY (`config_id`)
									) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;";
							$connection->manipule($sql);
							if ($connection->lasterror=="") {
								$sql = "INSERT INTO `gallery_configs` (`config_id`, `config_name`, `config_value`) VALUES
										(1, 'max_pic_file_size', '10'),
										(2, 'max_pic_width', '5072'),
										(3, 'max_pic_height', '5072'),
										(4, 'pic_thumb_width', '256');";
								$connection->manipule($sql);
								if ($connection->lasterror=="")
									return true; /* Se executou os SQL com sucesso */
							}
						}
					}
				}
			}
		}
		
		die($connection->lasterror);
		
		return false; /* Se falhou em alguma operação */
	}

	/* Converte formato da data */
	function dateConversion($data) {
		$data = substr($data,0,10);
		return implode(preg_match("~\/~", $data) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data) == 0 ? "-" : "/", $data)));
	}
	
	/* Apaga o registro de um álbum e suas fotos do banco de dados */
	function deleteAlbumRecord($dbConnection, $albumid) {
		$dbConnection->deleteRecord("gallery_pictures","album_id=".$albumid);
		$dbConnection->deleteRecord("gallery_albuns","album_id=".$albumid);
	}
	
	/* Apaga o registro da foto do banco de dados */
	function deletePictureRecord($dbConnection, $picid) {
		$dbConnection->deleteRecord("gallery_pictures","pic_id=".$picid);
	}
	
	/* Busca os dados da página Quem Somos */
	function getAboutData($dbConnection) {
		$res = array("about_id"=>"0","about_title"=>"","about_text"=>"","about_image"=>"");
		$sql = "select about_id, about_title, about_text, about_image from gallery_about";
		$dbConnection->execute($sql);
		if ($dbConnection->lasterror=="") {
			if (($dbConnection->recordcount) && ($dbConnection->getData())) {
				$res["about_id"]    = $dbConnection->data[0];
				$res["about_title"] = $dbConnection->data[1];
				$res["about_text"]  = $dbConnection->data[2];
				$res["about_image"] = $dbConnection->data[3];
			}
		}
		return $res;
	}
	
	/* Busca os dados da página Como Encomendar */
	function getHowData($dbConnection) {
		$res = array("how_id"=>"0","how_title"=>"","how_text"=>"","how_image"=>"");
		$sql = "select how_id, how_title, how_text, how_image from gallery_how";
		$dbConnection->execute($sql);
		if ($dbConnection->lasterror=="") {
			if (($dbConnection->recordcount) && ($dbConnection->getData())) {
				$res["how_id"]    = $dbConnection->data[0];
				$res["how_title"] = $dbConnection->data[1];
				$res["how_text"]  = $dbConnection->data[2];
				$res["how_image"] = $dbConnection->data[3];
			}
		}
		return $res;
	}
	
	/* Busca os dados da página Frases */
	function getFrasesData($dbConnection) {
		$res = array("frases_id"=>"0","frases_title"=>"","frases_text"=>"","frases_image"=>"");
		$sql = "select frases_id, frases_title, frases_text, frases_image from gallery_frases";
		$dbConnection->execute($sql);
		if ($dbConnection->lasterror=="") {
			if (($dbConnection->recordcount) && ($dbConnection->getData())) {
				$res["frases_id"]    = $dbConnection->data[0];
				$res["frases_title"] = $dbConnection->data[1];
				$res["frases_text"]  = $dbConnection->data[2];
				$res["frases_image"] = $dbConnection->data[3];
			}
		}
		return $res;
	}

	/* Obtem os dados do álbum */
	function getAlbumData($dbConnection, $idAlbum) {
		$res = array("album_id"=>"","album_name"=>"","album_description"=>"","album_cover"=>"");
		$sql = "select album_id, album_name, album_description, album_cover from gallery_albuns where album_id=$idAlbum";
		$dbConnection->execute($sql);
		if ($dbConnection->lasterror=="") {
			if (($dbConnection->recordcount) && ($dbConnection->getData())) {
				$res["album_id"] = $dbConnection->data[0];
				$res["album_name"] = $dbConnection->data[1];
				$res["album_description"] = $dbConnection->data[2];
				$res["album_cover"] = $dbConnection->data[3];
			}
		}
		return $res;
	}
	
	/* Obtém as configurações */
	function getAllConfigs($dbConnection) {
		$res = array();

		$sql = "select config_name, config_value from gallery_configs";
		$dbConnection->execute($sql);
		while ($dbConnection->getData()) {
			$res[$dbConnection->data[0]] = $dbConnection->data[1];
		}

		return $res;
	}
	
	/* Obtem as imagens do álbum */
	function getAlbumPictures($dbConnection, $idAlbum, $categoryFilter="") {
		$res = array();
		$sql = "select pic_id, album_id, pic_name, pic_filename, pic_originalname, pic_description, pic_category" . 
		       "  from gallery_pictures " . 
			   " where (album_id = $idAlbum)";
			   
		if ($categoryFilter!="")
			$sql .= " and (pic_category='" . $categoryFilter . "')";
			   
		$dbConnection->execute($sql);
		if ($dbConnection->lasterror=="") {
			if ($dbConnection->recordcount) {
				while ($dbConnection->getData()) {
					$res[] = array(
						"pic_id"=>$dbConnection->data[0],
						"album_id"=>$dbConnection->data[1],
						"pic_name"=>$dbConnection->data[2],
						"pic_filename"=>$dbConnection->data[3],
						"pic_originalname"=>$dbConnection->data[4],
						"pic_description"=>$dbConnection->data[5],
						"pic_category"=>$dbConnection->data[6]
					);
				}
			}
		}
		return $res;
	}
	
	/* Lista todos os álbuns existentes */
	function getAllAlbuns($dbConnection, $exception="") {
		$res = array();

		$conditions = "";
		if ($exception!="")
			$conditions = " where ($exception) ";

		$sql = "select album_id, album_name, album_description, album_cover from gallery_albuns $conditions order by album_name";
		$dbConnection->execute($sql);
		if ($dbConnection->lasterror=="") {
			while ($dbConnection->getData()) {
				$res[] = array(
					"album_id"=>$dbConnection->data[0], 
					"album_name"=>$dbConnection->data[1], 
					"album_description"=>$dbConnection->data[2], 
					"album_cover"=>$dbConnection->data[3]
				);
			}
		}
		
		return $res;
	}
	
	function getArticles($dbConnection, $withText, $filter="") {
		$res = array();

		$text_field = ($withText) ? "article_text" : "'' as article_text";

		$sql = "select article_id, article_date, article_title, article_image, $text_field from gallery_articles";

		if ($filter!="")
			$sql .= " where (" . $filter . ")";

		$sql .= " order by article_date desc";

		$dbConnection->execute($sql);
		if ($dbConnection->lasterror=="") {
			while ($dbConnection->getData()) {
				$res[] = array(
					"article_id"=>$dbConnection->data[0], 
					"article_date"=>$dbConnection->data[1], 
					"article_title"=>$dbConnection->data[2], 
					"article_image"=>$dbConnection->data[3],
					"article_text"=>$dbConnection->data[4]
				);
			}
		}

		return $res;
	}
	
	/* Lista os nomes das categorias de um álbum */
	function getCategoryNames($dbConnection, $idAlbum) {
		$res = array();

		$sql = "select distinct pic_category from gallery_pictures where album_id='$idAlbum' order by pic_category";
		$dbConnection->execute($sql);
		if ($dbConnection->lasterror=="") {
			while ($dbConnection->getData()) {
				$res[] = $dbConnection->data[0];
			}
		}

		return $res;
	}
	
	/* Retorna a config. atual do banco de dados */
	function getDatabaseConfig() {
		if ($GLOBALS["published"]) { /* published = banco de dados do site oficial (produção) */
			return array(
				"name"=>$GLOBALS["config_Database_Name"],
				"server"=>$GLOBALS["config_Database_Server"],
				"user"=>$GLOBALS["config_Database_User"],
				"password"=>$GLOBALS["config_Database_Password"]
			);
		} else {
			return array(
				"name"=>$GLOBALS["localConfig_Database_Name"],
				"server"=>$GLOBALS["localConfig_Database_Server"],
				"user"=>$GLOBALS["localConfig_Database_User"],
				"password"=>$GLOBALS["localConfig_Database_Password"]
			);
		}
	}
	
	/* Retorna a lista de especialistas cadastrados */
	function getSpecialists($dbConnection) {
		/* Localiza o ID do álbum de especialistas */
		$idAlbum = searchTableID($dbConnection, "gallery_albuns", array("album_name='" . SPECIALISTS . "'"), "album_id");
		/* Se localizou */
		if ($idAlbum)
			return getAlbumPictures($dbConnection, $idAlbum);

		return array(); /* Retorna um array vazio se não encontrar o album */
	}
	
	/* Retorna a lista de estética cadastradas */
	function getAesthetics($dbConnection) {
		/* Localiza o ID do álbum de especialistas */
		$idAlbum = searchTableID($dbConnection, "gallery_albuns", array("album_name='" . AESTHETICS . "'"), "album_id");
		/* Se localizou */
		if ($idAlbum)
			return getAlbumPictures($dbConnection, $idAlbum);

		return array(); /* Retorna um array vazio se não encontrar o album */
	}
	
	/* Retorna a lista de ortodontia cadastradas */
	function getOrthodontics($dbConnection) {
		/* Localiza o ID do álbum de especialistas */
		$idAlbum = searchTableID($dbConnection, "gallery_albuns", array("album_name='" . ORTHODONTICS . "'"), "album_id");
		/* Se localizou */
		if ($idAlbum)
			return getAlbumPictures($dbConnection, $idAlbum);

		return array(); /* Retorna um array vazio se não encontrar o album */
	}
	
	/* Retorna lista de especialistas vinculados a uma determinada imagem de especialidade */
	function getSpecialistsFromPic($dbConnection, $idPic) {
		$todos = getSpecialists($dbConnection);
		$res = array();
		$sql = "select specialist_id from gallery_pic_specialists where picture_id = $idPic";
		$dbConnection->execute($sql);
		if ($dbConnection->recordcount) {
			while ($dbConnection->getData()) {
				$idEspec = $dbConnection->data[0];
				foreach($todos as $um) {
					if ($um["pic_id"] == $idEspec)
						$res[] = $um;
				}
			}
		}
		return $res;
	}
	
	/* Busca os dados da página Dicas */
	function getTipsData($dbConnection) {
		$res = array("tips_id"=>"0","tips_title"=>"","tips_text"=>"","tips_image"=>"");
		$sql = "select tips_id, tips_title, tips_text, tips_image from gallery_tips";
		$dbConnection->execute($sql);
		if ($dbConnection->lasterror=="") {
			if (($dbConnection->recordcount) && ($dbConnection->getData())) {
				$res["tips_id"]    = $dbConnection->data[0];
				$res["tips_title"] = $dbConnection->data[1];
				$res["tips_text"]  = $dbConnection->data[2];
				$res["tips_image"] = $dbConnection->data[3];
			}
		}
		return $res;
	}
	
	/* Retorna um campo GET garantindo a existência de um default em caso de campo não informado */
	function getField($parameter,$default="") {
		return (isset($_GET[$parameter])) ? $_GET[$parameter] : $default;
	}
	
	/* Busca os dados do usuário logado */
	function getLoggedUserData($dbConnection) {
		/* Estrutura de dados padrão do usuário */
		$return = array(
			"user_id"=>"",
			"user_admin"=>"",
			"user_name"=>"",
			"user_pass"=>"",
			"user_session"=>"",
			"error"=>""
		);
	
		/* Dados da sessão corrente */
		$usersession = sessionField("usersession");
		$username = sessionField("username");
		
		/* Se há sessão aberta, busca os dados no banco */
		if (($usersession!="") && ($username!="")) {
			$sql = "select user_id, user_admin, user_name, user_pass, user_session from gallery_users where (user_session='$usersession') and (user_name='$username')";
			$dbConnection->execute($sql);
			if ($dbConnection->lasterror!="") /* Sempre checa possíveis erros de SQL */
				$return["error"] = $dbConnection->lasterror;
			else if ($dbConnection->getData()) {
				/* Em caso de encontrar os dados, preenche o retorno */
				$return["user_id"]      = $dbConnection->data[0];
				$return["user_admin"]   = $dbConnection->data[1];
				$return["user_name"]    = $dbConnection->data[2];
				$return["user_pass"]    = $dbConnection->data[3];
				$return["user_session"] = $dbConnection->data[4];
			}
		}
		
		return $return; /* Por fim, retorna os dados */
	}
	
	/* Retorna um array de imagens baseado nos IDs solicitados */
	function getPicturesByMultipleID($dbConnection, $idPictures) {
		$res = array();
		$sql = "select pic_id, album_id, pic_name, pic_filename, pic_originalname, pic_description, pic_category " . 
		       "  from gallery_pictures " . 
			   " where (pic_id in ($idPictures))";
		$dbConnection->execute($sql);
		if ($dbConnection->lasterror=="") {
			if ($dbConnection->recordcount) {
				while ($dbConnection->getData()) {
					$res[] = array(
						"pic_id"=>$dbConnection->data[0],
						"album_id"=>$dbConnection->data[1],
						"pic_name"=>$dbConnection->data[2],
						"pic_filename"=>$dbConnection->data[3],
						"pic_originalname"=>$dbConnection->data[4],
						"pic_description"=>$dbConnection->data[5], 
						"pic_category"=>$dbConnection->data[6]
					);
				}
			}
		}
		return $res;
	}
	
	/* Retorna os dados de uma foto pelo ID */
	function getPictureData($dbConnection, $idPicture) {
		$res = array(
			"pic_id"=>0,
			"album_id"=>0,
			"pic_name"=>"",
			"pic_filename"=>"",
			"pic_originalname"=>"",
			"pic_description"=>"",
			"pic_category"=>""
		);
		
		$sql = "select pic_id, album_id, pic_name, pic_filename, pic_originalname, pic_description, pic_category " . 
		       "  from gallery_pictures " . 
			   " where (pic_id = $idPicture)";
		$dbConnection->execute($sql);
		if ($dbConnection->lasterror=="") {
			if ($dbConnection->recordcount) {
				if ($dbConnection->getData()) {
					$res = array(
						"pic_id"=>$dbConnection->data[0],
						"album_id"=>$dbConnection->data[1],
						"pic_name"=>$dbConnection->data[2],
						"pic_filename"=>$dbConnection->data[3],
						"pic_originalname"=>$dbConnection->data[4],
						"pic_description"=>$dbConnection->data[5],
						"pic_category"=>$dbConnection->data[6]
					);
				}
			}
		}
		return $res;
	}
	
	/* Lista os artigos (sem o título) */
	function getTitleArticles($dbConnection) {
		
		return getArticles($dbConnection, WITHOUT_TEXT);
		
	}
	
	/* Retorna os dados de um usuário pelo seu tipo (administrador ou editor) */
	function getUserDataByType($dbConnection, $userType) {
		$res = false; /* Retorna falso se não encontrar */
		
		/* SQL de Busca */
		$dbConnection->execute("select user_id, user_admin, user_name, user_pass, user_session from gallery_users where user_admin='$userType'");
		/* Se rodar sem erro: */
		if ($dbConnection->lasterror == "") {
			/* Se encontrar algo: */
			if (($dbConnection->recordcount > 0)&&($dbConnection->getData())) {
				/* Retorna os campos */
				$res = array(
					"user_id"=>$dbConnection->data[0],
					"user_admin"=>$dbConnection->data[1],
					"user_name"=>$dbConnection->data[2],
					"user_pass"=>$dbConnection->data[3],
					"user_session"=>$dbConnection->data[4]
				);
			}
		}
		
		return $res;
	}
	
	/* Retorna os dados de um usuário via senha */
	function getUserDataByPass($userPassword, $dbConnection) {
		$res = false; /* Retorna falso se não encontrar */
		
		/* SQL de Busca */
		$dbConnection->execute("select user_id, user_admin, user_name, user_pass, user_session from gallery_users where user_pass='" . md5($userPassword) ."'");
		/* Se rodar sem erro: */
		if ($dbConnection->lasterror == "") {
			/* Se encontrar algo: */
			if (($dbConnection->recordcount > 0)&&($dbConnection->getData())) {
				/* Retorna os campos */
				$res = array(
					"user_id"=>$dbConnection->data[0],
					"user_admin"=>$dbConnection->data[1],
					"user_name"=>$dbConnection->data[2],
					"user_pass"=>$dbConnection->data[3],
					"user_session"=>$dbConnection->data[4]
				);
			}
		}
		
		return $res;
	}
	
	function iconOption($caption, $link, $image, $columns) {
		/* Escreve um ícone de ação na tela */
		echo "	<div class='col-xs-$columns central' style='padding-bottom:20px'>";
		echo "		<a href='$link'><img src='./img/$image.png' class='img32' /><br />$caption</a>";
		echo "	</div>";
	}
	
	
	/* Realiza o check-list inicial para correto funcionamento do site */
	function initialCheckList() {
		/* Checa se a config. do banco de dados está correta */
		$databaseConfig = getDatabaseConfig();
		/* Se não há banco configurado, encerra redirecionando a página de apoio */
		if ($databaseConfig["name"]=="") {
			redirectTo("nodatabase.php");
			return false;
		} else {
			/* Se o banco já foi configurado, inicia as próximas checagens */
			/* Conecta-se ao banco de dados */
			$connection = new connMySQL($databaseConfig);
			/* Se a conexão falhar redireciona a página de erro */
			if ($connection->fail) {
				redirectTo("faildatabase.php?failid=" . $connection->fail);
				return false;
			} else {
				/* Após conectar-se, checa as tabelas do banco de dados */
				$allTables = array("gallery_users");
				$tables    = $connection->getTableNames();
				$success   = true;
				$created   = false;
				foreach ($allTables as $neededTable) {
					/* Verifica se a tabela necessária já está criada */
					if (!in_array($neededTable, $tables)) {
						// Se não, tenta criar a tabela
						if (createTable($neededTable, $connection))
							$created = true;
						else
							$success = false;
					}
				}
				/* Verifica se teve sucesso com as tabelas, se não, informa o problema */
				if ($success)	{
					if ($created)
						/* Se criou com sucesso, informa que sistema está pronto para ser usado */
						redirectTo("readytables.php");
				} else
					redirectTo("failtables.php");
			}
		}
		/* Se o check-list está ok, apenas retorna True */
		return true;
	}
	
	/* Insere um novo álbum e retorna o ID */
	function insertNewAlbum($dbConnection, $albumname, $albumdesc) {
		if ($dbConnection->insertRecord("gallery_albuns",
										array("album_id"=>"0",
											  "album_name"=>$albumname,
											  "album_description"=>$albumdesc,
											  "album_cover"=>"")))
			return $dbConnection->last_insert_id;
		else
			return 0;
	}
	
	/* Cadastra uma nova imagem para o álbum */
	function insertNewPicture($dbConnection, $albumID, $fileName, $originalName, $categoryName) {
		if ($dbConnection->insertRecord("gallery_pictures",
										array("pic_id"=>"0",
										      "album_id"=>$albumID,
											  "pic_name"=>$originalName,
											  "pic_filename"=>$fileName,
											  "pic_originalname"=>$originalName,
											  "pic_category"=>$categoryName)))
			return $dbConnection->last_insert_id;
		else
			return 0;
	}
	
	/* Realiza o logout de um usuário */
	function logoutUserById($userid, $dbConnection) {
		$res = ($dbConnection->updateRecord("gallery_users",array("user_session"=>"null"),array("user_id=".$userid)));
		if ($res) {
			$_SESSION["usersession"] = "";
			$_SESSION["username"]    = "";
			$_SESSION["useradmin"]   = "";
		}
		return $res;
	}
	
	/* Retorna um campo POST garantindo a existência de um default em caso de campo não informado */
	function postField($parameter,$default="") {
		return (isset($_POST[$parameter])) ? $_POST[$parameter] : $default;
	}

	/* Faz o redirecionamento para outra página no mesmo diretório via Headers */
	function redirectTo($pageName) {
		$actualLink = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]; /* Caminho atual */
		$novoLink = str_replace(basename($_SERVER['PHP_SELF']),$pageName,$actualLink); /* Novo caminho */
		if ($novoLink==$actualLink) /* Evita problemas caso o nome do arquivo atual não esteja aparecendo na URL do site (home page) */
			$novoLink .= $pageName;
		header("location:".$novoLink); /* Efetua o redirecionamento */
	}
	
	/* Retorna um erro padronizado em JSON */
	function returnError($errorCode, $errorName) {
		die(arrayPHP_JSON(array("feedback"=>"fail","errorcode"=>$errorCode,"errorname"=>$errorName)));
	}
	
	/* Localiza campo baseado em um conjunto de condições SQL */
	function searchTableID($dbConnection, $tableName, $arrayConditions, $returnField) {
		/* Gera a SQL com os dados passados: */
		$sql = "select $returnField from $tableName ";
		if (sizeof($arrayConditions) > 0) {
			/* Lista as condições SQL de busca */
			$separator = "where";
			foreach ($arrayConditions as $condition) {
				$sql .= $separator . " ($condition)";
				$separator = "and";
			}
			/* Garante o retorno de apenas 1 resultado */
			$sql .= " limit 0,1";
		}
		
		/* Faz a busca no banco de dados */
		$dbConnection->execute($sql);
		/* Se não houver erros: */
		if ($dbConnection->lasterror=="") {
			/* Se encontrou um resultado: */
			if (($dbConnection->recordcount) && ($dbConnection->getData())) {
				/* Retorna a informação */
				return $dbConnection->data[0];
			}
		}
		
		/* Caso não retornar nada da busca, retorna FALSE */
		return false;
	}
	
	
	/* Retorna um campo SESSION garantindo a existência de um default em caso de campo não informado */
	function sessionField($parameter,$default="") {
		return (isset($_SESSION[$parameter])) ? $_SESSION[$parameter] : $default;
	}
	
	/* Verifica se há uma sessão logada existente */
	function sessionExists($dbConnection) {
		$usersession = sessionField("usersession");
		$username = sessionField("username");
		if (($usersession!="") && ($username!="")) {
			$sql = "select user_id from gallery_users where (user_session='$usersession') and (user_name='$username')";
			$dbConnection->execute($sql);
			if ($dbConnection->getData()) {
				$userid = $dbConnection->data[0];
				return ($userid);
			}
		}
		return false;
	}
	
	/* Grava os dados da página Quem Somos */
	function setAboutData($dbConnection, $about_title, $about_text, $about_image) {
		$noConditions = array();
		$newData = array("about_title"=>$about_title,"about_text"=>$about_text,"about_image"=>$about_image);
		$dbConnection->updateRecord("gallery_about",$newData,$noConditions);
	}
	
	/* Grava os dados da página Quem Somos */
	function setFrasesData($dbConnection, $frases_title, $frases_text, $frases_image) {
		$noConditions = array();
		$newData = array("frases_title"=>$frases_title,"frases_text"=>$frases_text,"frases_image"=>$frases_image);
		$dbConnection->updateRecord("gallery_frases",$newData,$noConditions);
	}
	
	/* Grava os dados de um artigo */
	function setArticle($dbConnection, $article_id, $edt_article_title, $edt_article_text, $img_article) {
		if ($article_id=="0") {
			$dbConnection->insertRecord(
				"gallery_articles",
				array(
					"article_id"=>"0",
					"article_title"=>$edt_article_title,
					"article_text"=>$edt_article_text,
					"article_image"=>$img_article
				)
			);
			return $dbConnection->last_insert_id;
		} else {
			$dbConnection->updateRecord(
				"gallery_articles",
				array(
					"article_title"=>$edt_article_title,
					"article_text"=>$edt_article_text,
					"article_image"=>$img_article
				),
				array("article_id=".$article_id)
			);
			return $article_id;
		}
	}
	
	/* Grava a seleção de especialistas para uma especialidade */
	function setSpecialistsToItem($dbConnection, $idPic, $idSpecialists) {
		if ($idPic) {
			$dbConnection->deleteRecord("gallery_pic_specialists","picture_id=$idPic");
			if (sizeof($idSpecialists)) {
				foreach($idSpecialists as $idEsp) {
					$dbConnection->insertRecord(
						"gallery_pic_specialists",
						array(
							"id"=>"0",
							"picture_id"=>$idPic,
							"specialist_id"=>$idEsp
						)
					);
				}
			}
		}
	}
	
	/* Define a sessão de um usuário logado */
	function setUserSession($user_id, $user_session, $dbConnection) {
		return ($dbConnection->updateRecord("gallery_users",array("user_session"=>$user_session),array("user_id=$user_id")));
	}
	
	/* Converte as quebras de linha de uma string html */
	function stringEnterToBR($string) {
		return str_replace("\n","<br />",$string);
	}
	
	/* Converte as quebras de linha de html para Enter */
	function stringBRToEnter($string) {
		$string = str_replace("<br />","\n",$string);
		return str_replace("<br>","\n",$string);
	}

	/* HTML de titulo da página */
	function titleInfo($title, $help="") {
		echo"	<div class='row'>";
		echo"		<div class='col-lg-12'>";
		echo"			<h1 class='page-header'>$title</h1>";
		if ($help!="")
			echo"			<p>$help</p>";
		echo"		</div>";
		echo"	</div>";
	}
	
	/* Atualiza a capa do álbum */
	function updateAlbumCover($dbConnection, $albumID, $filename) {
		return ($dbConnection->updateRecord("gallery_albuns",array("album_cover"=>$filename),array("album_id=".$albumID)));
	}
	
	/* Atualiza os dados do álbum */
	function updateAlbumData($dbConnection, $albumID, $albumname, $albumdesc) {
		return ($dbConnection->updateRecord("gallery_albuns",
											array("album_name"=>$albumname,"album_description"=>$albumdesc),
											array("album_id=".$albumID)));
	}
	
	/* Edita o nome de uma imagem */
	function updatePictureName($dbConnection, $idpic, $namepic, $descpic="", $article_id="0") {
		return ($dbConnection->updateRecord("gallery_pictures",array("pic_name"=>$namepic,"pic_description"=>$descpic),array("pic_id=".$idpic)));
	}
?>