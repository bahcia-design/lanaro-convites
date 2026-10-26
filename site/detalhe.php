<?php
	if (isset($_GET["checkstatus"])) {
		die("online");
	}
	
	error_reporting(E_ALL);
	
	require_once "./gallery/site/mainlib.php";
	
	/* Cria conexão com banco de dados */
	$dbConnection = new connMySQL(getDatabaseConfig());
	/* ID do produto */
	$idPicture = getField("id","0");
	/* Obtém os dados do produto */
	$pictureData = getPictureData($dbConnection, $idPicture);
	/* Busca os dados de como Encomendar */
	$howData = getHowData($dbConnection);
	/* Encerra conexão */
	$dbConnection->close();

	include "head.php";
?>
            <div class="container">
                <div class="content">
					<div class="row">
						<div class="col-xs-12 col-sm-6">
							<img src="./gallery/site/photos/<?php echo $pictureData["pic_filename"]; ?>" class="img-responsive img-thumbnail" />
						</div>
						<div class="col-xs-12 col-sm-6 esquerda">
							<h1 id="tit_produto" foto="<?php echo $pictureData["pic_filename"]; ?>" descr="<?php echo $pictureData["pic_name"]; ?>"><?php echo $pictureData["pic_name"]; ?></h1>
							<hr />
							<p>
								<?php
									$desc = str_replace("\n","<br />",$pictureData["pic_description"]);
									echo $desc;
								?>
							</p>
							<div>
								<ul class="nav nav-tabs">
								  <li class="active"><a data-toggle="tab" href="#home"><?php echo $howData["how_title"]; ?></a></li>
								  <li><a data-toggle="tab" href="#menu1">Tenho interesse!</a></li>
								</ul>
								<div class="tab-content">
								  <div id="home" class="tab-pane fade in active">
									<?php
										echo "<h3>" . $howData["how_title"] . "</h3>";
									
										$how = str_replace("\n","<br />",$howData["how_text"]);
										echo "<p>" . $how . "</p>";
									?>
								  </div>
								  <div id="menu1" class="tab-pane fade">
									<h3>Interesse</h3>
									<div class="form-group">
										<label for="nome">Nome *</label>
										<input type="text" class="form-control" id="nome">
									</div>
									<div class="form-group">
										<label for="fone">Telefone *</label>
										<input type="text" class="form-control" id="fone">
									</div>
									<div class="form-group">
										<label for="email">Email</label>
										<input type="email" class="form-control" id="email">
									</div>
									<div class="form-group">
										<label for="mensagem">Alguma observação ou dúvida?</label>
										<textarea class="form-control" id="mensagem"></textarea>
									</div>
									<div id="div_botao">
										<button onclick="iniciaEnvioPedido()" type="button" class="btn btn-default"><i class="fa fa-paper-plane" aria-hidden="true"></i> Enviar</button>
									</div>
									<div id="div_loading" style="display:none">
										<img src="./img/load1.gif" /> Enviando...
									</div>
								  </div>
								</div>
							</div>
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>

<?php
	include "foot.php";
?>