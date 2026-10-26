<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Galeria de Imagens">
    <meta name="author" content="Willian Soares">

    <title>Galeria de Imagens :: Editor</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Ícone da Aba -->
	<link rel="SHORTCUT ICON" href="./favicon.png" type="image/png" />

    <!-- Estilos do site -->
    <link rel="stylesheet" media="(max-width: 800px)" href="./css/thumbnail-gallery-mob.css">
    <link rel="stylesheet" media="(min-width: 801px)" href="./css/thumbnail-gallery.css">
	
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Opções</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="./home.php">Gallery</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
					<?php
						/*
							Esta checagem abaixo é para não mostrar o botão sair, sem estar logado.
						*/
						if (sessionField("usersession")!="") {
							/* Opções do administrador para Álbuns */
							if (sessionField("useradmin")==USER_ADMIN) {
								if ($page=="albuns") {
									echo "<li>";
									echo "	<a href='javascript:void(0)' onclick=\"askNewAlbum('','')\"><span class=\"glyphicon glyphicon-plus\"></span> Criar álbum</a>";
									echo "</li>";
								}
								/* Opções para as imagens */
								if ($page=="pictures") {
									echo "<li>";
									echo "	<a href='javascript:void(0)' onclick=\"askSendPictures(".getField("idalbum").")\"><span class=\"glyphicon glyphicon-plus\"></span> Enviar imagens</a>";
									echo "</li>";
								}
							}

							echo "<li>";
							echo "	<a href=\"./gallery.php\"><span class=\"glyphicon glyphicon-th\"></span> Álbuns</a>";
							echo "</li>";

							/* Opções do administrador */
							if (sessionField("useradmin")==USER_ADMIN) {
								echo "<li>";
								echo "	<a href=\"./config.php\"><span class=\"glyphicon glyphicon-cog\"></span> Configurações</a>";
								echo "</li>";
								echo "<li>";
								echo "	<a href=\"./users.php\"><span class=\"glyphicon glyphicon-eye-open\"></span> Usuários</a>";
								echo "</li>";
							}
						}
					?>
                    <li>
                        <a href="helpme.php"><span class="glyphicon glyphicon-question-sign"></span> Ajuda</a>
                    </li>
					<?php
						/*
							Esta checagem abaixo é para não mostrar o botão sair, sem estar logado.
						*/
						if (sessionField("usersession")!="") {
							echo "<li>";
							echo "	<a href=\"./logout.php\"><span class=\"glyphicon glyphicon-remove-sign\"></span> Sair</a>";
							echo "</li>";
						}
					?>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">