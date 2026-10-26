<?php
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
			
                <h3>Brasões</h3>
                <hr>
				<div class="row">
					<div class="col-xs-0"></div>
					<div class="col-xs-12">
						<img src="./img/brasoes001.jpg" class="img-responsive img-thumbnail" />
					</div>
					<div class="col-xs-0"></div>
				</div>
				<hr>
				<p>
					
				</p>
            </div>
        </div>
    </div>
	
				
<?php
	include "foot.php";
?>