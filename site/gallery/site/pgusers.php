<?php
	require_once "mainlib.php"; /* Funções gerais, chamar em todo .php de conteúdo que for criado */
	
	/* Conexão com Banco de Dados */
	$dbConnection = new connMySQL(getDatabaseConfig());
	/* Dados do usuário logado */
	$userData = getLoggedUserData($dbConnection);
	
	/* Obtém os dados dos usuários do sistema */
	$adminUser  = getUserDataByType($dbConnection, USER_ADMIN);
	$editorUser = getUserDataByType($dbConnection, USER_EDITOR);

	/* Encerra conexão */
	$dbConnection->close();
	
	/* Se achou o usuário continua: */
	if ($userData["user_name"]!="") {
		/* Verifica as permissões de administrador */
		if ($userData["user_admin"]) {
			/* Título com a quantidade de registros encontrados */
			titleInfo("Usuários","");

			/* Quadros de ação dos usuários: */
		?>
			<div class="row">
			  <div class="col-sm-6 central margemabaixo">
				<div>
					<img src="./img/admin1.png" class="img32" />
				</div>
				<div class="negrito">
					<?php echo $adminUser["user_name"]; ?>
				</div>
				<div class="italico">
					(administrador)
				</div>
				<div class="row">
					<div class="col-xs-12 central">
						<button type="button" class="btn btn-primary" onclick="askChangeName(USER_ADMIN, '<?php echo $adminUser["user_name"]; ?>')"><span class="glyphicon glyphicon-pencil"></span> Alterar nome</button>
						<button type="button" class="btn btn-danger" onclick="askChangePassword(USER_ADMIN,'','','')"><span class="glyphicon glyphicon-check"></span> Alterar senha</button>
					</div>
				</div>
			  </div>
			  <div class="col-sm-6 central margemabaixo">
				<div>
					<img src="./img/user1.png" class="img32" />
				</div>
				<div class="negrito">
					<?php echo $editorUser["user_name"]; ?>
				</div>
				<div class="italico">
					(editor)
				</div>
				<div class="row">
					<div class="col-xs-12 central">
						<button type="button" class="btn btn-primary" onclick="askChangeName(USER_EDITOR, '<?php echo $editorUser["user_name"]; ?>')"><span class="glyphicon glyphicon-pencil"></span> Alterar nome</button>
						<button type="button" class="btn btn-danger" onclick="askChangePassword(USER_EDITOR,'','','')"><span class="glyphicon glyphicon-check"></span> Alterar senha</button>
					</div>
				</div>
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