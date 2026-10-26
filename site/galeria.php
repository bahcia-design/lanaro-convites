<?php
	if (isset($_GET["checkstatus"])) {
		die("online");
	}

	error_reporting(E_ALL);

	require_once "./gallery/site/mainlib.php";

	/* Verifica qual nome de álbum deve buscar */
	$albumName = getField("album","Casamento");
	/* Verifica qual subcategoria deve buscar */
	$categoryName = getField("cat");
	/* Cria conexão com banco de dados */
	$dbConnection = new connMySQL(getDatabaseConfig());
	/* Busca o ID do álbum */
	$idAlbum = searchTableID($dbConnection, "gallery_albuns", array("album_name='" . $albumName . "'"), "album_id");
	/* Se localizou */
	if ($idAlbum) {
		/* Busca os dados do álbum */
		$albumData = getAlbumData($dbConnection, $idAlbum);
		/* Busca as fotos do álbum */
		$albumPictures = getAlbumPictures($dbConnection, $idAlbum, $categoryName);
		/* Busca as categorias do álbum */
		$albumCategories = getCategoryNames($dbConnection, $idAlbum);
	}
	/* Encerra conexão */
	$dbConnection->close();

	include "head.php";

	$sliders = 0;
	$mostraSlider = "none";
	$pastaSlider = "";

	if ($albumName=="Casamento") {
		$sliders = 3;
		$mostraSlider = "block";
		$pastaSlider = "casamento";
	} else if ($albumName=="15 anos") {
		$sliders = 3;
		$mostraSlider = "block";
		$pastaSlider = "15anos";
	} else if ($albumName=="Corporativo") {
		$sliders = 2;
		$mostraSlider = "block";
		$pastaSlider = "corporativo";
	} else if ($albumName=="Infantil") {
		$sliders = 4;
		$mostraSlider = "block";
		$pastaSlider = "infantil";
	} else if ($albumName=="Festas") {
		$sliders = 2;
		$mostraSlider = "block";
		$pastaSlider = "festas";
	}
?>
            <div class="container">
				<div style="display:<?php echo $mostraSlider; ?>">
					<div id="myCarousel" class="carousel slide" data-ride="carousel">
					  <!-- Indicators -->
					  <ol class="carousel-indicators">
						<?php
							for ($c=1;$c<=$sliders;$c++) {
								$active = ($c==1) ? "active" : "";
								echo "<li data-target='#myCarousel' data-slide-to='" . ($c - 1) . "' class='$active'></li>";
							}
						?>
					  </ol>

					  <div class="carousel-inner" role="listbox">
					  <?php
						/* Imagens dos sliders */
						if ($sliders) {
							for ($c=1;$c<=$sliders;$c++) {
								$active = ($c==1) ? "active" : "";

								echo "<div class='item $active'>
										  <img src='./img/sliders/$pastaSlider/slider$c.jpg' alt=''>
										  <div class='carousel-caption'>
											<!--<h3>Casamento</h3>
											<p>The atmosphere in Chania has a touch of Florence and Venice.</p>-->
										  </div>
									  </div>";
							}
						}
					  ?>
					  </div>

					  <!-- Left and right controls -->
					  <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
						<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
						<span class="sr-only">Anterior</span>
					  </a>
					  <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
						<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
						<span class="sr-only">Próximo</span>
					  </a>
					</div>							
				</div>

                <div class="content">
					<?php
						if ($idAlbum == 0) {
							echo "<h1>Álbum não encontrado.</h1>";
						} else {
							echo "<h1>" . $albumData["album_name"] . "</h1>";
							echo "<div style='padding-bottom:20px'>";
							$sep = "";
							foreach ($albumCategories as $cat) {
								echo $sep . "<a href='./galeria.php?album=" . $albumName . "&cat=" . $cat . "'>" . $cat . "</a>";
								$sep = "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;";
							}
							echo "</div>";
							
							if (sizeof($albumPictures)) {
								$feitos = 0;
								foreach($albumPictures as $pic) {
									if ($feitos == 0)
										echo "<div class='row'>";

									echo "	<div class='col-xs-6 col-sm-3 col-md-3 margemabaixo'>";
									echo "		<a href='./detalhe.php?id=" . $pic["pic_id"] . "'>";
									echo "			<img src='./gallery/site/photos/thumb/" . $pic["pic_filename"] . "' class='img-responsive img-thumbnail' /><br />" . $pic["pic_name"];
									echo "		</a>";
									echo "	</div>";
									
									$feitos++;
									if ($feitos > 3) {
										$feitos = 0;
										echo "</div><div class='row'>";
									}
								}
								
								if ($feitos > 0)
									echo "</div>";
							} else {
								echo "	<div class='col-xs-6 col-sm-3 col-md-3 margemabaixo'>";
								echo "		Nenhum produto encontrado.";
								echo "	</div>";
							}
							echo "</div>";
						}
					?>

				</div>
            </div>
        </div>
    </div>

<?php
	include "foot.php";
?>