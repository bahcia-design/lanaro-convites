<?php
	require_once "mainlib.php"; /* Funções gerais, chamar em todo .php de conteúdo que for criado */
	
	/* Conexão com Banco de Dados */
	$dbConnection = new connMySQL(getDatabaseConfig());
	/* Dados do usuário logado */
	$userData = getLoggedUserData($dbConnection);
	/* Busca os dados de Quem Somos */
	$about = getAboutData($dbConnection);
	/* Encerra conexão */
	$dbConnection->close();

	/* Se achou o usuário continua: */
	if ($userData["user_name"]!="") {
		/* Verifica as permissões de administrador */
		if ($userData["user_admin"]) {
			/* Título com a quantidade de registros encontrados */
			titleInfo("Quem Somos","");
			
			if ($about["about_image"]!="")
				$pathImg = "./img/about/" . $about["about_image"];
			else
				$pathImg = "./img/back_about1.png";
			
			?>
			<div class="margemabaixo">
				<div class="form-group" id="div_form_pic">
					<center>
						<img src="<?php echo $pathImg; ?>" id="img_about" filename="<?php echo ($about["about_image"]!="") ? $about["about_image"] : ""; ?>" class="img-responsive" /><br />
						<form id='faboutpic' style='overflow:hidden'>
							<input type="file" onchange='startUploadAboutPic()' accept='image/*' id='about_pic' name='about_pic' />
						</form>
					</center>
				</div>
				<div class="form-group" style="text-align:center;display:none" id="div_img_loading">
					Carregando, aguarde...<br />
					<img src="./img/load_blue1.gif" />
				</div>				
				<div class="form-group">
					<label for="edt_about_title">Título da página:</label>
					<input type="text" class="form-control" id="edt_about_title" placeholder="Título" value="<?php echo $about["about_title"]; ?>">
				</div>
				<div class="form-group">
					<label for="edt_about_text">Texto da página:</label>
					<textarea class="form-control" id="edt_about_text" rows=20 placeholder="Descrição"><?php echo $about["about_text"]; ?></textarea>
				</div>
				<div class="form-group">
					<button class="btn btn-primary" onclick="confirmAboutData()"><span class="glyphicon glyphicon-ok"></span> Salvar alterações</label>
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