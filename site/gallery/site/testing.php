<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
</head><body>
<?php
	/**
		Página de testes do sistema
	*/

	require_once "mainlib.php"; /* Funções gerais, chamar em todo .php de conteúdo que for criado */

	/* Conexão com Banco de Dados */
	$dbConnection = new connMySQL(getDatabaseConfig());

	/* Ação a ser testada */
	$acao = getField("a","");

	/* Se teste de leitura do quem somos */
	if ($acao == "busca_quem") {
		$about = getAboutData($dbConnection);
		echo "id: " . $about["about_id"] . "<br />";
		echo "title: " . $about["about_title"] . "<br />";
		echo "text: " . $about["about_text"] . "<br />";
		echo "image: " . $about["about_image"];
	} elseif ($acao == "grava_quem") {
		setAboutData($dbConnection, "Titulo novo", "Texto novo", "imagem.jpg");
		echo "Feito";
	} elseif ($acao == "get_esp") {
		print_r(getSpecialists($dbConnection));
	} else if ($acao == "sel_spec") {
		$idPic = "1";
		$idSpecialists = array("2","3","4");
		setSpecialistsToItem($dbConnection, $idPic, $idSpecialists);
		echo "Done. Check the table.";
	} else if ($acao == "get_spec_pic") {
		$idPic = "6";
		print_r(getSpecialistsFromPic($dbConnection, $idPic));
	} else {
		echo "Ops!";
	}

	echo "<p>";
	echo "<a href='?a=busca_quem'>Busca Quem Somos</a><br />";
	echo "<a href='?a=grava_quem'>Grava Quem Somos</a><br />";
	echo "<a href='?a=get_esp'>Lista especialistas</a><br />";
	echo "<a href='?a=sel_spec'>Seleciona especialistas de uma foto</a><br />";
	echo "<a href='?a=get_spec_pic'>Busca especialistas de uma foto</a><br />";
	echo "</p>";

	/* Encerra conexão */
	$dbConnection->close();
?>
</body></html>