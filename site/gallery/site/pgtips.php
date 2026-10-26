<?php
	require_once "mainlib.php"; /* Funções gerais, chamar em todo .php de conteúdo que for criado */
	
	/* Conexão com Banco de Dados */
	$dbConnection = new connMySQL(getDatabaseConfig());
	/* Dados do usuário logado */
	$userData = getLoggedUserData($dbConnection);
	/* Busca os dados de Quem Somos */
	$tips = getTipsData($dbConnection);
	/* Encerra conexão */
	$dbConnection->close();

	/* Se achou o usuário continua: */
	if ($userData["user_name"]!="") {
		/* Verifica as permissões de administrador */
		if ($userData["user_admin"]) {
			/* Título com a quantidade de registros encontrados */
			titleInfo("Dicas","");
			
			if ($tips["tips_image"]!="")
				$pathImg = "./img/tips/" . $tips["tips_image"];
			else
				$pathImg = "./img/back_about1.png";
			
			?>
			<div class="margemabaixo">
				<div class="form-group" id="div_form_pic">
					<center>
						<img src="<?php echo $pathImg; ?>" id="img_tips" filename="" class="img-responsive" /><br />
						<form id='ftipspic' style='overflow:hidden'>
							<input type="file" onchange='startUploadTipsPic()' accept='image/*' id='tips_pic' name='tips_pic' />
						</form>
					</center>
				</div>
				<div class="form-group" style="text-align:center;display:none" id="div_img_loading">
					Carregando, aguarde...<br />
					<img src="./img/load_blue1.gif" />
				</div>				
				<div class="form-group">
					<label for="edt_tips_title">Título da página:</label>
					<input type="text" class="form-control" id="edt_tips_title" placeholder="Título" value="<?php echo $tips["tips_title"]; ?>">
				</div>
				<div class="form-group">
					<label for="edt_tips_text">Texto da página:</label>
					<textarea class="form-control" id="edt_tips_text" rows=20 placeholder="Descrição"><?php echo $tips["tips_text"]; ?></textarea>
				</div>
				<div class="form-group">
					<button class="btn btn-primary" onclick="confirmTipsData()"><span class="glyphicon glyphicon-ok"></span> Salvar alterações</label>
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