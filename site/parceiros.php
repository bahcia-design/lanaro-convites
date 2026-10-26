<?php
	if (isset($_GET["checkstatus"])) {
		die("online");
	}

	error_reporting(E_ALL);

	require_once "./gallery/site/mainlib.php";

	$albumName = "Parceiros";
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
?>
            <div class="container">

                <div class="content">
					<?php
						if ($idAlbum == 0) {
							echo "<h1>Álbum não encontrado.</h1>";
						} else {
							echo "<h1>" . $albumData["album_name"] . "</h1>";
							echo "<div style='padding-bottom:20px'>";
							$sep = "";
							foreach ($albumCategories as $cat) {
								echo $sep . "<a href='./parceiros.php?cat=" . $cat . "'>" . $cat . "</a>";
								$sep = "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;";
							}
							echo "</div>";
							echo "<div class='row'>";
							if (sizeof($albumPictures)) {
								foreach($albumPictures as $pic) {
									$desc = str_replace("\n","<br />",$pic["pic_description"]);
									
									echo "	<div class='col-xs-12 col-sm-6 col-md-6 margemabaixo'>";
									echo "		<div class='row'>";
									echo "			<div class='col-xs-4'>";
									echo "				<img src='./gallery/site/photos/thumb/" . $pic["pic_filename"] . "' class='img-responsive img-circle img-thumbnail' />";
									echo "			</div>";
									echo "			<div class='col-xs-8' style='text-align:left'>";
									echo "				<h3>" . $pic["pic_name"] . "</h3>";
									echo "				<label>&nbsp;<br />" . $desc . "</label>";
									echo "			</div>";
									echo "		</div>";
									echo "	</div>";
								}
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