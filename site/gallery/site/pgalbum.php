<?php
	require_once "mainlib.php"; /* Funções gerais, chamar em todo .php de conteúdo que for criado */
	
	/* Conexão com Banco de Dados */
	$dbConnection = new connMySQL(getDatabaseConfig());
	/* Dados do usuário logado */
	$userData = getLoggedUserData($dbConnection);

	/* Nome do álbum (prioridade) */
	$albumName = getField("albumname");
	if ($albumName!="")
		/* Busca o ID do album pelo nome */
		$idAlbum = searchTableID($dbConnection, "gallery_albuns", array("album_name='$albumName'"), "album_id");
	else
		/* ID do álbum */
		$idAlbum = getField("idalbum");	
		
	/* Filtro por categoria */
	$categoryFilter = getField("category");
	if ($categoryFilter=="all")
		$categoryFilter = "";
	else if ($categoryFilter=="")
		$categoryFilter = sessionField("category");
	$_SESSION["category"] = $categoryFilter;

	/* Dados do álbum */
	$albumData = getAlbumData($dbConnection, $idAlbum);

	/* Imagens do álbum */
	$pictures = getAlbumPictures($dbConnection, $idAlbum, $categoryFilter);
	
	/* Nomes das categorias do álbum */
	$categoryNames = getCategoryNames($dbConnection, $idAlbum);

	/* Encerra conexão */
	$dbConnection->close();
		
	/* Se achou o usuário continua: */
	if ($userData["user_name"]!="") {
	
		/* Verifica se achou o álbum corretamente */
		if ($albumData["album_id"] > 0) {
			$desc = "";
			if (trim($albumData["album_description"])!="")
				$desc = str_replace("\n","<br />",$albumData["album_description"]) . "<br />&nbsp;<br />";

			$htmlCat = "";
			/* Checa se mostra as categorias */
			if (sizeof($categoryNames)) {
				$btnColor = ($categoryFilter=="") ? "warning" : "info";
				
				$htmlCat .= "<div id='wellcat' total='" . sizeof($categoryNames) . "' class='well'><div class='btn-group'>";
				$htmlCat .= "<a href='./album.php?idalbum=" . $idAlbum . "&category=all' class='btn btn-$btnColor btn-sm'>Todas</a>";
				
				$c = 0;
				foreach($categoryNames as $name) {
					$btnColor = ($categoryFilter==$name) ? "warning" : "info";
					$selected = ($categoryFilter==$name) ? "yes" : "no";
					$htmlCat .= "<a id='btncat" . $c . "' selectme='$selected' href='./album.php?idalbum=" . $idAlbum . "&category=" . $name . "' class='btn btn-$btnColor btn-sm'>$name</a>";
					$c++;
				}
				$htmlCat .= "</div></div>";
			}
			
			/* Título com a quantidade de registros encontrados */
			titleInfo("Galeria ".$albumData["album_name"],$desc . "</p>" . $htmlCat . "<p>" . counterInfo(sizeof($pictures), "Você ainda não tem imagens nesta galeria.", "", "Você tem %% imagens:"));

			/* Ações iniciais */
			$botoes = array(
				actionButton("Enviar imagens","askSendPictures(".$albumData["album_id"].")","primary","plus"),
				actionButton("Voltar às galerias","window.location='./gallery.php'","primary","chevron-left")
			);
			if ($userData["user_admin"]==USER_ADMIN)
				$botoes[] = actionButton("Editar galeria","askAlbumEdit(".$albumData["album_id"].")","success","pencil");
			
			
			actionButtons($botoes);

			/* Exibe as miniaturas do álbum */
			echo "<div class='row'>";
			foreach ($pictures as $picture) {
				$img="./photos/thumb/".$picture["pic_filename"];
				$name=$picture["pic_name"];
				if (strlen($name) > 30)
					$name = substr($name,0,30)."...";

				echo "<div class='central col-lg-3 col-md-4 col-xs-6 thumb'>
						<a class='thumbnail' href='detail.php?idpic=".$picture["pic_id"]."'>
							<img class='img-responsive' src='$img' alt=''>
							<span>$name</span>
						</a>
					</div>";
			}
			echo "</div>";

			/* Ações de rodapé (apenas administrador) */
			if ($userData["user_admin"]==USER_ADMIN) {
				/* Não mostra se for especialistas */
				if ($albumName != SPECIALISTS) {
					actionButtons(array(
						actionButton("Apagar álbum inteiro...","askDeleteAlbum(".$albumData["album_id"].")","danger","remove")
					));
				}
			}

			?>	
			<!--<div class="row">

				<div class="col-lg-3 col-md-4 col-xs-6 thumb">
					<a class="thumbnail" href="#">
						<img class="img-responsive" src="http://placehold.it/400x300" alt="">
					</a>
				</div>
				<div class="col-lg-3 col-md-4 col-xs-6 thumb">
					<a class="thumbnail" href="#">
						<img class="img-responsive" src="http://placehold.it/400x300" alt="">
					</a>
				</div>
				<div class="col-lg-3 col-md-4 col-xs-6 thumb">
					<a class="thumbnail" href="#">
						<img class="img-responsive" src="http://placehold.it/400x300" alt="">
					</a>
				</div>
			</div>-->
			<?php
			
		} else {
			/* Informa que não encontrou o id do album */
			titleInfo("Ops!","O álbum não foi encontrado, desculpe o transtorno. <a href='./gallery.php'>Clique aqui</a> para ver as galerias.");
		}	
	} else {
		/* Informa que não encontrou o usuário */
		titleInfo("Ops!","Aparentemente não identificamos seu login, desculpe o transtorno. <a href='./index.php'>Clique aqui</a> para entrar novamente.");
	}
?>