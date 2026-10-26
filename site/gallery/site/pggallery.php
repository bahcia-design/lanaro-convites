<?php
	require_once "mainlib.php"; /* Funções gerais, chamar em todo .php de conteúdo que for criado */

	/* Conexão com Banco de Dados */
	$dbConnection = new connMySQL(getDatabaseConfig());
	/* Dados do usuário logado */
	$userData = getLoggedUserData($dbConnection);

	/* Lista os álbuns existentes exceto os especiais que tem páginas próprias */
	$albuns = getAllAlbuns($dbConnection, "(album_name <> 'Parceiros')");
	
	/* Encerra conexão */
	$dbConnection->close();
	
	/* Se achou o usuário continua: */
	if ($userData["user_name"]!="") {
		
		/* Limpa o filtro de categorias para evitar problemas ao trocar de álbum */
		$_SESSION["category"] = "";

		/* Título com a quantidade de registros encontrados */
		titleInfo("Galerias disponíveis",counterInfo(sizeof($albuns), "Você ainda não tem nenhum álbum.", "", "Você tem %% álbuns:"));

		/* Ações iniciais (apenas para administrador) */
		if ($userData["user_admin"]==USER_ADMIN) {
			actionButtons(array(
				/* Inclusão de novo álbum */
				actionButton("Criar novo álbum agora","askNewAlbum('','')","primary","plus")
			));
		}

		/* Exibe as miniaturas dos álbuns */
		echo "<div class='row'>";
		foreach ($albuns as $album) {
			if ($album["album_cover"]=="")
				$img="./img/nopic1.png";
			else
				$img="./photos/thumb/".$album["album_cover"];

			echo "<div class='central col-lg-3 col-md-4 col-xs-6 thumb'>
					<a class='thumbnail' href='./album.php?idalbum=".$album["album_id"]."'>
						<img class='img-responsive' src='$img' alt=''>
						<span>".$album["album_name"]."</span>
					</a>
				</div>";
		}
		echo "</div>";	
	} else {
		/* Informa que não encontrou o usuário */
		titleInfo("Ops!","Aparentemente não identificamos seu login, desculpe o transtorno. <a href='./index.php'>Clique aqui</a> para entrar novamente.");
	}
?>