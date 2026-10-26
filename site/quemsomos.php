<?php
	if (isset($_GET["checkstatus"])) {
		die("online");
	}
	
	error_reporting(E_ALL);

	require_once "./gallery/site/mainlib.php";
	
	include "head.php";
?>

    <div id="tf-about">
        <div class="container">
            <div class="section-title">
				<?php

					/* Conexão com Banco de Dados */
					$dbConnection = new connMySQL(getDatabaseConfig());
					/* Busca os dados da sessão de Quem Somos */
					$aboutData = getAboutData($dbConnection);
					/* Encerra conexão */
					$dbConnection->close();
				
				?>

                <h3><?php echo $aboutData["about_title"]; ?></h3>
                <!--<hr>
				<div class="row">
					<div class="col-xs-1 col-sm-2"></div>
					<div class="col-xs-10 col-sm-8">
						<img src="./gallery/site/img/about/<?php echo $aboutData["about_image"]; ?>" class="img-responsive img-thumbnail" />
					</div>
					<div class="col-xs-1 col-sm-2"></div>
				</div>-->
				<hr>
				<p>
					<?php
						$text = str_replace("\n","<br />",$aboutData["about_text"]);
						echo $text;
					?>
				</p>
            </div>
        </div>
    </div>
	
				
<?php
	include "foot.php";
?>