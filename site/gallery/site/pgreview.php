<?php
	require_once "mainlib.php"; /* Funções gerais, chamar em todo .php de conteúdo que for criado */
	
	/* Conexão com Banco de Dados */
	$dbConnection = new connMySQL(getDatabaseConfig());
	/* Dados do usuário logado */
	$userData = getLoggedUserData($dbConnection);
	
	/* ID do álbum */
	$idalbum = getField("idalbum");
	/* ID das imagens a revisar */
	$idPictures = getField("pictures");

	/* Dados do álbum */
	$albumData = getAlbumData($dbConnection, $idalbum);
	/* Imagens do álbum */
	$pictures = getPicturesByMultipleID($dbConnection, $idPictures);

	/* Se achou o usuário continua: */
	if ($userData["user_name"]!="") {
		/* Título com a quantidade de registros encontrados */
		titleInfo("Editar imagens",counterInfo(sizeof($pictures), "Você não tem imagens para editar.", "", "Você tem %% imagens para editar:"));

		/* Ações iniciais */
		actionButtons(array(
			actionButton("Voltar para galeria","window.location='./album.php?idalbum=$idalbum'","primary","chevron-left")
		));

		/* Exibe as miniaturas do álbum */
		$i = 0;
		foreach ($pictures as $picture) {
			$img="./photos/thumb/".$picture["pic_filename"];
			echo "<div class='row'>";
			echo "<div class='col-xs-2 thumb'>
					<a class='thumbnail' href='#'>
						<img class='img-responsive' src='$img' alt=''>
					</a>
				</div>";
			echo "<div class='col-xs-10'>
					<div class='margemabaixo'><div class='alert alert-success'>".$picture["pic_originalname"]."</div></div>
					<div><input type='hidden' class='form-control' id='f_idPicture_$i' value='".$picture["pic_id"]."' />
					<input type='text' class='form-control' placeholder='Título da foto' id='f_textPicture_$i' value='".$picture["pic_name"]."' /></div>";

			echo "<div><textarea class='form-control' rows=10 id='f_descPicture_$i' placeholder='Descrição completa'></textarea></div>";

			echo "	</div>";
			echo "</div>";

			$i++;
		}

		echo "<div id='dloadingreview' class='oculto'><span><img id='imgLoading' src='./img/load_blue1.gif' /> Processando...&nbsp;&nbsp;&nbsp;</span></div>";
		echo "<div id='dsavereview'>";

		/* Ações iniciais */
		actionButtons(array(
			actionButton("Salvar edições","startSaveEditions($idalbum)","primary","ok")
		));

		echo "</div>";
		
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
		/* Informa que não encontrou o usuário */
		titleInfo("Ops!","Aparentemente não identificamos seu login, desculpe o transtorno. <a href='./index.php'>Clique aqui</a> para entrar novamente.");
	}
	
	/* Encerra conexão */
	$dbConnection->close();
?>