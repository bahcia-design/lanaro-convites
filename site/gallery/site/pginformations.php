<?php
	if (!isset($infoID))
		$infoID = "";
		
	$title   = "";
	$content = "";
		
	if ($infoID=="nodatabase") {
		$title   = "Banco de dados não configurado";
		$content = "<div class='central'><img src='./img/remove-from-database.png' /><br />Parece que o Banco de Dados ainda não foi configurado. Você pode fazer isso editando 
		            o arquivo <b>dbconf.php</b> na raíz desta pasta.<br />&nbsp;<br />Se você não faz idéia do que é isso, contate o administrador ou desenvolvedor do site.</div>
					<div class='central'><a href='./index.php' class='btn btn-primary'><span class='glyphicon glyphicon-repeat'></span> Tentar novamente</a></div>";
	} else if ($infoID=="fail") {
		$title   = "Conexão com Banco de Dados falhou";
		$content = "<div class='central margemabaixo'><img src='./img/remove-from-database.png' /><br />A conexão com o banco de dados falhou. Você pode checar as configurações 
		no arquivo <b>dbconf.php</b> na raíz desta pasta.<br />&nbsp;<br />Se você não faz idéia do que é isso, contate o administrador ou desenvolvedor do site.</div>
		<div class='alert alert-danger'>Código de Erro: <b>" . getField("failid") . "</b> [<a href='https://www.google.com.br/search?q=mysql%20error%20" . getField("failid") . "' target='_blank' />pesquisar no Google</a>]</div>
		<div class='central'><a href='./index.php' class='btn btn-primary'><span class='glyphicon glyphicon-repeat'></span> Tentar novamente</a></div>";
	} else if ($infoID=="tables") {
		$title   = "Há algum problema com suas Tabelas";
		$content = "<div class='central margemabaixo'><img src='./img/generate-tables.png' /><br />Não conseguimos preparar as tabelas iniciais do sistema, pode ser algum problema técnico ou de permissões.<br />
 		            Se você não faz idéia do que é isso, contate o administrador ou desenvolvedor do site.</div>
					<div class='central'><a href='./index.php' class='btn btn-primary'><span class='glyphicon glyphicon-repeat'></span> Tentar novamente</a></div>";
	} else if ($infoID=="logout") {
		$title   = "Ops! Algo deu errado em sua saída";
		$content = "<div class='central margemabaixo'><img src='./img/remove-from-database.png' /><br />Não conseguimos efetuar o logout.<br />
 		            " . getField("error") . ".</div>
					<div class='central'><a href='./logout.php' class='btn btn-primary'><span class='glyphicon glyphicon-repeat'></span> Tentar novamente</a></div>";
	} else if ($infoID=="ready") {
		$title   = "Parabéns, tudo pronto!";
		$content = "<div class='central margemabaixo'><img src='./img/check-ready.png' /><br />O sistema <b>gallery</b> está pronto para ser acessado e utilizado.<br />
		            Por padrão, a primeira senha de acesso do administrador é <i><b>gallery</b></i>, e do editor é <i><b>editor</b></i> mesmo ok? Bom trabalho.</div>
					<div class='central'><a href='./index.php' class='btn btn-primary'><span class='glyphicon glyphicon-ok'></span> Começar agora</a></div>";
	}
?>
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><?php echo $title; ?></h1>
            </div>
		</div>
        <div class="row">
            <div class="col-lg-12">
				<?php echo $content; ?>
            </div>
		</div>