<?php
  include "smtp/ztmail.php";
  ini_set('display_errors', '0');

  $nome    = (isset($_GET["cnome"]))    ? $_GET["cnome"]    : "";
  $fone    = (isset($_GET["cfone"]))    ? $_GET["cfone"]    : "";
  $email   = (isset($_GET["cemail"]))   ? $_GET["cemail"]   : "";
  $msg     = (isset($_GET["cmsg"]))     ? $_GET["cmsg"]     : "";
  $produto = (isset($_GET["cproduto"])) ? $_GET["cproduto"] : "";
  $foto    = (isset($_GET["cfoto"]))    ? $_GET["cfoto"]    : "";

  if (($nome!="") && ($fone!="") && ($produto != "")) {
	  $body = "<p>Mensagem recebida automaticamente pelo site LaNaRo Convites:</p><p>" .
			  "<table border=1><tr><td><b>Produto:</b></td><td>" . $produto . "<br /><img width=640 src=\"http://lanaroconvites.com.br/gallery/site/photos/$foto\"></td></tr>" .
			  "<tr><td><b>Cliente:</b></td><td>" . $nome . "</td></tr>" .
			  "<tr><td><b>Telefone:</b></td><td>" . $fone . "</td></tr>" .
			  "<tr><td><b>Email:</b></td><td>" . $email . "</td></tr>" .
			  "<tr><td><b>Mensagem:</b></td><td>" . $msg . "</td></tr></table>";

	  if (ztmail($body, "Novo Contato")) {
		$retorno = "Mensagem enviada com sucesso.";
	  } else {
		$retorno = "Não foi possível enviar a mensagem.";
	  }
  } else {
	  $retorno = "Dados incompletos para envio.";
  }

  echo $retorno;
?>