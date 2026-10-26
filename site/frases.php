<?php
	header('Content-Type: text/html; charset=utf-8');

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
				$frasesData = getFrasesData($dbConnection);
				/* Encerra conexão */
				$dbConnection->close();
				
				?>
			
                <h3><?php echo $frasesData["frases_title"]; ?></h3>
                <!--<hr>
				<div class="row">
					<div class="col-xs-1 col-sm-2"></div>
					<div class="col-xs-10 col-sm-8">
						<img src="./gallery/site/img/frases/<?php echo $frasesData["frases_image"]; ?>" class="img-responsive img-thumbnail" />
					</div>
					<div class="col-xs-1 col-sm-2"></div>
				</div>-->
				<hr>
				<p>
					<?php
						$text = str_replace("\n","<br />",$frasesData["frases_text"]);
						echo $text;
					?>
				</p>
            </div>
        </div>
    </div>
	
				
<?php
	include "foot.php";
?>