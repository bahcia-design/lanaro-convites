<?php
	require_once "mainlib.php"; /* Funções gerais, chamar em todo .php de conteúdo que for criado */
	
	/* Conexão com Banco de Dados */
	$dbConnection = new connMySQL(getDatabaseConfig());
	/* Dados do usuário logado */
	$userData = getLoggedUserData($dbConnection);

	/* ID do artigo */
	$article_id = getField("article_id","0");

	/* Dados para inclusão de novo artigo */
	$article_title = "";
	$article_text  = "";
	$article_image = "";

	/* Busca o artigo se for alteração */
	if ($article_id > 0) {
		$articles = getArticles($dbConnection, WITH_TEXT, "article_id=$article_id");

		if (sizeof($articles)) {
			$article_title = $articles[0]["article_title"];
			$article_text  = $articles[0]["article_text"];
			$article_image = $articles[0]["article_image"];
		} else
			$article_id = "0";
	}

	/* Encerra conexão */
	$dbConnection->close();

	/* Se achou o usuário continua: */
	if ($userData["user_name"]!="") {
		/* Verifica as permissões de administrador */
		if ($userData["user_admin"]) {
			/* Título com a quantidade de registros encontrados */
			if ($article_id > 0)
				titleInfo("Editar artigo","");
			else
				titleInfo("Incluir artigo","");

			if ($article_image!="")
				$pathImg = "./img/articles/" . $article_image;
			else
				$pathImg = "./img/back_article1.png";

			?>
			<div class="margemabaixo">
				<div class="form-group" id="div_form_pic">
					<center>
						<img src="<?php echo $pathImg; ?>" id="img_article" filename="" class="img-responsive" /><br />
						<form id='farticlepic' style='overflow:hidden'>
							<input type="file" onchange='startUploadArticlePic()' accept='image/*' id='article_pic' name='article_pic' />
						</form>
					</center>
				</div>
				<div class="form-group" style="text-align:center;display:none" id="div_img_loading">
					Carregando, aguarde...<br />
					<img src="./img/load_blue1.gif" />
				</div>				
				<div class="form-group">
					<label for="edt_article_title">Título do artigo:</label>
					<input type="text" class="form-control" id="edt_article_title" placeholder="Título" value="<?php echo $article_title; ?>">
				</div>
				<div class="form-group">
					<label for="edt_article_text">Texto:</label>
					<textarea class="form-control" id="edt_article_text" rows=20 placeholder="Descrição"><?php echo $article_text; ?></textarea>
				</div>
				<div class="form-group">
					<input type=hidden id='edt_article_id' value='<?php echo $article_id; ?>' />
					<button class="btn btn-primary" onclick="confirmArticleData()"><span class="glyphicon glyphicon-ok"></span> Salvar</label>
				</div>
			</div>
			<?php
		} else {
			/* Informa que não encontrou o usuário */
			titleInfo("Ops!","Aparentemente não identificamos suas permissões para este acesso, desculpe o transtorno. <a href='./logout.php'>Clique aqui</a> para tentar novamente.");
		}
	} else {
		/* Informa que não encontrou o usuário */
		titleInfo("Ops!","Aparentemente não identificamos seu login, desculpe o transtorno. <a href='./index.php'>Clique aqui</a> para entrar novamente.");
	}
?>