<?php
	header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Basic Page Needs
    ================================================== -->
    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv="x-ua-compatible" content="IE=9" /><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lá na Rô Convites e Arte</title>
    <meta name="description" content="Lá na Rô Convites e Arte">
    <meta name="keywords" content="convites, arte, casamento, padrinhos, festas, eventos">
    <meta name="author" content="imaginesites.com.br">

    <!-- Favicons
    ================================================== -->
    <link rel="shortcut icon" href="./img/favicon.png" type="image/x-icon">
    <link rel="apple-touch-icon" href="./img/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="./img/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="./img/apple-touch-icon-114x114.png">

    <!-- Bootstrap -->
    <!--<link rel="stylesheet" type="text/css"  href="./css/bootstrap.css">-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./fonts/font-awesome/css/font-awesome.css">

    <!-- Stylesheet
    ================================================== -->
    <link rel="stylesheet" type="text/css" href="./css/style5.css">
    <link rel="stylesheet" type="text/css" href="./css/responsive.css">

    <script type="text/javascript" src="./js/modernizr.custom.js"></script>
	<script type="text/javascript" src="./js/jsinnerdoc.js"></script>

<!--    <link href='http://fonts.googleapis.com/css?family=Raleway:500,600,700,100,800,900,400,200,300' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Playball' rel='stylesheet' type='text/css'> -->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	<script>
		function iniciaEnvioPedido() {
			nome_produto  = $.trim($("#tit_produto").attr("descr"));
			foto_produto  = $.trim($("#tit_produto").attr("foto"));
			nome_cliente  = $.trim($("#nome").val());
			fone_cliente  = $.trim($("#fone").val());
			email_cliente = $.trim($("#email").val());
			msg_cliente   = $.trim($("#mensagem").val());

			if (nome_produto == "") {
				alert("Produto não selecionado.");
			} else if (nome_cliente == "") {
				alert("Informe seu nome por favor.");
				$("#nome").focus();
			} else if (fone_cliente == "") {
				alert("Informe seu telefone por favor.");
				$("#fone").focus();
			} else {
				/*console.log("Mostra loading");*/
				$("#div_botao").hide();
				$("#div_loading").show();

				loadDocument("xmailer.php?cfoto="+foto_produto+"&cproduto="+nome_produto+"&cnome="+nome_cliente+"&cfone="+fone_cliente+"&cemail="+email_cliente+"&cmsg="+msg_cliente, "div_loading");
			}
		}
		
		function scrollElemento(elemento) {
			setTimeout(function(){
				elTop = $("#"+elemento).offset().top;		
				tIdeal = elTop + 5;
				$('html, body').animate({
					scrollTop: tIdeal
				}, 2000);
			},100);
		}
	</script>
  </head>
  <body>
	<div id="tf-home">
		<div class="overlay">
			<div id="sticky-anchor"></div>
            <nav id="tf-menu" class="navbar navbar-default">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                      </button>
					  <!--<a class="navbar-brand" href="index.php"><img src="./img/logo5.png" alt="logo"></a>-->
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
							<?php
								if (basename($_SERVER["SCRIPT_FILENAME"], '.php') != "index") {
									echo "<div class='pull-left textos_topo'>
									<div style='padding-top:9px;padding-right:10px;text-align:center'>
									<img src='./img/minilogo.png' />
									</div></div>";
								}
							?>
						<div class="pull-left textos_topo">
							<div style="padding-top:5px;text-align:center">
								<i class="fa fa-phone-square" aria-hidden="true"></i> 19 3601.6781 <a href='https://api.whatsapp.com/send?phone=5519981487391&text=Ol%C3%A1,%20tudo%20bem?' target='_blank'><i class="fa fa-comments" aria-hidden="true"></i> 19 98148.7391</a><br />
								Av Cillos, 3641 - Pq Novo Mundo, Americana SP
							</div>
						</div>
						<ul class="nav navbar-nav navbar-right">
							<li><a href="./index.php">Início</a></li>
							<li><a href="./quemsomos.php">Quem Somos</a></li>
							<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Comemorações <i class="fa fa-angle-down"></i></a>
								<ul class="dropdown-menu">
									<li><a href="./galeria.php?album=Casamento">Casamento</a></li>
									<li><a href="./galeria.php?album=Corporativo">Corporativo</a></li>
									<li><a href="./galeria.php?album=Infantil">Infantil</a></li>
									<li><a href="./galeria.php?album=15 anos">15 anos</a></li>
									<li><a href="./galeria.php?album=Festas">Festas</a></li>
								</ul>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Dicas <i class="fa fa-angle-down"></i></a>
								<ul class="dropdown-menu">
									<li><a href="./frases.php">Frases</a></li>
									<li><a href="./parceiros.php">Parceiros</a></li>
									<li><a href="./dicas.php">Dicas</a></li>
									<li><a href="./brasoes.php">Brasões</a></li>
								</ul>
							</li>					
							<!--<li><a href="./loja.php">Loja</a></li>-->
							<li><a href="./contato.php">Contato</a></li>
							<!--<li><a href="https://www.facebook.com/L%C3%A1-na-R%C3%B4-Convites-e-Arte-121694601304667/" style="padding:0px" target="_blank"><img src="./img/ico_face.png" width=32 border="0" /></a></li>
							<li><a href="https://www.instagram.com/lanaroconvites/" style="padding:0px" target="_blank"><img src="./img/ico_insta.png" width=32 border="0" /></a></li>-->
						</ul>
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav>