<?php
	if (isset($_GET["checkstatus"])) {
		die("online");
	}
	
	error_reporting(E_ALL);

	require_once "./gallery/site/mainlib.php";
	
	include "head.php";
?>
            <div class="container">
                <div class="content" style="padding-top:0px">
					<div class="row">
						<div class="col-xs-1 col-sm-4"></div>
						<div class="col-xs-10 col-sm-4" style="padding-bottom:30px;"><img src="./img/logo3.png" class="img-responsive" /></div>
						<div class="col-xs-1 col-sm-4"></div>
					</div>
				
						<div style="padding-bottom:30px;display:none">
							<div id="myCarousel" class="carousel slide" data-ride="carousel">
							  <!-- Indicators -->
							  <ol class="carousel-indicators">
								<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
								<li data-target="#myCarousel" data-slide-to="1"></li>
							  </ol>

							  <!-- Wrapper for slides -->
							  <div class="carousel-inner" role="listbox">
								<div class="item active">
								  <img src="./img/sliders/slider1.jpg" alt="Chania">
								  <div class="carousel-caption">
									<!--<h3>Chania</h3>
									<p>The atmosphere in Chania has a touch of Florence and Venice.</p>-->
								  </div>
								</div>

								<div class="item">
								  <img src="./img/sliders/slider2.jpg" alt="Chania">
								  <div class="carousel-caption">
									<!--<h3>Chania</h3>
									<p>The atmosphere in Chania has a touch of Florence and Venice.</p>-->
								  </div>
								</div>

							  </div>

							  <!-- Left and right controls -->
							  <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
								<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
								<span class="sr-only">Previous</span>
							  </a>
							  <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
								<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
								<span class="sr-only">Next</span>
							  </a>
							</div>							
						</div>
				
					<!--<div class="row">
						<div class="col-xs-1 col-sm-1 col-md-4 col-lg-4"></div>
						<div class="col-xs-10 col-sm-10 col-md-4 col-lg-4" style="padding-bottom:30px">
							<img src="./img/logo4.png" class="img-responsive" />
						</div>
						<div class="col-xs-1 col-sm-1 col-md-4 col-lg-4"></div>
					</div>-->
				
					
					<div class="row">
					    <div class="col-sm-2 col-md-2"></div>
						<div class="col-xs-6 col-sm-2 col-md-2 margemabaixo">
							<a href="./galeria.php?album=Casamento">
								<img src="./img/ico_casamento1.png" class="img-responsive iconeshome" />
							</a>
						</div>
						<div class="col-xs-6 col-sm-2 col-md-2 margemabaixo">
							<a href="./galeria.php?album=Corporativo">
								<img src="./img/ico_corporativo1.png" class="img-responsive iconeshome" />
							</a>
						</div>
						<div class="col-xs-6 col-sm-2 col-md-2 margemabaixo">
							<a href="./galeria.php?album=Infantil">
								<img src="./img/ico_infantil1.png" class="img-responsive iconeshome" />
							</a>
						</div>
						<div class="col-xs-6 col-sm-2 col-md-2 margemabaixo">
							<a href="./galeria.php?album=15 anos">
								<img src="./img/ico_15anos1.png" class="img-responsive iconeshome" />
							</a>
						</div>
						<div class="col-sm-2 col-md-2"></div>
					</div><div class="row">	
						<div class="col-sm-2 col-md-2"></div>
						<div class="col-xs-6 col-sm-2 col-md-2 margemabaixo">
							<a href="./galeria.php?album=Festas">
								<img src="./img/ico_festas1.png" class="img-responsive iconeshome" />
							</a>
						</div>
						<div class="col-xs-6 col-sm-2 col-md-2 margemabaixo">
							<a href="#" data-toggle="dropdown">
								<img onclick="scrollElemento('menudicas')" src="./img/ico_dicas1.png" class="img-responsive iconeshome" />
								<ul id='menudicas' class="dropdown-menu">
									<li><a href="./frases.php">Frases</a></li>
									<li><a href="./parceiros.php">Parceiros</a></li>
									<li><a href="./dicas.php">Dicas</a></li>
									<li><a href="./brasoes.php">Brasões</a></li>
								</ul>
							</a>
						</div>
						<div class="col-xs-6 col-sm-2 col-md-2 margemabaixo">
							<a href="./quemsomos.php">
								<img src="./img/ico_quem2.png" class="img-responsive iconeshome" />
							</a>
						</div>
						<div class="col-xs-6 col-sm-2 col-md-2 margemabaixo">
							<a href="./galeria.php?album=Loja%20Virtual">
								<img src="./img/ico_loja2.png" class="img-responsive iconeshome" />
							</a>
						</div>
						<div class="col-sm-2 col-md-2"></div>
					</div>
				</div>
            </div>
				
<?php
	include "foot.php";
?>