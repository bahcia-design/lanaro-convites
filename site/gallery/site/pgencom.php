<?php
	require_once "mainlib.php"; /* Funções gerais, chamar em todo .php de conteúdo que for criado */
	
	/* Conexão com Banco de Dados */
	$dbConnection = new connMySQL(getDatabaseConfig());
	/* Dados do usuário logado */
	$userData = getLoggedUserData($dbConnection);
	/* Busca os dados de Quem Somos */
	$como = getHowData($dbConnection);
	/* Encerra conexão */
	$dbConnection->close();

	/* Se achou o usuário continua: */
	if ($userData["user_name"]!="") {
		/* Verifica as permissões de administrador */
		if ($userData["user_admin"]) {
			/* Título com a quantidade de registros encontrados */
			titleInfo("Como encomendar","");
			
			?>
			<div class="margemabaixo">
				<div class="form-group">
					<label for="edt_how_title">Título da página:</label>
					<input type="text" class="form-control" id="edt_how_title" placeholder="Título" value="<?php echo $como["how_title"]; ?>">
				</div>
				<div class="form-group">
					<label for="edt_how_text">Texto da página:</label>
					<textarea class="form-control" id="edt_how_text" rows=20 placeholder="Descrição"><?php echo $como["how_text"]; ?></textarea>
				</div>
				<div class="form-group">
					<button class="btn btn-primary" onclick="confirmComoEncomendarData()"><span class="glyphicon glyphicon-ok"></span> Salvar alterações</label>
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