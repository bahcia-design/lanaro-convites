<?php
	//@header("Content-Type: text/html; charset=UTF-8");

	function salvaFotoEnvio($arquivo, $pasta) {
		$res = "";
		if (isset($arquivo["name"])&&($arquivo["name"]!="")) { 
			// Pega extensão do arquivo
			if (!preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $arquivo["name"], $ext))
				$ext = array(".jpg");

			// Gera um nome único para a imagem
			$res = md5(uniqid(time())) . strtolower($ext[0]);

			// Caminho de onde a imagem ficará
			$pasta = getcwd() . "/" . $pasta . "/";
			$imagem_dir = $pasta . $res;

			// Faz o upload da imagem
			move_uploaded_file($arquivo["tmp_name"], $imagem_dir);
		}
		return $res;
	}

	$pasta = "../img/about";

	if (isset($_FILES["about_pic"])) {
		$f = salvaFotoEnvio($_FILES["about_pic"], $pasta);
		if ($f=="")
			die("erro:Não foi possível salvar o arquivo");
		else
			die("success:".$f);
	} else
		die("erro:Arquivo não informado");
?>