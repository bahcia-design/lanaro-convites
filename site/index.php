<?php
	if (isset($_GET["checkstatus"])) {
		die("online");
	}

	$date = date("Ymd");
	if ($date >= "20190103") {
		header('Location:http://www.lanaroconvites.com.br/index.html');
		die();
	}

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
		function contaTempo() {
			setTimeout(function(){
				window.location='./home.php';
			},5000);
		}
	</script>
	
  </head>
  <body onload="contaTempo()">
	<div id="div-recado" style="text-align:center">
		<img src="./img/recado_640.jpg" class="img-responsive" />
		<p>
			<br />&nbsp;<br />
			<a href="./home.php">Continuar para o site</a>
		</p>
	</div>
  </body>
</html>