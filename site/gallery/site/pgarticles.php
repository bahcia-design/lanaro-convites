<?php
	require_once "mainlib.php"; /* Funções gerais, chamar em todo .php de conteúdo que for criado */
	
	/* Conexão com Banco de Dados */
	$dbConnection = new connMySQL(getDatabaseConfig());
	/* Dados do usuário logado */
	$userData = getLoggedUserData($dbConnection);
	/* Lista todos os artigos cadastrados (sem o texto) */
	$articles = getTitleArticles($dbConnection);
	/* Encerra conexão */
	$dbConnection->close();

	/* Se achou o usuário continua: */
	if ($userData["user_name"]!="") {
		/* Verifica as permissões de administrador */
		if ($userData["user_admin"]) {
			/* Título com a quantidade de registros encontrados */
			$btnInclui = "<a href='editarticle.php' class='btn btn-success btn-sm'><span class=\"glyphicon glyphicon-plus\"></span> Incluir novo artigo</a>";
			if (sizeof($articles) == 0)
				titleInfo("Nenhum artigo",$btnInclui);
			else if (sizeof($articles) == 1)
				titleInfo("1 artigo",$btnInclui);
			else
				titleInfo(sizeof($articles) . " artigos",$btnInclui);
			?>
			<div class="margemabaixo">
				<?php
					if (sizeof($articles) > 0) {
						foreach($articles as $article) {
							if ($article["article_image"]!="")
								$pathImg = "./img/articles/" . $article["article_image"];
							else
								$pathImg = "./img/back_article1.png";
							
							echo "<div class='row'>";
							echo "<div class='col-xs-1'>";
							echo "<img src='" . $pathImg . "' class='img-responsive img-thumbnail' />";
							echo "</div>";
							echo "<div class='col-xs-7'>";
							echo "<b>" . $article["article_title"] . "</b>";
							echo "</div>";
							echo "<div class='col-xs-2'>";
							echo dateConversion($article["article_date"]);
							echo "</div>";
							echo "<div class='col-xs-2'>";
							echo actionButton("", "startArticleEdition(" . $article["article_id"] . ")", "info", "pencil");
							echo "</div>";
							echo "</div>";
						}
					}
				?>
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