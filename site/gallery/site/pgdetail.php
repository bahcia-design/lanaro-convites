<?php
	require_once "mainlib.php"; /* Funções gerais, chamar em todo .php de conteúdo que for criado */
	
	/* Conexão com Banco de Dados */
	$dbConnection = new connMySQL(getDatabaseConfig());
	/* Dados do usuário logado */
	$userData = getLoggedUserData($dbConnection);

	/* ID do álbum */
	$idPicture = getField("idpic");

	/* Dados da imagem */
	$pictureData = getPictureData($dbConnection, $idPicture);
	
	/* Dados do álbum */
	$albumData = getAlbumData($dbConnection, $pictureData["album_id"]);
	
	/* Todos os especialistas (se necessário) */
	if (($albumData["album_name"] == AESTHETICS) || ($albumData["album_name"] == ORTHODONTICS)) {
		$allSpecialists = getSpecialists($dbConnection);
		/* Artigos para linkar a especialidade: */
		$articleNames = getArticles($dbConnection, WITHOUT_TEXT);
	}

	/* Se achou o usuário continua: */
	if ($userData["user_name"]!="") {
	
		/* Verifica se achou o álbum corretamente */
		if ($pictureData["pic_id"] > 0) {

			/* Título com a quantidade de registros encontrados */
			titleInfo($pictureData["pic_name"],"");

			$img="./photos/".$pictureData["pic_filename"];

			/* Exibe os dados da imagem */
			echo "<div class='row'>";
			echo "<div class='col-xs-2'>&nbsp;</div>
					<div class='col-xs-8 thumb'>			
						<img class='img-responsive' src='$img' alt=''>
					</div>
					<div class='col-xs-2'>&nbsp;</div>
				</div>";
			echo "<div class='row'>";
			echo "<div class='col-xs-2'>&nbsp;</div><div class='col-xs-8 margemabaixo'>
					<input type='hidden' class='form-control' id='f_idpic' value='".$pictureData["pic_id"]."' />
					<input type='text' class='form-control' placeholder='Título da foto' id='f_namepic' value='".$pictureData["pic_name"]."' />";

			echo "<div><textarea class='form-control' rows=10 id='f_descpic' placeholder='Descrição'>" . stringBRToEnter($pictureData["pic_description"]) . "</textarea></div>";
			if (($albumData["album_name"] == AESTHETICS) || ($albumData["album_name"] == ORTHODONTICS)) {
				echo "<div>";
				echo "<select id='cboArticle' class='form-control'><option value='0'>Artigo para Saiba Mais:</option>";
				foreach ($articleNames as $article) {
					$sel = ($pictureData["pic_article_id"] == $article["article_id"]) ? " selected " : "";
					echo "<option $sel value='" . $article["article_id"] . "'>" . $article["article_title"] . "</option>";
				}
				echo "</select>";
				echo "</div>";
			
				$thatSpec = getSpecialistsFromPic($dbConnection, $pictureData["pic_id"]);
				/* Monta os IDs selecionados */
				$valueIDs = "";
				foreach($thatSpec as $spec) {
					$valueIDs .= "(" . $spec["pic_id"] . ")";
				}

				/* Monta a grade de foto dos especialistas */
				echo "<div style='padding:20px'><div>Selecione os especialistas:<input type=hidden value='$valueIDs' id='sp_sel_" . $pictureData["pic_id"] . "' /></div><div class='row'>";
				foreach ($allSpecialists as $spec) {
					$this_id = $pictureData["pic_id"] . "_" . $spec["pic_id"];

					/* Verifica se está selecionado ou não */
					if (strpos($valueIDs,"(".$spec["pic_id"].")") === FALSE) {
						$marked = "";
						$color  = "#fff";
					} else {
						$marked = " selected=1";
						$color  = "#c0c0c0";
					}
					
					echo "<div class='col-xs-1' id='div_pic_$this_id' style='background-color:$color'>";
					echo "<img $marked id='img_pic_$this_id' onclick='specialistSelecion(".$pictureData["pic_id"].",".$spec["pic_id"].")' src='./photos/thumb/".$spec["pic_filename"] . "' class='img-responsive img-circle'>";
					echo "</div>";
				}
				echo "</div></div>";
			}
			

			echo"	</div><div class='col-xs-2'>&nbsp;</div>";
			echo "</div>";
			echo "<div class='row'>";
			echo "<div class='col-xs-2'>&nbsp;</div><div id='dbuttons' class='col-xs-8 margemabaixo'>";

			/* Ações iniciais */
			actionButtons(array(
				actionButton("Salvar","askSavePicture(".$pictureData["pic_id"].",".$pictureData["album_id"].")","primary","ok"),
				actionButton("Capa","askAlbumCover(".$pictureData["album_id"].",'".$pictureData["pic_filename"]."')","success","film"),
				actionButton("Excluir","askDeletePicture(".$pictureData["pic_id"].",".$pictureData["album_id"].")","danger","remove"),
				actionButton("Voltar","window.location='album.php?idalbum=".$pictureData["album_id"]."'","primary","chevron-left")
			));	
			
			echo "</div><div id='ddetailloading' class='col-xs-8 margemabaixo oculto'><span><img src='./img/load_blue1.gif' /> Processando...</span></div><div class='col-xs-2'>&nbsp;</div></div>";
		} else {
			/* Informa que não encontrou o id do album */
			titleInfo("Ops!","A imagem não foi encontrada, desculpe o transtorno. <a href='./gallery.php'>Clique aqui</a> para ver as galerias.");
		}	
	} else {
		/* Informa que não encontrou o usuário */
		titleInfo("Ops!","Aparentemente não identificamos seu login, desculpe o transtorno. <a href='./index.php'>Clique aqui</a> para entrar novamente.");
	}

	/* Encerra conexão */
	$dbConnection->close();
?>